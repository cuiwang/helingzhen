<?php
	global $_GPC,$_W;
	$share_data = $this->module['config'];
	$openid = m('user') -> getOpenid();
	if($_GPC['op']=='invite'){
		//被分享
		$invite_openid = $_GPC['invite_openid'];
		//是否记录已存在
		$inviterecord = m('invite')->getInvitesBy2Openid($openid,$invite_openid,$_W['uniacid']);
		$if = m('invite')->getifinviteboth($invite_openid,$openid,$_W['uniacid']);
		$only = pdo_fetch("select id from".tablename('weliam_indiana_invite')."where beinvited_openid='{$openid}'" );
		if(empty($inviterecord) && ($invite_openid!=$openid) && $if && empty($only) && !empty($openid)){
			//不存在
				//生成邀请记录
				$data=array(
					'uniacid'=>$_W['uniacid'],
					'beinvited_openid'=>$openid,
					'invite_openid'=>$invite_openid,
					'createtime'=>TIMESTAMP,
				);
				pdo_insert('weliam_indiana_invite',$data);
		}
		header("location:".$this->createMobileUrl('index'));
	}else{
		$total=0;
		$uniacid=$_W['uniacid'];
		$invite = m('invite')->get2InvitesByOpenid($openid,$_W['uniacid']);
		foreach($invite as$key=>$value){
			$member = m('member')->getInfoByOpenid($value['beinvited_openid']);
			$invite[$key]['avatar'] = $member['avatar'];
			$invite[$key]['nickname'] = $member['nickname'];
			if($value['type']==0){
				$total++;
			}
		}
		$buy_total = pdo_fetchcolumn("select credit1 from".tablename('weliam_indiana_invite')."where uniacid=:uniacid and invite_openid=:invite_openid and type=:type",array(':uniacid'=>$_W['uniacid'],':invite_openid'=>$openid,':type'=>2));
		$credit1=m('credit')->getCreditByOpenid($openid,$_W['uniacid']);
		
		include $this->template('invite');
	}
	
?>