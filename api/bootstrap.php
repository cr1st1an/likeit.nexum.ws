<?php

include_once '../vendors/epi/Epi.php';

Epi::setPath('root', '/srv/vhosts/likeit.nexum.ws');
Epi::setPath('base', Epi::getPath('root') . '/vendors/epi');
Epi::setPath('config', Epi::getPath('root') . '/api/config');
Epi::setPath('controller', Epi::getPath('root') . '/api/controller');
Epi::setPath('locale', Epi::getPath('root') . '/api/locale');

Epi::init('api', 'cache', 'config', 'database', 'route');

getConfig()->load('default.ini', 'secure.ini');

EpiDatabase::employ(
        getConfig()->get('db')->type, getConfig()->get('db')->name, getConfig()->get('db')->host, getConfig()->get('db')->username, getConfig()->get('db')->password
);

EpiCache::employ(
        EpiCache::getInstance(
                EpiCache::MEMCACHED, getConfig()->get('memcached')->host, getConfig()->get('memcached')->port, getConfig()->get('memcached')->compress, getConfig()->get('memcached')->expiry
        )
);

// GLOBAL FUNCTIONS

function t($key) {
    $locale = getConfig()->get('locale');

    if (file_exists(Epi::getPath('locale') . "/$locale.php")) {
        include_once Epi::getPath('locale') . "/$locale.php";
    } else {
        // include default locale
    }

    if (isset($lang[$key]))
        return $lang[$key];
    else
        return $key;
}

// MAIN CONTROLLER

include_once 'controller/v1.php';

getRoute()->run();