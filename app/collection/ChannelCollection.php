<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 19:35
 */
class ChannelCollection extends Collection
{

    function __construct() {
        parent::__construct();
        $this->_table = 'channel';
        $this->_model = 'ChannelModel';
        $this->_key = 'id';
    }
}