<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : Memcache.php
 */
class Driver_Cache_Memcache extends PT_Base {

    protected static $handler = null;
    protected static $prefix  = null;

    public function __construct($option = array()) {
        if ((function_exists('saeAutoLoader') or function_exists('sae_auto_load')) && function_exists('memcache_init')) {
            self::$handler = memcache_init();
        } elseif (isset($_SERVER['HTTP_BAE_LOGID'])) {
            include PT_PATH . '/library/bae/BaeMemcache.class.php';
            $cacheid       = $this->config->get('bae_cache_id');
            $host          = $this->config->get('bae_cache_host');
            $port          = $this->config->get('bae_cache_port');
            $user          = $this->config->get('bae_cache_user');
            $pwd           = $this->config->get('bae_cache_pwd');
            self::$handler = new BaeMemcache($cacheid, $host . ': ' . $port, $user, $pwd);
        } else {
            self::$handler = new Memcache();
            self::$handler->connect($this->config->get('memcache_host', '127.0.0.1'), $this->config->get('memcache_port', '11211'));
        }
        if (!self::$handler) {
            PT_Log::record('链接缓存驱动失败');
        }
        self::$prefix = $this->config->get('cache_prefix', substr(md5(PT_ROOT), 3, 3) . '_');
    }

    public function set($key, $value, $time = 0) {
        return self::$handler->set(self::$prefix . $key, $value, MEMCACHE_COMPRESSED, $time);
    }

    public function get($key) {
        $return = self::$handler->get(self::$prefix . $key);
        if ($return === false) return null;
        return $return;
    }

    public function rm($key) {
        return self::$handler->delete(self::$prefix . $key);
    }

    public function inc($key, $num = 1) {
        return self::$handler->increment(self::$prefix . $key, $num);
    }

    public function dec($key, $num = 1) {
        return self::$handler->decrement(self::$prefix . $key, $num);
    }

    public function clear() {
        self::$handler->flush();
    }
}