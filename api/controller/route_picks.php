<?php

class Route_Picks {

    public function getRoot() {
        include_once Epi::getPath('data') . 'mc_ig_oembed.php';
        include_once Epi::getPath('data') . 'mc_picks.php';
        include_once Epi::getPath('lib') . 'data_handler.php';

        $MC_IG_Oembed = new MC_IG_Oembed();
        $MC_Picks = new MC_Picks();

        $DataHandler = new DataHandler();
        
        $response = array();
        $picks_data = array();
        
        if (empty($response)) {
            $i = 0;
            do {
                $r_getPick = $MC_Picks->getPick(++$i);
                if ($r_getPick['success']) {
                    $picks_data[$i] = $r_getPick['pick_data'];
                    $r_getOembed = $MC_IG_Oembed->getOembed(INSTAGRAM_PHOTO_URL . $picks_data[$i]['guid']);
                    if ($r_getOembed['success']) {
                        $picks_data[$i]['oembed'] = $r_getOembed['oembed_data'];
                        if (empty($picks_data[$i]['img_url']))
                            $picks_data[$i]['img_url'] = $picks_data[$i]['oembed']['url'];
                    } else {
                        unset($picks_data[$i]);
                    }
                }
            } while ($r_getPick['success']);
            
            $response['success'] = true;
            $response['message'] = t('ok040');
            $response['origin'] = 'picks';
            $response['picks_data'] = $DataHandler->picksFeed($picks_data);
        }

        return $response;
    }

}