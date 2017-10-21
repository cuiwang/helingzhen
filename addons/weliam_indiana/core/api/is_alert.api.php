<?php 
	require '../../../../framework/bootstrap.inc.php';
	require IA_ROOT. '/addons/weliam_indiana/defines.php';
	require WELIAM_INDIANA_INC.'function.php';
	
	load()->func('communication');
	
	$uniacid = $_GPC['uniacid'];
	$period_number = $_GPC['period_number'];
	
	$winner = pdo_fetch("select openid,nickname,goodsid,code,partakes,endtime from".tablename('weliam_indiana_period')."where uniacid=:uniacid and period_number=:period_number",array(':uniacid'=>$uniacid,':period_number'=>$period_number));
	$goodsname = pdo_fetchcolumn('select title from'.tablename('weliam_indiana_goodslist')." where uniacid=:uniacid and id=:id",array(':uniacid'=>$uniacid,':id'=>$winner['goodsid']));

	$sql = 'SELECT settings FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
	$settings = pdo_fetchcolumn($sql, array(':uniacid' => $uniacid, ':module' => 'weliam_indiana'));
	$settings = iunserializer($settings);
	$tpl_id_short = $settings['m_open'];
	
	$url = 'http://'. $_SERVER["SERVER_NAME"].'/app/index.php?i='.$uniacid.'&c=entry&do=person&m=weliam_indiana';
	m('log')->WL_log('is_alert','$tpl_id_short',$tpl_id_short,$uniacid);
	$parters = pdo_fetchall('select openid from'.tablename('weliam_indiana_record')." where uniacid=:uniacid and period_number=:period_number and openid not like '%machine%' limit 200",array(':uniacid'=>$uniacid,':period_number'=>$period_number));
	foreach($parters as $key => $value){
		if($value['openid'] != $winner['openid']){
			$data  = array(
				"result"=>array( "value"=> "很遗憾你购买的商品未中奖【".$goodsname."】","color"=>"#173177"),
				"totalWinMoney"=>array("value"=>'中奖人:'.$winner['nickname'].'|中奖号码:'.$winner['code'],"color"=>"#173177"),
				"issueInfo"=>array("value"=>$period_number,"color"=>"#173177"),
				"fee"=>array('value'=>$winner['partakes'].'人次',"color"=>"#173177"),
				"betTime"=>array('value'=>date('Y-m-d H:s:i',$winner['endtime']),"color"=>"#173177"),
				"remark"=>array('value' => "\r点击查看详情！", "color" => "#4a5077"),
			);
			m('common')->sendTplNotice($value['openid'],$tpl_id_short,$data,$url,'');
		}
		
	}
