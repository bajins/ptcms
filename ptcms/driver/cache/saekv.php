<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : Saekv.php
 */
class Driver_Cache_Saekv {

    protected static $handler = null;

    public function __construct($option = array()) {
        self::$handler = new SaeKV();
        self::$handler->init();
    }

    public function set($key, $value, $time = 0) {
        if (self::$handler->set($key, $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function get($key) {
        return self::$handler->get($key);
    }

    public function rm($key) {
        return self::$handler->delete($key);
    }

    public function clear() {

    }
}