<?php

class Route_Likes {

    public function deleteRoot($ID_IG_PHOTO) {
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $Instagram = new Instagram();

        $response = array();
        
        $response = $Validator->verifySession();
        
        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = getSession()->get('access_token');
            
            $r_deleteLike = $Instagram->deletePhotoLike($ID_IG_PHOTO, $request_data);
            
            if (200 !== $r_deleteLike['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_deleteLike['meta']['error_message'];
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok006');
        }
        
        return $response;
    }

    public function postRoot() {
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $post = array();
        
        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('id_ig_photo'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = getSession()->get('access_token');
            
            
            $r_postLike = $Instagram->postPhotoLike($post['id_ig_photo'], $request_data);
            
            if (200 !== $r_postLike['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_postLike['meta']['error_message'];
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok006');
        }
        
        return $response;
    }
    
}