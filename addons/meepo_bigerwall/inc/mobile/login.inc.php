<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
if(empty($rid)){
	message('参数错误，请重新进入！');
}
if(empty($ridwall)){
	message('活动不存在或是已经被删除！');
}
if(TIMESTAMP<$ridwall['activity_starttime']){
			$msg='活动在'.date('Y-m-d H:i:s',$ridwall['activity_starttime']).'开始,到时再来哦';
			message($msg);
}
if(TIMESTAMP>$ridwall['activity_endtime']){
	$msg='活动在'.date('Y-m-d H:i:s',$ridwall['activity_endtime']).'结束啦!';
	message($msg);
}
include $this->template('login');