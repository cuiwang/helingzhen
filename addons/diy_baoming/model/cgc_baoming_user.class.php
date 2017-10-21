<?php

class cgc_baoming_user
{
    public function __construct()
    {
        $this->table_name = 'cgc_baoming_user';
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
        ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id",
        array(':openid'=>$openid,':activity_id'=>$activity_id));
        return $user;
    }
    
    public function selectByUserShare($openid,$activity_id){
       	global $_W;
        $uniacid = $_W['uniacid'];
          
        $user = pdo_fetchall("SELECT ". $this->columns ." FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id",
        array(':openid'=>$openid,':activity_id'=>$activity_id));
        return $user;
    }
    
     public function selectByConcatUser($openid,$activity_id){
        global $_W;
        $uniacid = $_W['uniacid'];         
        $user = pdo_fetch("SELECT GROUP_CONCAT(cj_code) AS cj_code,nickname,openid,headimgurl,realname,tel FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id and cj_code!='' group by nickname,openid",
        array(':openid'=>$openid,':activity_id'=>$activity_id));
        return $user;
    }
    
     public function selectByGroupUser(){
        global $_W;
        $uniacid = $_W['uniacid'];        
        $list = pdo_fetchall("SELECT count(*) num,activity_id FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and share_status=1 group by activity_id",
        array(),'activity_id');
        return $list;
    }
    
    
    public function selectTotal_zj($activity_id){
        global $_W;
        $uniacid = $_W['uniacid'];        
        $count = pdo_fetchcolumn("SELECT count(*) num FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and zj_status=1 and activity_id=$activity_id"
        );
        return $count;
    }
    
    
    public function selectPay_count($activity_id){
      global $_W;
      $uniacid = $_W['uniacid'];        
      $count = pdo_fetchcolumn("SELECT count(*) num FROM ". tablename($this->table_name) 
      ." WHERE uniacid=$uniacid and is_pay=1 and activity_id=$activity_id");
      return $count;
    }
    
    
    
     

    
      public function deleteAll($activity_id) {
        global $_W;
        $condition = '`uniacid`=:uniacid and activity_id=:activity_id';
          $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
         $pars[':activity_id'] = $activity_id;
      
        $sql = 'delete FROM ' . tablename($this->table_name) . " WHERE {$condition}";
       return pdo_query($sql, $pars);
    }
    
 
    
  public function selectRecords($openid,$activity_id){
        global $_W;
        $uniacid = $_W['uniacid'];         
        $record= pdo_fetchall("SELECT * FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and activity_id=:activity_id and openid=:openid  and cj_code!='' order by cj_code desc",
        array(':openid'=>$openid,':activity_id'=>$activity_id));
        return $record;
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