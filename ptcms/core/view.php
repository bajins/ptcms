<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : view.php
 */
class PT_View {

    // 模板存储变量
    protected $_tpl_vars = array();
    // 模版基地址
    protected $tplpath = TPL_PATH;
    // 模版文件名
    protected $tplfile = '';
    // 模版全路径
    protected $tplfilepath = '';
    // 模版
    protected $theme = '';
    protected $pt;

    public function __construct() {
        $this->pt=PT_Base::getInstance();
        PT_Plugin::call('view_start');
        //初始化模版
        $this->getTheme();
    }

    public function setFile($file) {
        $this->tplfile = $file;
    }

    public function setPath($path) {
        $this->tplpath = $path;
    }

    /**
     * 获取风格
     *
     * @return mixed|string
     */
    public function getTheme() {
        //设置了默认模版名
        $this->theme = $this->pt->config->get('tpl_theme', 'default');
        if ($this->theme) {
            //值不为空 则为自动侦测目录
            if (isset($_GET['t'])) {
                $auto = $_GET['t'];
                $this->pt->cookie->set('THEME', $auto, 25920000);
            } elseif ($this->pt->cookie->get('THEME')) {
                $auto = $this->pt->cookie->get('THEME');
            }
            if (isset($auto)) {
                if (is_dir($this->tplpath . '/' . $auto)) {
                    $this->theme = $auto;
                    $this->pt->config->set('tpl_theme', $this->theme);
                } else {
                    $this->pt->cookie->del('THEME');
                }
            }
            //读取模版配置文件
            if ($tplconfig = pt::import($this->tplpath . '/' . $this->theme . '/config.php')) {
                foreach ($tplconfig as $k => $v) {
                    $this->pt->config->set("tplconfig.{$k}", $v['value']);
                }
            }
        }
        return $this->theme;
    }

    /**
     * 模板变量赋值,支持连贯操作
     *
     * @access public
     * @param mixed $var
     * @param mixed $value
     * @return PT_View
     */
    public function set($var, $value = null) {
        if (is_array($var)) {
            $this->_tpl_vars = array_merge($this->_tpl_vars, $var);
        } else {
            $this->_tpl_vars[$var] = $value;
        }
        return $this;
    }

    /*
     * 获取模板变量值
     */
    public function get($var = '') {
        if ($var == '') return $this->_tpl_vars;
        if (isset($this->_tpl_vars[$var])) return $this->_tpl_vars[$var];
        if (strpos($var, '.') !== false) {
            $arr = explode('.', $var);
            $tmp = $this->_tpl_vars;
            foreach ($arr as $v) {
                if (substr($v, 0, 1) === '$') $v = $this->get($v);
                $tmp = $tmp[$v];
            }
            if (!empty($tmp)) {
                return $tmp;
            }
        }
        return null;
    }

    public function __set($name, $var) {
        if (!is_object($var))   return $this->set($name, $var);
        return false;
    }

    public function __get($name) {
        if (isset($this->_tpl_vars[$name])){
            return $this->_tpl_vars[$name];
        }
        return null;
    }

    /**
     * 加载并视图片段文件内容
     *
     * @access public
     * @param string $tpl    视图片段文件名称
     * @param string $module 所属模块
     * @param string $theme  所属模版
     * @return string
     */
    public function fetch($tpl = null, $module = null, $theme = null) {
        $this->tplfilepath = $this->getTplFile($tpl, $module, $theme);
        extract($this->_tpl_vars, EXTR_OVERWRITE);
        ob_start();
        include $this->checkCompile();
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * 获得模版位置
     *
     * @param string $tpl    视图模板
     * @param string $module 所属模块
     * @param string $theme  所属模版
     * @return string
     */
    protected function getTplFile($tpl, $module = null, $theme = null) {
        $tpl    = ($tpl === null) ? $this->tplfile : $tpl;
        $theme  = ($theme === null) ? $this->theme : $theme;
        $module = ($module === null) ? MODULE_NAME : $module;
        if (substr($tpl, 0, 1) === '/') { //绝对目录 可以设置模版
            $tplfile = PT_ROOT . $tpl;
            $tmpl    = $this->tplpath . "/{$theme}/" . $this->pt->config->get("tpl_public", 'public');
        } else {
            if (!$tpl) {
                $tpl = CONTROLLER_NAME . '_' . ACTION_NAME;
            }
            //判断模版目录
            $protect = $this->pt->config->get('tpl_protect', '');
            $suffix  = $this->pt->config->get('tpl_suffix', 'html');
            if ($theme) {
                // 设置了模版 模版目录为template下对应的设置的模版目录
                $tplfile = rtrim($this->tplpath . ($protect?"/{$theme}/{$protect}/{$module}":"/{$theme}/{$module}"), '/') . "/{$tpl}.{$suffix}";
                if (!is_file($tplfile)) {
                    //没有找到的模版默认使用default匹配一次
                    if (is_file($this->tplpath . "/{$theme}/{$module}/{$tpl}.{$suffix}")) {
                        //去掉保护目录
                        $tplfile = $this->tplpath . "/{$theme}/{$module}/{$tpl}.{$suffix}";
                        $this->pt->log->record('指定的模版（' . $tplfile . '）不存在，尝试使用' . $tplfile . '模版成功');
                    } elseif ($theme !== 'default' && is_file($this->tplpath . "/default/{$module}/{$tpl}.{$suffix}")) {
                        //使用默认模版
                        $this->pt->log->record('指定的模版（' . $tplfile . '）不存在，尝试使用默认模版成功');
                        $tplfile = $this->tplpath . "/default/{$module}/{$tpl}.{$suffix}";
                        $theme   = 'default';
                    }
                }
                $tmpl = $this->tplpath . "/{$theme}/" . $this->pt->config->get("tpl_public", 'public');
            } else {
                //未设置模版 模版目录为对应模块的view目录
                $tplfile = APP_PATH . "/{$module}/view/{$tpl}.{$suffix}";
                $tmpl    = APP_PATH . "/{$module}/view/";
            }
        }
        $realtpl = str_replace('\\', '/', realpath($tplfile));
        if (!$realtpl) {
            if (APP_DEBUG){
                halt("模版{$tpl}不存在:" . $tplfile);
            }else{
                $this->pt->response->error("模版{$tpl}不存在");
            }
        }
        defined('__TMPL__') || define('__TMPL__', rtrim(PT_DIR . str_replace(PT_ROOT, '', $tmpl), '/'));
        return $realtpl;
    }

    /**
     * @return string
     */
    protected function checkCompile() {
        $tplfile      = ltrim(str_replace(array(PT_ROOT, '/application/', '/template/'), '/', $this->tplfilepath), '/');
        $compiledFile = CACHE_PATH . '/template/' . substr(str_replace('/', ',', $tplfile), 0, -5) . '.php';
        if (APP_DEBUG || !is_file($compiledFile) || filemtime($compiledFile) < filemtime($this->tplfilepath)) {
            // 获取模版内容
            $content = F($this->tplfilepath);
            $this->pt->plugin->call('template_compile_start', $content);
            $driverclass = 'Driver_View_' . $this->pt->config->get('view_driver', 'Mc');
            /* @var $driver Driver_view_MC */
            $driver = new $driverclass();
            // 解析模版
            $content = $driver->compile($content);
            //判断是否开启layout
            if ($this->pt->config->get('layout', false)) {
                $includeFile = $this->getTplFile($this->pt->config->get('layout_name', 'layout'));
                $layout      = $driver->compile(F($includeFile));
                $content     = str_replace('__CONTENT__', $content, $layout);
            }
            $content = '<?php defined(\'PT_ROOT\') || exit(\'Permission denied\');?>' . $this->replace($content);
            $this->pt->plugin->call('template_compile_end', $content);
            F($compiledFile, $content);
        }
        return $compiledFile;
    }

    // 模版输出替换
    protected function replace($content) {
        $replace = array(
            '__TMPL__'    => '<?php echo __TMPL__;?>', // 项目模板目录
            '__ROOT__'    => '<?php echo PT_DIR;?>', // 当前网站地址
            '__APP__'     => '<?php echo __APP__;?>', // 当前项目地址
            '__MODULE__'  => '<?php echo __MODULE__;?>',
            '__ACTION__'  => '<?php echo __ACTION__;?>', // 当前操作地址
            '__SELF__'    => '<?php echo __SELF__;?>', // 当前页面地址
            '__URL__'     => '<?php echo  __URL__;?>', // 当前控制器地址
            '__DIR__'  => '<?php echo PT_DIR;?>', // 站点公共目录
            '__PUBLIC__'  => '<?php echo PT_DIR;?>' . '/public', // 站点公共目录
            '__RUNINFO__' => '<?php echo $this->pt->response->runinfo();?>', // 站点公共目录
        );
        $content = strtr($content, $replace);
        // 判断是否显示runtime info 信息
        return $content;
    }

}

/**
 * 默认值函数
 *
 * @return string
 */
function defaultvar() {
    $args  = func_get_args();
    $value = array_shift($args);
    if(!is_numeric($value)){
        return $value;
    }elseif (isset($args[$value])) {
        return $args[$value];
    } else {
        return '';
    }
}


/**
 * 时间函数优化
 *
 * @param $time
 * @param $format
 * @return mixed
 */
function datevar($time, $format) {
    if ($time == '0') return '';
    return date($format, $time);
}

/**
 * @param string $content
 * @return string
 */
function parseTpl($content) {
    if ($content=='') return '';
    $cachefile = CACHE_PATH . '/template/parsetpl/' . md5($content) . '.php';
    if (!is_file($cachefile)) {
        $driverclass = 'Driver_View_' . PT_Base::getInstance()->config->get('view_driver', 'Mc');
        /* @var $driver Driver_view_MC */
        $driver  = new $driverclass();
        $content = $driver->compile($content);
        F($cachefile, $content);
    }
    return $cachefile;
}
class view extends PT_view{}
