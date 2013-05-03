<?php

class Validator {

    public function verifySession() {
        $response = array();

        $id_session = getSession()->get('id_session');

        if (empty($id_session)) {
            $response['success'] = false;
            $response['message'] = t('error006') . $id_session;
            $response['trigger'] = 'no_session';
        }
        
        return $response;
    }

    public function getGetParams($KEYS) {
        $response = array();
        $get = array();

        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (isset($_GET[$key])) {
                    $get[$key] = $_GET[$key];
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error003') . $key;
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok004');
            $response['get'] = $get;
        }

        return $response;
    }

    public function getPostParams($KEYS) {
        $response = array();
        $post = array();

        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (isset($_POST[$key])) {
                    $post[$key] = $_POST[$key];
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error003') . $key;
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok004');
            $response['post'] = $post;
        }

        return $response;
    }

    public function getPutParams($KEYS) {
        $response = array();
        $put = array();
        
        parse_str(file_get_contents("php://input"), $_PUT);
        
        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (isset($_PUT[$key])) {
                    $put[$key] = $_PUT[$key];
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error003') . $key;
                }
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok004');
            $response['put'] = $put;
        }

        return $response;
    }
    
}