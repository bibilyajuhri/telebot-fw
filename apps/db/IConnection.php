<?php 

namespace apps\db;

interface IConnection{
	public static function getInstance(String $getDb, Array $config);
	public function close();
	public function select();
}

