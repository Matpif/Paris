<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class ClassementController extends Controller
{
    /**
     * @var UtilisateurModel
     */
    private $_userSelected;
    /**
     * @var MatchCollection
     */
    private $_matchCollection;

    function __construct()
    {
        parent::__construct();
        $this->_url = '/Classement';
        $this->_title = 'Classement';
        $this->_page = 'Classement';
        $this->setTemplate('/classement.phtml');
        $this->addJS('/js/classement.js');
    }

    /**
     * @return UtilisateurCollection
     */
    private function getAllUtilisateur() {
        $utilisateurCollection = new UtilisateurCollection();
        return $utilisateurCollection->load(['privilege' => ['!=', 9]]);
    }

    /**
     * @param $utilisateurId
     * @return int
     */
    private function getScoreUtilisateur($utilisateurId) {
        $pariCollection = new PariCollection();
        $pariCollection->load(['utilisateur_id' => $utilisateurId]);
        
        $score = 0;
        /** @var PariModel $pari */
        foreach ($pariCollection as $pari) {
            $score += $pari->getScore();
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
        
        return $utilisateurCollection->sort('score');
    }

    public function userPointAction() {
        $this->setTemplate('/userPoint.phtml');
        $post = Access::getRequest();
        
        if (isset($post['user'])) {
            $this->_userSelected = (new UtilisateurCollection())->loadById($post['user']);
        }
    }

    /**
     * @param $matchId
     * @return MatchModel|null
     */
    public function getMatch($matchId) {

        if ($this->_matchCollection == null) {
            $this->_matchCollection = new MatchCollection();
        }

        return $this->_matchCollection->loadById($matchId);
    }

    /**
     * @return UtilisateurModel
     */
    public function getUserSelected()
    {
        return $this->_userSelected;
    }
}