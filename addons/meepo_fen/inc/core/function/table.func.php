<?php
load()->func('db');
function insert_menu($module,$do,$title){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'menu');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_insert('modules_bindings',array('module'=>$module,'do'=>$do,'title'=>$title,'entry'=>'menu'));
	}
}

function insert_cover($module,$do,$title){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'cover');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_insert('modules_bindings',array('module'=>$module,'do'=>$do,'title'=>$title,'entry'=>'cover'));
	}
}

function delete_cover($module,$do){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'cover');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_delete('modules_bindings',array('module'=>$module,'do'=>$do,'entry'=>'cover'));
	}
}


function delete_menu($module,$do){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'menu');
	$is = pdo_fetch($sql,$params);
	if(!empty($is)){
		pdo_delete('modules_bindings',array('module'=>$module,'do'=>$do,'entry'=>'menu'));
	}
}

function table_schema($table){
	global $_W;
	$table = db_table_schema(pdo(),$table);
	$fields = $table['fields'];
	foreach ($fields as $fi){
		if($fi['increment']){}else{
			$data['name'] = $fi['name'];
			$lsit[] = $data;
		}
	}
	return $list;
}
