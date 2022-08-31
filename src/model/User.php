<?php
namespace Msitaric\Phpzad\model;

use Model;
use Timestamps;

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