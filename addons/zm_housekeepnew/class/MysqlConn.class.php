<?php
/**
 *
 * 数据库操作
 *
 **/
defined('IN_IA') or exit('Access Denied');

class MysqlConn extends WeModuleSite
{
    //$tablename 数据表名  $data需要查的数据  $fields返回的字段名称 $keyfield
    public function myGet($tablename, $data = array(), $fields = array())
    {
        global $_W;
        return pdo_get($tablename, $data, $fields);
    }

    public function myGetall($tablename, $data = array(), $fields = array(), $keyfield = '')
    {
        global $_W;
        return pdo_getall($tablename, $data, $fields, $keyfield);
    }

    //$limit 值定查询语句的limit值 array(start,end) $total 查询的总条数
    public function myGetsel($tablename, $data = '', $id, $pindex, $psize, $a = '')
    {
        global $_W;
        return pdo_fetchall("SELECT $data FROM " . tablename($tablename) . " WHERE wid = '" . $_W['uniacid'] . "' $a ORDER BY $id  LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
    }

    public function myGettop($tablename, $data = '', $a = '',$id)
    {
        global $_W;
        return pdo_fetchall("SELECT $data FROM " . tablename($tablename) . " WHERE wid = '" . $_W['uniacid'] . "' $a ORDER BY $id");
    }

    //$where where条件 $params where条件多对应的值
    public function myGetnum($tablename, $where = '', $params = array())
    {
        global $_W;
        return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($tablename) . " $where", $params);
    }

    public function myGetlian($field, $tab, $tab1, $data)
    {
        global $_W;

        return pdo_fetch("SELECT $field FROM " . tablename($tab) . " LEFT JOIN " . tablename($tab1) . " ON " . $data);

    }

    public function myInsert($database, $data)
    {
        global $_W;
        pdo_insert($database, $data);
        $uid = pdo_insertid();
        return $uid;
    }

    public function myUpdate($database, $data = array(), $sele = array(), $glue = '')
    {
        global $_W;
        return pdo_update($database, $data, $sele, $glue);
    }

    public function myDelete($database, $data = array(), $glue = 'AND')
    {
        global $_W;
        return pdo_delete($database, $data, $glue);
    }

    public function myPager($table, $gpc,$a='',$id='id',$data='*',$where=" WHERE wid=:wid")
    {
        global $_W;
        $pindex = max(1, intval($gpc));
        $psize = 15;
        $total = $this->myGetnum($table, $where, array(':wid' => $_W['uniacid']));
        $all['all'] = $this->myGetsel($table, $data, $id, $pindex, $psize,$a);
        $all['pager'] = pagination($total, $pindex, $psize);
        $all['total'] =  $total;
        return $all;
    }
}
