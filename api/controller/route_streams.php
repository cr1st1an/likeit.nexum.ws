<?php

class Route_Streams {

//    public function test() {
//        include_once Epi::getPath('lib') . 'instagram.php';
//
//        $Instagram = new Instagram();
//
//        $request_data = array();
//        $request_data['access_token'] = '806569.8c8f279.1f0c39aafdc14d3b85b5ab8860022294';
//        $request_data['count'] = 100;
//        
//        $response = $Instagram->getUserMediaRecent('3000558', $request_data);
//        
//        print_r($response);
//    }

    public function getRoot() {
        include_once Epi::getPath('data') . 'mc_streams.php';
        include_once Epi::getPath('data') . 'db_streams_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Streams = new MC_Streams();
        $DB_Streams_Subscribers = new DB_Streams_Subscribers();

        $Validator = new Validator();

        $response = array();
        $id_subscriber = getSession()->get('id_subscriber');
        $streams_subscribers_ids = array();
        $streams_data = array();

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_select = $DB_Streams_Subscribers->select($id_subscriber);

            if ($r_select['success']) {
                $streams_subscribers_ids = $r_select['streams_subscribers_ids'];
            } else {
                $response = $r_select;
            }
        }

        if (empty($response)) {
            foreach ($streams_subscribers_ids as $id => $stream_subscriber_ids) {
                $r_getStream = $MC_Streams->getStream($stream_subscriber_ids['id_stream']);

                if ($r_getStream['success']) {
                    $streams_data[$id] = $r_getStream['stream_data'];
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
        $id_stream = null;
        $id_subscriber = getSession()->get('id_subscriber');
        $stream_data = array();

        $response = $Validator->verifySession();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('stream', 'identifier', 'title'));

            if ($r_getPostParams['success']) {
                $post = $r_getPostParams['post'];
            } else {
                $response = $r_getPostParams;
            }
        }

        if (empty($response)) {
            $stream_data = array(
                'stream' => $post['stream'],
                'identifier' => $post['identifier'],
                'title' => $post['title']
            );
            $r_insert = $DB_Streams->insert($stream_data);
            if ($r_insert['success']) {
                $id_stream = $r_insert['id_stream'];
                $stream_data['id_stream'] = $id_stream;
            } else {
                $response['success'] = false;
                $response['message'] = t('error002') . serialize($post);
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
        include_once Epi::getPath('lib') . 'data_handler.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_Streams = new MC_Streams();

        $DataHandler = new DataHandler();
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $params = array();
        $get = array();
        $media_data = array();
        $next_max_key = 'next_max_id';
        $next_max_id = null;
        $access_token = getSession()->get('access_token');

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

            if ($r_getGetParams['success']) {
                $get = $r_getGetParams['get'];
            } else {
                $response = $r_getGetParams;
            }
        }

        if (empty($response)) {
            $request_data = array();
            $request_data['access_token'] = $access_token;
            $request_data['count'] = 100;

            if (isset($_GET['max_id']))
                $request_data['max_id'] = $_GET['max_id'];

            switch ($get['stream']) {
                case 'feed':
                    $r_getMedia = $Instagram->getUserMediaFeed($request_data);
                    break;
                case 'liked':
                    $r_getMedia = $Instagram->getUserMediaLiked($request_data);
                    $next_max_key = 'next_max_like_id';
                    break;
                case 'location':
                    $r_getMedia = $Instagram->getLocationMediaRecent($get['identifier'], $request_data);
                    break;
                case 'popular':
                    $r_getMedia = $Instagram->getMediaPopular($request_data);
                    break;
                case 'self':
                    $r_getMedia = $Instagram->getUserMediaRecent('self', $request_data);
                    break;
                case 'tag':
                    $r_getMedia = $Instagram->getTagMediaRecent($get['identifier'], $request_data);
                    $next_max_key = 'next_max_tag_id';
                    break;
                case 'user':
                    $r_getMedia = $Instagram->getUserMediaRecent($get['identifier'], $request_data);
                    break;
            }

            if (200 === $r_getMedia['meta']['code']) {
                $media_data = $r_getMedia['data'];
                if (isset($r_getMedia['pagination'][$next_max_key]))
                    $next_max_id = $r_getMedia['pagination'][$next_max_key];
            } else {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getMedia['meta']['error_message'];
            }
        }

        if (empty($response)) {
            if (empty($_GET['max_id'])) {
                $r_getId = $MC_Streams->getID($get['stream'], $get['identifier']);
                if ($r_getId['success']) {
                    $update_data = array(
                        'thumbnail' => $DataHandler->thumbnail($media_data)
                    );
                    $MC_Streams->updateStream($r_getId['id_stream'], $update_data);
                }
            }

            $response['success'] = true;
            $response['message'] = t('ok005');
            $response['stream'] = $get['stream'];
            $response['identifier'] = $get['identifier'];
            $response['next_max_id'] = $next_max_id;
            $response['media_data'] = $DataHandler->mediaFeed($media_data);
        }

        return $response;
    }

    public function getSearch() {
        include_once Epi::getPath('lib') . 'data_handler.php';
        include_once Epi::getPath('lib') . 'instagram.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DataHandler = new DataHandler();
        $Instagram = new Instagram();
        $Validator = new Validator();

        $response = array();
        $params = array();
        $get = array();
        $results_data = array();
        $access_token = getSession()->get('access_token');

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

            if ($r_getGetParams['success']) {
                $get = $r_getGetParams['get'];
            } else {
                $response = $r_getGetParams;
            }
        }

        if (empty($response)) {

            $request_data = array();
            $request_data['access_token'] = $access_token;

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

            if (200 === $r_getResults['meta']['code']) {
                $results_data = $DataHandler->searchResults($get['stream'], $r_getResults['data']);
            } else {
                $response['success'] = false;
                $response['message'] = t('error001') . $r_getResults['meta']['error_message'];
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