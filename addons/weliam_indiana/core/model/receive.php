<?php

define('IN_MOBILE', true);
$period_number = $_POST['period_number'];
$num = $_POST['jiexiao_time'];
$couponid = $_POST['couponid'];
$buynum = $_POST['num'];
ignore_user_abort(TRUE);
set_time_limit(0);
if(!empty($period_number)){
	//揭晓
	$params = array('nihao2'=>date('H:i:s',time()),'period_number'=>$period_number,'jiexiao_time'=>$_POST['jiexiao_time'],'lackopenid'=>$_POST['lackopenid'],'url2'=>$_POST['url2'],'goodsid'=>$_POST['goodsid'],'uniacid'=>$_POST['uniacid']);
	file_put_contents("../../../../addons/weliam_indiana/params10.log", var_export($params, true).PHP_EOL, FILE_APPEND);
	sleep(60*$num);
	
	require '../../../../framework/bootstrap.inc.php';
	$newclass = new DB(); 
	$newclass->update("weliam_indiana_period",array('status'=>3),array('period_number'=>$period_number));
	$params = array('nihao2'=>date('H:i:s',time()),'period_number'=>$period_number,'jiexiao_time'=>$_POST['jiexiao_time'],'lackopenid'=>$_POST['lackopenid'],'url2'=>$_POST['url2'],'goodsid'=>$_POST['goodsid'],'uniacid'=>$_POST['uniacid']);
	file_put_contents("../../../../addons/weliam_indiana/params10.log", var_export($params, true).PHP_EOL, FILE_APPEND);
	
	$goods = pdo_fetch("select title from".tablename("weliam_indiana_goodslist")."where id='{$_POST['goodsid']}' and uniacid='{$_POST['uniacid']}'");
	$url2 =$_POST['url2'];
	$datam  = array(
		"first"=>array( "value"=> "恭喜你！你参与的一元夺宝已中奖！","color"=>"#173177"),
		"keyword1"=>array('value' => "一元夺宝", "color" => "#4a5077"),
		"keyword2"=>array('value' => $goods['title'], "color" => "#4a5077"),
		"remark"=>array("value"=>'点击查看详情', "color" => "#4a5077"),
	);
	$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
	$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_POST['uniacid'], ':module' => 'weliam_indiana'));
	$settings = iunserializer($settings);
	$template_id = $settings['m_suc'];
	
	$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $_POST['uniacid']));
	$params = array('acid'=>$acid,'nihao2'=>date('H:i:s',time()),'period_number'=>$period_number,'jiexiao_time'=>$_POST['jiexiao_time'],'lackopenid'=>$_POST['lackopenid'],'url2'=>$_POST['url2'],'goodsid'=>$_POST['goodsid'],'uniacid'=>$_POST['uniacid'],'datam'=>$datam,'template_id'=>$template_id);
	file_put_contents("../../../../addons/weliam_indiana/params10.log", var_export($params, true).PHP_EOL, FILE_APPEND);
	$account= WeAccount :: create($acid);
	$account -> sendTplNotice($_POST['lackopenid'], $template_id, $datam, $url2);
}else{
//生成优惠卷
	require '../../../../framework/bootstrap.inc.php';
	$coupons = pdo_fetch("select * from".tablename("weliam_indiana_coupon")."where couponid = '{$couponid}'");
	$endtime = intval($coupons['daylimit'])*24*3600;
	$insert = array(
		'couponid' => $couponid,
		'uniacid' => $coupons['uniacid'],
		'firstopenid' => $_POST['openid'],
		'secondopenid' => $_POST['openid'],
		'gettime' => TIMESTAMP,
		'endtime' =>TIMESTAMP+$endtime ,
		'status' => 1,
		'merchantid'=>$coupons['merchantid'],
		'couponnum'=>$buynum,
		'coupon_number'=>date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99))
	);
	pdo_insert('weliam_indiana_coupon_record', $insert);
}
