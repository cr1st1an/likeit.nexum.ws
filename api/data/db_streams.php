<?php

class DB_Streams {

    protected $_name = 'streams';

    public function insert($DATA) {
        $response = array();
        $id_stream = null;

        $stream = $DATA['stream'];
        if (empty($response) && empty($stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "STREAM " . t('txt003') . "DB_Streams->insert()";
        }

        $identifier = $DATA['identifier'];
        if (empty($response) && empty($identifier)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "IDENTIFIER " . t('txt003') . "DB_Streams->insert()";
        }

        if (empty($response)) {
            $select_data = array(
                'stream' => $stream,
                'identifier' => $identifier
            );
            $stream_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE stream=:stream AND identifier=:identifier', $select_data);

            if (empty($stream_data)) {
                $insert_data = array(
                    'stream' => $stream,
                    'identifier' => $identifier,
                    'title' => $DATA['title']
                );
                $id_stream = getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(stream, identifier, title) VALUES(:stream, :identifier, :title)', $insert_data
                );
            } else {
                $id_stream = $stream_data['id_stream'];
            }

            $response['success'] = true;
            $response['message'] = t('ok008') . $id_stream;
            $response['id_stream'] = $id_stream;
        }

        return $response;
    }

    public function select($ID_STREAM) {
        $response = array();
        $stream_data = array();
        
        $id_stream = (int) $ID_STREAM;
        if (empty($response) && empty($id_stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_STREAM " . t('txt003') . "DB_Streams->select()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_stream' => $id_stream
            );
            $stream_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_stream=:id_stream', $select_data);
            
            if(empty($stream_data)){
                $response['success'] = false;
                $response['message'] = t('error007') . $id_stream;
            }
        }
        
        if(empty($response)){
            $response['success'] = true;
            $response['message'] = t('ok027') . $id_stream;
            $response['stream_data'] = $stream_data;
        }
        
        return $response;
    }

}