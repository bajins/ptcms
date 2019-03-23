<?php

class PT_Request extends PT_Base {

    public function isGet() {
        if (defined('IS_GET')) {
            return IS_GET;
        }
        return $_SERVER['REQUEST_METHOD'] === 'GET' ? true : false;

    }

    public function isPost() {
        if (defined('IS_POST')) {
            return IS_POST;
        }
        return $_SERVER['REQUEST_METHOD'] === 'POST' ? true : false;
    }

    public function isAjax() {
        if (defined('IS_AJAX')) {
            return IS_AJAX;
        }
        return ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['isajax']) || !empty($_GET['isajax'])) ? true : false;
    }

    public function isMobile() {
        if (defined('IS_MOBILE')) return IS_MOBILE;
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            if (stristr($_SERVER['HTTP_VIA'], "wap")) {
                return true;
            }
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'UCBrowser');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        if($_SERVER['HTTP_HOST']==parse_url($this->config->get('wap_domain'),PHP_URL_HOST)){
            return true;
        }
        return false;
    }

    public function isSpider($ua=null) {
        if (defined('IS_SPIDER')) return IS_SPIDER;
        empty($ua) && $ua = $_SERVER['HTTP_USER_AGENT'];
        $ua      = strtolower($ua);
        $spiders = array('bot', 'crawl', 'spider', 'slurp', 'sohu-search', 'lycos', 'robozilla');
        foreach ($spiders as $spider) {
            if (false !== strpos($ua, $spider)) return true;
        }
        return false;
    }

    public function getModuleName() {
        return MODULE_NAME;
    }

    public function getControllerName() {
        return CONTROLLER_NAME;
    }

    public function getActionNAME() {
        return ACTION_NAME;
    }

    public function getIp($default = '0.0.0.0') {
        $ip = $_SERVER['REMOTE_ADDR'];
        $i  = explode('.', $ip);
        if ($i[0] == 10 || ($i[0] == 172 && $i[1] > 15 && $i[1] < 32) || ($i[0] == 192 && $i[1] == 168)) {
            //如果是内网ip重新获取
            $keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP','HTTP_X_REAL_IP');
            foreach ($keys as $key) {
                if (empty($_SERVER[$key])) continue;
                $ips = explode(',', $_SERVER[$key], 1);
                $ip  = $ips[0];
                break;
            }
        }
        $l = ip2long($ip);
        if ((false !== $l) && ($ip === long2ip($l))) return $ip;
        return $default;
    }

    /**
     * 获取host
     *
     * @param null $domain
     * @return mixed|null|string
     */
    public static function getSiteCode($domain = null) {
        $domain = ($domain !== null) ? (strpos($domain, '://') ? parse_url($domain, PHP_URL_HOST) : $domain) : $_SERVER['HTTP_HOST'];
        // 替换域名中的-为_
        $domain = str_replace('-', '_', $domain);
        // 去掉端口
        if (strpos($domain, ':') !== false) $domain = substr($domain, 0, strpos($domain, ':'));
        // 去掉开始的www.
        if (stripos($domain, 'www.') === 0) $domain = substr($domain, 4);
        return strtolower($domain);
    }
}

class request extends PT_request {

}