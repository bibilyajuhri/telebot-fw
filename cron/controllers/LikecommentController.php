<?php 

namespace cron\controllers;

use apps\controllers\CronController;
use components\InstagramV2;
use components\BTele;
use models\QueueLikecomment;
use models\Acc;
use models\HistoryAction;

class LikecommentController extends CronController{

	private $limitQueue = 2;
	private $limitAcc = 50;

	function actionIndex(){
		$model = QueueLikecomment::find()
			->andWhere(['done'=> false])
			// ->andWhere('running < total')
			->limit($this->limitQueue)
			->all();
		if(!$model){
			echo "tidak ada proses";
			return false;
		}
		$instagram = new InstagramV2();
		$msg = "✅ cron like comment\n";
		$isAda = false;
		foreach ($model as $key => $value) {
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
				->where('not exists(select 1 from history_action where acc.id = acc_id and action = \''.HistoryAction::ACTION_COMMENTLIKE.'\' and trans_id = \''.$value->comment_id.'\')')
				->andWhere(['status'=> true])
				->andWhere(['can_licom'=> true])
				->limit($limit)
				->all();
			if(!$acc){
				$value->done = true;
				$value->update();
				continue;
			}
			$msg .= "\n{$value->username} ({$value->comment_id})\n";
			$okCount = 0;
			foreach ($acc as $keyAcc => $valueAcc) {
				$isAda = true;
				$instagram->setHeader([$valueAcc->ig_claim, $valueAcc->csrf]);
				$instagram->setHeaderCookie([$valueAcc->cookie]);
				$action = 'comment';
				$type = 'like';
				$executed = $instagram->comment('like', $value->comment_id);
				$executed = json_decode($executed);
				$response = null;
				if($executed === null){
					$response = 'executed';
				}else{
					$response = $executed->status;
				}
				if($response == 'ok'){
					$okCount++;
				}else{
					$msg .= ". {$valueAcc->username} => {$action} {$type} : {$response}\n";
				}
				$val[] = [
					HistoryAction::ACTION_COMMENTLIKE, 
					$value->id, 
					$value->comment_id, 
					$valueAcc->id, 
					$response, 
					\common\Config::now(),
					json_encode($executed)
				];
			}
			$msg .= "success: {$okCount}\n";
			$history->saveBulk($col, $val);
			$countAcc = count($acc);
			$value->running = $value->running + $countAcc;
			if($value->running >= $value->total || $countAcc < $this->limitAcc){
				$value->done = true;
			}
			$value->update();
		}
		echo $isAda ? "proses berhasil dieksekusi" : "tidak ada proses dieksekusi";
		if($isAda){
			$bTele = new BTele();
			$bTele->setChatId(\common\Config::get()['developerChatId']);
			$bTele->setText(urlencode($msg))->sendMessage();
		}
		return true;
	}

	function actionTes(){
		$history = HistoryAction::findOne(11030);
		die(var_dump($history->acc()));
		return true;
	}
}