<?php
namespace Msitaric\Phpzad\controller;

use Msitaric\Phpzad\model\User;

class UserController {
    public function getUsers() {
        $loader = new \Twig\Loader\FilesystemLoader('/path/to/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
        ]);
        $users = new User;
        $template = $twig->load("index.php");
        echo $template->render($users->all());
    }
}