<?php

class Route_Streams {

    public function test() {
        include_once Epi::getPath('lib') . 'instagram.php';

        $Instagram = new Instagram();

        $request_data = array();
        $request_data['access_token'] = '806569.8c8f279.1f0c39aafdc14d3b85b5ab8860022294';
        $request_data['count'] = 100;
        
        $response = $Instagram->getUserPhotosRecent('3000558', $request_data);
        
        print_r($response);
    }

    public function getRoot() {
        include_once Epi::getPath('data') . 'mc_streams.php';
        include_once Epi::getPath('data') . 'db_streams_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Streams = new MC_Streams();
        $DB_Streams_Subscribers = new DB_Streams_Subscribers();

        $Validator = new Validator();

        $response = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $user = getSession()->get('user');

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_select = $DB_Streams_Subscribers->select($id_subscriber);

            if (!$r_select['success']) {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            $streams_data = $r_select['streams_data'];

            foreach ($streams_data as $id => $stream_data) {
                $r_getStream = $MC_Streams->getStream($stream_data['stream'], $stream_data['identifier']);

                if ($r_getStream['success']) {
                    $streams_data[$id]['thumbnail'] = $r_getStream['stream_data']['thumbnail'];
                }
            }

            $response['success'] = true;
            $response['message'] = t('ok011');
            $response['streams_data'] = $streams_data;
        }

        return $response;
    }

    public function postRoot() {
        include_once Epi::getPath('data') . 'db_streams.php';
        include_once Epi::getPath('data') . 'db_streams_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Streams = new DB_Streams();
        $DB_Streams_Subscribers = new DB_Streams_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $id_stream = '';
        $id_subscriber = getSession()->get('id_subscriber');

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('stream', 'identifier', 'title'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $r_selectWhereStreamIdentifier = $DB_Streams->selectWhereStreamIdentifier($post['stream'], $post['identifier']);
            if (!$r_selectWhereStreamIdentifier['success']) {
                $stream_data = array(
                    'stream' => $post['stream'],
                    'identifier' => $post['identifier'],
                    'title' => $post['title']
                );
                $r_insert_1 = $DB_Streams->insert($stream_data);

                if (!$r_insert_1['success']) {
                    $response = $r_insert_1;
                } else {
                    $id_stream = $r_insert_1['id_stream'];
                    $stream_data['id_stream'] = $id_stream;
                }
            } else {
                $id_stream = $r_selectWhereStreamIdentifier['stream_data']['id_stream'];
                $stream_data = $r_selectWhereStreamIdentifier['stream_data'];
            }
        }

        if (empty($response)) {
            $stream_subscriber_data = array(
                'id_stream' => $id_stream,
                'id_subscriber' => $id_subscriber
            );
            $r_insert_2 = $DB_Streams_Subscribers->insert($stream_subscriber_data);

            if (!$r_insert_2['success']) {
                $response = $r_insert_2;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok008');
            $response['stream_data'] = $stream_data;
        }

        return $response;
    }

    public function deleteRoot($ID_STREAM) {
        include_once Epi::getPath('data') . 'db_streams_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Streams_Subscribers = new DB_Streams_Subscribers();

        $Validator = new Validator();

        $response = array();
        $id_subscriber = getSession()->get('id_subscriber');

        $response = $Validator->verifySession();

        if (empty($response)) {
            $response = $DB_Streams_Subscribers->delete($ID_STREAM, $id_subscriber);
        }

        return $response;
    }

    public function getInstagram() {
        include_once Epi::getPath('data') . 'mc_streams.php';
        include_once Epi::getPath('lib') . 'data_cleaner.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Streams = new MC_Streams();

        $DataCleaner = new DataCleaner();
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $params = array();
        $get = array();
        $photos_data = array();
        $next_max_key = 'next_max_id';
        $next_max_id = null;
        $id_ig_user = getSession()->get('id_ig_user');

        $response = $Validator->verifySession();

        if (empty($response)) {

            $params[] = 'stream';
            switch ($_GET['stream']) {
                case 'user':
                case 'tag':
                case 'location':
                    $params[] = 'identifier';
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
            $request_data['count'] = 100;

            if (isset($_GET['max_id']))
                $request_data['max_id'] = $_GET['max_id'];

            switch ($get['stream']) {
                case 'feed':
                    $r_getPhotos = $Instagram->getUserPhotosFeed($request_data);
                    break;
                case 'liked':
                    $r_getPhotos = $Instagram->getUserPhotosLiked($request_data);
                    $next_max_key = 'next_max_like_id';
                    break;
                case 'location':
                    $r_getPhotos = $Instagram->getLocationPhotosRecent($get['identifier'], $request_data);
                    break;
                case 'popular':
                    $r_getPhotos = $Instagram->getPhotosPopular($request_data);
                    break;
                case 'self':
                    $r_getPhotos = $Instagram->getUserPhotosRecent('self', $request_data);
                    break;
                case 'tag':
                    $r_getPhotos = $Instagram->getTagPhotosRecent($get['identifier'], $request_data);
                    $next_max_key = 'next_max_tag_id';
                    break;
                case 'user':
                    $r_getPhotos = $Instagram->getUserPhotosRecent($get['identifier'], $request_data);
                    break;
            }

            if (200 !== $r_getPhotos['meta']['code']) {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getPhotos['meta']['error_message'];
            } else {
                $photos_data = $DataCleaner->photoFeed($r_getPhotos['data']);

                if (isset($r_getPhotos['pagination'][$next_max_key]))
                    $next_max_id = $r_getPhotos['pagination'][$next_max_key];

                if (empty($request_data['max_id']))
                    $MC_Streams->updateStream($get['stream'], $get['identifier'], array('thumbnail' => $DataCleaner->thumbnail($photos_data)));
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok005');
            $response['stream'] = $get['stream'];
            $response['identifier'] = $get['identifier'];
            $response['next_max_id'] = $next_max_id;
            $response['photos_data'] = $photos_data;
        }

        return $response;
    }

    public function getSearch() {
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

            $params[] = 'stream';
            switch ($_GET['stream']) {
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

            switch ($get['stream']) {
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
                $results_data = $DataCleaner->searchResults($get['stream'], $r_getResults['data']);
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok010');
            $response['stream'] = $get['stream'];
            $response['results_data'] = $results_data;
        }

        return $response;
    }

}