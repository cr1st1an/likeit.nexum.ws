<?php

class DB_Subscribers {

    protected $_name = 'subscribers';

    public function insertValuesIdIG($ID_IG_USER) {
        $response = array();
        
        $id_ig_user = (int) $ID_IG_USER;
        if (empty($response) && empty($id_ig_user)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_USER ". t('txt003') . "DB_Subscribers->insert()";
        }
        
        if (empty($response)) {
            $id_subscriber = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(created, id_ig_user) VALUES(:created, :id_ig_user)', array(':created' => date("Y-m-d H:i:s"), ':id_ig_user' => $id_ig_user)
            );
            
            $response['success'] = true;
            $response['message'] = t('ok002') . $id_subscriber;
            $response['id_subscriber'] = $id_subscriber;
        }
        
        return $response;
    }

    public function selectWhereIdIG($ID_IG_USER) {
        $response = array();
        
        $id_ig_user = (int) $ID_IG_USER;
        if (empty($response) && empty($id_ig_user)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_USER ". t('txt003') . "DB_Subscribers->selectWhereIdInstagram()";
        }
        
        if (empty($response)) {
            $subscriber = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_ig_user=:id_ig_user', array(':id_ig_user' => $id_ig_user));

            if (empty($subscriber)) {
                $response['success'] = false;
                $response['message'] = t('error004') . $id_ig_user;
            } else {
                $response['success'] = true;
                $response['message'] = t('ok003') . $id_ig_user;
                $response['subscriber_data'] = $subscriber;
            }
        }
        
        return $response;
    }

    public function update() {
        
    }

    public function delete() {
        
    }

}