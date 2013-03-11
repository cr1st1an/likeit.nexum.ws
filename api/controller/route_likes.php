<?php

class Route_Likes {

    public function deleteRoot($ID_IG_MEDIA) {
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $access_token = getSession()->get('access_token');
        
        $response = $Validator->verifySession();
        
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
            $response['success'] = true;
            $response['message'] = t('ok016');
        }
        
        return $response;
    }

    public function postRoot() {
        include_once Epi::getPath('data') . 'db_likes.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $DB_Likes = new DB_Likes();
        
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $post = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $access_token = getSession()->get('access_token');
        
        $response = $Validator->verifySession();

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
            $insert_data = array(
                'id_subscriber' => $id_subscriber,
                'id_ig_media' => $post['id_ig_media'],
                'id_album' => 0
            );
            $DB_Likes->insert($insert_data);
            
            $response['success'] = true;
            $response['message'] = t('ok006');
        }
        
        return $response;
    }
    
}