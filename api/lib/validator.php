<?php

class Validator {

    public function verifySession() {
        $response = array();

        $id_session = getSession()->get('id_session');

        if (empty($id_session)) {
            $response['success'] = false;
            $response['message'] = t('error006') . $id_session;
        }

        return $response;
    }

    public function getGetParams($KEYS) {
        $response = array();
        $get = array();

        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (empty($_GET[$key])) {
                    $response['success'] = false;
                    $response['message'] = t('error003') . $key;
                } else {
                    $get[$key] = $_GET[$key];
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
                if (empty($_POST[$key])) {
                    $response['success'] = false;
                    $response['message'] = t('error003') . $key;
                } else {
                    $post[$key] = $_POST[$key];
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

    public function getDataParams($KEYS, $DATA) {
        $response = array();
        $data = array();

        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (empty($DATA[$key])) {
                    if ('created' !== $key) {
                        $response['success'] = false;
                        $response['message'] = t('error003') . $key;
                    } else {
                        $data[':created'] = date("Y-m-d H:i:s");
                    }
                } else {
                    $data[':' . $key] = $DATA[$key];
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok004');
            $response['data'] = $data;
        }

        return $response;
    }

}