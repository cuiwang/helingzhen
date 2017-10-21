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
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->jb_table) . " WHERE weid = :weid AND rid = :rid ORDER BY displayid ASC",array(':weid'=>$weid,':rid'=>$rid));
}elseif ($op == 'post') {
	$jb_id = intval($_GPC['jb_id']);
	if(!empty($jb_id)){
		$jb = pdo_fetch("SELECT * FROM ".tablename($this->jb_table)." WHERE rid=:rid AND id=:id",array(':rid'=>$rid,':id'=>$jb_id));
	}
	if (checksubmit('submit')) {
		$data = array(
			'displayid'=>intval($_GPC['displayid']),
			'weid' => $weid,
			'rid'=>$rid,
			'name' =>$_GPC['name'],//奖品名称
			'tx' =>$_GPC['tx'],
			'des' =>$_GPC['des'],
		);
		
		if (!empty($jb_id)) {
			pdo_update($this->jb_table, $data, array('id' => $jb_id));
			message('保存成功！', $this->createWebUrl('jb_manage', array('id'=>$id)), 'success');
		} else {
			$data['createtime'] = time();
			pdo_insert($this->jb_table,$data);
			message('新增嘉宾成功！', $this->createWebUrl('jb_manage', array('id'=>$id,'op' => 'post')), 'success');
		}
	}
}elseif($op=='del'){
	$jb_id = intval($_GPC['jb_id']);
	pdo_delete($this->jb_table,array('id'=>$jb_id));
	message('删除嘉宾成功！', $this->createWebUrl('jb_manage', array('id'=>$id)), 'success');
}else {
	message('请求方式不存在');
}

include $this->template('jb_manage');