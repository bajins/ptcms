<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : Pdo.php
 */
class Driver_Db_Mysql_Pdo {

    /**
     * 单例模式实例化对象
     *
     * @var object
     */
    public static $instance;

    /**
     * 数据库连接ID
     *
     * @var object
     */
    public $db_link;
    /**
     * 事务处理开启状态
     *
     * @var boolean
     */
    protected $Transactions;

    /**
     * 构造函数
     *
     * 用于初始化运行环境,或对基本变量进行赋值
     *
     * @param array $params 数据库连接参数,如主机名,数据库用户名,密码等
     */
    public function __construct($params = array()) {
        //连接数据库 ,PDO::ATTR_PERSISTENT => true
        $params['charset']=empty($params['charset'])?'utf8':$params['charset'];
        $this->db_link = @new PDO("mysql:host={$params['host']};port={$params['port']};dbname={$params['name']}", $params['user'], $params['pwd'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$params['charset']}"));
        if (!$this->db_link) {
            trigger_error($params['driver'] . ' Server connect fail! <br/>Error Message:' . $this->error() . '<br/>Error Code:' . $this->errno(), E_USER_ERROR);
        }
        return true;
    }

    /**
     * 执行SQL语句
     *
     * SQL语句执行函数
     *
     * @access public
     * @param string $sql SQL语句内容
     * @return mixed
     */
    public function query($sql) {

        //参数分析
        if (!$sql) {
            return false;
        }
        if (APP_DEBUG || isset($_GET['debug'])) {
            $t   = microtime(true);
            $result = $this->db_link->query($sql);
            $GLOBALS['_sql'][] = number_format(microtime(true) - $t, 5) . ' - ' . $sql;
        } else {
            $result = $this->db_link->query($sql);
        }
        $GLOBALS['_sqlnum']++;
        return $result;
    }

    public function execute($sql) {
        //参数分析
        if (!$sql) {
            return false;
        }
        if (APP_DEBUG || isset($_GET['debug'])) {
            $t   = microtime(true);
            $result = $this->db_link->exec($sql);
            $GLOBALS['_sql'][] = number_format(microtime(true) - $t, 5) . ' - ' . $sql;
        } else {
            $result = $this->db_link->exec($sql);
        }
        $GLOBALS['_sqlnum']++;
        return $result;
    }

    /**
     * 获取数据库错误描述信息
     *
     * @access public
     * @return string
     */
    public function error() {
        $info = $this->db_link->errorInfo();
        return isset($info[2])?$info['2']:'';
    }

    /**
     * 获取数据库错误信息代码
     *
     * @access public
     * @return int
     */
    public function errno() {

        return $this->db_link->errorCode();
    }

    /**
     * 通过一个SQL语句获取一行信息(字段型)
     *
     * @access public
     * @param string $sql SQL语句内容
     * @return mixed
     */
    public function fetch($sql) {

        //参数分析
        if (!$sql) {
            return false;
        }

        $result = $this->query($sql);
        if (!$result) {
            return false;
        }

        $myrow = $result->fetch(PDO::FETCH_ASSOC);
        if (!$myrow) return null;

        return $myrow;
    }

    /**
     * 通过一个SQL语句获取全部信息(字段型)
     *
     * @access public
     * @param string $sql SQL语句
     * @return array
     */
    public function fetchAll($sql) {

        //参数分析
        if (!$sql) {
            return false;
        }

        $result = $this->query($sql);

        if (!$result) {
            return false;
        }

        $myrow = $result->fetchAll(PDO::FETCH_ASSOC);
        if (!$myrow) return null;

        return $myrow;
    }

    /**
     * 获取insert_id
     *
     * @access public
     * @return int
     */
    public function insertId() {
        return $this->db_link->lastInsertId();
    }

    /**
     * 转义字符
     *
     * @access public
     * @param string $string 待转义的字符串
     * @return string
     */
    public function escapeString($string) {
        //参数分析
        return addslashes($string);
    }

    /**
     * 开启事务处理
     *
     * @access public
     * @return boolean
     */
    public function startTrans() {
        if ($this->Transactions == false) {
            $this->db_link->beginTransaction();
            $this->Transactions = true;
        }
        return true;
    }

    /**
     * 提交事务处理
     *
     * @access public
     * @return boolean
     */
    public function commit() {

        if ($this->Transactions == true) {
            if ($this->db_link->commit()) {
                $this->Transactions = false;
            }
        }

        return true;
    }

    /**
     * 事务回滚
     *
     * @access public
     * @return boolean
     */
    public function rollback() {

        if ($this->Transactions == true) {
            if ($this->db_link->rollBack()) {
                $this->Transactions = false;
            }
        }
    }

    /**
     * 关闭数据库连接
     */
    public function __destruct() {

        if ($this->db_link == true) {
            $this->db_link = null;
        }
    }

    /**
     * 单例模式
     *
     * @access public
     * @param array $params 数据库连接参数,如数据库服务器名,用户名,密码等
     * @return object
     */
    public static function getInstance($params) {
        if (!self::$instance) {
            self::$instance = new self($params);
        }

        return self::$instance;
    }
}