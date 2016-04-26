<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 19:35
 */
class PariCollection extends Collection
{

    function __construct() {
        parent::__construct();
        $this->_table = 'pari';
        $this->_model = 'PariModel';
        $this->_key = 'id';
    }
}