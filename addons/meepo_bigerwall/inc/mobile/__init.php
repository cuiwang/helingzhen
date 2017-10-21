<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];

if(empty($rid)){
	message('活动rid错误！');
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
if(!empty($ridwall)){
	$zhufus = explode("#",$ridwall['qd_zhufus']);
}
if(empty($ridwall)){
	message('活动不存在或是已经被删除！');
}
if(TIMESTAMP<$ridwall['activity_starttime']){
			$msg='活动在'.date('Y-m-d H:i:s',$ridwall['activity_starttime']).'开始,到时再来哦';
			message($msg);
}
		

if(TIMESTAMP>$ridwall['activity_endtime']){
	$msg='活动在'.date('Y-m-d H:i:s',$ridwall['activity_endtime']).'结束啦!';
	message($msg);
}
if(empty($openid)){
	$url = $ridwall['gz_url'];
	header("location:$url");
	exit;
}else{
	if($_W['account']['level']<=3){
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['account']['oauth']['acid']);
		$access_token = $accObj->fetch_token();
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		load()->func('communication');
		$content = ihttp_request($url);		
		$info = @json_decode($content['content'], true);
		if(empty($info['openid'])){
			$check_user = mc_oauth_userinfo();
			$openid  = $_SESSION['openid'] = $check_user['openid'];
		}
	}
}
$sql = "SELECT `id` FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' => $openid, ':rid' => $rid,':weid' =>$weid);
$fanid =  pdo_fetchcolumn($sql,$param);
if(empty($fanid)){
	$verify = random(5,true);
	$data = array(
		'openid' =>$openid,
		'rid' => $rid,
		'isjoin' =>1,
		'lastupdate' =>TIMESTAMP,
		'isblacklist' =>0,
		'status' =>2,
		'othid' =>0,
		'vote'=>0,
		'verify'=>$verify,
		'weid'=>$weid,
	);
	if($_W['account']['level']<=3){
		load()->model('mc');
		$oauth_user = mc_oauth_userinfo();
		if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
					$userinfo = $oauth_user;
		}else{
					message("借用oauth失败");
		}
	}else{
			if($_W['fans']['follow']=='1'){
					$userinfo = $this->get_follow_fansinfo($openid);
					if($userinfo['subscribe']!='1'){
						message('获取粉丝信息失败');
					}
			}else{
					if($ridwall['gz_must']=='0'){
						$oauth_user = mc_oauth_userinfo();
						if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
									$userinfo = $oauth_user;
						}else{
									message("借用oauth失败");
						}
					}else{
						$url = $ridwall['gz_url'];
						header("location:$url");
						exit;
					}
			}
	}
	if(!empty($userinfo['avatar'])){
		 $data['avatar'] = $userinfo['avatar'];
	}else{
			if(empty($userinfo['headimgurl'])){
			  $data['avatar'] =  '../addons/meepo_bigerwall/cdhn80.jpg';
			}else{
				$data['avatar'] = $userinfo['headimgurl'];
			}
	}
	if(empty($userinfo['sex'])){
		$data['sex'] = '0';
	}else{
		$data['sex'] = $userinfo['sex'];
	}
	
	if(!empty($userinfo['nickname'])){
		$userinfo['nickname'] = $this->removeEmoji($userinfo['nickname']);
		if(!empty($userinfo['nickname'])){
			$data['nickname'] = $userinfo['nickname'];
		}else{
			$data['nickname'] = 'wechat_'.time();
		}
	}else{
		$data['nickname'] = 'wechat_'.time();
	}
	pdo_insert('weixin_flag',$data); 
}
$sql = "SELECT * FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' =>$openid, ':rid' => $rid,':weid' =>$weid);
$member =  pdo_fetch($sql,$param);
if($ridwall['lurumobile'] == '1'){		     
		if(empty($member['mobile']) || empty($member['realname'])){
				 $url = $this->createMobileUrl('getmobilerealname',array('rid'=>$rid,'op'=>$_GPC['do']));
				 header("location:$url");
				 exit;
		}
}
  

