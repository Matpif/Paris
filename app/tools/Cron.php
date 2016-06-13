<?php

if (ReadIni::getInstance()->getAttribute('general', 'debug') == true) {
    // DEBUG MODE ON
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    // DEBUG MODE ON
}

function __autoload($class_name) {
    if (strpos($class_name, 'Model') !== false) {
        include_once "../model/{$class_name}.php";
    } else if (strpos($class_name, 'Collection') !== false) {
        include_once "../collection/{$class_name}.php";
    } else if (strpos($class_name, 'Controller') !== false) {
        include_once "../controller/{$class_name}.php";
    } else {
        include_once "{$class_name}.php";
    }
}

interface Cron
{
    public function _run();
}

if (isset($argv[1]) && file_exists('cron/'.$argv[1].'.php')) {

    include_once 'cron/'.$argv[1].'.php';
    $_cron = new $argv[1];

    if ($_cron instanceof Cron) {
        $_cron->_run();
    }
}