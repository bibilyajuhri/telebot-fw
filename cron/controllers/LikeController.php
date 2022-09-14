<?php 

namespace cron\controllers;

use apps\controllers\CronController;
use components\InstagramV2;
use components\BTele;
use models\QueueLike;
use models\Acc;
use models\HistoryAction;

class LikeController extends CronController{

	private $limitQueue = 2;
	private $limitAcc = 50;

	function actionIndex(){
		$model = QueueLike::find()
			->andWhere(['done'=> false])
			// ->andWhere('running < total')
			->limit($this->limitQueue)
			->all();
		if(!$model){
			echo "tidak ada proses";
			return false;
		}
		$instagram = new InstagramV2();
		$msgAll = '';
		$isAda = false;
		$bTele = new BTele();
		foreach ($model as $key => $value) {
			$msg = "✅ cron like\n";
			$history = new HistoryAction();
			$col = ['action', 'ref_id', 'trans_id', 'acc_id', 'response', 'created_at', 'full_response'];
			$val = [];
			if(!$value->sent_at){
				$value->sent_at = \common\Config::now();
			}

			$limit = $value->total - $value->running >= $this->limitAcc ? $this->limitAcc : $value->total - $value->running;
			if(!$limit){
				$value->done = true;
				$value->update();
				continue;
			}
			$acc = Acc::find()
				->where('not exists(select 1 from history_action where acc.id = acc_id and action = \''.HistoryAction::ACTION_LIKE.'\' and trans_id = \''.$value->post_id.'\')')
				->andWhere(['status'=> true])
				->andWhere(['can_like'=> true])
				->limit($limit)
				->all();
			if(!$acc){
				$value->done = true;
				$value->update();
				continue;
			}
			$msg .= "\n{$value->username} ({$value->post_id})\n";
			foreach ($acc as $keyAcc => $valueAcc) {
				$isAda = true;
				$instagram->setHeader([$valueAcc->ig_claim, $valueAcc->csrf]);
				$instagram->setHeaderCookie([$valueAcc->cookie]);
				$action = 'post';
				$type = 'like';
				$executed = $instagram->post('like', $value->post_id);
				$executed = json_decode($executed);
				$response = null;
				if($executed === null){
					$response = 'executed';
				}else{
					$response = $executed->status;
				}
				if($response != 'ok'){
					$msg .= ". {$valueAcc->username} => {$action} {$type} : {$response}\n";
				}
				$val[] = [
					HistoryAction::ACTION_LIKE, 
					$value->id, 
					$value->post_id, 
					$valueAcc->id, 
					$response, 
					\common\Config::now(),
					json_encode($executed)
				];
			}
			$msg .= "\n";
			$history->saveBulk($col, $val);
			$countAcc = count($acc);
			$value->running = $value->running + $countAcc;
			if($value->running >= $value->total || $countAcc < $this->limitAcc){
				$value->done = true;
			}
			$value->update();
			$bTele->setChatId($value->username);
			$bTele->setText(urlencode($msg))->sendMessage();
			$msgAll .= $msg;
		}
		echo $isAda ? "proses berhasil dieksekusi" : "tidak ada proses dieksekusi";
		if($isAda){
			$bTele->setChatId(\common\Config::get()['developerChatId']);
			$bTele->setText(urlencode($msgAll))->sendMessage();
		}
		return true;
	}
}