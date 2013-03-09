<?php

class DB_Albums {

    protected $_name = 'albums';

    public function insert($DATA) {
        include_once Epi::getPath('lib') . 'validator.php';

        $Validator = new Validator();

        $response = array();
        $data = array();

        if (empty($response)) {
            $r_getDataParams = $Validator->getDataParams(array(
                'title'
                    ), $DATA);

            if (!$r_getDataParams['success']) {
                $response = $r_getDataParams;
            } else {
                $data = $r_getDataParams['data'];
            }
        }

        if (empty($response)) {
            $id_album = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(title) VALUES(:title)', $data
            );

            $response['success'] = true;
            $response['message'] = t('ok018') . $id_album;
            $response['id_album'] = $id_album;
        }

        return $response;
    }
//
//    public function selectWhereStreamIdentifier($STREAM, $IDENTIFIER) {
//        $response = array();
//
//        $stream = $STREAM;
//        if (empty($response) && empty($stream)) {
//            $response['success'] = false;
//            $response['message'] = t('error003') . "STREAM " . t('txt003') . "DB_Streams->selectWhereStreamIdentifier()";
//        }
//
//        $identifier = $IDENTIFIER;
//        if (empty($response) && empty($identifier)) {
//            $response['success'] = false;
//            $response['message'] = t('error003') . "IDENTIFIER " . t('txt003') . "DB_Streams->selectWhereStreamIdentifier()";
//        }
//
//        if (empty($response)) {
//            $stream = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE stream=:stream AND  identifier=:identifier', array(':stream' => $stream, ':identifier' => $identifier));
//
//            if (empty($stream)) {
//                $response['success'] = false;
//                $response['message'] = t('error007') . $stream . ' ' . $identifier;
//            } else {
//                $response['success'] = true;
//                $response['message'] = t('ok007') . $stream . ' ' . $identifier;
//                $response['stream_data'] = $stream;
//            }
//        }
//
//        return $response;
//    }
    
}