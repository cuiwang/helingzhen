<?php
global $_GPC, $_W;
	   $weid = $_W['weid'];
	   $luckid = intval($_GPC['luckid']);
	   $id = intval($_GPC['luckTag_id']);
	   $rid = intval($_GPC['rid']);
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
	   $total = pdo_fetchcolumn("select count(*) from " . tablename('weixin_luckuser') . " where weid = '{$_W['uniacid']}' AND rid='{$rid}'");
	   if($id && $rid){
		     $tag = pdo_fetch("SELECT * FROM ".tablename('weixin_awardlist')." WHERE weid=:weid  AND luckid=:luckid AND id=:id",array(':weid'=>$weid,':luckid'=>$rid,':id'=>$id));
			 
             $tag['num'] = intval($total);
		     $arr['map'] = $tag;
       }elseif(!$id && $rid){
	        $cfg = $this->module['config'];
            $arr['map']=  array('tag_exclude'=>intval($ridwall['cjtag_exclude']),'num'=>intval($total));
	   
	   }
	   die(json_encode($arr));