<?php

namespace components;

class Button{

	public static function makeButton($text, Array $options){
		return array_merge(['text'=> $text], $options);
	}
	
	public static function startButton($delimiter = '^'){
		$resi = self::makeButton('🚚 Cek resi', ['callback_data' => '/resi']);
		$ts = self::makeButton('🇮🇩 Translate 🇬🇧', ['callback_data' => '/ts']);

		$inline_keyboard = [
			[
				$resi,
				$ts
			],
		]; 
		$keyboard = [
			"inline_keyboard" => $inline_keyboard
		];
		$replyMarkup = json_encode($keyboard);
		return $replyMarkup;
    }

    public static function goHome($delimiter = '^'){
		$input = self::makeButton("⬅ Kembali ke menu", ["callback_data" => '/start']);
		$inline_keyboard = [
			[
				$input,
			],
		]; 
		$keyboard = [
			"inline_keyboard" => $inline_keyboard
		];
		$replyMarkup = json_encode($keyboard);
		return $replyMarkup;
    }
}