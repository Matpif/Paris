<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 19:22
 */
abstract class Collection implements Iterator
{
    /**
     * Table model
     * @var string
     */
    protected $_table;

    /**
     * @var array(Model)
     */
    protected $_rows;

    /**
     * ID of table
     * @var string
     */
    protected $_key;

    /**
     * Model of collection
     * @var string
     */
    protected $_model;

    /**
     * Instance of SQLite
     * @var SQLite
     */
    private $_db;

    /**
     * Possition in array for iterator
     * @var int
     */
    private $_position = 0;

    function __construct()
    {
        $this->_db = SQLite::getInstance();
    }

    /**
     * Load Models by attributes values
     * @param $attributes array
     * @return $this
     */
    public function load($attributes) {

        $dataParamList = $this->_db->dataParamList($attributes, $this->_key, ' AND ');
        $query = "SELECT * FROM {$this->_table} WHERE ".$dataParamList;
        
        $stmt = $this->_db->prepareQuery($query, $attributes);
        $results = $stmt->execute();

        unset($this->_rows);

        while ($result = $results->fetchArray(SQLITE3_ASSOC)) {
            /**
             * @var $model Model
             */
            $model = new $this->_model;
            $model->setData($result);
            $this->_rows[] = $model;
        }

        return $this;
    }

    /**
     * Select all attributes
     * @param $id
     * @return null | Model
     */
    public function loadById($id) {

        $query = "SELECT * FROM {$this->_table} WHERE {$this->_key} = :{$this->_key}";
        $stmt = $this->_db->prepareQuery($query, [$this->_key => $id]);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        $model = null;
        if ($result && is_array($result)) {
            /**
             * @var $model Model
             */
            $model = new $this->_model;
            $model->setData($result);
        }

        return $model;
    }

    public function getFirstRow() {
        return (isset($this->_rows[0]))?$this->_rows[0]:null;
    }

    public function count() {
        return count($this->_rows);
    }

    public function current() {
        return $this->_rows[$this->_position];
    }
    public function next() {
        ++$this->_position;
    }
    public function key() {
        return $this->_position;
    }
    public function valid() {
        return isset($this->_rows[$this->_position]);
    }
    public function rewind() {
        $this->_position = 0;
    }
}