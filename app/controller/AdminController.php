<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 04/05/16
 * Time: 16:52
 */
class AdminController extends Controller
{

    const DEFAULT_PRIVILEGE = UtilisateurModel::PRIVILEGE_USER;

    /**
     * @var UtilisateurModel
     */
    private $_newUser;
    /**
     * @var MatchModel
     */
    private $_newMatch;
    /**
     * @var PouleModel
     */
    private $_newPoule;
    /**
     * @var EquipeModel
     */
    private $_newEquipe;

    function __construct()
    {
        parent::__construct();
        $this->_url = '/Admin';
        $this->setTemplate('/admin/index.phtml');
        $this->_title = 'Administration';
        $this->_page = 'Admin';
        $this->setTemplateHeader('/admin/header.phtml');
    }

    public function newUserAction() {
        $get = Access::getRequest();
        if (isset($get['id'])) {
            $this->_newUser = (new UtilisateurCollection())->loadById($get['id']);
        }

        $this->setTemplate('/admin/addUser.phtml');
        $this->_title = 'Création de profil';
    }

    public function newMatchAction() {
        $get = Access::getRequest();
        if (isset($get['id'])) {
            $this->_newMatch = (new MatchCollection())->loadById($get['id']);
        }

        $this->setTemplate('/admin/addMatch.phtml');
        $this->_title = 'Création de match';
    }

    public function newPouleAction() {
        $get = Access::getRequest();
        if (isset($get['id'])) {
            $this->_newPoule = (new PouleCollection())->loadById($get['id']);
        }

        $this->setTemplate('/admin/addPoule.phtml');
        $this->_title = 'Création de poule';
    }

    public function newEquipeAction() {
        $get = Access::getRequest();
        if (isset($get['id'])) {
            $this->_newEquipe = (new EquipeCollection())->loadById($get['id']);
        }

        $this->setTemplate('/admin/addEquipe.phtml');
        $this->_title = 'Création d\'équipe';
    }

    public function listUserAction() {
        $this->setTemplate('/admin/listUser.phtml');
        $this->_title = 'Liste des utilisateurs';
    }

    public function listMatchAction() {
        $this->setTemplate('/admin/listMatch.phtml');
        $this->_title = 'Liste des matchs';
    }

    public function listPouleAction() {
        $this->setTemplate('/admin/listPoule.phtml');
        $this->_title = 'Liste des poules';
    }
    public function listEquipeAction() {
        $this->setTemplate('/admin/listEquipe.phtml');
        $this->_title = 'Liste des équipes';
    }


    public function addUserAction(){
        $post = Access::getRequest();

        $utilisateur = new UtilisateurModel();

        if (isset($post['login'], $post['email'])) {

            if (isset($post['id'])) {
                $utilisateur->setAttribute('id', $post['id']);
            }
            
            $utilisateur->setAttribute('login', $post['login']);
            $utilisateur->setAttribute('email', $post['email']);
            $utilisateur->setAttribute('lastName', $post['lastName']);
            $utilisateur->setAttribute('firstName', $post['firstName']);
            $utilisateur->setAttribute('privilege', $post['privilege']);

            $newPassword = $utilisateur->newPassword();
            if (!isset($post['id'])) {
                $utilisateur->setPassword($newPassword);
            }
            
            if ($utilisateur->save()) {

                if (!isset($post['id'])) {
                    $sendMail = new SendMail();
                    $sendMail->setDestinataire($utilisateur->getAttribute('email'));
                    $sendMail->setTemplate('nouvelUtilisateur.phtml', ['mail' => $utilisateur->getAttribute('email'), 'password' => $newPassword]);
                    $sendMail->setObjet('Bienvenue sur Paris');

                    $sendMail->envoi();
                }
                
                $messages = new MessageManager();
                $messages->newMessage('L\'utilisateur a été sauvegardé correctement', Message::LEVEL_SUCCESS);
                $this->redirect($this->getUrlAction('listUser'));
            } else {
                $this->_newUser = $utilisateur;
                $messages = new MessageManager();
                $messages->newMessage('Un problème est survenue', Message::LEVEL_ERROR);
                $this->setTemplate('/admin/addUser.phtml');
            }
        } else {
            $this->_newUser = $utilisateur;
            $messages = new MessageManager();
            $messages->newMessage('Un des champs n\'est pas corect', Message::LEVEL_ERROR);
            $this->setTemplate('/admin/addUser.phtml');
        }
    }
    
    public function addMatchAction() {
        $post = Access::getRequest();
        $match = new MatchModel();

        if (isset($post['date'], $post['equipe_id_1'], $post['equipe_id_2'])) {

            if (!empty($post['date']) && $post['equipe_id_1'] != $post['equipe_id_2']) {

                if (isset($post['id'])) {
                    $match->setAttribute('id', $post['id']);
                }

                $match->setAttribute('date', $post['date']);
                $match->setAttribute('equipe_id_1', $post['equipe_id_1']);
                $match->setAttribute('equipe_id_2', $post['equipe_id_2']);

                if (!empty($post['channel_id']))
                    $match->setAttribute('channel_id', $post['channel_id']);
                else
                    $match->setAttribute('channel_id', null);

                if (isset($post['flag_phase_finale']))
                    $match->setAttribute('flag_phase_finale', 1);
                else
                    $match->setAttribute('flag_phase_finale', 0);

                if ($match->save()) {

                    $messages = new MessageManager();
                    $messages->newMessage('Le match a été sauvegardé correctement', Message::LEVEL_SUCCESS);
                    $this->redirect($this->getUrlAction('listMatch'));
                } else {

                    $messages = new MessageManager();
                    $messages->newMessage('Un problème est survenue', Message::LEVEL_ERROR);
                    $this->setTemplate('/admin/addMatch.phtml');
                }
            }else {
                $messages = new MessageManager();
                $messages->newMessage('Tous les champs sont obligatoires et les équipes doivent être différentes', Message::LEVEL_ERROR);
                $this->setTemplate('/admin/addMatch.phtml');
            }

        } else {
            $messages = new MessageManager();
            $messages->newMessage('Tous les champs sont obligatoires', Message::LEVEL_ERROR);
            $this->setTemplate('/admin/addMatch.phtml');
        }
    }

    public function addPouleAction() {
        $post = Access::getRequest();
        $poule = new PouleModel();

        if (isset($post['name']) && !empty($post['name'])) {

            if (isset($post['id'])) {
                $poule->setAttribute('id', $post['id']);
            }

            $poule->setAttribute('name', $post['name']);

            if ($poule->save()) {
                $messages = new MessageManager();
                $messages->newMessage('La poule a été sauvegardée correctement', Message::LEVEL_SUCCESS);
                //$this->redirect($this->getUrlAction('listPoule'));
            } else {

                $messages = new MessageManager();
                $messages->newMessage('Un problème est survenue', Message::LEVEL_ERROR);
                $this->setTemplate('/admin/addPoule.phtml');
            }

        } else {
            $messages = new MessageManager();
            $messages->newMessage('Tous les champs sont obligatoires', Message::LEVEL_ERROR);
            $this->setTemplate('/admin/addPoule.phtml');
        }
    }

    public function addEquipeAction() {
        $post = Access::getRequest();
        $equipe = new EquipeModel();

        if (isset($post['name'], $post['image'], $post['poule_id']) && !empty($post['name']) && !empty($post['poule_id'])) {

            if (isset($post['id'])) {
                $equipe->setAttribute('id', $post['id']);
            }

            $equipe->setAttribute('name', $post['name']);
            $equipe->setAttribute('image', $post['image']);
            $equipe->setAttribute('poule_id', $post['poule_id']);

            if ($equipe->save()) {
                $messages = new MessageManager();
                $messages->newMessage('L\'équipe a été sauvegardé correctement', Message::LEVEL_SUCCESS);
            } else {

                $messages = new MessageManager();
                $messages->newMessage('Un problème est survenue', Message::LEVEL_ERROR);
                $this->setTemplate('/admin/addPoule.phtml');
            }

        } else {
            $messages = new MessageManager();
            $messages->newMessage('Tous les champs avec un * sont obligatoires', Message::LEVEL_ERROR);
            $this->setTemplate('/admin/addPoule.phtml');
        }
    }

    public function deleteUserAction() {
        $get = Access::getRequest();
        if (isset($get['id'])){
            /** @var UtilisateurModel $utilisateur */
            $utilisateur = (new UtilisateurCollection())->loadById($get['id']);

            if ($utilisateur && $utilisateur->remove()) {
                $messages = new MessageManager();
                $messages->newMessage('L\'utilisateur a été correctement supprimé', Message::LEVEL_SUCCESS);
            } else {
                $messages = new MessageManager();
                $messages->newMessage('L\'utilisateur n\'a pas été correctement supprimé', Message::LEVEL_ERROR);
            }
        }

        $this->redirect($this->getUrlAction('listUser'));
    }

    public function deletePouleAction() {
        $get = Access::getRequest();
        if (isset($get['id'])){
            /** @var PouleModel $poule */
            $poule = (new PouleCollection())->loadById($get['id']);

            if ($poule && $poule->remove()) {
                $messages = new MessageManager();
                $messages->newMessage('La poule a été correctement supprimée', Message::LEVEL_SUCCESS);
            } else {
                $messages = new MessageManager();
                $messages->newMessage('La poule n\'a pas été correctement supprimée', Message::LEVEL_ERROR);
            }
        }

        $this->redirect($this->getUrlAction('listPoule'));
    }

    public function deleteEquipeAction() {
        $get = Access::getRequest();
        if (isset($get['id'])){
            /** @var EquipeModel $equipe */
            $equipe = (new EquipeCollection())->loadById($get['id']);

            if ($equipe && $equipe->remove()) {
                $messages = new MessageManager();
                $messages->newMessage('L\'équipe a été correctement supprimée', Message::LEVEL_SUCCESS);
            } else {
                $messages = new MessageManager();
                $messages->newMessage('L\'équipe n\'a pas été correctement supprimée', Message::LEVEL_ERROR);
            }
        }

        $this->redirect($this->getUrlAction('listEquipe'));
    }

    public function deleteMatchAction() {
        $get = Access::getRequest();
        if (isset($get['id'])){
            /** @var MatchModel $match */
            $match = (new MatchCollection())->loadById($get['id']);

            if ($match && $match->remove()) {
                $messages = new MessageManager();
                $messages->newMessage('Le match a été correctement supprimée', Message::LEVEL_SUCCESS);
            } else {
                $messages = new MessageManager();
                $messages->newMessage('Le match n\'a pas été correctement supprimée', Message::LEVEL_ERROR);
            }
        }

        $this->redirect($this->getUrlAction('listMatch'));
    }


    public function addScoreMatchAction() {
        $post = Access::getRequest();

        if (isset($post['match'])) {
            $matchCollection = new MatchCollection();
            foreach ($post['match'] as $matchId => $scores) {
                /** @var MatchModel $match */
                $match = $matchCollection->loadById($matchId);

                if ($match) {

                    if (trim($scores['score_equipe_1']) != "" && trim($scores['score_equipe_2']) != "") {
                        $match->setAttribute('score_equipe_1', $scores['score_equipe_1']);
                        $match->setAttribute('score_equipe_2', $scores['score_equipe_2']);
                    } else {
                        $match->setAttribute('score_equipe_1', null);
                        $match->setAttribute('score_equipe_2', null);
                    }

                    $match->save();
                }
            }

            $messages = new MessageManager();
            $messages->newMessage('Les scores ont bien été sauvegardés', Message::LEVEL_SUCCESS);
        } else {
            $messages = new MessageManager();
            $messages->newMessage('Match non trouvé', Message::LEVEL_ERROR);
        }

        $this->redirect($this->getUrlAction('listMatch'));
        //$this->setTemplate('/admin/listMatch.phtml');
    }

    public function getScoreMatchAction() {
        $post = Access::getRequest();

        if (isset($post['id'])) {
            /** @var MatchModel $match */
            $match = (new MatchCollection())->loadById($post['id']);

            if ($match) {
                $cs = new Crowdscores();
                $score = $cs->getScore($match);

                if (isset($score['score_equipe_1'], $score['score_equipe_2'])) {
                    $match->setAttribute('score_equipe_1', $score['score_equipe_1']);
                    $match->setAttribute('score_equipe_2', $score['score_equipe_2']);
                    $match->setAttribute('score_tir_but_1', $score['score_tir_but_1']);
                    $match->setAttribute('score_tir_but_2', $score['score_tir_but_2']);
                    $match->setAttribute('is_finish', $score['is_finish']);

                    if ($match->save()) {
                        $messages = new MessageManager();
                        $messages->newMessage('Le score récupéré', Message::LEVEL_SUCCESS);
                    } else {
                        $messages = new MessageManager();
                        $messages->newMessage('Le score non sauvegardé', Message::LEVEL_ERROR);
                    }
                } else {
                    $messages = new MessageManager();
                    $messages->newMessage('Imossible de récupérer le score', Message::LEVEL_ERROR);
                }
            } else {
                $messages = new MessageManager();
                $messages->newMessage('Match non trouvé', Message::LEVEL_ERROR);
            }
        }
    }

    /**
     * @return UtilisateurModel
     */
    public function getNewUser()
    {
        return ($this->_newUser)?$this->_newUser:new UtilisateurModel();
    }

    /**
     * @return MatchModel
     */
    public function getNewMatch()
    {
        return ($this->_newMatch)?$this->_newMatch:new MatchModel();
    }

    /**
     * @return PouleModel
     */
    public function getNewPoule()
    {
        return ($this->_newPoule)?$this->_newPoule:new PouleModel();
    }

    /**
     * @return EquipeModel
     */
    public function getNewEquipe()
    {
        return ($this->_newEquipe)?$this->_newEquipe:new EquipeModel();
    }

    /**
     * @return Collection
     */
    public function getAllEquipe() {
        $_equipeCollection = new EquipeCollection();
        return $_equipeCollection->loadAll(["name" => Collection::SORT_ASC]);
    }

    /**
     * @return Collection
     */
    public function getAllPoule() {
        $_pouleCollection = new PouleCollection();
        return $_pouleCollection->loadAll(["name" => Collection::SORT_ASC]);
    }

    /**
     * @return Collection
     */
    public function getAllUser() {
        $_utilisateurCollection = new UtilisateurCollection();
        return $_utilisateurCollection->loadAll(["login" => Collection::SORT_ASC]);
    }

    /**
     * @return Collection
     */
    public function getMatchBegin() {
        $_matchCollection = new MatchCollection();
        return $_matchCollection->load(["date" => ["<", date('Y-m-d H:i:s')]], ["date" => Collection::SORT_DESC]);
    }

    /**
     * @return Collection
     */
    public function getMatchNotBegin() {
        $_matchCollection = new MatchCollection();
        return $_matchCollection->load(["date" => [">", date('Y-m-d H:i:s')]], ["date" => Collection::SORT_ASC]);
    }

    public function getAllChannel() {
        $channelCollection = (new ChannelCollection())->loadAll();
        return $channelCollection;
    }

    /**
     * @param $id
     * @return PouleModel|null
     */
    public function getPoule($id) {
        return (new PouleCollection())->loadById($id);
    }
}