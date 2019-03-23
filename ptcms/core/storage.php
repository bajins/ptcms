<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : Storage.php
 */
class PT_Storage extends PT_Base {

    protected static $handler = null;

    /**
     * @param string $type
     * @return Driver_Storage_File
     */
    static public function getInstance($type = '') {
        $type = $type ? $type : PT_Base::getInstance()->config->get('storage_type', 'file') . '_' . PT_Base::getInstance()->config->get('storage_path', 'storage');
        if (empty(self::$handler[$type])) {
            $class                = 'Driver_Storage_' . PT_Base::getInstance()->config->get('storage_type');
            self::$handler[$type] = new $class(PT_Base::getInstance()->config->get('storage_option', array()));
        }
        return self::$handler[$type];
    }

    public static function exist($file) {
        return self::getInstance()->exist($file);
    }

    public static function write($file, $content) {
        if ($content !== false)
            return self::getInstance()->write($file, $content);
        return false;
    }

    public static function read($file) {
        return self::getInstance()->read($file);
    }

    public static function append($file, $content) {
        if ($content !== false)
            return self::getInstance()->read($file, $content);
        return false;
    }

    public static function remove($file) {
        return self::getInstance()->remove($file);
    }

    public static function getUrl($file) {
        return self::getInstance()->getUrl($file);
    }

    public static function getPath($file) {
        return self::getInstance()->getPath($file);
    }

    public static function error() {
        return self::getInstance()->error();
    }
}
class storage extends PT_storage{}