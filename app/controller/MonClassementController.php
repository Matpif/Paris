<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 05/06/16
 * Time: 10:55
 */
class MonClassement extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/Error';
        $this->_title = 'Error';
        $this->_page = 'Error';
    }
}