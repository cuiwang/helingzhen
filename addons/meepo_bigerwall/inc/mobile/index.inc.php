<?php
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		if(empty($rid)){
		  message('参数错误，请重新进入！');
		}
		 $ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
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
		 if(empty($ridwall['refreshtime'])){
		   $ridwall['refreshtime'] = 5000;
		 }else{
		   $ridwall['refreshtime'] = $ridwall['refreshtime']*1000;
		 }
		 if(empty($ridwall['saytasktime'])){
		   $ridwall['saytasktime'] = 4000;
		 }else{
		   $ridwall['saytasktime'] = $ridwall['saytasktime']*1000;
		 }
		 $saytasktime = $ridwall['refreshtime'] - 1000;
		 if(empty($ridwall['voterefreshtime'])){
		   $ridwall['voterefreshtime'] = 10000;
		 }else{
		   $ridwall['voterefreshtime'] = $ridwall['voterefreshtime']*1000;
		 }
		 if(!empty($ridwall["indexstyle"])){
			$style = $ridwall["indexstyle"];
		 }else{
			$style ="defaultV1.0.css";
		 }
	   if(isset($_COOKIE["Meepo".$rid]) && $_COOKIE["Meepo".$rid] ==$ridwall['loginpass'] ){
	   }elseif(isset($_COOKIE["Meepo".$rid]) && $_COOKIE["Meepo".$rid] =='meepoceshi'){
	   } else {
			 $forward =$_W['siteroot']."app/".$this->createMobileurl('login',array('rid'=>$rid));
                    $forward = str_replace('./','', $forward);
					header('location: ' .$forward);
					exit;
     }
    $weid = $_W['uniacid'];
		$signtotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_signs') . " WHERE weid = '{$weid}' AND status=1 AND rid='{$rid}'");
		$walltotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE weid = '{$weid}' AND isshow=1 AND isblacklist=0 AND rid='{$rid}'");
    $signusers = pdo_fetchall('SELECT * FROM ' . tablename('weixin_signs') . " WHERE weid = '{$weid}' AND rid='{$rid}' AND status=1 ORDER BY createtime DESC ");
		if(is_array($signusers)){
			foreach($signusers as &$row){
						  $row['content'] = emotion(emo($row['content']));
			}
			unset($row);
		}
		$keyword = pdo_fetchcolumn("SELECT `content` FROM ".tablename('rule_keyword')." WHERE  uniacid='{$weid}' AND module='meepo_bigerwall' AND rid='{$rid}'");
		$modules = pdo_fetchall("SELECT `modules_url`,`bg`,`name`,`id` FROM ".tablename('weixin_modules')." WHERE rid=:rid AND weid = :weid AND status = :status",array(':rid'=>$rid,':weid'=>$weid,':status'=>'1'));
		
		include $this->template('newindex');
		
	