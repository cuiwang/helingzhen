<?php
    global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		$ridwall = pdo_fetch("SELECT `signwords`,`saywords`,`cjwords`,`ddpwords`,`votewords`,`danmuwords` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
		$arr = array();
    $arr[] = array('id'=>1,'wallid'=>1,'tag'=>2,'title'=>empty($ridwall['signwords']) ? '关注'.$_W['account']['name']."关注微信号，发送含有“签到“二字的任意内容”即可签到" : $ridwall['signwords']);
		$arr[] = array('id'=>2,'wallid'=>2,'tag'=>1,'title'=>empty($ridwall['saywords']) ? '关注'.$_W['account']['name']."发送任意内容或者图片即可参与上墙!" : $ridwall['saywords']);
		$arr[] = array('id'=>3,'wallid'=>3,'tag'=>2,'title'=>empty($ridwall['cjwords']) ? '关注'.$_W['account']['name']."发送任意内容或者图片即可参与上墙!" : $ridwall['cjwords']);
		$arr[] = array('id'=>$_W['uniacid'],'wallid'=>4,'tag'=>2,'title'=>empty($ridwall['ddpwords']) ? '关注'.$_W['account']['name']."发送任意内容或者图片即可参与上墙!" : $ridwall['ddpwords']);
		$arr[] = array('id'=>$_W['uniacid'],'wallid'=>5,'tag'=>2,'title'=>empty($ridwall['ddpwords']) ? '关注'.$_W['account']['name']."发送任意内容或者图片即可参与上墙!" : $ridwall['ddpwords']
		);
		$arr[] = array('id'=>$_W['uniacid'],'wallid'=>6,'tag'=>2,'title'=>empty($ridwall['votewords']) ? '关注'.$_W['account']['name']."发送'投票'即可参与投票!" : $ridwall['votewords']
		);
	  $arr[] = array('id'=>$_W['uniacid'],'wallid'=>7,'tag'=>2,'title'=>empty($ridwall['danmuwords']) ? '关注'.$_W['account']['name']."发送'消息即可弹幕哦！!" : $ridwall['danmuwords']
		);
		$data['headmessage'] = $arr;
		die(json_encode($data));