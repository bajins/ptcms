<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : plugin.php
 */
class PT_Plugin extends PT_Base {

    protected $pt;
    //子类hook点
    public static $_tags = array();

    public function __construct() {
        $this->pt=PT_Base::getInstance();
    }
    /**
     * 调用插件
     *
     * @param $tag
     * @param null $param
     */
    public static function call($tag, &$param = null) {
        if (isset(self::$_tags[$tag])) {
            foreach (self::$_tags[$tag] as $name) {
                $classname = $name . 'Plugin';
                $handler = new $classname();
                $handler->run($param);
            }
        }
    }

    /**
     * 注册插件方法
     *
     * @param array $data
     */
    public static function register(array $data) {
        foreach ($data as $tag => $var) {
            self::add($tag, $var);
        }
    }

    /**
     * 添加插件方法
     *
     * @param $tag
     * @param $var
     */
    public static function add($tag, $var) {
        if (!is_array($var)) $var = array($var);
        if (isset(self::$_tags[$tag])) {
            self::$_tags[$tag] = array_unique(array_merge(self::$_tags[$tag], $var));
        } else {
            self::$_tags[$tag] = $var;
        }
    }

    /**
     * 删除插件方法
     *
     * @param $tag
     * @param $var
     */
    public static function del($tag, $var) {
        if (isset(self::$_tags[$tag])) {
            $key = array_search($var, self::$_tags[$tag]);
            if ($key !== false) {
                unset(self::$_tags[$tag][$key]);
            }
            if (empty(self::$_tags[$tag])) unset(self::$_tags[$tag]);
        }
    }

    /**
     * 获取插件列表
     *
     * @param string $tag
     * @return array
     */
    public static function get($tag = '') {
        if (empty($tag)) return self::$_tags;
        if (isset(self::$_tags[$tag])) {
            return self::$_tags[$tag];
        } else {
            return array();
        }
    }

    /**
     * 获取开启的所有的插件
     * @return array
     */
    public static function getlist() {
        $list = array();
        foreach (self::$_tags as $v) {
            $list = array_merge($list, $v);
        }
        return array_unique($list);
    }


    // 返回插件的配置项
    public function loadconfig() {
        $name = substr(get_class($this), 0, -6);
        $list=pt::import(APP_PATH.'/common/plugin/'.$name.'/config.php');
        if ($list){
            $config=array();
            foreach($list as $v){
                $config[$v['key']]=$v['value'];
            }
            $this->pt->config->set(array('pluginconfig'=>$config));
            return $config;
        }
        return array();
    }
}


class plugin extends PT_plugin{}