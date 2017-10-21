<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$data = array();
if($_W['isajax']){
	pdo_update('weixin_wall_reply',array('bahe_status'=>2),array('rid'=>$rid,'weid'=>$weid));
	$data['errno'] = 0;
	$point1 = pdo_fetchcolumn("SELECT SUM(point) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
	$point2 = pdo_fetchcolumn("SELECT SUM(point) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
	if($point1 > $point2){
		$data['result'] = 1;
	}
	if($point1 < $point2){
		$data['result'] = 2;
	}
	if($point1 == $point2){
		$data['result'] = 3;
	}
	if($data['result']==3){
		$data['top_info'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team!=:team ORDER BY point DESC,createtime DESC LIMIT 3",array(':rid'=>$rid,':weid'=>$weid,':team'=>'0'));
	}elseif($data['result']==1){
		$data['top_info'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team ORDER BY point DESC,createtime DESC LIMIT 3",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
	}else{
		$data['top_info'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team ORDER BY point DESC,createtime DESC LIMIT 3",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
	}
	$prize = pdo_fetchcolumn("SELECT `prize` FROM ".tablename('weixin_bahe_prize')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$rid,':weid'=>$weid));
	$data['prize_setting'] = array();
	if(!empty($prize)){
			$prize	= iunserializer($prize);
			
			for($i=0;$i<count($prize['prizetype']);$i++){
					$data['prize_setting'][] = array(
							'prizetype'=>$prize['prizetype'][$i],
							'rank_start'=>$prize['rank_start'][$i],
							'rank_end'=>$prize['rank_end'][$i],
					); 
			}
		
	}else{
		$data['prize_setting'] = array();
	}
	$data['team1_info'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team ORDER BY point DESC,createtime DESC",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
	$data['team2_info'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid  AND team=:team ORDER BY point DESC,createtime DESC",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
	die(json_encode($data));
}else{
	die(json_encode(error(-1,'fail')));
}