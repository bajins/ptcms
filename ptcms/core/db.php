<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : Model.php
 */
class PT_Db{
    protected $pt;
    protected static $_config;
    protected static $_class;

    public function __construct(){
        $this->pt=PT_Base::getInstance();
    }

    /**
     * @param string $name
     * @return Driver_Db_Dao
     */
    public function getInstance($name='') {
        if (self::$_config == array()) {
            self::$_config = $this->parseConfig();
        }
        $name=($name=='')?'__empty__':$name;
        if (empty(self::$_class[$name])){
            $class='Driver_Db_'.self::$_config['type'].'_dao';
            self::$_class[$name]=new $class(self::$_config,$name);
        }
        return self::$_class[$name];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function table($name='') {
        return $this->getInstance($name);
    }

    public function parseConfig() {
        $config_params['type']=$this->pt->config->get('db_type', 'mysql');
        switch($config_params['type']){
            case 'mysql':
                $config_params['master'] = array(
                    array(
                        'host'    => $this->pt->config->get('db_mysql_master_host', $this->pt->config->get('mysql_master_host','localhost')),
                        'port'    => $this->pt->config->get('db_mysql_master_port', $this->pt->config->get('mysql_master_port','3306')),
                        'name'    => $this->pt->config->get('db_mysql_master_name', $this->pt->config->get('mysql_master_name','ptcms')),
                        'user'    => $this->pt->config->get('db_mysql_master_user', $this->pt->config->get('mysql_master_user','root')),
                        'pwd'     => $this->pt->config->get('db_mysql_master_pwd', $this->pt->config->get('mysql_master_pwd','')),
                    )
                );
                if ($this->pt->config->get('db_mysql_salve_host')){
                    $config_params['singleton']=false;
                    $config_params['slave'] = array(
                        array(
                            'host'    => $this->pt->config->get('db_mysql_salve_host', 'localhost'),
                            'port'    => $this->pt->config->get('db_mysql_salve_port', '3306'),
                            'name'    => $this->pt->config->get('db_mysql_salve_name', 'ptcms'),
                            'user'    => $this->pt->config->get('db_mysql_salve_user','root'),
                            'pwd'     => $this->pt->config->get('db_mysql_salve_pwd', ''),
                        )
                    );
                }else{
                    $config_params['singleton']=true;
                    $config_params['slave']  = $config_params['master'];
                }
                $config_params['driver']=$this->pt->config->get('db_mysql_driver', $this->pt->config->get('mysql_driver','pdo'));
                $config_params['prefix']=$this->pt->config->get('db_mysql_prefix', $this->pt->config->get('mysql_prefix','ptcms_'));
                $config_params['charset']=$this->pt->config->get('db_mysql_charset', 'utf8');
                break;
        }
        return $config_params;
    }
}
class db extends PT_db{}
