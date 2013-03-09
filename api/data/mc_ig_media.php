<?php

class MC_IG_Media {

    protected $_name = 'MC_IG_Media_';
    
    public function getPhoto($ID) {
        $response = array();
        $photo_data = array();

        $id = $ID;
        if (empty($response) && empty($id)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID " . t('txt003') . "MC_IG_Media->getPhoto()";
        }
        
        if (empty($response)) {
            $key = $this->_name . $id;
            
            $cached_data = getCache()->get($key);
            if (!$cached_data) {
                $response['success'] = false;
                $response['message'] = t('error009') . ' $id: ' . $id . ' [MEMCACHED]';
            } else {
                $photo_data = $cached_data;
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok023') . ' $id: ' . $id . ' [MEMCACHED]';
            $response['photo_data'] = $photo_data;
        }
        
        return $response;
    }
    
    public function setPhoto($ID, $DATA) {
        $response = array();
        $photo_data = array();

        $id = $ID;
        if (empty($response) && empty($id)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID " . t('txt003') . "MC_IG_Media->setPhoto()";
        }
        
        $data = $DATA;
        if (empty($response) && !is_array($data)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "DATA " . t('txt003') . "MC_IG_Media->setPhoto()";
        }
        
        if (empty($response)) {
            $key = $this->_name . $id;
            
            getCache()->set($key, $data);
            
            $response['success'] = true;
            $response['message'] = t('ok013') . ' $stream: ' . $id . ' [MEMCACHED]';
        }
        
        return $response;
    }
    
}