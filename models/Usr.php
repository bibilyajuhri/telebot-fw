<?php 

namespace models;

use apps\models\Model;

class Usr extends Model{

	function __construct(){
		parent::__construct();
	}
	
	static function getDb(){
		return 'main';
	}

	static function tableName(){
		return 'usr';
	}

	function findOrCreate(){
		$new = false;
		find:
		$find = self::find()->andWhere(['user_id'=> (string)$this->user_id])->one();
		if(!$find){
			$find = new self();
			$find->user_id = (string)$this->user_id;
			$find->created_at = \common\Config::now();
			$find->save();
			goto find;
		}
		if($find->is_banned){
			return false;
		}
		return $find;
	}
}