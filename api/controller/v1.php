<?php

include_once Epi::getPath('controller') . 'route_sessions.php';
getApi()->post('/v1/sessions', array('Route_Sessions', 'postRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_streams.php';
getApi()->get('/v1/streams', array('Route_Streams', 'getRoot'), EpiApi::external);
getApi()->post('/v1/streams', array('Route_Streams', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/streams/(\w+)', array('Route_Streams', 'deleteRoot'), EpiApi::external);
getApi()->get('/v1/streams/instagram', array('Route_Streams', 'getInstagram'), EpiApi::external);
getApi()->get('/v1/streams/search', array('Route_Streams', 'getSearch'), EpiApi::external);
//getApi()->get('/v1/streams/test', array('Route_Streams', 'test'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_likes.php';
getApi()->post('/v1/likes', array('Route_Likes', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/likes/(\w+)', array('Route_Likes', 'deleteRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_relationships.php';
getApi()->get('/v1/relationships', array('Route_Relationships', 'getRoot'), EpiApi::external);
getApi()->post('/v1/relationships', array('Route_Relationships', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/relationships/(\w+)', array('Route_Relationships', 'deleteRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_albums.php';
getApi()->get('/v1/albums', array('Route_Albums', 'getRoot'), EpiApi::external);
getApi()->post('/v1/albums', array('Route_Albums', 'postRoot'), EpiApi::external);
getApi()->post('/v1/albums/media', array('Route_Albums', 'postMedia'), EpiApi::external);
getApi()->delete('/v1/albums/media/(\w+)/(\w+)', array('Route_Albums', 'deleteMedia'), EpiApi::external);

function block() {
    return array(
        'success' => false,
        'message' => t('txt001')
    );
}
getApi()->get('(.*)', 'block', EpiApi::external);