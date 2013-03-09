<?php

class DB_Albums_IG_Media {

    protected $_name = 'albums_ig_media';

    public function insert($DATA) {
        include_once Epi::getPath('lib') . 'validator.php';

        $Validator = new Validator();

        $response = array();
        $data = array();

        if (empty($response)) {
            $r_getDataParams = $Validator->getDataParams(array(
                'id_album', 'id_ig_media'
                    ), $DATA);

            if (!$r_getDataParams['success']) {
                $response = $r_getDataParams;
            } else {
                $data = $r_getDataParams['data'];
            }
        }

        if (empty($response)) {
            $album_ig_media = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_album=:id_album AND  id_ig_media=:id_ig_media', $data);

            if (empty($album_ig_media)) {
                $data[':created'] = date("Y-m-d H:i:s");
                getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_album, id_ig_media, created) VALUES(:id_album, :id_ig_media, :created)', $data
                );
            }

            $response['success'] = true;
            $response['message'] = t('ok021');
        }
        
        return $response;
    }
    
    public function select($ID_ALBUM){
        $response = array();
        
        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM ". t('txt003') . "DB_Albums_IG_Media->select()";
        }
        
        if (empty($response)) {
            $ids_data = getDatabase()->all('SELECT * FROM ' . $this->_name . ' WHERE id_album=:id_album ORDER BY created DESC', array(':id_album' => $id_album));

            $response['success'] = true;
            $response['message'] = t('ok022') . $id_subscriber;
            $response['ids_data'] = $ids_data;
        }
        
        return $response;
    }

}