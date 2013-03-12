<?php

class Route_Albums {

    public function getRoot() {
        include_once Epi::getPath('data') . 'mc_albums.php';
        include_once Epi::getPath('data') . 'mc_ig_media.php';
        include_once Epi::getPath('data') . 'db_albums_ig_media.php';
        include_once Epi::getPath('data') . 'db_albums_subscribers.php';
        include_once Epi::getPath('lib') . 'data_handler.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Albums = new MC_Albums();
        $MC_IG_Media = new MC_IG_Media();
        $DB_Albums_IG_Media = new DB_Albums_IG_Media();
        $DB_Albums_Subscribers = new DB_Albums_Subscribers();

        $DataHandler = new DataHandler();
        $Validator = new Validator();

        $response = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $id_album = null;
        $albums_subscribers_ids = array();
        $albums_ig_media_ids = array();
        $albums_data = array();

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_select = $DB_Albums_Subscribers->select($id_subscriber);

            if ($r_select['success']) {
                $albums_subscribers_ids = $r_select['albums_subscribers_ids'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            foreach ($albums_subscribers_ids as $album_subscriber_ids) {
                $r_getAlbum = $MC_Albums->getAlbum($album_subscriber_ids['id_album']);

                if ($r_getAlbum['success']) {
                    $albums_data[] = $r_getAlbum['album_data'];
                }

                if ($_GET['id_album'] === $album_subscriber_ids['id_album']) {
                    $id_album = $_GET['id_album'];
                }
            }

            if (empty($id_album)) {
                $response['success'] = true;
                $response['message'] = t('ok020');
                $response['albums_data'] = $albums_data;
            }
        }

        if (empty($response)) {
            $r_select = $DB_Albums_IG_Media->select($id_album);
            if ($r_select['success']) {
                $albums_ig_media_ids = $r_select['albums_ig_media_ids'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            $media_data = array();
            $request_data = array();
            $request_data['access_token'] = getSession()->get('access_token');

            foreach ($albums_ig_media_ids as $album_ig_media_ids) {
                $r_getMedia = $MC_IG_Media->getMedia($album_ig_media_ids['id_ig_media'], true, $request_data);

                if ($r_getMedia['success']) {
                    $media_data[] = $r_getMedia['media_data'];
                }
            }


            $update_data = array(
                'thumbnail' => $DataHandler->thumbnail($media_data)
            );
            $MC_Albums->updateAlbum($id_album, $update_data);
            
            $response['success'] = true;
            $response['message'] = t('ok022');
            $response['id_album'] = $id_album;
            $response['media_data'] = $DataHandler->mediaFeed($media_data);
            $response['albums_data'] = $albums_data;
        }

        return $response;
    }

    public function postRoot() {
        include_once Epi::getPath('data') . 'db_albums.php';
        include_once Epi::getPath('data') . 'db_albums_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Albums = new DB_Albums();
        $DB_Albums_Subscribers = new DB_Albums_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $id_album = null;

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('title'));

            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
            }
        }

        if (empty($response)) {
            $album_data = array(
                'title' => $post['title']
            );
            $r_insert_1 = $DB_Albums->insert($album_data);

            if ($r_insert_1['success']) {
                $id_album = $r_insert_1['id_album'];
            } else {
                $response = $r_insert;
            }
        }

        if (empty($response)) {
            $album_subscriber_data = array(
                'id_album' => $id_album,
                'id_subscriber' => $id_subscriber
            );
            $r_insert_2 = $DB_Albums_Subscribers->insert($album_subscriber_data);

            if (!$r_insert_2['success']) {
                $response = $r_insert_2;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok018');
        }

        return $response;
    }

    public function postMedia() {
        include_once Epi::getPath('data') . 'mc_albums.php';
        include_once Epi::getPath('data') . 'mc_ig_media.php';
        include_once Epi::getPath('data') . 'mc_likes.php';
        include_once Epi::getPath('data') . 'db_albums_subscribers.php';
        include_once Epi::getPath('data') . 'db_albums_ig_media.php';
        include_once Epi::getPath('data') . 'db_likes.php';
        include_once Epi::getPath('lib') . 'data_handler.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Albums = new MC_Albums();
        $MC_IG_Media = new MC_IG_Media();
        $MC_Likes = new MC_Likes();

        $DB_Albums_Subscribers = new DB_Albums_Subscribers();
        $DB_Albums_IG_Media = new DB_Albums_IG_Media();

        $DataHandler = new DataHandler();
        $Validator = new Validator();

        $response = array();
        $post = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $albums_subscribers_ids = array();
        $is_subscribed = false;

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('id_album', 'id_ig_media'));
            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
            }
        }

        if (empty($response)) {
            $r_select = $DB_Albums_Subscribers->select($id_subscriber);
            if ($r_select['success']) {
                $albums_subscribers_ids = $r_select['albums_subscribers_ids'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            foreach ($albums_subscribers_ids as $album_subscriber_ids) {
                if ($post['id_album'] === $album_subscriber_ids['id_album'])
                    $is_subscribed = true;
            }

            if (!$is_subscribed) {
                $response['success'] = false;
                $response['message'] = t('error008');
            }
        }

        if (empty($response)) {
            $album_ig_media_data = array(
                'id_album' => $post['id_album'],
                'id_ig_media' => $post['id_ig_media']
            );
            $r_insert = $DB_Albums_IG_Media->insert($album_ig_media_data);

            if (!$r_insert['success']) {
                $response = $r_insert;
            }
        }

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = getSession()->get('access_token');

            $r_getMedia = $MC_IG_Media->getMedia($post['id_ig_media'],  true, $request_data);
            if ($r_getMedia['success']) {
                $update_data = array(
                    'thumbnail' => $DataHandler->thumbnail(array($r_getMedia['media_data']))
                );
                $MC_Albums->updateAlbum($post['id_album'], $update_data);
            }
            
            $MC_Likes->insertAlbum($id_subscriber, $post['id_ig_media'], $post['id_album']);
            
            $response['success'] = true;
            $response['message'] = t('ok021');
        }

        return $response;
    }
    
    public function deleteMedia($ID_ALBUM, $ID_IG_MEDIA) {
        include_once Epi::getPath('data') . 'mc_likes.php';
        include_once Epi::getPath('data') . 'db_albums_subscribers.php';
        include_once Epi::getPath('data') . 'db_albums_ig_media.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $MC_Likes = new MC_Likes();
        
        $DB_Albums_Subscribers = new DB_Albums_Subscribers();
        $DB_Albums_IG_Media = new DB_Albums_IG_Media();
        
        $Validator = new Validator();
        
        $response = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $albums_subscribers_ids = array();
        $is_subscribed = false;
        
        $response = $Validator->verifySession();
        
        if (empty($response)) {
            $r_select = $DB_Albums_Subscribers->select($id_subscriber);
            if ($r_select['success']) {
                $albums_subscribers_ids = $r_select['albums_subscribers_ids'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            foreach ($albums_subscribers_ids as $album_subscriber_ids) {
                if ($ID_ALBUM === $album_subscriber_ids['id_album'])
                    $is_subscribed = true;
            }

            if (!$is_subscribed) {
                $response['success'] = false;
                $response['message'] = t('error008');
            }
        }
        
        if (empty($response)) {
            $r_delete = $DB_Albums_IG_Media->delete($ID_ALBUM, $ID_IG_MEDIA);
            
            if (!$r_delete['success']) {
                $response = $r_delete;
            }
        }
        
        if (empty($response)) {
            $response =  $MC_Likes->deleteAlbum($id_subscriber, $ID_IG_MEDIA, $ID_ALBUM);
        }
        
        return $response;
    }
    
}