<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 26/04/16
 * Time: 13:50
 */
class Message
{
    const LEVEL_ERROR = 0;
    const LEVEL_INFO = 1;
    const LEVEL_SUCCESS = 2;

    /**
     * @var string
     */
    private $_message;
    /**
     * @var int
     */
    private $_level;

    /**
     * Message constructor.
     * @param string $_mesagge
     * @param int $_level
     */
    public function __construct($_mesagge, $_level)
    {
        $this->_message = $_mesagge;
        $this->_level = $_level;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->_level;
    }

    /**
     * Retourne le message au format HTML
     * @return string
     */
    public function getMessageHtml() {

        $returned = '';
        switch ($this->_level) {
            case self::LEVEL_ERROR:
                $returned = '<div class="alert alert-danger" role="alert"><b>Oh snap!</b> '.$this->getMessage().'</div>';
                break;
            case self::LEVEL_INFO:
                $returned = '<div class="alert alert-info" role="alert"><b>Warning!</b> '.$this->getMessage().'</div>';
                break;
            case self::LEVEL_SUCCESS:
                $returned = '<div class="alert alert-success" role="alert"><b>Well done!</b> '.$this->getMessage().'</div>';
                break;
        }

        return $returned;
    }
}