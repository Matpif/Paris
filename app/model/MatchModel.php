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
    
    /**
     * Get score by team name key
     * @param $key int
     * @return mixed|string
     */
    public function getScore($key) {
        if($key == 1){
            if (is_array($this->_data) && array_key_exists('score_equipe_1', $this->_data)){
                return $this->_data['score_equipe_1'];
            }
            else{
                return '-';
            }
        }
        elseif($key == 2){
            if (is_array($this->_data) && array_key_exists('score_equipe_2', $this->_data)){
                return $this->_data['score_equipe_2'];
            }
            else{
                return '-';
            }
        }
        else{
            return '-';
        }
    }
}
