<?php

include_once Epi::getPath('controller') . 'route_albums.php';
getApi()->get('/v1/albums', array('Route_Albums', 'getRoot'), EpiApi::external);
getApi()->post('/v1/albums', array('Route_Albums', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/albums/([0-9]+)', array('Route_Albums', 'deleteRoot'), EpiApi::external);
getApi()->put('/v1/albums/([0-9]+)', array('Route_Albums', 'updateRoot'), EpiApi::external);
getApi()->post('/v1/albums/media', array('Route_Albums', 'postMedia'), EpiApi::external);
getApi()->delete('/v1/albums/media/([0-9]+)/([0-9\_]+)', array('Route_Albums', 'deleteMedia'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_likes.php';
getApi()->post('/v1/likes', array('Route_Likes', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/likes/([0-9\_]+)', array('Route_Likes', 'deleteRoot'), EpiApi::external);
getApi()->get('/v1/likes/trending', array('Route_Likes', 'getTrending'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_picks.php';
getApi()->get('/v1/picks', array('Route_Picks', 'getRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_proxy.php';
getApi()->get('/v1/proxy/oembed', array('Route_Proxy', 'getP1'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_relationships.php';
getApi()->get('/v1/relationships', array('Route_Relationships', 'getRoot'), EpiApi::external);
getApi()->post('/v1/relationships', array('Route_Relationships', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/relationships/([0-9]+)', array('Route_Relationships', 'deleteRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_sessions.php';
getApi()->get('/v1/sessions', array('Route_Sessions', 'getRoot'), EpiApi::external);
getApi()->post('/v1/sessions', array('Route_Sessions', 'postRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_streams.php';
getApi()->get('/v1/streams', array('Route_Streams', 'getRoot'), EpiApi::external);
getApi()->post('/v1/streams', array('Route_Streams', 'postRoot'), EpiApi::external);
getApi()->delete('/v1/streams/([0-9]+)', array('Route_Streams', 'deleteRoot'), EpiApi::external);
getApi()->get('/v1/streams/instagram', array('Route_Streams', 'getInstagram'), EpiApi::external);
getApi()->get('/v1/streams/search', array('Route_Streams', 'getSearch'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_subscribers.php';
getApi()->post('/v1/subscribers/device_token', array('Route_Subscribers', 'postDeviceToken'), EpiApi::external);
getApi()->post('/v1/subscribers/email', array('Route_Subscribers', 'postEmail'), EpiApi::external);
getApi()->post('/v1/subscribers/invite', array('Route_Subscribers', 'postInvite'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_workers.php';
getApi()->get('/v1/workers/invite_subscriber', array('Route_Workers', 'getW1'), EpiApi::external);
getApi()->get('/v1/workers/load_albums_to_cache', array('Route_Workers', 'getW2'), EpiApi::external);
getApi()->get('/v1/workers/remove_missing_from_cache', array('Route_Workers', 'getW3'), EpiApi::external);

function block() {
    return array(
        'success' => false,
        'message' => t('txt001')
    );
}
getApi()->get('(.*)', 'block', EpiApi::external);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");