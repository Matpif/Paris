<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:41
 */
class PariModel extends Model
{

    function __construct() {
        parent::__construct();
        $this->_key = 'id';
        $this->_table = 'pari';
    }

    /**
     * @return int
     */
    function getScore() {
        $score = 0;
        $matchCollection = new MatchCollection();
        $match = $matchCollection->load(['id' => $this->getAttribute('match_id'), 'date' => ['<=', date('Y-m-d H:i:s')]])
                                    ->getFirstRow();

        /** @var MatchModel $match */
        if ($match) {
            // Si l'utilisateur à le bon score
            if ($match->getAttribute('score_equipe_1') == $this->getAttribute('score_equipe_1')
                && $match->getAttribute('score_equipe_2') == $this->getAttribute('score_equipe_2')) {
                
                if ($match->getAttribute('score_equipe_1') == $match->getAttribute('score_equipe_2')
                    && $match->getAttribute('flag_phase_finale') == 1) {

                    if ($match->getAttribute('score_tir_but_1') == $this->getAttribute('score_tir_but_1')
                        && $match->getAttribute('score_tir_but_2') == $this->getAttribute('score_tir_but_2')) {

                        $score += 5;
                        
                    } else if (($match->getAttribute('score_tir_but_1') < $match->getAttribute('score_tir_but_2')
                            && $this->getAttribute('score_tir_but_1') < $this->getAttribute('score_tir_but_2'))
                        || ($match->getAttribute('score_tir_but_1') > $match->getAttribute('score_tir_but_2')
                            && $this->getAttribute('score_tir_but_1') > $this->getAttribute('score_tir_but_2'))){

                        $score += 3;
                    }
                } else {
                    $score += 5;
                }

                // Sinon si l'utilisateur à la bonne équipe gagnante
            } else if (($match->getAttribute('score_equipe_1') < $match->getAttribute('score_equipe_2')
                    && $this->getAttribute('score_equipe_1') < $this->getAttribute('score_equipe_2'))
                || ($match->getAttribute('score_equipe_1') > $match->getAttribute('score_equipe_2')
                    && $this->getAttribute('score_equipe_1') > $this->getAttribute('score_equipe_2'))) {
                $score += 3;
            } // Sinon pas de point
        }

        return $score;
    }
}