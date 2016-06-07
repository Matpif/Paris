<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:41
 */
class MatchModel extends Model
{
    private $_equipeCollection;

    function __construct() {
        parent::__construct();
        $this->_key = 'id';
        $this->_table = 'match';
    }

    /**
     * @return EquipeModel|null
     */
    public function getEquipe_1() {
        if ($this->_equipeCollection == null)
            $this->_equipeCollection = new EquipeCollection();
        return $this->_equipeCollection->loadById($this->getAttribute('equipe_id_1'));
    }

    /**
     * @return EquipeModel|null
     */
    public function getEquipe_2() {
        if ($this->_equipeCollection == null)
            $this->_equipeCollection = new EquipeCollection();
        return $this->_equipeCollection->loadById($this->getAttribute('equipe_id_2'));
    }
    
    /**
     * Get score by team name key
     * @return mixed|string
     */
    public function getScore() {
        return (isset($this->_data['score_equipe_1'], $this->_data['score_equipe_2']))?$this->_data['score_equipe_1'].' - '.$this->_data['score_equipe_2']:'';
    }
}
