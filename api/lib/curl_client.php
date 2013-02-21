<?php

class CurlClient {
    
    protected $curl = null;
    
    public function __construct() {
        $this->initializeCurl();
    }
    
    public function get($url, array $data = null) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_URL, sprintf("%s?%s", $url, http_build_query($data)));
        return $this->fetch();
    }
    
    public function post($url, array $data = null) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        return $this->fetch();
    }
    
    public function put($url, array $data = null) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    }
    
    public function delete($url, array $data = null) {
        curl_setopt($this->curl, CURLOPT_URL, sprintf("%s?%s", $url, http_build_query($data)));
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->fetch();
    }
    
    protected function initializeCurl() {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }
    
    protected function fetch() {
        $raw_response = curl_exec($this->curl);
        $error = curl_error($this->curl);
        if ($error) {
            echo $error;
            exit();
        }
        return $raw_response;
    }
    
}