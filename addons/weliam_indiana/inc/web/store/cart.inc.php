<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020 http://www.startingline.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Describe: 购物车参数设置
// +----------------------------------------------------------------------
// | Author: startingline<800083075@qq.com>
// +----------------------------------------------------------------------
global $_W,$_GPC;
$op = !empty($_GPC['op'])?$_GPC['op']:'ill';
if($op == 'ill') exit('参数错误');

if($op == 'display'){
	$re = pdo_fetch("select * from".tablename('weliam_indiana_cartsetting')."where uniacid=:uniacid and type=:type",array(':uniacid'=>$_W['uniacid'],':type'=>1));
	$re['allnum'] = unserialize($re['allnum']);
	include $this->template("cart");
}

if($op == 'change'){
	$data['num'] = $_GPC['buynum'];
	$data['is_show'] = $_GPC['is_show'];
	$data['type'] = 1;
	$data['allnum'] = serialize($_GPC['allnum']);
	if(pdo_fetch("select id from".tablename("weliam_indiana_cartsetting")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']))){
		pdo_update('weliam_indiana_cartsetting',$data,array('uniacid'=>$_W['uniacid']));
	}else{
		$data['uniacid'] = $_W['uniacid'];
		pdo_insert('weliam_indiana_cartsetting',$data);
	}
	message("修改成功",referer(),'success');
}
?>