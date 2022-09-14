<?php 

require 'autoload.php';
require 'error-handler.php';
require 'settings.php';

$run = false;
if(isset($_GET['class'])){
	$run = true;
	$param = $_GET;
	$class = $param['class'];
	$action = isset($param['action']) ? $param['action'] : 'Index';
	unset($param['action']);
	unset($param['class']);
}

if($run){
	ob_start();
	$className = ucfirst($class).'Controller';
	$filePath = 'api/v1/controllers/'.$className.'.php';
	if(!file_exists($filePath) || in_array($className, ['Controller', 'BController'])){
		throw new \Exception("Controller(Command) not found", 1);
	}

	$controllerWithNamespace = "\\api\\v1\\controllers\\".$className;
	$controller = new $controllerWithNamespace($param);
	$actionName = 'action'.$action;
	$return = $controller->$actionName();
	$return = json_encode($return);
	header('Content-type: application/json');
	echo $return;
	ob_end_flush();
}

/// another process ///