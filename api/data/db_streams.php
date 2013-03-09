<?php

class DB_Streams {

    protected $_name = 'streams';

    public function insert($DATA) {
        include_once Epi::getPath('lib') . 'validator.php';

        $Validator = new Validator();

        $response = array();
        $data = array();

        if (empty($response)) {
            $r_getDataParams = $Validator->getDataParams(array(
                'stream', 'identifier', 'title'
                    ), $DATA);

            if (!$r_getDataParams['success']) {
                $response = $r_getDataParams;
            } else {
                $data = $r_getDataParams['data'];
            }
        }

        if (empty($response)) {
            $id_stream = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(stream, identifier, title) VALUES(:stream, :identifier, :title)', $data
            );

            $response['success'] = true;
            $response['message'] = t('ok008') . $id_stream;
            $response['id_stream'] = $id_stream;
        }

        return $response;
    }

    public function selectWhereStreamIdentifier($STREAM, $IDENTIFIER) {
        $response = array();

        $stream = $STREAM;
        if (empty($response) && empty($stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "STREAM " . t('txt003') . "DB_Streams->selectWhereStreamIdentifier()";
        }

        $identifier = $IDENTIFIER;
        if (empty($response) && empty($identifier)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "IDENTIFIER " . t('txt003') . "DB_Streams->selectWhereStreamIdentifier()";
        }

        if (empty($response)) {
            $stream = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE stream=:stream AND  identifier=:identifier', array(':stream' => $stream, ':identifier' => $identifier));

            if (empty($stream)) {
                $response['success'] = false;
                $response['message'] = t('error007') . $stream . ' ' . $identifier;
            } else {
                $response['success'] = true;
                $response['message'] = t('ok007') . $stream . ' ' . $identifier;
                $response['stream_data'] = $stream;
            }
        }

        return $response;
    }
    
}