<?php 

namespace models;

use apps\models\Model;

class SessionLastpostid extends Model{

	function __construct(){
		parent::__construct();
	}
	
	static function getDb(){
		return 'main';
	}

	static function tableName(){
		return 'session_lastpostid';
	}

	function createOrUpdate(){
		$isNew = false;
		$self = self::find()->where(['username'=> $this->username])->one();
		if(!$self){
			$isNew = true;
			$self = new self();
		}
		$self->username = $this->username;
		$self->post_code = $this->post_code;
		$self->updated_at = \common\Config::now();
		$save = $isNew ? $self->save() : $self->update();
		return $save;
	}
}