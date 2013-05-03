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

        if (empty($response)) {
            $invite = rand(1000, 9999);
            $r_updateInvite = $DB_Subscribers->updateInvite($subscriber_data['id_subscriber'], md5($invite));
            if (!$r_updateInvite['success']) {
                $response = $r_updateInvite;
            }
        }

        if (empty($response)) {
            $payload = array(
                'message' => array(
                    'html' =>
                    "Hi!<br/>
<br/>
Thank you for trying Like it! Every download helps us create a better app, which is our full time obsession right now. Over the course of the next weeks you'll be able to get updates and see great improvements, if you send us feedback that is.<br/>
<br/>
To get in touch just reply this email, or send us a message through our Facebook page: <a href='http://facebook.com/applikeit'>http://facebook.com/applikeit</a><br/>
<br/>
Like it!<br/>
 by Cristian and Roman Castillo<br/>
<br/>
P.S. your access code is: $invite",
                    'text' =>
                    "Hi!

Thank you for trying Like it! Every download helps us create a better app, which is our full time obsession right now. Over the course of the next weeks you'll be able to get updates and see great improvements, if you send us feedback that is.

To get in touch just reply this email, or send us a message through our Facebook page: http://facebook.com/applikeit

Like it!
 by Cristian and Roman Castillo

P.S. your access code is: $invite",
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
        exit();
        
        include_once Epi::getPath('data') . 'mc_likes.php';

        $MC_Likes = new MC_Likes();

        $likes_data = getDatabase()->all('SELECT * FROM likes');

        foreach ($likes_data as $like_data) {
            $MC_Likes->deleteAlbum($like_data['id_subscriber'], $like_data['id_ig_media'], 1984);
        }

        return array('OK');
    }

    public function getW3() {
        exit();
        
        include_once Epi::getPath('data') . 'mc_ig_media.php';

        $MC_IG_Media = new MC_IG_Media();

        $likes_data = getDatabase()->all('SELECT * FROM likes GROUP BY id_ig_media');

        foreach ($likes_data as $like_data) {
            $r_getMedia = $MC_IG_Media->getMedia($like_data['id_ig_media'], false);
            if ($r_getMedia['success']) {
                if (file_get_contents($r_getMedia['media_data']['images']['thumbnail']['url'], 0, NULL, 0, 1)) {
                    echo "YES \n";
                } else {
                    $MC_IG_Media->deleteMedia($like_data['id_ig_media']);
                }
            }
        }
    }

}