<?php

include_once Epi::getPath('controller') . 'route_sessions.php';
getApi()->post('/v1/sessions', array('Route_Sessions', 'postRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_streams.php';
getApi()->get('/v1/streams', array('Route_Streams', 'getRoot'), EpiApi::external);
getApi()->post('/v1/streams', array('Route_Streams', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/streams/(\w+)', array('Route_Streams', 'deleteRoot'), EpiApi::external);
getApi()->get('/v1/streams/instagram', array('Route_Streams', 'getInstagram'), EpiApi::external);
getApi()->get('/v1/streams/search', array('Route_Streams', 'getSearch'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_likes.php';
getApi()->post('/v1/likes', array('Route_Likes', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/likes/(\w+)', array('Route_Likes', 'deleteRoot'), EpiApi::external);

function block() {
    return array(
        'success' => false,
        'message' => t('txt001')
    );
}
getApi()->get('(.*)', 'block', EpiApi::external);