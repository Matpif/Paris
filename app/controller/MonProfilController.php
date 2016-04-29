<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class MonProfilController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/MonProfil';
        $this->_title = 'Mon Profil';
        $this->_page = 'MonProfil';
    }
}