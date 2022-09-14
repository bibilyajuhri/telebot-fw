<?php

namespace cron\controllers;

use apps\controllers\CronController;
use models\QueueLikecomment;
use models\QueueLike;
use models\HistoryAction;

class ClearhistoryController extends CronController{

	// public function actionIndex(){
	// 	$now = date('Y-m-d');
	// 	$listLicom = QueueLikecomment::find()->where(['done'=> true])
	// 				->andWhere("date('{$now}') >= (date(created_at) + INTERVAL '2days')")
	// 				->all();
	// 	$listLike = QueueLike::find()->where(['done'=> true])
	// 				->andWhere("date('{$now}') >= (date(created_at) + INTERVAL '2days')")
	// 				->all();
	// 	$list = array_merge($listLicom ?: [], $listLike ?: []);
	// 	foreach ($list as $key => $value) {
	// 		if($getAction = $this->getAction($value)){
	// 			$trans_id = $value->{$getAction['param']};
	// 			$action = $getAction['action'];
	// 			$history = new HistoryAction;
	// 			$delete = $history->deleteBulk("ref_id = '{$value->id}' and trans_id = '{$trans_id}' and action = '{$action}'");
	// 			$value->delete();
	// 		}
	// 	}	
	// }

	// private function getAction($instance){
	// 	if($instance instanceof QueueLikecomment){
	// 		return ['action'=> HistoryAction::ACTION_COMMENTLIKE, 'param'=> 'comment_id'];
	// 	}
	// 	if($instance instanceof QueueLike){
	// 		return ['action'=> HistoryAction::ACTION_LIKE, 'param'=> 'post_id'];
	// 	}
	// 	return null;
	// }

	public function actionIndex(){
		$queueLike = new QueueLike;
		$queueLikecomment = new QueueLikecomment;
		$history = new HistoryAction;
		$bTele = new \components\BTele();
		$bTele->setChatId(645364384);
		$now = date('Y-m-d', strtotime(\common\Config::now()));

		$dl = $queueLike->deleteBulk("(done = true) and (date('{$now}') >= (date(created_at) + INTERVAL '2days'))");
		$dlc = $queueLikecomment->deleteBulk("(done = true) and (date('{$now}') >= (date(created_at) + INTERVAL '2days'))");
		$dh = $history->deleteBulk("(date('{$now}') >= (date(created_at) + INTERVAL '2days'))");
		$msg = "✅ cron clear history\n";
		$msg .= "queue like: {$dl}\n";
		$msg .= "queue like comment: {$dlc}\n";
		$msg .= "history action: {$dh}\n";
		$bTele->setText(urlencode($msg))->sendMessage();
		return true;
	}
}