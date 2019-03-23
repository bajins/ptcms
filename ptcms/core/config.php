<?php
class PT_Config extends PT_Base{

    protected static $_config=array();

    /**
     * 获取参数
     * @param string $name  参数名
     * @param null $default 默认值
     * @return array|null
     */
    public function get($name='', $default = null) {
        if ($name=='') return self::$_config;
        $name = strtolower($name);
        if (strpos($name,'.')){
            //数组模式 找到返回
            $c = self::$_config;
            $fields = explode('.', $name);
            foreach($fields as $field){
                if(!isset($c[$field])) return $default;
                $c = $c[$field];
            }
            return $c;
        }else{
            //非数组模式 找不到用默认值，并且设置默认值
            if (isset(self::$_config[$name])){
                return self::$_config[$name];
            }else{
                self::$_config[$name]=$default;
                return self::$_config[$name];
            }
        }
    }

    /**
     * 设置值 目前只
     * @param $key
     * @param $var
     * @return bool
     */
    public function set($key, $var='') {
        // 设置值
        if(empty($key)) return false;
        //数组 调用注册方法
        if (is_array($key)) return $this->register($key);
        //多级模式
        $c = self::$_config;
        $k = &$c;
        $fields = explode('.', $key);
        foreach ($fields as $field) {
            $k = &$k[$field];
        }
        $k = $var;
        self::$_config=$c;
        return true;
    }

    /*
	 * 注册配置
	 */
    public function register($config){
        if (!is_array($config)) return false;
        self::$_config = array_merge(self::$_config, array_change_key_case($config));
        return true;
    }

    // save 只可以写入公共配置文件的 支持数组
    public function save($key, $value='') {
        $file=APP_PATH . '/common/config.php';
        $config = include $file;
        if (is_array($key)){
            $config=array_merge($config, $key);
        }elseif(isset($config[$key])){
            $config[$key]=$value;
        }else{
            return;
        }
        if (!F($file, $config)){
            $this->response->error('修改失败，请检查'.$file . '文件权限',0,0);
        };
    }
}
class config extends PT_config{}