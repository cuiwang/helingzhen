<?php
class core {
	public $data;
	public $tablename;
	
	function __construct(){
		include CORE_PATH.'data/install.php';
	}
	
	function fetch($fetch){
		$sql = "SELECT * FROM ".tablename($this->tablename)." WHERE 1";
		$params = array();
		
		if(!empty($fetch)){
			foreach($fetch as $key=>$fe){
				$sql .= " AND {$key} = :".$key;
				$params[":".$key] = $fe;
			}
		}
		return pdo_fetch($sql,$params);
	}
	
	function fetchcount($fetch){
		$sql = "SELECT COUNT(*) FROM ".tablename($this->tablename)." WHERE 1";
		$params = array();
		
		if(!empty($fetch)){
			foreach($fetch as $key=>$fe){
				$sql .= " AND {$key} = :".$key;
				$params[":".$key] = $fe;
			}
		}
		return pdo_fetchcolumn($sql,$params);
	}
	
	function fetchsum($filed,$fetch){
		$sql = "SELECT SUM({$filed}}) FROM ".tablename($this->tablename)." WHERE 1";
		$params = array();
		
		if(!empty($fetch)){
			foreach($fetch as $key=>$fe){
				$sql .= " AND {$key} = :".$key;
				$params[":".$key] = $fe;
			}
		}
		return pdo_fetchcolumn($sql,$params);
	}
	
	function fetchpage($fetch){
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "SELECT * FROM ".tablename($this->tablename)." WHERE 1";
		$sum = "SELECT COUNT(*) FROM ".tablename($this->tablename)." WHERE 1";
		$params = array();
		if(!empty($fetch)){
			foreach($fetch as $key=>$fe){
				$sql .= " AND {$key} = :".$key;
				$sum .= " AND {$key} = :".$key;
				$params[":".$key] = $fe;
			}
		}
		$sql .= " LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$lists = pdo_fetchall($sql,$params);
		$total = pdo_fetchcolumn($sum, $params);
		$pager = pagination($total, $pindex, $psize);
		
		return array('list'=>$lists,'pager'=>$pager);
	}
	
	function help(){
		$sql = "SHOW COLUMNS FROM ".tablename($this->tablename);
		$list = pdo_fetchall($sql);
		$lists = "";
		foreach($list as $li){
			$lists[] = $li['Field'];
		}
		return $lists;
	}
	
	function fetchall($fetch = array()){
		if(empty($this->tablename)){
			return error('-1','未初始化');
		}
		$sql = "SELECT * FROM ".tablename($this->tablename)." WHERE 1";
		$params = array();
		if(!empty($fetch)){
			foreach($fetch as $key=>$fe){
				$sql .= " AND {$key} = :".$key;
				$params[":".$key] = $fe;
			}
		}
		return pdo_fetchall($sql,$params);
	}
	function clearall($fetch = array()){
		if(empty($this->tablename)){
			return error('-1','未初始化');
		}
		pdo_delete($this->tablename,$fetch);
		return true;
	}
	
	function insert($data){
		if(empty($data)){
			return error('-1','数据不能为空');
		}
		if(empty($this->tablename)){
			return error('-1','未初始化');
		}
		return pdo_insert($this->tablename,$data);
	}
	
	function setTable($tablename){
		$this->tablename = $tablename;
	}
	
	function setData($data){
		$this->data = $data;
	}
	
	function getData(){
		return !empty($this->data)?$this->data:array();
	}
}