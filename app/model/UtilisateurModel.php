<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:41
 */
class UtilisateurModel extends Model
{

    const PRIVILEGE_ADMIN = 9;
    const PRIVILEGE_USER = 0;

    function __construct() {
        parent::__construct();
        $this->_key = 'id';
        $this->_table = 'utilisateur';
    }
}