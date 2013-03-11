<?php

class MC_Albums {

    protected $_name = 'Albums_';

    public function getAlbum($ID_ALBUM) {
        include_once Epi::getPath('data') . 'db_albums.php';

        $DB_Albums = new DB_Albums();

        $response = array();
        $album_data = array();

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "MC_Albums->getAlbum()";
        }

        if (empty($response)) {
            $key = $this->_name . $id_album;

            $cached_data = getCache()->get($key);
            if ($cached_data) {
                $album_data = $cached_data;
            } else {
                $r_select = $DB_Albums->select($id_album);
                if ($r_select['success']) {
                    $album_data = $r_select['album_data'];
                    getCache()->set($key, $album_data);
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error010') . ' $id_album: ' . $id_album . ' [MEMCACHED]';
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok025') . ' $id_album: ' . $id_album . ' [MEMCACHED]';
            $response['album_data'] = $album_data;
        }

        return $response;
    }

    public function updateAlbum($ID_ALBUM, $DATA) {
        $response = array();

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "MC_Albums->updateAlbum()";
        }

        $data = $DATA;
        if (empty($response) && !is_array($data)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "DATA " . t('txt003') . "MC_Albums->updateAlbum()";
        }
        
        if (empty($response)) {
            $key = $this->_name . $id_album;
            $album_data = array();

            $cached_data = getCache()->get($key);

            if ($cached_data) {
                $album_data = $cached_data;
                foreach ($data as $data_key => $data_value) {
                    $album_data[$data_key] = $data_value;
                }
                getCache()->set($key, $album_data);
            }

            $response['success'] = true;
            $response['message'] = t('ok026') . ' $id_album: ' . $id_album . ' [MEMCACHED]';
        }
        
        return $response;
    }

}