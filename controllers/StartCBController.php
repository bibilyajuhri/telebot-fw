<?php 

namespace controllers;

use apps\controllers\Controller;
use apps\controllers\IController;
use models\Acc;
use views\start\Index;
use components\BTele;
use components\Button;

class StartCBController extends Controller implements IController{

	function actionIndex(){
		$acc = Acc::find()->andWhere(['username'=> 'rennataclaire'])->one();
		$bTele = new BTele();
		$bTele->setChatId($this->arrayPesan['chatId'])
			->setReplyTo($this->arrayPesan['messageId'])
			->setTokenBot($this->arrayPesan['botToken'])
			->setMessageId($this->arrayPesan['messageId']);

		$text = Index::getMessage(['title'=> $acc->username]);
		$bTele->setText(urlencode($text))->setKeyboard(Button::startButton())->editMessage();
	}
}