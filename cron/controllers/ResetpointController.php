<?php

namespace cron\controllers;

use apps\controllers\CronController;
use models\Usr;
use models\Settings;

class ResetpointController extends CronController{

	private $defaultAllPoint = 3;

	public function actionIndex(){
		$bTele = new \components\BTele();
		$bTele->setChatId(645364384);

		$usr = new Usr;
		
		$pointlike = POINT_LIKE;
		$pointlike = $pointlike ?: $this->defaultAllPoint;
		
		$pointlike_vip = POINT_LIKE_VIP;
		$pointlike_vip = $pointlike_vip ?: $this->defaultAllPoint;
		
		$pointlikecomment = POINT_LIKECOMMENT;
		$pointlikecomment = $pointlikecomment ?: $this->defaultAllPoint;
		
		$pointlikecomment_vip = POINT_LIKECOMMENT_VIP;
		$pointlikecomment_vip = $pointlikecomment_vip ?: $this->defaultAllPoint;

		$like = $usr->updateBulk(['like_point'=> (int)$pointlike], "is_banned = false and is_vip = false and like_point < {$pointlike}");
		$like_vip = $usr->updateBulk(['like_point'=> (int)$pointlike_vip], "is_banned = false and is_vip = true and like_point < {$pointlike_vip}");
		$likecomment = $usr->updateBulk(['likecomment_point'=> (int)$pointlikecomment], "is_banned = false and is_vip = false and likecomment_point < {$pointlikecomment}");
		$likecomment_vip = $usr->updateBulk(['likecomment_point'=> (int)$pointlikecomment_vip], "is_banned = false and is_vip = true and likecomment_point < {$pointlikecomment_vip}");

		$msg = "✅ cron reset point\n";
		$msg .= "like: {$like}\n";
		$msg .= "like vip: {$like_vip}\n";
		$msg .= "like comment: {$likecomment}\n";
		$msg .= "like comment vip: {$likecomment_vip}\n";
		$bTele->setText(urlencode($msg))->sendMessage();
		return true;
	}
}