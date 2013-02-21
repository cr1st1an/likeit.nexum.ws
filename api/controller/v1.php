<?php

include_once Epi::getPath('controller') . 'route_sessions.php';
getApi()->post('/v1/sessions', array('Route_Sessions', 'postRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_streams.php';
getApi()->get('/v1/streams', array('Route_Streams', 'getRoot'), EpiApi::external);
getApi()->get('/v1/streams/instagram', array('Route_Streams', 'getInstagram'), EpiApi::external);
getApi()->get('/v1/streams/search', array('Route_Streams', 'getSearch'), EpiApi::external);

function block() {
    return array(
        'success' => false,
        'message' => t('txt001')
    );
}
getApi()->get('(.*)', 'block', EpiApi::external);