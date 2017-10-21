<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	$return = array();
	if($rotate_id){
		$rotate = pdo_fetch("SELECT `status`,`pnum` FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$rotate_id));
		if($rotate['status']!=3){
			$return['status'] = 1;
		}else{
			$return['status'] = 2;
		}
		$data = pdo_fetchall("SELECT * FROM ".tablename($this->shake_user_table)." WHERE weid=:weid AND rid=:rid AND rotate_id=:rotate_id ORDER BY count DESC LIMIT 0,10",array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
		$return['data'] = array();
		if(!empty($data)){
			$shake_config = pdo_fetch("SELECT `point`,`award_again` FROM ".tablename($this->shake_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
			foreach($data as $key=>$row){
				  if($return['status'] == 2 && $rotate['pnum']>$key && $shake_config['award_again']==2){
					 pdo_update($this->shake_user_table,array('award'=>1),array('rid'=>$rid,'weid'=>$weid,'id'=>$row['id']));
				  }
				$row['progress'] = sprintf("%.2f", ($row['count']/$shake_config['point'])*100);
				$row['mid'] = $key+2;
				$return['data'][] = $row;
			}
		}
		$return['errno'] = 0;
		die(json_encode($return));
	}
}