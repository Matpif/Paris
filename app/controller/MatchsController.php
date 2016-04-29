<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class MatchsController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/Matchs';
        $this->_title = 'Les Matchs';
        $this->_page = 'Matchs';
    }
}