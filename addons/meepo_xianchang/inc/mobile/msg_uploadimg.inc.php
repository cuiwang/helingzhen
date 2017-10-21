<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
$data = array();
$insert = array();
$sql = "SELECT * FROM ".tablename($this->user_table)." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' =>$openid, ':rid' =>$rid,':weid' =>$weid);
$user =  pdo_fetch($sql,$param);
if(empty($user)){
	$data = error(-1,'错误你的信息不存在或是已经被删除！');
	die(json_encode($data));
}
if($user['isblacklist']==2){
	$data = error(-1,'你已经被拉入黑名单、上墙失败！');
	die(json_encode($data));
}
$xianchang = pdo_fetch("SELECT * FROM ".tablename($this->xc_table)." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
if(empty($xianchang)){
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
				if($_W['account']['level']<4){
					$accObj= WeixinAccount::create($_W['oauth_account']['acid']);
				}else{
					$accObj= WeixinAccount::create($_W['account']['acid']);
				}
				$access_token = $accObj->fetch_token();
			 $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
			 $pic_data = ihttp_request($url);
			  $info = @json_decode($pic_data['content'], true);
			 if(!empty($info['errcode'])){
					$data = error(-1,$info['errmsg']);
					die(json_encode($data));
			 }
			 $path = "images/meepo_xianchang/";
			 load()->func('file');
			 $picurl = $path.random(30) .".jpg";
			 if(!file_write($picurl,$pic_data['content'])){
					$data = error(-1,'上传接口无效、上传失败');
					die(json_encode($data));
			 }
			$insert = array(
				'rid' =>$rid,
				'openid' =>$openid,
				'type' =>2,
				'createtime' => TIMESTAMP,
				'weid'=>$weid,
			);
	$status = pdo_fetchcolumn("SELECT `status` FROM ".tablename($this->wall_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	$insert['status'] = $status;
	if (!empty($_W['setting']['remote']['type'])) { 
		$remotestatus = file_remote_upload($picurl); 
		if (is_error($remotestatus)) {
			$data = error(-1,'远程附件上传失败、请检查！');
			die(json_encode($data));
		} else {
			$insert['content']  = tomedia($picurl);
		}
	}else{
		$insert['content'] =  tomedia($picurl);
	}
	$insert['avatar'] = $user['avatar'];
	$insert['nick_name'] = $user['nick_name'];
	pdo_insert($this->wall_table, $insert);
	$message = '上墙成功，请多多关注大屏幕！';
	$back = array();
	$back['picurl'] = tomedia($picurl);
	$back['tip'] = $message;
	$data = error(0,$back);
	die(json_encode($data));
}