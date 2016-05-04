<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class MonProfilController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/MonProfil';
        $this->_title = 'Mon Profil';
        $this->_page = 'MonProfil';
        $this->setTemplate('/profil.phtml');
    }

    /**
     * @return UtilisateurModel
     */
    public function getUtilisateur() {
        return Access::getInstance()->getCurrentUser();
    }

    public function saveAction() {
        $post = Access::getRequest();

        if (isset($post['login'], $post['email'])) {
            $utilisateur = (new UtilisateurCollection())->loadById($this->getUtilisateur()->getAttribute('id'));
            //$utilisateur = $this->getUtilisateur();
            
            $utilisateur->setAttribute('login', $post['login']);
            $utilisateur->setAttribute('email', $post['email']);
            
            if (isset($post['password'], $post['password2'])
                && !empty($post['password'])
                && !empty($post['password2'])
                && $post['password'] == $post['password2']) {

                $utilisateur->setAttribute('password', sha1($post['password']));
            }

            if ($utilisateur->save()) {
                
                Access::getInstance()->setCurrentUser($utilisateur);
                $messages = new MessageManager();
                $messages->newMessage('Votre profil a été sauvegardé correctement', Message::LEVEL_SUCCESS);
            } else {
                $messages = new MessageManager();
                $messages->newMessage('Un problème est survenue', Message::LEVEL_ERROR);
            }
        }
    }
}