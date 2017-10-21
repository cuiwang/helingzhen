<?php 
	global $_W,$_GPC;
	$operation = isset($_GPC['op'])?$_GPC['op']:'user';
	
	//活动信息
	$asql = "SELECT * FROM " . tablename('zofui_hby_activity') . " WHERE `uniacid` = :uniacid ORDER BY `id` DESC";
	$actinfo = pdo_fetch($asql , array(
		':uniacid' => $_W['uniacid']			
	));
	
	//参与用户
	if($operation == 'user'){

	//分页数据
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$usql = "SELECT COUNT(*) FROM " . tablename('zofui_hby_user') . " WHERE `uniacid` = :uniacid AND `actid` = :actid";	
	$total = pdo_fetchcolumn($usql,array(
		':uniacid' => $_W['uniacid'],
		':actid' => $actinfo['id']	
	));
	$userinfo = pdo_fetchall("select * from" . tablename('zofui_hby_user') . "where `uniacid` ='{$_W['uniacid']}' AND `actid` = '{$actinfo['id']}' ORDER BY `id` DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	//分页函数
	$pager = pagination($total, $pindex, $psize);
	}
	
	//红包记录
	if($operation == 'hblog'){

	//分页数据
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$usql = "SELECT COUNT(*) FROM " . tablename('zofui_hby_hblog') . " WHERE `uniacid` = :uniacid AND `actid` = :actid";	
	$total = pdo_fetchcolumn($usql,array(
		':uniacid' => $_W['uniacid'],
		':actid' => $actinfo['id']
	));
	$hbinfo = pdo_fetchall("select * from" . tablename('zofui_hby_hblog') . "where `uniacid` ='{$_W['uniacid']}' AND `actid` = '{$actinfo['id']}' ORDER BY `id` DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	
	foreach((array)$hbinfo as $k=>$v){
		$sql = "SELECT * FROM" . tablename('zofui_hby_user') . "WHERE `uniacid` = :uniacid AND `actid` = :actid AND `openid` = :openid ORDER BY `id` DESC LIMIT 1";
		$uinfo = pdo_fetch($sql,array(
			':uniacid' => $_W['uniacid'],
			':actid' => $actinfo['id'],			
			':openid' => $v['openid']	
		));	
		
		$hbinfo[$k]['headimgurl'] = $uinfo['headimgurl'];
		$hbinfo[$k]['nickname'] = $uinfo['nickname'];
	}
	
	//分页函数
	$pager = pagination($total, $pindex, $psize);
	}
	
	//历史红包记录
	if($operation == 'oldact'){
		
		$oldactinfo = pdo_fetchall("select * from" . tablename('zofui_hby_activity') . "where `uniacid` ='{$_W['uniacid']}' ");
		
		$asql = "SELECT * FROM " . tablename('zofui_hby_activity') . " WHERE `uniacid` = :uniacid AND `id` = :actid ORDER BY `id` DESC";
		$thisactinfo = pdo_fetch($asql , array(
			':uniacid' => $_W['uniacid'],
			':actid' => $_GPC['actid']		
		));		
		
		//分页数据
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$usql = "SELECT COUNT(*) FROM " . tablename('zofui_hby_hblog') . " WHERE `uniacid` = :uniacid AND `actid` = :actid";	
		$total = pdo_fetchcolumn($usql,array(
			':uniacid' => $_W['uniacid'],
			':actid' => $_GPC['actid']
		));
		$hbinfo = pdo_fetchall("select * from" . tablename('zofui_hby_hblog') . "where `uniacid` ='{$_W['uniacid']}' AND `actid` = '{$_GPC['actid']}' ORDER BY `id` DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		foreach((array)$hbinfo as $k=>$v){
			$sql = "SELECT * FROM" . tablename('zofui_hby_user') . "WHERE `uniacid` = :uniacid AND `actid` = :actid AND `openid` = :openid ORDER BY `id` DESC LIMIT 1";
			$uinfo = pdo_fetch($sql,array(
				':uniacid' => $_W['uniacid'],
				':actid' => $_GPC['actid'],			
				':openid' => $v['openid']	
			));	
			
			$hbinfo[$k]['headimgurl'] = $uinfo['headimgurl'];
			$hbinfo[$k]['nickname'] = $uinfo['nickname'];
		}
		
		//分页函数
		$pager = pagination($total, $pindex, $psize);
	}
	
	//增加点击次数
	if($_GPC['op'] == 'addtimes'){
		if($_GPC['times'] <=0){
			die(0);
		}
		
		$userinfo = pdo_fetch("select * from" . tablename('zofui_hby_user') . "where `uniacid` ='{$_W['uniacid']}' AND `id` = '{$_GPC['id']}'");
		$res = pdo_update('zofui_hby_user',array('times'=>($userinfo['times']+intval($_GPC['times']))),array('id'=>$_GPC['id']));	
		if($res){
			echo $userinfo['times']+intval($_GPC['times']);die;
		}else{
			die(0);
		}	
	}
	
	//用户搜索
	if($operation == 'search'){
		//分页数据
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$total = pdo_fetchcolumn(" SELECT COUNT(*) FROM " . tablename('zofui_hby_user') . " WHERE `uniacid` ='{$_W['uniacid']}'  AND `actid` = '{$actinfo['id']}' AND `id` = '{$_GPC['for']}' OR `nickname` LIKE '%{$_GPC['for']}%'");
		//分页数据结束
		$userinfo = pdo_fetchall(" SELECT * FROM " . tablename('zofui_hby_user') . " WHERE `uniacid` ='{$_W['uniacid']}' AND `actid` = '{$actinfo['id']}' AND `id` = '{$_GPC['for']}' OR `nickname` LIKE '%{$_GPC['for']}%' ORDER BY id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		//分页函数
		$pager = pagination($total, $pindex, $psize);
		
	}	
	
	//红包记录搜索
	if($operation == 'hbsearch'){
		//分页数据
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$total = pdo_fetchcolumn(" SELECT COUNT(*) FROM " . tablename('zofui_hby_hblog') . " WHERE `uniacid` ='{$_W['uniacid']}'  AND `actid` = '{$actinfo['id']}' AND `id` = '{$_GPC['for']}'");
		//分页数据结束
		$hbinfo = pdo_fetchall(" SELECT * FROM " . tablename('zofui_hby_hblog') . " WHERE `uniacid` ='{$_W['uniacid']}' AND `id` = '{$_GPC['for']}' ORDER BY id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		
		foreach((array)$hbinfo as $k=>$v){
			$sql = "SELECT * FROM" . tablename('zofui_hby_user') . "WHERE `uniacid` = :uniacid AND `actid` = :actid AND `openid` = :openid ORDER BY `id` DESC LIMIT 1";
			$uinfo = pdo_fetch($sql,array(
				':uniacid' => $_W['uniacid'],
				':actid' => $actinfo['id'],			
				':openid' => $v['openid']	
			));	
			
			$hbinfo[$k]['headimgurl'] = $uinfo['headimgurl'];
			$hbinfo[$k]['nickname'] = $uinfo['nickname'];
		}		
		
		//分页函数
		$pager = pagination($total, $pindex, $psize);
		
	}	
	
	include $this->template('web/act');