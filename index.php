<?php 

require 'autoload.php';
require 'error-handler.php';
require 'settings.php';

$inputJson = file_get_contents("php://input");
if($inputJson || true){
	$router = new \common\Router($inputJson);
	ob_start();
	$router->run();
	ob_end_flush();
}

/// another process ///