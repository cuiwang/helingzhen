<?php

class cgc_baoming_activity
{
    public function __construct()
    {
        $this->table_name = 'cgc_baoming_activity';
        $this->columns = '*';

       
    }

    public function selectByOpenid($openid){
        global $_W;
        $uniacid = $_W['uniacid'];
        $user = pdo_fetch("SELECT ". $this->columns ." FROM ". tablename($this->table_name) 
        ." WHERE uniacid=$uniacid and openid=:openid ",array(':openid'=>$openid));
        return $user;
    }
    
      public function deleteAll() {
        global $_W;
        $condition = '`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $sql = 'delete FROM ' . tablename($this->table_name) . " WHERE {$condition}";
       return pdo_query($sql, $pars);
    }
    
 
    
   public function update_paystatus($openid) {
       	global $_W;
        $rec = array();
        $filters = array();
        $filters['openid'] = $openid;
        $filters['uniacid'] = $_W['uniacid'];
        $rec['pay_status'] = 1;
        $rec['paytime'] = TIMESTAMP;  
        $ret = pdo_update($this->table_name, $rec, $filters);
        if(!empty($ret)) {   
            return true;
        } else {
            return false;
        }
    }
  
  public function add_pay_num($cgc_baoming_activity,$activity_id){
    if (empty($cgc_baoming_activity)){
      $cgc_baoming_activity=pdo_fetch("select * from " . tablename('cgc_baoming_activity') . " where id=:id", array (
      'id' => $activity_id
	  ));
     }
    if ($cgc_baoming_activity){
      $ret = pdo_update('cgc_baoming_activity',array("pay_numed"=>$cgc_baoming_activity['pay_numed']+1),array ('id' => $cgc_baoming_activity['id']));
     }
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
    
     
    
     


}