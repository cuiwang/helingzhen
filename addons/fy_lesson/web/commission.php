<?php
/**
 * 分销佣金管理
 */

$pindex = max(1, intval($_GPC['page']));
$psize = 10;
if($op=='display'){
	$status = intval($_GPC['status']);

	$timetype    = trim($_GPC['timetype']);
	$lesson_type = intval($_GPC['lesson_type']);
	$cash_way    = intval($_GPC['cash_way']);
	$cashid      = intval($_GPC['cashid']);
	$nickname    = trim($_GPC['nickname']);

	$condition = " a.uniacid='{$uniacid}' AND a.status='{$status}' ";

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime   = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime   = strtotime($_GPC['time']['end']);
	}
	if($timetype=='addtime'){
		$condition .= " AND a.addtime>'{$starttime}' AND a.addtime<'{$endtime}' ";
	}
	if($timetype=='disposetime'){
		$condition .= " AND a.disposetime>'{$starttime}' AND a.disposetime<'{$endtime}' ";
	}
	if(!empty($lesson_type)){
		$condition .= " AND a.lesson_type='{$lesson_type}' ";
	}
	if(!empty($cash_way)){
		$condition .= " AND a.cash_way='{$cash_way}' ";
	}
	if(!empty($cashid)){
		$condition .= " AND a.id='{$cashid}' ";
	}
	if(!empty($nickname)){
		$condition .= " AND (b.nickname LIKE '%{$nickname}%' OR b.realname LIKE '%{$nickname}%' OR b.mobile LIKE '%{$nickname}%') ";
	}

	$list = pdo_fetchall("SELECT a.*,b.mobile,b.nickname,b.avatar FROM " . tablename($this->table_cashlog) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_cashlog) . "  a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

	/* 导出excel表格 */
	if($_GPC['export']==1){
		if($status==0){
			$filename = "待打款提现申请";
		}elseif($status==1){
			$filename = "已打款提现申请";
		}elseif($status==-1){
			$filename = "无效提现申请";
		}
		
		$i = 0;
		foreach ($list as $key => $value) {
			$arr[$i]['id']              = $value['id'];
			$arr[$i]['nickname']        = $value['nickname'];
			$arr[$i]['mobile']          = $value['mobile'];
			if($value['cash_way']==1){
				$arr[$i]['cash_way'] = '帐户余额';
			}elseif($value['cash_way']==2){
				$arr[$i]['cash_way'] = '微信钱包';
			}elseif($value['cash_way']==3){
				$arr[$i]['cash_way'] = '支付宝';
			}
			$arr[$i]['pay_account']     = $value['pay_account'];
			$arr[$i]['lesson_type']		= $value['lesson_type']==1?'分销佣金提现':'课程收入提现';
			$arr[$i]['cash_num']	    = $value['cash_num'];
			$arr[$i]['addtime']		    = date('Y-m-d H:i:s', $value['addtime']);
			$arr[$i]['cash_type']		= $value['cash_type']==1?'管理员审核':'自动到账';
			$arr[$i]['disposetime']		= date('Y-m-d H:i:s', $value['disposetime']);
			if($value['status']==0){
				$arr[$i]['status'] = "待打款";
			}elseif($value['status']==1){
				$arr[$i]['status'] = "已打款";
			}elseif($value['status']==-1){
				$arr[$i]['status'] = "无效佣金";
			}
			$arr[$i]['partner_trade_no'] = $value['partner_trade_no'];
			$arr[$i]['payment_no']       = $value['payment_no'];
			$arr[$i]['remark']           = $value['remark'];
			$i++;
		}

		$this->exportexcel($arr, array('提现单号', '粉丝昵称', '手机号码','提现方式','提现帐号', '提现类型', '申请佣金', '申请时间', '处理方式', '处理时间','状态','商户订单号','微信订单号','管理员备注'), $filename);
		exit();
	}

}elseif($op=='detail'){
	$id = intval($_GPC['id']);
	$cashlog = pdo_fetch("SELECT a.*,b.mobile,b.nickname,b.realname,b.avatar FROM " .tablename($this->table_cashlog). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.id='{$id}'");
	if(empty($cashlog)){
		message("该条提现申请不存在或已被删除！", "", "error");
	}

	if(checksubmit('submit')){
		if($cashlog['status']!=0){
			message("该条提现申请已处理！", "", "error");
		}

		$status = intval($_GPC['status']); /* 状态 0.待打款 1.已打款 -1.无效申请 */
		$remark = trim($_GPC['remark']); /* 管理员备注信息 */

		$upcashlog = array();
		$upcashlog['remark'] = $remark;
		if($status == 1){
			if($cashlog['cash_way']==2){ //提现到微信钱包
				$post = array('total_amount'=>$cashlog['cash_num'], 'desc'=>'用户申请微课堂佣金提现');
				$fans = array('openid'=>$cashlog['openid'], 'nickname'=>$cashlog['nickname']);
				$result = $this->companyPay($post,$fans);

				if($result['result_code']=='SUCCESS'){
					$upcashlog['status']           = 1;
					$upcashlog['disposetime']      = strtotime($result['payment_time']);
					$upcashlog['partner_trade_no'] = $result['partner_trade_no'];
					$upcashlog['payment_no']	   = $result['payment_no'];

					$res = pdo_update($this->table_cashlog, $upcashlog, array('id'=>$cashlog['id']));
					if($res){
						$this->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->待打款提现申请", "[处理成功]提现单号:{$id}的提现申请");
					}
					message("提现处理成功，佣金已发放到用户微信钱包！", $this->createWebUrl('commission', array('status'=>0)), "success");

				}elseif($result['result_code']=='FAIL'){
					$this->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->待打款提现申请", "[处理失败]提现单号:{$id}的提现申请，原因:".$result['return_msg']);
					message($result['return_msg']."，微信接口返回信息：".$result['err_code_des'], "", "error");
				}
			}elseif($cashlog['cash_way']==3){ //提现到支付宝
				if(empty($remark)){
					message("请输入管理员备注", "", "warning");
				}
				$upcashlog['status']           = 1;
				$upcashlog['disposetime']      = time();
				pdo_update($this->table_cashlog, $upcashlog, array('id'=>$cashlog['id']));

				message("提现处理成功", $this->createWebUrl('commission', array('status'=>0)), "success");
			}
			
		}elseif($status=='-1'){
			if(empty($remark)){
				message("请输入管理员备注", "", "warning");
			}

			$upcashlog['status']	  = -1;
			$upcashlog['disposetime'] = time();

			$res = pdo_update($this->table_cashlog, $upcashlog, array('id'=>$cashlog['id']));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->待打款提现申请", "[处理成功]设置提现单号:{$id}的提现申请为无效状态");
			}

			message("操作成功，该条提现申请已设置为无效！", $this->createWebUrl('commission', array('status'=>0)), "success");
		}
	}
}elseif($op=='level'){
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' ORDER BY id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}'");
	$pager = pagination($total, $pindex, $psize);

}elseif($op=='editlevel'){
	$id = intval($_GPC['id']);
	if($id>0){
		$level = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' AND id='{$id}'");
		if(empty($level)){
			message("该分销商等级不存在或已被删除", "", "error");
		}
	}

	if(checksubmit('submit')){
		$data = array(
			'uniacid'	  => $uniacid,
			'levelname'   => trim($_GPC['levelname']),
			'commission1' => floatval($_GPC['commission1']),
			'commission2' => floatval($_GPC['commission2']),
			'commission3' => floatval($_GPC['commission3']),
			'updatemoney' => floatval($_GPC['updatemoney']),
		);
		if(empty($data['levelname'])){
			message("请输入等级名称");
		}
		if(empty($data['commission1'])){
			message("请输入一级分销比例");
		}

		if(empty($id)){
			pdo_insert($this->table_commission_level, $data);
			$id = pdo_insertid();
			if($id){
				$this->addSysLog($_W['uid'], $_W['username'], 1, "分销管理->分销商等级", "新增ID:{$id}的分销商等级");
			}
		}else{
			$res = pdo_update($this->table_commission_level, $data, array('uniacid'=>$uniacid, 'id'=>$id));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->分销商等级", "编辑ID:{$id}的分销商等级");
			}
		}

		message("操作成功", $this->createWebUrl("commission", array('op'=>'level')), "success");
	}

}elseif($op=='dellevel'){
	$id = intval($_GPC['id']);
	$level = pdo_fetch("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' AND id='{$id}'");
	if(empty($level)){
		message("该分销商等级不存在或已被删除", "", "error");
	}

	$res = pdo_delete($this->table_commission_level, array('uniacid'=>$uniacid, 'id'=>$id));
	if($res){
		if($res){
			$this->addSysLog($_W['uid'], $_W['username'], 2, "分销管理->分销商等级", "删除ID:{$res}的分销商等级");
		}
	}

	message("删除成功", $this->createWebUrl("commission", array('op'=>'level')), "success");
	
}elseif($op=='commissionlog'){
	$nickname = trim($_GPC['nickname']);
	$bookname = trim($_GPC['bookname']);
	$grade	  = intval($_GPC['grade']);
	$remark	  = trim($_GPC['remark']);

	$condition = " uniacid='{$uniacid}' ";
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime   = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime   = strtotime($_GPC['time']['end'])+86399;
	}
	$condition .= " AND addtime>'{$starttime}' AND addtime<'{$endtime}' AND change_num>0 ";

	if(!empty($nickname)){
		$condition .= " AND nickname LIKE '%{$nickname}%' ";
	}

	if(!empty($bookname)){
		$condition .= " AND bookname LIKE '%{$bookname}%' ";
	}
	if(!empty($grade)){
		$condition .= " AND grade = '{$grade}' ";
	}
	if(!empty($remark)){
		$condition .= " AND remark LIKE '%{$remark}%' ";
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_commission_log) . " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_commission_log) . " WHERE {$condition} ");
	$pager = pagination($total, $pindex, $psize);

	/* 导出excel表格 */
	if($_GPC['export']==1){
		$lists = pdo_fetchall("SELECT * FROM " . tablename($this->table_commission_log) . " WHERE {$condition} ORDER BY id DESC");
		$i = 0;
		foreach ($lists as $key => $value) {
			$arr[$i]['id']			   = $value['id'];
			$arr[$i]['nickname']       = $value['nickname'];
			$arr[$i]['openid']         = $value['openid'];
			$arr[$i]['bookname']       = $value['bookname'];
			if($value['grade'] == '1'){
				$arr[$i]['grade'] = "一级分销";
			}elseif($value['grade'] == '2'){
				$arr[$i]['grade'] = "二级分销";
			}elseif($value['grade'] == '3'){
				$arr[$i]['grade'] = "三级分销";
			}
			$arr[$i]['change_num']      = $value['change_num']."元";
			$arr[$i]['remark']		    = $value['remark'];
			$arr[$i]['addtime']         = date('Y-m-d H:i:s',$value['addtime']);
			$i++;
		}
	 
		$this->exportexcel($arr, array('ID', '会员信息', '会员openid', '分销课程', '分销等级', '分销佣金', '备注', '分销时间'), "分销佣金明细-".date('Y-m-d',time()));
		exit();
	}

}elseif($op=='statis'){
	$nickname = trim($_GPC['nickname']);
	$realname = trim($_GPC['realname']);
	$mobile	  = trim($_GPC['mobile']);
	$ranking  = intval($_GPC['ranking']);

	$condition = " a.uniacid='{$uniacid}' ";

	if(!empty($nickname)){
		$condition .= " AND b.nickname LIKE '%{$nickname}%' ";
	}
	if(!empty($realname)){
		$condition .= " AND b.realname LIKE '%{$realname}%' ";
	}
	if(!empty($mobile)){
		$condition .= " AND b.mobile LIKE '%{$mobile}%' ";
	}
	if(empty($ranking) || $ranking==1){
		$ORDER = " ORDER BY total_commission DESC ";
	}elseif($ranking==2){
		$ORDER = " ORDER BY pay_commission DESC ";
	}elseif($ranking==3){
		$ORDER = " ORDER BY nopay_commission DESC ";
	}

	$list = pdo_fetchall("SELECT a.nopay_commission,a.pay_commission,a.nopay_commission+a.pay_commission AS total_commission,b.uid,b.nickname,b.realname,b.mobile FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} {$ORDER},uid ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} ");
	$pager = pagination($total, $pindex, $psize);

	/* 导出excel表格 */
	if($_GPC['export']==1){
		$lists = pdo_fetchall("SELECT a.nopay_commission,a.pay_commission,a.nopay_commission+a.pay_commission AS total_commission,b.uid,b.nickname,b.realname,b.mobile FROM " . tablename($this->table_member) . " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} {$ORDER},uid ASC ");
		$i = 0;
		foreach ($lists as $key => $value) {
			$arr[$i]['uid']			     = $value['uid'];
			$arr[$i]['nickname']         = $value['nickname'];
			$arr[$i]['realname']         = $value['realname'];
			$arr[$i]['mobile']		     = $value['mobile'];
			$arr[$i]['pay_commission']   = $value['pay_commission']."元";
			$arr[$i]['nopay_commission'] = $value['nopay_commission']."元";
			$arr[$i]['total_commission'] = $value['total_commission']."元";
			$i++;
		}
	 
		$this->exportexcel($arr, array('会员ID', '会员昵称', '会员姓名', '手机号码', '已申请佣金', '待申请佣金', '累计佣金'), "分销佣金统计-".date('Y-m-d',time()));
		exit();
	}

}


include $this->template('commission');


?>