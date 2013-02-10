<?php

include_once '../vendors/epi/Epi.php';
Epi::setPath('config', '../app/config');
Epi::setPath('base', '../vendors/epi/');

Epi::init('cache', 'config', 'database', 'route');

getConfig()->load('default.ini', 'secure.ini');

EpiDatabase::employ(
        getConfig()->get('db')->type, getConfig()->get('db')->name, getConfig()->get('db')->host, getConfig()->get('db')->username, getConfig()->get('db')->password
);

EpiCache::employ(
        EpiCache::getInstance(
                EpiCache::MEMCACHED, getConfig()->get('memcached')->host, getConfig()->get('memcached')->port, getConfig()->get('memcached')->compress, getConfig()->get('memcached')->expiry
        )
);

getRoute()->get('/', 'home');
getRoute()->get('/contact', 'contactUs');
getRoute()->get('/data', 'data');
getRoute()->get('/cache', 'cache');
getRoute()->run();

function home() {
    echo getConfig()->get('welcome_message');
}

function contactUs() {
    echo 'Send us an email at <a href="mailto:foo@bar.com">foo@bar.com</a>';
}

function data() {
    print_r(getDatabase()->one('SELECT * FROM user WHERE id=:id', array(':id' => 1)));
}

function cache() {
    echo getCache()->get('1') . '<br/>';
    echo getCache()->get('2') . '<br/>';
    echo getCache()->get('3') . '<br/>';
    echo getCache()->get('4') . '<br/>';
    echo getCache()->get('5') . '<br/>';
    echo getCache()->get('6') . '<br/>';
    echo getCache()->get('7') . '<br/>';
    echo getCache()->get('01') . '<br/>';
    echo getCache()->get('02') . '<br/>';
    echo getCache()->get('03') . '<br/>';
    echo getCache()->get('04') . '<br/>';
    echo getCache()->get('05') . '<br/>';
    echo getCache()->get('06') . '<br/>';
    echo getCache()->get('07') . '<br/>';
    echo getCache()->get('001') . '<br/>';
    echo getCache()->get('002') . '<br/>';
    echo getCache()->get('003') . '<br/>';
    echo getCache()->get('004') . '<br/>';
    echo getCache()->get('005') . '<br/>';
    echo getCache()->get('006') . '<br/>';
    echo getCache()->get('007') . '<br/>';
    echo getCache()->get('0001') . '<br/>';
    echo getCache()->get('0002') . '<br/>';
    echo getCache()->get('0003') . '<br/>';
    echo getCache()->get('0004') . '<br/>';
    echo getCache()->get('0005') . '<br/>';
    echo getCache()->get('0006') . '<br/>';
    echo getCache()->get('0007') . '<br/>';
}