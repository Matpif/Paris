<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 12/04/16
 * Time: 18:58
 */
class SQLite extends SQLite3
{
    private static $_instance;

    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new SQLite();
        }

        return self::$_instance;
    }

    function __construct()
    {
        parent::__construct(ReadIni::getInstance()->getAttribute("sqlite", "dbname"));
    }

    /**
     * Add param value in query
     * @param $query
     * @param null|array $params
     * @return SQLite3Stmt
     */
    public function prepareQuery($query, $params = null) {
        /**
         * @var $stmt SQLite3Stmt
         */
        $stmt = $this->prepare($query);
       
        if ($stmt !== false && is_array($params)) {
            foreach ($params as $key => $param) {

                if (is_array($param)) {
                    if ($param[1] != null)
                        $stmt->bindValue(":{$key}", $param[1]);
                    else
                        $stmt->bindValue(":{$key}", $param[1], SQLITE3_NULL);
                } else {
                    if ($param != null)
                        $stmt->bindValue(":{$key}", $param);
                    else
                        $stmt->bindValue(":{$key}", $param, SQLITE3_NULL);
                }
            }
        }

        return $stmt;
    }

    /**
     * Créer le string "attribut1 = :attribut1, attribut2 = :attribut2, ..." pour l'update
     * @param $data
     * @param $keyTable
     * @param string $delimiter
     * @return string
     */
    public function dataParamList($data, $keyTable, $delimiter = ', ', $withId = false){
        $string = "";
        foreach($data as $key => $value){
            if($keyTable != $key || $withId) {
                if (is_array($value)) {
                    $signe = $value[0];
                    $string .= "{$key}{$signe}:{$key}{$delimiter}";
                } else {
                    $string .= "{$key}=:{$key}{$delimiter}";
                }
            }
        }
        $string = substr($string, 0, -strlen($delimiter));
        return $string;
    }
}