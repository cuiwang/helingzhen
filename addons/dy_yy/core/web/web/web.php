<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

/***********侧栏*************/
$frames = array();
$frames['store']['title'] = '<i class=""></i>&nbsp;&nbsp; 产品管理';
$frames['store']['items'] = array();
$frames['store']['items']['add']['url'] = $this->createWebUrl('web/goods',array('op'=>"add"));
$frames['store']['items']['add']['title'] = '增加商品';
$frames['store']['items']['add']['actions'] = array('op','add');
$frames['store']['items']['add']['active'] = '';
$frames['store']['items']['index']['url'] = $this->createWebUrl('web/goods',array('op'=>"index"));
$frames['store']['items']['index']['title'] = '商品列表';
$frames['store']['items']['index']['actions'] = array('op','index');
$frames['store']['items']['index']['active'] = '';
$list = pdo_get('dy_index', array('uniacid' => $_W['uniacid']));


if (checksubmit()) {
	$huandeng="";
	foreach ($_GPC['huandeng'] as $k => $v) {
		$huandeng.=$v.'#';
	}
	$data = array(
			'title'=>$_GPC['title'],
			'top'=>$_GPC['top'],
			'width'=>$_GPC['width'],
			'color'=>$_GPC['color'],
			'titlecolor'=>$_GPC['titlecolor'],
			'copyright'=>$_GPC['copyright'],
			'huandeng'=>$huandeng,
			'url'=>$_GPC['url'],
			'banner'=>$_GPC['banner'],
			'tuwen'=>$_GPC['tuwen'],
			'uniacid'=>$_W['uniacid']
		);
	if (empty($list)) {
		// 插入
		$a = pdo_insert('dy_index',$data);
	}else{
		//更新
		$a =pdo_update('dy_index',$data,array('uniacid'=>$_W['uniacid']));
	}
	if ($a) {
		message("操作成功",$this->createWebUrl('web/web'));
	}


}
$list['huandeng']=explode("#", $list['huandeng']);
unset($list['huandeng'][count($list['huandeng'])-1]);

include $this->template('web/web/index');

/**********侧兰******************/
