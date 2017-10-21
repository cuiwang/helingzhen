<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
$data = array();
$insert = array();
$sql = "SELECT * FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' =>$openid, ':rid' =>$rid,':weid' =>$weid);
$member =  pdo_fetch($sql,$param);
if(empty($member)){
	$data = error(-1,'错误你的信息不存在或是已经被删除！');
	die(json_encode($data));
}
if($member['isblacklist']==1){
	$data = error(-1,'你已经被拉入黑名单、上墙失败！');
	die(json_encode($data));
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
if(empty($ridwall)){
	$data = error(-1,'活动不存在或是已经被删除！');
	die(json_encode($data));
}
if($_W['isajax']){
	$media_id = $_GPC['media_id'];
	if(empty($media_id)){
		$data = error(-1,'上传失败');
		die(json_encode($data));
	}
				load()->func('communication');
				load()->classs('weixin.account');
				if($_W['account']['level']<=3){
					$accObj= WeixinAccount::create($_W['oauth_account']['acid']);
				}else{
					$accObj= WeixinAccount::create($_W['account']['acid']);
				}
				$access_token = $accObj->fetch_token();
			 $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
			 $pic_data = ihttp_request($url);
			 if(empty($pic_data['content'])){
					$data = error(-1,'上传接口无效、上传失败');
					die(json_encode($data));
			 }
			 $path = "images/meepo_bigerwall/";
			 load()->func('file');
			 $picurl = $path.random(30) .".jpg";
			 file_write($picurl,$pic_data['content']);
			$insert = array(
				'rid' =>$rid,
				'openid' =>$openid,
				'type' =>'image',
				'createtime' => TIMESTAMP,
				'weid'=>$weid,
			);
	if ($ridwall['isshow']=='0') {
		$insert['isshow'] = 1;
	} else {
		$insert['isshow'] = 0;
	}
	if (!empty($_W['setting']['remote']['type'])) { 
		$remotestatus = file_remote_upload($picurl); 
		if (is_error($remotestatus)) {
			$data = error(-1,'远程附件上传失败、请检查！');
			die(json_encode($data));
		} else {
			$insert['image']  = tomedia($picurl);
		}
	}else{
		$insert['image'] =  tomedia($picurl);
	}
	$insert['content'] = 'meepo图片消息';
	$insert['avatar'] = $member['avatar'];
	$insert['nickname'] = $member['nickname'];
	pdo_insert('weixin_wall', $insert);
	if($ridwall['isshow']=='0'){		 
		if(!empty($ridwall['send_tips'])) {
			$message = $ridwall['send_tips'];
		} else {
			$message = '上墙成功，请多多关注大屏幕！';
		}
	}else{
		if(empty($ridwall['send_tips'])){
			$message = '发送消息成功，请等待管理员审核';
		}else{
			$message = $ridwall['send_tips'];	
		}
		
	}
	$back = array();
	$back['picurl'] = tomedia($picurl);
	$back['tip'] = $message;
	$data = error(0,$back);
	die(json_encode($data));
}