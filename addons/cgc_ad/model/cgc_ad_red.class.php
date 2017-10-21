<?php

class cgc_ad_red
{
    public function __construct()
    {
        $this->table_name = 'cgc_ad_red';
        $this->columns = '*';
    }
    
    
    public function getRedByAdv($weid, $advid, $nickname){
    	global $_W;
    	$con="t1.weid=$weid AND t1.advid=$advid";
    	if (!empty($nickname)) {
    		$con .= " AND (t2.nicheng like '%{$nickname}%' or t2.nickname like '%{$nickname}%') ";
    	}
    	 
    	$sql = "SELECT t1.*,t2.openid,t2.nickname,t2.headimgurl FROM " .tablename($this->table_name) . " as t1". 
    		" left join   ".tablename('cgc_ad_member')." t2  on t1.mid=t2.id WHERE $con ORDER BY t1.id DESC";
    	$ds = pdo_fetchall($sql);
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