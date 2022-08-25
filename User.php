<?php
include_once("Model.php");
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