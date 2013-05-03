<?php

class MC_IG_Oembed {

    protected $_name = 'MC_IG_Oembed';

    public function getOembed($LINK) {
        include_once Epi::getPath('lib') . 'instagram.php';
        
        $Instagram = new Instagram();
        
        $response = array();
        $oembed_data = array();
        
        if (empty($response)) {
            $key = $this->_name . md5($LINK);

            $cached_data = getCache()->get($key);
            if ($cached_data) {
                $oembed_data = $cached_data;
            } else {
                $oembed_data = $Instagram->getOembed($LINK);
                if(null !== $oembed_data) {
                    getCache()->set($key, $oembed_data);
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error016') . ' [MEMCACHED]';
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok041') . ' [MEMCACHED]';
            $response['oembed_data'] = $oembed_data;
        }
        
        return $response;
    }
    
}