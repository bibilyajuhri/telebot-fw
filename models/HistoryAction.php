<?php 

namespace models;

use apps\models\Model;

class HistoryAction extends Model{

	const ACTION_COMMENTLIKE = 'comment_like';
	const ACTION_COMMENT = 'comment';
	const ACTION_LIKE = 'like';
	const ACTION_FOLLOW = 'follow';
	const ACTION_VIEW = 'view';

	function __construct(){
		parent::__construct();
	}
	
	static function getDb(){
		return 'main';
	}

	static function tableName(){
		return 'history_action';
	}

	function acc(){
		return Acc::find()->andWhere(['id'=> $this->acc_id])->one();
	}
}