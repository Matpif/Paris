<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 25/04/16
 * Time: 11:43
 */
class LoginController extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->_url = '/Login';
        $this->setTemplate('/login.phtml');
        $this->_title = 'Login';
        $this->_page = 'Login';
    }

    public function signInAction() {
        $request = Access::getRequest();
        if ($request['email'] && $request['password']) {
            $utilisateurCollection = new UtilisateurCollection();
            $attributes = ['email' => $request['email'],
                            'password' => sha1($request['password']),
                            ];

            /**
             * @var $utilisateur UtilisateurModel
             */
            $utilisateur = $utilisateurCollection
                                        ->load($attributes)
                                        ->getFirstRow();
            
            if ($utilisateur) {
                Access::getInstance()->setCurrentUser($utilisateur);
                $this->redirect('/');
            } else {
                (new MessageManager())->newMessage("Erreur d'identification", Message::LEVEL_ERROR);
                unset ($_SESSION['utilisateur']);
                $this->redirect($this->getUrl());
            }
            exit;
        }

        $this->redirect($this->getUrl());
        exit;
    }

    public function disconnectAction() {
        if (isset($_SESSION['utilisateur']))
            unset ($_SESSION['utilisateur']);

        $this->redirect($this->getUrl());
        exit;
    }
    
    public function forgetPasswordAction() {

        if (ReadIni::getInstance()->getAttribute('general', 'functionForgetPassword') == false) {
            $this->redirect($this->getUrl());
        }

        $post = Access::getRequest();

        if (isset($post['action'], $post['email']) && $post['action'] == 'forgetPassword') {

            $utilisateurCollection = new UtilisateurCollection();

            /** @var UtilisateurModel $utilisateur */
            if ($utilisateur = $utilisateurCollection->load(['email' => $post['email']])->getFirstRow()) {
                $newPassword = $utilisateur->newPassword();
                $utilisateur->setAttribute('password', $newPassword);
                if ($utilisateur->save()) {
                    $message = "Vous avez demandé un nouveau mot de passe.\r\nEmail: ".$utilisateur->getAttribute('email')."\r\nMot de passe: ".$newPassword;
                    if (mail($utilisateur->getAttribute('email'), 'Mot de passe oublié', $message)) {
                        $messageManager = new MessageManager();
                        $messageManager->newMessage("Une nouveau mot de passe vient de vous être envoyé.", Message::LEVEL_SUCCESS);
                    } else {
                        $messageManager = new MessageManager();
                        $messageManager->newMessage("Un problème est survenu lors de l'envoie du mail<br/>Merci de contacter l'administrateur.", Message::LEVEL_ERROR);
                    }
                } else {
                    $messageManager = new MessageManager();
                    $messageManager->newMessage("Une erreur est survenue merci de réessayer plus tard", Message::LEVEL_ERROR);
                }
            } else {
                $messageManager = new MessageManager();
                $messageManager->newMessage("Adresse mail inconnue", Message::LEVEL_ERROR);
            }

            $this->redirect($this->getUrl());
        } else {
            $this->setTemplate('/forgetPassword.phtml');
            $this->_title = 'Mot de passe oublié';
        }
    }
}