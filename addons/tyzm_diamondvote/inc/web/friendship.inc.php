<?php
/**
 * 钻石投票模块-后台管理-编辑
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');

load()->func('file');
load()->func('tpl');
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$friendship = pdo_fetch("SELECT * FROM " . tablename($this->tablefriendship) . " WHERE uniacid = :uniacid ORDER BY `id` DESC", array(':uniacid' => $uniacid));
$id=$_GPC['friendship_id'];
$packata=@unserialize($friendship['packata']);	
if ($_W['ispost']) {
	$config=@iserializer(array('area'=>1)); 
	
	for ($k = 0; $k < count($_POST['packname']); $k++){
			$packata[$k] = array(
				"packicon" => $_POST['packicon'][$k],
				"packname" => $_POST['packname'][$k],
				"packprice" => $_POST['packprice'][$k],
				"packnum" => $_POST['packnum'][$k],
				"packtime" => $_POST['packtime'][$k],
			);
		}
		
	$packata=@iserializer($packata);
	
	$insert = array(
			'uniacid' => $uniacid,
			'title' => $_GPC['title'],
			'eventrule' => htmlspecialchars_decode($_GPC['eventrule']),
			'config'=>$config,
			'packata' =>$packata,
			'status' => $_GPC['status'],
			'createtime' => time(),	
	);
	if (empty($id)){
			pdo_insert($this->tablefriendship, $insert);
		}else{
			unset($insert['createtime']);
			pdo_update($this->tablefriendship, $insert, array('id' => $id));
	}
	message('活动设置成功！', $this->createWebUrl('friendship', array('name' => 'tyzm_diamondvote')), 'success');
}



include $this->template('friendshipedit');