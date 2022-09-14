<?php 

namespace api\v1\controllers;

use apps\controllers\ApiController;
use models\Usr;
use models\QueueLike;
use models\QueueLikecomment;

class QueueController extends ApiController{

	function actionAddlicom(){
		if($this->rawData){
			$usr = new Usr;
			$usr->user_id = $this->rawData->user_id;
			$usr = $usr->findOrCreate();
			if($usr === false){
				return[
					'status'=> false,
					'message'=> 'null'
				];
			}
			if($usr->likecomment_point <= 0){
				return[
					'status'=> false,
					'message'=> 'poin anda habis, silahkan submit lagi pada jam berikutnya!'
				];
			}

			$queue = new QueueLikecomment();
			$queue->comment_id = $this->rawData->comment_id;
			$queue->username = $this->rawData->username;
			$queue->total = $this->rawData->total;
			$queue->usr_id = $usr->id;

			if($queue->createOrUpdate()){
				$usr->likecomment_point -= 1;
				$usr->update();
				return[
					'status'=> true
				];
			}
		}
		return[
			'status'=> false,
			'message'=> 'payload tidak valid!'
		];
	}

	function actionAddlike(){
		if($this->rawData){
			$usr = new Usr;
			$usr->user_id = $this->rawData->user_id;
			$usr = $usr->findOrCreate();
			if($usr === false){
				return[
					'status'=> false,
					'message'=> 'null'
				];
			}
			if($usr->like_point <= 0){
				return[
					'status'=> false,
					'message'=> 'poin anda habis, silahkan submit lagi pada jam berikutnya!'
				];
			}

			$queue = new QueueLike();
			$queue->post_id = $this->rawData->post_id;
			$queue->username = $this->rawData->username;
			$queue->total = $this->rawData->total;
			$queue->usr_id = $usr->id;

			if($queue->createOrUpdate()){
				$usr->like_point -= 1;
				$usr->update();
				return[
					'status'=> true
				];
			}
		}
		return[
			'status'=> false,
			'message'=> 'payload tidak valid!'
		];
	}
}