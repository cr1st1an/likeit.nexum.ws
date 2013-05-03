<?php

class Route_Sessions {

    public function getRoot() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Sessions = new DB_Sessions();
        $DB_Subscribers = new DB_Subscribers();

        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $get = array();
        $session_data = array();
        $subscriber_data = array();
        $user_data = array();

        if (empty($response)) {
            $r_getGetParams = $Validator->getGetParams(array('id_session', 'client', 'version', 'id_install', 'code'));

            if ($r_getGetParams['success']) {
                $get = $r_getGetParams['get'];
            } else {
                $response = $r_getGetParams;
            }
        }

        if (empty($response)) {
            $r_select = $DB_Sessions->select($get['id_session']);
            if ($r_select['success']) {
                $session_data = $r_select['session_data'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            $r_select = $DB_Subscribers->select($session_data['id_subscriber']);
            if ($r_select['success']) {
                $subscriber_data = $r_select['subscriber_data'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            if (md5($get['code']) !== $session_data['md5_code']) {
                $response['success'] = false;
                $response['message'] = t('error012');
            }
        }

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $session_data['access_token'];

            $r_getUser = $Instagram->getUser('self', $request_data);
            if (200 === $r_getUser['meta']['code']) {
                $user_data = $r_getUser['data'];
            } else {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getUser['meta']['error_message'];
            }
        }

        if (empty($response)) {
            getSession()->set('id_session', $session_data['id_session']);
            getSession()->set('id_subscriber', $session_data['id_subscriber']);
            getSession()->set('id_ig_user', $session_data['id_ig_user']);
            getSession()->set('access_token', $session_data['access_token']);
            getSession()->set('scl', $subscriber_data['scl']);
            
            $response['success'] = true;
            $response['message'] = t('ok034') . getSession()->get('id_session');
            $response['id_session'] = $session_data['id_session'];
            $response['user_data'] = $user_data;
            if (empty($subscriber_data['email']))
                $response['trigger'] = 'no_email';
            else if (!$subscriber_data['verified'])
                $response['trigger'] = 'no_code';
        }

        if (!$response['success']) {
            $response['trigger'] = 'no_session';
        }
        
        return $response;
    }

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
        $subscriber_data = array();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('client', 'version', 'id_install', 'code'));

            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
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
            $r_insert = $DB_Subscribers->insert(
                    array(
                        'id_ig_user' => $instagram_user['id']
                    )
            );
            if ($r_insert['success']) {
                $id_subscriber = $r_insert['id_subscriber'];
            } else {
                $response['success'] = false;
                $response['message'] = t('error002') . $instagram_user['id'];
            }
        }

        if (empty($response)) {
            $r_select = $DB_Subscribers->select($id_subscriber);
            if ($r_select['success']) {
                $subscriber_data = $r_select['subscriber_data'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            $session_data = array(
                'id_subscriber' => $id_subscriber,
                'id_ig_user' => $instagram_user['id'],
                'id_install' => $post['id_install'],
                'client' => $post['client'],
                'version' => $post['version'],
                'md5_code' => md5($post['code']),
                'access_token' => $access_token
            );
            $r_insert_1 = $DB_Sessions->insert($session_data);

            if (!$r_insert_1['success']) {
                $response = $r_insert_1;
            }
        }

        if (empty($response)) {


            getSession()->set('id_session', $r_insert_1['id_session']);
            getSession()->set('id_subscriber', $session_data['id_subscriber']);
            getSession()->set('id_ig_user', $session_data['id_ig_user']);
            getSession()->set('access_token', $session_data['access_token']);
            if(empty($subscriber_data['scl']))
                getSession()->set('scl', 0);
            else
                getSession()->set('scl', $subscriber_data['scl']);

            $response['success'] = true;
            $response['message'] = t('ok001') . getSession()->get('id_session');
            $response['id_session'] = getSession()->get('id_session');
            $response['user_data'] = $instagram_user;
            if (empty($subscriber_data['email']))
                $response['trigger'] = 'no_email';
            else if (!$subscriber_data['verified'])
                $response['trigger'] = 'no_code';
        }
        
        return $response;
    }

}