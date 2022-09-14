<?php 

namespace models;

use apps\models\Model;

class Acc extends Model{

	function __construct(){
		parent::__construct();
	}
	
	static function getDb(){
		return 'main';
	}

	static function tableName(){
		return 'acc';
	}

	static function getAccScrape(){
		$now = \common\Config::now();
		$acc = self::find()->where(['use_scrapping'=> true])->andWhere(['status'=> true])->andWhere('last_use_scrapping is null')->one();
		if($acc){
			return $acc;
		}
		$acc = self::find()->where(['use_scrapping'=> true])
			->andWhere(['status'=> true])
			->andWhere("'{$now}'::timestamp >= (last_use_scrapping::timestamp + INTERVAL '1 hours')")
			->orderBy('last_use_scrapping asc')
			->one();
		return $acc;
	}

}