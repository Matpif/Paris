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
    /**
     * @var ChannelCollection
     */
    private $_channelCollection;

    function __construct()
    {
        parent::__construct();
        $this->_url = '/Matchs';
        $this->setTemplate('/matchs.phtml');
        $this->_title = 'Les Matchs';
        $this->_page = 'Matchs';
        $this->addJS('/js/matchs.js');
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
        return $matchCollection->load(["date" => ["<", date('Y-m-d H:i:s')], 'is_finish' => 0], ["date" => Collection::SORT_ASC]);
    }

    public function getMatchFinish() {
        $matchCollection = new MatchCollection();
        return $matchCollection->load(["is_finish" => 1], ["date" => Collection::SORT_DESC]);
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

    /**
     * @param $channel_id
     * @return ChannelModel|null
     */
    public function getChannel($channel_id) {
        if ($this->_channelCollection == null)
            $this->_channelCollection = (new ChannelCollection())->loadAll();
        
        /** @var ChannelModel $channel */
        foreach ($this->_channelCollection as $channel) {
            if ($channel->getAttribute('id') == $channel_id)
                return $channel;
        }

        return null;
    }
    
    public function saveAction() {

        $erreur = false;
        $info = true;
        $post = Access::getRequest();

        /** @var UtilisateurModel $_utilisateur */
        $_utilisateur = $this->getUtilisateur();

        $pariCollection = new PariCollection();
        $matchCollection = new MatchCollection();

        foreach($post['paris'] as $matchId => $score) {
            $same = false;
            if (trim($score['score_equipe_1']) != '' && trim($score['score_equipe_2']) != '') {

                $match = $matchCollection->load(array("id" => $matchId, 'date' => [">", date('Y-m-d H:i:s')]))->getFirstRow();

                if ($match) {
                    $pari = $pariCollection->load(array("utilisateur_id" => $_utilisateur->getAttribute('id'), "match_id" => $match->getAttribute('id')))->getFirstRow();

                    if ($pari == null) {
                        $pari = new PariModel();
                        $pari->setAttribute('utilisateur_id', $_utilisateur->getAttribute('id'));
                        $pari->setAttribute('match_id', $match->getAttribute('id'));
                    }

                    $same = ($pari->getAttribute('score_equipe_1') == $score['score_equipe_1'] && $pari->getAttribute('score_equipe_2') == $score['score_equipe_2']);

                    $pari->setAttribute('score_equipe_1', $score['score_equipe_1']);
                    $pari->setAttribute('score_equipe_2', $score['score_equipe_2']);

                    if($match->getAttribute('flag_phase_finale') == 1 && $score['score_equipe_1'] == $score['score_equipe_2']){

                        if(isset($score['score_tir_but_1']) && isset($score['score_tir_but_2'])) {

                            $same = $same && ($pari->getAttribute('score_tir_but_1') == $score['score_tir_but_1'] && $pari->getAttribute('score_tir_but_2') == $score['score_tir_but_2']);

                            if (trim($score['score_equipe_1']) != '' && trim($score['score_equipe_2']) != ''
                                && $score['score_tir_but_1'] != $score['score_tir_but_2']) {

                                $pari->setAttribute('score_tir_but_1', $score['score_tir_but_1']);
                                $pari->setAttribute('score_tir_but_2', $score['score_tir_but_2']);
                            }
                            else
                                $info = false;
                        }
                        else
                            $info = false;
                    }
                    else{
                        $pari->setAttribute('score_tir_but_1', null);
                        $pari->setAttribute('score_tir_but_2', null);
                    }

                    if (!$same) {
                        if (!$info || !$pari->save())
                            $erreur = false;
                    }
                }
            }
        }

        if(!$info){
            $messages = new MessageManager();
            $messages->newMessage('Un ou plusieurs paris n\'ont pas été sauvegardés', Message::LEVEL_INFO);
        } else {
            if (!$erreur) {
                $messages = new MessageManager();
                $messages->newMessage('Vos paris ont été sauvegardé correctement', Message::LEVEL_SUCCESS);
            } else {
                $messages = new MessageManager();
                $messages->newMessage('Un problème est survenu, tous vos paris n\'ont pas été enregistrés correctement.', Message::LEVEL_ERROR);
            }
        }

        $this->redirect($this);
    }
}