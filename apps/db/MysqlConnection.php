<?php 

namespace apps\db;

class MysqlConnection implements IConnection{

	private static $db = [];
	private static $instance = [];
	private static $getDb;

	private function __construct(){}

	public static function getInstance(String $getDb, Array $config){
		$config = $config[$getDb];
		self::$getDb = $getDb;
		if(!isset(self::$instance[$getDb]) || !self::$instance[$getDb]){
			self::$db[$getDb] = new \mysqli($config['host'], $config['username'], $config['password'], $config['dbName']);
			self::$instance[$getDb] = new self;
		}
		return self::$instance[$getDb];
	}

	public function close(){
		$close = self::$db[self::$getDb]->close();
		unset(self::$db[self::$getDb]);
		unset(self::$instance[self::$getDb]);
		return $close;
	}

	public function select(){
		var_dump(self::$db[self::$getDb]->query("select*from user"));
	}

	public function query($query){
		$exec = self::$db[self::$getDb]->query($query);
		if(!$exec){
			$this->error = self::$db[self::$getDb]->error;
		}
		return $exec;
	}

	public function fetch($mysqli){
		$arr = [];
		$index = 0;
		while($row = $mysqli->fetch_assoc()){
			$arr[$index] = $row;
			$index++;
		}
		return $arr;
	}
}