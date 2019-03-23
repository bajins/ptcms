<?php

class pt extends PT_Base {

    protected static $base;

    /**
     * 框架开始调用
     */
    public function start() {
        self::$base = PT_Base::getInstance();
        //初始化加载
        $this->init();
        $this->plugin->call('app_init_start');
        //加载站点配置文件
        $this->config->register(self::import(APP_PATH . '/common/' . $this->request->getSiteCode() . '.config.php'));
        // 路由解析
        $this->plugin->call('dispatcher_start');
        self::dispatcher();
        $this->plugin->call('dispatcher_end');
        defined('IS_POST') || define('IS_POST', $this->request->isPost());
        defined('IS_GET') || define('IS_GET', $this->request->isGet());
        defined('IS_AJAX') || define('IS_AJAX', $this->request->isAjax());
        //加载函数
        pt::import(APP_PATH . '/common/function.php');
        if (MODULE_NAME != 'common') {
            // 加载模块文件
            $this->config->register(self::import(APP_PATH . '/' . MODULE_NAME . '/config.php'));
            // 加载函数
            self::import(APP_PATH . '/' . MODULE_NAME . '/function.php');
        }
        // 控制器调用
        $this->app();
    }


    /**
     * 注册autoload等操作
     */
    protected function init() {
        // 设定错误和异常处理
        register_shutdown_function(array(__CLASS__, 'shutdown'));
        //set_error_handler(array(__CLASS__, 'error'));
        set_exception_handler(array(__CLASS__, 'exception'));
        // 注册AUTOLOAD方法
        spl_autoload_register(array(__CLASS__, 'autoload'));
        // 取消对GPC的自动处理
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            ini_set('magic_quotes_runtime', 0);
            if (get_magic_quotes_gpc()) {
                function stripslashes_deep($value) {
                    $value = is_array($value) ? array_map('stripslashes_deep', $value) : (isset($value) ? stripslashes($value) : null);
                    return $value;
                }

                $_POST   = stripslashes_deep($_POST);
                $_GET    = stripslashes_deep($_GET);
                $_COOKIE = stripslashes_deep($_COOKIE);
            }
        }
        // 注册插件
        $this->plugin->register($this->config->get('plugin', array()));
    }

    protected function app() {
        //加载控制器启动的插件
        $this->plugin->call('controller_start');
        //正常模式
        $controllerFile = APP_PATH . '/' . MODULE_NAME . '/controller/' . CONTROLLER_NAME . '.php';
        $classname      = CONTROLLER_NAME . 'Controller';
        $actionname     = ACTION_NAME . 'Action';
        if (MODULE_NAME == 'plugin') {
            //插件控制器
            $controllerFile = APP_PATH . '/common/plugin/' . CONTROLLER_NAME . '/manage.php';
            $classname      = 'manageController';
            $actionname     = ACTION_NAME . 'Action';
        } elseif (!in_array(MODULE_NAME, explode(',', $this->config->get('allow_module', '')))) {
            $this->response->error(MODULE_NAME . '模块不允许访问');
        }
        if (is_file($controllerFile)) {
            include $controllerFile;
            if (class_exists($classname, false)) {
                /* @var $app PT_Controller */
                $app = new $classname();
                //加载init方法
                if (method_exists($app, 'init')) {
                    $app->init();
                }
                // 加载action
                if (method_exists($app, $actionname)) {
                    $return=$app->$actionname();
                    if ($this->response->isAutoRender()) {
                        switch ($_GET['f']) {
                            case 'json':
                                $data=empty($return)?$app->view->get():$return;
                                $this->response->setBody($this->response->jsonEncode($data),'text/json');
                                break;
                            case 'jsonp':
                                $data=empty($return)?$app->view->get():$return;
                                $this->response->setBody($this->response->jsonpEncode($data),'text/json');
                                break;
                            case 'xml':
                                $data=empty($return)?$app->view->get():$return;
                                $this->response->setBody($this->response->xmlEncode($data),'text/xml');
                                break;
                            default:
                                $app->display();
                        }
                    }
                } else {
                    $this->response->error("当前控制器下" . get_class($app) . "找不到指定的方法 {$_GET['a']}Action");
                }
                $this->plugin->call('controller_end');
            } else {
                $this->response->error('控制器' . CONTROLLER_NAME . '对应的文件中未找到类' . $classname);
            }
        } else {
            $this->response->error(MODULE_NAME . '模块下控制器' . CONTROLLER_NAME . 'Controller对应的文件不存在');
        }
    }

    public static function import($filename) {
        static $_importFiles = array();
        if (!isset($_importFiles[$filename])) {
            if (is_file($filename)) {
                $_importFiles[$filename] = include $filename;
            } else {
                $_importFiles[$filename] = false;
            }
        }
        return $_importFiles[$filename];
    }


    protected static function dispatcher() {
        self::$base->dispatcher->run();
        // 获取分组 模块和操作名称
        define('MODULE_NAME', strtolower($_GET['m']));
        define('CONTROLLER_NAME', strtolower($_GET['c']));
        define('ACTION_NAME', strtolower($_GET['a']));

        if ($_SERVER['REQUEST_METHOD'] != 'cli') {
            define('__SELF__', strip_tags($_SERVER['REQUEST_URI']));
            define('__APP__', rtrim($_SERVER['SCRIPT_NAME'], '/'));
            // 当前模块和分组地址
            define('__MODULE__', __APP__ . '?s=' . strtolower(empty($_GET['_m']) ? $_GET['m'] : $_GET['_m']));
            define('__URL__', __MODULE__ . '/' . CONTROLLER_NAME);
            // 当前操作地址
            define('__ACTION__', __URL__ . '/' . ACTION_NAME);
        }
    }

    // 自动加载
    public static function autoload($class) {
        $classfile = strtolower(str_replace('_', '/', $class));
        //pt_开头的类指定目录到core
        if (strpos($classfile, 'pt/') === 0) $classfile = str_replace('pt/', 'core/', $classfile);
        if (is_file(PT_PATH . '/core/' . $classfile . '.php')) {
            pt::import(PT_PATH . '/core/' . $classfile . '.php');
        }
        if (is_file(PT_PATH . '/' . $classfile . '.php')) {
            pt::import(PT_PATH . '/' . $classfile . '.php');
        } elseif (substr($classfile, -10) == 'controller') {
            if (!pt::import(APP_PATH . '/' . MODULE_NAME . '/controller/' . substr($classfile, 0, -10) . '.php')) {
                pt::import(APP_PATH . '/common/controller/' . substr($classfile, 0, -10) . '.php');
            }
        } elseif (substr($classfile, -5) == 'model') {
            //适配ptcms_a_b这样的表
            $classfile = substr(str_replace('/', '_', $classfile), 0, -5);
            if (isset($GLOBALS['_automap']['model'][$classfile])) {
                //存在这个model
                if (isset($GLOBALS['_automap']['model'][$classfile][MODULE_NAME])) {
                    $file = $GLOBALS['_automap']['model'][$classfile][MODULE_NAME];
                } elseif (isset($GLOBALS['_automap']['model'][$classfile]['common'])) {
                    $file = $GLOBALS['_automap']['model'][$classfile]['common'];
                } else {
                    $file = current(array_slice($GLOBALS['_automap']['model'][$classfile], 0, 1));
                }
                pt::import($file);
            }
        } elseif (substr($classfile, -5) == 'block') {
            $classfile = substr($classfile, 0, -5);
            if (isset($GLOBALS['_automap']['block'][$classfile])) {
                //存在这个block
                if (isset($GLOBALS['_automap']['block'][$classfile][MODULE_NAME])) {
                    $file = $GLOBALS['_automap']['block'][$classfile][MODULE_NAME];
                } elseif (isset($GLOBALS['_automap']['block'][$classfile]['common'])) {
                    $file = $GLOBALS['_automap']['block'][$classfile]['common'];
                } else {
                    $file = current(array_slice($GLOBALS['_automap']['block'][$classfile], 0, 1));
                }
                pt::import($file);
            }
        } elseif (substr($classfile, -6) == 'plugin') {
            $classname = substr($classfile, 0, -6);
            pt::import(APP_PATH . '/common/plugin/' . $classname . '/' . $classname . '.php');
        } else {
            if (!pt::import(PT_PATH . '/library/' . $classfile . '.php') && isset($GLOBALS['_automap']['library'][$classfile])) {
                if (isset($GLOBALS['_automap']['library'][$classfile][MODULE_NAME])) {
                    $file = $GLOBALS['_automap']['library'][$classfile][MODULE_NAME];
                } elseif (isset($GLOBALS['_automap']['library'][$classfile]['common'])) {
                    $file = $GLOBALS['_automap']['library'][$classfile]['common'];
                } else {
                    $file = current(array_slice($GLOBALS['_automap']['library'][$classfile], 0, 1));
                }
                pt::import($file);
            }
        }
    }

    // 中止操作
    public static function shutdown() {
        //如果开启日志 则记录日志
        if (self::$base->config->get('log', false)) self::$base->log->build();
        // 如果自定义了close函数 则进行调用
        if (function_exists('pt_close')) {
            pt_close();
        }
        // 判断是否有错误
        if ($e = error_get_last()) {
            if (in_array($e['type'], array(1, 4))) {
                halt($e['message'], $e['file'], $e['line']);
            }
        }
    }

    // 异常处理
    public static function exception(Exception $e) {
        halt($e->getmessage(), $e->getFile(), $e->getLine());
    }

    // 错误处理
    public static function error($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
                halt($errstr, $errfile, $errline);
                break;
            case E_USER_ERROR:
            case E_STRICT:
            case E_USER_WARNING:
            case E_USER_NOTICE:
            default:
                break;
        }
    }
}