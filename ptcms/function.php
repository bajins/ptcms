<?php
/**
 * 文件函数
 *
 * @param string $file    需要写入的文件，系统的绝对路径加文件名
 * @param bool   $content 不填写 读取 null 删除 其他 写入
 * @param string $mod     写入模式，
 * @return mixed
 */
function F($file, $content = false, $mod = '') {
    if ($content === false) {
        return is_file($file) ? file_get_contents($file) : false;
    } elseif ($content === null) {
        if (is_file($file)) {
            //删除文件
            return unlink($file);
        } elseif (is_dir($file)) {
            //删除目录
            $handle = opendir($file);
            while (($filename = readdir($handle)) !== false) {
                if ($filename !== '.' && $filename !== '..') F($file . '/' . $filename, null);
            }
            closedir($handle);
            return @rmdir($file);
        }
    } else {
        if (!strpos($file, '://') && !is_dir(dirname($file))) {
            mkdir(dirname($file), 0755, true);
        }
        if (is_array($content)) {
            $content = '<?php' . PHP_EOL . 'return ' . var_export($content, true) . ';';
        }
        if ($mod) {
            return file_put_contents($file, strval($content), LOCK_EX | $mod);
        } else {
            return file_put_contents($file, strval($content), LOCK_EX);
        }
    }
    return false;
}

function halt($msg, $file = '', $line = '') {
    if ($_SERVER['REQUEST_METHOD'] == 'cli') {
        exit($msg);
    } else if (APP_DEBUG) {
        PT_Base::getInstance()->response->setHeader();
        $e['message'] = $msg;
        $e['file']    = $file;
        $e['line']    = $line;
        include PT_PATH . '/error.tpl';
        exit;
    } else {
        PT_Base::getInstance()->response->error($msg . ' [' . $file . '(' . $line . ')]');
    }
}

/**
 * 链接生成
 *
 * @param string $method  对应方法
 * @param array  $args    参数
 * @param array  $ignores 忽略参数
 * @return string
 */
function U($method = '', $args = array(), $ignores = array()) {
    static $rules = null, $_method = array(), $_map = array(), $power = false, $rewriteargparam = false;
    if ($rules === null) {
        $rules           = PT_Base::getInstance()->config->get('url_rules');
        $_map            = PT_Base::getInstance()->config->get('map_module');
        $power           = PT_Base::getInstance()->config->get('rewritepower', false);
        $rewriteargparam = PT_Base::getInstance()->config->get('rewriteargparam', false);
    }
    //忽视args中的部分参数
    if (!empty($ignores)) {
        foreach ($ignores as $key => $var) {
            if (isset($args[$key]) && $args[$key] == $var) unset($args[$key]);
        }
    }
    if (empty($_method[$method])) {
        if (substr_count($method, '.') == 1) {
            $_method[$method] = MODULE_NAME . '.' . $method;
        } elseif ($method === '') {
            $_method[$method] = MODULE_NAME . '.' . CONTROLLER_NAME . '.' . ACTION_NAME;
        } elseif (substr_count($method, '.') == 0) {
            $_method[$method] = MODULE_NAME . '.' . CONTROLLER_NAME . '.' . $method;
        } else {
            $_method[$method] = $method;
        }
        $_method[$method] = strtolower($_method[$method]);
    }
    $method = $_method[$method];
    if (!empty($rules[$method]) && empty($args['_force'])) {
        $keys  = array();
        $rule  = $rules[$method];
        $oargs = $args;
        foreach ($args as $key => &$arg) {
            $keys[] = '{' . $key . '}';
            $arg    = rawurlencode(urldecode($arg));
            if (strpos($rule, '{' . $key . '}')) unset($oargs[$key]);
        }
        $url = clearUrl(str_replace($keys, $args, $rule));
        if (strpos($url, ']')) {
            $url = strtr($url, array('[' => '', ']' => ''));
        }
        if ($oargs && $rewriteargparam) {
            return PT_DIR . $url . (strpos($url, '?') ? '&' : '?') . http_build_query($oargs);
        } else {
            return PT_DIR . $url;
        }
    } else {
        list($param['m'], $param['c'], $param['a']) = explode('.', $method);
        if (isset($_map[$param['m']])) $param['m'] = $_map[$param['m']];
        //调整顺序为m c a
        krsort($param);
        $param = array_merge($param, $args);
        if ($power) {
            if (isset($_GET['f'])) {
                $url = PT_DIR . '/' . $param['m'] . '/' . $param['c'] . '/' . $param['a'] . '.' . $_GET['f'];
            } else {
                $url = PT_DIR . '/' . $param['m'] . '/' . $param['c'] . '/' . $param['a'];
            }
            unset($param['m'], $param['c'], $param['a']);
            if ($param) {
                $url .= '?' . http_build_query($param);
            }
        } else {
            $url = __APP__ . '?' . http_build_query($param);
        }
        return $url;
    }
}

/**
 * 清除url中可选参数
 *
 * @param $url
 * @return mixed
 */
function clearUrl($url) {
    while (preg_match('#\[[^\[\]]*?\{\w+\}[^\[\]]*?\]#', $url, $match)) {
        $url = str_replace($match['0'], '', $url);
    }
    return $url;
}


/**
 * 去除代码中的空白和注释
 *
 * @param string $content 代码内容
 * @return string
 */
function strip_whitespace($content) {
    $stripStr = '';
    //分析php源码
    $tokens     = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;
                //过滤空格
                case T_WHITESPACE:
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                case T_START_HEREDOC:
                    $stripStr .= "<<<ptcms\n";
                    break;
                case T_END_HEREDOC:
                    $stripStr .= "ptcms;\n";
                    for ($k = $i + 1; $k < $j; $k++) {
                        if (is_string($tokens[$k]) && $tokens[$k] == ';') {
                            $i = $k;
                            break;
                        } else if ($tokens[$k][0] == T_CLOSE_TAG) {
                            break;
                        }
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}

/**
 * 获取自动加载的目录文件
 *
 * @return array
 */
function get_auto_map() {
    $map  = array();
    $dirs = array_unique(explode(',', trim((PT_Base::getInstance()->config->get('allow_module', null, '') . ',common'), ',')));
    foreach ($dirs as $dir) {
        $path = APP_PATH . '/' . $dir;
        if (!is_dir($path)) continue;
        $handle = opendir($path);
        while (($dirname = readdir($handle)) !== false) {
            if (in_array($dirname, array('model', 'block', 'library'))) {
                $handle1 = opendir($path . '/' . $dirname);
                while (($filename = readdir($handle1)) !== false) {
                    $filepath = $path . '/' . $dirname . '/' . $filename;
                    if ($filename{0} != '.' && is_dir($filepath)) {
                        $handle2 = opendir($filepath);
                        while (($filename1 = readdir($handle2)) !== false) {
                            if (substr($filename1, -4) == '.php') {
                                $map[$dirname][$filename . '_' . substr($filename1, 0, -4)][$dir] = $filepath . '/' . $filename1;
                            }
                        }
                        closedir($handle2);
                    } elseif (substr($filename, -4) == '.php') {
                        $map[$dirname][substr($filename, 0, -4)][$dir] = $filepath;
                    }
                }
                closedir($handle1);
            }
        }
        closedir($handle);
    }
    return $map;
}

/**
 * 获取和设置配置参数 支持批量定义
 *
 * @param string|array $name    配置变量
 * @param mixed        $value   配置值
 * @param mixed        $default 默认值
 * @return mixed
 */
function C($name = null, $value = null, $default = null) {
    static $handler = null;
    if (!$handler) $handler = PT_Base::getInstance()->config;
    if (empty($name)) {
        //湖区全部
        return $handler->get();
    }
    if (is_string($name)) {
        $name = strtolower($name);
        if (is_null($value)) {
            //获取
            return $handler->get($name, $default);
        } else {
            //设置
            return $handler->set($name, $value);
        }
    } else if (is_array($name)) {
        // 批量设置
        return $handler->register($name);
    }
    // 避免非法参数
    return null;
}

/**
 * Cookie 设置、获取、删除
 *
 * @param string $name   cookies名称
 * @param string $value  cookie值
 * @param string $option cookie参数
 * @return mixed
 */
function cookie($name, $value = '', $option = null) {
    static $handler = null;
    if (!$handler) $handler = PT_Base::getInstance()->cookie;
    // 清除指定前缀的所有cookie
    if ('' === $value) {
        //获取
        return $handler->get($name);
    } else if (is_null($value)) {
        //删除
        $handler->rm($name);
    } else {
        // 设置cookie
        $handler->set($name, $value, $option);
    }
}

function get_ip($default = '0.0.0.0') {
    return PT_Base::getInstance()->request->getIp($default);
}

function dump($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

/**
 * M函数用于实例化Model
 *
 * @param string $name Model库名
 * @return object
 */
function M($name = '') {
    static $handler = null;
    if (!$handler) $handler = PT_Base::getInstance();
    return $handler->model($name);
}


/**
 * 获取输入参数 支持过滤和默认值
 *
 * @param string $name    变量的名称 支持指定类型
 * @param mixed  $default 不存在的时候默认值
 * @param mixed  $filter  参数过滤方法
 * @param array  $input
 * @return mixed
 */
function I($name, $filter = 'int', $default = null, $input = array()) {
    static $handler = null;
    if (!$handler) $handler = PT_Base::getInstance()->input;
    // 可以从指定的数组中取值
    if ($input == array()) {
        if (strpos($name, '.')) {
            // 指定参数来源
            list($method, $name) = explode('.', $name, 2);
        } else {
            // 默认为post
            $method = 'post';
        }
        switch (strtolower($method)) {
            case 'get'     :
                $input = $_GET;
                break;
            case 'post'    :
                $input = $_POST;
                break;
            case 'put'     :
                parse_str(file_get_contents('php://input'), $input);
                break;
            case 'request' :
                $input = $_REQUEST;
                break;
            case 'session' :
                $input = $_SESSION;
                break;
            case 'cookie'  :
                $input = $_COOKIE;
                $name  = C('cookie_prefix') . $name;
                break;
            case 'server'  :
                $input = $_SERVER;
                break;
            case 'globals' :
                $input = $GLOBALS;
                break;
            default:
                return null;
        }
    }
    return $handler->param($name, $filter, $default, $input);
}
