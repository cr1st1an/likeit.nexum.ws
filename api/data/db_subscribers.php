<?php

class DB_Subscribers {

    protected $_name = 'subscribers';

    public function insert($DATA) {
        $response = array();
        $id_subscriber = null;
        
        $id_ig_user = (int) $DATA['id_ig_user'];
        if (empty($response) && empty($id_ig_user)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_IG_USER " . t('txt003') . "DB_Subscribers->insert()";
        }
        
        if (empty($response)) {
            $select_data = array(
                'id_ig_user' => $id_ig_user
            );
            $subscriber_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_ig_user=:id_ig_user', $select_data);

            if (empty($subscriber_data)) {
                $insert_data = array(
                    'id_ig_user' => $id_ig_user,
                    'created' => date("Y-m-d H:i:s")
                );
                $id_subscriber = getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_ig_user, created) VALUES(:id_ig_user, :created)', $insert_data
                );
            } else {
                $id_subscriber = $subscriber_data['id_subscriber'];
            }

            $response['success'] = true;
            $response['message'] = t('ok002') . $id_subscriber;
            $response['id_subscriber'] = $id_subscriber;
        }

        return $response;
    }
    
}