<?php

// todo f函数改为storage
class PT_log extends PT_Base {

    public static $logstr = array();

    /**
     * 直接写日志
     *
     * @param $str
     * @param string $type
     */
    public static function write($str, $type = 'pt') {
        $str = "[" . date('Y-m-d H:i:s') . "] " . $str . PHP_EOL;
        F(CACHE_PATH . '/log/' . $type . '_' . date('Ymd') . '.txt', $str, FILE_APPEND);
    }

    /**
     * 记录日志
     *
     * @param $str
     * @param string $type
     */
    public static function record($str, $type = 'pt') {
        self::$logstr[$type][] = "[" . date('Y-m-d H:i:s') . "] " . $str . PHP_EOL;
    }

    /**
     * 把记录的日志写入文件
     */
    public static function build() {

        foreach (self::$logstr as $type => $log) {
            $file = CACHE_PATH . '/log/' . $type . '_' . date('Ymd') . '.txt';
            if (is_array($log)) {
                foreach ($log as $v) {
                    F($file, $v, FILE_APPEND);
                }
            } else {
                F($file, $log, FILE_APPEND);
            }
        }
    }
}
class log extends PT_log{}