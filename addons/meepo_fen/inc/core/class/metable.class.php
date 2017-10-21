<?php
function metable($table){
    static $metables;
    if(empty($metables["{$table}"])) {
		$metables["{$table}"] = new MeTable($table);
	}
	return $metables["{$table}"];
}
class MeTable extends MeModuleSite {
    public $table;
    public function __construct($table){
        $this->table = $table;
    }
    public function get($filters){
        //获取单个
        $sql = "SELECT * FROM ".tablename($this->table)." WHERE 1 ";
        $params = array();
        foreach ($filters as $key=>$filter) {
            $sql .= " AND {$key}=:{$key}";
            $params[":{$key}"] = $filter;
        }
        $sql .= " limit 1";
        return pdo_fetch($sql,$params);
    }
    public function gets($filters){
        //获取所有
        $sql = "SELECT * FROM ".tablename($this->table)." WHERE 1 ";
        $params = array();
        foreach ($filters as $key=>$filter) {
            $sql .= " AND {$key}=:{$key}";
            $params[":{$key}"] = $filter;
        }
        return pdo_fetchall($sql,$params);
    }
    public function create($data){
        //生成单个
        if(pdo_insert($this->table,$data)){
            return pdo_insertid();
        }else{
            return false;
        }
    }
    public function creates($data){
        //批量生成
        $ids = array();
        foreach ($datas as $data) {
            $ids[] = $this->create($data);
        }
        return $ids;
    }
    public function delete($filter){
        // 删除
        return pdo_delete($this->table,$filter);
    }
    public function update($data,$filter){
        //更新
        return pdo_update($this->table,$data,$filter);
    }
}
