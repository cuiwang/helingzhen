<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020 http://www.startingline.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Describe: 短信通知
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
if (!defined('IN_IA')) {
	exit('Access Denied');
} 

class Welian_Indiana_Sms{
	function sendSms($note_code,$param,$mobile){
		global $_W;
		$sms_param = wlsetting_read('sms_param');
		m('topclient')->appkey = $sms_param['note_appkey'];
		m('topclient')->secretKey = $sms_param['note_secretKey'];
		m('smsnum')->setExtend(rand(1000,9999));
		m('smsnum')->setSmsType("normal");
		m('smsnum')->setSmsFreeSignName($sms_param['note_sign']);
		m('smsnum')->setSmsParam(json_encode($param));
		m('smsnum')->setRecNum($mobile);
		m('smsnum')->setSmsTemplateCode($note_code);
		$resp = m('topclient')->execute(m('smsnum'),'6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805');
		$res = object_array($resp);
		return $res;
	}
	//获取系统设置
	function get_setting(){
		global $_W;
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'weliam_indiana'));
		$settings = iunserializer($settings);
		return $settings;
	}
	
	//替换默认变量
	function replaceTemplate($str, $datas = array()){
		foreach ($datas as $d) {
			$str = str_replace('【' . $d['name'] . '】', $d['value'], $str);
		}
		return $str;
	}
	
	//发送身份验证信息
	function smsSF($code,$mobile){
		global $_W;
		$sms = wlsetting_read('sms');
		$settings = self::get_setting();
		$smses = pdo_fetch("select * from".tablename('weliam_indiana_smstpl')."where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$sms['dy_sf']));
		$param = unserialize($smses['data']);
		$datas = array(
			array('name' => '商城名称', 'value' => $settings['sname']),
			array('name' => '版权信息', 'value' => $settings['copyright']),
			array('name' => '验证码', 'value' => $code)
		);
		foreach ($param as $d) {
			$params[$d['data_temp']] = self::replaceTemplate($d['data_shop'], $datas);
		}
		return self::sendSms($smses['smstplid'],$params,$mobile);
	}
	
	//发送购买成功信息
	function smsGM($mobile,$title,$number){
		global $_W;
		$sms = wlsetting_read('sms');
		$settings = self::get_setting();
		$smses = pdo_fetch("select * from".tablename('weliam_indiana_smstpl')."where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$sms['dy_gm']));
		$param = unserialize($smses['data']);
		$datas = array(
			array('name' => '商城名称', 'value' => $settings['sname']),
			array('name' => '版权信息', 'value' => $settings['copyright']),
			array('name' => '商品名称', 'value' => $title),
			array('name' => '商品数量', 'value' => $number)
		);
		foreach ($param as $d) {
			$params[$d['data_temp']] = self::replaceTemplate($d['data_shop'], $datas);
		}
		return self::sendSms($smses['smstplid'],$params,$mobile);
	}
	
	//发送充值成功信息
	function smsCZ($mobile,$number){
		global $_W;
		$sms = wlsetting_read('sms');
		$settings = self::get_setting();
		$smses = pdo_fetch("select * from".tablename('weliam_indiana_smstpl')."where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$sms['dy_cz']));
		$param = unserialize($smses['data']);
		$datas = array(
			array('name' => '商城名称', 'value' => $settings['sname']),
			array('name' => '版权信息', 'value' => $settings['copyright']),
			array('name' => '充值金额', 'value' => $number)
		);
		foreach ($param as $d) {
			$params[$d['data_temp']] = self::replaceTemplate($d['data_shop'], $datas);
		}
		return self::sendSms($smses['smstplid'],$params,$mobile);
	}
	
	//发送中奖信息
	function smsZJ($mobile,$title,$name){
		global $_W;
		$sms = wlsetting_read('sms');
		$settings = self::get_setting();
		$smses = pdo_fetch("select * from".tablename('weliam_indiana_smstpl')."where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$sms['dy_zj']));
		$param = unserialize($smses['data']);
		$datas = array(
			array('name' => '商城名称', 'value' => $settings['sname']),
			array('name' => '版权信息', 'value' => $settings['copyright']),
			array('name' => '商品名称', 'value' => $title),
			array('name' => '获奖者', 'value' => $name)
		);
		foreach ($param as $d) {
			$params[$d['data_temp']] = self::replaceTemplate($d['data_shop'], $datas);
		}
		return self::sendSms($smses['smstplid'],$params,$mobile);
	}
	
	//发送未中奖信息
	function smsWZJ($mobile,$title,$name){
		global $_W;
		$sms = wlsetting_read('sms');
		$settings = self::get_setting();
		$smses = pdo_fetch("select * from".tablename('weliam_indiana_smstpl')."where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$sms['dy_wzj']));
		$param = unserialize($smses['data']);
		$datas = array(
			array('name' => '商城名称', 'value' => $settings['sname']),
			array('name' => '版权信息', 'value' => $settings['copyright']),
			array('name' => '商品名称', 'value' => $title),
			array('name' => '获奖者', 'value' => $name)
		);
		foreach ($param as $d) {
			$params[$d['data_temp']] = self::replaceTemplate($d['data_shop'], $datas);
		}
		return self::sendSms($smses['smstplid'],$params,$mobile);
	}
	
	//发送活动信息
	function smsHD($mobile,$name){
		global $_W;
		$sms = wlsetting_read('sms');
		$settings = self::get_setting();
		$smses = pdo_fetch("select * from".tablename('weliam_indiana_smstpl')."where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$sms['dy_hd']));
		$param = unserialize($smses['data']);
		$datas = array(
			array('name' => '商城名称', 'value' => $settings['sname']),
			array('name' => '版权信息', 'value' => $settings['copyright']),
			array('name' => '用户名称', 'value' => $name)
		);
		foreach ($param as $d) {
			$params[$d['data_temp']] = self::replaceTemplate($d['data_shop'], $datas);
		}
		return self::sendSms($smses['smstplid'],$params,$mobile);
	}
}
?>