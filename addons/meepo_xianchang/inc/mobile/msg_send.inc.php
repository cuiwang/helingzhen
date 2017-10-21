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
if($user['status']!=1){
	$data = error(-1,'您的信息未被审核通过、上墙失败！');
	die(json_encode($data));
}
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
	$content = $_GPC['content'];
	if(empty($content)){
		$data = error(-1,'请先输入内容');
		die(json_encode($data));
	}
	$insert = array(
		'rid' =>$rid,
		'openid' =>$openid,
		'type' =>1,
		'createtime' => TIMESTAMP,
		'weid'=>$weid,
	);
	$forbidden_words = pdo_fetchcolumn("SELECT `forbidden_words` FROM ".tablename($this->wall_config_table)." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));
	if(!empty($forbidden_words)){
		if(strexists($forbidden_words,'#')){
			$mg_arr = explode('#',$forbidden_words); 
			if(is_array($mg_arr)){
					foreach($mg_arr as $row){
							//$content = str_replace($row,'***',$content);
							if(strexists($content,$row)){  
								$data = error(-1,'请文明发言！');
								die(json_encode($data));
							}
					}
			}
		}else{
			if(strexists($content,$forbidden_words)){  
				$data = error(-1,'请文明发言！');
				die(json_encode($data));
			}
		}
		$insert['content'] = $content;
	}else{
		$insert['content'] = $content;
	}
	$status = pdo_fetchcolumn("SELECT `status` FROM ".tablename($this->wall_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	$insert['status'] = $status;
	//$insert['content'] = $content;
	$insert['avatar'] = $user['avatar'];
	$insert['nick_name'] = $user['nick_name'];
	pdo_insert($this->wall_table, $insert);
	$message = '上墙成功，请多多关注大屏幕！';
	$back = array();
	$back['content'] = $this->emo($content);
	$back['tip'] = $message;
	$data = error(0,$back);
	die(json_encode($data));
}

