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
        if (array_key_exists($key, $this->_data))
            return $this->_data[$key];
        else
            return '';
    }

    /**
     * Save Model into DB
     */
    public function save() {
        if ($this->_data[$this->_key]) {
            $this->insert();
        } else {
            $this->update();
        }
    }

    /**
     * Update dans la table les données de $this->data
     */
    private function update(){

        $dataParamList = $this->_db->dataParamList($this->_data, $this->_key);
        $query = "UPDATE {$this->_table} SET $dataParamList WHERE {$this->_key}=:{$this->_key}";

        $stmt = $this->_db->prepareQuery($query, $this->_data);
        return $stmt->execute();
    }

    /**
     * Insert dans la table les données dans $this->data
     */
    private function insert(){
        $columns = array_keys($this->_data);
        $fieldList = implode(',',$columns);
        $paramList = ':'.implode(", :",$columns);
        
        $query = "INSERT INTO {$this->_tabale} ($fieldList) VALUES ($paramList)";

        $stmt = $this->_db->prepareQuery($query, $this->_data);

        if ($stmt->execute()) {
            $this->_data[$this->_key] = $this->_db->lastInsertRowID();
            return true;
        } else {
            return false;
        }
    }
}