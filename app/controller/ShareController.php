<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 25/04/16
 * Time: 11:43
 */
class ShareController extends Controller
{
    /**
     * @var UtilisateurCollection
     */
    private $_utilisateurCollection;

    function __construct()
    {
        parent::__construct();
        $this->_url = '/ParisPartages';
        $this->_title = 'Partage de paris';
        $this->_page = 'Share';
        $this->setTemplate('/share.phtml');
    }

    /**
     * @param $matchId
     * @return null| PariCollection
     */
    public function getPari($matchId) {
        return (new PariCollection())->load(['match_id' => $matchId]);
    }

    /**
     * @return MatchCollection
     */
    public function getAllMatch() {
        return (new MatchCollection())->loadAll(['date' => Collection::SORT_ASC]);
    }

    /**
     * @return MatchCollection
     */
    public function getMatchAvailable() {
        return (new MatchCollection())->load(['is_finish' => 0], ["date" => Collection::SORT_ASC]);
    }

    /**
     * @return MatchCollection
     */
    public function getMatchFinished() {
        return (new MatchCollection())->load(['is_finish' => 1], ["date" => Collection::SORT_DESC]);
    }

    public function getUtilisateur($utilisateurId) {

        if ($this->_utilisateurCollection == null) {
            $this->_utilisateurCollection = new UtilisateurCollection();
            $this->_utilisateurCollection->loadAll();
        }

        /** @var UtilisateurModel $utilisateur */
        foreach ($this->_utilisateurCollection as $utilisateur) {
            if ($utilisateur->getAttribute('id') == $utilisateurId)
                return $utilisateur;
        }

        return null;
    }
}