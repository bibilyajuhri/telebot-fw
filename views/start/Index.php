<?php

namespace views\start;

use components\BTele;
use components\Button;

class Index extends \apps\views\Views{

	function __construct(Array $var){
		parent::__construct($var);
	}

	function run(){
		$bTele = new BTele();
		$bTele->setChatId($this->chatId)
		->setReplyTo($this->messageId)
		->setTokenBot($this->botToken)
		->setMessageId($this->messageId);
		
		$teks = "✨Selamat datang {$this->title}✨ \n\n";
		$teks .= "Ada yang bisa saya bantu?\n";
		$bTele->setText(urlencode($teks))->setKeyboard(Button::startButton());
		$send = $bTele->sendMessage();
	}

	static function getMessage(Array $params){
		$teks = "✨Selamat datang {$params['title']}✨ \n\n";
		$teks .= "Ada yang bisa saya bantu?\n";
		return $teks;
	}

}