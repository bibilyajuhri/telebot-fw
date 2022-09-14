<?php 

namespace models;

use apps\models\Model;

class Settings extends Model{

	function __construct(){
		parent::__construct();
	}
	
	static function getDb(){
		return 'main';
	}

	static function tableName(){
		return 'settings';
	}

	static function findModule($code){
		return self::find()->where(['code'=> $code])->andWhere(['is_active'=> true])->one();
	}

	static function loadAllActiveModule(){
		return self::find()->where(['is_active'=> true])->all();
	}

	static function loadAllModule(){
		return self::find()->all();
	}

	static function getValueByCodeAllModule($arrData, $code){
		foreach ($arrData as $key => $value) {
			if($value->code == $code){
				return $value->value;
			}
		}
		return null;
	}

}