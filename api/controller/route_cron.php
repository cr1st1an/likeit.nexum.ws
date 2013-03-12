<?php

class Route_Cron {

    public function getMediaAlbumsReset() {
        include_once Epi::getPath('data') . 'mc_likes.php';
        
        $MC_Likes = new MC_Likes();
        
        $likes_data = getDatabase()->all('SELECT * FROM likes');
        
        foreach($likes_data as $like_data){
            $MC_Likes->deleteAlbum($like_data['id_subscriber'], $like_data['id_ig_media'], 1984);
        }
        
        return array('OK');
    }

}