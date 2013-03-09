<?php

class Route_Albums {

    public function getRoot() {
        include_once Epi::getPath('data') . 'mc_albums.php';
        include_once Epi::getPath('data') . 'mc_ig_media.php';
        include_once Epi::getPath('data') . 'db_albums_ig_media.php';
        include_once Epi::getPath('data') . 'db_albums_subscribers.php';
        include_once Epi::getPath('lib') . 'data_cleaner.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Albums = new MC_Albums();
        $MC_IG_Media = new MC_IG_Media();
        $DB_Albums_IG_Media = new DB_Albums_IG_Media();
        $DB_Albums_Subscribers = new DB_Albums_Subscribers();

        $DataCleaner = new DataCleaner();
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $id_album = null;
        $albums_data = array();

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_select = $DB_Albums_Subscribers->select($id_subscriber);

            if (!$r_select['success']) {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            $albums_data = $r_select['albums_data'];
            
            foreach ($albums_data as $id => $album_data) {
                $r_getAlbum = $MC_Albums->getAlbum($album_data['id_album']);
                if ($r_getAlbum['success']) {
                    $albums_data[$id]['thumbnail'] = $r_getAlbum['album_data']['thumbnail'];
                }

                if ($_GET['id_album'] === $album_data['id_album']) {
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

            if (!$r_select['success']) {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            $ids_data = $r_select['ids_data'];
            $photos_data = array();

            foreach ($ids_data as $id => $id_data) {
                $r_getPhoto_1 = $MC_IG_Media->getPhoto($id_data['id_ig_media']);
                if ($r_getPhoto_1['success']) {
                    $photos_data[] = $r_getPhoto_1['photo_data'];
                } else {
                    $request_data = array();
                    $request_data['access_token'] = getSession()->get('access_token');
                    $r_getPhoto_2 = $Instagram->getPhoto($id_data['id_ig_media'], $request_data);
                    if (200 === $r_getPhoto_2['meta']['code']) {
                        $photos_data[] = $r_getPhoto_2['data'];
                        $MC_IG_Media->setPhoto($id_data['id_ig_media'], $r_getPhoto_2['data']);
                    }
                }
            }

            $photos_data = $DataCleaner->photoFeed($photos_data);

            $response['success'] = true;
            $response['message'] = t('ok022');
            $response['id_album'] = $id_album;
            $response['photos_data'] = $photos_data;
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
        $album_data = array();

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('title'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $album_data = array(
                'title' => $post['title']
            );
            $r_insert_1 = $DB_Albums->insert($album_data);

            if (!$r_insert_1['success']) {
                $response = $r_insert;
            } else {
                $album_data['id_album'] = $r_insert_1['id_album'];
            }
        }

        if (empty($response)) {
            $album_subscriber_data = array(
                'id_album' => $album_data['id_album'],
                'id_subscriber' => $id_subscriber
            );
            $r_insert_2 = $DB_Albums_Subscribers->insert($album_subscriber_data);

            if (!$r_insert_2['success']) {
                $response = $r_insert_2;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok008');
            $response['album_data'] = $album_data;
        }

        return $response;
    }

    public function postPhoto() {
        include_once Epi::getPath('data') . 'mc_albums.php';
        include_once Epi::getPath('data') . 'mc_ig_media.php';
        include_once Epi::getPath('data') . 'db_albums_subscribers.php';
        include_once Epi::getPath('data') . 'db_albums_ig_media.php';
        include_once Epi::getPath('lib') . 'data_cleaner.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Albums = new MC_Albums();
        $MC_IG_Media = new MC_IG_Media();

        $DB_Albums_Subscribers = new DB_Albums_Subscribers();
        $DB_Albums_IG_Media = new DB_Albums_IG_Media();

        $DataCleaner = new DataCleaner();
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $post = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $albums_data = array();
        $is_subscribed = false;

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('id_album', 'id_ig_media'));
            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $r_select = $DB_Albums_Subscribers->select($id_subscriber);
            if (!$r_select['success']) {
                $response = $r_select;
            } else {
                $albums_data = $r_select['albums_data'];
            }
        }

        if (empty($response)) {
            foreach ($albums_data as $album_data) {
                if ($post['id_album'] === $album_data['id_album'])
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
            $photos_data = array();
            $r_getPhoto_1 = $MC_IG_Media->getPhoto($post['id_ig_media']);

            if ($r_getPhoto_1['success']) {
                $photos_data[] = $r_getPhoto_1['photo_data'];
            } else {
                $request_data = array();
                $request_data['access_token'] = getSession()->get('access_token');
                $r_getPhoto_2 = $Instagram->getPhoto($post['id_ig_media'], $request_data);
                if (200 === $r_getPhoto_2['meta']['code']) {
                    $photos_data[] = $r_getPhoto_2['data'];
                    $MC_IG_Media->setPhoto($post['id_ig_media'], $r_getPhoto_2['data']);
                }
            }

            $photos_data = $DataCleaner->photoFeed($photos_data);
            $MC_Albums->updateAlbum($post['id_album'], array('thumbnail' => $DataCleaner->thumbnail($photos_data)));

            $response['success'] = true;
            $response['message'] = t('ok021');
        }

        return $response;
    }

}