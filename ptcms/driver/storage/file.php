<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : File.php
 */
class Driver_Storage_File extends PT_Base {

    protected static $path = null;
    protected static $url  = null;

    public function __construct($domain = '') {
        self::$path = PT_ROOT . '/' . $this->config->get('storage_path') . '/';
        self::$url  = PT_DIR . '/' . $this->config->get('storage_path') . '/';
    }

    public function exist($file) {
        return is_file(self::$path . $file);
    }

    public function write($file, $content) {
        return F(self::$path . $file, $content);
    }

    public function read($file) {
        return F(self::$path . $file);
    }

    public function append($file, $content) {
        return F(self::$path . $file, $content, FILE_APPEND);
    }

    public function remove($file) {
        return @unlink(self::$path . $file);
    }

    public function getUrl($file) {
        return self::$url . ``;
    }

    public function getPath($file) {
        return self::$path . $file;
    }

    public function error() {
        return '';
    }
}