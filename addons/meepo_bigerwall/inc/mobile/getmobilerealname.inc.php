<?php
global $_GPC, $_W;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('规则不存在或是已经被删除！');
}
$op = empty($_GPC['op']) ? 'qd':$_GPC['op'];
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$weid,':rid'=>$rid));
$openid = $_W['openid'];
$check = pdo_fetchcolumn("SELECT `mobile` FROM".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid));
$go_url = $this->createMobileUrl($op,array('rid'=>$rid,'createtime'=>time()));
include $this->template('mobilereaname');