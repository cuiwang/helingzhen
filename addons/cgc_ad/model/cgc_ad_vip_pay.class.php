<?php
class cgc_ad_vip_pay
{
public function __construct()
    {
       $this->table_name = 'cgc_ad_vip_pay';
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
    
}
