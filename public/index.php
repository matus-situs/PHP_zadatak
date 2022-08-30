<?php

include_once '../classes/Request.php';
include_once '../classes/Router.php';
include_once "../controller/UserController.php";
$router = new Router(new Request);


$router->get('/users', UserController::getUsers());