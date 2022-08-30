<?php
include_once("../model/Model.php");
class User extends Model {
    use Timestamps;
    protected $table = __CLASS__;
    protected $attributes = [
        "Username" => '',
        "Password" => '',
        "Status" => 'active'
    ];
    protected $allowed = [
        "Username" => '',
        "Password" => '',
    ]; 
}