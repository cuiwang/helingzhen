<?php
/**
 * 钻石投票-会员中心
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$userinfo=$this->oauthuser;
$packid=intval($_GPC['packid']);
$rid=intval($_GPC['rid']);
$ty=$_GPC['ty'];


$reply = pdo_fetch("SELECT config FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
$friendship = pdo_fetch("SELECT * FROM " . tablename($this->tablefriendship) . " WHERE uniacid = :uniacid ORDER BY `id` DESC", array(':uniacid' => $uniacid));
$packata=@unserialize($friendship['packata']);	

$vipdata=$packata[$packid];

if($ty['ispost']){
    $tid='8888'.date('YmdHi').random(8, 1);//会员支付
	$params = array(
		'tid' => $tid,
		'ordersn' => $tid,
		'title' => '开通会员',
		'fee' => sprintf("%.2f",$vipdata['packprice']),
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
	$viporder = array(
	    'rid'=>$rid,
		'ptid'=>$tid ,
		'uniacid'=>$_W['uniacid'] ,
		'oauth_openid'=> $this->oauthuser['oauth_openid'],
		'avatar'=>$this->oauthuser['avatar'],
		'nickname'=> $this->oauthuser['nickname'],
		'user_ip'=> $_W['clientip'],
		'packname'=> $vipdata['packname'],
		'packicon'=> $vipdata['packicon'],
		'packtime'=> $vipdata['packtime']*86400+TIMESTAMP,
		'packnum'=> $vipdata['packnum'],
		'fee'=> $params['fee'],
		'ispay'=> 0,
		'status'=>0 ,
		'createtime'=>TIMESTAMP
	);
	if(pdo_insert($this->tableviporder, $viporder)){
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


$vipuser = pdo_fetch("SELECT packname,packtime FROM " . tablename($this->tableviporder) . " WHERE oauth_openid = :oauth_openid  AND ispay=1  AND packtime>".TIMESTAMP." ORDER BY `packnum` DESC", array(':oauth_openid' => $this->oauthuser['oauth_openid']));

$packnum=pdo_fetchcolumn("SELECT sum(packnum) FROM ".tablename($this->tableviporder)." WHERE oauth_openid = :oauth_openid AND ispay=1 AND packtime>".TIMESTAMP." ORDER BY `packnum` DESC", array(':oauth_openid' => $this->oauthuser['oauth_openid']));

$looknumt=pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($this->tablelooklist)." WHERE oauth_openid = :oauth_openid ", array(':oauth_openid' => $this->oauthuser['oauth_openid']));
$packnum=$packnum-$looknumt;
$packnum=$packnum<1?0:$packnum;

$_W['page']['sitename']="会员中心";
include $this->template('user');
