<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : dc.php
 */
class Dc{

    static $_data = array();

    //更新信息
    static public function update($type, $id, $data) {
        return PT_Base::getInstance()->model->set($type,$id,$data);
    }

    //删除信息
    static public function rm($type, $id) {
        return PT_Base::getInstance()->model->rm($type,$id);
    }

    static public function del($type, $id) {
        return PT_Base::getInstance()->model->rm($type,$id);
    }

    static public function delete($type, $id) {
        return PT_Base::getInstance()->model->rm($type,$id);
    }

    //更新信息
    static public function refresh($type, $id) {
        return PT_Base::getInstance()->model->rm($type,$id);
    }

    // 获取数据
    static public function get($type, $id, $field = '') {
        return PT_Base::getInstance()->model->get($type,$id,$field);
    }
}