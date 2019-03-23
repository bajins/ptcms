<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : block.php
 */
class PT_Block{
    protected $pt;
    public function __construct() {
        $this->pt=PT_Base::getInstance();
    }

    public function getInstance($class) {
        static $_class;
        $class = $class . 'Block';
        if (empty($_class[$class])) {
            if (class_exists($class)) {
                $_class[$class] = new $class();
            }else{
                $_class[$class] = null;
            }
        }
        return $_class[$class];
    }

    public function getData($name,$param) {
        $key = $this->getKey($name,$param);
        $cachetime = $this->pt->input->param('cachetime','int',$this->pt->config->get('cachetime', 600),$param);
        $data = $this->pt->cache->get($key);
        $hander=$this->getInstance($name);
        if ($name){
            if ($hander && (APP_DEBUG || $data === null)) {
                $data = $hander->run($param);
                if (!empty($param['template'])) {
                    $this->pt->view->set($param);
                    if ($layout=$this->pt->config->get('layout')){
                        $this->pt->config->set('layout',false);
                        $data = $this->pt->view->fetch($param['template']);
                        $this->pt->config->set('layout',$layout);
                    }else{
                        $data = $this->pt->view->fetch($param['template']);
                    }
                }
                $this->pt->cache->set($key, $data, $cachetime);
            }
        }else{
            $this->pt->view->set($param);
            if ($layout=$this->pt->config->get('layout')){
                $this->pt->config->set('layout',false);
                $data = $this->pt->view->fetch($param['template']);
                $this->pt->config->set('layout',$layout);
            }else{
                $data = $this->pt->view->fetch($param['template']);
            }
        }
        if (isset($param['order']) && $param['order']=='rand'){
            $param['num']=empty($param['num'])?count($data):$param['num'];
            $list=array();
            if(count($data)>=$param['num']){
                $num=$param['num'];
                $num=($num==0)?count($data):$num;
                $keys=array_rand($data,$num);
                if ($num==1) $keys=array($keys);
                foreach($keys as $v){
                    $list[]=$data[$v];
                }
                $data=$list;
            }
        }
        return $data;
    }

    /**
     * 检查缓存是否有效  false 需要更新
     *
     * @param $key
     * @param $cachetime
     * @return bool
     */
    public function checkCache($key, $cachetime) {
        $data = $this->pt->cache->get($key);
        if (!isset($data['time']) || ($cachetime <> 0 && $data['time'] + $cachetime < NOW_TIME)) {
            return false;
        }
        return $data['data'];
    }

    public function getKey($name,$param) {
        return md5($name . serialize($param));
    }

}

class Block extends PT_Block{}