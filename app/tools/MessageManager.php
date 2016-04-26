<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 26/04/16
 * Time: 13:53
 */
class MessageManager
{
    /**
     * @var array
     */
    private $_messages;

    /**
     * MessageManager constructor.
     */
    public function __construct()
    {
        $this->_messages = [];
        if (isset($_SESSION['messages'])) {
            $this->_messages = unserialize($_SESSION['messages']);
        }
    }

    /**
     * @param string $message
     * @param int $level
     */
    public function newMessage($message, $level = Message::LEVEL_INFO) {
        $this->_messages[] = new Message($message, $level);
        $_SESSION['messages'] = serialize($this->_messages);
    }

    /**
     * Return the last message
     * @return Message|null
     */
    public function getLastMessage() {
        $countMessage = count($this->_messages);
        $message = null;

        if ($countMessage > 0) {
            $message = $this->_messages[$countMessage-1];
            unset($this->_messages[$countMessage-1]);
            $_SESSION['messages'] = serialize($this->_messages);
        }
        return $message;
    }

    /**
     * @return array Message
     */
    public function getMessages() {
        $messages = $this->_messages;
        $this->_messages = [];
        $_SESSION['messages'] = serialize($this->_messages);
        return $messages;
    }
}