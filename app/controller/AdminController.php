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
        $this->setTemplate('/admin/addMatch.phtml');
        $this->_title = 'Création de match';
    }

    public function newPouleAction() {
        $this->setTemplate('/admin/addPoule.phtml');
        $this->_title = 'Création de poule';
    }

    public function newEquipeAction() {
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

    public function addUserAction(){
        $post = Access::getRequest();

        $utilisateur = new UtilisateurModel();

        if (isset($post['login'], $post['email'], $post['password'], $post['password2'])) {

            if (isset($post['id'])) {
                $utilisateur->setAttribute('id', $post['id']);
            }
            
            $utilisateur->setAttribute('login', $post['login']);
            $utilisateur->setAttribute('email', $post['email']);
            $utilisateur->setAttribute('lastName', $post['lastName']);
            $utilisateur->setAttribute('firstName', $post['firstName']);
            $utilisateur->setAttribute('privilege', self::DEFAULT_PRIVILEGE);

            if (!empty($post['password'])
                && !empty($post['password2'])
                && $post['password'] == $post['password2']) {

                $utilisateur->setPassword($post['password']);
            } else {
                $this->_newUser = $utilisateur;
                $messages = new MessageManager();
                $messages->newMessage('Les mots de passe ne sont pas identiques', Message::LEVEL_ERROR);
                $this->setTemplate('/admin/addUser.phtml');
                return;
            }

            if ($utilisateur->save()) {

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
                $match->setAttribute('date', $post['date']);
                $match->setAttribute('equipe_id_1', $post['equipe_id_1']);
                $match->setAttribute('equipe_id_2', $post['equipe_id_2']);

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

            if ($utilisateur->remove()) {
                $messages = new MessageManager();
                $messages->newMessage('L\'utilisateur a été correctement supprimé', Message::LEVEL_SUCCESS);
            } else {
                $messages = new MessageManager();
                $messages->newMessage('L\'utilisateur n\'a pas été correctement supprimé', Message::LEVEL_ERROR);
            }
        }

        $this->redirect($this->getUrlAction('listUser'));
        //$this->setTemplate('/admin/listUser.phtml');
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

    /**
     * @return UtilisateurModel
     */
    public function getNewUser()
    {
        return ($this->_newUser)?$this->_newUser:new UtilisateurModel();
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
}