<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
$content = $_GPC['sign_words'];
$sign_config = pdo_fetch("SELECT `signcheck`,`sign_success`,`had_sign_content` FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
if($_W['isajax']){
$data = array();
if(empty($content)){
	$data = error(-1,'请输入签到祝福语');
	die(json_encode($data));
}
$check_sign = pdo_fetch("SELECT * FROM ".tablename('weixin_signs')." WHERE openid = :openid AND rid = :rid AND weid = :weid",array(':openid'=>$openid,':rid'=>$rid,':weid'=>$weid));
	if(!empty($check_sign)){
			if($check_sign['status']==1){
				if(!empty($sign_config['had_sign_content'])){
						$had_sign_content = $sign_config['had_sign_content'];
				}else{
						$had_sign_content = '您已经签到过啦！';
				}
				
			}else{
				if(!empty($sign_config['had_sign_content'])){
						$had_sign_content = $sign_config['had_sign_content'];
				}else{
						$had_sign_content = '您已经签到过啦、请等到管理员审核！';
				}
			}
			$data = error(-1,$had_sign_content);
			die(json_encode($data));
	}else{
			$sql = "SELECT `avatar`,`nickname`,`isblacklist` FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
			$param = array(':openid' =>$openid, ':rid' => $rid,':weid' =>$weid);
			$member =  pdo_fetch($sql,$param);
			if(empty($member)){
				$data = error(-1,'错误你的信息不存在或是已经被删除！');
				die(json_encode($data));
			}
			if($member['isblacklist']==1){
				$data = error(-1,'你已经被拉入黑名单、签到失败！');
				die(json_encode($data));
			}
		 $signs = array();
		 if($sign_config['signcheck']=='2'){
				
				 $signs['status'] = 1;
		 }else{
				 $signs['status'] = 2;
		 } 
		 $signs['openid'] = $openid;
		 $signs['avatar'] = $member['avatar'];
		 $signs['nickname'] = str_replace('"','',$member['nickname']);
		 $signs['content'] = $this->removeEmoji($content);
		 $signs['weid'] = $weid;
		 $signs['rid'] = $rid;
		 $signs['createtime'] = time();
		 pdo_insert('weixin_signs',$signs);
		 
		 
		 if($sign_config['signcheck']==2){
				 $log = $openid."|".$member['nickname']."|".$member['avatar']."|\n";
				 logging_sign($weid,$rid,$log);
				if(!empty($sign_config['sign_success'])){
						$sign_success = $sign_config['sign_success'];
				}else{
						$sign_success = '恭喜、签到成功！';
				}
				 
		 }else{
				if(!empty($sign_config['sign_success'])){
						$sign_success = $sign_config['sign_success'];
				}else{
						$sign_success = '恭喜、签到成功、等待管理员审核！';
				}	 
		 } 
		 $data = error(0,$sign_success);
		 die(json_encode($data));
	}
}
function logging_sign($weid,$rid,$log){
	global $_W;
	$filename = IA_ROOT . '/addons/meepo_bigerwall/'.$weid.'/'. $rid . '/sign.txt';
	load()->func('file');
	mkdirs(dirname($filename));
	$fp=fopen($filename,"a+");
	$str =$log;
	fwrite($fp,$str);
	fclose($fp);
}



