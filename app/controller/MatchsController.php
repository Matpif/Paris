<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class MatchsController extends Controller
{
    /**
     * @var PariCollection
     */
    private $_pariCollection;

    function __construct()
    {
        parent::__construct();
        $this->_url = '/Matchs';
        $this->setTemplate('/matchs.phtml');
        $this->_title = 'Les Matchs';
        $this->_page = 'Matchs';
    }

    /**
     * @return MatchCollection
     */
    public function getAllMatch() {
        $matchCollection = new MatchCollection();
        return $matchCollection->loadAll(["date" => Collection::SORT_ASC]);
    }

    /**
     * @param MatchModel $match
     * @return null | PariModel
     */
    public function getPari($match) {

        /** @var UtilisateurModel $_utilisateur */
        $_utilisateur = Access::getInstance()->getCurrentUser();
        if ($this->_pariCollection == null) {
            $this->_pariCollection = new PariCollection();
        }

        return $this->_pariCollection->load(array("utilisateur_id" => $_utilisateur->getAttribute('utilisateur_id'), "match_id" => $match->getAttribute('id')))->getFirstRow();
    }
}