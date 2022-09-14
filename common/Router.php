<?php  

namespace common;

use common\Config;

class Router{

	private $inputJson;
	private $inputArray;
	private $config;

	private $arrayPesan = [];

	function __construct($inputJson){
		$this->inputJson = $inputJson;
		$this->inputArray = json_decode($inputJson, true);
		$this->config = Config::get();
		$this->buildAttribute();
	}

	private function buildAttribute(){
		$botToken = isset($_GET['bot']) ? $_GET['bot'] : 'main';
		$message = null;
		$replyMessage = null;
		$pesan = null;
		$chatId = null;
		$userId = null;
		$username = null;
		$messageId = null;
		if(isset($this->inputArray['message']) || isset($this->inputArray['edited_message'])){
			$message = isset($this->inputArray['message']) ? $this->inputArray['message'] : $this->inputArray['edited_message'];
			$pesan = isset($message['text']) ? $message['text'] : '';

			$chatId = $message['chat']['id'];
			$userId = $message['from']['id'];
			$username = isset($message['chat']['username']) ? $message['chat']['username'] : '';
			$messageId = $message['message_id'];

			if($this->config['debugMode']['active'] && $chatId != $this->config['debugMode']['chatId']){
				die('debug mode on!');
			}

			if($userId == $chatId){
				if(!in_array($pesan[0], $this->config['replacement'])){
					if($userId == $this->config['ownerChatId']){
						$pesan = '/privatechat|fromme|'.$pesan;
					}else{
						$pesan = '/privatechat|notme|'.$pesan;
					}
				}
			}else{
				if($pesan){
					if(!in_array($pesan[0], $this->config['replacement'])){
						$pesan = $pesan[0].'/groupchat|fromme|'.$pesan;
					}else
					if(in_array($pesan[0], $this->config['replacement'])){
						//masuk ke controller pasti, karna format /controller|ke-2|ke-3
					}else{
						$pesan = $pesan[0].'/groupchat|fromme|'.$pesan;
					}
				}
			}


			if(isset($message['reply_to_message'])){
				$replyMessage = $message['reply_to_message'];
			}
			
			if(isset($message['new_chat_members'])){
				$pesan = '/newmember|multi';
				$username = $message['new_chat_members'];
			}else
			if(isset($message['new_chat_member'])){
				$pesan = '/newmember';
				$username = $message['new_chat_member'];
			}

			if(isset($message['left_chat_member'])){
				$pesan = '/leftmember';
				$username = $message['left_chat_member'];
			}
		}

		$callback_query = null;
		$cbId = null;
		$data = null;
		$cbChatId = null;
		$cbUserId = null;
		$cbUsername = null;
		$cbMessageId = null;
		if(isset($this->inputArray['callback_query'])){
			$callback_query = $this->inputArray['callback_query'];
			$cbId = $callback_query['id'];
			$data = $callback_query['data'];
			$cbChatId = $callback_query['message']['chat']['id'];
			$cbUserId = $callback_query['from']['id'];
			$cbUsername = isset($callback_query['from']['username']) ? $callback_query['from']['username'] : '';
			$cbMessageId = $callback_query['message']['message_id'];
		}
		$this->botToken = $botToken;
		$this->message = $message;
		$this->message = $message;
		$this->replyMessage = $replyMessage;
		$this->pesan = $pesan;
		$this->chatId = $chatId;
		$this->userId = $userId;
		$this->username = $username;
		$this->messageId = $messageId;
		$this->callback_query = $callback_query;
		$this->cbId = $cbId;
		$this->data = $data;
		$this->cbChatId = $cbChatId;
		$this->cbUserId = $cbUserId;
		$this->cbUsername = $cbUsername;
		$this->cbMessageId = $cbMessageId;
		return true;
	}

	public function run(){
		if(!$this->inputArray){
			return false;
		}
		if(!isset($this->inputArray['callback_query'])){
			$array = explode($this->config['delimiter'], $this->pesan);
			for ($i=0; $i < count($array); $i++) { 
				$arrayPesan['ke-'.$i] = $array[$i];
				if($i == 0){
					if(in_array($array[$i], $this->config['chatWithSufiks']['text'])){
						$arrayPesan['ke-'.$i] = str_replace($this->config['chatWithSufiks']['sufiks'], '', $arrayPesan['ke-'.$i]);
					}
				}
			}

			$include = $arrayPesan['ke-0'];
			foreach ($this->config['replacement'] as $key => $value) {
				$include = str_replace($value, '', $include);
			}

			$arrayPesan['pesan'] = $this->pesan;
			$arrayPesan['chatId'] = $this->chatId;
			$arrayPesan['userId'] = $this->userId;
			$arrayPesan['username'] = $this->username;
			$arrayPesan['messageId'] = $this->messageId;
			$arrayPesan['message'] = $this->message;
			$arrayPesan['replyMessage'] = $this->replyMessage;

			$className = ucfirst($include).$this->config['sufiksFile'];
		}else{
			$array = explode($this->config['delimiterCallback'], $this->data);
			for ($i=0; $i < count($array); $i++) { 
				$arrayPesan['ke-'.$i] = $array[$i];
			}

			$include = $arrayPesan['ke-0'];
			foreach ($this->config['replacement'] as $key => $value) {
				$include = str_replace($value, '', $include);
			}

			$arrayPesan['pesan'] = $this->data;
			$arrayPesan['chatId'] = $this->cbChatId;
			$arrayPesan['userId'] = $this->cbUserId;
			$arrayPesan['username'] = $this->cbUsername;
			$arrayPesan['messageId'] = $this->cbMessageId;
			$arrayPesan['cbId'] = $this->cbId;

			$className = ucfirst($include).$this->config['sufiksFileCallback'];
		}
		$arrayPesan['botToken'] = $this->botToken;
		$this->arrayPesan = $arrayPesan;
		// $className = 'StartController';
		$filePath = 'controllers/'.$className.'.php';
		if(!file_exists($filePath) || in_array($className, ['Controller', 'BController'])){
			throw new \Exception("Controller(Command) not found", 1);
		}

		$controllerWithNamespace = "\\controllers\\".$className;
		$controller = new $controllerWithNamespace($this->arrayPesan);
		$controller->actionIndex();
		$this->finish();
	}

	private function finish(){
		$this->makeHistory();
		$this->closeConnection();
	}

	private function makeHistory(){
		if(
			isset($this->config['createHistoryMessage']) && 
			$this->config['createHistoryMessage']['allow'] &&
			$this->config['createHistoryMessage']['tableName']
		){

		}
		return true;
	}

	private function closeConnection(){
		\apps\db\DbConnection::closeAllConnection();
	}
}