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

    public function getMatchAvailable() {
        $matchCollection = new MatchCollection();
        return $matchCollection->load(["date" => [">", date('Y-m-d H:i:s')]], ["date" => Collection::SORT_ASC]);
    }

    public function getMatchInProgress() {
        $matchCollection = new MatchCollection();
        return $matchCollection->load(["date" => ["<", date('Y-m-d H:i:s', time() + 5400)]], ["date" => Collection::SORT_ASC]);
    }

    public function getMatchFinish() {
        $matchCollection = new MatchCollection();
        return $matchCollection->load(["date" => ["<", date('Y-m-d H:i:s', time() + 5400)]], ["date" => Collection::SORT_ASC]);
    }

    /**
     * @param MatchModel $match
     * @return null | PariModel
     */
    public function getPari($match) {

        /** @var UtilisateurModel $_utilisateur */
        $_utilisateur = $this->getUtilisateur();
        if ($this->_pariCollection == null) {
            $this->_pariCollection = new PariCollection();
        }
        
        return $this->_pariCollection->load(array("utilisateur_id" => $_utilisateur->getAttribute('id'), "match_id" => $match->getAttribute('id')))->getFirstRow();
    }

    /**
     * @return UtilisateurModel
     */
    public function getUtilisateur() {
        return Access::getInstance()->getCurrentUser();
    }
    
    public function saveAction() {

        $erreur = false;
        $post = Access::getRequest();

        /** @var UtilisateurModel $_utilisateur */
        $_utilisateur = $this->getUtilisateur();

        $pariCollection = new PariCollection();
        $matchCollection = new MatchCollection();

        foreach($post['paris'] as $matchId => $score) {

            if (trim($score['score_equipe_1']) != '' && trim($score['score_equipe_2']) != '') {
                $match = $matchCollection->loadById($matchId);
                $pari = $pariCollection->load(array("utilisateur_id" => $_utilisateur->getAttribute('id'), "match_id" => $match->getAttribute('id')))->getFirstRow();

                if ($pari == null) {
                    $pari = new PariModel();
                    $pari->setAttribute('utilisateur_id', $_utilisateur->getAttribute('id'));
                    $pari->setAttribute('match_id', $match->getAttribute('id'));
                }

                $pari->setAttribute('score_equipe_1', $score['score_equipe_1']);
                $pari->setAttribute('score_equipe_2', $score['score_equipe_2']);

                if(!$pari->save())
                    $erreur = false;
            }
        }

        if(!$erreur){
            $messages = new MessageManager();
            $messages->newMessage('Vos paris ont été sauvegardé correctement', Message::LEVEL_SUCCESS);
        } else {
            $messages = new MessageManager();
            $messages->newMessage('Un problème est survenu, tous vos paris n\'ont pas été enregistrés correctement.', Message::LEVEL_ERROR);
        }

        $this->redirect($this);
    }
}