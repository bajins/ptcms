<?php

class PT_Input extends PT_Base {

    public function get($name, $type = 'int', $default = null) {
        return $this->param($name, $type, $default, $_GET);
    }

    public function post($name, $type = 'int', $default = null) {
        return $this->param($name, $type, $default, $_POST);
    }

    public function request($name, $type = 'int', $default = null) {
        return $this->param($name, $type, $default, $_REQUEST);
    }

    public function put($name, $type = 'int', $default = null) {
        static $input = null;
        if ($input === null) parse_str(file_get_contents('php://input'), $input);
        return $this->param($name, $type, $default, $input);
    }

    public function server($name, $type = 'int', $default = null) {
        return $this->param($name, $type, $default, $_SERVER);
    }

    public function globals($name, $type = 'int', $default = null) {
        return $this->param($name, $type, $default, $GLOBALS);
    }

    public function cookie($name, $type = 'int', $default = null) {
        return $this->param($this->config->get('cookie_prefix', '') . $name, $type, $default, $_COOKIE);
    }

    public function session($name, $type = 'int', $default = null) {
        return $this->param($name, $type, $default, $GLOBALS);
    }

    public function files($name, $type = 'int', $default = null) {
        return $this->param($name, $type, $default, $_FILES);
    }

    public function has($name, $type = 'request') {

    }

    public function param($name, $filter = 'int', $default = null, $param = array()) {
        $value = isset($param[ $name ]) ? $param[ $name ] : null;
        return $this->filter->filter($value, $filter, $default);
    }
}
class input extends PT_input{}