<?php

class MC_Streams {

    protected $_name = 'Streams_';

    public function getStream($STREAM, $IDENTIFIER) {
        $response = array();
        $stream_data = array();

        $stream = $STREAM;
        if (empty($response) && empty($stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "STREAM " . t('txt003') . "MC_Streams->getStream()";
        }

        $identifier = $IDENTIFIER;
        if (empty($response) && empty($identifier)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "IDENTIFIER " . t('txt003') . "MC_Streams->getStream()";
        }

        if (empty($response)) {
            $key = $this->_name . $stream . '_' . $identifier;
            

            $cached_data = getCache()->get($key);
            if (!$cached_data) {
                $response['success'] = false;
                $response['message'] = t('error007') . ' $stream: ' . $stream . ' $identifier: ' . $identifier . ' [MEMCACHED]';
            } else {
                $stream_data = $cached_data;
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok007') . ' $stream: ' . $stream . ' $identifier: ' . $identifier . ' [MEMCACHED]';
            $response['stream_data'] = $stream_data;
        }

        return $response;
    }
    
    public function updateStream($STREAM, $IDENTIFIER, $DATA) {
        $response = array();

        $stream = $STREAM;
        if (empty($response) && empty($stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "STREAM " . t('txt003') . "MC_Streams->updateWhereStreamIdentifier()";
        }

        $identifier = $IDENTIFIER;
        if (empty($response) && empty($identifier)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "IDENTIFIER " . t('txt003') . "MC_Streams->updateWhereStreamIdentifier()";
        }

        $data = $DATA;
        if (empty($response) && !is_array($data)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "DATA " . t('txt003') . "MC_Streams->updateWhereStreamIdentifier()";
        }

        if (empty($response)) {
            $key = $this->_name . $stream . '_' . $identifier;
            $stream_data = array();

            $cached_data = getCache()->get($key);

            if ($cached_data) {
                $stream_data = $cached_data;
                foreach ($data as $data_key => $data_value) {
                    $stream_data[$data_key] = $data_value;
                }
            } else {
                $stream_data = $data;
            }
            
            getCache()->set($key, $stream_data);
            
            $response['success'] = true;
            $response['message'] = t('ok013') . ' $stream: ' . $stream . ' $identifier: ' . $identifier . ' [MEMCACHED]';
            $response['stream_data'] = $stream_data;
        }

        return $response;
    }

}