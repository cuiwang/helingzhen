<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */


global $_W, $_GPC;
$weid = $_W['uniacid'];
$id = $rid = intval($_GPC['id']);
load()->func('tpl');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
if($op == 'list') {
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->xc2_table) . " WHERE weid = :weid AND rid = :rid ORDER BY displayid ASC",array(':weid'=>$weid,':rid'=>$rid));
}elseif ($op == 'post') {
	$xc_id = intval($_GPC['xc_id']);
	if(!empty($xc_id)){
		$xc = pdo_fetch("SELECT * FROM ".tablename($this->xc2_table)." WHERE rid=:rid AND id=:id",array(':rid'=>$rid,':id'=>$xc_id));
	}
	if (checksubmit('submit')) {
		$data = array(
			'displayid'=>intval($_GPC['displayid']),
			'weid' => $weid,
			'rid'=>$rid,
			'img' =>$_GPC['img'],
		);
		
		if (!empty($xc_id)) {
			pdo_update($this->xc2_table, $data, array('id' => $xc_id));
			message('保存成功！', $this->createWebUrl('xc_manage', array('id'=>$id)), 'success');
		} else {
			
			pdo_insert($this->xc2_table,$data);
			message('新增相片成功！', $this->createWebUrl('xc_manage', array('id'=>$id,'op' => 'post')), 'success');
		}
	}
}elseif($op=='del'){
	$xc_id = intval($_GPC['xc_id']);
	pdo_delete($this->xc2_table,array('id'=>$xc_id));
	message('删除相片成功！', $this->createWebUrl('xc_manage', array('id'=>$id)), 'success');
}else {
	message('请求方式不存在');
}

include $this->template('xc_manage');