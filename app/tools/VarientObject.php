<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 22/07/16
 * Time: 10:30
 */
abstract class VarientObject
{

    /**
     * @var array
     */
    protected $_data;

    /**
     * Set data to array
     * @param $data array
     */
    public function setData($data) {
        $this->_data = $data;
    }

    /**
     * Set value in data
     * @param $key string
     * @param $value mixed
     */
    public function setAttribute($key, $value) {
        $this->_data[$key] = $value;
    }

    /**
     * Get data by key
     * @param $key string
     * @return mixed|string
     */
    public function getAttribute($key) {
        if (is_array($this->_data) && array_key_exists($key, $this->_data))
            return $this->_data[$key];
        else
            return '';
    }

    public function __call($method, $args) {
        $key = $this->_underscore(substr($method,3));
        if (isset($args[0]) && (strstr($args[0], '=') || strstr($args[0], '&'))) {
            $key .= '[' . strlen($args[0]) . ']';
        }
        switch (substr($method, 0, 3)) {
            case 'get' :
                $data = $this->getAttribute($key);
                return $data;

            case 'set' :
                $result = $this->setAttribute($key, isset($args[0]) ? $args[0] : null);
                return $result;

            case 'uns' :
                //Varien_Profiler::start('UNS: '.get_class($this).'::'.$method);
                //$result = $this->unsetData($key);
                //Varien_Profiler::stop('UNS: '.get_class($this).'::'.$method);
                //return $result;
                break;
            case 'has' :
                return isset($this->_data[$key]);
        }
        throw new Exception("Invalid method ".get_class($this)."::".$method."(".print_r($args,1).")");
    }

    protected function _underscore($name) {
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        return $result;
    }

}