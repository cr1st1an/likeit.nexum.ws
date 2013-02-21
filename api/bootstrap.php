<?php

include_once '../vendors/epi/Epi.php';

Epi::setPath('root', '/srv/vhosts/likeit.nexum.ws');
Epi::setPath('base', Epi::getPath('root') . '/vendors/epi/');
Epi::setPath('config', Epi::getPath('root') . '/api/config/');
Epi::setPath('controller', Epi::getPath('root') . '/api/controller/');
Epi::setPath('data', Epi::getPath('root') . '/api/data/');
Epi::setPath('locale', Epi::getPath('root') . '/api/locale/');
Epi::setPath('lib', Epi::getPath('root') . '/api/lib/');

Epi::init('api', 'cache', 'config', 'database', 'route', 'session');

getConfig()->load('default.ini', 'secure.ini');

define('INSTAGRAM_CLIENT_ID', '8c8f279095cb4c4a8c384e1cef007a3b');
define('INSTAGRAM_CLIENT_SECRET', '972bba42303e4143a7dd489e3513da77');
define('INSTAGRAM_CALLBACK_URL', 'http://login.nexum.ws/instagram/');

EpiDatabase::employ(
        getConfig()->get('db')->type, getConfig()->get('db')->name, getConfig()->get('db')->host, getConfig()->get('db')->username, getConfig()->get('db')->password
);

EpiCache::employ(
        EpiCache::getInstance(
                EpiCache::MEMCACHED, getConfig()->get('memcached')->host, getConfig()->get('memcached')->port, getConfig()->get('memcached')->compress, getConfig()->get('memcached')->expiry
        )
);

EpiSession::employ(
        EpiSession::getInstance(
                EpiCache::MEMCACHED, getConfig()->get('memcached')->host, getConfig()->get('memcached')->port, getConfig()->get('memcached')->compress, getConfig()->get('memcached')->expiry
        )
);

// GLOBAL FUNCTIONS
include_once Epi::getPath('locale') . getConfig()->get('locale') . ".php";

function t($key) {
    global $lang;
    if (isset($lang[$key]))
        return $lang[$key];
    else
        return $key;
}

// MAIN CONTROLLER

include_once 'controller/v1.php';

getRoute()->run();