<?php

class MC_Picks {

    protected $_name = 'Picks_';

    public function getPick($ID_PICK, $FETCH_SOURCE = true, $LOAD_CACHED = true) {
        include_once Epi::getPath('data') . 'db_picks.php';

        $DB_Picks = new DB_Picks();

        $response = array();
        $pick_data = array();

        $id_pick = (int) $ID_PICK;
        if (empty($response) && empty($id_pick)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_PICK " . t('txt003') . "MC_Picks->getPick()";
        }

        if (empty($response)) {
            $key = $this->_name . $id_pick;

            $cached_data = getCache()->get($key);
            if ($cached_data && $LOAD_CACHED) {
                $pick_data = $cached_data;
            } else if ($FETCH_SOURCE) {
                $r_select = $DB_Picks->select($id_pick);
                if ($r_select['success']) {
                    $pick_data = $r_select['pick_data'];
                    getCache()->set($key, $pick_data);
                } else {
                    $response['success'] = false;
                    $response['message'] = t('error017') . ' $id_pick: ' . $id_pick . ' [MEMCACHED]';
                }
            } else {
                $response['success'] = false;
                $response['message'] = t('error017') . ' $id_pick: ' . $id_pick . ' [MEMCACHED]';
            }
        }

        if (empty($response)) {
            $this->setID(INSTAGRAM_PHOTO_URL . $pick_data['guid'], $id_pick);

            $response['success'] = true;
            $response['message'] = t('ok042') . ' $id_pick: ' . $id_pick . ' [MEMCACHED]';
            $response['pick_data'] = $pick_data;
        }

        return $response;
    }
    
    public function getID($LINK) {
        $response = array();
        $id_pick = null;

        $link = $LINK;
        if (empty($response) && empty($link)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "LINK " . t('txt003') . "MC_Picks->getID()";
        }

        if (empty($response)) {
            $key = $this->_name . md5($link);

            $cached_data = getCache()->get($key);

            if ($cached_data) {
                $id_pick = $cached_data;
            } else {
                $response['success'] = false;
                $response['message'] = t('error011') . ' $link: ' . $link . ' [MEMCACHED]';
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok042') . ' $link: ' . $link . ' [MEMCACHED]';
            $response['id_pick'] = $id_pick;
        }

        return $response;
    }

    public function setID($LINK, $ID_PICK) {
        $response = array();

        $link = $LINK;
        if (empty($response) && empty($link)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "LINK " . t('txt003') . "MC_Picks->setID()";
        }

        $id_pick = (int) $ID_PICK;
        if (empty($response) && empty($id_pick)) {
            $response['success'] = false;
            $response['message'] = t('error003') . "ID_PICK " . t('txt003') . "MC_Picks->setID()";
        }

        if (empty($response)) {
            $key = $this->_name . md5($link);
            getCache()->set($key, $id_pick);

            $response['success'] = true;
            $response['message'] = t('ok029') . ' $link: ' . $link . ' [MEMCACHED]';
        }

        return $response;
    }

}