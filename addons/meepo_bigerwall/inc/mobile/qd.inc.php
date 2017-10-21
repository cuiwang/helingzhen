<?php
include MODULE_ROOT.'/inc/mobile/__init.php';
if($ridwall['qdqshow']!='1'){
	message('本次活动未开启签到活动！');
}
if(empty($member)){
	message('错误你的信息不存在或是已经被删除！');
}
 $check_sign = pdo_fetch("SELECT * FROM ".tablename('weixin_signs')." WHERE openid = :openid AND rid = :rid AND weid = :weid",array(':openid'=>$openid,':rid'=>$rid,':weid'=>$weid));
 if(!empty($check_sign)){
	$sign_createtime = pdo_fetchcolumn("SELECT `createtime` FROM ".tablename('weixin_signs')." WHERE   rid = :rid AND weid = :weid AND openid = :openid",array(':rid'=>$rid,':weid'=>$weid,':openid'=>$openid));
	$sign_nums = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('weixin_signs')." WHERE   rid = :rid AND weid = :weid AND createtime < :createtime",array(':rid'=>$rid,':weid'=>$weid,':createtime'=>$sign_createtime));
	$sign_nums = $sign_nums +1;
 }
include $this->template('qd');
