<?php
	class Notifications_Pushover implements IPushNotification {
		
		private $token;
		private $user;
		public function __construct($token, $user){
			$this->token = $token;
			$this->user = $user;
		}
		
		static $priorities = array(
			0 => 'info',
			1 => 'warning',
			2 => 'error',
		);
		
		public static function getName(){
			return  "pushover.net";
		}
		
		public static function getParameters(){
			return array(
				'token' => 'API Token/Key',
				'user' => 'Your User Key',
			);
		}
		
		public function notify($message, $severity = 'info', $event = null){
			curl_setopt_array($ch = curl_init(), array(
				CURLOPT_URL => "https://api.pushover.net/1/messages.json",
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => http_build_query($data = array(
					"token" => $this->token,
					"user" => $this->user,
					"message" => $code = strip_tags(preg_replace('/<([\/]?)span[^>]*>/i', '<\1b>', $message), "<b><i><u><a><font><p><br>"),
					"title" => strip_tags($event),
					"priority" => (int)array_search($severity, self::$priorities),
					"timestamp" => time(),
					"html" => preg_match('/<[^>]+>/', $code),
				)),
			));
			curl_exec($ch);
			curl_close($ch);
		}
	}