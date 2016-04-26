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
        $this->_pageAccess = ['accueil' => ['connect' => true, 'level' => '0'],
                                'login' => ['connect' => false, 'level' => '0']
                                ];
    }

    public function controlAccess($page) {

        if (isset($this->_pageAccess[$page])) {
            if ($this->_pageAccess[$page]['connect'] === true) {

                if (!isset($_SESSION['utilisateur'])) {
                    $login = new LoginController();
                    header('Location: ' . $login->getUrl());
                    exit;
                } else {
                    /**
                     * @var $utilisateur UtilisateurModel
                     */
                    $utilisateur = unserialize($_SESSION['utilisateur']);
                    if ($utilisateur->getAttribute('privilege') < $this->_pageAccess[$page]['level']) {
                        header("HTTP/1.0 403 Forbidden");
                        exit;
                    }
                }
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }
}