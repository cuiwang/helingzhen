<?php
global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $id = intval($_GPC['luckUser_id']);
	   $openid = trim($_GPC['luckUser_openid']);
	   $option = intval($_GPC['option']);
	   $rid = intval($_GPC['rid']);
	   $lastnum =  pdo_fetchcolumn("SELECT num FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$option,':weid'=>$weid,':luckid'=>$rid));
	   if(!empty($openid) && $id && $option){
		      
		      pdo_update('weixin_awardlist',array('num'=>$lastnum - 1),array('id'=>$option,'weid'=>$weid,'luckid'=>$rid));
			    pdo_update('weixin_flag',array('award_id'=>'meepo'),array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
	        pdo_delete('weixin_luckuser',array('id'=>$id,'openid'=>$openid,'rid'=>$rid)); 
			    $data = $openid;
			    die(json_encode($data));
	   }else{
	      die('');
	   }