<?php
/**
 * 钻石投票-投票
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;

$uniacid=$this->oauthuniacid();
$rid=intval($_GPC['rid']);
$id=intval($_GPC['id']);
$ty=$_GPC['ty'];
$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));

if(empty($reply)){
	message("参数错误"); 
}

if($reply['starttime']>time()){
	message("活动还没有开始"); 
}
 
//活动未开始
if($reply['endtime']<time()){
	message("活动已经结束"); 
}

//活动未开始
if(empty($reply['status'])){
	message("活动已禁用"); 
}

//投票时间
if($reply['votestarttime']> time()){
	message("未开始投票！"); 
}elseif($reply['voteendtime']<time()){
	message("已结束投票！");
}

$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);


$voteuser = pdo_fetch("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid AND  id = :id ", array(':rid' => $rid,':id' => $id));


if($_W['ispost']){
	//是否达到最小人数
	if(!empty($reply['minnumpeople'])){
		$condition="";
		if($reply['ischecked']==1){
		  $condition.=" AND status=1 ";
		}
		$jointotal = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid  ".$condition , array(':rid' => $rid));
		if($reply['minnumpeople']>$jointotal){
			exit(json_encode(array('status' => '0', 'msg' => "活动还未开始，没有达到最小参赛人数！")));
		}
	}
	
	if($ty=='jd'){
		//最多扔鸡蛋
		$eggsy=$reply['everyoneegg']-$voteuser['eggnum'];
		if(intval($_GPC['ptiefen'])>$eggsy && !empty($reply['everyoneegg'])){
			if($eggsy>0){
				exit(json_encode(array('status' => '0', 'msg' => "最多还能扔".$eggsy."颗鸡蛋，修改后再扔送！:-D")));
			}else{
				exit(json_encode(array('status' => '0', 'msg' => "最多能扔".$reply['everyoneegg']."颗鸡蛋，放过TA吧！:-D")));
			} 
		}
		$ptiefen=intval($_GPC['ptiefen'])*$reply['eggvalue'];
	}else{
		//最多送钻石
		$diamondsy=$reply['everyonediamond']-$voteuser['diamondnum'];
		if(intval($_GPC['ptiefen'])>$diamondsy && !empty($reply['everyonediamond'])){
			if($diamondsy>0){
				exit(json_encode(array('status' => '0', 'msg' => "最多还能送".$diamondsy."颗钻石，修改后再送！:-D")));
			}else{
				exit(json_encode(array('status' => '0', 'msg' => "最多能送".$reply['everyonediamond']."颗钻石，给其他人点机会吧！:-D")));
			} 
		}
		
		$ptiefen=intval($_GPC['ptiefen'])*$reply['diamondvalue'];
	}
	
	
	
	

	
	
	
	
	
	
	if(!empty($reply['everyonediamond']) && ($voteuser['diamondnum']+$voteuser['eggnum'])>=$reply['everyonediamond']){
	    
    }
	//$ptiefen=($ptiefen>1)?$ptiefen:1;
	
	//测试
	//$ptiefen=0.01;
	$tid=date('YmdHi').random(8, 1);
	$params = array(
		'tid' => $tid,
		'ordersn' => $tid,
		'title' => '投票付款',
		'fee' => sprintf("%.2f",$ptiefen),
		'user' => $_W['member']['uid'],
		'module' => $this->module['name'],
	);
	
	
	
	$moduels = uni_modules();
	if(empty($params) || !array_key_exists($params['module'], $moduels)) {
		exit(json_encode(array('status' => '0', 'msg' => "访问错误.")));
	}
    $setting = uni_setting($uniacid, 'payment');
	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
	$pars  = array();
	$pars[':uniacid'] = $uniacid;
	$pars[':module'] = $params['module'];
	$pars[':tid'] = $params['tid'];
	$log = pdo_fetch($sql, $pars);
	if(!empty($log) && $log['status'] != '0') {
		$out['status'] = 201;
		$out['msg'] = "请勿重新提交！";
		exit(json_encode($out));
	}
	if(!empty($log) && $log['status'] == '0') {
		$log = null;
	}
	if(empty($log)){
		$moduleid = pdo_fetchcolumn("SELECT mid FROM ".tablename('modules')." WHERE name = :name", array(':name' => $params['module']));
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$fee = $params['fee'];
		$record = array();
		$record['uniacid'] = $uniacid;
		$record['openid'] = $_W['member']['uid'];
		$record['module'] = $params['module'];
		$record['type'] = 'wechat';
		$record['tid'] = $params['tid'];
		$record['uniontid'] = date('YmdHis').$moduleid.random(8,1);
		$record['fee'] = $fee;
		$record['status'] = '0';
		$record['is_usecard'] = 0;
		$record['card_id'] = 0;
		$record['card_fee'] = $fee;
		$record['encrypt_code'] = '';
		$record['acid'] = $_W['acid'];
		if(pdo_insert('core_paylog', $record)) {
			$plid = pdo_insertid();
			$record['plid'] = $plid;
			$log = $record;
		} else {
			exit(json_encode(array('status' => '0', 'msg' => "操作失败，请刷新后再试！")));
		}
	}
	$ps = array();
	$ps['tid'] = $log['plid'];
	$ps['uniontid'] = $log['uniontid'];
	$ps['user'] = $_W['fans']['from_user'];
	$ps['fee'] = $log['card_fee'];
	$ps['title'] = $params['title'];
	if(!empty($plid)){
		$tag = array();
		$tag['acid'] = $_W['acid'];
		$tag['uid'] = $_W['member']['uid'];
		pdo_update('core_paylog', array('openid' => $this->oauthuser['openid'], 'tag' => iserializer($tag)), array('plid' => $plid));
	}

	load()->model('payment');
	load()->func('communication');
	$sl = base64_encode(json_encode($ps));
	$auth = sha1($sl . $uniacid . $_W['config']['setting']['authkey']);
	if($ty=='jd'){
		$votetype=2;
	}else{
		$votetype=1;
	}
	$votedata = array(
			'rid'=>$rid, 
			'tid'=>$id,
			'uniacid'=>$_W['uniacid'],
			'from_user'=>$this->oauthuser['from_user'],
			'openid'=>$this->oauthuser['openid'],
			'avatar' =>$this->oauthuser['avatar'],
			'nickname'=>$this->oauthuser['nickname'],
			'user_ip'=>$_W['clientip'],
			'votetype'=>$votetype,
			'fee'=>$params['fee'],
			'paynum'=>intval($_GPC['ptiefen']),
			'ptid'=>$log['tid'],
			'plid'=>$log['plid'],
			'mch_billno'=>$setting['payment']['wechat']['mchid']. date("Ymd", time()) . date("His", time()) . rand(1111, 9999),
			'ispay'=>0,
			'status'=>0,
			'createtime'=>time()
	);
	
	if(pdo_insert($this->tablevotedata, $votedata)){
		$out['status'] = 200;
		if($_W['account']['level']<3){
			$out['pay_url'] =$_W['siteroot']."app/index.php?c=entry&do=pay&m=tyzm_diamondvote&i={$uniacid}&auth={$auth}&ps={$sl}&ty=wechat";
		}else{
			$out['pay_url'] = $_W['siteroot']."payment/wechat/pay.php?i={$uniacid}&auth={$auth}&ps={$sl}";
		}
		exit(json_encode($out));
	}else{
		exit(json_encode(array('status' => '0', 'msg' => "操作失败，请刷新后再试！")));
	}
	exit;
}


$_share['title'] =!empty($reply['sharetitle'])?$reply['sharetitle']:$reply['title'];
$_share['imgUrl'] =!empty($reply['shareimg'])?tomedia($reply['shareimg']):tomedia($reply['thumb']);
$_share['desc'] =!empty($reply['sharedesc'])?$reply['sharedesc']:$reply['description'];
$_W['page']['sitename']=$reply['title'];

if($ty=='jd'){
	include $this->template('payjdvote');
}else{
	include $this->template('payvote');
}






