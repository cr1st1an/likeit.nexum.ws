<?php

class DataCleaner {

    public function photoFeed($FEED_DATA) {
        $data_photos = array();

        foreach ($FEED_DATA as $key => $data) {
            $data_photos[$key]['id'] = $data['id'];
            $data_photos[$key]['src_306'] = $data['images']['low_resolution']['url'];
        }

        return $data_photos;
    }

    public function searchResults($KIND, $RESULTS_DATA) {
        $data_results = array();

        foreach ($RESULTS_DATA as $key => $data) {
            $data_results[$key]['kind'] = $KIND;
            switch($KIND){
                case 'tags':
                    $data_results[$key]['id'] = $data['name'];
                    $data_results[$key]['text'] = '#'.$data['name'];
                    break;
                case 'users':
                    $data_results[$key]['id'] = $data['id'];
                    $data_results[$key]['text'] = '@'.$data['username'];
                    $data_results[$key]['photo'] = $data['profile_picture'];
                    break;
                case 'locations':
                    $data_results[$key]['id'] = $data['id'];
                    $data_results[$key]['text'] = $data['name'];
                    break;
            }
        }
        
        return $data_results;
    }

}