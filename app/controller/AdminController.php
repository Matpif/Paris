<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 04/05/16
 * Time: 16:52
 */
class AdminController extends Controller
{

    const DEFAULT_PRIVILEGE = 0;

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
        $this->setTemplate('/admin/addUser.phtml');
        $this->_title = 'Création de profil';
    }

    public function addUserAction(){
        $post = Access::getRequest();

        $utilisateur = new UtilisateurModel();

        if (isset($post['login'], $post['email'], $post['password'], $post['password2'])) {

            $utilisateur->setAttribute('login', $post['login']);
            $utilisateur->setAttribute('email', $post['email']);
            $utilisateur->setAttribute('lastName', $post['lastName']);
            $utilisateur->setAttribute('firstName', $post['firstName']);
            $utilisateur->setAttribute('privilege', self::DEFAULT_PRIVILEGE);

            if (!empty($post['password'])
                && !empty($post['password2'])
                && $post['password'] == $post['password2']) {

                $utilisateur->setAttribute('password', sha1($post['password']));
            } else {
                $this->_newUser = $utilisateur;
                $messages = new MessageManager();
                $messages->newMessage('Les mots de passe ne sont pas identiques', Message::LEVEL_ERROR);
                $this->setTemplate('/admin/addUser.phtml');
                return;
            }

            if ($utilisateur->save()) {

                Access::getInstance()->setCurrentUser($utilisateur);
                $messages = new MessageManager();
                $messages->newMessage('L\'utilisateur a été sauvegardé correctement', Message::LEVEL_SUCCESS);
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

    /**
     * @return UtilisateurModel
     */
    public function getNewUser()
    {
        return ($this->_newUser)?$this->_newUser:new UtilisateurModel();
    }
}