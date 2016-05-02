<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 25/04/16
 * Time: 11:43
 */
class Controller
{
    const PATH_TEMPLATE = '../view/template';

    /**
     * @var string
     */
    private $_header;
    /**
     * @var string
     */
    private $_footer;
    /**
     * @var String
     */
    protected $_url;
    /**
     * @var string
     */
    private $_rootUrl;
    /**
     * Path of template
     * @var string
     */
    private $_template;
    /**
     * @var string
     */
    protected $_title;
    /**
     * @var string
     */
    protected $_page;

    private static $_instance;
    public static function getController($controller) {
        if (!isset(self::$_instance[$controller]) || self::$_instance[$controller] == null) {
            self::$_instance[$controller] = new $controller;
        }

        return self::$_instance[$controller];
    }

    function __construct()
    {
        $this->_url = '';
        $this->_rootUrl = ReadIni::getInstance()->getAttribute('general', 'root_url');
        $this->setTemplate('/default.phtml');
        $this->setTemplateHeader('/header.phtml');
        $this->setTemplateFooter('/footer.phtml');
        $this->_title = 'Paris Euro 2016';

    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $returned = '';
        if (file_exists($this->_template)){
            ob_start();
            include($this->_template);
            $returned = ob_get_contents();
            ob_end_clean();
        }

        return $returned;
    }

    /**
     * @return string
     */
    public function getHeader() {
        $returned = '';
        if (file_exists($this->_header)){
            ob_start();
            include($this->_header);
            $returned = ob_get_contents();
            ob_end_clean();
        }

        return $returned;
    }

    /**
     * @return string
     */
    public function getFooter() {
        $returned = '';
        if (file_exists($this->_footer)){
            ob_start();
            include($this->_footer);
            $returned = ob_get_contents();
            ob_end_clean();
        }

        return $returned;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @return String
     */
    public function getUrl() {
        return $this->_url;
    }

    /**
     * @param $action string
     * @return string
     */
    public function getUrlAction($action) {
        return $this->getUrl().'/'.$action;
    }

    /**
     * @param $template string
     */
    public function setTemplate($template) {
        $this->_template = self::PATH_TEMPLATE.$template;
    }

    protected  function setTemplateHeader($template) {
        $this->_header = self::PATH_TEMPLATE.$template;
    }

    protected  function setTemplateFooter($template) {
        $this->_footer = self::PATH_TEMPLATE.$template;
    }

    /**
     * @param $file string
     * @return string
     */
    public function getUrlfile($file) {
        return $this->_rootUrl.$file;
    }
}