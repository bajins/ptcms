<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : qq.php
 */
class Driver_Oauth_QQ extends oauth {

    /**
     * 获取requestCode的api接口
     *
     * @var string
     */
    protected $getRequestCodeURL = 'https://graph.qq.com/oauth2.0/authorize';

    /**
     * 获取access_token的api接口
     *
     * @var string
     */
    protected $getAccessTokenURL = 'https://graph.qq.com/oauth2.0/token';

    /**
     * 获取request_code的额外参数,可在配置中修改 URL查询字符串格式
     *
     * @var array
     */
    protected $authorizeParam = array(
        "scope" => 'get_user_info,add_topic,add_share,add_t',
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
    protected $apiBase = 'https://graph.qq.com/';

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
            'oauth_consumer_key' => $this->appid,
            'access_token' => $this->token,
            'openid' => $this->getOpenId(),
            'format' => 'json'
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
        parse_str($result, $data);
        if (!empty($data['access_token'])) {
            $this->token = $data['access_token'];
            return array(
                'openid' => $this->getOpenId(),
                'token' => $data['access_token'],
                'expires' => $data['expires_in'],
                'refresh' => $data['refresh_token'],
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
        $data = http::get($this->apiBase . 'oauth2.0/me', array('access_token' => $this->token));
        $data = json_decode(trim(substr($data, 9), " );\n"), true);
        if (isset($data['openid'])) {
            $this->openid = $data['openid'];
            return $data['openid'];
        } else
            return false;
    }

    /**
     * 获取用户信息
     *
     * @return array
     */
    public function getInfo() {
        $data = $this->call('user/get_user_info');
        return array(
            'id' => $this->openid,
            'name' => $data['nickname'],
            'gender' => $data['gender'],
            'avatar' => $data['figureurl_2'],
        );
    }
}