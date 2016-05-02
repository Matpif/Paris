<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 13/04/16
 * Time: 19:43
 */
class Access
{
    /**
     * @var array
     */
    private $_pageAccess;
    /**
     * @var string
     */
    private $_currentPage;
    /**
     * @var UtilisateurModel
     */
    private $_utilisateur;
    /**
     * @var ErrorController
     */
    private $_errorController;
    /**
     * @var Access
     */
    private static $_instance;


    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Access();
        }

        return self::$_instance;
    }

    public static function getRequest() {
        return array_merge($_POST, $_GET, $_COOKIE);
    }

    function __construct()
    {
        $this->_pageAccess = [
                                'Accueil' => ['connect' => true, 'level' => '0'],
                                'Matchs' => ['connect' => true, 'level' => '0'],
                                'MesParis' => ['connect' => true, 'level' => '0'],
                                'MonProfil' => ['connect' => true, 'level' => '0'],
                                'Classement' => ['connect' => true, 'level' => '0'],
                                'Login' => ['connect' => false, 'level' => '0'],
                                ];
    }

    public function controlAccess($page) {
        $this->_currentPage = $page;
        if (isset($this->_pageAccess[$page])) {
            if ($this->_pageAccess[$page]['connect'] === true) {

                if (!isset($_SESSION['utilisateur'])) {
                    /** @var LoginController $login */
                    $login = Controller::getController('LoginController');
                    header('Location: ' . $login->getUrl());
                    exit;
                } else {
                    /**
                     * @var $utilisateur UtilisateurModel
                     */
                    $this->_utilisateur = unserialize($_SESSION['utilisateur']);
                    if ($this->_utilisateur->getAttribute('privilege') < $this->_pageAccess[$page]['level']) {
                        $this->pageForbidden();
                    }
                }
            }
        } else {
            $this->pageNotFound();
        }
    }

    /**
     * Page non trouvée
     */
    private function pageNotFound() {
        /** @var ErrorController $controller */
        $this->_errorController = Controller::getController('ErrorController');
        $this->_errorController->notFound();
    }

    /**
     * Page non autorisée
     */
    private function pageForbidden() {
        /** @var ErrorController $controller */
        $this->_errorController = Controller::getController('ErrorController');
        $this->_errorController->forbidden();
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }

    public function isActivePage($page) {
        return $this->_currentPage == $page;
    }

    /**
     * @return UtilisateurModel
     */
    public function getCurrentUser() {
        return $this->_utilisateur;
    }

    /**
     * @return ErrorController
     */
    public function getErrorController()
    {
        return $this->_errorController;
    }
}