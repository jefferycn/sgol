<?php

class DB extends PDO {
    private static $connection;
    protected $folderSql;

    public function __construct($app) {
        $dsn = $app->config('DATABASE_DSN');
        $user = $app->config('DATABASE_USER');
        $passwd = $app->config('DATABASE_PASSWD');
        $this->folderSql = $app->config('FOLDER_SQL');
		parent::__construct($dsn, $user, $passwd);
    }

    public static function getInstance($app) {
        if(self::$connection instanceof PDO) {
            return self::$connection;
        }else {
            return self::$connection = new DB($app);
        }
    }

    protected function getSql($name) {
        $file = $this->folderSql . $name . ".sql";
        if(!file_exists($file)) {
            throw new exception("sql $name not defined.");
        }
        return file_get_contents($file);
    }

    public function getRow($name = '', $options = array()) {
        $rows = $this->getRows($name, $options);
        if(is_array($rows)) {
            return array_pop($rows);
        }else {
            return array();
        }
    }

    public function getOne($name, $options = array()) {
        $row = $this->getRow($name, $options);
        if(is_array($row)) {
            return array_pop($row);
        }else {
            return $row;
        }
    }

    public function save($name, $options = array()) {
        return (boolean) $this->getStmt($name, $options);
    }

    public function getStmt($name, $options = array()) {
        $sql = $this->getSql($name);

        $stmt = $this->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->execute($options)) {
            return $stmt;
        }else {
            $this->dbErrorHandler($sql, $stmt->errorInfo());
        }
    }

    public function getRows($name, $options = array()) {
        $sql = $this->getSql($name);

        $stmt = $this->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->execute($options)) {
            return $stmt->fetchAll();
        }else {
            $this->dbErrorHandler($sql, $stmt->errorInfo());
        }
    }

    public function getIndexedRows($indexBy, $name, $options = array()) {
        $stmt = $this->getStmt($name, $options);
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
            $this->dbErrorHandler($name, $stmt->errorInfo());
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
