<?php
 class Record{
    public function create($entity){
        global $_W;
        $rec = coll_elements(array('openid', 'wish'), $entity);
        $rec['uniacid'] = $_W['uniacid'];
        $rec['views'] = 0;
        $rec['timecreated'] = TIMESTAMP;
        $ret = pdo_insert('mbwish_records', $rec);
        if(!empty($ret)){
            $acid = pdo_insertid();
            return $acid;
        }
        return error(-1, '操作失败, 请稍后重试');
    }
    public function flowIncrease($id){
        $sql = 'UPDATE ' . tablename('mbwish_records') . ' SET `views`=`views`+1 WHERE `id`=:id';
        $pars = array();
        $pars[':id'] = $id;
        $ret = pdo_query($sql, $pars);
        if($ret !== false){
            return true;
        }
        return error(-1, '操作失败, 请稍后重试');
    }
    public function getOne($id){
        $sql = 'SELECT * FROM ' . tablename('mbwish_records') . " WHERE `id`=:id";
        $pars = array();
        $pars[':id'] = $id;
        $entity = pdo_fetch($sql, $pars);
        if(!empty($entity)){
            if(is_serialized($entity['wish'])){
                $entity['wish'] = @unserialize($entity['wish']);
            }
            return $entity;
        }
        return array();
    }
    public function getAll($filters, $pindex = 1, $psize = 20, & $total = 0){
        global $_W;
        $condition = '`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if(!empty($filters['openid'])){
            $condition .= ' AND `openid`=:openid';
            $pars[':openid'] = $filters['openid'];
        }
        if(!empty($filters['views'])){
            $condition .= ' AND `views` LIKE :views';
            $pars[':views'] = $filters['views'];
        }
        $sql = 'SELECT * FROM ' . tablename('mbwish_records') . " WHERE {$condition} ORDER BY `id` DESC";
        if($pindex > 0){
            $sql = "SELECT COUNT(*) FROM " . tablename('mbwish_records') . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $pars);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM " . tablename('mbwish_records') . " WHERE {$condition} ORDER BY `id` DESC LIMIT {$start},{$psize}";
        }
        $ds = pdo_fetchall($sql, $pars);
        if(!empty($ds)){
            foreach($ds as & $row){
                if(is_serialized($row['wish'])){
                    $row['wish'] = @unserialize($row['wish']);
                }
            }
            return $ds;
        }
        return array();
    }
}
