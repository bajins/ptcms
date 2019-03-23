<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : weibo.php
 */
class Driver_Oauth_Weibo extends oauth {

    /**
     * 获取requestCode的api接口
     *
     * @var string
     */
    protected $getRequestCodeURL = 'https://api.weibo.com/oauth2/authorize';

    /**
     * 获取access_token的api接口
     *
     * @var string
     */
    protected $getAccessTokenURL = 'https://api.weibo.com/oauth2/access_token';

    /**
     * 获取request_code的额外参数,可在配置中修改 URL查询字符串格式
     *
     * @var array
     */
    protected $authorizeParam = array(
        //'scope'=>'all'
        'forcelogin' => 'true',
    );

    /**
     * 获取accesstoekn时候的附加参数
     *
     * @var array
     */
    protected $getTokenParam = array();


    /**
     * API根路径
     *
     * @var string
     */
    protected $apiBase = 'https://api.weibo.com/2/';

    /**
     * 组装接口调用参数 并调用接口
     *
     * @param  string $api    微博API
     * @param  array $param   调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @param  bool $multi
     * @return string json
     */
    public function call($api, $param = array(), $method = 'GET', $multi = false) {
        /* 腾讯QQ调用公共参数 */
        $params = array(
            'access_token' => $this->token,
        );
        $params = array_merge($params, $param);
        $data = http::get($this->apiBase . $api, $params);
        return json_decode($data, true);
    }

    /**
     * 解析access_token方法请求后的返回值
     *
     * @param $result
     * @return mixed
     * @throws Exception
     */
    protected function parseToken($result) {
        $data = json_decode($result, true);
        if (isset($data['access_token'])) {
            $this->token = $data['access_token'];
            $this->openid = $data['uid'];
            return array(
                'openid' => $this->openid,
                'token' => $data['access_token'],
                'expires' => $data['expires_in'],
                'refresh' => '',
            );
        } else
            return "获取 ACCESS_TOKEN 出错：{$result}";
    }

    /**
     * 获取openid
     *
     * @return mixed
     * @throws Exception
     */
    public function getOpenId() {
        if ($this->openid) return $this->openid;
        return false;
    }

    /**
     * 获取用户信息
     *
     * @return array
     */
    public function getInfo() {
        $data = $this->call('users/show.json', array('uid' => $this->getOpenId()));
        return array(
            'id' => $this->openid,
            'name' => $data['screen_name'],
            'gender' => ($data['gender'] == 'f') ? '女' : (($data['gender'] == 'm') ? '男' : '未知'),
            'avatar' => $data['avatar_large'],
        );
    }
}