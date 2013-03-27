<?php

class MC_IG_Media {

    protected $_name = 'MC_IG_Media_';

    public function getMedia($ID_IG_MEDIA, $FETCH_SOURCE, $REQUEST_DATA = array()) {
        include_once Epi::getPath('lib') . 'instagram.php';

        $Instagram = new Instagram();

        $response = array();
        $media_data = array();

        $id_ig_media = $ID_IG_MEDIA;
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "MC_IG_Media->getMedia()";
        }

        if (empty($response)) {
            $key = $this->_name . $id_ig_media;

            $cached_data = getCache()->get($key);
            if ($cached_data) {
                $media_data = $cached_data;
            } else if ($FETCH_SOURCE) {
                $r_getMedia = $Instagram->getMedia($id_ig_media, $REQUEST_DATA);
                if (200 === $r_getMedia['meta']['code']) {
                    $media_data = $r_getMedia['data'];
                    getCache()->set($key, $media_data);
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error009') . ' $id_ig_media: ' . $id_ig_media . ' [MEMCACHED]';
                }
            } else {
                $response['success'] = false;
                $response['message'] = t('error009') . ' $id_ig_media: ' . $id_ig_media . ' [MEMCACHED]';
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok023') . ' $id_ig_media: ' . $id_ig_media . ' [MEMCACHED]';
            $response['media_data'] = $media_data;
        }

        return $response;
    }

}