<?php 

namespace controllers;

use apps\controllers\Controller;
use apps\controllers\IController;
use models\Acc;
use views\start\Index;
use components\BTele;
use components\Button;

class StartController extends Controller implements IController{
	// function actionIndex(){
	// 	$user = Dept::find()
	// 		// ->leftJoin([Dept::tableName(), Dept::tableName().'.id = '.User::tableName().'.dept_id'])
	// 		// ->andWhere(['allow_without_selfie'=> true])
	// 		->andWhere(['name'=> 'CEO'])
	// 		->all();
	// 	// $user2 = new User();
	// 	// $user2->name = 'gg';
	// 	// $user2->login = 'gg';
	// 	// $user2->save();
	// 	var_dump($user);
	// 	// $this->render('start/index',[
	// 	// 	'model'=> $user
	// 	// ]);
	// 	// $this->redirect('start', ['view', 'id'=> 1]);
	// }

	// function actionView(Array $params){
	// 	return $this->render('start/index',[
	// 		'title'=> 'view',
	// 		// 'acc'=> $params['acc']
	// 	]);
	// }

	function actionIndex(){
		$acc = Acc::find()->andWhere(['username'=> 'rennataclaire'])->one();
		$bTele = new BTele();
		$bTele->setChatId($this->arrayPesan['chatId'])
			->setReplyTo($this->arrayPesan['messageId'])
			->setTokenBot($this->arrayPesan['botToken'])
			->setMessageId($this->arrayPesan['messageId']);

		$text = Index::getMessage(['title'=> $acc->username]);
		$bTele->setText(urlencode($text))->setKeyboard(Button::startButton())->sendMessage();
	}
}