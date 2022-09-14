<?php 

namespace common;

use models\Settings;

class Config{

	public static function now($timezone = 'Asia/Jakarta'){
		$timezone = TIMEZONE ?: $timezone;
		date_default_timezone_set($timezone);
		return date('Y-m-d H:i:s');
	}

	public static function get(){
		$config = [
			'developerChatId'=> '645364384',
			'ownerChatId'=> '645364384',
			'delimiter'=> '|',
			'delimiterCallback'=> '^',
			'replacement'=> ['/', '!'],
			'sufiksFile'=> 'Controller',
			'sufiksFileCallback'=> 'CBController',
			'createHistoryMessage'=> [
				'allow'=> false,
				'tableName'=> '',
			],
			'db'=> [ // [getDb=> [config]];
				'main'=> [
					'allowConnection'=> true,
					'driver'=> 'psql',
					'host'=> 'ec2-3-222-74-92.compute-1.amazonaws.com',
					'username'=> 'dvroekiiydqdwb',
					'password'=> '1a3a73b805b6fc059f7f89d9a0ae432305f94b7a4c000b9e7ad5bc3d4b46ee5d',
					'dbName'=> 'd63f4d6q64hsha',
					'port'=> '5432'
				]
			],
			'chatWithSufiks'=>[
				'sufiks'=> '@tanyabibilbot',
				'text'=>[
					'/start@tanyabibilbot',
				],
			],
			'debugMode'=>[
				'active'=> false,
				'chatId'=> '-1001354473233'
			]
		];
		return $config;
	}
}