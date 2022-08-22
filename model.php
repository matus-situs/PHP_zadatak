<?php

class Model {
    private $attributes = [];
    private $allowed = [];
    private $table;
    public function toArray() {
        return get_object_vars($this);
    }
    public function __get($name) {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
    }
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
    //public function __toString() {} 
    //public function __call($method, $arguments) {}
    public function __isset($name) {
        return isset($this->attributes[$name]);
    }
    public function __unset($name) {
        if (array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }
    }
    public function __wakeup() {
        //spoji na bazu
    }
    public function __sleep() {
        return ['attributes', 'table', 'allowed'];
    }
    public function save() {

    }
}
