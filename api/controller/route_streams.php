<?php

class Route_Streams {

    public function getRoot() {

        include_once Epi::getPath('lib') . 'instagram.php';
        
        $Instagram = new Instagram();
        
        $request_data = array();
        $request_data['access_token'] = '806569.8c8f279.1f0c39aafdc14d3b85b5ab8860022294';
        
        $r_getPhotos = $Instagram->getUserPhotosLiked($request_data);
        
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
        $photos_data = array();
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
            $request_data = array();
            $request_data['access_token'] = getSession()->get('access_token');
            $request_data['count'] = 24;

            if (isset($_GET['max_id']))
                $request_data['max_id'] = $_GET['max_id'];

            switch ($get['kind']) {
                case 'popular':
                    $r_getPhotos = $Instagram->getPhotosPopular($request_data);
                    break;
                case 'feed':
                    $r_getPhotos = $Instagram->getUserPhotosFeed($request_data);
                    break;
                case 'liked':
                    $r_getPhotos = $Instagram->getUserPhotosLiked($request_data);
                    $next_max_key = 'next_max_like_id';
                    break;
                case 'user':
                    $r_getPhotos = $Instagram->getUserPhotosRecent($get['id'], $request_data);
                    break;
                case 'tag':
                    $r_getPhotos = $Instagram->getTagPhotosRecent($get['id'], $request_data);
                    $next_max_key = 'next_max_tag_id';
                    break;
                case 'location':
                    $r_getPhotos = $Instagram->getLocationPhotosRecent($get['id'], $request_data);
                    break;
            }

            if (200 !== $r_getPhotos['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getPhotos['meta']['error_message'];
            } else {
                $photos_data = $DataCleaner->photoFeed($r_getPhotos['data']);
                if(isset($r_getPhotos['pagination'][$next_max_key]))
                    $next_max_id = $r_getPhotos['pagination'][$next_max_key];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok005');
            $response['kind'] = $get['kind'];
            $response['photos_data'] = $photos_data;
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
        $results_data = array();

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

            $request_data = array();
            $request_data['access_token'] = getSession()->get('access_token');

            if (isset($get['q']))
                $request_data['q'] = $get['q'];

            if (isset($get['lat']))
                $request_data['lat'] = $get['lat'];
            if (isset($get['lng']))
                $request_data['lng'] = $get['lng'];

            switch ($get['kind']) {
                case 'users':
                    $r_getResults = $Instagram->getUsers($request_data);
                    break;
                case 'tags':
                    $r_getResults = $Instagram->getTags($request_data);
                    break;
                case 'locations':
                    $r_getResults = $Instagram->getLocations($request_data);
                    break;
            }

            if (200 !== $r_getResults['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getResults['meta']['error_message'];
            } else {
                $results_data = $DataCleaner->searchResults($get['kind'], $r_getResults['data']);
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok005');
            $response['kind'] = $get['kind'];
            $response['results_data'] = $results_data;
        }

        return $response;
    }

}