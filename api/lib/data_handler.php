<?php

class DataHandler {
    
    public function thumbnail($FEED_DATA){
        $thumbnail = null;
        
        if(isset($FEED_DATA[0]['images']['low_resolution']['url']))
            $thumbnail = $FEED_DATA[0]['images']['low_resolution']['url'];
        
        return $thumbnail;
    }
    
    public function mediaFeed($FEED_DATA) {
        include_once Epi::getPath('data') . 'db_likes.php';
        
        $DB_Likes = new DB_Likes();
        
        $media_data = array();
        $id_subscriber = getSession()->get('id_subscriber');

        foreach ($FEED_DATA as $key => $data) {
            $media_data[$key]['id_ig_media'] = $data['id'];
            $media_data[$key]['caption'] = $data['caption']['text'];
            $media_data[$key]['liked'] = $data['user_has_liked'];
            
            $media_data[$key]['author'] = array();
            $media_data[$key]['author']['id'] = $data['user']['id'];
            $media_data[$key]['author']['username'] = $data['user']['username'];
            $media_data[$key]['author']['profile_picture'] = $data['user']['profile_picture'];
            
            $media_data[$key]['location'] = array();
            $media_data[$key]['location']['id'] = $data['location']['id'];
            $media_data[$key]['location']['name'] = $data['location']['name'];
            
            $media_data[$key]['urls'] = array();
            $media_data[$key]['urls']['150'] = $data['images']['thumbnail']['url'];
            $media_data[$key]['urls']['306'] = $data['images']['low_resolution']['url'];
            $media_data[$key]['urls']['612'] = $data['images']['standard_resolution']['url'];
            
            $media_data[$key]['tags'] = $data['tags'];
            
            $media_data[$key]['likes'] = array();
            $media_data[$key]['likes']['count'] = $data['likes']['count'];
            $media_data[$key]['likes']['data'] = array();
            
            foreach($data['likes']['data'] as $key_like => $data_like){
                $media_data[$key]['likes']['data'][$key_like]['id'] = $data_like['id'];
                $media_data[$key]['likes']['data'][$key_like]['username'] = $data_like['username'];
                $media_data[$key]['likes']['data'][$key_like]['profile_picture'] = $data_like['profile_picture'];
            }
            
            $r_selectLikes = $DB_Likes->select($id_subscriber, $data['id']);
            
            $media_data[$key]['albums'] = array();
            foreach($r_selectLikes['likes_data'] as $like_data){
                $media_data[$key]['albums'][] = $like_data['id_album'];
            }
        }
        
        return $media_data;
    }
    
    public function searchResults($KIND, $RESULTS_DATA) {
        $results_data = array();

        foreach ($RESULTS_DATA as $key => $data) {
            $results_data[$key]['kind'] = $KIND;
            switch($KIND){
                case 'tags':
                    $results_data[$key]['id'] = $data['name'];
                    $results_data[$key]['text'] = '#'.$data['name'];
                    break;
                case 'users':
                    $results_data[$key]['id'] = $data['id'];
                    $results_data[$key]['text'] = '@'.$data['username'];
                    $results_data[$key]['photo'] = $data['profile_picture'];
                    break;
                case 'locations':
                    $results_data[$key]['id'] = $data['id'];
                    $results_data[$key]['text'] = $data['name'];
                    break;
            }
        }
        
        return $results_data;
    }

}