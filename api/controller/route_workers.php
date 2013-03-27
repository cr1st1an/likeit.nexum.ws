<?php

class Route_Workers {

    public function getW1() {
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'mandrill.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $DB_Subscribers = new DB_Subscribers();

        $Mandrill = new Mandrill();
        $Validator = new Validator();

        $response = array();
        $get = array();
        $subscriber_data = array();

        if (empty($response)) {
            $r_getGetParams = $Validator->getGetParams(array('id_subscriber'));

            if ($r_getGetParams['success']) {
                $get = $r_getGetParams['get'];
            } else {
                $response = $r_getGetParams;
            }
        }

        if (empty($response)) {
            $r_select = $DB_Subscribers->select($get['id_subscriber']);
            if ($r_select['success']) {
                $subscriber_data = $r_select['subscriber_data'];
            } else {
                $response = $r_select;
            }
        }
        
        // VALIDATE IF MAIL IS THERE
        // VALIDATE IF VERIFIED
        
        if(empty($response)) {
            $invite = rand(1000,9999);
            $r_updateInvite = $DB_Subscribers->updateInvite($subscriber_data['id_subscriber'], md5($invite));
            if (!$r_updateInvite['success']) {
                $response = $r_updateInvite;
            }
        }
        
        if (empty($response)) {
            $payload = array(
                'message' => array(
                    'html' => 'This is your invitation code: '.$invite,
                    'text' => 'This is your invitation code: '.$invite,
                    'subject' => 'Welcome to Like it!',
                    'from_email' => 'welcome@likeit.co',
                    'from_name' => 'Like it!',
                    'to' => array(
                        array(
                            'email' => $subscriber_data['email']
                        ),
                    ),
                ),
                'async' => true
            );
            
            $response = $Mandrill->call('/messages/send', $payload);
        }
        
        return $response;
    }
    
    public function getW2() {
        include_once Epi::getPath('data') . 'mc_likes.php';

        $MC_Likes = new MC_Likes();

        $likes_data = getDatabase()->all('SELECT * FROM likes');

        foreach ($likes_data as $like_data) {
            $MC_Likes->deleteAlbum($like_data['id_subscriber'], $like_data['id_ig_media'], 1984);
        }

        return array('OK');
    }

}