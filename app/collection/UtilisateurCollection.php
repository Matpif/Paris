<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 19:35
 */
class UtilisateurCollection extends Collection
{

    function __construct() {
        parent::__construct();
        $this->_table = 'utilisateur';
        $this->_model = 'UtilisateurModel';
        $this->_key = 'id';
    }
}