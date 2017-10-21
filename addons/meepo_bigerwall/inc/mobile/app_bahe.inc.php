<?php
include MODULE_ROOT.'/inc/mobile/__init.php';
if($ridwall['baheshow']!='1'){
	message('本次活动未开启拔河活动！');
}
if(empty($member)){
	message('错误你的信息不存在或是已经被删除！');
}
$game_status  = pdo_fetchcolumn("SELECT `bahe_status` FROM ".tablename('weixin_wall_reply')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$rid,':weid'=>$weid));
if($game_status==2){
$my_cj = pdo_fetch("SELECT `point`,`createtime` FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND openid=:openid",array(':rid'=>$rid,':weid'=>$weid,':openid'=>$openid));
$my_pm = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND point>=:point AND  createtime>=:createtime",array(':rid'=>$rid,':weid'=>$weid,':point'=>$my_cj['point'],':createtime'=>$my_cj['createtime']));
$prize = pdo_fetchcolumn("SELECT `prize` FROM ".tablename('weixin_bahe_prize')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$rid,':weid'=>$weid));
		if(!empty($prize)){
			$prize = iunserializer($prize);
			for($i=0;$i<count($prize['prizetype']);$i++){
				if($my_pm>=$prize['rank_start'][$i] && $my_pm<=$prize['rank_end'][$i]){
						$prize_name = $prize['prizename'][$i];
				}
			}
		}
}
include $this->template('app_bahe');
