<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:41
 */
class UtilisateurModel extends Model
{

    function __construct() {
        parent::__construct();
        $this->_key = 'id';
        $this->_table = 'utilisateur';
    }
}