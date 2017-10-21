<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Invite {
	//判断是否互相邀请
	public function getifinviteboth($beinvited_openid = '',$invite_openid='',$uniacid=0) {
			global $_W;
			$invite = pdo_fetch("select * from".tablename('weliam_indiana_invite')."where beinvited_openid=:beinvited_openid and invite_openid=:invite_openid and uniacid=:uniacid and type = 0",array(':beinvited_openid'=>$beinvited_openid,':invite_openid'=>$invite_openid,':uniacid'=>$uniacid));
			if(empty($invite)){
				return TRUE;
			}
			return FALSE;
	} 
	public function getInvitesByOpenid($openid = '',$uniacid=0) {
			global $_W;
			$invites = pdo_fetchall("select * from".tablename('weliam_indiana_invite')."where beinvited_openid=:beinvited_openid and uniacid=:uniacid and type = 0",array(':beinvited_openid'=>$openid,':uniacid'=>$uniacid));
			return $invites;
	} 
	public function get2InvitesByOpenid($openid = '',$uniacid=0) {
			global $_W;
			$invites = pdo_fetchall("select * from".tablename('weliam_indiana_invite')."where invite_openid=:invite_openid and uniacid=:uniacid",array(':invite_openid'=>$openid,':uniacid'=>$uniacid));
			return $invites;
	}
	public function getInvitesBy2Openid($beinvited_openid = '',$invite_openid='',$uniacid=0) {
			global $_W;
			$invite = pdo_fetch("select * from".tablename('weliam_indiana_invite')."where beinvited_openid=:beinvited_openid and invite_openid=:invite_openid and uniacid=:uniacid and type = 0",array(':beinvited_openid'=>$beinvited_openid,':invite_openid'=>$invite_openid,':uniacid'=>$uniacid));
			return $invite;
	}
	public function updateBy2Openid($beinvited_openid = '',$invite_openid='',$uniacid=0,$credit1=0) {
			global $_W;
			$invite = pdo_fetch("select * from".tablename('weliam_indiana_invite')."where beinvited_openid=:beinvited_openid and invite_openid=:invite_openid and uniacid=:uniacid and type = 0",array(':beinvited_openid'=>$beinvited_openid,':invite_openid'=>$invite_openid,':uniacid'=>$uniacid));
			pdo_update("weliam_indiana_invite",array('credit1'=>$invite['credit1']+$credit1),array('id'=>$invite['id'],'type'=>'0'));
	}
	public function getInvitesFollowed($openid = '',$uniacid=0) {
			//判断是否已经被邀请
			global $_W;
			$invites = pdo_fetch("select * from".tablename('weliam_indiana_invite')."where beinvited_openid=:beinvited_openid and uniacid=:uniacid and type = 1",array(':beinvited_openid'=>$openid,':uniacid'=>$uniacid));
			return $invites;
	}
	public function getFollowedBoth($beinvited_openid='',$invite_openid='',$uniacid=0) {
			//判断相互邀请
			global $_W;
			$invites = pdo_fetch("select * from".tablename('weliam_indiana_invite')."where beinvited_openid='{$invite_openid}' and invite_openid='{$beinvited_openid}' and uniacid='{$uniacid}'");
			return $invites;
	}
	public function createInvitesFollowed($beinvited_openid = '',$invite_openid='',$uniacid=0,$creditnum=0){
			//被邀请人关注，邀请人获得积分
			global $_W;
			$data = array(
				'uniacid' => $uniacid,
				'beinvited_openid' => $beinvited_openid,
				'invite_openid' => $invite_openid,
				'credit1' => $creditnum,
				'createtime'=>time(),
				'type' => 1
			);
			$credit1=m('credit')->getCreditByOpenid($invite_openid,$_W['uniacid']);
			m('credit')->updateCredit1($invite_openid,$uniacid,$creditnum);
			pdo_insert("weliam_indiana_invite",$data);
			
	}
	public function addFollowedDB($openid='',$dbnum=0){
			//用户关注送夺宝币
			global $_W;
			$isfollowed = pdo_fetch("select * from".tablename('weliam_indiana_rechargerecord')."where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' and paytype = '-1'");
			if(empty($isfollowed) && $dbnum>0 ){
				$data = array(
					'uniacid' => $_W['uniacid'],
					'openid' => $openid,
					'num' => $dbnum,
					'createtime' => time(),
					'status' => 1,
					'paytype' => '-1',
					'type' => 1
				);
				pdo_insert("weliam_indiana_rechargerecord",$data);			
				m('credit')->updateCredit2($openid,$_W['uniacid'],$dbnum);
			}
	}
} 
