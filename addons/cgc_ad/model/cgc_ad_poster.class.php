<?php

class cgc_ad_poster {

    public function __construct()
    {
        $this->table_name = 'cgc_ad_poster';
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
    
    public function getOneByQuan($quan_id) {
        global $_W;
        $condition = '`weid`=:uniacid AND `quan_id`=:id';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $quan_id;
        $sql = 'SELECT * FROM ' . tablename($this->table_name) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        return $entity;
    }
      

    public function getAll($con, $pindex = 0, $psize = 20, &$total = 0) {
        global $_W;
        $sql = "SELECT COUNT(*) FROM " . tablename($this->table_name) . " WHERE   {$con}";
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
}
?>