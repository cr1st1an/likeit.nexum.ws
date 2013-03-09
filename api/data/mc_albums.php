<?php

class MC_Albums {

    protected $_name = 'Albums_';

    public function getAlbum($ID) {
        $response = array();
        $album_data = array();
        
        $id = $ID;
        if (empty($response) && empty($id)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID " . t('txt003') . "MC_Albums->getAlbum()";
        }
        
        if (empty($response)) {
            $key = $this->_name . $id;
            
            $cached_data = getCache()->get($key);
            if (!$cached_data) {
                $response['success'] = false;
                $response['message'] = t('ok025') . ' $id: ' . $id . ' [MEMCACHED]';
            } else {
                $album_data = $cached_data;
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok007') . ' $id: ' . $id . ' [MEMCACHED]';
            $response['album_data'] = $album_data;
        }

        return $response;
    }
    
    public function updateAlbum($ID, $DATA) {
        $response = array();
        
        $id = $ID;
        if (empty($response) && empty($id)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID " . t('txt003') . "MC_Albums->updateAlbum()";
        }

        $data = $DATA;
        if (empty($response) && !is_array($data)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "DATA " . t('txt003') . "MC_Albums->updateAlbum()";
        }
        
        if (empty($response)) {
            $key =  $this->_name . $id;
            $album_data = array();

            $cached_data = getCache()->get($key);

            if ($cached_data) {
                $album_data = $cached_data;
                foreach ($data as $data_key => $data_value) {
                    $album_data[$data_key] = $data_value;
                }
            } else {
                $album_data = $data;
            }
            
            getCache()->set($key, $album_data);
            
            $response['success'] = true;
            $response['message'] = t('ok026') . ' $stream: ' . ' $id: ' . $id . ' [MEMCACHED]';
            $response['album_data'] = $album_data;
        }
        
        return $response;
    }

}