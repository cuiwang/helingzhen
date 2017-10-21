<?php
/**
 * MEEPO新版微现场 精仿乐享微现场【米波科技出品 必属精品】
 *
 *官网 http://meepo.com.cn 作者 QQ 284099857
 */

global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
if(empty($rid)){
	message('活动rid错误！');
}
$xianchang = pdo_fetch("SELECT * FROM ".tablename($this->xc_table)." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
$xianchang['controls'] = iunserializer($xianchang['controls']);
if(empty($xianchang)){
	message('活动不存在或是已经被删除！');
}
if(TIMESTAMP<$xianchang['start_time']){
	$msg='活动在'.date('Y-m-d H:i:s',$xianchang['start_time']).'开始,到时再来哦';
	message($msg);
}
if(TIMESTAMP>$xianchang['end_time']){
	$msg='活动在'.date('Y-m-d H:i:s',$xianchang['end_time']).'结束啦!';
	message($msg);
}
if(empty($openid)){
	$url = $xianchang['gz_url'];
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
if($xianchang['gz_must']=='1' && $_W['fans']['follow']!='1' && $_W['account']['level']==4){//录入资料后取关
		$url = $xianchang['gz_url'];
		header("location:$url");
		exit;
}
$sql = "SELECT `id` FROM ".tablename($this->user_table)." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' => $openid, ':rid' => $rid,':weid' => $weid);
$fanid =  pdo_fetchcolumn($sql,$param);
if(empty($fanid)){
	$data = array(
		'openid' =>$openid,
		'rid' => $rid,
		'group'=>0,
		'isblacklist' =>1,
		'can_lottory' =>1,
		'status' =>$xianchang['status'],
		'weid'=>$weid,
		'createtime'=>time(),
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
			$oauth_user = mc_oauth_userinfo();
			if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
					$userinfo = $oauth_user;
					if($xianchang['gz_must']=='1' && $userinfo['subscribe']!='1'){
						$url = $xianchang['gz_url'];
						header("location:$url");
						exit;
					}
			}else{
					message("借用oauth失败");
			}
	}
	if(!empty($userinfo['avatar'])){
		 $data['avatar'] = $userinfo['avatar'];
	}else{
			if(empty($userinfo['headimgurl'])){
			  if(empty($this->module['config']['user_avatar'])){
				$data['avatar'] =  $_W['siteroot'].'addons/meepo_xianchang/cdhn80.jpg';
			  }else{
				$data['avatar'] =  tomedia($this->module['config']['user_avatar']);
			  }
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
			$data['nick_name'] = $userinfo['nickname'];
		}else{
			$data['nick_name'] = 'wechat_'.time();
		}
	}else{
		$data['nick_name'] = 'wechat_'.time();
	}
	pdo_insert($this->user_table,$data); 
	$fanid = pdo_insertid();
}
$user =  pdo_fetch("SELECT * FROM ".tablename($this->user_table)." WHERE id=:id",array(':id' =>$fanid));
$bd_manage = pdo_fetch("SELECT `show` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($user['mobile']) && $bd_manage['show']==1){
		$bd_url = $this->createMobileUrl('bd',array('rid'=>$rid,'frompage'=>$_GPC['do']));
		header("location:$bd_url");
		exit;
}

  

