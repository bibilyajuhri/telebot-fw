<?php 

namespace models;

use apps\models\Model;

class QueueLikecomment extends Model{

	function __construct(){
		parent::__construct();
	}
	
	static function getDb(){
		return 'main';
	}

	static function tableName(){
		return 'queue_likecomment';
	}

	function createOrUpdate(){
		$new = false;
		$queue = self::find()->andWhere(['comment_id'=> (string)$this->comment_id])->orderBy('id desc')->one();
		if(!$queue){
			$new = true;
			$queue = new self();
			$queue->total = 0;
		}
		$queue->comment_id = $this->comment_id;
		$queue->username = $this->username;
		$queue->total = (int)$queue->total + (int)$this->total;
		$queue->done = false;
		$queue->created_at = \common\Config::now();
		$queue->sent_at = null;
		$queue->usr_id = $this->usr_id;
		$save = $new ? $queue->save() : $queue->update();
		return $save;
	}
}