<?php

namespace apps\controllers;

class CronController{

	public $param;

	function  __construct(Array $param){
		$this->param = new \stdClass;
		foreach ($param as $key => $value) {
			$this->param->$key = $value;
		}
	}
}