<?php

class MC_Streams {

    protected $_name = 'Streams_';

    public function getStream($ID_STREAM, $FETCH_SOURCE = true) {
        include_once Epi::getPath('data') . 'db_streams.php';

        $DB_Streams = new DB_Streams();

        $response = array();
        $stream_data = array();

        $id_stream = (int) $ID_STREAM;
        if (empty($response) && empty($id_stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_STREAM " . t('txt003') . "MC_Streams->getStream()";
        }

        if (empty($response)) {
            $key = $this->_name . $id_stream;

            $cached_data = getCache()->get($key);
            if ($cached_data) {
                $stream_data = $cached_data;
            } else if($FETCH_SOURCE) {
                $r_select = $DB_Streams->select($id_stream);
                if ($r_select['success']) {
                    $stream_data = $r_select['stream_data'];
                    getCache()->set($key, $stream_data);
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
            $this->setID($stream_data['stream'], $stream_data['identifier'], $id_stream);
            
            $response['success'] = true;
            $response['message'] = t('ok007') . ' $id_stream: ' . $id_stream . ' [MEMCACHED]';
            $response['stream_data'] = $stream_data;
        }

        return $response;
    }

    public function updateStream($ID_STREAM, $DATA) {
        $response = array();

        $id_stream = (int) $ID_STREAM;
        if (empty($response) && empty($id_stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "STREAM " . t('txt003') . "MC_Streams->updateStream()";
        }

        $data = $DATA;
        if (empty($response) && !is_array($data)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "DATA " . t('txt003') . "MC_Streams->updateStream()";
        }

        if (empty($response)) {
            $key = $this->_name . $id_stream;
            $stream_data = array();

            $cached_data = getCache()->get($key);

            if ($cached_data) {
                $stream_data = $cached_data;
                foreach ($data as $data_key => $data_value) {
                    $stream_data[$data_key] = $data_value;
                }
                getCache()->set($key, $stream_data);
            }
            
            $response['success'] = true;
            $response['message'] = t('ok013') . ' $id_stream: ' . $id_stream . ' [MEMCACHED]';
        }

        return $response;
    }

    public function getID($STREAM, $IDENTIFIER) {
        $response = array();
        $id_stream = null;

        $stream = $STREAM;
        if (empty($response) && empty($stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "STREAM " . t('txt003') . "MC_Streams->getID()";
        }

        $identifier = $IDENTIFIER;
        if (empty($response) && empty($identifier)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "IDENTIFIER " . t('txt003') . "MC_Streams->getID()";
        }

        if (empty($response)) {
            $key = $this->_name . $stream . '_' . $identifier;

            $cached_data = getCache()->get($key);

            if ($cached_data) {
                $id_stream = $cached_data;
            } else {
                $response['success'] = false;
                $response['message'] = t('error011') . ' $stream: ' . $stream . ' $identifier: ' . $identifier . ' [MEMCACHED]';
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok028') . ' $stream: ' . $stream . ' $identifier: ' . $identifier . ' [MEMCACHED]';
            $response['id_stream'] = $id_stream;
        }

        return $response;
    }

    public function setID($STREAM, $IDENTIFIER, $ID_STREAM) {
        $response = array();

        $stream = $STREAM;
        if (empty($response) && empty($stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "STREAM " . t('txt003') . "MC_Streams->setID()";
        }

        $identifier = $IDENTIFIER;
        if (empty($response) && empty($identifier)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "IDENTIFIER " . t('txt003') . "MC_Streams->setID()";
        }

        $id_stream = (int) $ID_STREAM;
        if (empty($response) && empty($id_stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_STREAM " . t('txt003') . "MC_Streams->setID()";
        }
        
        if (empty($response)) {
            $key = $this->_name . $stream . '_' . $identifier;
            getCache()->set($key, $id_stream);

            $response['success'] = true;
            $response['message'] = t('ok029') . ' $stream: ' . $stream . ' $identifier: ' . $identifier . ' [MEMCACHED]';
        }
        
        return $response;
    }

}