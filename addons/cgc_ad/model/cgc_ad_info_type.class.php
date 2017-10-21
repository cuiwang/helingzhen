<?php

class cgc_ad_info_type
{
    public function __construct()
    {
        $this->table_name = 'cgc_ad_info_type';
        $this->columns = '*';
    }

    public function selectByOpenid($openid){
        global $_W;
        $weid = $_W['uniacid'];
        $user = pdo_fetch("SELECT ". $this->columns ." FROM ". tablename($this->table_name) 
        ." WHERE weid=$weid and openid=:openid ",array(':openid'=>$openid));
        return $user;
    }
    
    
    public function selectByParentid($id){
        global $_W;
        $weid = $_W['uniacid'];
        $parent_id = pdo_fetchcolumn("SELECT parent_id FROM ". tablename($this->table_name) 
        ." WHERE weid=$weid and id=:id ",array(':id'=>$id));
        return $parent_id;
    }
    
      public function deleteAll() {
        global $_W;
        $condition = '`weid`=:weid';
        $pars = array();
        $pars[':weid'] = $_W['uniacid'];
        $sql = 'delete FROM ' . tablename($this->table_name) . " WHERE {$condition}";
       return pdo_query($sql, $pars);
    }
 
    public function insert($entity) {
        global $_W;
        $ret = pdo_insert($this->table_name, $entity);
        if(!empty($ret)) {
            $id = pdo_insertid();
            return $id;
        }
        return false;
    }
    
    
    public function modify($id, $entity) {
        global $_W;
        $id = intval($id);
        $ret = pdo_update($this->table_name, $entity, array('id'=>$id));
        return $ret;
    }

    public function delete($id) {
        global $_W;
        $pars = array();
        $pars[':weid'] = $_W['uniacid'];
        $pars[':id'] = $id;       
        $sql = 'DELETE FROM ' . tablename($this->table_name) . ' WHERE `weid`=:weid AND `id`=:id';
        $ret=pdo_query($sql, $pars);      
        return $ret;
    }
    

    public function getOne($id) {
        global $_W;
        $condition = '`weid`=:weid AND `id`=:id';
        $pars = array();
        $pars[':weid'] = $_W['uniacid'];
        $pars[':id'] = $id;
        $sql = 'SELECT * FROM ' . tablename($this->table_name) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        return $entity;
    }
    

    public function getAll($con, $pindex = 0, $psize = 20, &$total = 0) {
        global $_W;
        $sql = "SELECT COUNT(*) FROM " . tablename($this->table_name) . " WHERE {$con}";
        $total = pdo_fetchcolumn($sql);
        $start = ($pindex - 1) * $psize;
        $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE {$con} ORDER BY `parent_id` ASC, `id` DESC LIMIT {$start},{$psize}";  
        $ds = pdo_fetchall($sql);
        return $ds;
    }
    
     
    
     


}