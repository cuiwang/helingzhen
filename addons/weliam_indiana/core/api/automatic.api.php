<?php 
	require '../../../../framework/bootstrap.inc.php';
	require IA_ROOT. '/addons/weliam_indiana/defines.php';
	require WELIAM_INDIANA_INC.'function.php';
	
	load()->func('communication');
	
	$uniacid = $_GPC['uniacid'];
	$period_number = $_GPC['period_number'];
	$ip_a = $_SERVER["SERVER_ADDR"];
	$ip_b = $_SERVER["REMOTE_ADDR"];
	
	$url2 = 'http://'. $_SERVER["SERVER_NAME"].'/app/index.php?i='.$uniacid.'&c=entry&do=person&m=weliam_indiana';
	
	$period = pdo_fetch("select goodsid,periods,nickname,openid,jiexiao_time from".tablename('weliam_indiana_period')." where uniacid=:uniacid and period_number=:period_number",array(':uniacid'=>$uniacid,':period_number'=>$period_number));
	$goods = pdo_fetch("select title,automatic from".tablename('weliam_indiana_goodslist')."where id=:id",array(':id'=>$period['goodsid']));
	$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
	$settings = pdo_fetchcolumn($sql, array(':uniacid' => $uniacid, ':module' => 'weliam_indiana'));
	$settings = iunserializer($settings);
	$tpl_id_short = $settings['m_send'];
	
	if(strstr($period['openid'],'machine') != '' || $period['openid'] == ''){
		m('log')->WL_log('automatic','自动发货情况','【第'.$period['periods'].'期商品'.$goods['title'].'】未找到真实用户',$uniacid);
		exit('未找到真实用户');
	}
	$automatic = unserialize($goods['automatic']);
	if($automatic['select'] == ''){
		m('log')->WL_log('automatic','自动发货情况','【第'.$period['periods'].'期商品'.$goods['title'].'】未设置自动发货',$uniacid);
		exit('未设置自动发货');
	}
	m('log')->WL_log('automatic','自动发货情况',$automatic['select'],$uniacid);
	if($automatic['select'] == 2){//积分
		m('log')->WL_log('automatic','查询情况',$period,$uniacid);
		$re = pdo_update('weliam_indiana_period',array('status'=>7),array('period_number'=>$period_number,'uniacid'=>$uniacid));
		m('log')->WL_log('automatic','状态修改情况',$re,$uniacid);
		if($re == 1){
			m('credit')->updateCredit1($period['openid'],$uniacid,$automatic['num']);
			$data  = array(
				"first"=>array( "value"=> "你一元购的奖品已经发货了。","color"=>"#173177"),
				"keyword1"=>array( "value"=> $period_number,"color"=>"#173177"),
				"keyword2"=>array( "value"=> '自动发货（积分：'.$automatic['num'].'个）',"color"=>"#173177"),
				"keyword3"=>array( "value"=> '请到个人中心兑换',"color"=>"#173177"),
				"remark"=>array('value' => "\r点击查看详情！", "color" => "#4a5077"),
			);
			m('common')->sendTplNotice($period['openid'],$tpl_id_short,$data,$url2,'');
			m('log')->WL_log('automatic','自动发货情况','【第'.$period['periods'].'期商品'.$goods['title'].'】自动发货成功',$uniacid);
		}else{
			m('log')->WL_log('automatic','自动发货情况','【第'.$period['periods'].'期商品'.$goods['title'].'】自动发货失败',$uniacid);
			exit('未设置自动发货');
		}
	}elseif($automatic['select'] == 3){//夺宝币
		$re = pdo_update("weliam_indiana_period",array('status'=>'7'),array('period_number'=>$period_number,'uniacid'=>$uniacid));
		if($re > 0){
			m('credit')->updateCredit2($period['openid'],$uniacid,$automatic['num']);
			$data  = array(
				"first"=>array( "value"=> "你一元购的奖品已经发货了。","color"=>"#173177"),
				"keyword1"=>array( "value"=> $period_number,"color"=>"#173177"),
				"keyword2"=>array( "value"=> '自动发货（夺宝币：'.$automatic['num'].'个）',"color"=>"#173177"),
				"keyword3"=>array( "value"=> '请到个人中心查看',"color"=>"#173177"),
				"remark"=>array('value' => "\r点击查看详情！", "color" => "#4a5077"),
			);
			m('common')->sendTplNotice($period['openid'],$tpl_id_short,$data,$url2,'');
			m('log')->WL_log('automatic','自动发货情况','【第'.$period['periods'].'期商品'.$goods['title'].'】自动发货成功',$uniacid);
		}else{
			m('log')->WL_log('automatic','自动发货情况','【第'.$period['periods'].'期商品'.$goods['title'].'】自动发货失败',$uniacid);
			exit('未设置自动发货');
		}
	}
	