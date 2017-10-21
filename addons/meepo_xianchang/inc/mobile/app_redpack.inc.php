<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/__init.php';
if(!in_array('redpack',$xianchang['controls'])){
	message('本次活动未开启红包活动！');
}
if(empty($user)){
	message('错误你的信息不存在或是已经被删除！');
}

$redpack = pdo_fetch("SELECT * FROM ".tablename($this->redpack_rotate_table)." WHERE weid = :weid AND rid=:rid AND status!=:status ORDER BY id ASC LIMIT 1",array(':weid'=>$weid,':rid'=>$rid,':status'=>'3'));
if(empty($redpack)){
	message('抢红包活动已经全部结束啦');
}
$redpack_config = pdo_fetch("SELECT * FROM ".tablename($this->redpack_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($redpack_config)){
	message('请先配置红包参数');
}

if(empty($_GPC['Meepo'.$weid]) && $redpack_config['weixin_pay']==0){
			$reply=pdo_fetch('SELECT `appid` FROM '.tablename($this->redpack_config_table).' WHERE weid=:weid AND rid=:rid',array(':weid'=>$weid,':rid'=>$rid));
		    $callback =$_W['siteroot']."app/".$this->createMobileurl('app_oauth',array('rid'=>$rid));
            $callback = str_replace('./','',$callback);
			$callback = urlencode($callback);
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location: ' . $forward);
			exit();
}
$redpack_user_id = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->redpack_user_table)." WHERE weid = :weid AND rid=:rid AND openid=:openid AND rotate_id =:rotate_id",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid,':rotate_id'=>$redpack['id']));
if(empty($redpack_user_id)){
    $join_man = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->redpack_user_table)." WHERE weid = :weid AND rid=:rid AND rotate_id =:rotate_id",array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$redpack['id']));//已经加入的总人数
	if($join_man >= $redpack_config['max_man']){
		message('本轮准许参与抢红包活动仅限'.$redpack_config['max_man'].'人参与、请等待下一轮开始');
	}else{
		pdo_insert($this->redpack_user_table,array('rid'=>$rid,'weid'=>$weid,'openid'=>$openid,'rotate_id'=>$redpack['id'],'nick_name'=>$user['nick_name'],'avatar'=>$user['avatar'],'createtime'=>time()));
	}
}

include $this->template('app_redpack');