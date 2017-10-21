<?php
global $_GPC, $_W;
$weid = $_W['weid'];
$id = intval($_GPC['id']);
if(empty($id)){
   message('错误、规则不存在！');
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$id));
if(isset($_COOKIE["Meepo".$id]) && $_COOKIE["Meepo".$id] ==$ridwall['loginpass'] ){
}elseif(isset($_COOKIE["Meepo".$id]) && $_COOKIE["Meepo".$id] =='meepoceshi'){
} else {
	$forward =$_W['siteroot']."app/".$this->createMobileurl('login',array('rid'=>$id));
	$forward = str_replace('./','', $forward);
	header('location: ' .$forward);
	exit;
}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "SELECT * FROM ".tablename('weixin_luckuser')." WHERE  weid=:weid AND rid=:rid ORDER BY createtime ASC LIMIT ".($pindex - 1) * $psize.",{$psize}";
		$list = pdo_fetchall($sql, array(':weid'=>$weid,':rid'=>$id));
		if(!empty($list) && is_array($list)){
		   foreach($list as &$row){
			       $info = pdo_fetch("SELECT nickname,avatar FROM ".tablename('weixin_flag')."WHERE openid = :openid AND weid = :weid AND rid=:rid",array(':openid'=>$row['openid'],':weid'=>$weid,':rid'=>$id));
				   $row['avatar'] = $info['avatar'];
				   $row['nickname'] = $info['nickname'];
				   if($row['awardid']  && empty($row['bypername'])){
				      $luckinfo = pdo_fetch("SELECT tag_name,luck_name FROM ".tablename('weixin_awardlist')."WHERE id = :id AND weid = :weid AND luckid=:luckid",array(':id'=>$row['awardid'],':weid'=>$weid,':luckid'=>$id));
					  $row['tag_name'] = $luckinfo['tag_name'];
					  $row['luck_name'] = $luckinfo['luck_name'];
				   }else{
					   $row['tag_name'] = '按人数抽奖';
				      $row['luck_name'] = $row['bypername'];
				   }				   
			 }
			 unset($row);
		}
		include $this->template('awardlist');