<?php

class Route_Subscribers {

    public function postDeviceToken() {
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'parse.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Subscribers = new DB_Subscribers();

        $Parse = new Parse();
        $Validator = new Validator();

        $response = array();
        $post = array();
        $subscriber_data = array();

        $response = $Validator->verifySession();
        $id_subscriber = getSession()->get('id_subscriber');
        $id_ig_user = getSession()->get('id_ig_user');

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('device_token'));

            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
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
            
        }

        if (empty($response) && $post['device_token'] !== $subscriber_data['device_token']) {
            $r_updateDeviceToken = $DB_Subscribers->updateDeviceToken($id_subscriber, $post['device_token']);
            if ($r_updateDeviceToken['success']) {
                $channels = array();

                $parameters = array();
                $parameters['where'] = array(
                    'deviceToken' => $post['device_token']
                );
                $parameters['limit'] = '1';

                $r_call_1 = $Parse->call('installations', array(), 'GET', $parameters);

                if (isset($r_call_1['error'])) {
                    $response['success'] = false;
                    $response['message'] = "Parse says: " . $r_call_1['error'];
                } else {
                    if (!empty($r_call_1['results']))
                        $channels = $r_call_1['results'][0]['channels'];
                }

                $channels[] = 'ig-' . $id_ig_user;

                $payload = array();
                $payload['deviceType'] = 'ios';
                $payload['deviceToken'] = $post['device_token'];
                $payload['channels'] = $channels;

                $r_call_2 = $Parse->call('installations', $payload);
                if (isset($r_call_2['error'])) {
                    $response['success'] = false;
                    $response['message'] = "Parse says: " . $r_call_2['error'];
                }
            } else {
                $response = $r_updateDeviceToken;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok036') . $id_subscriber;
        }

        return $response;
    }

    public function postEmail() {
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Subscribers = new DB_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $subscriber_data = array();

        $response = $Validator->verifySession();
        $id_subscriber = getSession()->get('id_subscriber');

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('email'));

            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
            }
        }

        if (empty($response) && !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $response['success'] = false;
            $response['message'] = t('error015') . $post['email'];
        }

        if (empty($response)) {
            $r_select = $DB_Subscribers->select($id_subscriber);
            if ($r_select['success']) {
                $subscriber_data = $r_select['subscriber_data'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response) && $post['email'] !== $subscriber_data['email']) {
            $r_updateEmail = $DB_Subscribers->updateEmail($id_subscriber, $post['email']);
            if ($r_updateEmail['success']) {
                getApi()->invoke('/v1/workers/invite_subscriber', EpiRoute::httpGet, array('_GET' => array('id_subscriber' => $id_subscriber)));
            } else {
                $response = $r_updateEmail;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok036') . $id_subscriber;
        }

        return $response;
    }

    public function postInvite() {
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Subscribers = new DB_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $subscriber_data = array();

        $response = $Validator->verifySession();
        $id_subscriber = getSession()->get('id_subscriber');

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('invite'));

            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
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
            if (md5($post['invite']) !== $subscriber_data['md5_invite']) {
                $response['success'] = false;
                $response['message'] = t('error005');
            }
        }

        if (empty($response)) {
            $response = $DB_Subscribers->updateVerified($id_subscriber, true);
        }

        return $response;
    }

}