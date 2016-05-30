<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 25/04/16
 * Time: 13:52
 */
class AccueilController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/Accueil';
        $this->setTemplate('/accueil.phtml');
        $this->_title = 'Accueil';
        $this->_page = 'Accueil';
    }

    public function getUtilisateur() {
        return Access::getInstance()->getCurrentUser();
    }

    public function getScore() {
        $pariCollection = new PariCollection();
        $pariCollection->load(['utilisateur_id' => $this->getUtilisateur()->getAttribute('id')]);

        $score = 0;
        /** @var PariModel $pari */
        foreach ($pariCollection as $pari) {
            $score += $pari->getScore();
        }

        return $score;
    }
}