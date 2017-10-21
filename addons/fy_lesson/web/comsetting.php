<?php
/**
 * 分销设置
 */
 load()->func('tpl');
 load()->func('file');

/* 分享课程和讲师 */
$sharelink	  = unserialize($setting['sharelink']);
$sharelesson  = unserialize($setting['sharelesson']);
$shareteacher = unserialize($setting['shareteacher']);
$cash_way	  = unserialize($setting['cash_way']);

/* 佣金比例 */
$commission = unserialize($setting['commission']);

$cert = file_exists(dirname(dirname(__FILE__))."/cert/apiclient_cert".$uniacid.".pem");
$key = file_exists(dirname(dirname(__FILE__))."/cert/apiclient_key".$uniacid.".pem");
$rootca = file_exists(dirname(dirname(__FILE__))."/cert/rootca".$uniacid.".pem");

if (checksubmit('submit')) {
	$data = array(
		'is_sale'	       => intval($_GPC['is_sale']),     /* 分销功能 */
		'self_sale'	       => intval($_GPC['self_sale']),   /* 分销内购 */
		'sale_rank'	       => intval($_GPC['sale_rank']),   /* 分销身份 1.任何人 2.VIP身份 */
		'level'			   => intval($_GPC['level']),       /* 分销级别 */
		'rec_income'	   => trim($_GPC['rec_income']),	/* 直接推荐下级奖励 */
		'cash_type'	       => intval($_GPC['cash_type']),   /* 佣金提现处理方式 */
		'cash_way'	       => serialize($_GPC['cash_way']), /* 佣金提现方式 */
		'cash_lower'	   => floatval($_GPC['cash_lower']),/* 佣金提现下限 */
		'mchid'			   => trim($_GPC['mchid']),         /* 微信支付商户号 */
		'mchkey'		   => trim($_GPC['mchkey']),        /* 商户支付密钥(API密钥) */
		'serverIp'		   => trim($_GPC['serverIp']),      /* 服务器IP */
	);

	/* 分享课程和讲师 */
	$sharelink = array(
		'title'  => $_GPC['sharelinktitle'],
		'desc'   => $_GPC['sharelinkdesc'],
		'images' => $_GPC['sharelinkimg'],
	);
	$data['sharelink'] = serialize($sharelink);

	$sharelesson = array(
		'title'  => $_GPC['sharelessontitle'],
		'images' => $_GPC['sharelessonimg'],
	);
	$data['sharelesson'] = serialize($sharelesson);

	$shareteacher = array(
		'title'  => $_GPC['shareteachertitle'],
		'images' => $_GPC['shareteacherimg'],
	);
	$data['shareteacher'] = serialize($shareteacher);
 
	/* 佣金比例 */
	$commission = array(
		'commission1' => floatval($_GPC['commission1']),
		'commission2' => floatval($_GPC['commission2']),
		'commission3' => floatval($_GPC['commission3']),
		'updatemoney' => floatval($_GPC['updatemoney']),
	);
	$data['commission'] = serialize($commission);

	if(!empty($_FILES['apiclient_cert']['name'])){
		$this->upfile($_FILES['apiclient_cert'], "apiclient_cert");
	}
	if(!empty($_FILES['apiclient_key']['name'])){
		$this->upfile($_FILES['apiclient_key'], "apiclient_key");
	}
	if(!empty($_FILES['rootca']['name'])){
		$this->upfile($_FILES['rootca'], "rootca");
	}
	
	/* 清空会员推广海报缓存 */
	if($_GPC['clearposter']){
		$files = glob(ATTACHMENT_ROOT.'images/fy_lesson/*');
		foreach($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
		//清空token缓存
		pdo_update('core_cache', array('value'=>null), array('key'=>"accesstoken:".$_W['acid']));
	}

	if (empty($setting)) {
		message("请先设置基本设置！", $this->createWebUrl('setting'), "warning");
	} else {
		$res = pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
		if($res){
			$this->addSysLog($_W['uid'], $_W['username'], 3, "分销管理->分销设置", "编辑分销设置内容");
		}
	}

	message('操作成功', $this->createWebUrl('comsetting'), 'success');
}


include $this->template('comsetting');

?>