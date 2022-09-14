<?php 

namespace apps\request;

use common\Debugger;

class Curl{
	public $baseUrl;
	public $url;
	public $headers = [];
	public $method;
	public $data;

	function __construct($baseUrl){
		$this->baseUrl = $baseUrl;
	}

	public function setUrl(String $url){
		$this->url = $url;
		return $this;
	}

	public function setMethod(String $method){
		$this->method = $method;
		return $this;
	}

	public function setData(Array $data){
		$this->data = $data;
		return $this;
	}

	public function send(){
		$ch = curl_init();

		$optArray = array(
	        CURLOPT_URL => $this->baseUrl.'/'.$this->url,
	        CURLOPT_RETURNTRANSFER => true,
	    );

	    if(strtolower($this->method) == 'post'){
	    	$optArray = array_merge($optArray, [CURLOPT_POST=> 1]);
	    	$optArray = array_merge($optArray, [CURLOPT_POSTFIELDS=> json_encode($this->data)]);
	    }else{
	    	// $optArray = array_merge($optArray, [CURLOPT_CUSTOMREQUEST=> 'GET']);
	    }
		    
		// Debugger::createLog(json_encode($optArray));
		curl_setopt_array($ch, $optArray);
		$result = curl_exec($ch);
		    
		$err = curl_error($ch);
		curl_close($ch);    
		    
		if($err<>""){
			echo "Error: $err<br>";
		}else{
			echo "Pesan Terproses<br>";
		}
		return $result;
	}
}