<?php

include_once Epi::getPath('controller') . '/users.php';
getApi()->get('/v1/users/auth/?', array('Users', 'auth'), EpiApi::external);

function block() {
    return array(
        'success' => false,
        'message' => t('txt001')
    );
}
getApi()->get('(.*)', 'block', EpiApi::external);




//function data() {
//    print_r(getDatabase()->one('SELECT * FROM user WHERE id=:id', array(':id' => 1)));
//}
//
//function cache() {
//    $time = getCache()->get('time');
//    if (empty($time)) {
//        echo getCache()->set('time', date("Y-m-d H:i:s"), 1500) . '<br/>';
//        echo getCache()->set('00', '0 sec', 0) . '<br/>';
//        echo getCache()->set('01', '10 sec', 10) . '<br/>';
//        echo getCache()->set('02', '20 sec', 20) . '<br/>';
//        echo getCache()->set('03', '30 sec', 30) . '<br/>';
//        echo getCache()->set('04', '60 sec', 60) . '<br/>';
//        echo getCache()->set('05', '120 sec', 120) . '<br/>';
//        echo getCache()->set('06', '300 sec', 300) . '<br/>';
//        echo getCache()->set('07', '600 sec', 600) . '<br/>';
//        echo getCache()->set('08', '900 sec', 900) . '<br/>';
//        echo getCache()->set('09', '1200 sec', 1200) . '<br/>';
//    }
//    echo getCache()->get('time') . '<br/>';
//    echo getCache()->get('00') . '<br/>';
//    echo getCache()->get('01') . '<br/>';
//    echo getCache()->get('02') . '<br/>';
//    echo getCache()->get('03') . '<br/>';
//    echo getCache()->get('04') . '<br/>';
//    echo getCache()->get('05') . '<br/>';
//    echo getCache()->get('06') . '<br/>';
//    echo getCache()->get('07') . '<br/>';
//    echo getCache()->get('08') . '<br/>';
//    echo getCache()->get('09') . '<br/>';
//}