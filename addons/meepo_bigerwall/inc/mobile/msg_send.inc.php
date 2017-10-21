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
	$content = $_GPC['content'];
	if(empty($content)){
		$data = error(-1,'请先输入内容');
		die(json_encode($data));
	}
	$insert = array(
		'rid' =>$rid,
		'openid' =>$openid,
		'type' =>'text',
		'image'=> '',
		'createtime' => TIMESTAMP,
		'weid'=>$weid,
	);
	if ($ridwall['isshow']=='0') {
		$insert['isshow'] = 1;
	} else {
		$insert['isshow'] = 0;
	}
	if(!empty($ridwall['mg_words'])){
		if(strexists($ridwall['mg_words'],'#')){
			$mg_arr = explode('#',$ridwall['mg_words']); 
			if(is_array($mg_arr)){
					foreach($mg_arr as $row){
							$content = str_replace($row,'***',$content);
					}
			}
		}else{
			$content = str_replace($ridwall['mg_words'],'***',$content);
		}
		$insert['content'] = $content;
	}else{
		$insert['content'] = $content;
	}
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
	$back['content'] = emo($content);
	$back['tip'] = $message;
	$data = error(0,$back);
	
	die(json_encode($data));
}

