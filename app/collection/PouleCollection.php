<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 19:35
 */
class PouleCollection extends Collection
{

    function __construct() {
        parent::__construct();
        $this->_table = 'poule';
        $this->_model = 'PouleModel';
        $this->_key = 'id';
    }
}