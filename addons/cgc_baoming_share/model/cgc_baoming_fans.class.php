<?php

class cgc_baoming_fans
{
    public function __construct()
    {
        $this->table_name = 'cgc_baoming_fans';
        $this->columns = '*';

       
    }

    public function selectByOpenid($openid){
        global $_W;
        $uniacid = $_W['uniacid'];
        $user = pdo_fetch("SELECT ". $this->columns ." FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and openid=:openid ",array(':openid'=>$openid));
        return $user;
    }
    
        public function selectByUser($openid,$activity_id){
        global $_W;
        $uniacid = $_W['uniacid'];
        $user = pdo_fetch("SELECT ". $this->columns ." FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id",array(':openid'=>$openid,':activity_id'=>$activity_id));
        return $user;
    }
    
    
      public function deleteAll($id) {
        global $_W;
        $condition = '`uniacid`=:uniacid';
          $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if (!empty($id)){
         $condition .= ' and `activity_id`=:id';
           $pars[':id'] = $id;
        }
      
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
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $id;       
        $sql = 'DELETE FROM ' . tablename($this->table_name) . ' WHERE `uniacid`=:uniacid AND `id`=:id';
        $ret=pdo_query($sql, $pars);      
        return $ret;
    }
    

    public function getOne($id) {
        global $_W;
        $condition = '`uniacid`=:uniacid AND `id`=:id';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
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
        $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE {$con} ORDER BY `id` DESC LIMIT {$start},{$psize}";  
        $ds = pdo_fetchall($sql);
        return $ds;
    }
    
    
        public function getTotal($con) {
        global $_W;
        $sql = "SELECT COUNT(*) FROM " . tablename($this->table_name) . " WHERE {$con}";
        $total = pdo_fetchcolumn($sql);
      
        return $total;
    }
    
    
     
    
     


}