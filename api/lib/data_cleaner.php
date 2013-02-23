<?php

class DataCleaner {

    public function photoFeed($FEED_DATA) {
        $photos_data = array();

        foreach ($FEED_DATA as $key => $data) {
            $photos_data[$key]['id'] = $data['id'];
            $photos_data[$key]['src_150'] = $data['images']['thumbnail']['url'];
            $photos_data[$key]['src_306'] = $data['images']['low_resolution']['url'];
            $photos_data[$key]['src_612'] = $data['images']['standard_resolution']['url'];
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