<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:41
 */
class MatchModel extends Model
{

    function __construct() {
        parent::__construct();
        $this->_key = 'id';
        $this->_table = 'match';
    }

    /**
     * @return EquipeModel|null
     */
    public function getEquipe_1() {
        $equipeCollection = new EquipeCollection();
        return $equipeCollection->loadById($this->getAttribute('equipe_id_1'));
    }

    /**
     * @return EquipeModel|null
     */
    public function getEquipe_2() {
        $equipeCollection = new EquipeCollection();
        return $equipeCollection->loadById($this->getAttribute('equipe_id_2'));
    }
}