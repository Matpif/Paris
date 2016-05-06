<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:41
 */
Abstract class Model
{
    /**
     * Model attribute
     * @var array
     */
    protected $_data;

    /**
     * Table model
     * @var string
     */
    protected $_table;

    /**
     * ID of table
     * @var string
     */
    protected $_key;

    /**
     * Instance of SQLite
     * @var SQLite
     */
    private $_db;

    function __construct()
    {
        $this->_db = SQLite::getInstance();
    }

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

    /**
     * Save Model into DB
     * @return bool
     */
    public function save() {
        if ($this->_data[$this->_key]) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * Update dans la table les données de $this->data
     *
     * @return bool
     */
    private function update(){

        $dataParamList = $this->_db->dataParamList($this->_data, $this->_key);
        $query = "UPDATE {$this->_table} SET $dataParamList WHERE {$this->_key}=:{$this->_key}";
        $stmt = $this->_db->prepareQuery($query, $this->_data);
        return ($stmt->execute() !== false);
    }

    /**
     * Insert dans la table les données dans $this->data
     *
     * @return bool
     */
    private function insert(){
        $columns = array_keys($this->_data);
        $fieldList = implode(',',$columns);
        $paramList = ':'.implode(", :",$columns);
        
        $query = "INSERT INTO {$this->_table} ($fieldList) VALUES ($paramList)";

        $stmt = $this->_db->prepareQuery($query, $this->_data);

        if ($stmt->execute() !== false) {
            $this->_data[$this->_key] = $this->_db->lastInsertRowID();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Supprime le model de sa table
     * 
     * @return bool
     */
    public function remove() {
        $query = "DELETE FROM {$this->_table} WHERE {$this->_key}=:{$this->_key}";

        $stmt = $this->_db->prepareQuery($query, [$this->_key => $this->getAttribute($this->_key)]);

        return $stmt->execute() !== false;
    }
}