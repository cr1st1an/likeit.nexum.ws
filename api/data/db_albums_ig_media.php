<?php

class DB_Albums_IG_Media {

    protected $_name = 'albums_ig_media';

    public function insert($DATA) {
        $response = array();

        $id_album = (int) $DATA['id_album'];
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "DB_Albums_IG_Media->insert()";
        }

        $id_ig_media = $DATA['id_ig_media'];
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "DB_Albums_IG_Media->insert()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_album' => $id_album,
                'id_ig_media' => $id_ig_media
            );
            $albums_ig_media_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_album=:id_album AND  id_ig_media=:id_ig_media', $select_data
            );

            if (empty($albums_ig_media_data)) {
                $insert_data = array(
                    'id_album' => $id_album,
                    'id_ig_media' => $id_ig_media,
                    'created' => date("Y-m-d H:i:s")
                );
                getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_album, id_ig_media, created) VALUES(:id_album, :id_ig_media, :created)', $insert_data
                );
            }

            $response['success'] = true;
            $response['message'] = t('ok021');
        }

        return $response;
    }

    public function select($ID_ALBUM) {
        $response = array();

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "DB_Albums_IG_Media->select()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_album' => $id_album
            );
            $albums_ig_media_ids = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_album=:id_album ORDER BY created DESC', $select_data
            );

            $response['success'] = true;
            $response['message'] = t('ok022') . $id_album;
            $response['albums_ig_media_ids'] = $albums_ig_media_ids;
        }

        return $response;
    }
    
    public function delete($ID_ALBUM, $ID_IG_MEDIA) {
        $response = array();

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "DB_Albums_IG_Media->delete()";
        }

        $id_ig_media = $ID_IG_MEDIA;
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "DB_Albums_IG_Media->delete()";
        }

        if (empty($response)) {
            $delete_data = array(
                'id_album' => $id_album,
                'id_ig_media' => $id_ig_media
            );
            getDatabase()->execute('DELETE FROM ' . $this->_name . ' WHERE id_album=:id_album AND id_ig_media=:id_ig_media', $delete_data);

            $response['success'] = true;
            $response['message'] = t('ok031') . $id_album;
        }
        
        return $response;
    }
    
}