<?php
include_once("User.php");

$user = new User();
/*$user->Username = "orginal";
$user->Password = "orginal";
$user->save();
$user->Username = "promjenjen";
$user->Password = "promjenjen";*/
$user->addTimestamps();
echo "<br>";
var_dump($user->fetchByID(12));
echo "<br>";
//var_dump($user->fetchByAttribute("Username", "pero"));
echo "<br>";
//var_dump($user->all());
echo "<br>";
