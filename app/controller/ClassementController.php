<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class ClassementController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/Classement';
        $this->_title = 'Classement';
        $this->_page = 'Classement';
    }
}