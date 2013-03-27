<?php

class Parse {

    const API_URL = 'https://api.parse.com/1/';
    const APP_ID = PARSE_APP_ID;
    const MASTER_KEY = PARSE_MASTER_KEY;
    const REST_KEY = PARSE_REST_KEY;

    public function __construct() {
        
    }

    public function call($endpoint, $payload, $method = "POST", $params = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-Parse-Application-Id:' . self::APP_ID,
            'X-Parse-Master-Key: ' . self::MASTER_KEY,
            'X-Parse-REST-API-Key: ' . self::REST_KEY
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == 'POST') {
            $data = str_replace('[]', '{}', json_encode($payload));
            if ($data === '') {
                $data = '{}';
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else if ($method == 'GET') {
            $params = http_build_query($params, '', '&');
            $endpoint = $endpoint . '?' . $params;
        }
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $endpoint);
        
        $result = curl_exec($ch);
        curl_close($ch);
        $decoded = json_decode($result, true);

        return is_null($decoded) ? $result : $decoded;
    }
    
}