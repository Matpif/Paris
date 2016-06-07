<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 07/06/16
 * Time: 09:23
 */
class AboutController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/About';
        $this->setTemplate('/about.phtml');
        $this->_title = 'About';
        $this->_page = 'About';
    }
}