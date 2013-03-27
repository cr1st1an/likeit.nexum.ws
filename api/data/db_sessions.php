<?php

class DB_Sessions {

    protected $_name = 'sessions';

    public function insert($DATA) {
        $response = array();
        
        $id_subscriber = (int) $DATA['id_subscriber'];
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Sessions->insert()";
        }

        $id_ig_user = (int) $DATA['id_ig_user'];
        if (empty($response) && empty($id_ig_user)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_USER " . t('txt003') . "DB_Sessions->insert()";
        }

        $id_install = $DATA['id_install'];
        if (empty($response) && empty($id_install)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_INSTALL " . t('txt003') . "DB_Sessions->insert()";
        }

        $md5_code = $DATA['md5_code'];
        if (empty($response) && empty($md5_code)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "MD5_CODE " . t('txt003') . "DB_Sessions->insert()";
        }

        $client = $DATA['client'];
        if (empty($response) && empty($client)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "CLIENT " . t('txt003') . "DB_Sessions->insert()";
        }

        $version = $DATA['version'];
        if (empty($response) && empty($version)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "VERSION " . t('txt003') . "DB_Sessions->insert()";
        }

        $access_token = $DATA['access_token'];
        if (empty($response) && empty($access_token)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ACCESS_TOKEN " . t('txt003') . "DB_Sessions->insert()";
        }

        $created = date("Y-m-d H:i:s");
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        if (empty($response)) {
            $insert_data = array(
                'id_subscriber' => $id_subscriber,
                'id_ig_user' => $id_ig_user,
                'id_install' => $id_install,
                'created' => $created,
                'client' => $client,
                'version' => $version,
                'md5_code' => $md5_code,
                'access_token' => $access_token,
                'ip' => $ip
            );
            $id_session = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(id_subscriber, id_ig_user, id_install, created, client, version, md5_code, access_token, ip) VALUES(:id_subscriber, :id_ig_user, :id_install, :created, :client, :version, :md5_code, :access_token, :ip)', $insert_data
            );

            $response['success'] = true;
            $response['message'] = t('ok001') . $id_session;
            $response['id_session'] = $id_session;
        }

        return $response;
    }

    public function select($ID_SESSION) {
        $response = array();

        $id_session = (int) $ID_SESSION;
        if (empty($response) && empty($id_session)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SESSION " . t('txt003') . "DB_Sessions->select()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_session' => $id_session
            );
            $session_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_session=:id_session', $select_data
            );

            $response['success'] = true;
            $response['message'] = t('ok034') . $id_session;
            $response['session_data'] = $session_data;
        }

        return $response;
    }

}