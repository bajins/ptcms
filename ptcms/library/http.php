<?php

class http {

    public static function curl($url, $params = array(), $method = 'GET', $header = array(), $option = array()) {
        $opts = array(
            CURLOPT_TIMEOUT        => PT_Base::getInstance()->config->get('timeout', 10),
            CURLOPT_CONNECTTIMEOUT => PT_Base::getInstance()->config->get('timeout', 10),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HEADER         => false,
            CURLOPT_USERAGENT      => PT_Base::getInstance()->config->get('user_agent', 'PTCMS Spider'),
            CURLOPT_REFERER        => isset($header['referer'])?$header['referer']:$url,
            CURLOPT_NOSIGNAL       => 1,
            CURLOPT_ENCODING       => 'gzip, deflate',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );

        if(isset($header['cookie'])){
            $opts[CURLOPT_COOKIE]=$header['cookie'];
            unset($header['cookie']);
        }

        if(isset($header['useragent'])){
            $opts[CURLOPT_USERAGENT]=$header['useragent'];
            unset($header['useragent']);
        }

        if(isset($header['showheader'])){
            $opts[CURLOPT_HEADER]=true;
            unset($header['showheader']);
        }

        if(!empty($header)){
            $opts[CURLOPT_HTTPHEADER]=$header;
        }

        //补充配置
        foreach ($option as $k => $v) {
            $opts[$k] = $v;
        }
        //安全模式
        if (ini_get("safe_mode") || ini_get('open_basedir')) {
            unset($opts[CURLOPT_FOLLOWLOCATION]);
        }
        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET':
                $opts[CURLOPT_URL] = $url;
                break;
            case 'POST':
                //判断是否传输文件
                $opts[CURLOPT_URL]        = $url;
                $opts[CURLOPT_POST]       = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                exit('不支持的请求方式！');
        }

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        //todo safe_mode模式下需要处理的location
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        if ($error && $errno !== 28) {
            PT_Log::record('Curl获取远程内容错误！原因：' . $error . ' 地址：' . $url);
            return false;
        }
        return $data;
    }

    public static function filegc($url, $params = array(), $method = 'GET', $header = array(), $option = array()) {
        $header  = array_merge(array("Referer: ".PT_Base::getInstance()->config->get('referer', $url), "User-Agent: " . PT_Base::getInstance()->config->get('user_agent', 'PTCMS Spider', "Accept-Encoding: gzip,deflate")), $header);
        $context = array(
            'http' => array(
                'method'  => $method,
                'header'  => implode("\r\n", $header),
                'timeout' => PT_Base::getInstance()->config->get('timeout', 10),
            )
        );
        if ($option) $context['http'] = array_merge($context['http'], $option);
        if ($method == 'POST') {
            if (is_array($params)) $params = http_build_query($params);
            $content_length             = strlen($params);
            $header[]                   = "Content-type: application/x-www-form-urlencoded";
            $header[]                   = "Content-length: $content_length";
            $context['http']['header']  = implode("\r\n", $header);
            $context['http']['content'] = $params;
        }
        $stream_context = stream_context_create($context);
        $data           = @file_get_contents($url, false, $stream_context);
        return self::gzdecode($data);
    }

    public static function fsock($url, $params = array(), $method = 'GET') {
        $urlinfo = parse_url($url);
        $port    = isset($urlinfo["port"]) ? $urlinfo["port"] : 80;
        $path    = $urlinfo['path'] . (!empty($urlinfo['query']) ? '?' . $urlinfo['query'] : '') . (!empty($urlinfo['fragment']) ? '#' . $urlinfo['fragment'] : '');

        $in = "{$method} {$path} HTTP/1.1\r\n";
        $in .= "Host: {$urlinfo['host']}\r\n";
        $in .= "Content-Type: application/octet-stream\r\n";
        $in .= "Connection: Close\r\n";
        $in .= "Hostname: {$urlinfo['host']}\r\n";
        $in .= "User-Agent: " . PT_Base::getInstance()->config->get('user_agent', 'PTCMS Spider') . "\r\n";
        $in .= "Referer: ".PT_Base::getInstance()->config->get('referer', $url)."\r\n";
        $in .= "Accept-Encoding: gzip,deflate\r\n";
        if ($method == 'POST') {
            $params = is_array($params) ? http_build_query($params) : $params;
            $in .= "Content-Length: " . strlen($params) . "\r\n\r\n";
        }

        $address = gethostbyname($urlinfo['host']);
        $fp      = fsockopen($address, $port, $err, $errstr, PT_Base::getInstance()->config->get('timeout', 10));
        if (!$fp) {
            exit ("cannot conncect to {$address} at port {$port} '{$errstr}'");
        }
        fwrite($fp, $in . $params, strlen($in . $params));

        $f_out = '';
        while ($out = fread($fp, 2048))
            $f_out .= $out;

        $tmp = explode("\r\n\r\n", $f_out);
        fclose($fp);
        return $tmp[1];
    }

    public static function get($url, $data = array()) {
        $func = PT_Base::getInstance()->config->get('httpmethod', 'curl');
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        if ($data) {
            if (strpos($url, '?')) {
                $url .= '&' . $data;
            } else {
                $url .= '?' . $data;
            }
            $data = array();
        }
        if (APP_DEBUG || isset($_GET['debug'])) {
            $t                 = microtime(true);
            $res               = self::$func($url, $data, 'GET');
            $GLOBALS['_api'][] = $func . ' GET ' . number_format(microtime(true) - $t, 5) . ' ' . strlen($res) . ' ' . $url;
        } else {
            $res = self::$func($url, $data, 'GET');
        }
        if(strpos($res,'<script>window.location=') && strpos($res,'jdfwkey')){
            //金盾防火墙
            $url='http://'.parse_url($url,PHP_URL_HOST).collect::getMatch('window.location="(.+?)"',$res);
            $res=self::get($url,$data);
        }else{
            $GLOBALS['_apinum']++;
        }
        return $res;
    }

    public static function post($url, $data = array(), $header = array()) {
        $func = PT_Base::getInstance()->config->get('httpmethod', 'curl');
        if (APP_DEBUG || isset($_GET['debug'])) {
            $t                 = microtime(true);
            $res               = self::$func($url, $data, 'POST');
            $GLOBALS['_api'][] = $func . ' POST ' . number_format(microtime(true) - $t, 5) . ' ' . strlen($res) . ' ' . $url . json_encode($data, 256);
        } else {
            $res = self::$func($url, $data, 'POST', $header);
        }
        $GLOBALS['_apinum']++;
        return $res;
    }

    public static function getMethod() {
        $method = array();
        if (function_exists('curl_init')) {
            $method['curl'] = 'curl函数(推荐)';
        }
        if (function_exists('fsockopen') && ini_get('allow_url_fopen')) {
            $method['fsock'] = 'fsockopen函数';
        }
        if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
            $method['filegc'] = 'file_get_content函数';
        }
        return $method;
    }

    //触发G
    public static function trigger($url) {
        if (stripos($url, 'http') === 0) {
            $func = PT_Base::getInstance()->config->get('httpmethod', 'curl');
            if (APP_DEBUG || isset($_GET['debug'])) {
                $t                 = microtime(true);
            }
            if ($func == 'curl') {
                http::curl($url, array(), 'GET', array(), array(
                    CURLOPT_TIMEOUT_MS        => 500,
                    CURLOPT_CONNECTTIMEOUT_MS => 500,
                ));
            } else {
                http::filegc($url, array(), 'GET', array(), array(
                    'timeout' => 0,
                ));
            }
            if (APP_DEBUG || isset($_GET['debug'])) {
                $GLOBALS['_api'][] = $func . ' trigger ' . number_format(microtime(true) - $t, 5) . '  ' . $url;
            }
        }
        $GLOBALS['_apinum']++;
        return;
    }

    // Gzip解压函数
    public static function gzdecode($data) {
        if (strlen($data) < 18) return $data;
        $res = unpack('vfile_type', substr($data, 0, 2));
        if ($res['file_type'] <> 35615) return $data;
        if (function_exists('gzdecode') && $unpacked = gzdecode($data)) return $unpacked;
        $flags     = ord(substr($data, 3, 1));
        $headerlen = 10;
        if ($flags & 4) {
            $extralen = unpack('v', substr($data, 10, 2));
            $extralen = $extralen[1];
            $headerlen += $extralen + 2;
        }
        if ($flags & 8) $headerlen = strpos($data, chr(0), $headerlen) + 1;
        if ($flags & 16) $headerlen = strpos($data, chr(0), $headerlen) + 1;
        if ($flags & 2) $headerlen += 2;
        $unpacked = gzinflate(substr($data, $headerlen));
        if ($unpacked === false) return $data;
        return $unpacked;
    }
}