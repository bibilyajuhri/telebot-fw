<?php

namespace apps\controllers;

use \common\Config;

class Controller{

	public $arrayPesan;
	private $config;

	function __construct($arrayPesan){
		$this->arrayPesan = $arrayPesan;
		$this->config = Config::get();
	}

	private function getDir($folder){
		return str_replace("apps/controllers", "", __DIR__).$folder.'/';
	}

	private function sanitizeFullPath($path){
		return str_replace("//", "/", $path);
	}

	// $path = '/folder/file'
	// protected function render(String $path, Array $params = []){
	// 	$dir = $this->getDir('views');
	// 	$fullPath = $this->sanitizeFullPath($dir.$path);
	// 	foreach ($params as $key => $value) {
	// 		${$key} = $value;
	// 	}

	// 	$chatId = $this->arrayPesan['chatId'];
	// 	$messageId = $this->arrayPesan['messageId'];
	// 	$botToken = $this->arrayPesan['botToken'];

	// 	if(!file_exists($fullPath.".php")){
	// 		throw new \Exception("View file doesn't exists ({$path})", 1);
	// 	}
	// 	require $fullPath.".php";
	// }

	protected function render($path, Array $params){
		$var = [];
		foreach ($params as $key => $value) {
			$var[$key] = $value;
		}

		$var['chatId'] = $this->arrayPesan['chatId'];
		$var['messageId'] = $this->arrayPesan['messageId'];
		$var['botToken'] = $this->arrayPesan['botToken'];

		$folder = explode("/", $path)[0];
		$viewsFile = ucfirst(explode("/", $path)[1]);
		$views = "\\views\\{$folder}\\{$viewsFile}";
		$views = new $views($var);
		$views->run();
	}

	// $controller = 'controllerName' or 'folder/controllerName'
	protected function redirect(String $controller, Array $params = []){
		$dir = $this->getDir('controllers');
		$controllerName = $controller;
		$path = '';
		if(stripos($controller, '/') !== false){
			$controllerName = explode("/", $controller)[1];
			$path = explode("/", $controller)[0];
		}
		$controllerName = ucfirst($controllerName);
		$fullPath = $this->sanitizeFullPath($dir.$path.'/'.$controllerName.$this->config['sufiksFile']);
		if(!file_exists($fullPath.".php")){
			throw new \Exception("Controller file doesn't exists ({$controller})", 1);
		}
		$className = $controllerName.$this->config['sufiksFile'];
		$filePath = 'controllers/'.$className.'.php';
		if(in_array($className, ['Controller', 'BController'])){
			throw new \Exception("Controller not found", 1);
		}

		$controllerWithNamespace = "\\controllers\\".$className;
		if($path){
			$controllerWithNamespace = "\\controllers\\{$path}\\".$className;
		}
		$controller = new $controllerWithNamespace($this->arrayPesan);
		$action = ucfirst($params[0]);
		unset($params[0]);
		$action = "action{$action}";
		return $controller->$action($params);
	}
}