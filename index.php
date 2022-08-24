<?php
include_once("users.php");

$user = new User();
$user->Username = "marko";
$user->Password = "peric";
$user->save();
$user->delete(7);
$user->addTimestamps();
$user->forceDelete(8);
echo "<br>";
var_dump($user->fetchByID(8));
echo "<br>";
var_dump($user->fetchByAttribute("Username", "pero"));
echo "<br>";
var_dump($user->all());
echo "<br>";
