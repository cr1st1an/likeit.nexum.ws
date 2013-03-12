<?php

class MC_Likes {

    protected $_name = 'Likes_';

    public function getAlbums($ID_SUBSCRIBER, $ID_IG_MEDIA, $FETCH_SOURCE = true) {
        include_once Epi::getPath('data') . 'db_likes.php';

        $DB_Likes = new DB_Likes();
        
        $response = array();
        $likes_data = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "MC_Likes->getAlbums()";
        }

        $id_ig_media = $ID_IG_MEDIA;
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "MC_Likes->getAlbums()";
        }

        if (empty($response)) {
            $key = $this->_name . $id_subscriber . '_' . $id_ig_media;

            $cached_data = getCache()->get($key);
            if ($cached_data) {
                $likes_data = $cached_data;
            } else if ($FETCH_SOURCE) {
                $r_select = $DB_Likes->select($id_subscriber, $id_ig_media);
                if ($r_select['success']) {
                    $likes_data = $r_select['likes_data'];
                    getCache()->set($key, $likes_data);
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error007') . ' $id_stream: ' . $id_stream . ' [MEMCACHED]';
                }
            } else {
                $response['success'] = false;
                $response['message'] = t('error007') . ' $id_stream: ' . $id_stream . ' [MEMCACHED]';
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok028') . ' $id_subscriber: ' . $id_subscriber . ' [MEMCACHED]';
            $response['likes_data'] = $likes_data;
        }

        return $response;
    }

    public function insertAlbum($ID_SUBSCRIBER, $ID_IG_MEDIA, $ID_ALBUM) {
        include_once Epi::getPath('data') . 'db_likes.php';

        $DB_Likes = new DB_Likes();

        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "MC_Likes->insertAlbum()";
        }

        $id_ig_media = $ID_IG_MEDIA;
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "MC_Likes->insertAlbum()";
        }

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "MC_Likes->insertAlbum()";
        }

        if (empty($response)) {
            $insert_data = array(
                'id_subscriber' => $id_subscriber,
                'id_ig_media' => $id_ig_media,
                'id_album' => $id_album
            );
            $r_insert = $DB_Likes->insert($insert_data);

            if (!$r_insert['success']) {
                $response = $r_insert;
            }
        }

        if (empty($response)) {
            $key = $this->_name . $id_subscriber . '_' . $id_ig_media;
            $likes_data = array();

            $r_select = $DB_Likes->select($id_subscriber, $id_ig_media);
            if ($r_select['success']) {
                $likes_data = $r_select['likes_data'];
                getCache()->set($key, $likes_data);
            }

            $response['success'] = true;
            $response['message'] = t('ok026') . ' $id_subscriber: ' . $id_subscriber . ' [MEMCACHED]';
        }

        return $response;
    }

    public function deleteAlbum($ID_SUBSCRIBER, $ID_IG_MEDIA, $ID_ALBUM) {
        include_once Epi::getPath('data') . 'db_likes.php';

        $DB_Likes = new DB_Likes();

        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "MC_Likes->deleteAlbum()";
        }

        $id_ig_media = $ID_IG_MEDIA;
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "MC_Likes->deleteAlbum()";
        }

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "MC_Likes->deleteAlbum()";
        }

        if (empty($response)) {
            $r_delete = $DB_Likes->delete($id_subscriber, $id_ig_media, $id_album);

            if (!$r_delete['success']) {
                $response = $r_delete;
            }
        }
        
        if (empty($response)) {
            $key = $this->_name . $id_subscriber . '_' . $id_ig_media;
            $likes_data = array();
            
            $r_select = $DB_Likes->select($id_subscriber, $id_ig_media);
            if ($r_select['success']) {
                $likes_data = $r_select['likes_data'];
                getCache()->set($key, $likes_data);
            }
            
            $response['success'] = true;
            $response['message'] = t('ok026') . ' $id_subscriber: ' . $id_subscriber . ' [MEMCACHED]';
        }

        return $response;
    }

}