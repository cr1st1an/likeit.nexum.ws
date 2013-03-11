<?php

class DB_Streams_Subscribers {

    protected $_name = 'streams_subscribers';

    public function insert($DATA) {
        $response = array();
        
        $id_stream = (int) $DATA['id_stream'];
        if (empty($response) && empty($id_stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_STREAM " . t('txt003') . "DB_Streams_Subscribers->insert()";
        }

        $id_subscriber = (int) $DATA['id_subscriber'];
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Streams_Subscribers->insert()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_stream' => $id_stream,
                'id_subscriber' => $id_subscriber
            );
            $stream_subscriber_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_stream=:id_stream AND  id_subscriber=:id_subscriber', $select_data);

            if (empty($stream_subscriber_data)) {
                $insert_data = array(
                    'id_stream' => $id_stream,
                    'id_subscriber' => $id_subscriber
                );
                getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_stream, id_subscriber) VALUES(:id_stream, :id_subscriber)', $insert_data
                );
            }

            $response['success'] = true;
            $response['message'] = t('ok009');
        }

        return $response;
    }

    public function select($ID_SUBSCRIBER) {
        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Streams_Subscribers->select()";
        }

        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber
            );
            $streams_subscribers_ids = getDatabase()->all('SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber', $select_data);

            $response['success'] = true;
            $response['message'] = t('ok011') . $id_subscriber;
            $response['streams_subscribers_ids'] = $streams_subscribers_ids;
        }

        return $response;
    }

    public function delete($ID_STREAM, $ID_SUBSCRIBER) {
        $response = array();

        $id_stream = (int) $ID_STREAM;
        if (empty($response) && empty($id_stream)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_STREAM " . t('txt003') . "DB_Streams_Subscribers->delete()";
        }

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Streams_Subscribers->delete()";
        }

        if (empty($response)) {
            $delete_data = array(
                'id_stream' => $id_stream,
                'id_subscriber' => $id_subscriber
            );
            getDatabase()->execute('DELETE FROM ' . $this->_name . ' WHERE id_stream=:id_stream AND id_subscriber=:id_subscriber', $delete_data);

            $response['success'] = true;
            $response['message'] = t('ok012') . $id_stream;
        }

        return $response;
    }

}