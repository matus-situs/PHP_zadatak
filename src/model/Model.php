<?php
namespace Msitaric\Phpzad\classes;
use Connection;
use Exception;
use PDO;

trait Timestamps
{
    public function addTimestamps()
    {
        $sql = "SHOW COLUMNS FROM " . $this->table . " LIKE 'deleted_at'";
        $column = $this->connection->query($sql)->fetch();
        if (!$column) {
            $sql = "ALTER TABLE " . $this->table
                . " ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP,"
                . "ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,"
                . "ADD COLUMN deleted_at DATETIME DEFAULT NULL";
            $this->connection->query($sql);
        }
    }
    public function delete($id)
    {
        $sql = "SHOW COLUMNS FROM " . $this->table . " LIKE 'deleted_at'";
        $column = $this->connection->query($sql)->fetch();
        if ($column) {
            $sql = "UPDATE " . $this->table . " SET " . $this->table . ".deleted_at=CURRENT_DATE WHERE id=" . $id;
            $this->connection->query($sql);
        }
    }
}

abstract class Model
{
    protected $attributes = [];
    protected $allowed = [];
    protected $table;
    protected $database;
    protected $connection;
    public function __construct()
    {
        $this->database = new Connection;
        $this->connection = $this->database->connectDB("localhost", "test", "root", null);
    }
    public function toArray()
    {
        return get_object_vars($this);
    }
    public function __get($name)
    {
        if (array_key_exists($name, $this->allowed)) {
            return $this->allowed[$name];
        }
    }
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->allowed)) {
            $this->allowed[$name] = $value;
        } else {
            throw new Exception("No permission to set this variable " . $name);
        }
    }
    public function __toString()
    {
        $string = "Table name: " . $this->table . ". ";
        $string .= "Attributes: ";
        foreach ($this->attributes as $key => $attribute) {
            $string .= $key . " - " . $attribute . ", ";
        }
        return $string;
    }
    public function __call($method, $arguments)
    {
        return call_user_func($method, $arguments);
    }
    public function __isset($name)
    {
        return isset($this->allowed[$name]);
    }
    public function __unset($name)
    {
        if (array_key_exists($name, $this->allowed)) {
            unset($this->allowed[$name]);
        }
    }
    public function __wakeup()
    {
        $this->connection = $this->database->connectDB("localhost", "test", null, null);
    }
    public function __sleep()
    {
        return ['table', 'attributes', 'allowed'];
    }
    public function save()
    {
        foreach ($this->allowed as $key => $attribute) {
            $this->attributes[$key] = $this->allowed[$key];
        }

        if (!array_key_exists("id", $this->attributes)) {
            $sql = "INSERT INTO " . $this->table . " (";

            foreach ($this->attributes as $key => $attribute) {
                $sql .= $key . ",";
            }

            $sql = rtrim($sql, ",");
            $sql .= ") VALUES (";

            foreach ($this->attributes as $key => $attribute) {
                $sql .= "'" . $attribute . "',";
            }

            $sql = rtrim($sql, ",");
            $sql .= ")";
            $this->connection->query($sql);

            $sql = "SELECT * FROM " . $this->table . " WHERE ";

            foreach ($this->attributes as $key => $attribute) {
                $sql .= $key . "='" . $attribute . "' AND ";
            }

            $sql = rtrim($sql, "AND ");
            $sql .= "ORDER BY id DESC LIMIT 1";
            $row = $this->connection->query($sql)->fetch();
            $this->attributes["id"] = $row["id"];
        } else {
            $sql = "UPDATE ".$this->table." SET ";

            foreach ($this->attributes as $key => $attribute) {
                $sql .= $key . "='" . $attribute . "', ";
            }

            $sql = rtrim($sql, ", ");
            $sql .= " WHERE id=".$this->attributes["id"];
            $this->connection->query($sql);
        }
    }
    public function all()
    {
        $sql = "SELECT * FROM ".$this->table;
        return $this->connection->query($sql)->fetchAll();
    }
    public function fetchByID($id)
    {
        $sql = "SHOW COLUMNS FROM " . $this->table . " LIKE ?";
        $statement = $this->connection->prepare($sql);
        $data = "deleted_at";
        $statement->bindParam(1, $data, PDO::PARAM_STR);
        $statement->execute();
        $column = $statement->fetch();
        if (!$column) {
            $sql = "SELECT * FROM " . $this->table . " WHERE id=?";
        } else {
            $sql = "SELECT * FROM " . $this->table . " WHERE id=? AND deleted_at IS NULL";
        }

        $statement = $this->connection->prepare($sql);
        $statement->bindParam(1, $id, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
    public function fetchByAttribute($attribute, $value)
    {
        $sql = "SHOW COLUMNS FROM " . $this->table . " LIKE 'deleted_at'";
        $column = $this->connection->query($sql)->fetch();

        if (!$column) {
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $attribute . "='" . $value . "'";
        } else {
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $attribute . "='" . $value . "' AND deleted_at IS NULL";
        }

        return $this->connection->query($sql)->fetchAll();
    }
    public function forceDelete($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id=" . $id;
        $this->connection->query($sql);
    }
}
