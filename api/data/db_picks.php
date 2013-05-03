<?php

class DB_Picks {

    protected $_name = 'picks';
    
    public function select($ID_PICK) {
        $response = array();
        $pick_data = array();
        
        $id_pick = (int) $ID_PICK;
        if (empty($response) && empty($id_pick)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_PICK " . t('txt003') . "DB_Picks->select()";
        }
        
        if (empty($response)) {
            $select_data = array(
                'id_pick' => $id_pick
            );
            $pick_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_pick=:id_pick', $select_data);
            
            if(empty($pick_data)){
                $response['success'] = false;
                $response['message'] = t('error017') . $id_pick;
            }
        }
        
        if(empty($response)){
            $response['success'] = true;
            $response['message'] = t('ok042') . $id_pick;
            $response['pick_data'] = $pick_data;
        }
        
        return $response;
    }
    
    public function selectAll() {
        $response = array();
        
        if (empty($response)) {
            $picks_data = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE status = 1 ORDER BY publish DESC'
            );
            
            $response['success'] = true;
            $response['message'] = t('ok040');
            $response['picks_data'] = $picks_data;
        }
        
        return $response;
    }
    
}