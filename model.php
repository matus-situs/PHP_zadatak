<?php
require_once("connection.php");

trait Timestamps {
    private function addTimestamps() {
        $sql = " created_at DATETIME DEFAULT CURRENT_TIMESTAMP,"
        ." updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,"
        ." deleted_at DATETIME DEFAULT NULL";
        return $sql;
    }
    private function stringExists() {
        return "deleted_at IS NULL";
    }
    protected function deleteModel() {
        $sql = "UPDATE ".$this->table." SET ".$this->table.".deleted_at=CURRENT_DATE";
        $this->connection->query($sql);
    }
}

class Model {
    use Timestamps;
    protected $attributes = [];
    protected $allowed = [];
    protected $table;
    private $database;
    private $connection;
    public function __construct() {
        $this->database = new Connection;
        $this->connection = $this->database->connectDB("localhost", "test", null, null);
    }
    protected function toArray() {
        return get_object_vars($this);
    }
    public function __get($name) {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
    }
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
    public function __toString() {
        $string = "Table name: ".$this->table.". ";
        $string .= "Attributes: ";
        foreach ($this->attributes as $attribute) {
            $string .= $attribute.", ";
        }
        return $string;
    } 
    public function __call($method, $arguments) {
        return call_user_func_array($method, $arguments);
    }
    public function __isset($name) {
        return isset($this->attributes[$name]);
    }
    public function __unset($name) {
        if (array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }
    }
    public function __wakeup() {
        $this->connection = $this->database->connectDB("localhost", "test", null, null);
    }
    public function __sleep() {
        return ['table', 'attributes', 'allowed'];
    }
    protected function save() {
        $sql = "SHOW TABLES";
        $tables = $this->connection->query($sql)->fetchAll(PDO::FETCH_GROUP);
        if (in_array($this->table, array_keys($tables))) {
            $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`WHERE `TABLE_NAME`=".$this->table;
            $columns = $this->connection->query($sql)->fetchAll();
            foreach ($this->attributes as $attribute) {
                if (!array_key_exists($attribute, $columns)) {
                    $sql = "ALTER TABLE ".$this->table." ADD COLUMN ".$attribute." TEXT";
                    $this->connection->query($sql);
                }
            }
        } else {
            $sql = "CREATE TABLE ".$this->table." (";
            foreach ($this->attributes as $attribute) {
                $sql .= $attribute." TEXT,";
            }
            $sql .= $this->addTimestamps().");";
            $this->connection->query($sql);
        }
    }
    protected function all() {
        $sql = "SELECT * FROM ".$this->table." WHERE deleted_at ".$this->stringExists();
        return $this->connection->query($sql)->fetchAll();
    }
    protected function fetchByID($id) {
        $sql = "SELECT * FROM ".$this->table." WHERE id=".$id." AND ".$this->stringExists();
        return $this->connection->query($sql)->fetch();
    }
    protected function fetchByAttribute($attribute, $value) {
        $sql = "SELECT ".$attribute." FROM ".$this->table." WHERE ".$attribute."='".$value."' AND ".$this->stringExists();
        return $this->connection->query($sql)->fetchAll();
    }
    protected function forceDelete() {
        $sql = "DROP TABLE ".$this->table;
        $this->connection->query($sql);
    }
}