<?php

class DataCleaner {

    public function photoFeed($FEED_DATA) {
        $photos_data = array();

        foreach ($FEED_DATA as $key => $data) {
            $photos_data[$key]['id'] = $data['id'];
            $photos_data[$key]['caption'] = $data['caption']['text'];
            
            $photos_data[$key]['author'] = array();
            $photos_data[$key]['author']['id'] = $data['user']['id'];
            $photos_data[$key]['author']['username'] = $data['user']['username'];
            $photos_data[$key]['author']['profile_picture'] = $data['user']['profile_picture'];
            
            $photos_data[$key]['location'] = array();
            $photos_data[$key]['location']['id'] = $data['location']['id'];
            $photos_data[$key]['location']['name'] = $data['location']['name'];
            
            $photos_data[$key]['urls'] = array();
            $photos_data[$key]['urls']['150'] = $data['images']['thumbnail']['url'];
            $photos_data[$key]['urls']['306'] = $data['images']['low_resolution']['url'];
            $photos_data[$key]['urls']['612'] = $data['images']['standard_resolution']['url'];
            
            $photos_data[$key]['tags'] = $data['tags'];
            
            $photos_data[$key]['likes'] = array();
            $photos_data[$key]['likes']['count'] = $data['likes']['count'];
            foreach($data['likes']['data'] as $key_like => $data_like){
                $photos_data[$key]['likes'][$key_like]['id'] = $data_like['id'];
                $photos_data[$key]['likes'][$key_like]['profile_picture'] = $data_like['profile_picture'];
            }
            
        }
        
        return $photos_data;
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