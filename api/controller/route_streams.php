<?php

class Route_Streams {

    public function getRoot() {

        include_once Epi::getPath('lib') . 'instagram.php';
        
        $Instagram = new Instagram();
        
        $data_request = array();
        $data_request['access_token'] = '806569.8c8f279.1f0c39aafdc14d3b85b5ab8860022294';
        
        $r_getPhotos = $Instagram->getUserPhotosLiked($data_request);
        
        print_r($r_getPhotos);
    }

    public function getInstagram() {
        include_once Epi::getPath('lib') . 'data_cleaner.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DataCleaner = new DataCleaner();
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $params = array();
        $get = array();
        $data_photos = array();
        $next_max_key = 'next_max_id';
        $next_max_id = null;

        $response = $Validator->verifySession();

        if (empty($response)) {

            $params[] = 'kind';
            switch ($_GET['kind']) {
                case 'user':
                case 'tag':
                case 'location':
                    $params[] = 'id';
                    break;
            }

            $r_getGetParams = $Validator->getGetParams($params);

            if (!$r_getGetParams['success']) {
                $response = $r_getGetParams;
            } else {
                $get = $r_getGetParams['get'];
            }
        }

        if (empty($response)) {
            $data_request = array();
            $data_request['access_token'] = getSession()->get('access_token');
            $data_request['count'] = 24;

            if (isset($_GET['max_id']))
                $data_request['max_id'] = $_GET['max_id'];

            switch ($get['kind']) {
                case 'popular':
                    $r_getPhotos = $Instagram->getPhotosPopular($data_request);
                    break;
                case 'feed':
                    $r_getPhotos = $Instagram->getUserPhotosFeed($data_request);
                    break;
                case 'liked':
                    $r_getPhotos = $Instagram->getUserPhotosLiked($data_request);
                    $next_max_key = 'next_max_like_id';
                    break;
                case 'user':
                    $r_getPhotos = $Instagram->getUserPhotosRecent($get['id'], $data_request);
                    break;
                case 'tag':
                    $r_getPhotos = $Instagram->getTagPhotosRecent($get['id'], $data_request);
                    $next_max_key = 'next_max_tag_id';
                    break;
                case 'location':
                    $r_getPhotos = $Instagram->getLocationPhotosRecent($get['id'], $data_request);
                    break;
            }

            if (200 !== $r_getPhotos['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getPhotos['meta']['error_message'];
            } else {
                $data_photos = $DataCleaner->photoFeed($r_getPhotos['data']);
                if(isset($r_getPhotos['pagination'][$next_max_key]))
                    $next_max_id = $r_getPhotos['pagination'][$next_max_key];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok005');
            $response['kind'] = $get['kind'];
            $response['data_photos'] = $data_photos;
            $response['next_max_id'] = $next_max_id;
        }

        return $response;
    }

    public function getSearch($KIND) {
        include_once Epi::getPath('lib') . 'data_cleaner.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DataCleaner = new DataCleaner();
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $params = array();
        $get = array();
        $data_results = array();

        $response = $Validator->verifySession();

        if (empty($response)) {

            $params[] = 'kind';
            switch ($_GET['kind']) {
                case 'users':
                case 'tags':
                    $params[] = 'q';
                    break;
                case 'locations':
                    $params[] = 'lat';
                    $params[] = 'lng';
                    break;
            }

            $r_getGetParams = $Validator->getGetParams($params);

            if (!$r_getGetParams['success']) {
                $response = $r_getGetParams;
            } else {
                $get = $r_getGetParams['get'];
            }
        }

        if (empty($response)) {

            $data_request = array();
            $data_request['access_token'] = getSession()->get('access_token');

            if (isset($get['q']))
                $data_request['q'] = $get['q'];

            if (isset($get['lat']))
                $data_request['lat'] = $get['lat'];
            if (isset($get['lng']))
                $data_request['lng'] = $get['lng'];

            switch ($get['kind']) {
                case 'users':
                    $r_getResults = $Instagram->getUsers($data_request);
                    break;
                case 'tags':
                    $r_getResults = $Instagram->getTags($data_request);
                    break;
                case 'locations':
                    $r_getResults = $Instagram->getLocations($data_request);
                    break;
            }

            if (200 !== $r_getResults['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getResults['meta']['error_message'];
            } else {
                $data_results = $DataCleaner->searchResults($get['kind'], $r_getResults['data']);
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok005');
            $response['kind'] = $get['kind'];
            $response['data_results'] = $data_results;
        }

        return $response;
    }

}