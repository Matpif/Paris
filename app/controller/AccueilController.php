<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 25/04/16
 * Time: 13:52
 */
class AccueilController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/accueil';
        $this->setTemplate('/accueil.phtml');
        $this->_title = 'Accueil';
    }
}