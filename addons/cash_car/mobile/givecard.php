<?php
/**
 * 用户转赠洗车卡
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
$from_user = $_W['fans']['from_user'];
$title = '转赠洗车卡';

$id = $_GPC['id'];


if($setting['is_give']=='0'){
	message("当前系统不允许转赠洗车卡！");
}

if(empty($id)){
	message("该洗车卡不存在");
}else{
	$onecard = pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE id='{$id}' AND from_user='{$from_user}'");
	if(empty($onecard)){
		message("该洗车卡不存在或已被删除");
	}
	if($onecard['number']==0){
		message("该洗车卡剩余次数不足");
	}
	if(time() > $onecard['validity']){
		message("该洗车卡已过期");
	}
}

if(checksubmit('submit')){
	$mobile   = trim($_GPC['mobile'])?trim($_GPC['mobile']):message("手机号码不能为空");
	$give_num = trim($_GPC['give_num'])?trim($_GPC['give_num']):message("赠送次数不能为空");

	if(!is_numeric($give_num) || $give_num==0 || $give_num > $onecard['number']){
		message("赠送次数有误");
	}

	//赠送人手机号码
	$giver = pdo_fetch("SELECT mobile FROM " . tablename('mc_members') . " WHERE uniacid='{$weid}' AND uid='{$_W['fans']['uid']}'");
	

	//接收会员信息
	$receiver = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uniacid='{$weid}' AND mobile='{$mobile}'");
	if(empty($receiver)){
		message("该手机号码用户不存在");
	}
	//接收粉丝信息
	$receiver_fans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE uid='{$receiver[uid]}'");
	//会员洗车卡信息
	$tmp = pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid='{$receiver[uid]}' AND onlycard='{$onecard[onlycard]}'");
	if(empty($tmp)){
		$insert = array(
			'weid'      => $weid,
			'uid'       => $receiver['uid'],
			'weid'      => $onecard['weid'],
			'from_user' => $receiver_fans['openid'],
			'title'     => $onecard['title'],
			'price'     => $onecard['price'],
			'number'    => $give_num,
			'onlycard'  => $onecard['onlycard'],
			'validity'  => $onecard['validity'],
		);
		$result1 = pdo_insert($this->table_member_onecard, $insert);
	}else{
		$updata = array('number'=>$tmp['number']+$give_num, 'price'=>($tmp['price']+$onecard['price'])*0.5);
		$where  = array(
			'uid'      => $receiver['uid'],
			'onlycard' => $onecard['onlycard'],
		);
		$result1 = pdo_update($this->table_member_onecard,$updata,$where);
	}

	if($result1){
		$result2 = pdo_update($this->table_member_onecard,array('number'=>$onecard['number']-$give_num),array('id'=>$onecard['id']));

		//转赠日志
		$give_log = array(
			'weid'               => $weid,
			'giver_id'           => $onecard['uid'],
			'give_mobile'        => $giver['mobile'],
			'giver_from_user'    => $from_user,
			'receiver_id'        => $receiver['uid'],
			'receiver_mobile'    => $mobile,
			'receiver_from_user' => $receiver_fans['openid'],
			'title'              => $onecard['title'],
			'number'             => $give_num,
			'add_time'           => time(),
		);
		pdo_insert($this->table_member_onecard_log, $give_log);

		//转赠人洗车卡明细
		$give_record = array(
			'weid'      => $weid,
			'uid'       => $onecard['uid'],
			'openid'    => $from_user,
			'title'     => $onecard['title'],
			'reduce'    => '-'.$give_num,
			'total'     => $onecard['number']-$give_num,
			'remark'    => "给用户[".$receiver['mobile']."]转赠洗车卡",
			'add_time'  => time(),
		);
		pdo_insert($this->table_onecard_record, $give_record);

		//接收人洗车卡明细
		$receiver_record = array(
			'weid'      => $weid,
			'uid'       => $receiver['uid'],
			'openid'    => $receiver_fans['openid'],
			'title'     => $onecard['title'],
			'reduce'    => $give_num,
			'total'     => $tmp['number']+$give_num,
			'remark'    => "用户[".$giver['mobile']."]转赠洗车卡",
			'add_time'  => time(),
		);
		pdo_insert($this->table_onecard_record, $receiver_record);
	}
	if($result2){
		message("转赠成功", $this->createMobileUrl('onecardlist', array('op'=>'mycard')), "success");
	}else{
		message("转赠失败");
	}

}

include $this->template('givecard');