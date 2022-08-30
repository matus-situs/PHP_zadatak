<?php
include "../model/User.php";
class UserController {
    public static function getUsers() {
        $users = new User;
        return json_encode($users->all());
    }
    public static function returnUser($id) {
        $users = new User;
        return json_encode($users->fetchByID($id));
    }
}