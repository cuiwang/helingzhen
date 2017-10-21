<?php

class cgc_ad_tx
{
    public function __construct()
    {
       $this->table_name = 'cgc_ad_tx';
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
    
     public function hasExists($quan_id,$openid) {
       global $_W;
       $con="quan_id=$quan_id and openid='$openid' and status=0 and tx_time<".time().
       " group by sj having count(sj)>1";
       $sql = "SELECT FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i') sj FROM " . tablename($this->table_name) . " WHERE $con "; 
       $ds = pdo_fetchall($sql);
       if (empty($ds)){
         $con="quan_id=$quan_id and openid='$openid' and status=0 and tx_time<".time();
         $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE $con "; 
         $ds = pdo_fetch($sql);
       } else {
       	 return  array("code"=>-1,"msg"=>"有重复记录");
       }
       
       
       
       if (empty($ds)){
       	 return  array("code"=>-2,"msg"=>"没有记录");
       } else {
         return array("code"=>0,"msg"=>$ds);
       }
       
      /* if (count($ds)>1){
         
       }*/
     /*  $new_array=array();
       foreach ($ds as $item){
        $new_array[]=date('Y-m-d H:i', $item['tx_time']);
       }*/
      /* return $ds;*/
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
    
   public function delete($quan_id,$id) {
       global $_W;
       $pars = array();
       $pars[':uniacid'] = $_W['uniacid'];
       $pars[':id'] = $id;
       $pars[':quan_id'] = $quan_id;        
       $sql = 'DELETE FROM ' . tablename($this->table_name) . ' WHERE `weid`=:uniacid AND `id`=:id AND `quan_id`=:quan_id';

       $ret=pdo_query($sql, $pars);
       return $ret;
    }

	public function deleteone($id) {
		global $_W;
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':id'] = $id;
		$sql = 'DELETE FROM ' . tablename($this->table_name) . ' WHERE `weid`=:uniacid AND `id`=:id';

		$ret=pdo_query($sql, $pars);
		return $ret;
	}
    
}