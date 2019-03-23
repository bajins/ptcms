<?php

class PT_Cookie extends PT_Base {

    protected $option;

    public function __construct($config=array()) {
        $this->option = array(
            'prefix' => $this->config->get('cookie_prefix', 'PTCMS_'),
            // cookie 保存时间
            'expire' => intval($this->config->get('cookie_expire', 2592000)),
            // cookie 保存路径
            'path'   => $this->config->get('cookie_path', '/'),
            // cookie 有效域名
            'domain' => $this->config->get('cookie_domain'),
        );
        if (!$config) $this->option = array_merge($this->option, $config);
    }

    public function get($name, $default=null) {
        $name = $this->option['prefix'] . $name;
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        } else {
            return $default;
        }
    }

    public function set($name, $value = '', $option = null) {
        if (!is_null($option)) {
            if (is_numeric($option))
                $option = array('expire' => $option);
            elseif (is_string($option))
                parse_str($option, $option);
            $config = array_merge($this->option, array_change_key_case($option));
        } else {
            $config = $this->option;
        }
        $name = $this->option['prefix'] . $name;
        $expire = !empty($config['expire']) ? time() + $config['expire'] : 0;
        setcookie($name, $value, $expire, $config['path'], $config['domain']);
        $_COOKIE[$name] = $value;
    }

    public function rm($name) {
        $name = $this->option['prefix'] . $name;
        setcookie($name, '', time() - 3600, $this->option['path'], $this->option['domain']);
        // 删除指定cookie
        unset($_COOKIE[$name]);
    }

    public function del($name) {
         $this->rm($name);
    }

    public function clear() {
        foreach ($_COOKIE as $key => $val) {
            if (0 === stripos($key, $this->option['prefix'])) {
                setcookie($key, '', time() - 3600, $this->option['prefix']['path'], $this->option['prefix']['domain']);
                unset($_COOKIE[$key]);
            }
        }
        return true;
    }
}

class cookie extends PT_cookie{}