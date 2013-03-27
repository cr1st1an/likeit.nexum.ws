<?php

class Mandrill {
    const API_URL = 'http://mandrillapp.com/api/1.0';
    const API_KEY = MANDRILL_API_KEY;

    private $_api_key = self::API_KEY;
    
    public function __construct($api_key = null) {
        if (!empty($api_key))
            $this->_api_key = $api_key;
    }
    
    public function call($url, $params = array()) {
        if (is_null($params)) {
            $params = array();
        }

        $url = strtolower($url);
        $url .= '.json';

        $params = array_merge($params, array('key' => $this->_api_key));
        
        $json = json_encode($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL.$url);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json)));
        
        $result = curl_exec($ch);
        curl_close($ch);
        $decoded = json_decode($result, true);

        return is_null($decoded) ? $result : $decoded;
    }
    
}