<?php

class Driver_Api_Ptcms extends PT_Api{

    protected $appid;
    protected $appkey;

    public function __construct() {
        $this->appid = $this->config->get('appid');
        $this->appkey = $this->config->get('appkey');
    }

    public function call($url, $params = array(), $method = 'GET', $header = array()) {
        $params['rule'] = $this->config->get('apirule');
        $params['appid'] = $this->appid;
        $params['format'] = 'json';
        $params['datetime'] = $_SERVER['REQUEST_TIME'];
        $params['host'] = $_SERVER['HTTP_HOST'];
        $params['sign'] = $this->sign($params);
        $data = array();
        for ($i = 0; $i < 5; $i++) {
            $con = ($method == 'GET') ? http::get($url, $params) : http::post($url, $params, $header);
            if ($con) {
                $data=json_decode($con,true);
                break;
            }
        }
        if (!empty($data) && is_array($data)) {
            if ($data['status'] == 1) {
                return $data['data'];
            } else {
                $this->log->write('调用接口出现错误！原因：' . $data['msg'] . ' 参数：' . var_export($params, true));
            }
        } else {
            $this->log->write('调用接口失败！方法' . $method . ' 参数：' . var_export($params, true));
        }
        return array();
    }

    //对参数进行签名
    public function sign($params) {
        asort($params);
        $str = '';
        foreach ($params as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str = substr($str, 0, -1);
        return md5($str . $this->appkey);
    }
}