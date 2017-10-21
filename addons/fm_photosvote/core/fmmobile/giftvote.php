<?php
/**
 * 女神来了模块定义
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 * (c) Copyright 2016 FantasyMoons. All Rights Reserved.
 */
defined('IN_IA') or exit('Access Denied');

$op = empty($_GPC['op']) ? '' : $_GPC['op'] ;
$now = time();
if ($op == 'phb') {

	$users = pdo_fetchall("SELECT from_user, giftnum FROM " . tablename($this -> table_users) . ' WHERE rid = :rid AND status = 1 ', array(':rid' => $rid));
	foreach ($users as $row) {
		$total_gift = $this -> getgiftnum($rid, $row['from_user'], $uni);
		if (!empty($total_gift)) {
			pdo_update($this->table_users, array('giftnum'=>$total_gift), array('from_user'=>$row['from_user'], 'rid'=>$rid));
		}

	}
	$gift = pdo_fetchall("SELECT from_user, giftnum FROM " . tablename($this->table_users) . ' WHERE rid = :rid AND giftnum > 0 ORDER BY giftnum DESC LIMIT 0, 3', array(':rid' => $rid));
	foreach ($gift as $key => $value) {
		$pmid = $key+1;
		$gift[$key]['pmid'] = $pmid;
		$gift[$key]['mlz'] = '魅力值'  . $value['giftnum'];
		$gift[$key]['avatar'] = $this->getname($rid, $value['from_user'],'20', 'avatar');
		$gift[$key]['username'] = $this->getname($rid, $value['from_user'],'10');
	}
	$giftavatar = tomedia('./addons/fm_photosvote/icon.jpg');
	$title = '礼物排行榜 - ' . $rbasic['title'];
	$templatename = $rbasic['templates'];
	if ($templatename != 'default' && $templatename != 'stylebase') {
		require FM_CORE . 'fmmobile/tp.php';
	}
	$toye = $this -> templatec($templatename, $_GPC['do']);
	include $this -> template($toye);
}elseif ($op == 'phblist') {
	$myml = pdo_fetch("SELECT giftnum FROM " . tablename($this->table_users) . ' WHERE rid = :rid AND from_user = :from_user', array(':rid' => $rid,':from_user' => $from_user));
	if (empty($myml['giftnum'])) {
		$mymlnum = '0';
	}else{
		$mymlnum = $myml['giftnum'];
	}
	$data = pdo_fetchall("SELECT from_user, giftnum FROM " . tablename($this->table_users) . ' WHERE rid = :rid AND giftnum > 0 ORDER BY giftnum DESC LIMIT 1, 1000000', array(':rid' => $rid));

	foreach ($data as $key => $value) {
		$pmid = $key+2;
		$data[$key]['pmid'] = $pmid;
		$data[$key]['mlz'] = '魅力值 <span style="color:#f77090;">' . $value['giftnum'] . '</span>';
		$data[$key]['avatar'] = '<img class="ysimg pull-left" src="'.$this->getname($rid, $value['from_user'],'20', 'avatar').'" width="30" height="30" style="border-radius: 30px;display: inherit;    border-radius: 30px;margin-right: 10px;">';
		$data[$key]['username'] = $this->getname($rid, $value['from_user'],'10');

	}

	$title = '礼物排行榜 - ' . $rbasic['title'];
	$templatename = $rbasic['templates'];
	if ($templatename != 'default' && $templatename != 'stylebase') {
		require FM_CORE . 'fmmobile/tp.php';
	}
	$toye = $this -> templatec($templatename, $_GPC['do']);
	include $this -> template($toye);
}else{
	//查询自己是否参与活动
	if (empty($from_user)) {
		$data = array(
			'success' => -1,
			'msg' => '错误，未找到您的openid'
		);
		echo json_encode($data);
		exit ;
	}
	if ($_GPC['choosetype'] == 'gift') {
		$giftid = $_GPC['giftid'];
		$item = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ', array(':id' => $giftid));
		$rjifen = pdo_fetch("SELECT * FROM ".tablename($this->table_jifen)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		if (empty($item)) {
			$data = array(
				'success' => -1,
				'msg' => '没有找到您要送的礼物，请选择其他礼物'
			);
			echo json_encode($data);
			exit ;
		}
		$userjf = $this->cxjifen($rid, $from_user);
		$touser = $this->getname($rid, $tfrom_user);

		if ($item['piaoshu'] > 0) {
			$fuhao = '+';
		}
		if ($item['jifen'] > $userjf) {
			$usergift = pdo_fetch("SELECT * FROM " . tablename($this -> table_user_gift) . ' WHERE giftid = :giftid AND from_user = :from_user AND rid = :rid AND status = 1 ', array(':giftid' => $giftid,':from_user' => $from_user,':rid' => $rid));
			if (!empty($usergift) && $usergift['giftnum'] > 0) {
				$msg = "已有<span style='color:red;'> " .$usergift['giftnum'] ." </span>个，送给 " .$touser." ".$item['gifttitle'].",<span style='color:red;'> ".$fuhao.$item['piaoshu']." </span>票";
				$data = array(
					'success' => 1,
					'msg' => $msg
				);
				echo json_encode($data);
				exit ;
			}else{
				$ordersn = date('ymdhis') . random(4, 1);

				$price = $item['jifen'] / $rjifen['jifen_charge'];
				$datas = array(
					'uniacid' => $uniacid,
					'weid' => $uniacid,
					'rid' => $rid,
					'from_user' => $from_user,
					'tfrom_user' => $tfrom_user,
					'fromuser' => $fromuser,
					'mobile' => $this->getmobile($rid,$from_user),
					'ordersn' => $ordersn,
					'payyz' => '',
					'title' => '购买 ' . $item['gifttitle'] . ' 礼物',
					'giftid' => $giftid,
					'price' => $price,
					'jifen' =>$item['jifen'],
					'realname' => $nickname,
					'status' => '0',
					'paytype' => '6',
					'ispayvote' => '2',
					'remark' => '礼物购买订单，'.$nickname.'购买 ' . $item['gifttitle'] . ' 礼物， 消费 ' . $price . ' 元，',
					'ip' => getip(),
					'createtime' => time(),
				);
				$datas['iparr'] = !empty($_GPC['lbslocal']) ? $_GPC['lbslocal'] : getiparr($datas['ip']);
				pdo_insert($this->table_order, $datas);
				$log = pdo_get('core_paylog', array('uniacid' => $uniacid, 'module' => $this->module['name'], 'tid' => $ordersn));
				//在pay方法中，要检测是否已经生成了paylog订单记录，如果没有需要插入一条订单数据
				//未调用系统pay方法的，可以将此代码放至自己的pay方法中，进行漏洞修复
				if (empty($log)) {
			        $log = array(
		                'uniacid' => $uniacid,
		                'acid' => $_W['acid'],
		                'openid' => $from_user,
		                'module' => $this->module['name'], //模块名称，请保证$this可用
		                'tid' => $ordersn,
		                'fee' => $price,
		                'card_fee' => $price,
		                'status' => '0',
		                'is_usecard' => '0',
			        );
			        pdo_insert('core_paylog', $log);
				}

				$toparams = array();
				$toparams['tid'] = $ordersn;
				$toparams['rid'] = $rid;
				$toparams['user'] = $from_user;
				$toparams['fee'] = $price;
				$toparams['title'] = '购买 ' . $item['gifttitle'] . ' 礼物';
				$toparams['content'] = $toparams['title'] ;
				$toparams['ordersn'] = $ordersn;
				$toparams['module'] = $this->module['name'];
				$toparams['payyz'] = random(8);
				$toparams['virtual'] = false;
				$entoparams = base64_encode(json_encode($toparams));
				$msg = "支付<span style='color:red;'> " .$price ." </span>元，送给 " .$touser." ".$item['gifttitle'].",<span style='color:red;'> ".$fuhao.$item['piaoshu']." </span>票";
				$fmdata = array(
					"success" => -1,
					"flag" => 1,
					"votefee" => sprintf('%.2f', $price),
					"params" => $entoparams,
					"toparams" => $toparams,
					"msg" => $msg,
				);
				echo json_encode($fmdata);
				exit();
			}
		}else{
			$msg = "支付<span style='color:red;'> " .$item['jifen'] ." </span>积分，送给 " .$touser." ".$item['gifttitle'].",<span style='color:red;'> ".$fuhao.$item['piaoshu']." </span>票";
			$data = array(
				'success' => 1,
				'msg' => $msg
			);
			echo json_encode($data);
			exit ;
		}

	}else{

		if (!empty($from_user)) {
			if (!empty($tfrom_user)) {
				$tuser = pdo_fetch("SELECT * FROM " . tablename($this -> table_users) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user, ':rid' => $rid));
				$fmimage = $this->getpicarr($uniacid,$rid, $tfrom_user,1);
			}else{
				$fmdata = array(
					"success" => -1,
					"msg" => '没有找到该参赛者',
					"url" => $this->createMobileUrl($_GPC['vfrom'], array('rid'=> $rid, 'tfrom_user' =>$tfrom_user)),
				);
				echo json_encode($fmdata);
				exit;
			}

			if ($rvote['giftvote'] == 1) {
				if ($_W['account']['level'] == 4) {
					$u_uniacid = $uniacid;
				}else{
					$u_uniacid = $cfg['u_uniacid'];
				}
				$pays = pdo_fetch("SELECT payment FROM " . tablename('uni_settings') . " WHERE uniacid='{$u_uniacid}' limit 1");
				$pay = iunserializer($pays['payment']);

				if (!empty($_GPC['paymore'])) {
					$paymore = iunserializer(base64_decode(base64_decode($_GPC['paymore'])));
				}
				$giftid = $_GPC['giftid'];
				//print_r($giftid);
				$payordersn = pdo_fetch("SELECT id,payyz,ordersn FROM " . tablename($this -> table_order) . " WHERE rid='{$rid}' AND from_user = :from_user AND paytype = 6 AND giftid = :giftid ORDER BY id DESC,paytime DESC limit 1", array(':from_user' => $from_user, ':giftid' => $giftid));
				//print_r($payordersn);
				$voteordersn = pdo_fetch("SELECT id FROM " . tablename($this -> table_log) . " WHERE rid='{$rid}' AND from_user = :from_user AND ordersn = :ordersn ORDER BY id DESC limit 1", array(':from_user' => $from_user, ':ordersn' => $paymore['ordersn']));

			}

			$user = pdo_fetch("SELECT * FROM " . tablename($this -> table_users) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user, ':rid' => $rid));
			$voteer = pdo_fetch("SELECT nickname,realname,avatar FROM " . tablename($this -> table_voteer) . " WHERE rid = :rid AND from_user = :from_user", array(':rid' => $rid, ':from_user' => $from_user));
			$gift = $this->getgift($rid);
			$mygift = $this->getmygift($rid, $from_user);
			$xhtotal = ceil($gift['total']/6);


			$templatename = $rbasic['templates'];
			if ($templatename != 'default' && $templatename != 'stylebase') {
				require FM_CORE . 'fmmobile/tp.php';
			}
			$toye = $this -> templatec($templatename, $_GPC['do']);
			include $this -> template($toye);
		}
	}
}