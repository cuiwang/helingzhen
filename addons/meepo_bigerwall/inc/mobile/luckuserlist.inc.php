<?php
global $_GPC, $_W;
	   $weid = $_W['uniacid'];
       $luckid = intval($_GPC['luckTag_luckid']);
	   $id = intval($_GPC['luckTag_id']);
	   $rid = intval($_GPC['rid']);
       if($id && $luckid){
	      $user = pdo_fetchall("SELECT * FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid  AND rid=:rid AND weid = :weid",array(':awardid'=>$id,':weid'=>$weid,':rid'=>$rid));
		  $luckname = pdo_fetchcolumn("SELECT luck_name FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$id,':weid'=>$weid,':luckid'=>$rid));
		  if(is_array($user) && !empty($user)){
		     foreach($user as &$row){
			       $info = pdo_fetch("SELECT `nickname`,`avatar`,`mobile` FROM ".tablename('weixin_flag')."WHERE openid = :openid AND weid = :weid AND rid=:rid",array(':openid'=>$row['openid'],':weid'=>$weid,':rid'=>$rid));
				   $row['imgurl'] = $info['avatar'];
				   $row['name'] = !empty($info['realname']) ? $info['realname']:$info['nickname'];
				   $row['luckName'] = $luckname;
					 $row['mobile'] = $info['mobile'];
				   
			 }
			 unset($row);
			 $data['luckMap']['luckList'] = $user;
		  }else{
		      $data['luckMap']['luckList'] = array();
		  }
	   }elseif(!$id && $luckid){
	         $user = pdo_fetchall("SELECT * FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid AND weid = :weid  AND rid=:rid ",array(':awardid'=>0,':weid'=>$weid,':rid'=>$rid));
		  
		  if(is_array($user) && !empty($user)){
		     foreach($user as &$row){
			       $info = pdo_fetch("SELECT `nickname`,`avatar`,`mobile` FROM ".tablename('weixin_flag')."WHERE openid = :openid AND weid = :weid AND rid=:rid ",array(':openid'=>$row['openid'],':weid'=>$weid,':rid'=>$rid));
				   $row['imgurl'] = $info['avatar'];
				   $row['name'] = !empty($info['realname']) ? $info['realname']:$info['nickname'];
				   $row['luckName'] = $row['bypername'];
				   $row['mobile'] = $info['mobile'];
			 }
			 unset($row);
			 $data['luckMap']['luckList'] = $user;
		   }else{
		      $data['luckMap']['luckList'] = array();
		  } 
	   }else{
	          $data['luckMap']['luckList'] = array();
	   }
	   die(json_encode($data));