<?php
/**
 * Class PT_Base
 *
 * @property PT_Api $api
 * @property PT_Block $block
 * @property PT_Cache $cache
 * @property PT_Config $config
 * @property PT_Controller $controller
 * @property PT_Cookie $cookie
 * @property PT_Db $db
 * @property PT_Dispatcher $dispatcher
 * @property PT_Filter $filter
 * @property PT_Input $input
 * @property PT_Log $log
 * @property PT_Model $model
 * @property PT_Plugin $plugin
 * @property PT_Request $request
 * @property PT_Response $response
 * @property PT_Session $session
 * @property PT_Storage $storage
 * @property PT_View $view
 * @property PT_base $pt
 */
class PT_Base {

    protected static $single = null;
    protected static $_class = array();
    protected static $_model = array();
    protected $protected = array('api', 'block', 'cache', 'controller', 'config', 'cookie', 'db', 'dispatcher', 'filter', 'input', 'log', 'model', 'plugin', 'request', 'response', 'session', 'storage', 'view', 'pt');


    /**
     * @return PT_Base
     */
    public static function getInstance() {
        if (!self::$single) {
            self::$single = new PT_Base();
        }
        return self::$single;
    }

    /**
     * @return PT_Base
     */
    public function getInstanceof($name) {
        if (isset(self::$_class[$name])) {
            return self::$_class[$name];
        }
        if ($name == 'pt') {
            return $this->pt = self::getInstance();
        }
        if (is_file(PT_PATH . "/core/{$name}.php")) {
            $classname = 'PT_' . $name;
            if (!class_exists($classname, true)) pt::import(PT_PATH . "/core/{$name}.php");
            return self::$_class[$name] = new $classname();
        }
        return null;
    }

    public function __get($name) {
        return $this->$name = $this->getInstanceof($name);
    }

    /**
     * @param $name
     * @return Model|object
     */
    public function model($name='') {
        $class = null;
        if (isset(self::$_model[$name])) return self::$_model[$name];
        $classname = $name . 'Model';
        if (class_exists($classname)) {
            return self::$_model[$name] = new $classname($name);
        } elseif ( $this->db($name)) {
            return self::$_model[$name] = $this->db($name);
        }
        return $class;
    }

    /**
     * @param $name
     * @return Driver_Db_Dao
     */
    public function db($name) {
        return  $this->pt->config->get('db_type')?$this->getInstanceof('db')->getInstance($name):false;
    }

    /**
     * @param $name
     * @return PT_model
     */
    public function block($name) {
        return $this->getInstanceof('block')->getInstance($name);
    }

    public function load($name, $param=array()) {
        if(class_exists($name)){
            if(!empty($param) && method_exists($name,'__construct')){
                return call_user_func_array(array($name,'__construct'),$param);
            }else{
                return new $name;
            }
        }
        return false;
    }
}