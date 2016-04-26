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
                $returned = '<p class="bg-danger">'.$this->getMessage().'</p>';
                break;
            case self::LEVEL_INFO:
                $returned = '<p class="bg-info">'.$this->getMessage().'</p>';
                break;
            case self::LEVEL_SUCCESS:
                $returned = '<p class="bg-success">'.$this->getMessage().'</p>';
                break;
        }

        return $returned;
    }
}