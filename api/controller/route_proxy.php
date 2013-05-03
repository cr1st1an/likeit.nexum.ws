<?php

class Route_Proxy {

    public function getP1() {
        include_once Epi::getPath('data') . 'mc_ig_oembed.php';
        include_once Epi::getPath('data') . 'mc_picks.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $MC_IG_Oembed = new MC_IG_Oembed();
        $MC_Picks = new MC_Picks();
        
        $Validator = new Validator();

        $response = array();
        $get = array();
        $oembed_data = array();
        $pick_data = array();
        
        if (empty($response)) {
            $r_getGetParams = $Validator->getGetParams(array('link'));

            if ($r_getGetParams['success']) {
                $get = $r_getGetParams['get'];
            } else {
                $response = $r_getGetParams;
            }
        }


        if (empty($response)) {
            $r_getOembed = $MC_IG_Oembed->getOembed($get['link']);
            if ($r_getOembed['success']) {
                $oembed_data = $r_getOembed['oembed_data'];
                $r_getID = $MC_Picks->getID($get['link']);
                if($r_getID['success']){
                    $r_getPick = $MC_Picks->getPick($r_getID['id_pick']);
                    if($r_getPick['success']){
                        $pick_data = $r_getPick['pick_data'];
                        if (empty($pick_data['img_url']))
                            $pick_data['img_url'] = $oembed_data['url'];
                    }
                }
            } else {
                $response = $r_getOembed;
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = t('ok041');
            $response['oembed_data'] = $oembed_data;
            $response['pick_data'] = $pick_data;
        }

        return $response;
    }

}