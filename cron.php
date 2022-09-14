<?php 

require 'autoload.php';
require 'error-handler.php';
require 'settings.php';

// run via cli: php cron.php class action param1=value1 param2=value2
// run via browser: cron.php?class=class&action=action&param1=value1

$run = false;
if(isset($_GET['class'])){
	$run = true;
	$param = $_GET;
	$class = $param['class'];
	$action = isset($param['action']) ? $param['action'] : 'Index';
	unset($param['action']);
	unset($param['class']);
}else
if(isset($argv[1])){
	$run = true;
	$class = $argv[1];
	$action = isset($argv[2]) ? $argv[2] : 'Index';
	array_shift($argv);
	array_shift($argv);
	array_shift($argv);
	$param = [];
	foreach ($argv as $key => $value) {
		$arr = explode('=', $value);
		$param[$arr[0]] = isset($arr[1]) ? $arr[1] : null;
	}
}

if($run){
	ob_start();
	$className = ucfirst($class).'Controller';
	$filePath = __DIR__.'/cron/controllers/'.$className.'.php';
	if(!file_exists($filePath) || in_array($className, ['Controller', 'BController'])){
		throw new \Exception("Controller(Command) not found", 1);
	}

	$controllerWithNamespace = "\\cron\\controllers\\".$className;
	$controller = new $controllerWithNamespace($param);
	$actionName = 'action'.$action;
	$controller->$actionName();
	ob_end_flush();
}

/// another process ///