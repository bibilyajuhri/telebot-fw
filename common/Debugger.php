<?php

namespace common;

use models\ErrorLog;

class Debugger{

	public static function createLog(String $message){
		$errorLog = new ErrorLog();
	    $errorLog->message = $message;
	    $errorLog->created_at = \common\Config::now();
	    $errorLog->tipe_log = ErrorLog::TIPE_DEBUG;
	    $errorLog->save();
		// error_log($message."\n\n", 3, '/home/xcsrf/telebotv2/log/debug.log');
	}
}