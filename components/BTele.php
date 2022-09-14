<?php

namespace components;

use apps\request\Curl;

class BTele{
	// const TOKEN_MAIN = 'bot'.'876605555:AAFYovdp4f5bOPZ3plaIZ_QFrcHVbxPBUBc'; //@bibiljrbot
	const TOKEN_MAIN = 'bot'.'5049308659:AAEU1-v2ZTzGBWVohpYQt7o95U6McVfOqo0'; //@beebilbot

	public $chatId;
	public $fromChatId;
	public $userId;
	public $messageId;
	public $fileId;

	public $username;
	public $text;
	public $parseMode;
	public $replyTo;
	public $keyboard;
	public $photo;
	public $caption;
	public $sticker;
	public $animation;
	public $video;
	public $voice;

	public $endPoint;
	public $method;
	public $tokenBot;

	// poll
	public $question;
	public $options;
	public $type;
	public $multiple;
	public $correct;

	public $curl;

	public function __construct(){
		$this->parseMode = 'html';
		$this->replyTo = false;
		$this->keyboard = false;
		$this->tokenBot = 'main';
		$this->chatId = '645364384';
		$this->curl = new Curl('https://api.telegram.org');
	}

	public function setChatId($value){
		$this->chatId = $value;
		return $this;
	}

	public function setFromChatId($value){
		$this->fromChatId = $value;
		return $this;
	}

	public function setUserId($value){
		$this->userId = $value;
		return $this;
	}

	public function setMessageId($value){
		$this->messageId = $value;
		return $this;
	}

	public function setFileId($value){
		$this->fileId = $value;
		return $this;
	}

	public function setUsername($value){
		$this->username = $value;
		return $this;
	}

	public function setText($value){
		$this->text = $value;
		return $this;
	}

	public function setParseMode($value){
		$this->parseMode = $value;
		return $this;
	}

	public function setReplyTo($value){
		$this->replyTo = $value;
		return $this;
	}

	public function setKeyboard($value){
		$this->keyboard = $value;
		return $this;
	}

	public function setPhoto($value){
		$this->photo = $value;
		return $this;
	}

	public function setCaption($value){
		$this->caption = $value;
		return $this;
	}

	public function setSticker($value){
		$this->sticker = $value;
		return $this;
	}

	public function setAnimation($value){
		$this->animation = $value;
		return $this;
	}

	public function setVideo($value){
		$this->video = $value;
		return $this;
	}

	public function setVoice($value){
		$this->voice = $value;
		return $this;
	}

	public function setEndPoint($value){
		$this->endPoint = $value;
		return $this;
	}

	public function setMethod($value){
		$this->method = $value;
		return $this;
	}

	public function setTokenBot($value){
		$this->tokenBot = $value;
		return $this;
	}

	public function getTokenBot(){
		return $this->tokenBot;
	}

	// poll

	public function setQuestion($value){
		$this->question = $value;
		return $this;
	}

	public function setOptions($value){
		$this->options = $value;
		return $this;
	}

	public function setType($value){
		$this->type = $value;
		return $this;
	}

	public function setMultiple($value){
		$this->multiple = $value;
		return $this;
	}

	public function setCorrect($value){
		$this->correct = $value;
		return $this;
	}

	public function sendMessage(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->text)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		if($this->replyTo){
			$url = $tokenBot."/sendMessage?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&text=".$this->text."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard;
		}else{
			$url = $tokenBot."/sendMessage?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&text=".$this->text."&reply_markup=".$this->keyboard;
		}

		// $this->sendChatAction('typing');

		return $this->proccess($url);
	}

	public function forwardMessage(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->messageId) || is_null($this->fromChatId)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);
		$url = $tokenBot."/forwardMessage?chat_id=".$this->chatId."&message_id=".$this->messageId."&from_chat_id=".$this->fromChatId;

		// $this->sendChatAction('typing');

		return $this->proccess($url);
	}

	public function sendPhoto(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->photo)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		if($this->replyTo){
			$url = $tokenBot."/sendPhoto?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&photo=".$this->photo."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard."&caption=".$this->caption;
		}else{
			$url = $tokenBot."/sendPhoto?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&photo=".$this->photo."&reply_markup=".$this->keyboard."&caption=".$this->caption;
		}

		return $this->proccess($url);
	}

	public function sendSticker(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->sticker)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		if($this->replyTo){
			$url = $tokenBot."/sendSticker?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&sticker=".$this->sticker."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard;
		}else{
			$url = $tokenBot."/sendSticker?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&sticker=".$this->sticker."&reply_markup=".$this->keyboard;
		}

		return $this->proccess($url);
	}

	public function sendAnimation(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->animation)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		if($this->replyTo){
			$url = $tokenBot."/sendAnimation?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&animation=".$this->animation."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard."&caption=".$this->caption;
		}else{
			$url = $tokenBot."/sendAnimation?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&animation=".$this->animation."&reply_markup=".$this->keyboard."&caption=".$this->caption;
		}

		return $this->proccess($url);
	}

	public function sendVideo(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->video)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		if($this->replyTo){
			$url = $tokenBot."/sendVideo?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&video=".$this->video."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard."&caption=".$this->caption;
		}else{
			$url = $tokenBot."/sendVideo?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&video=".$this->video."&reply_markup=".$this->keyboard."&caption=".$this->caption;
		}

		return $this->proccess($url);
	}

	public function sendPoll(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->question) || is_null($this->options)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		if($this->replyTo){
			$url = $tokenBot."/sendPoll?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&question=".$this->question."&options=".$this->options."&type=".$this->type."&allows_multiple_answers=".$this->multiple."&correct_option_id=".$this->correct."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard;
		}else{
			$url = $tokenBot."/sendPoll?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&question=".$this->question."&options=".$this->options."&type=".$this->type."&allows_multiple_answers=".$this->multiple."&correct_option_id=".$this->correct."&reply_markup=".$this->keyboard;
		}
		// die(var_dump($url));
		// $url = $tokenBot."/sendPoll?chat_id=".$this->chatId."&question=".$this->question."&options=".$this->options."&type=".$this->type."&correct_option_id=".$this->correct."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard;
		// die(var_dump($url));

		return $this->proccess($url);
	}

	public function sendChatAction($action = null){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($action)){
			echo 'parameter required';
			return $this;
		}
		// $action
		// typing, upload_photo, record_video || upload_video, record_voice || upload_voice, upload_document, find_location, record_video_note || upload_video_note

		$tokenBot = $this->cekBotToken($this->tokenBot);

		$url = $tokenBot."/sendChatAction?chat_id=".$this->chatId."&action=".$action;

		return $this->proccess($url);
	}

	public function editMessage(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->messageId) || is_null($this->text)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		$url = $tokenBot."/editmessagetext?chat_id=".$this->chatId."&message_id=".$this->messageId."&text=".$this->text."&reply_markup=".$this->keyboard."&parse_mode=".$this->parseMode;

		// $this->sendChatAction('typing');

		return $this->proccess($url);
	}

	public function deleteMessage(){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->messageId)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		$url = $tokenBot."/deleteMessage?chat_id=".$this->chatId."&message_id=".$this->messageId;

		return $this->proccess($url);
	}

	public function sendNotif($callbackQueryId){
		if(is_null($this->tokenBot) || is_null($this->text)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		$url = $tokenBot."/answercallbackquery?callback_query_id=".$callbackQueryId."&text=".$this->text."&show_alert=true";

		return $this->proccess($url);
	}

	public function mute(){
		if(is_null($this->tokenBot) || is_null($this->chatId)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		$url = $tokenBot."/setChatPermissions?chat_id=".$this->chatId;

		return $this->proccess($url);
	}

	public function muteuser($second = null){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->userId)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);
		$perm = json_encode(['can_send_messages'=> false]);
		$now = strtotime('now');
		if($second){
			$untilDate = $now + $second;
		}else{
			$untilDate = $now + (24*60*60);
		}
		$url = $tokenBot."/restrictChatMember?chat_id=".$this->chatId."&user_id=".$this->userId."&until_date=".$untilDate."permissions=".$perm;

		return $this->proccess($url);
	}

	public function unmuteuser($second = null){
		if(is_null($this->tokenBot) || is_null($this->chatId) || is_null($this->userId)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);
		$perm = json_encode(['can_send_messages'=> true, 'can_send_media_messages'=> true]);
		$now = strtotime('now');
		if($second){
			$untilDate = $now + $second;
		}else{
			$untilDate = $now + (24*60*60);
		}
		$url = $tokenBot."/restrictChatMember?chat_id=".$this->chatId."&user_id=".$this->userId."&until_date=".$untilDate."permissions=".$perm;
		if($second == -1){
			$url = $tokenBot."/restrictChatMember?chat_id=".$this->chatId."&user_id=".$this->userId."&permissions=".$perm;
		}

		return $this->proccess($url);
	}

	public function unmute(){
		if(is_null($this->tokenBot) || is_null($this->chatId)){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);
		$perm = json_encode(['can_send_messages'=> true, 'can_send_media_messages'=> true]);
		$url = $tokenBot."/setChatPermissions?chat_id=".$this->chatId."&permissions=".$perm;

		return $this->proccess($url);
	}

	public function kick(){
		if(is_null($this->tokenBot) || is_null($this->chatId || is_null($this->userId))){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);
		$url = $tokenBot."/kickChatMember?chat_id=".$this->chatId."&user_id=".$this->userId;

		return $this->proccess($url);
	}

	public function unbanned(){
		if(is_null($this->tokenBot) || is_null($this->chatId || is_null($this->userId))){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);
		$url = $tokenBot."/unbanChatMember?chat_id=".$this->chatId."&user_id=".$this->userId;

		return $this->proccess($url);
	}

	public function sendVoice(){
		if(is_null($this->tokenBot) || is_null($this->chatId || is_null($this->voice))){
			echo 'parameter required';
			return $this;
		}

		$tokenBot = $this->cekBotToken($this->tokenBot);

		if($this->replyTo){
			$url = $tokenBot."/sendVoice?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&voice=".$this->voice."&caption=".$this->caption."&reply_to_message_id=".$this->replyTo."&reply_markup=".$this->keyboard;
		}else{
			$url = $tokenBot."/sendVoice?parse_mode=".$this->parseMode."&chat_id=".$this->chatId."&voice=".$this->voice."&caption=".$this->caption."&reply_markup=".$this->keyboard;
		}

		return $this->proccess($url);
	}

	public function cekBotToken($tokenBot){
		if(strtolower($tokenBot) == 'main'){
			$tokenBot = self::TOKEN_MAIN;
		}
		return $tokenBot;
	}

	public function proccess($url){
		$request = $this->curl->setUrl($url)
			->setMethod('get')
			->send();

		return $request;
	}
}