<?php

namespace apps\controllers;

class ApiController{

	public $param;
	public $rawData;
	public $postData;

	function  __construct(Array $param){
		$this->param = new \stdClass;
		foreach ($param as $key => $value) {
			$this->param->$key = $value;
		}

		$rawData = file_get_contents('php://input');
		if($rawData){
			$this->rawData = json_decode($rawData);
		}

		$postData = $_POST;
		if($postData){
			$this->postData = new \stdClass;
			foreach ($postData as $key => $value) {
				$this->postData->$key = $value;
			}
		}
	}
}