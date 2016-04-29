<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class MesParisController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/MesParis';
        $this->_title = 'Mes Paris';
        $this->_page = 'MesParis';
    }
}