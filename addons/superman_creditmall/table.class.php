<?php
/**
 * 【微赞】超级商城模块微站定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/thread-13060-1-1.html
 */
defined('IN_IA') or exit('Access Denied');
class SupermanTable {
    protected $_table;
    protected $_cache_key;
    protected $_pk = 'id';
    public function __construct($tablename) {
        $this->_table = $tablename;
    }
    public function fetch($filter, $orderby = '') {
        $data = array();
        if (!empty($filter) && is_array($filter)) {
            $where = 'WHERE 1=1';
            $params = array();
            foreach ($filter as $key=>$val) {
                $kpos = strpos($key, '#');
                if ($kpos !== false) {
                    $key = substr($key, 0, $kpos);
                }
                if (is_string($val) && substr($val, 0, 1) == '#') {    //自定义条件比较符号
                    $val = substr($val, 1);
                    $where .= " AND {$key}{$val}";
                } else if (is_array($val)) {    //支持in查询条件
                    $where .= " AND {$key} IN (".implode(',', $val).")";
                } else {
                    $where .= " AND {$key}=:{$key}";
                    $params[":{$key}"] = $val;
                }
            }
        } else if ($filter > 0 && is_numeric($filter)) {
            $where = " WHERE {$this->_pk}=:{$this->_pk}";
            $params[":{$this->_pk}"] = $filter;
        } else {
            return $data;
        }
        $sql = 'SELECT * FROM '.tablename($this->_table)." {$where} {$orderby} LIMIT 1";
        $data = pdo_fetch($sql, $params);
        return $data;
    }
    public function fetchall($filter, $orderby = '', $start = 0, $pagesize = 10, $keyfiled = '') {
        $data = array();
        if (!empty($filter)) {
            $where = 'WHERE 1=1';
            $params = array();
            foreach ($filter as $key=>$val) {
                $kpos = strpos($key, '#');
                if ($kpos !== false) {
                    $key = substr($key, 0, $kpos);
                }
                if (is_string($val) && substr($val, 0, 1) == '#') {    //自定义条件比较符号
                    $val = substr($val, 1);
                    $where .= " AND {$key}{$val}";
                } else if (is_array($val)) {    //支持in查询条件
                    $where .= " AND {$key} IN (".implode(',', $val).")";
                } else {
                    $where .= " AND {$key}=:{$key}";
                    $params[":{$key}"] = $val;
                }
            }
            if ($orderby == '') {
                $orderby = 'ORDER BY '.$this->_pk.' DESC';
            }
            $limit = '';
            if ($pagesize > 0) {
                $limit = "LIMIT {$start},{$pagesize}";
            }
            $sql = 'SELECT * FROM '.tablename($this->_table)." {$where} {$orderby} {$limit}";
            $data = pdo_fetchall($sql, $params, $keyfiled);
        }
        return $data;
    }
    public function count($filter) {
        $data = array();
        if (!empty($filter)) {
            $where = 'WHERE 1=1';
            $params = array();
            foreach ($filter as $key=>$val) {
                $kpos = strpos($key, '#');
                if ($kpos !== false) {
                    $key = substr($key, 0, $kpos);
                }
                if (is_string($val) && substr($val, 0, 1) == '#') {    //自定义条件比较符号
                    $val = substr($val, 1);
                    $where .= " AND {$key}{$val}";
                } else if (is_array($val)) {    //支持in查询条件
                    $where .= " AND {$key} IN (".implode(',', $val).")";
                } else {
                    $where .= " AND {$key}=:{$key}";
                    $params[":{$key}"] = $val;
                }
            }
            $sql = 'SELECT COUNT(*) FROM '.tablename($this->_table)." {$where}";
            $data = pdo_fetchcolumn($sql, $params);
        }
        return $data;
    }
    public function sum($filter, $field = '*') {
        $data = array();
        if (!empty($filter)) {
            $where = 'WHERE 1=1';
            $params = array();
            foreach ($filter as $key=>$val) {
                $kpos = strpos($key, '#');
                if ($kpos !== false) {
                    $key = substr($key, 0, $kpos);
                }
                if (is_string($val) && substr($val, 0, 1) == '#') {    //自定义条件比较符号
                    $val = substr($val, 1);
                    $where .= " AND {$key}{$val}";
                } else if (is_array($val)) {    //支持in查询条件
                    $where .= " AND {$key} IN (".implode(',', $val).")";
                } else {
                    $where .= " AND {$key}=:{$key}";
                    $params[":{$key}"] = $val;
                }
            }
            $sql = 'SELECT SUM('.$field.') FROM '.tablename($this->_table)." {$where}";
            $data = pdo_fetchcolumn($sql, $params);
        }
        return $data;
    }
    public function increment($data, $condition = array(), $glue= 'AND') {
        $result = false;
        if (is_array($data) && $data) {
            $condition = $this->implode($condition, $glue);
            $params = $condition['params'];
            $sql = "UPDATE ".tablename($this->_table)." SET ";
            $fields = array();
            foreach ($data as $field=>$value) {
                $fields[] = "{$field}={$field}+{$value}";
            }
            $sql .= implode(',', $fields);
            $sql .= $condition['fields'] ? ' WHERE '.$condition['fields'] : '';
            $result = pdo_query($sql, $params);
        }
        return $result;
    }
    public function decrement($data, $condition = array(), $glue= 'AND') {
        $result = false;
        if (is_array($data) && $data) {
            $condition = $this->implode($condition, $glue);
            $params = $condition['params'];
            $sql = "UPDATE ".tablename($this->_table)." SET ";
            $fields = array();
            foreach ($data as $field=>$value) {
                $fields[] = "{$field}={$field}-{$value}";
            }
            $sql .= implode(',', $fields);
            $sql .= $condition['fields'] ? ' WHERE '.$condition['fields'] : '';
            $result = pdo_query($sql, $params);
        }
        return $result;
    }
    public function insert($data) {
        pdo_insert($this->_table, $data);
        return pdo_insertid();
    }
    public function update($data, $condition) {
        return pdo_update($this->_table, $data, $condition);
    }
    public function delete($condition) {
        return pdo_delete($this->_table, $condition);
    }
    public function key($str) {
        return $this->_cache_key.$str;
    }
    public function field_exists($field) {
        return pdo_fieldexists($this->_table, $field);
    }
    //cache
    public function cache_read($key, $unserialize = false) {
        global $_W;
        if ($_W['config']['setting']['cache'] == 'memcache') {
            return cache_load($key, $unserialize);
        }
        return null;
    }
    public function cache_write($key, $value, $ttl = 0) {
        global $_W;
        if ($_W['config']['setting']['cache'] == 'memcache') {
            return cache_write($key, $value, $ttl);
        }
    }
    private function implode($params, $glue = ',') {
        $result = array('fields' => ' 1 ', 'params' => array());
        $split = '';
        $suffix = '';
        if (in_array(strtolower($glue), array('and', 'or'))) {
            $suffix = '__';
        }
        if (!is_array($params)) {
            $result['fields'] = $params;
            return $result;
        }
        if (is_array($params)) {
            $result['fields'] = '';
            foreach ($params as $fields => $value) {
                if (is_array($value)) {
                    $result['fields'] .= $split . "`$fields` IN ('".implode("','", $value)."')";
                } else {
                    $result['fields'] .= $split . "`$fields` =  :{$suffix}$fields";
                    $split = ' ' . $glue . ' ';
                    $result['params'][":{$suffix}$fields"] = is_null($value) ? '' : $value;
                }
            }
        }
        return $result;
    }
}
if (!class_exists('M')) {
    class M {
        static private $_objs;
        public static function t($tablename) {
            $classname = 'table_'.$tablename;
            if (!isset(self::$_objs[$classname])) {
                if (!class_exists($classname, false)) {
                    $filename = dirname(__FILE__).'/table/'.$tablename.'.php';
                    if (file_exists($filename)) {
                        require $filename;
                        self::$_objs[$classname] = new $classname;
                    } else {
                        if (func_num_args()) {
                            $ref = new ReflectionClass('SupermanTable');
                            $obj = $ref->newInstanceArgs(func_get_args());
                            if ($obj->field_exists('id')) {
                                self::$_objs[$classname] = $obj;
                            } else {
                                trigger_error('表文件 "'.'table/'.$tablename.'.php" 不存在，并且不存在 "id" 主键', E_USER_ERROR);
                            }
                        }
                    }
                }
            }
            return self::$_objs[$classname];
        }
    }
} else {
    exit('class M conflict');
}