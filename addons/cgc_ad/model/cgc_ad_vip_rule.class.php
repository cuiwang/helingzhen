<?php

class cgc_ad_vip_rule
{
    public function __construct()
    {
        $this->table_name = 'cgc_ad_vip_rule';
        $this->columns = '*';
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
    
    public function getRulesByQuan($weid, $quanid){
    	global $_W;
    	$con=" weid=$weid AND quan_id=$quanid";
    	
    	$sql = 'SELECT * FROM ' . tablename($this->table_name) . " WHERE {$con} order by id asc";
    	$ds = pdo_fetchall($sql);
    	return $ds;
    }
    
	public function getByConAll($con="",$key="") {
        global $_W;
        $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE weid={$_W['uniacid']} $con";
         if (empty($key)){
           $ds = pdo_fetchall($sql);
         } else {
           $ds = pdo_fetchall($sql,array(),$key);
         }
        return $ds;
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
    
     public function deleteAll($con="") {
        global $_W;
        $condition = '`weid`=:uniacid '.$con;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $sql = 'delete FROM ' . tablename($this->table_name) . " WHERE {$condition}";
       return pdo_query($sql, $pars);
    }
    
   public function delete($id) {
        global $_W;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $id;       
        $sql = 'DELETE FROM ' . tablename($this->table_name) . ' WHERE `weid`=:uniacid AND `id`=:id';
        $ret=pdo_query($sql, $pars);      
        return $ret;
    }
    

}