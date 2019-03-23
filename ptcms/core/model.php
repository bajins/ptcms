<?php

/**
 * Class PT_Model
 *
 * @method $this field() field( $var)
 * @method $this where() where( $var)
 * @method $this option() option( $var)
 * @method $this data() data( $var)
 * @method $this db() db( $var)
 * @method $this distinct() distinct( $var)
 * @method $this table() table( $var)
 * @method $this having() having( $var)
 * @method $this group() group( $var)
 * @method $this page() page( $var)
 * @method $this limit() limit( $var)
 * @method $this order() order( $var)
 * @method $this join() join( $var)
 * @method bool setTable() setTable( $var)
 * @method string getPk() getPk()
 * @method int sum() sum( $var)
 * @method int avg() avg( $var)
 * @method int min() min( $var)
 * @method int max() max( $var)
 * @method int count() count()
 * @method array find() find()
 * @method array select() select()
 * @method int|bool insert() insert(array $id)
 * @method int|bool insertAll() insertAll(array $id)
 * @method bool update() update(array $id)
 * @method bool delete() delete()
 * @method mixed getField() getField( $var, $res=false)
 * @method mixed setField() setField( $var)
 * @method mixed setInc() setInc( $field, $var=1)
 * @method mixed setDec() setDec( $field, $var=1)
 * @method mixed getLastSql() getLastSql()
 * @method mixed getError() getError()
 * @method mixed query() query( $var)
 * @method mixed execute() execute( $var)
 * @method mixed fetch() fetch( $var)
 * @method mixed fetchall() fetchall( $var)
 */
class PT_Model extends PT_Base {

    protected $table;
    protected $hasdb = false;
    protected static $_class = array();
    protected static $_data = array();
    protected $dbhand;

    public function __construct() {
        $classname=get_class($this);
        $name=strtolower(substr($classname,0,-5));
        if (isset(self::$_model[$name])) return self::$_model[$name];
        self::$_model[$name] = $this;
        if ($this->config->get('db_type')) $this->hasdb = true;
    }

    /**
     * __call魔法方法 用于调用db相关的方法
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args) {
        if (!$this->dbhand && $this->pt->config->get('db_type')) {
            //设置table则用table实例化db 否则是按照类名来实例化db
            $name         = $this->table ? $this->table : substr(get_class($this), 0, -5);
            $this->dbhand = $this->db($name);
        }
        if (method_exists($this->dbhand, $method)) {
            $res=call_user_func_array(array($this->dbhand, $method), $args);
            if (@is_subclass_of($res,'Driver_Db_Dao')) return $this;
            return $res;
        }
        $this->response->error('未定义的model操作', $method, 'f');
        return false;
    }

    //获取
    public function get($table, $id, $field = '') {
        $db = $this->db($table);
        if ($id == 0) return null;
        if (!isset(self::$_data[$table][$id])) {
            // 检索memCache，不存在则读取数据库
            self::$_data[$table][$id] = $this->cache->get($table . '.' . $id);
            if (APP_DEBUG || self::$_data[$table][$id] === null) {
                self::$_data[$table][$id] = $db->find($id);
                if (self::$_data[$table][$id]) {
                    //其他处理 如小说的链接
                    if ($this->model($table) && method_exists($this->model($table), 'dataAppend')) {
                        self::$_data[$table][$id] = $this->model($table)->dataAppend(self::$_data[$table][$id]);
                    }
                }
                $this->cache->set($table . '.' . $id, self::$_data[$table][$id], $this->config->get('cache_time', 900));
            }
        }
        if ($field !== '') {
            if (strpos($field, '.')) {
                $name  = explode('.', $field);
                $value = self::$_data[$table][$id];
                foreach ($name as $n) {
                    if (isset($value[$n])) {
                        $value = $value[$n];
                    } else {
                        return null;
                    }
                }
                return $value;
            } elseif (strpos($field, ',')) {
                //多字段获取  如"novelid,novelname"
                return array_intersect_key(self::$_data[$table][$id], array_flip(explode(',', $field)));
            } else {
                //单字段
                if (isset(self::$_data[$table][$id][$field])) {
                    return self::$_data[$table][$id][$field];
                } else {
                    return null;
                }
            }
        }
        return self::$_data[$table][$id];
    }

    //刷新
    public function flush($table, $id, $field = ''){
        $this->rm($table,$id);
        return $this->get($table,$id.$field);
    }

    //设置
    public function set($table, $id, $data) {
        $db = $this->db($table);
        if (false!==$db->where(array($db->getPk() => $id))->update($data)) {
            return $this->rm($table, $id);
        } else {
            return false;
        }
    }

    //删除
    public function rm($table, $id) {
        $this->cache->rm($table . '.' . $id);
        unset(self::$_data[$table][$id]);
        return true;
    }
}

class model extends PT_model{}