<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : pay.php
 */
class pay extends PT_Base{
    protected static $_c;
    /**
     * @var Driver_Pay_Alipay
     */
    protected $handler;

    public function __construct($type='alipay') {
        $this->handler=self::getInstance($type);
    }

    /**
     * @param string $type
     * @return Driver_Pay_Alipay
     */
    public static function getInstance($type='alipay') {
        if (empty(self::$_c[$type])){
            $class='Driver_Pay_'.$type;
            self::$_c[$type]=new $class();
        }
        return self::$_c[$type];
    }

    public function go($param){
        $url=$this->handler->go($param);

        //echo '<br /><a href="' . $url . '">支付</a>';exit;
        //echo '<br /><a href="' . $url . '" target="_blank">支付</a>';exit;
        $this->response->redirect($url);
    }

    public function notify($param) {
        return $this->handler->notify($param);
    }
}