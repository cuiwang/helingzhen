<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
$team = intval($_GPC['team']);
$data = array();
if($_W['isajax']){
	 $status = pdo_fetchcolumn("SELECT `bahe_status` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$weid,':rid'=>$rid));
	$count = $_GPC['point'];
	if($status=='1'){
		$sql = "UPDATE " . tablename('weixin_bahe_team') . " SET point= point + $count WHERE openid='$openid' AND rid = '$rid' AND team = '$team'";
		pdo_query($sql);
	}elseif($status=='2'){
		$point1 = pdo_fetchcolumn("SELECT SUM(point) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
		$point2 = pdo_fetchcolumn("SELECT SUM(point) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
		if($point1 > $point2){
			$data['top_info']['win_team'] = 1;
		}
		if($point1 < $point2){
			$data['top_info']['win_team'] = 2;
		}
		if($point1 == $point2){
			$data['top_info']['win_team'] = 3;
		}
		$my_cj = pdo_fetch("SELECT `point`,`createtime` FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND openid=:openid",array(':rid'=>$rid,':weid'=>$weid,':openid'=>$openid));
		$data['top_info']['point'] = $my_cj['point'];
		$my_pm = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND point>=:point AND  createtime>=:createtime",array(':rid'=>$rid,':weid'=>$weid,':point'=>$my_cj['point'],':createtime'=>$my_cj['createtime']));

		
		$data['top_info']['top'] =  $my_pm;
		$prize = pdo_fetchcolumn("SELECT `prize` FROM ".tablename('weixin_bahe_prize')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$rid,':weid'=>$weid));
		if(!empty($prize)){
			$prize = iunserializer($prize);
			for($i=0;$i<count($prize['prizetype']);$i++){
				if($my_pm>=$prize['rank_start'][$i] && $my_pm<=$prize['rank_end'][$i]){
					$data['top_info']['prize_image'] = tomedia($prize['prize_img'][$i]);
					$data['top_info']['prize_link'] = $prize['prize_getaddress'][$i];
				}
			}
		}
	}
	$data['bahe_status'] = $status;
	$data['my_pm'] = $my_pm;

	die(json_encode($data));
}