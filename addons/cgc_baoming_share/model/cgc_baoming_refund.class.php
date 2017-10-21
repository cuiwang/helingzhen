<?php

class cgc_baoming_refund
{
    public function __construct()
    {
        $this->table_name = 'cgc_baoming_refund';
        $this->columns = '*';
       
    }


      public function deleteAll() {
        global $_W;
        $condition = '`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $sql = 'delete FROM ' . tablename($this->table_name) . " WHERE {$condition}";
       return pdo_query($sql, $pars);
    }
    
 
    

    
      
    public function insert($entity) {
        global $_W;
        $ret = pdo_insert($this->table_name, $entity);
   
        return $ret;
    }
    
    
    public function modify($id,$entity) {
        global $_W;
        $uniacid = $_W['uniacid'];
        $ret = pdo_update($this->table_name, $entity, array('uniacid'=>$uniacid,'id'=>$id));
        return $ret;
    }

    public function delete($id) {
        global $_W;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $id;   
        $sql = 'DELETE FROM ' . tablename($this->table_name) . ' WHERE `uniacid`=:uniacid and id=:id';
        $ret=pdo_query($sql, $pars);      
        return $ret;
    }
    

    public function getOne($id) {
        global $_W;
        $condition = '`uniacid`=:uniacid and id=:id';
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
        $sql = "SELECT * FROM " . tablename($this->table_name) . " WHERE {$con} ORDER BY `uniacid` DESC LIMIT {$start},{$psize}";  
        $ds = pdo_fetchall($sql);
        return $ds;
    }
    
     
    
     


}