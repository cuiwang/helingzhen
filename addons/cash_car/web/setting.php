<?php
/**
 * 基本设置
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
$weid = $this->_weid;
$action = 'setting';
$title = '网站设置';

load()->func('tpl');

//banner图
$banner = unserialize($setting['banner']);

//支付方式开关
$payswitch = unserialize($setting['paytype']);

//分享信息
$sharelink = unserialize($setting['sharelink']);

$refuse = unserialize($setting['refuseorder']);

if (checksubmit('submit')) {
	$data = array(
		'weid'             => $_W['uniacid'],          //公众号id
		'istplnotice'      => intval($_GPC['istplnotice']), //是否开启模版消息
		'wmessage'         => trim($_GPC['wmessage']),   //师傅使用模版消息id
		'umessage'         => trim($_GPC['umessage']),   //用户下单成功通知模版消息id
		'cmessage'         => trim($_GPC['cmessage']),   //用户取消订单通知师傅模版消息id
		'title'            => trim($_GPC['title']),    //网站名称
		'book_days'        => trim($_GPC['book_days'])?trim($_GPC['book_days']):'2',//可提前预约天数
		'hours_time'       => trim($_GPC['hours_time'])?trim($_GPC['hours_time']):'3',//每个时间段最大订单量
		'radius'           => trim($_GPC['radius'])?trim($_GPC['radius']):'500',//允许下单最长距离
		'coords_time'      => trim($_GPC['coords_time'])?trim($_GPC['coords_time']):'600',//刷新用户位置时间
		'is_give'          => intval($_GPC['is_give']),//是否允许转赠洗车卡
		'store_model'      => intval($_GPC['store_model']),//1.单店模式  2.多店模式
		'banner'           => serialize($_GPC['banner']),//app端首页banner
		'evaluate_num'     => intval($_GPC['evaluate_num'])?intval($_GPC['evaluate_num']):'25',
		'check_space'      => intval($_GPC['check_space']),
		'smsurl'		   => trim($_GPC['smsurl']),
		'dateline'         => time(),
	);

	/* 支付方式控制 */
	$paytype = array(
		'wechat'   => intval($_GPC['wechat']),
		'alipay'   => intval($_GPC['alipay']),
		'unionpay' => intval($_GPC['unionpay']),
		'baifubao' => intval($_GPC['baifubao']),
	);
	$data['paytype'] = serialize($paytype);

	/* 洗车工超时未接单自动取消间隔 */
	$data['refuseorder'] = serialize(array('refusespace'=>intval($_GPC['refusespace']),'uptime'=>$refuse['uptime']));

	/* 变更营业模式则清空购物车表 */
	if($setting['store_model'] != $_GPC['store_model']){
		pdo_delete($this->table_cart);
	}

	/* 分享信息 */
	$sharelink = array(
		'title'  => trim($_GPC['sharelinktitle']),
		'desc'   => trim($_GPC['sharelinkdesc']),
		'images' => trim($_GPC['sharelinkimg']),
	);
	$data['sharelink'] = serialize($sharelink);

	if (empty($setting)) {
		pdo_insert($this->table_setting, $data);
	} else {
		unset($data['dateline']);
		//清空token缓存
		pdo_update('core_cache', array('value'=>null), array('key'=>"accesstoken:".$_W['acid']));
		pdo_update($this->table_setting, $data, array('weid' => $_W['uniacid']));
	}

	message('操作成功', $this->createWebUrl('setting'), 'success');
}

include $this->template('setting');