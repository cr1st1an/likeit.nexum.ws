<?php

class Instagram {

    const API_URL = 'https://api.instagram.com/v1';
    const API_OAUTH_URL = 'https://api.instagram.com/oauth/authorize';
    const API_OAUTH_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';
    const API_CLIENT_ID = INSTAGRAM_CLIENT_ID;
    const API_CLIENT_SECRET = INSTAGRAM_CLIENT_SECRET;
    const API_CALLBACK_URL = INSTAGRAM_CALLBACK_URL;

    protected $_token = null;
    protected $_access_token = null;
    protected $_curl_client = null;
    protected $_config = array(
        'client_id' => self::API_CLIENT_ID,
        'client_secret' => self::API_CLIENT_SECRET,
        'grant_type' => 'authorization_code',
        'redirect_uri' => self::API_CALLBACK_URL,
    );

    public function __construct($ACCESS_TOKEN = NULL) {
        include_once Epi::getPath('lib') . 'curl_client.php';
        $this->_curl_client = new CurlClient();

        if (!empty($ACCESS_TOKEN))
            $this->_access_token = $ACCESS_TOKEN;
    }

    public function auth($CODE) {
        $data = array(
            'client_id' => $this->_config['client_id'],
            'client_secret' => $this->_config['client_secret'],
            'grant_type' => $this->_config['grant_type'],
            'redirect_uri' => $this->_config['redirect_uri'],
            'code' => $CODE
        );

        return json_decode($this->_curl_client->post(self::API_OAUTH_TOKEN_URL, $data), true);
    }

    public function getUser() {
        
    }

    public function getUserFollows() {
        
    }

    public function getUserFollowedBy() {
        
    }

    public function getUserRequestedBy() {
        
    }

    public function getUserRelationship($ID, $DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/users/'.$ID.'/relationship', $DATA), true);
    }

    public function postUserRelationship($ID, $DATA) {
        return json_decode($this->_curl_client->post(self::API_URL . '/users/'.$ID.'/relationship', $DATA), true);
    }

    public function getUserPhotosFeed($DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/users/self/feed', $DATA), true);
    }
    
    public function getUserPhotosRecent($ID, $DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/users/'.$ID.'/media/recent', $DATA), true);
    }

    public function getUserPhotosLiked($DATA) {
        if(isset($DATA['max_id']))
            $DATA['max_like_id'] = $DATA['max_id'];
        return json_decode($this->_curl_client->get(self::API_URL . '/users/self/media/liked', $DATA), true);
    }
    
    public function getUsers($DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/users/search', $DATA), true);
    }

    public function getPhotosNear() {
        
    }

    public function getPhotosPopular($DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/media/popular', $DATA), true);
    }

    public function getPhoto($ID, $DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/media/'.$ID, $DATA), true);
    }

    public function getPhotoComments() {
        
    }

    public function postPhotoComment() {
        
    }

    public function deletePhotoComment() {
        
    }

    public function getPhotoLikes() {
        
    }

    public function postPhotoLike($ID, $DATA) {
        return json_decode($this->_curl_client->post(self::API_URL . '/media/'.$ID.'/likes', $DATA), true);
    }
    
    public function deletePhotoLike($ID, $DATA) {
        return json_decode($this->_curl_client->delete(self::API_URL . '/media/'.$ID.'/likes', $DATA), true);
    }

    public function getTag() {
        
    }

    public function getTagPhotosRecent($NAME, $DATA) {
        if(isset($DATA['max_id']))
            $DATA['max_tag_id'] = $DATA['max_id'];
        return json_decode($this->_curl_client->get(self::API_URL . '/tags/'.$NAME.'/media/recent', $DATA), true);
    }

    public function getTags($DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/tags/search', $DATA), true);
    }
    
    public function getLocation() {
        
    }

    public function getLocationPhotosRecent($ID, $DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/locations/'.$ID.'/media/recent', $DATA), true);
    }

    public function getLocations($DATA) {
        return json_decode($this->_curl_client->get(self::API_URL . '/locations/search', $DATA), true);
    }

    public function getGeoPhotosRecent() {
        
    }

}