<?php

class PT_Session {

    protected $pt;

    public function __construct() {
        $this->pt = PT_Base::getInstance();
    }

    public function start($name = array()) {
        $name = array_merge($this->pt->config->get('session', array()), $name);
        if ($this->pt->config->get('var_session_id') && isset($_REQUEST[$this->pt->config->get('var_session_id')])) {
            session_id($_REQUEST[$this->pt->config->get('var_session_id')]);
        } elseif (isset($name['id'])) {
            session_id($name['id']);
        }
        if (empty($name['type']) && $this->pt->config->get('driver_session')) {
            $name['type'] = $this->pt->config->get('driver_session');
        }
        if (isset($name['name'])) session_name($name['name']);
        if (isset($name['path'])) session_save_path($name['path']);
        if (isset($name['domain'])) ini_set('session.cookie_domain', $name['domain']);
        if (isset($name['expire'])) ini_set('session.gc_maxlifetime', $name['expire']);
        if (isset($name['use_trans_sid'])) ini_set('session.use_trans_sid', $name['use_trans_sid'] ? 1 : 0);
        if (isset($name['use_cookies'])) ini_set('session.use_cookies', $name['use_cookies'] ? 1 : 0);
        if (isset($name['cache_limiter'])) session_cache_limiter($name['cache_limiter']);
        if (isset($name['cache_expire'])) session_cache_expire($name['cache_expire']);
        if (isset($name['type'])) {
            $type   = $name['type'];
            $class  = 'Driver_Session_' . $type;
            $hander = new $class();
            session_set_save_handler(
                array(&$hander, "open"),
                array(&$hander, "close"),
                array(&$hander, "read"),
                array(&$hander, "write"),
                array(&$hander, "destroy"),
                array(&$hander, "gc"));
        }
        session_start();
    }

    public function __set($name, $value) {
        return $this->set($name, $value);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function get($name = '', $default = null) {
        if ($name == '') return $_SESSION;
        //数组模式 找到返回
        if (strpos($name, '.')) {
            //数组模式 找到返回
            $c      = $_SESSION;
            $fields = explode('.', $name);
            foreach ($fields as $field) {
                if (!isset($c[$field])) return $default;
                $c = $c[$field];
            }
            return $c;
        } elseif (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return $default;
        }
    }

    public function set($key, $value = '') {
        $_SESSION[$key] = $value;
        return true;
    }

    public function rm($key) {
        if (!isset($_SESSION[$key])) {
            return false;
        }

        unset($_SESSION[$key]);

        return true;
    }

    /**
     * 清空session值
     *
     * @access public
     * @return void
     */
    public static function clear() {

        $_SESSION = array();
    }

    /**
     * 注销session
     *
     * @access public
     * @return void
     */
    public static function destory() {

        if (session_id()) {
            unset($_SESSION);
            session_destroy();
        }
    }

    /**
     * 当浏览器关闭时,session将停止写入
     *
     * @access public
     * @return void
     */
    public static function close() {

        if (session_id()) {
            session_write_close();
        }
    }
}

class session extends PT_session {

}