<?php
include_once("model.php");
class User extends Model {
    use Timestamps;
    public function __construct() {
        $this->database = new Connection;
        $this->connection = $this->database->connectDB("localhost", "test", "root", null);
        $this->table = __CLASS__;
        $this->attributes = [
            "Username"  => '',
            "Password" => '',
            "CreationDate" => date("Y-m-d")
        ];
        $this->allowed = [
            "Username" => '',
            "Password" => '',
        ];
    }
}