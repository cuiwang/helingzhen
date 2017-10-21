<?php
global $_GPC, $_W;
	    $weid = $_W['uniacid'];
        $id = intval($_GPC['luckTag_id']);
		$rid = intval($_GPC['rid']);
		if($id){
         $user = pdo_fetchall("SELECT openid FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid AND weid = :weid AND rid=:rid",array(':awardid'=>$id,':weid'=>$weid,':rid'=>$rid));
				 if(!empty($user)){
						$data['list'] = $user;
						foreach($user as $row){
							pdo_update('weixin_flag',array('award_id'=>'meepo'),array('openid'=>$row['openid'],'weid'=>$weid,'rid'=>$rid));
						}
						pdo_delete('weixin_luckuser',array('awardid'=>$id,'weid'=>$weid,'rid'=>$rid));
						pdo_update('weixin_awardlist',array('num'=>0),array('id'=>$id,'weid'=>$weid,'luckid'=>$rid));
				 }else{
							$data['list'] = array();
				 }
		}
		die(json_encode($data));