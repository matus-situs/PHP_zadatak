<?php
class Connection {
    public function connectDB($host, $database, $username, $password) {
        return new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
    }
}