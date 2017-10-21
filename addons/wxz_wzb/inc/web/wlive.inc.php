<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$id = $_GPC['id'];
$cid = $_GPC['cid'];

load()->func('tpl');
$categorys = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_category')." WHERE uniacid=:uniacid",array(':uniacid'=>$uniacid));
if(empty($categorys)){
	message('请先添加分类',$this->createWebUrl('category_list'),'error');
}
$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_wlive_setting')." WHERE uniacid=:uniacid AND id=:id",array(':uniacid'=>$uniacid,':id'=>$id));

if (checksubmit('submit')) {
	$data = array();
	$data['uniacid'] = $uniacid;
	$data['img'] = $_GPC['img'];
	$data['title'] = $_GPC['title'];
	$data['total_num'] = $_GPC['num'];
	$data['real_num'] = $_GPC['num'];
	$data['islinkurl'] = 1;
	$data['linkurl'] = $_GPC['linkurl'];
	$data['isshow'] = intval($_GPC['isshow']);
	$data['cid'] = intval($_GPC['cid']);
	$data['type'] = 2;

	if($data['cid']=='0'){
		message('请选择直播分类');
	}

	if($data['linkurl']==''){
		message('请填写直播间链接');
	}

	$data['sort'] = intval($_GPC['sort']);

	if(!empty($id)){
		pdo_update('wxz_wzb_wlive_setting',$data,array('id'=>$id,'uniacid'=>$uniacid));
		message('编辑成功',referer(),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_wlive_setting',$data);
		message('新增成功',$this->createWebUrl('category_list'),'success');
	}

}
include $this->template('wlive');
?>