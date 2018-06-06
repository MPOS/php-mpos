<?php
class Notifications_NotifyMyAndroid implements IPushNotification {
    
    private $apiKey;
    public function __construct($apikey){
        $this->apiKey = $apikey;
    }
    
    static $priorities = array(
        0 => 'info',
        2 => 'error',
    );
    
    public static function getName(){
        return  "notifymyandroid.com";
    }
    
    public static function getParameters(){
        return array(
            'apikey' => 'API key',
        );
    }
    
    public function notify($message, $severity = 'info', $event = null){
        global $setting;
        curl_setopt_array($ch = curl_init(), array(
			CURLOPT_TIMEOUT_MS => 1500,
            CURLOPT_URL => "https://www.notifymyandroid.com/publicapi/notify",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query($data = array(
                "apikey" => $this->apiKey,
                "application" => $setting->getValue('website_title')?:"PHP-MPOS",
                "description" => $message,
                "content-type" => "text/html",
                "event" => $event,
                "priority" => array_search($severity, self::$priorities),
            )),
        ));
        curl_exec($ch);
        curl_close($ch);
    }
}