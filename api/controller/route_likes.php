<?php

class Route_Likes {

    public function deleteRoot($ID_IG_MEDIA) {
        include_once Epi::getPath('data') . 'mc_likes.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Likes = new MC_Likes();

        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();

        $response = $Validator->verifySession();
        $id_subscriber = getSession()->get('id_subscriber');
        $access_token = getSession()->get('access_token');

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $access_token;

            $r_deleteLike = $Instagram->deleteMediaLike($ID_IG_MEDIA, $request_data);

            if (200 !== $r_deleteLike['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_deleteLike['meta']['error_message'];
            }
        }

        if (empty($response)) {
            $MC_Likes->deleteAlbum($id_subscriber, $ID_IG_MEDIA, 0);

            $response['success'] = true;
            $response['message'] = t('ok016');
        }

        return $response;
    }

    public function postRoot() {
        include_once Epi::getPath('data') . 'mc_ig_media.php';
        include_once Epi::getPath('data') . 'mc_likes.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_IG_Media = new MC_IG_Media();
        $MC_Likes = new MC_Likes();

        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $post = array();

        $response = $Validator->verifySession();
        $id_subscriber = getSession()->get('id_subscriber');
        $access_token = getSession()->get('access_token');

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('id_ig_media'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $access_token;

            $r_postLike = $Instagram->postMediaLike($post['id_ig_media'], $request_data);

            if (200 !== $r_postLike['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_postLike['meta']['error_message'];
            }
        }

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $access_token;

            $MC_Likes->insertAlbum($id_subscriber, $post['id_ig_media'], 0);
            $MC_IG_Media->getMedia($post['id_ig_media'], true, $request_data);

            $response['success'] = true;
            $response['message'] = t('ok006');
        }

        return $response;
    }

    public function getTrending() {
        include_once Epi::getPath('data') . 'mc_ig_media.php';
        include_once Epi::getPath('data') . 'db_likes.php';
        include_once Epi::getPath('lib') . 'data_handler.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_IG_Media = new MC_IG_Media();
        $DB_Likes = new DB_Likes();

        $DataHandler = new DataHandler();

        $response = array();
        $likes_data = array();

        if (empty($response)) {
            $r_select = $DB_Likes->selectTrending();
            if ($r_select['success']) {
                $likes_data = $r_select['likes_data'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            $media_data = array();

            foreach ($likes_data as $like_data) {
                $r_getMedia = $MC_IG_Media->getMedia($like_data['id_ig_media'], false);

                if ($r_getMedia['success']) {
                    $media_data[] = $r_getMedia['media_data'];
                }
            }

            $response['success'] = true;
            $response['message'] = t('ok033');
            $response['origin'] = 'likes';
            $response['media_data'] = $DataHandler->mediaFeed($media_data);
        }

        return $response;
    }

}