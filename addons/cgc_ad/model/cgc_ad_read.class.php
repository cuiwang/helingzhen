<?php

class cgc_ad_read
{
    public function __construct()
    {
        $this->table_name = 'cgc_ad_read';
        $this->columns = '*';
    }
    
    public function getReadByAdv($weid, $advid, $quan_id, $nickname){
    	global $_W;
    	$con="t1.weid=$weid AND t1.advid=$advid AND t1.quan_id=$quan_id";
    	if (!empty($nickname)) {
    		$con .= " AND (t2.nicheng like '%{$nickname}%' or t2.nickname like '%{$nickname}%') ";
    	}
    	 
    	$sql = "SELECT t1.*,t2.openid,t2.nicheng,t2.thumb,t2.nickname,t2.headimgurl FROM " .tablename($this->table_name) . " as t1". 
    		" left join   ".tablename('cgc_ad_member')." t2  on t1.mid=t2.id WHERE $con ORDER BY t2.id DESC";
    	$ds = pdo_fetchall($sql);
    	return $ds;
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

    public function getAll($con, $pindex = 0, $psize = 20, &$total = 0) {
        global $_W;
        $sql = "SELECT COUNT(*) FROM " . tablename($this->table_name) . " WHERE {$con}";
        $total = pdo_fetchcolumn($sql);
        $start = ($pindex - 1) * $psize;
        $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE {$con} ORDER BY `id` DESC LIMIT {$start},{$psize}";  
        $ds = pdo_fetchall($sql);
        return $ds;
    }
    
   public function deleteAll() {
        global $_W;
        $condition = '`weid`=:uniacid';
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