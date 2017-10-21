<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_GPC, $_W;
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$award_id = intval($_GPC['award_id']);
	$award = pdo_fetch("SELECT `tag_name`,`luck_name`,`type` FROM ".tablename($this->lottory_award_table)." WHERE weid=:weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$award_id));
	$user_id = intval($_GPC['user_id']);
	$openid = $_GPC['openid'];
	$sql = "SELECT `nick_name`,`avatar` FROM ".tablename($this->user_table)." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
	$param = array(':openid' => $openid, ':rid' => $rid,':weid' => $weid);
	$user = pdo_fetch($sql,$param);
	$insert = array();
	$insert['weid'] = $weid;
	$insert['rid'] = $rid;
	$insert['openid'] = $openid;
	$insert['nick_name'] = $user['nick_name'];
	$insert['avatar'] = $user['avatar'];
	$insert['user_id'] = $user_id;
	$insert['lottory_id'] = $award_id;
	$insert['type'] = intval($award['type']);
	$insert['createtime'] = time();
	$insert_result = pdo_insert($this->lottory_user_table ,$insert);
	$insert_id = pdo_insertid();
	if(!empty($insert_result)){
		
		$lottory_config = pdo_fetch("SELECT `send_mess`,`def_mess` FROM ".tablename($this->lottory_config_table)." WHERE weid=:weid AND rid=:rid",array(":weid"=>$weid,":rid"=>$rid));
		if($lottory_config['send_mess']>0){
			
			if(empty($lottory_config['def_mess'])){
				$content = "亲爱的".$user['nick_name']."\n恭喜恭喜！\n你已经中: ".$award['tag_name']."\n奖品为: ".$award['luck_name']."\n请按照主持人的提示，到指定地点领取您的奖品！\n您的获奖验证码是: ".time();
			}else{
				if(strexists($lottory_config['def_mess'], '@')){
						$lottory_config['def_mess'] = str_replace('@',$user['nick_name'],$lottory_config['def_mess']);
				}
				if(strexists($lottory_config['def_mess'], '#')){
						$lottory_config['def_mess'] = str_replace('#',$award['tag_name'],$lottory_config['def_mess']);
				}
				if(strexists($lottory_config['def_mess'], '*')){
						$lottory_config['def_mess'] = str_replace('*',$award['luck_name'],$lottory_config['def_mess']);
				}
				if(strexists($lottory_config['def_mess'], '%')){
						$lottory_config['def_mess'] = str_replace('%',time(),$lottory_config['def_mess']);
				}
				$content = $lottory_config['def_mess'];
			}
			$this->send_message($rid,$openid,$content);
		}
		//$insert_id = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->lottory_user_table)." WHERE weid=:weid AND rid=:rid AND lottory_id=:lottory_id ORDER BY id DESC LIMIT 1",array(':weid'=>$weid,':rid'=>$rid,':lottory_id'=>$award_id));
		$result = array('result'=>0,'data'=>$insert_id);
	}else{
		$result = array('result'=>-1,'data'=>-1);
	}
	
	die(json_encode($result));
}