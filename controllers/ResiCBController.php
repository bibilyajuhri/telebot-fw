<?php 

namespace controllers;

use apps\controllers\Controller;
use apps\controllers\IController;

class ResiCBController extends Controller implements IController{

	function actionIndex(){
		return $this->render('resi/indexcb', []);
	}
}