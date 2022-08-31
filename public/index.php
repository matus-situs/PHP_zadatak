<?php
namespace Msitaric\Phpzad\public;

use Msitaric\Phpzad\classes\Request as Request;
use Msitaric\Phpzad\classes\Router as Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router(new Request);


$router->get('/users', "UserController::getUsers");