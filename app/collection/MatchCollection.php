<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 19:35
 */
class MatchCollection extends Collection
{

    function __construct() {
        parent::__construct();
        $this->_table = 'match';
        $this->_model = 'MatchModel';
        $this->_key = 'id';
    }
}