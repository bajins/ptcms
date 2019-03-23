<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : pinyin.php
 */
class pinyin {

    protected static $data = null;

    protected static function getdata() {
        if (self::$data === null) {
            $fp = fopen(dirname(__FILE__) . '/data/pinyin.dat', 'r') or exit('读取字典失败');
            while (!feof($fp)) {
                $line = trim(fgets($fp));
                self::$data[$line[0] . $line[1]] = substr($line, 3, strlen($line) - 3);
            }
            fclose($fp);
        }
        return self::$data;
    }

    /**
     * 转换成拼音
     *
     * @param string $str     待转换的字符串
     * @param bool $isfirst   是否只需要首字符
     * @param string $default 匹配不到默认显示字符
     * @return string
     */
    public static function change($str, $isfirst = false, $default = '0') {
        $str = iconv('UTF-8', 'GBK//ignore', $str);
        $data = self::getdata();
        $restr = '';
        for ($i = 0, $j = strlen($str); $i < $j; $i++) {
            if (ord($str[$i]) > 0x80) {
                $c = $str[$i] . $str[$i + 1];
                ++$i;
                if (isset($data[$c])) {
                    $restr .= $isfirst ? $data[$c]{0} : (C('pinyin_uc_first',null,1)?ucfirst($data[$c]):$data[$c]);
                } else {
                    $restr .= $default;
                }
            } elseif (preg_match("/[\w\-]/i", $str[$i])) {
                $restr .= $str[$i];
            } else {
                $restr .= $default;
            }
        }
        return $restr;
    }
}