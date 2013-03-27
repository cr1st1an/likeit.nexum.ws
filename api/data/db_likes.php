<?php

class DB_Likes {

    protected $_name = 'likes';

    public function insert($DATA) {
        $response = array();

        $id_subscriber = (int) $DATA['id_subscriber'];
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Likes->insert()";
        }

        $id_ig_media = $DATA['id_ig_media'];
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "DB_Likes->insert()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber,
                'id_ig_media' => $id_ig_media,
                'id_album' => (int) $DATA['id_album']
            );
            $likes_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber AND  id_ig_media=:id_ig_media AND  id_album=:id_album', $select_data
            );

            if (empty($likes_data)) {
                $insert_data = array(
                    'id_subscriber' => $id_subscriber,
                    'id_ig_media' => $id_ig_media,
                    'id_album' => (int) $DATA['id_album'],
                    'created' => date("Y-m-d H:i:s")
                );
                getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_subscriber, id_ig_media, id_album, created) VALUES(:id_subscriber, :id_ig_media, :id_album, :created)', $insert_data
                );
            }

            $response['success'] = true;
            $response['message'] = t('ok021');
        }

        return $response;
    }

    public function select($ID_SUBSCRIBER, $ID_IG_MEDIA) {
        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Likes->select()";
        }

        $id_ig_media = $ID_IG_MEDIA;
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "DB_Likes->select()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber,
                'id_ig_media' => $id_ig_media,
            );
            $likes_data = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber AND id_ig_media=:id_ig_media', $select_data
            );

            $response['success'] = true;
            $response['message'] = t('ok022') . $id_subscriber . '_' . $id_ig_media;
            $response['likes_data'] = $likes_data;
        }
        
        return $response;
    }
    
    public function delete($ID_SUBSCRIBER, $ID_IG_MEDIA, $ID_ALBUM) {
        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Likes->delete()";
        }

        $id_ig_media = $ID_IG_MEDIA;
        if (empty($response) && empty($id_ig_media)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_MEDIA " . t('txt003') . "DB_Likes->delete()";
        }
        
        if (empty($response)) {
            $delete_data = array(
                'id_subscriber' => $id_subscriber,
                'id_ig_media' => $id_ig_media,
                'id_album' => (int) $ID_ALBUM
            );
            getDatabase()->execute('DELETE FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber AND id_ig_media=:id_ig_media AND id_album=:id_album', $delete_data);

            $response['success'] = true;
            $response['message'] = t('ok032') . $id_ig_media;
        }
        
        return $response;
    }

    public function selectTrending() {
        $response = array();
        
        if (empty($response)) {
            $likes_data = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_album = 0 GROUP BY id_ig_media ORDER BY RAND()'
            );

            $response['success'] = true;
            $response['message'] = t('ok033');
            $response['likes_data'] = $likes_data;
        }
        
        return $response;
    }
}