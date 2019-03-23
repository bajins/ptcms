<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : Cache.php
 */
class PT_Cache{

    protected static $handler = null;

    /**
     * @param string $type;
     * @return Driver_Cache_File
     */
    public static function getInstance($type = '') {
        $type = $type ? $type : PT_Base::getInstance()->config->get('cache_driver', 'file');
        if (empty(self::$handler[$type])) {
            $class                = 'Driver_Cache_' . PT_Base::getInstance()->config->get('cache_driver');
            self::$handler[$type] = new $class(PT_Base::getInstance()->config->get('cache_option', array()));
        }
        return self::$handler[$type];
    }

    public static function set($key, $value, $time = 0) {
        $GLOBALS['_cacheWrite']++;
        return self::getInstance()->set($key, $value, $time);
    }

    public static function get($key) {
        $GLOBALS['_cacheRead']++;
        return self::getInstance()->get($key);
    }

    public static function rm($key) {
        return self::getInstance()->rm($key);
    }

    public static function clear() {
        self::getInstance()->clear();
    }
}
class cache extends PT_cache{}