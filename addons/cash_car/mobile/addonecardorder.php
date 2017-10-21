<?php
/**
 * 保存洗车卡订单
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$uid = $_GPC['uid'];
$this->_fromuser = $from_user;

if (empty($from_user)) {
	message('会话已过期，请重新发送关键字!');
}

$onlycardid  = $_GPC['onlycardid'];
if(empty($onlycardid)){
	$this->showMessageAjax("请选择要购买的洗车卡");
}

//查询洗车卡信息
$onlycar_info = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard) . " WHERE id='{$onlycardid}'");
if(empty($onlycar_info)){
	$this->showMessageAjax("该洗车卡不存在，请重新选择");
}
$order_sn = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

//检查用户是否还持有当前的洗车卡
$cards = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND onlycard=:onlycard AND number>0", array(':uid'=>$uid, 'weid'=>$weid, ':onlycard'=>$onlycar_info['onlycard']));
if($cards>0){
	$this->showMessageAjax("您还持有该洗车卡，无需购买");
}
if($onlycar_info['status']==0){
	$this->showMessageAjax("该洗车卡不存在或已下架");
}

$data = array(
	'weid'      => $weid,
	'uid'       => $uid,
	'from_user' => $from_user,
	'order_sn'  => $order_sn, //订单号,
	'title'     => $onlycar_info['title'],
	'onlycard'  => $onlycar_info['onlycard'],
	'number'    => $onlycar_info['number'],
	'amount'    => $onlycar_info['amount'],
	'validity'  => $onlycar_info['validity'],
	'status'    => 0,  //0.未支付
	'paytype'   => 0,  //支付方式 1余额，2微信支付，3支付宝
	'add_time'  => time(), //订单生成时间
);
pdo_insert($this->table_onecard_order, $data);
$orderid = pdo_insertid();

$result['orderid'] = $orderid;
$result['code'] = 'success';
$result['msg'] = '提交订单成功';
message($result, '', 'ajax');