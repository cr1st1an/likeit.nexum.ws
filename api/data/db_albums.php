<?php

class DB_Albums {

    protected $_name = 'albums';

    public function insert($DATA) {
        $response = array();

        $title = $DATA['title'];
        if (empty($response) && empty($title)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "TITLE " . t('txt003') . "DB_Albums->insert()";
        }

        if (empty($response)) {
            $insert_data = array(
                'title' => $title
            );

            $id_album = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(title) VALUES(:title)', $insert_data
            );

            $response['success'] = true;
            $response['message'] = t('ok018') . $id_album;
            $response['id_album'] = $id_album;
        }

        return $response;
    }

    public function select($ID_ALBUM) {
        $response = array();
        $album_data = array();

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "DB_Albums->select()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_album' => $id_album
            );
            $album_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_album=:id_album', $select_data
            );

            if (empty($album_data)) {
                $response['success'] = false;
                $response['message'] = t('error010') . $id_album;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok030') . $id_album;
            $response['album_data'] = $album_data;
        }

        return $response;
    }
    
}