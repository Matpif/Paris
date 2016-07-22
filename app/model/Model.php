<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:41
 */
Abstract class Model extends VarientObject
{
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
            echo $this->_db->lastErrorMsg();
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