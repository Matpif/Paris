<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 13/04/16
 * Time: 19:43
 */
class Access
{
    private $_pageAccess;
    private $_currentPage;
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
                    $utilisateur = unserialize($_SESSION['utilisateur']);
                    if ($utilisateur->getAttribute('privilege') < $this->_pageAccess[$page]['level']) {
                        $this->pageForbidden();
                    }
                }
            }
        } else {
            $this->pageNotFound();
            exit;
        }
    }

    private function pageNotFound() {
        header("HTTP/1.0 404 Not Found");
        $controller = new Controller();
        $controller->setTemplate('404.phtml');
        echo $controller->getHtml();
        exit;
    }

    private function pageForbidden() {
        header("HTTP/1.0 403 Forbidden");
        $controller = new Controller();
        $controller->setTemplate('403.phtml');
        echo $controller->getHtml();
        exit;
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
}