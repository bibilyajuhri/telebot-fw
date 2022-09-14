<?php 

namespace models;

use apps\models\Model;

class ErrorLog extends Model{

	const TIPE_ERROR = 'error';
	const TIPE_DEBUG = 'debug';
	
	function __construct(){
		parent::__construct();
	}
	
	static function getDb(){
		return 'main';
	}

	static function tableName(){
		return 'error_log';
	}

}