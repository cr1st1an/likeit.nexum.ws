<?php

class DB_Albums_Subscribers {

    protected $_name = 'albums_subscribers';

    public function insert($DATA) {
        $response = array();

        $id_album = (int) $DATA['id_album'];
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "DB_Albums_Subscribers->insert()";
        }

        $id_subscriber = (int) $DATA['id_subscriber'];
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Albums_Subscribers->insert()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_album' => $id_album,
                'id_subscriber' => $id_subscriber
            );
            $albums_subscribers_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_album=:id_album AND  id_subscriber=:id_subscriber', $select_data
            );

            if (empty($albums_subscribers_data)) {
                $insert_data = array(
                    'id_album' => $id_album,
                    'id_subscriber' => $id_subscriber
                );
                getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_album, id_subscriber) VALUES(:id_album, :id_subscriber)', $insert_data
                );
            } else {
                $update_data = array(
                    'id_album' => $id_album,
                    'id_subscriber' => $id_subscriber,
                    'active' => true
                );
                getDatabase()->execute('UPDATE ' . $this->_name . ' SET active=:active WHERE id_album=:id_album AND id_subscriber=:id_subscriber', $update_data);
            }

            $response['success'] = true;
            $response['message'] = t('ok019');
        }

        return $response;
    }

    public function select($ID_SUBSCRIBER) {
        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Albums_Subscribers->select()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber
            );
            $albums_subscribers_ids = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber AND active = true', $select_data
            );

            $response['success'] = true;
            $response['message'] = t('ok020') . $id_subscriber;
            $response['albums_subscribers_ids'] = $albums_subscribers_ids;
        }

        return $response;
    }

    public function delete($ID_ALBUM, $ID_SUBSCRIBER) {
        $response = array();

        $id_album = (int) $ID_ALBUM;
        if (empty($response) && empty($id_album)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_ALBUM " . t('txt003') . "DB_Albums_Subscribers->delete()";
        }

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Albums_Subscribers->delete()";
        }

        if (empty($response)) {
            $update_data = array(
                'id_album' => $id_album,
                'id_subscriber' => $id_subscriber,
                'active' => false
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET active=:active WHERE id_album=:id_album AND id_subscriber=:id_subscriber', $update_data);

            $response['success'] = true;
            $response['message'] = t('ok037') . $id_album;
        }
        
        return $response;
    }

}