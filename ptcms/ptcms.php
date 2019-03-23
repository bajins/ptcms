<?php
/**
 * PTCMS FrameWork 开发框架
 *
 * @File  : ptcms.php
 */

        
//设置时区（中国）
date_default_timezone_set("PRC");
// 记录开始运行时间
$GLOBALS['_startTime'] = microtime(true);
// 记录sql执行次数
$GLOBALS['_sql']    = array();
$GLOBALS['_sqlnum'] = 0;
// 缓存读取次数
$GLOBALS['_cacheRead'] = 0;
// 缓存写入次数
$GLOBALS['_cacheWrite'] = 0;
// 记录内存初始使用
$GLOBALS['_startUseMems'] = memory_get_usage();
// 记录网络请求
$GLOBALS['_api']    = array();
$GLOBALS['_apinum'] = 0;

//项目根目录
defined('PT_ROOT') || define('PT_ROOT', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
//PTCMS根目录
defined('PT_PATH') || define('PT_PATH', dirname(__FILE__));

// 判断是否有html缓存 或者其他的静态文件
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '.php') === false && strpos($_SERVER['REQUEST_URI'], '?') === false && is_file(PT_ROOT . $_SERVER['REQUEST_URI'])) {
    readfile(PT_ROOT . $_SERVER['REQUEST_URI']);
    exit;
}

// 框架版本号
define('PTCMS_VERSION', '3.2.5 20150410');
// debug信息 是否开启当前项目debug模式 默认 不开启
defined('APP_DEBUG') || define('APP_DEBUG', false);


// 获取目录地址
if (PHP_SAPI == 'cli') {
    $_root = '/';
} else {
    $_root = str_replace('\\', '/', dirname(rtrim(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']), '/')));
}
// 目录
defined('PT_DIR') || define('PT_DIR', rtrim($_root, '/'));
if (PHP_SAPI == 'cli') {
    $_SERVER['HTTP_HOST']      = '';
    $_SERVER['REQUEST_METHOD'] = 'cli';
    $_GET['s']                 = isset($argv['1']) ? $argv['1'] : '';
    defined('PT_URL') || define('PT_URL', $argv['0']);
} else {
    if ($pos = strpos($_SERVER['HTTP_HOST'], ':')) {
        $host = substr($_SERVER['HTTP_HOST'], 0, $pos);
    } else {
        $host = $_SERVER['HTTP_HOST'];
    }
    // 网站访问域名 不包括入口文件及参数
    defined('PT_URL') || define('PT_URL', 'http://' . $host . (($_SERVER['SERVER_PORT'] == 80) ? '' : ':' . $_SERVER['SERVER_PORT']) . PT_DIR);

}
// 项目目录
defined('APP_PATH') || define('APP_PATH', PT_ROOT . '/application');
//缓存目录
defined('CACHE_PATH') || define('CACHE_PATH', PT_ROOT . '/runtime');
//数据目录/
defined('DATA_PATH') || define('DATA_PATH', APP_PATH . '/common/data');
//模版目录
defined('TPL_PATH') || define('TPL_PATH', PT_ROOT . '/template');
// 环境常量
define('NOW_TIME', $_SERVER['REQUEST_TIME']);

// 自动识别SAE环境
//if (function_exists('saeAutoLoader') or function_exists('sae_auto_load')) {
//    // sae
//    defined('APP_MODE') or define('APP_MODE', 'sae');
//} else {
//    // 普通模式
//    defined('APP_MODE') or define('APP_MODE', 'common');
//}

//后台运行程序
if (!empty($_GET['backRun'])) {
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    } else {
        ignore_user_abort(true);
    }
}

// 编译模式
if (APP_DEBUG) {
    //加载基本文件
    include PT_PATH . '/function.php';
    include PT_PATH . '/core/base.php';
    include PT_PATH . '/core/pt.php';
    // 加载公共配置文件
    PT_Base::getInstance()->config->register(pt::import(APP_PATH . '/common/config.php'));
    // 开启错误输出
    ini_set('display_errors', 'on');
    // 设置错误输出级别
    error_reporting(E_ALL);
    $GLOBALS['_automap'] = get_auto_map();
} else {
    //隐藏php版本
    ini_set('expose_php',0);
    // 开启错误输出
    ini_set('display_errors', 'off');
    // 设置错误输出级别
    error_reporting(0);
    // 合并核心文件
    $runtimefile = CACHE_PATH . '/pt_runtime.php';
    if (!is_file($runtimefile)) {
        //加载函数库
        include PT_PATH . '/function.php';
        include PT_PATH . '/core/base.php';
        include PT_PATH . '/core/pt.php';
        // 加载公共配置文件
        PT_Base::getInstance()->config->register(pt::import(APP_PATH . '/common/config.php'));
        $files = array(
            PT_PATH . '/function.php',
            PT_PATH . '/core/base.php',
            PT_PATH . '/core/pt.php',
            PT_PATH . '/core/block.php',
            PT_PATH . '/core/cache.php',
            PT_PATH . '/core/config.php',
            PT_PATH . '/driver/cache/' . strtolower(PT_Base::getInstance()->config->get('cache_driver', 'file')) . '.php',
            PT_PATH . '/core/controller.php',
            PT_PATH . '/core/cookie.php',
            PT_PATH . '/core/dispatcher.php',
            PT_PATH . '/core/fliter.php',
            PT_PATH . '/core/input.php',
            PT_PATH . '/core/log.php',
            PT_PATH . '/core/model.php',
            PT_PATH . '/core/request.php',
            PT_PATH . '/core/response.php',
            PT_PATH . '/core/session.php',
            PT_PATH . '/core/storage.php',
            PT_PATH . '/core/plugin.php',
            PT_PATH . '/core/view.php',
        );
        if (PT_Base::getInstance()->config->get('db_type')) {
            $files[] = PT_PATH . '/core/db.php';
            $files[] = PT_PATH . '/driver/db/dao.php';
            $files[] = PT_PATH . '/driver/db/' . strtolower(PT_Base::getInstance()->config->get('db_type')) . '/dao.php';
            $files[] = PT_PATH . '/driver/db/' . strtolower(PT_Base::getInstance()->config->get('db_type')) . '/' . strtolower(PT_Base::getInstance()->config->get('mysql_driver', null, 'pdo')) . '.php';
        }
        $str = "<?php ";
        $str .= "\$GLOBALS['_automap']=" . var_export(get_auto_map(), true) . ';';
        foreach ($files as $file) {
            if (is_file($file)) $str .= trim(substr(php_strip_whitespace($file), 5)) . PHP_EOL;
        }
        if(F($runtimefile, $str)){
            PT_Base::getInstance()->controller->redirect($_SERVER['REQUEST_URI']);
        }else{
            exit('请检查目录权限['.CACHE_PATH.']');
        }
    }
    include $runtimefile;
    // 加载公共配置文件
    PT_Base::getInstance()->config->register(pt::import(APP_PATH . '/common/config.php'));
}


$pt = new pt();
$pt->start();

