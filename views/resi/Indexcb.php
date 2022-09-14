<?php

namespace views\resi;

use components\BTele;
use components\Button;

class Indexcb extends \apps\views\Views{
	
	function __construct(Array $var){
		parent::__construct($var);
	}

	function run(){
		$bTele = new BTele();
		$bTele->setChatId($this->chatId)
		->setReplyTo($this->messageId)
		->setTokenBot($this->botToken)
		->setMessageId($this->messageId);

		$text = "Menu: 🚚 Cek resi\n";
		$text .= "Format: /resi|ekspedisi|no_resi\n";
		$text .= "Contoh: /resi|jne|jne12312312\n\n";
		$text .= "Ekspedisi yang didukung: \n";
		$list = [
			['key'=> 'jne', 'name'=> 'Jalur Nugraha Ekakurir (JNE)'],
			['key'=> 'pos', 'name'=> 'POS Indonesia (POS)'],
			['key'=> 'tiki', 'name'=> 'Citra Van Titipan Kilat (TIKI)'],
			['key'=> 'pcp', 'name'=> 'Priority Cargo and Package (PCP)'],
			['key'=> 'rpx', 'name'=> 'RPX Holding (RPX)'],
			['key'=> 'wahana', 'name'=> 'Wahana Prestasi Logistik (WAHANA)'],
			['key'=> 'sicepat', 'name'=> 'SiCepat Express (SICEPAT)'],
			['key'=> 'jnt', 'name'=> 'JNT Express (JNT)'],
			['key'=> 'sap', 'name'=> 'SAP Express (SAP)'],
			['key'=> 'jet', 'name'=> 'JET Express (JET)'],
			['key'=> 'dse', 'name'=> '21 Express (DSE)'],
			['key'=> 'first', 'name'=> 'First Logistics (FIRST)'],
			['key'=> 'lion', 'name'=> 'Lion Parcel (LION)'],
			['key'=> 'ninja', 'name'=> 'Ninja Xpress (NINJA)'],
			['key'=> 'idl', 'name'=> 'IDL Cargo (IDL)'],
			['key'=> 'rex', 'name'=> 'Royal Express Indonesia (REX)'],
		];

		foreach ($list as $key => $value) {
			$text .= ($key + 1) .'. '.$value['key'].' -> '.$value['name']."\n";
		}

		$bTele->setText(urlencode($text))->setKeyboard(Button::goHome())->editMessage();
	}

}