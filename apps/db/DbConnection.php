<?php 

namespace apps\db;

use common\Config;

class DbConnection{

	public static function getInstance($getDb){
		if(!Config::get()['db'][$getDb]['allowConnection']){
			throw new \Exception("Error to connect driver, please set allow connection to true", 1);
		}
		switch (Config::get()['db'][$getDb]['driver']) {
			case 'mysql':
				return MysqlConnection::getInstance($getDb, Config::get()['db']);
				break;

			case 'psql':
				return PsqlConnection::getInstance($getDb, Config::get()['db']);
				break;
			
			default:
				return null;
				break;
		}
	}

	public static function closeAllConnection(){
		foreach (Config::get()['db'] as $key => $value) {
			if($value['allowConnection'] == false){
				continue;
			}
			switch ($value['driver']) {
				case 'mysql':
					$conn = MysqlConnection::getInstance($key, Config::get()['db']);
					break;

				case 'psql':
					$conn = PsqlConnection::getInstance($key, Config::get()['db']);
					break;
			}
			if(isset($conn) && $conn){
				var_dump($conn->close());
			}
		}
	}
}