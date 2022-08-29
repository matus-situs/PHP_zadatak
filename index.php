<?php
include_once("User.php");

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

$user = new User();

if (strpos($url,'/users/') !== false) {
    if(isset($_GET["user_id"])) {
        var_dump($user->fetchByID($_GET["user_id"]));
    } else {
        var_dump($user->all());
    }
}


/*$user->Username = "orginal";
$user->Password = "orginal";
$user->save();
$user->Username = "promjenjen";
$user->Password = "promjenjen";
$user->addTimestamps();
echo "<br>";
var_dump($user->fetchByID(12));
echo "<br>";
var_dump($user->fetchByAttribute("Username", "pero"));
echo "<br>";
var_dump($user->all());
echo "<br>";*/
