<?php

class cgc_ad_qr_task
{
    public function __construct()
    {
        $this->table_name = 'cgc_ad_qr_task';
        $this->columns = '*';
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
    
    
      public function deleteAll($advid) {
        global $_W;
        $condition = '`weid`=:uniacid and advid=:advid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':advid'] = $advid;

        $sql = 'delete FROM ' . tablename($this->table_name) . " WHERE {$condition}";
       return pdo_query($sql, $pars);
    }
    
       public function getByConAll($con,$key="") {
        global $_W;
        $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE $con"; 
        if  (empty($key)){
          $ds = pdo_fetchall($sql);
        } else {
          $ds = pdo_fetchall($sql,array(),$key);
        }
        return $ds;
    }
    
      public function getByCon($con) {
        global $_W;
        $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE $con";  
        $ds = pdo_fetch($sql);
        return $ds;
    }
     
     
        public function getOne($id) {
        global $_W;
        $condition = '`weid`=:uniacid AND `id`=:id';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $id;
        $sql = 'SELECT * FROM ' . tablename($this->table_name) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        return $entity;
    }
    
    
   public function delete($advid,$id) {
        global $_W;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $id;   
        $pars[':advid'] = $advid;        
        $sql = 'DELETE FROM ' . tablename($this->table_name) . ' WHERE `weid`=:uniacid AND `id`=:id and advid=:advid';
        $ret=pdo_query($sql, $pars);      
        return $ret;
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
    
}