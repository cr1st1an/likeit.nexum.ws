<?php

class Route_Relationships {
    
    public function deleteRoot($ID_IG_OTHER_USER) {
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        
        $response = $Validator->verifySession();
        $access_token = getSession()->get('access_token');
        
        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $access_token;
            $request_data['action'] = 'unfollow';
            
            $r_postUserRelationship = $Instagram->postUserRelationship($ID_IG_OTHER_USER, $request_data);
            
            if (200 !== $r_postUserRelationship['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_postUserRelationship['meta']['error_message'];
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok017');
        }
        
        return $response;
    }
    
    public function getRoot(){
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $Instagram = new Instagram();
        $Validator = new Validator();
        
        $response = array();
        $get = array();
        $access_token = getSession()->get('access_token');
        
        $response = $Validator->verifySession();
        
        if (empty($response)) {
            $r_getGetParams = $Validator->getGetParams(array('id_ig_other_user'));

            if ($r_getGetParams['success']) {
                $get = $r_getGetParams['get'];
            } else {
                $response = $r_getGetParams;
            }
        }
        
        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $access_token;
            
            
            $r_getUserRelationship = $Instagram->getUserRelationship($get['id_ig_other_user'], $request_data);
            
            if (200 !== $r_getUserRelationship['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getUserRelationship['meta']['error_message'];
            }
        }
        
        if (empty($response)) {
            $relationship_data = array();
            $relationship_data['id_ig_other_user'] = $get['id_ig_other_user'];
            $relationship_data['outgoing'] = $r_getUserRelationship['data']['outgoing_status'];
            $relationship_data['incoming'] = $r_getUserRelationship['data']['incoming_status'];
            
            $response['success'] = true;
            $response['message'] = t('ok014');
            $response['relationship_data'] = $relationship_data;
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
        $access_token = getSession()->get('access_token');
        
        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('id_ig_other_user'));

            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
            }
        }

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $access_token;
            $request_data['action'] = 'follow';
            
            $r_postUserRelationship = $Instagram->postUserRelationship($post['id_ig_other_user'], $request_data);
            
            if (200 !== $r_postUserRelationship['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_postUserRelationship['meta']['error_message'];
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok015');
        }
        
        return $response;
    }
}