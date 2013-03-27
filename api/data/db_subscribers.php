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

    public function select($ID_SUBSCRIBER) {
        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Subscribers->select()";
        }
        
        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber
            );
            $subscriber_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber', $select_data
            );

            $response['success'] = true;
            $response['message'] = t('ok035') . $id_subscriber;
            $response['subscriber_data'] = $subscriber_data;
        }

        return $response;
    }
    
    public function updateDeviceToken($ID_SUBSCRIBER, $DEVICE_TOKEN){
        $response = array();
        
        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Subscribers->updateDeviceToken()";
        }
        
        $device_token = $DEVICE_TOKEN;
        if (empty($response) && empty($device_token)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "DEVICE_TOKEN " . t('txt003') . "DB_Subscribers->updateDeviceToken()";
        }
        
        if (empty($response)) {
            $update_data = array(
                'id_subscriber' => $id_subscriber,
                'updated' => date("Y-m-d H:i:s"),
                'device_token' => $device_token
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, device_token=:device_token WHERE id_subscriber=:id_subscriber', $update_data);
            
            $response['success'] = true;
            $response['message'] = t('ok036') . $id_subscriber;
        }
        
        return $response;
    }
    
    public function updateEmail($ID_SUBSCRIBER, $EMAIL){
        $response = array();
        
        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Subscribers->updateEmail()";
        }
        
        $email = $EMAIL;
        if (empty($response) && empty($email)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "EMAIL " . t('txt003') . "DB_Subscribers->updateEmail()";
        }
        
        if (empty($response)) {
            $update_data = array(
                'id_subscriber' => $id_subscriber,
                'updated' => date("Y-m-d H:i:s"),
                'email' => $email,
                'md5_invite' => null,
                'verified' => false
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, email=:email, md5_invite=:md5_invite, verified=:verified WHERE id_subscriber=:id_subscriber', $update_data);
            
            $response['success'] = true;
            $response['message'] = t('ok036') . $id_subscriber;
        }
        
        return $response;
    }
    
    public function updateInvite($ID_SUBSCRIBER, $MD5_INVITE){
        $response = array();
        
        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Subscribers->updateMd5Invite()";
        }
        
        $md5_invite = $MD5_INVITE;
        if (empty($response) && empty($md5_invite)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "MD5_INVITE " . t('txt003') . "DB_Subscribers->updateMd5Invite()";
        }
        
        if (empty($response)) {
            $update_data = array(
                'id_subscriber' => $id_subscriber,
                'updated' => date("Y-m-d H:i:s"),
                'md5_invite' => $md5_invite,
                'verified' => false
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, md5_invite=:md5_invite, verified=:verified WHERE id_subscriber=:id_subscriber', $update_data);
            
            $response['success'] = true;
            $response['message'] = t('ok036') . $id_subscriber;
        }
        
        return $response;
    }
    
    public function updateVerified($ID_SUBSCRIBER, $VERIFIED){
        $response = array();
        
        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_SUBSCRIBER " . t('txt003') . "DB_Subscribers->updateVerified()";
        }
        
        $verified = $VERIFIED;
        if (empty($response) && !isset($verified)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "MD5_INVITE " . t('txt003') . "DB_Subscribers->updateVerified()";
        }
        
        if (empty($response)) {
            $update_data = array(
                'id_subscriber' => $id_subscriber,
                'updated' => date("Y-m-d H:i:s"),
                'md5_invite' => null,
                'verified' => $verified
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, md5_invite=:md5_invite, verified=:verified WHERE id_subscriber=:id_subscriber', $update_data);
            
            $response['success'] = true;
            $response['message'] = t('ok036') . $id_subscriber;
        }
        
        return $response;
    }
    
    
    
}