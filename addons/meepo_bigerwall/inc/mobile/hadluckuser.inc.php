<?php
global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $rid = intval($_GPC['rid']);
	   $user = pdo_fetchall("SELECT openid FROM ".tablename('weixin_luckuser')."WHERE  weid = :weid AND rid=:rid AND awardid != 0",array(':weid'=>$weid,':rid'=>$rid));
	   $listuser = pdo_fetchall("SELECT openid FROM ".tablename('weixin_luckuser')."WHERE  weid = :weid AND rid=:rid AND awardid = 0",array(':weid'=>$weid,':rid'=>$rid));
      
	       $data['map']['numList'] = $listuser;
	       $data['map']['tagList'] = $user;
	   
	  
	   die(json_encode($data));