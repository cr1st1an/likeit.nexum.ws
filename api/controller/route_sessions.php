<?php

class Route_Sessions {

    public function postRoot() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Sessions = new DB_Sessions();
        $DB_Subscribers = new DB_Subscribers();

        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $post = array();
        $access_token = '';
        $instagram_user = array();
        $id_subscriber = '';

        if (empty($response)) {
            $r_getPostParams_1 = $Validator->getPostParams(array('client', 'version', 'id_install', 'code'));

            if (!$r_getPostParams_1['success']) {
                $response = $r_getPostParams_1;
            } else {
                $post = $r_getPostParams_1['post'];
            }
        }

        if (empty($response)) {
            $r_auth_1 = $Instagram->auth($post['code']);

            if (empty($r_auth_1['error_message'])) {
                $access_token = $r_auth_1['access_token'];
                $instagram_user = $r_auth_1['user'];
            } else {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_auth_1['error_message'];
            }
        }

        if (empty($response)) {
            $r_selectWhereIdInstagram_1 = $DB_Subscribers->selectWhereIdInstagram($instagram_user['id']);
            if (!$r_selectWhereIdInstagram_1['success']) {
                $r_insertValuesIdInstagram_1 = $DB_Subscribers->insertValuesIdInstagram($instagram_user['id']);
                if (!$r_insertValuesIdInstagram_1['success']) {
                    $response['success'] = false;
                    $response['message'] = t('error002') . $instagram_user['id'];
                } else {
                    $id_subscriber = $r_insertValuesIdInstagram_1['id_subscriber'];
                }
            } else {
                $id_subscriber = $r_selectWhereIdInstagram_1['data_subscriber']['id_subscriber'];
            }
        }

        if (empty($response)) {
            $data_session = array(
                'id_subscriber' => $id_subscriber,
                'id_instagram' => $instagram_user['id'],
                'id_install' => $post['id_install'],
                'client' => $post['client'],
                'version' => $post['version'],
                'md5_code' => md5($post['code']),
                'access_token' => $access_token
            );
            $r_insert_1 = $DB_Sessions->insert($data_session);

            if (!$r_insert_1['success']) {
                $response = $r_insert_1;
            }
        }

        if (empty($response)) {
            getSession()->set('id_session', $r_insert_1['id_session']);
            getSession()->set('id_subscriber', $data_session['id_subscriber']);
            getSession()->set('id_instagram', $data_session['id_instagram']);
            getSession()->set('access_token', $data_session['access_token']);

            $response['success'] = true;
            $response['message'] = t('ok001') . getSession()->get('id_session');
        }

        return $response;
    }

}