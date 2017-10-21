<?php
/**
 * 洗车卡管理
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
 
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	if (checksubmit('submit')) { //排序
		if (is_array($_GPC['soft'])) {
			foreach ($_GPC['soft'] as $key => $val) {
				$data = array('soft' => intval($_GPC['soft'][$key]));
				pdo_update($this->table_onecard, $data, array('id' => $key));
			}
		}
		message('操作成功!',$this->createWebUrl('onecard'),'success');
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$title = trim($_GPC['title']);
	$condition = "uniacid='{$weid}'";

	if(!empty($title)){
		$condition .= " AND title LIKE '%{$title}%'";
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_onecard) . " WHERE {$condition} ORDER BY soft DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_onecard) . " WHERE {$condition}");

	$pager = pagination($total, $pindex, $psize);

}elseif ($operation == 'post') {
	$id = $_GPC['id'];
	if(!empty($id)){
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard) . " WHERE id={$id}");
	}
	if(checksubmit('submit')){
		$title     = trim($_GPC['title'])?trim($_GPC['title']):message('请输入洗车卡名称');
		$number    = trim($_GPC['number'])?trim($_GPC['number']):message('请输入洗车卡数量');
		$amount    = intval($_GPC['amount'])?intval($_GPC['amount']):message('请输入洗车卡总额');
		$onlycard  = trim($_GPC['onlycard'])?trim($_GPC['onlycard']):message('请输入洗车卡标识');
		$onlycard_name  = trim($_GPC['onlycard_name'])?trim($_GPC['onlycard_name']):message('请输入洗车卡标识名称');
		$soft      = trim($_GPC['soft'])?trim($_GPC['soft']):'0';
		$validity = trim($_GPC['validity'])?trim($_GPC['validity']):message('请选择有效期');

		if(!is_numeric($number) || !is_numeric($amount)){
			message('洗车卡套餐数量或总额必需为数字');
		}

		$tmp = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard). " WHERE onlycard='{$onlycard}'");
		if(!empty($tmp) && $tmp['onlycard'] != $onlycard){
			message('该洗车卡标识已被占用');
		}

		$data = array(
			'uniacid'        => $weid,
			'title'          => $title,
			'content'        => trim($_GPC['content']),
			'number'         => $number,
			'amount'         => $amount,
			'soft'           => $soft,
			'onlycard'       => $onlycard,
			'onlycard_name'  => $onlycard_name,
			'validity'       => $validity,
			'status'         => intval($_GPC['status']),
			'add_time'       => time(),
		);
		
		if($id){
			unset($data['onlycard']);
			unset($data['add_time']);
			pdo_update($this->table_onecard,$data,array('id'=>$id));
			message('更新成功',$this->createWebUrl('onecard'),'success');
		}else{
			$is_exit = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard) . " WHERE onlycard='{$onlycard}' LIMIT 1");
			pdo_insert($this->table_onecard,$data);
			message('添加成功',$this->createWebUrl('onecard'),'success');
		}
	}
}elseif ($operation == 'order') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " weid='{$weid}'";
	if(in_array($_GPC['status'], array('0','1'))){
		$condition .= " AND status='{$_GPC[status]}' ";
	}
	if(!empty($_GPC['mobile'])){
		$user = pdo_fetch("SELECT * FROM " .tablename('mc_members'). " WHERE mobile='{$_GPC[mobile]}'");
		$fans = pdo_fetch("SELECT * FROM " .tablename('mc_mapping_fans'). " WHERE uid='{$user[uid]}'");
		$condition .= " AND from_user LIKE '%{$fans['openid']}%' ";
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_onecard_order) . " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	
	foreach($list as $key=>$value){
		$list[$key]['user'] = pdo_fetch("SELECT * FROM " .tablename('mc_members'). " WHERE uid='{$value[uid]}'");
	}
	
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_onecard_order) . " WHERE {$condition}");

	$pager = pagination($total, $pindex, $psize);

}elseif ($operation == 'membercard') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;

	$condition = " weid='{$weid}' ";

	if(!empty($_GPC['mobile'])){
		$user = pdo_fetch("SELECT * FROM " .tablename('mc_members'). " WHERE mobile='{$_GPC[mobile]}'");
		$condition .= " AND uid LIKE '%{$user['uid']}%' ";
	}
	if($_GPC['status']==1){
		$condition .= " AND number>0 ";
	}
	if($_GPC['status']==2){
		$condition .= " AND number=0 ";
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	
	foreach($list as $key=>$value){
		$list[$key]['user'] = pdo_fetch("SELECT * FROM " .tablename('mc_members'). " WHERE uid='{$value[uid]}'");
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_member_onecard) . " WHERE {$condition}");

	$pager = pagination($total, $pindex, $psize);

}elseif ($operation == 'addmembercard') {
	$onecard_list = pdo_fetchall("SELECT * FROM " . tablename($this->table_onecard) . " WHERE uniacid={$weid}");
	if(checksubmit('submit')){
		$mobile      = trim($_GPC['mobile'])?trim($_GPC['mobile']):message('请输入会员手机号码！');
		$onecard_id  = trim($_GPC['onecard_id'])?trim($_GPC['onecard_id']):message('请选择洗车卡！');
		$number      = trim($_GPC['number']);
		//$is_add      = trim($_GPC['is_add'])?trim($_GPC['is_add']):message('请选择是否叠加洗车卡！');

		if(!empty($number) && !is_numeric($number)){
			message('可洗车次数必须为数字！');
		}

		//判断会员是否存在
		$member = pdo_fetch("SELECT uid FROM " . tablename('mc_members') . " WHERE uniacid='{$weid}' AND mobile ='{$mobile}'");
		if(empty($member)){
			message('该手机号码未绑定！');
		}
		$fans = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') . " WHERE uniacid='{$weid}' AND uid ='{$member['uid']}'");

		//查询洗车卡信息
		$onecard = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard). " WHERE id='{$onecard_id}'");
		if(empty($onecard)){
			message('该洗车卡不存在，请重新选择！');
		}

		//查询会员是否拥有该洗车卡
		$member_onecard = pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard). " WHERE uid='{$member['uid']}' AND onlycard='{$onecard['onlycard']}'");

		$number = $number?$number:$onecard['number'];
		$addmonecard = array(
			'uid'       => $member['uid'],
			'weid'      => $weid,
			'from_user' => $fans['openid'],
			'title'     => $onecard['title'],
			'price'     => round($onecard['amount']/$number,2),
			'number'    => $number,
			'onlycard'  => $onecard['onlycard'],
			'validity'  => time()+$onecard['validity']*86400,
		);
		if(!empty($member_onecard)){//会员拥有该洗车卡
			if($member_onecard['number']>0){
				message("会员还拥有该洗车卡，无需增加！");
			}else{
				$success = pdo_update($this->table_member_onecard, $addmonecard, array('id'=>$member_onecard['id']));

				$addmonecardlog = array(
					'weid'               => $weid,
					'giver_id'           => 0,
					'give_mobile'        => '后台管理员手动增加',
					'giver_from_user'    => 0,
					'receiver_id'        => $member['uid'],
					'receiver_mobile'    => $mobile,
					'receiver_from_user' => $fans['openid'],
					'title'              => $onecard['title'],
					'number'             => $number?$number:$onecard['number'],
					'add_time'           => time(),
				);
				pdo_insert($this->table_member_onecard_log, $addmonecardlog);
			}
			
		}else{//会员未拥有该洗车卡
			$success = pdo_insert($this->table_member_onecard, $addmonecard);
			if($success){
				$addmonecardlog = array(
					'weid'               => $weid,
					'giver_id'           => 0,
					'give_mobile'        => '后台管理员手动增加',
					'giver_from_user'    => 0,
					'receiver_id'        => $member['uid'],
					'receiver_mobile'    => $mobile,
					'receiver_from_user' => $fans['openid'],
					'title'              => $onecard['title'],
					'number'             => $number?$number:$onecard['number'],
					'add_time'           => time(),
				);
				pdo_insert($this->table_member_onecard_log, $addmonecardlog);
			}
		}

		//添加会员洗车卡明细
		$onecard_record = array(
			'weid'      => $weid,
			'uid'       => $member['uid'],
			'openid'    => $fans['openid'],
			'title'     => $onecard['title'],
			'reduce'    => $number?$number:$onecard['number'],
			'total'     => $number?$number:$onecard['number'],
			'remark'    => "后台管理员增加洗车卡",
			'add_time'  => time(),
		);
		pdo_insert($this->table_onecard_record, $onecard_record);
		message("新增会员洗车卡成功！", $this->createWebUrl("onecard", array('op'=>'addmembercard')), "success");
	}
}elseif ($operation == 'give') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;

	$condition = " weid='{$weid}'";
	if(!empty($_GPC['give'])){
		$condition .= " AND give_mobile='{$_GPC['give']}'";
	}
	if(!empty($_GPC['receiver'])){
		$condition .= " AND receiver_mobile='{$_GPC['receiver']}'";
	}

	//更新赠送人手机号码
	$null_mobile = pdo_fetchall("SELECT * FROM " . tablename($this->table_member_onecard_log) . " WHERE weid='{$weid}' AND give_mobile = ''");
	foreach($null_mobile as $null){
		$member = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uniacid='{$weid}' AND uid = '{$null['giver_id']}' AND mobile !=''");
		pdo_update($this->table_member_onecard_log, array('give_mobile'=>$member['mobile']), array('id'=>$null['id']));
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_member_onecard_log) . " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = 0;
	foreach($list as $value){
		$numbers += $value['number'];
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_member_onecard_log) . " WHERE {$condition}");

	$pager = pagination($total, $pindex, $psize);

}elseif($operation == 'onecardRecord'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;

	$condition = " a.weid='{$weid}'";
	if(!empty($_GPC['nickname'])){
		$condition .= " AND b.nickname LIKE '%{$_GPC['nickname']}%'";
	}
	if(!empty($_GPC['mobile'])){
		$condition .= " AND b.mobile LIKE '%{$_GPC['mobile']}%'";
	}
	if(!empty($_GPC['title'])){
		$condition .= " AND a.title LIKE '%{$_GPC['title']}%'";
	}
	
	$list = pdo_fetchall("SELECT a.*,b.nickname,b.mobile FROM " . tablename($this->table_onecard_record) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_onecard_record) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

}elseif ($operation == 'delete') {
	$id = $_GPC['id'];
	if(empty($id)){
		message("参数错误");
	}
	$item = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard) . " WHERE id={$id}");
	if(empty($item)){
		message("该套餐不存在");
	}
	pdo_delete($this->table_onecard, array('id'=>$id));
	message('删除成功',$this->createWebUrl('onecard'),'success');

}

include $this->template('onecard');