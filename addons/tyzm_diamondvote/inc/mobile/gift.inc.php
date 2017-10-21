<?php
/**
 * 送礼投票-投票
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;

is_weixin();

$rid=intval($_GPC['rid']);
$id=intval($_GPC['id']);
$ty=$_GPC['ty'];
$oauth_openid=$this->oauthuser['oauth_openid'];

if(empty($oauth_openid)){
	message("无法获取OPNEID，请查看是否借权或配置好公众号！(0101)"); 
}
$reply = pdo_fetch("SELECT rid,title,sharetitle,shareimg,sharedesc,config,giftdata,endtime,apstarttime,apendtime,votestarttime,voteendtime,status  FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
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
$giftdata=@unserialize($reply['giftdata']);	



$voteuser = pdo_fetch("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid AND  id = :id ", array(':rid' => $rid,':id' => $id));

if($ty['ispost']){
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
	$gift=$giftdata[$_GPC['giftid']];
	
	
	
	//最多送礼物
	$diamondsy=$reply['everyonediamond']-$voteuser['giftcount'];
	if($gift['giftprice'] > $diamondsy && !empty($reply['everyonediamond'])){
		if($diamondsy>0){
			exit(json_encode(array('status' => '0', 'msg' => "最多还能送".$diamondsy."元礼物，修改后再送！:-D")));
		}else{
			exit(json_encode(array('status' => '0', 'msg' => "最多能送".$reply['everyonediamond']."元礼物，给其他人点机会吧！:-D")));
		} 
	}	
	$tid=date('YmdHi').random(12, 1);
	$params = array(
		'tid' => $tid,
		'ordersn' => $tid,
		'title' => '投票送礼付款',
		'fee' => sprintf("%.2f",$gift['giftprice']),
		'user' => $_W['member']['uid'],
		'module' => $this->module['name'],
	);
	
	
	
	$acid=!empty($_SESSION['oauth_acid'])?$_SESSION['oauth_acid']:$_SESSION['acid'];
	if(!empty($_SESSION['oauth_acid'])){
		$acid=$_SESSION['oauth_acid'];
		$account_wechats = pdo_fetch("SELECT uniacid FROM " . tablename('account_wechats') . " WHERE  acid = :acid ", array(':acid' => $acid));
	    $uniacid=$account_wechats['uniacid'];
	}else{
		$acid=$_SESSION['acid'];
		$uniacid=$_W['uniacid'];
	}
	

	if(empty($reply['defaultpay'])){
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
		$ps['user'] = $_W['fans']['oauth_openid'];
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
		
	}
	$giftdata = array(
			'rid'=>$rid, 
			'tid'=>$id,
			'uniacid'=>$_W['uniacid'],
			'oauth_openid'=>$this->oauthuser['oauth_openid'],
			'openid'=>$this->oauthuser['openid'],
			'avatar' =>$this->oauthuser['avatar'],
			'nickname'=>$this->oauthuser['nickname'],
			'user_ip'=>$_W['clientip'],
			'gifticon'=>$gift['gifticon'],
			'gifttitle'=>$gift['gifttitle'],
			'giftvote'=>$gift['giftvote'],
			'fee'=>$params['fee'],
			'ptid'=>$tid,
			'ispay'=>0,
			'status'=>0,
			'createtime'=>time()
	);
	if(pdo_insert($this->tablegift, $giftdata)){
		if(empty($reply['defaultpay'])){
			$out['status'] = 200;
			$out['pay_url'] = $_W['siteroot']."payment/wechat/pay.php?i={$uniacid}&auth={$auth}&ps={$sl}";
			exit(json_encode($out));
		}else{
			$this->pay($params);
		}
	}else{
		exit(json_encode(array('status' => '0', 'msg' => "操作失败，请刷新后再试！")));
	}
	exit;
}
$lsun=0;
foreach ($giftdata as $key => $value) {
	$xiuyu=$key%3;
	if(empty($xiuyu)){
		$i++;
	}
	$giftlist[$i][$key]=$value; 
	$lsun=$key;
}
$pvtotal=pdo_fetch("SELECT pv_total FROM ".tablename($this->tablecount)." WHERE tid = :tid AND rid = :rid ", array(':tid' => $id,':rid' => $rid));
if(empty($pvtotal)){
	$pvtotal['pv_total']=0;
}
$pvtotal['pv_total']=$pvtotal['pv_total']+$voteuser['vheat'];
$reply['giftunit']=$reply['giftunit']?$reply['giftunit']:"点";
$_share['title'] =!empty($reply['sharetitle'])?$reply['sharetitle']:$reply['title'];
$_share['imgUrl'] =!empty($reply['shareimg'])?tomedia($reply['shareimg']):tomedia($reply['thumb']);
$_share['desc'] =!empty($reply['sharedesc'])?$reply['sharedesc']:$reply['description'];
$_W['page']['sitename']=$reply['title'];


include $this->template('payvote');






