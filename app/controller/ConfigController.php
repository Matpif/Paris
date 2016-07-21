<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 13/07/16
 * Time: 11:18
 */
class ConfigController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/Admin/Config';
        $this->setTemplate('/admin/config.phtml');
        $this->_title = 'Administration';
        $this->_page = 'Admin';
        $this->setTemplateHeader('/admin/header.phtml');
    }

    /**
     * @return array
     */
    public function getConfig() {
        return ReadIni::getInstance()->getConfig();
    }

    public function saveAction() {
        $post = Access::getRequest();
        // TODO: Save to file config.ini
        if (isset($post['config'])) {
            $writeIni = WriteIni::getInstance();
            $writeIni->writeToFile($post['config']);

            $message = new MessageManager();
            $message->newMessage("La configuration a bien été enregistrée", Message::LEVEL_SUCCESS);
            $this->redirect($this->_url);
        }
    }
}