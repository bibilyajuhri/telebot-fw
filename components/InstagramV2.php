<?php

namespace components;

class InstagramV2{
	const BASE_URL = 'https://www.instagram.com';
	protected $defaultHeaders = [];
	protected $defaultHeadersCommentAdd = [];
	public $header = [];
	public $headerCookie = [];

	public function __construct($header = null, $headerCookie = null){
		// $this->defaultHeaders[] = 'Authority: z-p4.www.instagram.com';
		// $this->defaultHeaders[] = 'Content-Length: 0';
		// $this->defaultHeaders[] = 'X-Instagram-Ajax: b10813bd9030';
		// $this->defaultHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
		// $this->defaultHeaders[] = 'Accept: */*';
		// $this->defaultHeaders[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36';
		// // $this->defaultHeaders[] = 'X-Instagram-Zero: 1';
		// $this->defaultHeaders[] = 'X-Requested-With: XMLHttpRequest';
		// $this->defaultHeaders[] = 'X-Asbd-Id: 437806';
		// $this->defaultHeaders[] = 'X-Ig-App-Id: 936619743392459';
		// $this->defaultHeaders[] = 'Origin: https://z-p4.www.instagram.com';
		// $this->defaultHeaders[] = 'Sec-Fetch-Site: same-origin';
		// $this->defaultHeaders[] = 'Sec-Fetch-Mode: cors';
		// $this->defaultHeaders[] = 'Sec-Fetch-Dest: empty';
		// $this->defaultHeaders[] = 'Referer: https://z-p4.www.instagram.com/beebil.jr/';
		// $this->defaultHeaders[] = 'Accept-Language: id,en-US;q=0.9,en;q=0.8';
		$this->defaultHeaders[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:92.0) Gecko/20100101 Firefox/92.0';
		$this->defaultHeaders[] = 'Accept: */*';
		$this->defaultHeaders[] = 'Accept-Language: en-US,en;q=0.5';
		$this->defaultHeaders[] = 'X-Instagram-Ajax: a05ea15e08fe';
		$this->defaultHeaders[] = 'X-Ig-App-Id: 936619743392459';
		$this->defaultHeaders[] = 'X-Asbd-Id: 198387';
		$this->defaultHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
		$this->defaultHeaders[] = 'X-Requested-With: XMLHttpRequest';
		$this->defaultHeaders[] = 'Origin: https://www.instagram.com';
		$this->defaultHeaders[] = 'Alt-Used: www.instagram.com';
		$this->defaultHeaders[] = 'Connection: keep-alive';
		$this->defaultHeaders[] = 'Referer: https://www.instagram.com/beebil.jr/';
		$this->defaultHeaders[] = 'Sec-Fetch-Dest: empty';
		$this->defaultHeaders[] = 'Sec-Fetch-Mode: cors';
		$this->defaultHeaders[] = 'Sec-Fetch-Site: same-origin';
		$this->defaultHeaders[] = 'Content-Length: 0';
		$this->defaultHeaders[] = 'Te: trailers';

		$this->defaultHeadersCommentAdd[] = 'Authority: www.instagram.com';
		$this->defaultHeadersCommentAdd[] = 'Pragma: no-cache';
		$this->defaultHeadersCommentAdd[] = 'Cache-Control: no-cache';
		$this->defaultHeadersCommentAdd[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"90\", \"Google Chrome\";v=\"90\"';
		$this->defaultHeadersCommentAdd[] = 'Sec-Ch-Ua-Mobile: ?0';
		$this->defaultHeadersCommentAdd[] = 'X-Instagram-Ajax: 30238f737d37';
		$this->defaultHeadersCommentAdd[] = 'Content-Type: multipart/form-data';
		$this->defaultHeadersCommentAdd[] = 'Accept: */*';
		$this->defaultHeadersCommentAdd[] = 'X-Requested-With: XMLHttpRequest';
		$this->defaultHeadersCommentAdd[] = 'X-Asbd-Id: 437806';
		$this->defaultHeadersCommentAdd[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36';
		$this->defaultHeadersCommentAdd[] = 'X-Ig-App-Id: 936619743392459';
		$this->defaultHeadersCommentAdd[] = 'Origin: https://www.instagram.com';
		$this->defaultHeadersCommentAdd[] = 'Sec-Fetch-Site: same-origin';
		$this->defaultHeadersCommentAdd[] = 'Sec-Fetch-Mode: cors';
		$this->defaultHeadersCommentAdd[] = 'Sec-Fetch-Dest: empty';
		$this->defaultHeadersCommentAdd[] = 'Referer: https://www.instagram.com/p/CRdjz7YJ8nK/';
		$this->defaultHeadersCommentAdd[] = 'Accept-Language: id,en-US;q=0.9,en;q=0.8';
		$this->header = $header;
		$this->headerCookie = $headerCookie;
	}

	public function setHeader($header){
		$this->header = $header;
	}

	public function setHeaderCookie($headerCookie){
		$this->headerCookie = $headerCookie;
	}

	public function likeThisPost($link){
		$findPost = $this->findPost($link);
		// die(var_dump($findPost));
		if($findPost){
			$result = json_decode($findPost);
			// die(var_dump($result));
			if(isset($result->graphql->shortcode_media->id)){
				$like = $this->post('like', $result->graphql->shortcode_media->id);
				// die(var_dump('$like'));
				return true;
			}//else{die(var_dump('a'));}
		}//else{die(var_dump($findPost));}
		return false;
	}

	// public function findCommentId($postLink, $username){
	// 	$findPostID = InstagramID::fromCode($this->getPostCode($postLink));
	// 	$findComment = $this->findComment($findPostID);
	// 	if($findComment){
	// 		$result = json_decode($findComment);
	// 		// error_log($findComment."\n", 3, '/home/www/www2bee/telebot/dev/debug.log');
	// 		// die(var_dump($result->items[0]));
	// 		if(isset($result->items[0]->preview_comments)){
	// 			$commentList = $result->items[0]->preview_comments;
	// 			foreach ($commentList as $key => $value) {
	// 				if($value->user->username == $username){
	// 					// die('s');
	// 					return $value->pk;
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return false;
	// }

	public function getPostCode($postUrl){
		$matches = explode('/', $postUrl);

        if(in_array($matches[4], ['reel', 'tv', 'p'])){
            return $matches[5];
        }
        return $matches[4];
	}

	public function findPost($link){
		$arrLink = explode('?', $link);
		$linkFix = $arrLink[0];
		$linkFix = $linkFix.'/?__a=1';
		$linkFix = str_replace('//?', "/?", $linkFix);
		// die(var_dump($linkFix));
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $linkFix);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		
		$headersFix = array_merge($this->defaultHeaders, $this->header, $this->headerCookie);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headersFix);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}

	public function findComment($postId){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://i.instagram.com/api/v1/media/{$postId}/info/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		
		$headersFix = array_merge($this->defaultHeaders, $this->header, $this->headerCookie);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headersFix);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}

	public function scrapping($link){
		$link = $link.'/?__a=1';
		$linkFix = str_replace("//?", "/?", $link);
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $linkFix);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		
		$headersFix = array_merge($this->defaultHeaders, $this->header, $this->headerCookie);
		// return $headersFix;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headersFix);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}

	public function user($type, $userId){//type [follow, unfollow]
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, self::BASE_URL.'/web/friendships/'.$userId.'/'.$type.'/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$headersFix = array_merge($this->defaultHeaders, $this->header, $this->headerCookie);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headersFix);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		    return json_encode(['status'=> 'false']);
		}
		$info = curl_getinfo($ch);
		// die(var_dump($info));
		curl_close($ch);
		return $result;
	}

	public function post($type, $postId){//type [like, unlike]
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, self::BASE_URL.'/web/likes/'.$postId.'/'.$type.'/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

		$headersFix = array_merge($this->defaultHeaders, $this->header, $this->headerCookie);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headersFix);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		    return json_encode(['status'=> 'false']);
		}
		$info = curl_getinfo($ch);
		// die(var_dump($info));
		curl_close($ch);
		return $result;
	}

	public function comment($type, $commentId, $commentText = ''){//type [like, unlike]
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		if($type == 'like' || $type == 'unlike'){
			curl_setopt($ch, CURLOPT_URL, self::BASE_URL.'/web/comments/'.$type.'/'.$commentId.'/');
		}else{
			$postId = $commentId;
			curl_setopt($ch, CURLOPT_URL, self::BASE_URL.'/web/comments/'.$postId.'/'.$type.'/');
			$data = ['comment_text'=> $commentText, 'replied_to_comment_id'=> null];
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			// curl_setopt($ch, CURLOPT_VERBOSE, true);
		}
		$defaultHeaders = $type == 'add' ? $this->defaultHeadersCommentAdd : $this->defaultHeaders;
		$headersFix = array_merge($defaultHeaders, $this->header, $this->headerCookie);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headersFix);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		    return json_encode(['status'=> 'false']);
		}
		curl_close($ch);
		return $result;
	}
}