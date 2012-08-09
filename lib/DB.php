<?php

class DB extends PDO {
    private static $connection;
    protected $queryString;
    protected $options;

    public function __construct($app) {
        $dsn = $app->config('DATABASE_DSN');
        $user = $app->config('DATABASE_USER');
        $passwd = $app->config('DATABASE_PASSWD');
		parent::__construct($dsn, $user, $passwd);
    }

    public static function getInstance($app) {
        if(self::$connection instanceof PDO) {
            return self::$connection;
        }else {
            return self::$connection = new DB($app);
        }
    }

    public function setQuery($sql) {
        $this->queryString = $sql;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function getRow($sql = '', $options = array()) {
        $rows = $this->getRows($sql, $options);
        if(is_array($rows)) {
            return array_pop($rows);
        }else {
            return array();
        }
    }

    public function getOne($sql = '', $options = array()) {
        $row = $this->getRow($sql, $options);
        if(is_array($row)) {
            return array_pop($row);
        }else {
            return $row;
        }
    }

    public function save($sql = '', $options = array()) {
        return (boolean) $this->getStmt($sql, $options);
    }

    public function getStmt($sql = '', $options = array()) {
        if(empty($sql)) {
            $sql = $this->queryString;
        }
        if(empty($options)) {
            $options = $this->options;
        }

        $stmt = $this->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->execute($options)) {
            return $stmt;
        }else {
            $this->dbErrorHandler($sql, $stmt->errorInfo());
        }
    }

    public function getRows($sql = '', $options = array()) {
        if(empty($sql)) {
            $sql = $this->queryString;
        }
        if(empty($options)) {
            $options = $this->options;
        }

        $stmt = $this->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->execute($options)) {
            return $stmt->fetchAll();
        }else {
            $this->dbErrorHandler($sql, $stmt->errorInfo());
        }
    }

    public function getIndexedRows($indexBy, $sql = '', $options = array()) {
        $stmt = $this->getStmt($sql, $options);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->execute($options)) {
            $items = array();
            while($row = $stmt->fetch()) {
                $key = $row[$indexBy];
                unset($row[$indexBy]);
                $fieldsNum = count($row);
                if($fieldsNum == 1) {
                    $items[$key] = array_pop($row);
                }else {
                    $items[$key] = $row;
                }
            }
            return $items;
        }else {
            $this->dbErrorHandler($sql, $stmt->errorInfo());
        }
    }

    public function insert($table, $insertData) {
        if(!is_array($insertData)) {
            return false;
        }
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(",", array_keys($insertData)),
            implode(",", array_fill(0, count($insertData), '?'))
        );
        return $this->getStmt($sql, array_values($insertData));
    }

    private function dbErrorHandler($sql, $errorInfo) {
        throw new exception (sprintf("Database Query Error: %s; Query: %s", $errorInfo[2], $sql)); 
    }
}
