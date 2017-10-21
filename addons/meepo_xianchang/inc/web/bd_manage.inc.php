<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$bd_manage = pdo_fetch("SELECT * FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($bd_manage)){
	$bd_manage['show'] = 1;
}else{
	$bd_manage['xm'] = iunserializer($bd_manage['xm']);
}
if(checksubmit('submit')){
	$data = array();
	$data['show'] = intval($_GPC['show']);
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$temp = array();
	if(!empty($_GPC['bd_displayid']) && is_array($_GPC['bd_displayid'])){
		foreach($_GPC['bd_displayid'] as $key=>$row){
			if(empty($_GPC['zd_show'][$key])){
				$_GPC['zd_show'][$key] = 2;
			}
			$temp[$row] = array('displayid'=>$row,'bd_name'=>$_GPC['bd_name'][$key],'zd_name'=>$_GPC['zd_name'][$key],'zd_show'=>$_GPC['zd_show'][$key]); 
		}
		ksort($temp);
	}
	$data['xm'] = iserializer($temp);
	$bd_manage_id = intval($_GPC['bd_manage_id']);
	if(empty($bd_manage_id)){
		pdo_insert($this->bd_manage_table,$data);
		message('保存成功',$this->createWebUrl('bd_manage',array('id'=>$id)),"success");
	}else{
		pdo_update($this->bd_manage_table,$data,array('id'=>$bd_manage_id,'weid'=>$weid));
		message('更新成功',$this->createWebUrl('bd_manage',array('id'=>$id)),"success");
	}
	
}
include $this->template('bd_manage');
 
      
