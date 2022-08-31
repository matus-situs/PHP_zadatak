<?php
include "../model/User.php";
require_once "../vendor/autoload.php";

class UserController {
    public function getUsers() {
        $loader = new \Twig\Loader\FilesystemLoader('/path/to/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
        ]);
        $users = new User;
        $template = $twig->load("index.html");
        echo $template->render($users->all());
    }
    public function returnUser($id) {
        $users = new User;
        return json_encode($users->fetchByID($id));
    }
}