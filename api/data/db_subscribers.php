<?php

class DB_Subscribers {

    protected $_name = 'subscribers';

    public function insertValuesIdInstagram($ID_INSTAGRAM) {
        $response = array();
        
        $id_instagram = (int) $ID_INSTAGRAM;
        if (empty($response) && empty($id_instagram)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_INSTAGRAM ". t('txt003') . "DB_Subscribers->insert()";
        }
        
        if (empty($response)) {
            $id_subscriber = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(created, id_instagram) VALUES(:created, :id_instagram)', array(':created' => date("Y-m-d H:i:s"), ':id_instagram' => $id_instagram)
            );
            
            $response['success'] = true;
            $response['message'] = t('ok002') . $id_subscriber;
            $response['id_subscriber'] = $id_subscriber;
        }
        
        return $response;
    }

    public function selectWhereIdInstagram($ID_INSTAGRAM) {
        $response = array();
        
        $id_instagram = (int) $ID_INSTAGRAM;
        if (empty($response) && empty($id_instagram)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_INSTAGRAM ". t('txt003') . "DB_Subscribers->selectWhereIdInstagram()";
        }
        
        if (empty($response)) {
            $subscriber = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_instagram=:id_instagram', array(':id_instagram' => $id_instagram));

            if (empty($subscriber)) {
                $response['success'] = false;
                $response['message'] = t('error004') . $id_instagram;
            } else {
                $response['success'] = true;
                $response['message'] = t('ok003') . $id_instagram;
                $response['data_subscriber'] = $subscriber;
            }
        }
        
        return $response;
    }

    public function update($ID_SUBSCRIBER, $DATA) {
        
    }

    public function delete() {
        
    }

}