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
                header('Location: /');
            } else {
                (new MessageManager())->newMessage("Erreur d'identification", Message::LEVEL_ERROR);
                unset ($_SESSION['utilisateur']);
                header('Location: '.$this->getUrl());
            }
            exit;
        }

        header('Location: '.$this->getUrl());
        exit;
    }

    public function disconnectAction() {
        if (isset($_SESSION['utilisateur']))
            unset ($_SESSION['utilisateur']);

        header('Location: '.$this->getUrl());
        exit;
    }
}