<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Tyzm_User{
	public function __construct() {
		global $_W;
	} 
	function Get_checkoauth(){
		global $_GPC,$_W;		
		load()->model('mc');
	    if($_W['account']['level']<3 && empty($_SESSION['oauth_openid']) ){//非认证，非借权
			//message('非认证服务号，请至“功能选项”-“借用oAuth权限”-“选择公众号”，借用其他认证服务号权限。', '', 'error');
		}
		if(!empty($_SESSION['oauth_openid'])){//借权
		
		    $userinfo=@json_decode(base64_decode($_COOKIE["cuserinfo"]),true);
			if(empty($userinfo['oauth_openid'])){
				$userinfo = mc_oauth_userinfo();
				isetcookie("cuserinfo",base64_encode(json_encode($userinfo)),time()+3600*24*7);
			}
			if(!empty($userinfo['unionid']) && $_W['account']['level']>=3){
				$rid=intval($_GPC['rid']);
				$modulelist = uni_modules(false);
				$isopenweixin=$modulelist['tyzm_diamondvote']['config']['isopenweixin'];
				if($isopenweixin){
					$unioninfo = pdo_fetch("SELECT follow,openid FROM " . tablename('mc_mapping_fans') . " WHERE unionid = :unionid AND uniacid=:uniacid", array(':unionid' => $userinfo['unionid'],':uniacid' => $_W['uniacid']));
				}
			}
			if(empty($unioninfo)){
				$oauth_openid = $_SESSION['oauth_openid'];
				$nickname = $userinfo['nickname'];
				$avatar = $userinfo['avatar'];
				$unionid= $userinfo['unionid']; 
				$openid = empty($_SESSION['openid'])?$_SESSION['oauth_openid']:$_SESSION['openid'];
				$follow = $_W['fans']['follow'];				
			}else{
				$oauth_openid = $_SESSION['oauth_openid'];
				$nickname = $userinfo['nickname'];
				$avatar = $userinfo['avatar'];
				$unionid= $userinfo['unionid']; 
				$openid = $unioninfo['openid'];
				$follow = $unioninfo['follow'];
			}
		}else{//非借权
			if($_W['account']['level']==2){
				$oauth_openid = $_W['fans']['openid'];
				$nickname = $_W['fans']['tag']['nickname'];
				$avatar = $_W['fans']['tag']['avatar'];
				$unionid= $_W['fans']['unionid']; 
				$openid = $_W['fans']['openid'];
				$follow = $_W['fans']['follow'];
			}else{
				$member = mc_fetch(intval($_SESSION['uid']), array('avatar','nickname'));//无openid 无follow 有avatar 有nickname
				$oauth_openid = $_W['fans']['openid'];		
				if(empty($member['nickname'])){
					$nickname = "微信用户";
				}else{
					$nickname = $member['nickname'];
				}
				if(empty($member['avatar'])){
					$avatar = $_W['siteroot']."/addons/tyzm_diamondvote/template/static/images/defaultuser.jpg";
				}else{
					$avatar = $member['avatar'];
				}
				$unionid= $_W['fans']['unionid']; 
				$openid = $_W['fans']['openid'];
				$follow = $_W['fans']['follow'];
			}
		}
		//过滤emoji
		$nickname = json_encode($nickname);
		$nickname = @preg_replace("#(\\\u[ed][0-9a-f]{3})#ie","",$nickname); //处理方式2，将emoji的unicode留下，其他不动 
		$nickname = json_decode($nickname);
		//过滤emoji
		$userinfo=array(
			'oauth_openid'=>$oauth_openid,
			'nickname'=>$nickname,
			'avatar'=>$avatar,
			'unionid'=>$unionid,
			'openid'=>$openid,
			'follow'=>$follow,
		);	
		return $userinfo;	
	}
	function sendkfinfo($openid,$content){//发送信息
		global $_GPC,$_W;
		$send['touser'] = trim($openid); 
		$send['msgtype'] = 'text'; 
		$send['text'] = array('content' => urlencode($content)); 
		$acc = WeAccount::create($_W['uniacid']); 
		$data = $acc->sendCustomNotice($send);
    }

}