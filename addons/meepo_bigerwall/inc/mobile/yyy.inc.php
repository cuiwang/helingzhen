<?php
global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		if(empty($rid)){
			 message('参数错误 请重新进入！');
		}
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$weid,':rid'=>$rid));
		if(empty($ridwall)){
			message('规则不存在或是已经被删除！');
		}

		if(TIMESTAMP<$ridwall['activity_starttime']){
					$msg='活动在'.date('Y-m-d H:i:s',$ridwall['activity_starttime']).'开始,到时再来哦';
					message($msg);
		}
		if(TIMESTAMP>$ridwall['activity_endtime']){
			$msg='活动在'.date('Y-m-d H:i:s',$ridwall['activity_endtime']).'结束啦!';
			message($msg);
		}
		if(!empty($ridwall["indexstyle"])){
			$style = $ridwall["indexstyle"];
		}else{
			$style ="defaultV1.0.css";
		}
		$account = pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid=".$weid);
        
        include $this->template('yyy');