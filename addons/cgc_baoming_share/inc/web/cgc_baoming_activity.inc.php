<?php
global $_W, $_GPC;
$settings = $this->module['config'];
load()->func('tpl');
$op = !empty ($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W["uniacid"];
$id = $_GPC['id'];



if ($op == 'display') {
	// include "upgrade.php";
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$con = "uniacid=$uniacid";

	$status = $_GPC['status'];
	if (!empty ($status) || $status == '0') {
		$con .= " and status=$status";
	}

	$total = 0;
	$cgc_baoming_activity = new cgc_baoming_activity();
	$list = $cgc_baoming_activity->getAll($con, $pindex, $psize, $total);
	$pager = pagination($total, $pindex, $psize);
	include $this->template('cgc_baoming_activity');
	exit ();
}

if ($op == 'post') {
	
	ini_set('display_errors', '1');
			error_reporting(E_ALL ^ E_NOTICE);
  
	$id = $_GPC['id'];
	$testlink = $_W['siteroot'] . 'app/' . murl('entry', array (
		'm' => $this->module['name'],
		'do' => 'succ',
		'id' => $id,
		'op' => "test"
	));
	$cgc_baoming_activity = new cgc_baoming_activity();
	if (!empty ($id)) {
		$item = $cgc_baoming_activity->getOne($id);
	}
	
	$item['pay_money'] = !empty ($item['pay_money']) ? $item['pay_money'] : 0;
	
	$item['start_time'] = !empty ($item['start_time']) ? date('Y-m-d H:i:s', $item['start_time']) : date('Y-m-d H:i:s');

	$item['end_time'] = !empty ($item['end_time']) ? date('Y-m-d H:i:s', $item['end_time']) : date('Y-m-d H:i:s', time() + 86400 * 20);

	$item['kj_time'] = !empty ($item['kj_time']) ? date('Y-m-d H:i:s', $item['kj_time']) : date('Y-m-d H:i:s', time() + 86400 * 30);

	if (checksubmit('submit')) {
	  
		if($_GPC['activity_type']=='2' && floatval($_GPC['pay_money'])<=0 ){
			message('支付金额必需大于0！', referer(), 'success');
		}
		
		if ($_GPC['activity_type']==1){
		  $_GPC['pay_num'] =$_GPC['coupon_pay_num'];
		  $_GPC['pay_numed']=$_GPC['coupon_pay_numed'];	
		}

		$data = array (
			"uniacid" => $_W['uniacid'],
			"title" => trim($_GPC['title']),
			"succ_url" => trim($_GPC['succ_url']),
			"status" => trim($_GPC['status']),
			"cj_code" => trim($_GPC['cj_code']),
			"cj_code_start" => trim($_GPC['cj_code_start']),
			"logo" => trim($_GPC['logo']),
			"share_thumb" => trim($_GPC['share_thumb']),
			"share_title" => trim($_GPC['share_title']),
			"share_desc" => trim($_GPC['share_desc']),
			"share_url" => trim($_GPC['share_url']),
			"end_time" => strtotime($_GPC['end_time']),
			"start_time" => strtotime($_GPC['start_time']),
			"kj_time" => strtotime($_GPC['kj_time']),
			"end_url" => trim($_GPC['end_url']),
			"award_mode" => trim($_GPC['award_mode']),
			"award_url" => ($_GPC['award_url']),
			"award_info" => ($_GPC['award_info']),
			"award_chance" => ($_GPC['award_chance']),
			"not_award_url" => trim($_GPC['not_award_url']),
			"not_award_info" => trim($_GPC['not_award_info']),
			"tel_show" => ($_GPC['tel_show']),
			"addr_show" => ($_GPC['addr_show']),
			"wechat_no_show" => trim($_GPC['wechat_no_show']),
			"realname_show" => trim($_GPC['realname_show']),
			"share_guide" => trim($_GPC['share_guide']),
			"share_guide_info" => trim($_GPC['share_guide_info']),
			"code_type" => trim($_GPC['code_type']),
			"bottom_guide" => trim($_GPC['bottom_guide']),
			"iplimit" => trim($_GPC['iplimit']),
			"zdyurl" => trim($_GPC['zdyurl']),
			"locationtype" => trim($_GPC['locationtype']),
			"join_num" => trim($_GPC['join_num']),
			"share_type" => trim($_GPC['share_type']),
			"yq_mode" => trim($_GPC['yq_mode']),
			"max_yq_num" => trim($_GPC['max_yq_num']),
			"index_logo" => trim($_GPC['index_logo']),
			"xl_num" => trim($_GPC['xl_num']),
			"tj_sys" => trim($_GPC['tj_sys']),
			"credit1_sys" => trim($_GPC['credit1_sys']),
			"rule" => trim($_GPC['rule']),
			"candidate_sys" => trim($_GPC['candidate_sys']),
			"my_mode" => trim($_GPC['my_mode']),
			"ewei_shop" => trim($_GPC['ewei_shop']),
			"jh_qrcode" => trim($_GPC['jh_qrcode']),
			"jh_bg" => trim($_GPC['jh_bg']),
			"jh_desc" => trim($_GPC['jh_desc']),
			"new_login_mode" => trim($_GPC['new_login_mode']),
			"must_guanzhu" => trim($_GPC['must_guanzhu']),
			"must_guanzhu_msg" => trim($_GPC['must_guanzhu_msg']),
			"cjm_interval" => trim($_GPC['cjm_interval']),
			"wxtx" => htmlspecialchars_decode(trim($_GPC['wxtx'])),
			"bm_wxtx" => htmlspecialchars_decode(trim($_GPC['bm_wxtx'])),
			"total_zj" => trim($_GPC['total_zj']),
			"jp_mc" => trim($_GPC['jp_mc']),
			"template_id" => trim($_GPC['template_id']),
			"new_wxtx" => (trim($_GPC['new_wxtx'])),
			"pay_money" => $_GPC['pay_money'],			
			"pay_num" => $_GPC['pay_num'],
            "pay_numed" => $_GPC['pay_numed'],
            "pay_time_point" => $_GPC['pay_time_point'],
            "activity_type" => $_GPC['activity_type'],
			"hx_pass" => $_GPC['hx_pass'],
			"redbag_flag" => $_GPC['redbag_flag'],
			"redbag_money"=>$_GPC['redbag_money'],
			"top_desc"=>$_GPC['top_desc'],	
			"whether_random"=>$_GPC['whether_random'],
			"cj_code_forbidden"=>$_GPC['cj_code_forbidden'],	
			"custom1_show"=>$_GPC['custom1_show'],	
			"custom2_show"=>$_GPC['custom2_show'],	
			"custom3_show"=>$_GPC['custom3_show'],	
			"custom1"=>$_GPC['custom1'],	
			"custom2"=>$_GPC['custom2'],	
			"custom3"=>$_GPC['custom3'],	
		    "code_prefix"=>$_GPC['code_prefix'],
		    "draw_num"=>intval($_GPC['draw_num']),
	        "support_return"=>intval($_GPC['support_return']),	
		    "model2_pic"=>($_GPC['model2_pic']),	
		    "model3_desc" => htmlspecialchars_decode(trim($_GPC['model3_desc'])),
		    "gz_qrcode"=>$_GPC['gz_qrcode'],	
		    "gz_gzh"=>$_GPC['gz_gzh'],
		    "friend_send"=>$_GPC['friend_send'],

		);

		if (!empty ($id)) {
			$temp = $cgc_baoming_activity->modify($id, $data);
		} else {
			$data['createtime'] = TIMESTAMP;
			$temp = $cgc_baoming_activity->insert($data);
		}
		message('信息更新成功', $this->createWebUrl('cgc_baoming_activity', array (
			'op' => 'display'
		)), 'success');
	}

	include $this->template('cgc_baoming_activity');
	exit ();
}

if ($op == 'delete') {
	$id = $_GPC['id'];
	$cgc_baoming_activity = new cgc_baoming_activity();
	$cgc_baoming_activity->delete($id);
	message('删除成功！', referer(), 'success');
}

if ($op == 'delete_all') {
	$cgc_baoming_activity = new cgc_baoming_activity();
	$cgc_baoming_activity->deleteAll();
	message('删除成功！', $this->createWebUrl('cgc_baoming_activity', array (
		'op' => 'display'
	)), 'success');
}

if ($op == 'enter') {
	$id = $_GPC['id'];
	include $this->template('cgc_baoming_enter');
	exit ();
}