<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 19:35
 */
class ClassementCollection extends Collection
{

    function __construct() {
        parent::__construct();
        $this->_table = 'classement';
        $this->_model = 'ClassementModel';
        $this->_key = 'id';
    }
}