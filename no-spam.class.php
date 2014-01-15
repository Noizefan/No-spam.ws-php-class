<?php

/**
	Sometimes I need to recive emails on PHP.
	This simple class working with email service, which give you an email address for 30 minutes.
	@author: noizefan, <qwerty.noizefan@yandex.ru>
*/

class emailService{
	
	public $mails = array();//for mails, must be empty if count(emails)==0
	public $email;//no comment
	public $count;//count of emails
	
	public function newEmail($email = false){//just get new email and clear array with emails
		if(!$email){
			$email = $this->rndName();
		}
		//some work with global variables
		$this->email = $email;
		$this->mails = array();
		
		$this->getEmailList();
		
		return $email; //need it if $email != false
	}
	
	public function getEmailList(){//gets new email list, or reload last.
		$page = $this->curl_get("http://no-spam.ws/?login=".$this->email);
		
		preg_match_all("/<input type=\"checkbox\" name=\"xdelete\[\]\" value=\"(.*?)\">/s", $page, $emailIds);
		$emailIds = $emailIds[1];//we need only masks, not checkbox
		
		for($i = 0; $i < count($emailIds); $i++){
			$email = $this->curl_get("http://no-spam.ws/?login=".$this->email."&mail=".$emailIds[$i]);
			//parsing email info
			preg_match("/<td>From:<\/td><td class=(.*?)><div style=(.*?)>(.*?)<\/div>/", $email, $from);//from
			preg_match("/<td>Subject:<\/td><td class=(.*?)><div style=(.*?)>(.*?)<\/div><\/td>/s", $email, $subject);//subject of mail
			preg_match("/<td colspan=2><div style=(.*?)>(.*?)<\/div><br><br>/s", $email, $body);//message
			
			$this->mails[] = array($from[3], $subject[3], $body[2]); //put mail into mail array
		}
	}
	
	public function curl_get($url, $proxy=false){//if you dont want to use proxy, $proxy must be false
		$ch = curl_init(); 
		//some cURL options
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.9; Windows XP)');
		if($proxy != false){
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
		
		$page=curl_exec($ch); 
		curl_close($ch);
		 
		return $page;
	}
	
	public function rndName(){
		return md5(rand(rand(0,5000),rand(5000,10000)).rand(0,10000).rand(0,10000).rand(0,10000));//stupidest way to generate random string :D
	}
	
}
?>
