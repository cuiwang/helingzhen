<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/download.php";

global $_W, $_GPC;
$settings = $this->module['config'];
load()->func('tpl');
$op = !empty ($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W["uniacid"];
$id = $_GPC['id'];
if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$con = "uniacid=$uniacid";

	$cgc_baoming_activity = new cgc_baoming_activity();
	$activity_id = $_GPC['activity_id'];
	if (!empty ($activity_id)) {
		$activity = $cgc_baoming_activity->getOne($activity_id);
	}

	if (!empty ($activity_id)) {
		$con .= " and  activity_id= $activity_id";
	}

	$cj_code = $_GPC['cj_code'];

	if (!empty ($cj_code)) {
		$con .= " and cj_code like '%$cj_code%'";
	}

	$nickname = $_GPC['nickname'];

	if (!empty ($nickname)) {
		$con .= " and nickname like '%$nickname%'";
	}

	$share_status = $_GPC['share_status'];

	if (!empty ($share_status) || $share_status == '0') {
		$con .= " and share_status=$share_status";
	}

	$zj_status = $_GPC['zj_status'];
	if (!empty ($zj_status) || $zj_status == '0') {
		$con .= " and zj_status=$zj_status";
	}
	
	$is_pay= $_GPC['is_pay'];
	if (!empty ($is_pay) || $is_pay == '0') {
		$con .= " and is_pay=$is_pay";
	}

	$yq_type = $_GPC['yq_type'];
	if (!empty ($yq_type) || $yq_type == '0') {
		$con .= " and yq_type=$yq_type";
	}

	$total = 0;
	$cgc_baoming_user = new cgc_baoming_user();

	if (isset ($_GPC['export'])) {
		ini_set('memory_limit', '512M');
		$list = $cgc_baoming_user->getAll($con, 1, 1000000, $total);
		download_user_csv($list,$activity['activity_type']);
		exit ();
	}

	$list = $cgc_baoming_user->getAll($con, $pindex, $psize, $total);

	$pager = pagination($total, $pindex, $psize);
	include $this->template('cgc_baoming_user');
	exit ();
}

if ($op == 'zj_msg') {
	$id = $_GPC['id'];
	$cgc_baoming_user = new cgc_baoming_user();
	if (!empty ($id)) {
		$user = $cgc_baoming_user->getOne($id);
	}

	if (empty ($user['zj_status'])) {
		message("没中奖");
	}
	$cgc_baoming_activity = new cgc_baoming_activity();

	$item = $cgc_baoming_activity->getOne($user['activity_id']);

	if (empty($item['template_id'])) {
		message("模板消息没有", referer(), "error");
	}

	$item['openid'] = $user['openid'];
	$_url = murl('entry', array (
		'm' => $this->module['name'],
		'do' => 'enter',
		'id' => $user['activity_id'],
		'form' => 'result'
	), true, true);
	$a = sendTemplate($item, $_url);
	if ($a == 1) {
		message("发送成功", referer(), "success");
	}



}
if ($op == 'post') {
	$id = $_GPC['id'];
	$cgc_baoming_user = new cgc_baoming_user();
	if (!empty ($id)) {
		$item = $cgc_baoming_user->getOne($id);
	}
	if (checksubmit('submit')) {
		$data = array (
			"uniacid" => $_W['uniacid'],
			"activity_id" => trim($_GPC['activity_id']),
			"openid" => $_GPC['openid'],
			"nickname" => $_GPC['nickname'],
			"headimgurl" => $_GPC['headimgurl'],
			"share_status" => $_GPC['share_status'],
			"status" => $_GPC['status'],
			"tel" => $_GPC['tel'],
			"zj_status" => $_GPC['zj_status'],
			"cj_code" => $_GPC['cj_code'],
			"hx_status" => $_GPC['hx_status'],
            "is_redbag" => $_GPC['is_redbag'],
			"createtime" => TIMESTAMP,
			
		);

		if (!empty ($id)) {
			$temp = $cgc_baoming_user->modify($id, $data);
		} else {
			$temp = $cgc_baoming_user->insert($data);
		}
		message('信息更新成功', $this->createWebUrl('cgc_baoming_user', array (
			'op' => 'display',
			'activity_id' => $_GPC['activity_id']
		)), 'success');
	}

	include $this->template('cgc_baoming_user');
	exit ();
}

if ($op == 'zj_status') {
	$id = $_GPC['id'];
	$cgc_baoming_user = new cgc_baoming_user();
	$data = array (
		"zj_status" => 1
	);

	if (!empty ($id)) {
		$temp = $cgc_baoming_user->modify($id, $data);
	}

	message('更新成功', referer(), 'success');
}


if($op=='send_redbag'){
	$id = $_GPC['id'];
	$activity_id = $_GPC['activity_id'];
	
	$this->send_redbag($id,$settings);
	
	message('红包发送成功!', referer(), 'success');
}

if ($op == 'delete') {
	$id = $_GPC['id'];
	$cgc_baoming_user = new cgc_baoming_user();
	$cgc_baoming_user->delete($id);
	message('删除成功！', referer(), 'success');
}

if ($op == 'delete_all') {
	$id = $_GPC['activity_id'];
	$cgc_baoming_user = new cgc_baoming_user();
	$cgc_baoming_user->deleteAll($id);
	message('删除成功！', $this->createWebUrl('cgc_baoming_user', array (
		'op' => 'display',
		'activity_id' => $_GPC['activity_id']
	)), 'success');
}

if ($op=='batch_zj') {
     $ids=$_GPC['id'];		
     if (empty($ids)){
      	message('信息不能为空.');
	 }
	 
	 $cgc_baoming_user = new cgc_baoming_user();
	 $data = array ("zj_status" => 1);
	 foreach ($ids as $id){	
		if (!empty ($id)) {
			$temp = $cgc_baoming_user->modify($id, $data);
		}	
	 }
    message('设置成功',$this->createWebUrl('cgc_baoming_user', array('op' => 'display','activity_id' => $_GPC['activity_id'])), 'success');
 }
 
if ($op=='batch_redbag') {
     $ids=$_GPC['id'];		
     if (empty($ids)){
      	message('信息不能为空.');
	 }
	 
	 foreach ($ids as $id){	
		if (!empty ($id)) {
			$this->send_redbag($id, $settings);
		}	
	 }
    message('红包发送成功',$this->createWebUrl('cgc_baoming_user', array('op' => 'display','activity_id' => $_GPC['activity_id'])), 'success');
 }
  	

if ($op == 'import') {

	$cgc_baoming_activity = new cgc_baoming_activity();
	$activity = $cgc_baoming_activity->getOne($_GPC['activity_id']);
	$_GPC['cj_code_start'] = $activity['cj_code_start'];

	$file = $_FILES["upfile"];

	if (end(explode('.', $file['name'])) != "csv") {
		message('请导入csv文件！', referer(), 'error');
	}

	import_csv($file['tmp_name']);
}

if ($_GPC['op'] == "gen_example") {
	$list = mc_fansinfo_all();
	download_user_example($list);
	exit ();
}

function import_csv($file_name) {
	global $_W, $_GPC;
	$weid = $_W['uniacid'];
	$file = fopen($file_name, 'r');
	$i = $j = 0;

	while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容

		$i++;
		if ($i == 1) {
			continue;
		}
		$tel_list[] = $data;
		$j++;
		if ($j >= 1000) {
			$sql = import_data($tel_list);
			pdo_query($sql);
			$tel_list = array ();
			$j = 0;
		}
	}

	if ($j != 0) {
		$sql = import_data($tel_list);
		pdo_query($sql);
	}
	fclose($file);

	message('导入成功！', referer(), 'success');
}

function import_data($data) {
	global $_W, $_GPC;
	$weid = $_W['uniacid'];
	$zz = count($data);

	$i = 0;
	$sqldata = "";
	setlocale(LC_ALL, 'zh_CN');

	foreach ($data as $key => $arr) {

		if (empty ($arr[3])) {
			$cj_code = getNextAvaliableCjcode($_GPC['activity_id'], $_GPC['cj_code_start']);
		} else {
			$cj_code = $arr[3];
		}

		$encode = mb_detect_encoding($arr[1], array (
			"ASCII",
			"UTF-8",
			"GB2312",
			"GBK"
		));

		if ($encode != "UTF-8") {
			$arr[1] = iconv("GBK", "UTF-8", $arr[1]);
		}
		$data = array (
			'uniacid' => $weid,
			'activity_id' => $_GPC['activity_id'],
			'openid' => $arr[0],
			'nickname' => trim($arr[1]),
			"headimgurl" => trim($arr[2]),
			"share_status" => 1,
			"cj_code" => $cj_code,
			'createtime' => time()
		);

		$i++;
		$headerdata = "insert into " . tablename("cgc_baoming_user") . "(" . implode(",", array_keys($data)) . ") values";
		if ($zz == $i) {
			$sqldata .= "('" . implode("','", array_values($data)) . "');";
		} else {
			$sqldata .= "('" . implode("','", array_values($data)) . "'),";
		}
	}

	return $headerdata . $sqldata;

}

if ($op == 'batch_zj_msg') {
	$id = $_GPC['id'];
	$cgc_baoming_user = new cgc_baoming_user();
	if (!empty ($id)) {
		$user = $cgc_baoming_user->getOne($id);
	}

	if (empty ($user['zj_status'])) {
		message("没中奖");
	}

	$cgc_baoming_activity = new cgc_baoming_activity();

	$item = $cgc_baoming_activity->getOne($user['activity_id']);

	if (empty ($item['template_id'])) {
		message("模板消息没有", referer(), "error");
	}

	$item['openid'] = $user['openid'];
	$_url = murl('entry', array (
		'm' => $this->module['name'],
		'do' => 'enter',
		'id' => $user['activity_id'],
		'form' => 'result'
	), true, true);
	$a = sendTemplate($item, $_url);
	if ($a == 1) {
		message("发送成功", referer(), "success");
	}

	

}

if ($op == 'batch_zj_msg2') {
	$ids=$_GPC['id'];		
    if (empty($ids)){
      	message('信息不能为空.');
	}
	
	$cgc_baoming_user = new cgc_baoming_user();
	$cgc_baoming_activity = new cgc_baoming_activity();
	
	$item = $cgc_baoming_activity->getOne( $_GPC['activity_id']);
	
	if (empty ($item['template_id'])) {
		message("模板消息没有",$this->createWebUrl('cgc_baoming_user', array('op' => 'display','activity_id' => $_GPC['activity_id'])), "error");
	}
	
	$j=0;
 	foreach ($ids as $id){	
		if (!empty ($id)) {
			$user = $cgc_baoming_user->getOne($id);
		}
		if (!empty($user['zj_status'])) {
			$item['openid'] = $user['openid'];
			$_url = murl('entry', array (
				'm' => $this->module['name'],
				'do' => 'enter',
				'id' => $user['activity_id'],
				'form' => 'result'
			), true, true);
			$a = sendTemplate($item, $_url);
			if ($a== 1) {
				$j++;
				//message('发送失败'.$user['nickname'],$this->createWebUrl('cgc_baoming_user', array('op' => 'display','activity_id' => $_GPC['activity_id'])), 'success');
			}
		}
 	}
    message('发送成功'.$j."条",$this->createWebUrl('cgc_baoming_user', array('op' => 'display','activity_id' => $_GPC['activity_id'])), 'success');

}