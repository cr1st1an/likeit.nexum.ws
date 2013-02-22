<?php

class CurlClient {

    protected $curl = null;

    public function __construct() {
        $this->initializeCurl();
    }

    public function request($method, $path, $data) {
        $raw = null;
        switch ($method) {
        case 'post':
            $raw = $this->_curl_client->post($path, $data);
        break;
        case 'get':
            $raw = $this->_curl_client->get($path, $data);
        break;
        case 'put':
            $raw = $this->_curl_client->put($path, $data);
        break;
        case 'delete':
            $raw = $this->_curl_client->delete($path, $data);
        break;
        }
        $json = json_decode($raw, true);
        if ($json == null) {
            if (json_last_error() != JSON_ERROR_NONE) {
                trigger_error(sprintf(`Could not unmarshal non-JSON string "%s"`, $raw), E_USER_ERROR);
            }
        }
        return $json;
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