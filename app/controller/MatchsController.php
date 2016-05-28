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
        $pari = $this->_pariCollection->load(array("utilisateur_id" => $_utilisateur->getAttribute('utilisateur_id'), "match_id" => $match->getAttribute('id')))->getFirstRow();
        if(is_null($pari)){
            $pari = new PariModel();
            $pari->setAttribute("utilisateur_id", $_utilisateur->getAttribute('utilisateur_id'));
            $pari->setAttribute("match_id", $match->getAttribute('id'));
            $pari->setAttribute("score_equipe_1", 0);
            $pari->setAttribute("score_equipe_2", 0);
            if(!$pari->save()){
                $message = new MessageManager();
                $message->newMessage("Une erreur est survenue");
            }
        }
        $pari = $this->_pariCollection->load(array("utilisateur_id" => $_utilisateur->getAttribute('utilisateur_id'), "match_id" => $match->getAttribute('id')))->getFirstRow();
        return $pari;
    }

    /**
     * @return UtilisateurModel
     */
    public function getUtilisateur() {
        return Access::getInstance()->getCurrentUser();
    }
    
    public function saveAction() {
        $flag = 0;
        $post = Access::getRequest();
        $ids = json_decode($post['ids']);
        foreach($ids as $id) {
            $pari = (new PariCollection())->loadById($id);
            $pari->setAttribute('score_equipe_1', $post['score_equipe_1'.$id]);
            $pari->setAttribute('score_equipe_2', $post['score_equipe_2'.$id]);
            if(!$pari->save()){
                $flag = 1;
            }
        }
        if($flag == 0){
            $messages = new MessageManager();
            $messages->newMessage('Vos paris ont été sauvegardé correctement', Message::LEVEL_SUCCESS);
        }
        else{
            $messages = new MessageManager();
            $messages->newMessage('Un problème est survenue', Message::LEVEL_ERROR);
        }
    }
}