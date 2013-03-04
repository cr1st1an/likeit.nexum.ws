<?php

class DB_Sessions {

    protected $_name = 'sessions';

    public function insert($DATA) {
        include_once Epi::getPath('lib') . 'validator.php';

        $Validator = new Validator();

        $response = array();
        $data = array();

        if (empty($response)) {
            $r_getDataParams_1 = $Validator->getDataParams(array(
                'id_subscriber', 'id_ig_user', 'id_install', 'created', 'client', 'version', 'md5_code', 'access_token'
                    ), $DATA);

            if (!$r_getDataParams_1['success']) {
                $response = $r_getDataParams_1;
            } else {
                $data = $r_getDataParams_1['data'];
            }
        }

        if (empty($response)) {
            $id_session = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(id_subscriber, id_ig_user, id_install, created, client, version, md5_code, access_token) VALUES(:id_subscriber, :id_ig_user, :id_install, :created, :client, :version, :md5_code, :access_token)', $data
            );

            $response['success'] = true;
            $response['message'] = t('ok001') . $id_session;
            $response['id_session'] = $id_session;
        }

        return $response;
    }

    public function select() {
        
    }

    public function update() {
        
    }

    public function delete() {
        
    }

}