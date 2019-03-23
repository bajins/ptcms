<?php

/**
 * Class PT_Api
 * @property Driver_Api_Ptcms $handle
 */
class PT_Api extends PT_Base {

    protected $apiurl = '';
    protected $name = '';
    protected $param = array();
    protected $header = array();

    public function __construct() {
        $this->apiurl = $this->config->get('apiserver');
        $driverclass = 'Driver_Api_' . $this->config->get('driver_api', 'ptcms');
        $this->handle = new $driverclass;
    }

    public function __call($method, $param = array()) {
        $param = ($param == array()) ? array() : $param['0'];
        return $this->call($method, $param);
    }

    public function method($var) {
        $this->name = $var;
        return $this;
    }

    public function param($var) {
        $this->param = $var;
        return $this;
    }

    public function header($var) {
        $this->header = $var;
        return $this;
    }

    public function get() {
        return $this->call($this->name, $this->param, 'GET', $this->header);
    }

    public function post() {
        return $this->call($this->name, $this->param, 'POST', $this->header);
    }

    public function put() {
        return $this->call($this->name, $this->param, 'PUT', $this->header);
    }

    public function delete() {
        return $this->call($this->name, $this->param, 'DELETE', $this->header);
    }

    // 调用API
    public function call($name, $param = array(), $method = 'GET', $header = array()) {

        if (strpos($name, 'http') === 0) {
            $url = $name;
        } else {
            $url = $this->apiurl . '/' . $name;
        }
        //get方式则把参数加到url里面
        if ($method == 'GET' && $header != array()) {
            $param = array_merge($param, $header);
        }
        return $this->handle->call($url, $param, $method, $header);
    }
}

class Api extends PT_Api{}