<?php


/**
 * Analyse un fichier de configuration
 *
 * @author ANTARR
 */
class ReadIni {

    private static $_instance;

    public static function getInstance(){

        if (self::$_instance == null) {
            self::$_instance = new ReadIni('config.ini');
        }

        return self::$_instance;
    }

    private $config;
    
    public function __construct($cheminFichier) {                        
        $this->config = parse_ini_file($cheminFichier, true);
    }
    
    
    /**
     * Retourne la liste des paramètre du fichier .ini 
     */
    public function getConfig(){
        return $this->config;
    }
    
    public function getAttribute($section, $attribute) {

        if (isset($this->config[$section][$attribute]))
            return $this->config[$section][$attribute];
        else
            return null;
    }
}

?>
