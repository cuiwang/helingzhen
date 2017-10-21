<?php
global $_GPC, $_W;  
       $point=intval($_GPC['point']);
		 $openid = $_W['openid'];
		 //$wechat  = $_GPC['openid'];
		 $rid = intval($_GPC['rid']);
         $weid = $_W['uniacid'];
         $sql = "SELECT openid FROM ".tablename('weixin_shake_toshake')." WHERE openid=:openid AND weid=:weid AND rid=:rid";
         $res = pdo_fetch($sql,array(":openid"=>$openid,':weid'=>$weid,':rid'=>$rid));
         $isopen = pdo_fetchcolumn("SELECT `isopen` FROM ".tablename('weixin_wall_reply')." WHERE weid='{$weid}' AND rid='{$rid}'");
		 if($isopen=='1'){
						echo 1;
	     }else{
				 if(!empty($res)) {
					 if($point){
					   pdo_update('weixin_shake_toshake',array('point'=>$point),array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
					 }
					 echo 2;
				 }else{
					 echo 3;
					   
				 }
	    }