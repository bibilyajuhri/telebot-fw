<?php 

namespace apps\db;

class PsqlConnection implements IConnection{

	private static $db = [];
	private static $instance = [];
	private static $getDb;

	private function __construct(){}

	public static function getInstance(String $getDb, Array $config){
		$config = $config[$getDb];
		self::$getDb = $getDb;
		if(!isset(self::$instance[$getDb]) || (!self::$db && !self::$instance)){
			$connection_string = "host={$config['host']} port={$config['port']} dbname={$config['dbName']} user={$config['username']} password={$config['password']} ";
			$connect = pg_connect($connection_string);
			if($connect){
				self::$db[$getDb] = $connect;
				self::$instance[$getDb] = new self;
			}else{
				throw new \Exception("Error to connect psql", 1);
			}
		}
		return self::$instance[$getDb];
	}

	public function close(){
		$close = pg_close(self::$db[self::$getDb]);
		unset(self::$db[self::$getDb]);
		unset(self::$instance[self::$getDb]);
		return $close;
	}

	public function select(){
		die(var_dump("select psql"));
	}

	public function query($query){
		$exec = pg_query(self::$db[self::$getDb], $query);
		if(!$exec){
			$this->error = pg_last_error(self::$db[self::$getDb]);
		}
		return $exec;
	}

	public function update($query){
		$exec = pg_query(self::$db[self::$getDb], $query);
		if(!$exec){
			$this->error = pg_last_error(self::$db[self::$getDb]);
		}
		return pg_affected_rows($exec);
	}

	public function delete($query){
		$exec = pg_query(self::$db[self::$getDb], $query);
		if(!$exec){
			$this->error = pg_last_error(self::$db[self::$getDb]);
		}
		return pg_affected_rows($exec);
	}

	public function insert($query){
		$exec = pg_query(self::$db[self::$getDb], $query);
		if(!$exec){
			$this->error = pg_last_error(self::$db[self::$getDb]);
		}
		return pg_affected_rows($exec);
	}

	public function fetch($resource){
		$arr = [];
		if($row = pg_fetch_all($resource)){
			return $row;
		}
		return $arr;
	}
}