<?php
include MODULE_ROOT.'/inc/mobile/__init.php';
if($ridwall['yyyshow']!='1'){
	message('本次活动未开启摇一摇活动！');
}
if(empty($member)){
	message('错误你的信息不存在或是已经被删除！');
}
if($member['isblacklist']==1){
	message('你已经被拉入黑名单、不能参加摇一摇！');
}
$shake_id = pdo_fetch("SELECT `id` FROM ".tablename('weixin_shake_toshake')." WHERE openid=:openid AND weid=:weid AND rid=:rid",array(':openid'=>$openid,':weid'=>$weid,':rid'=>$rid));
if(empty($shake_id)){
	 pdo_insert('weixin_shake_toshake',array('openid'=>$openid,'phone'=>emotion($member['nickname']),'point'=>'0','avatar'=>$member['avatar'],'weid'=>$weid,'rid'=>$rid));
}
include $this->template('shakehands');