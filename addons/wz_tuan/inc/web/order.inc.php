<?php
global $_W, $_GPC;
$this -> backlists();
//$this->myconstruct();
load() -> func('tpl');
checklogin();
$this -> checkmode();
$gettime = $this -> module['config']['gettime'];//自动签收时间
$weid = $_W['uniacid'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	//更新团状态
	$this->updategourp();
	//更新团状态
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = "  uniacid = :weid";
	$paras = array(':weid' => $_W['uniacid']);

	$status = $_GPC['status'];
	$transid = $_GPC['transid'];
	$pay_type = $_GPC['pay_type'];
	$keyword = $_GPC['keyword'];
	$member = $_GPC['member'];
	$time = $_GPC['time'];

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) ;
		$condition .= " AND  createtime >= :starttime AND  createtime <= :endtime ";
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	if (!empty($_GPC['transid'])) {

		$condition .= " AND  transid =  '{$_GPC['transid']}'";
	}
	if (!empty($_GPC['pay_type'])) {

		$condition .= " AND  pay_type = '{$_GPC['pay_type']}'";
	} elseif ($_GPC['pay_type'] === '0') {
		$condition .= " AND  pay_type = '{$_GPC['pay_type']}'";
	}
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND  orderno LIKE '%{$_GPC['keyword']}%'";
	}
	if (!empty($_GPC['member'])) {
		$condition .= " AND (addname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%')";
	}
	if ($status != '') {
		$condition .= " AND  status = '" . intval($status) . "'";
		if($status==4){
			$allnogettime = pdo_fetchall("select * from".tablename('wz_tuan_order')."where gettime='' and uniacid='{$_W['uniacid']}' and status=3");
			if(empty($gettime)){
				$gettime = 5;
			}
			$now = time();
			foreach($allnogettime as $key =>$value){
				$shouldgettime = $value['sendtime']+$gettime*24*3600;
				if($shouldgettime<$now){
					pdo_update('wz_tuan_order',array('gettime'=>$shouldgettime,'status'=>4),array('id'=>$value['id']));
				}
			}
			
		}
	}
	$sql = "select  * from " . tablename('wz_tuan_order') . " where $condition and mobile<>'虚拟' and is_hexiao=0 ORDER BY createtime DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$list = pdo_fetchall($sql, $paras);
	$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'info', 'name' => '余额支付'), '2' => array('css' => 'success', 'name' => '在线支付'), '3' => array('css' => 'warning', 'name' => '货到付款'));
	$orderstatus = array('0' => array('css' => 'default', 'name' => '待付款'), '1' => array('css' => 'info', 'name' => '已付款'), '2' => array('css' => 'warning', 'name' => '待发货'), '3' => array('css' => 'success', 'name' => '已发货'), '4' => array('css' => 'success', 'name' => '已签收'), '5' => array('css' => 'default', 'name' => '已取消'), '6' => array('css' => 'danger', 'name' => '待退款'), '7' => array('css' => 'default', 'name' => '已退款'));
	foreach ($list as $key => $value) {
		$s = $value['status'];
		$list[$key]['statuscss'] = $orderstatus[$value['status']]['css'];
		$list[$key]['status'] = $orderstatus[$value['status']]['name'];
		$list[$key]['css'] = $paytype[$value['pay_type']]['css'];
		if ($value['pay_type'] == 2) {
			if (empty($value['transid'])) {
				$list[$key]['paytype'] = '微信支付';
			} else {
				$list[$key]['paytype'] = '微信支付';
			}
		} else {
			$list[$key]['paytype'] = $paytype[$value['pay_type']]['name'];
		}
		$goodsss = pdo_fetch("select gname,gimg from" . tablename('wz_tuan_goods') . "where id = '{$value['g_id']}'");
		$list[$key]['gname'] = $goodsss['gname'];
		$list[$key]['gimg'] = $goodsss['gimg'];
	}
	$all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and mobile<>'虚拟' and is_hexiao=0 ");
	$status0 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=0 and mobile<>'虚拟' and is_hexiao=0");
	$status1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=1 and mobile<>'虚拟' and is_hexiao=0");
	$status2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=2 and mobile<>'虚拟' and is_hexiao=0");
	$status3 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=3 and mobile<>'虚拟' and is_hexiao=0");
	$status4 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=4 and mobile<>'虚拟' and is_hexiao=0");
	$status5 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=5 and mobile<>'虚拟' and is_hexiao=0");
	$status6 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=6 and mobile<>'虚拟' and is_hexiao=0");
	$status7 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=7 and mobile<>'虚拟' and is_hexiao=0");
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE $condition and mobile<>'虚拟' and is_hexiao=0", $paras);
	$pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}'  and mobile<>'虚拟' and is_hexiao=0");
$status0 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=0 and mobile<>'虚拟' and is_hexiao=0");
	$status1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=1 and mobile<>'虚拟' and is_hexiao=0");
	$status2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=2 and mobile<>'虚拟' and is_hexiao=0");
	$status3 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=3 and mobile<>'虚拟' and is_hexiao=0");
	$status4 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=4 and mobile<>'虚拟' and is_hexiao=0");
	$status5 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=5 and mobile<>'虚拟' and is_hexiao=0");
	$status6 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=6 and mobile<>'虚拟' and is_hexiao=0");
	$status7 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=7 and mobile<>'虚拟' and is_hexiao=0");
	$item = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_order') . " WHERE id = :id", array(':id' => $id));
	if ($item['status'] == 7) {
		$refund_record = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_refund_record') . " WHERE orderid = :id", array(':id' => $id));
	}
	if (empty($item)) {
		message("抱歉，订单不存在!", referer(), "error");
	}

	if (checksubmit('editstatus')) {
		if ($_GPC['thestatus']=='') {
			message('请选择订单状态！');
		} else {
			pdo_update('wz_tuan_order', array('status'=>$_GPC['thestatus']), array('id' => $id));
		}
		message('修改成功！', referer(), 'success');
	}
	if (checksubmit('getgoods')) {
		if ($_GPC['recvname']=='' || $_GPC['recvmobile']=='' || $_GPC['recvaddress']=='' || $_GPC['addresstype']=='') {
			message('请输入完整信息！');
		} else {
			pdo_update('wz_tuan_order', array('addname' => $_GPC['recvname'], 'mobile' => $_GPC['recvmobile'], 'address' => $_GPC['recvaddress'],'addresstype'=>$_GPC['addresstype']), array('id' => $id));
		}
		message('修改成功！', referer(), 'success');
	}
	if (checksubmit('confirmsend')) {
		if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
			message('请输入快递单号！');
		}
		pdo_update('wz_tuan_order', array('status' => 3, 'express' => $_GPC['express'], 'expresssn' => $_GPC['expresssn'], 'sendtime' => TIMESTAMP), array('id' => $id));
		//发货提醒
		require_once IA_ROOT . '/addons/wz_tuan/source/Message.class.php';
		load() -> model('account');
		$access_token = WeAccount::token();
		$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
		$url2 = '';
		$sendmessage = new Message();
		$res = $sendmessage -> send_success($item['orderno'], $item['openid'], $_GPC['express'], $_GPC['expresssn'], $this, $url1, $url2);
		//发货提醒
		message('发货操作成功！', referer(), 'success');
	}
	if (checksubmit('cancelsend')) {
		pdo_update('wz_tuan_order', array('status' => 2), array('id' => $id));
		message('取消发货操作成功！', referer(), 'success');
	}
	if (checksubmit('refund')) {
		
		$refund_id = $_GPC['refund_id'];
		//页面获取的退款订单号
		$refund_ids = pdo_fetch("select * from" . tablename('wz_tuan_order') . "where id='{$refund_id}'");
		$res=$this->refund($refund_ids['orderno'],'',2);
		if($res=='success'){
			message('退款成功了！', referer(), 'success');
		} else {
			message('退款失败，服务器正忙，请稍等等！', referer(), 'fail');
		}
	}
	if (checksubmit('confrimpay')) {
		pdo_update('wz_tuan_order', array('status' => 1, 'pay_type' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
		// //设置库存
		// $this->setOrderStock($id);
		message('确认订单付款操作成功！', referer(), 'success');
	}
	$item['user'] = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_address') . " WHERE id = {$item['addressid']}");
	$goods = pdo_fetchall("select * from" . tablename('wz_tuan_goods') . "WHERE id={$item['g_id']}");
	$item['goods'] = $goods;
} elseif ($operation == 'delete') {
	/*订单删除*/
	$orderid = intval($_GPC['id']);
	if (pdo_delete('wz_tuan_order', array('id' => $orderid))) {
		message('订单删除成功', $this -> createWebUrl('order', array('op' => 'display')), 'success');
	} else {
		message('订单不存在或已被删除', $this -> createWebUrl('order', array('op' => 'display')), 'error');
	}

}elseif($operation == 'tuikuan') {
		/*处理退款*/
				$checkboxs = $_GPC['checkbox'];
				
				$success_num =0;
				$fail_num =0;
				foreach($checkboxs as$k=>$value){
					$refund_ids = pdo_fetch("select * from".tablename('wz_tuan_order')."where id='{$value}'");
					
					$res = $this->refund($refund_ids['orderno'],'',2);
					
					if($res == 'success'){
						$success_num+=1;
					}else{
						$fail_num+=1;
					}
				}
				message('退款操作成功，成功！'.$success_num.'人,失败'.$fail_num.'人', referer(), 'success');
		/*处理退款*/
} elseif ($operation == 'refundall') {
	include_once '../addons/wz_tuan/WxPay.Api.php';
	$WxPayApi = new WxPayApi();

	load() -> func('communication');
	load() -> model('account');
	$accounts = uni_accounts();
	$allorders = pdo_fetchall("select * from" . tablename('wz_tuan_order') . "where uniacid={$_W['uniacid']} and status = 1");
	$now = time();
	$num = 0;
	foreach ($allorders as $ke => $value) {
		$endtime = $value['endtime'];
		if ($now - $value['starttime'] > $endtime * 3600 && $value['transid'] != '' && $value['success'] == 1) {
			$num++;
			$fee = $value['price'] * 100;
			$refundid = $value['transid'];
			$acid = $_W['uniacid'];
			$path_cert = IA_ROOT . '/addons/wz_tuan/cert/' . $_W['uniacid'] . '/apiclient_cert.pem';
			//证书路径
			$path_key = IA_ROOT . '/addons/wz_tuan/cert/' . $_W['uniacid'] . '/apiclient_key.pem';
			//证书路径
			$key = $this -> module['config']['apikey'];
			//商户支付秘钥（API秘钥）
			$appid = $accounts[$acid]['key'];
			//身份标识（appid）
			$mchid = $this -> module['config']['mchid'];
			//微信支付商户号(mchid)
			/*$input：退款必须要的参数*/
			$input = new WxPayRefund();
			$input -> SetAppid($appid);
			$input -> SetMch_id($mchid);
			$input -> SetOp_user_id($mchid);
			$input -> SetOut_refund_no($mchid . date("YmdHis"));
			$input -> SetRefund_fee($fee);
			$input -> SetTotal_fee($fee);
			$input -> SetTransaction_id($refundid);
			$result = $WxPayApi -> refund($input, 6, $path_cert, $path_key, $key);
			if ($result['return_code'] == 'SUCCESS') {
				pdo_update('wz_tuan_order', array('status' => 4), array('id' => $value['id']));
				pdo_query("update" . tablename('wz_tuan_goods') . " set gnum=gnum+1 where id = '{$value['g_id']}'");
			}

		}
	}
	if ($num == 0) {
		message('未找到已付款且团购过期的微信订单。', referer(), 'fail');
	} else {
		message('一键退款成功！共处理了' . $num . '个订单。', referer(), 'success');
	}
}elseif($operation == 'output'){
	
	$status = $_GPC['status'];
	$keyword = $_GPC['keyword'];
	$member = $_GPC['member'];
	$time = $_GPC['time'];
	$transid = $_GPC['transid'];
	$paytype = $_GPC['pay_type'];
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']);
	$condition = " uniacid='{$_W['uniacid']}' ";
	if ($status != '') {
		$condition .= " AND status= '{$status}' ";
	}
	if ($keyword != '') {
		$condition .= " AND g_id = '{$keyword}'";
	}
	if (!empty($member)) {
		$condition .= " AND (addname LIKE '%{$member}%' or mobile LIKE '%{$member}%')";
	}
	if (!empty($time)) {
		$condition .= " AND  createtime >= $starttime AND  createtime <= $endtime  ";
	}
	if (!empty($transid)) {

		$condition .= " AND  transid =  '{$transid}'";
	}
	if (!empty($paytype)) {
		$condition .= " AND  pay_type = '{$paytype}'";
	}
	$orders = pdo_fetchall("select * from" . tablename('wz_tuan_order') . "where $condition");
	switch($status){
		case NULL: 
		$str = '全部订单_' . time();
		break;
		case 1: 
		$str = '已支付订单_' . time();
		break;
		case 2: 
		$str = '待发货订单' . time();
		break;
		case 3: 
		$str = '已发货订单' . time();
		break;
		case 4:
		$str = '已签收订单' . time();
		break;
		case 5:
		$str = '已取消订单' . time();
		break;
		case 6: 
		$str = '待退款订单' . time();
		break;
		case 7:
		$str = '已退款订单' . time();
		break;
		default:
		$str = '待支付订单' . time();break;
	}
	

	/* 输入到CSV文件 */
	$html = "\xEF\xBB\xBF";
	/* 输出表头 */
	$filter = array('aa' => '订单编号', 'bb' => '姓名', 'cc' => '电话', 'dd' => '总价(元)', 'ee' => '状态', 'ff' => '下单时间', 'gg' => '商品名称', 'hh' => '收货地址', 'ii' => '微信订单号', 'jj' => '快递单号', 'kk' => '快递名称','ll'=>'地址类型');
	foreach ($filter as $key => $title) {
		$html .= $title . "\t,";
	}
	$html .= "\n";
	foreach ($orders as $k => $v) {
		if ($v['status'] == '0') {
			$thisstatus = '未支付';
		}
		if ($v['status'] == '1') {
			$thisstatus = '已支付';
		}
		if ($v['status'] == '2') {
			$thisstatus = '待发货';
		}
		if ($v['status'] == '3') {
			$thisstatus = '已发货';
		}
		if ($v['status'] == '4') {
			$thisstatus = '已签收';
		}
		if ($v['status'] == '5') {
			$thisstatus = '已取消';
		}
		if ($v['status'] == '6') {
			$thisstatus = '待退款';
		}
		if ($v['status'] == '7') {
			$thisstatus = '已退款';
		}
		if ($v['status'] == '') {
			$thisstatus = '全部订单';
		}
		$thistatus = '待发货';
		$goods = pdo_fetch("select * from" . tablename('wz_tuan_goods') . "where id = '{$v['g_id']}' and uniacid='{$_W['uniacid']}'");
		$time = date('Y-m-d H:i:s', $v['createtime']);
		if($v['addresstype']==1){
			$addresstype='公司';
		}else{
			$addresstype='家庭';
		}
		
		$orders[$k]['aa'] = $v['orderno'];
		$orders[$k]['bb'] = $v['addname'];
		$orders[$k]['cc'] = $v['mobile'];
		$orders[$k]['dd'] = $v['price'];
		$orders[$k]['ee'] = $thisstatus;
		$orders[$k]['ff'] = $time;
		$orders[$k]['gg'] = $goods['gname'];
		$orders[$k]['hh'] = $v['address'];
		$orders[$k]['ii'] = $v['transid'];
		$orders[$k]['jj'] = $v['expresssn'];
		$orders[$k]['kk'] = $v['express'];
		$orders[$k]['ll'] = $addresstype;
		foreach ($filter as $key => $title) {
			$html .= $orders[$k][$key] . "\t,";
		}
		$html .= "\n";
	}
	/* 输出CSV文件 */
	header("Content-type:text/csv");
	header("Content-Disposition:attachment; filename={$str}.csv");
	echo $html;
	exit();
	
	
}
include $this -> template('web/order');
?>