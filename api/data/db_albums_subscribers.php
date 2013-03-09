<?php

class DB_Albums_Subscribers {

    protected $_name = 'albums_subscribers';
    
    public function insert($DATA) {
        include_once Epi::getPath('lib') . 'validator.php';

        $Validator = new Validator();

        $response = array();
        $data = array();

        if (empty($response)) {
            $r_getDataParams = $Validator->getDataParams(array(
                'id_album', 'id_subscriber'
                    ), $DATA);

            if (!$r_getDataParams['success']) {
                $response = $r_getDataParams;
            } else {
                $data = $r_getDataParams['data'];
            }
        }

        if (empty($response)) {
            $album_subscriber = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_album=:id_album AND  id_subscriber=:id_subscriber', $data);

            if (empty($album_subscriber)) {
                getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_album, id_subscriber) VALUES(:id_album, :id_subscriber)', $data
                );
            }
            
            $response['success'] = true;
            $response['message'] = t('ok019');
        }
        
        return $response;
    }
    
    public function select($ID_SUBSCRIBER){
        $response = array();
        
        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER ". t('txt003') . "DB_Albums_Subscribers->select()";
        }
        
        if (empty($response)) {
            $albums_data = getDatabase()->all('SELECT * FROM ' . $this->_name . ' LEFT JOIN albums ON (' . $this->_name . '.id_album = albums.id_album) WHERE ' . $this->_name . '.id_subscriber=:id_subscriber ORDER BY title ASC', array(':id_subscriber' => $id_subscriber));

            $response['success'] = true;
            $response['message'] = t('ok020') . $id_subscriber;
            $response['albums_data'] = $albums_data;
        }
        
        return $response;
    }
//    
//    public function delete($ID_STREAM, $ID_SUBSCRIBER){
//        $response = array();
//        
//        $id_stream = (int) $ID_STREAM;
//        if (empty($response) && empty($id_stream)) {
//            $response['success'] = false;
//            $response['message'] = t('error003') . "ID_STREAM ". t('txt003') . "DB_Streams_Subscribers->delete()";
//        }
//        
//        $id_subscriber = (int) $ID_SUBSCRIBER;
//        if (empty($response) && empty($id_subscriber)) {
//            $response['success'] = false;
//            $response['message'] = t('error003') . "ID_SUBSCRIBER ". t('txt003') . "DB_Streams_Subscribers->delete()";
//        }
//        
//        if (empty($response)) {
//            $streams_data = getDatabase()->execute('DELETE FROM ' . $this->_name . ' WHERE id_stream=:id_stream AND id_subscriber=:id_subscriber', array(':id_stream' => $id_stream, ':id_subscriber' => $id_subscriber));
//
//            $response['success'] = true;
//            $response['message'] = t('ok012') . $id_stream;
//        }
//        
//        return $response;
//    }
    
}