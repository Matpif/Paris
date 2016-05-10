<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class ClassementController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/Classement';
        $this->_title = 'Classement';
        $this->_page = 'Classement';
        $this->setTemplate('/classement.phtml');
    }

    /**
     * @return UtilisateurCollection
     */
    private function getAllUtilisateur() {
        $utilisateurCollection = new UtilisateurCollection();
        return $utilisateurCollection->loadAll();
    }

    /**
     * @param $utilisateurId
     * @return int
     */
    private function getScoreUtilisateur($utilisateurId) {
        $matchCollection = new MatchCollection();
        $pariCollection = new PariCollection();
        $pariCollection->load(['utilisateur_id' => $utilisateurId]);
        
        $score = 0;
        /** @var PariModel $pari */
        foreach ($pariCollection as $pari) {
            $match = $matchCollection->load(['id' => $pari->getAttribute('match_id'), 'date' => ['<=', date('Y-m-d H:i:s')]])
                                        ->getFirstRow();

            /** @var MatchModel $match */
            if ($match) {
                // Si l'utilisateur à le bon score
                if ($match->getAttribute('score_equipe_1') == $pari->getAttribute('score_equipe_1')
                    && $match->getAttribute('score_equipe_2') == $pari->getAttribute('score_equipe_2')) {
                    $score += 5;

                    // Sinon si l'utilisateur à la bonne équipe gagnante
                } else if (($match->getAttribute('score_equipe_1') < $match->getAttribute('score_equipe_2')
                                && $pari->getAttribute('score_equipe_1') < $pari->getAttribute('score_equipe_2'))
                            || ($match->getAttribute('score_equipe_1') > $match->getAttribute('score_equipe_2')
                                && $pari->getAttribute('score_equipe_1') > $pari->getAttribute('score_equipe_2'))) {
                    $score += 3;
                } // Sinon pas de point
            }
        }
        
        return $score;
    }

    /**
     * @return UtilisateurCollection
     */
    public function getAllUtilisateurWithScore() {
        $utilisateurCollection = $this->getAllUtilisateur();
        /** @var UtilisateurModel $utilisateur */
        foreach ($utilisateurCollection as $utilisateur) {
            $score = $this->getScoreUtilisateur($utilisateur->getAttribute('id'));
            $utilisateur->setAttribute('score', $score);
        }
        // TODO: Sort Order by Score
        return $utilisateurCollection->sort('score');
    }
}