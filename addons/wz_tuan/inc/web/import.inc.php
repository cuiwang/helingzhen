<?php
global $_W, $_GPC;
load() -> func('tpl');
checklogin();
$this -> backlists();
$this -> checkmode();
$weid = $_W['uniacid'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = "  uniacid = :weid";
	$paras = array(':weid' => $_W['uniacid']);

	$status = $_GPC['status'];
	$keyword = $_GPC['keyword'];
	//商品ID
	$member = $_GPC['member'];
	//电话、姓名
	$time = $_GPC['time'];
	//下单时间

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$condition .= " AND  createtime >= :starttime AND  createtime <= :endtime ";
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND  g_id='{$_GPC['keyword']}'";
	}
	if (!empty($_GPC['member'])) {
		$condition .= " AND (addname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%')";
	}
	if ($status!='') {
		if ($status == 1) {
			$condition .= " AND is_tuan=1 ";
		} else {
			$condition .= " AND is_tuan=0 ";
		}

	}
	$condition .= " AND status=2 ";
	$sql = "select  * from " . tablename('wz_tuan_order') . " where $condition and is_hexiao=0 ORDER BY createtime DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$list = pdo_fetchall($sql, $paras);
	$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'info', 'name' => '余额支付'), '2' => array('css' => 'success', 'name' => '在线支付'), '3' => array('css' => 'warning', 'name' => '货到付款'));
	$orderstatus = array('0' => array('css' => 'default', 'name' => '待付款'), '1' => array('css' => 'info', 'name' => '已付款'), '2' => array('css' => 'warning', 'name' => '待发货'), '3' => array('css' => 'success', 'name' => '已发货'), '4' => array('css' => 'success', 'name' => '已签收'), '5' => array('css' => 'success', 'name' => '已取消'), '6' => array('css' => 'danger', 'name' => '待退款'), '7' => array('css' => 'success', 'name' => '已退款'));
	foreach ($list as &$value) {
		$s = $value['status'];
		$value['statuscss'] = $orderstatus[$value['status']]['css'];
		$value['status'] = $orderstatus[$value['status']]['name'];
		$value['css'] = $paytype[$value['pay_type']]['css'];
		if ($value['pay_type'] == 2) {
			if (empty($value['transid'])) {
				$value['paytype'] = '微信支付';
			} else {
				$value['paytype'] = '微信支付';
			}
		} else {
			$value['paytype'] = $paytype[$value['pay_type']]['name'];
		}
		$goodsss = pdo_fetch("select * from" . tablename('wz_tuan_goods') . "where id = '{$value['g_id']}'");
		$value['freight'] = $goodsss['freight'];
	}
	$total_tuan = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=2 and is_tuan=1 and is_hexiao=0", $paras);
	$total_single = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=2 and is_tuan=0 and is_hexiao=0", $paras);
	$total_all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE uniacid='{$_W['uniacid']}' and status=2 and is_hexiao=0");
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order') . " WHERE $condition and is_hexiao=0", $paras);
	$pager = pagination($total, $pindex, $psize);

} elseif ($operation == 'import') {
	$file = $_FILES['fileName'];
	$max_size = "2000000";
	$fname = $file['name'];
	$ftype = strtolower(substr(strrchr($fname, '.'), 1));
	//文件格式
	$uploadfile = $file['tmp_name'];

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (is_uploaded_file($uploadfile)) {
			if ($file['size'] > $max_size) {
				echo "Import file is too large";
				exit ;
			}
			if ($ftype == 'xls') {
				require_once '../framework/library/phpexcel/PHPExcel.php';
				$objReader = PHPExcel_IOFactory::createReader('Excel5');
				$objPHPExcel = $objReader -> load($uploadfile);
				$sheet = $objPHPExcel -> getSheet(0);
				$highestRow = $sheet -> getHighestRow();
				$succ_result = 0;
				$error_result = 0;
				for ($j = 2; $j <= $highestRow; $j++) {
					$orderNo = trim($objPHPExcel -> getActiveSheet() -> getCell("A$j") -> getValue());
					$expressOrder = trim($objPHPExcel -> getActiveSheet() -> getCell("J$j") -> getValue());
					$expressName = trim($objPHPExcel -> getActiveSheet() -> getCell("K$j") -> getValue());
					if (!empty($expressOrder) && !empty($expressName)) {
						$res = pdo_update('wz_tuan_order', array('status' => 3, 'express' => $expressName, 'expresssn' => $expressOrder,'sendtime'=>TIMESTAMP), array('orderno' => $orderNo));
						if ($res) {
							$succ_result += 1;
						} else {
							$error_result += 1;
						}
						$order = pdo_fetch("select * from" . tablename('wz_tuan_order') . "where orderno ='{$orderNo}'");

						/*发货提醒消息模板*/
						require_once IA_ROOT . '/addons/wz_tuan/source/Message.class.php';
						load() -> model('account');
						$access_token = WeAccount::token();
						$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
						$url2 = '';
						$sendmessage = new Message();
						$res = $sendmessage -> send_success($orderNo, $order['openid'], $expressName, $expressOrder, $this, $url1, $url2);
						/*发货提醒模板消息*/
					} else {
						if (!empty($orderNo)) {
							$error_result += 1;
						}
					}
				}
			}elseif ($ftype == 'csv') {
			    if (empty ($uploadfile)) { 
			        echo '请选择要导入的CSV文件！'; 
			        exit; 
			    } 
			    $handle = fopen($uploadfile, 'r'); 
				$n = 0; 
			    while ($data = fgetcsv($handle, 10000)) { 
			        $num = count($data); 
			        for ($i = 0; $i < $num; $i++) { 
			            $out[$n][$i] = $data[$i]; 
			        } 
			        $n++; 
			    } 
			    $result = $out; //解析csv 
			    $len_result = count($result); 
			    if($len_result==0){ 
			        echo '没有任何数据！'; 
			        exit; 
			    } 
				$succ_result = 0;
				$error_result = 0;
			    for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值 
			        $orderNo = trim(iconv('gb2312', 'utf-8', $result[$i][0])); //中文转码 
			        if($orderNo==''){			
			        	continue;
			        }
			       
			        $expressOrder = trim(iconv('gb2312', 'utf-8', $result[$i][9])); 
			        $expressName =trim(iconv('gb2312', 'utf-8', $result[$i][10]));
				
					if (!empty($expressOrder) && !empty($expressName)) {
						$res = pdo_update('wz_tuan_order', array('status' => 3, 'express' => $expressName, 'expresssn' => $expressOrder,'sendtime'=>TIMESTAMP), array('orderno' => $orderNo));
						if ($res) {
							$succ_result += 1;
						} else {
							$error_result += 1;
						}
						$order = pdo_fetch("select * from" . tablename('wz_tuan_order') . "where orderno ='{$orderNo}'");
						/*发货提醒消息模板*/
						require_once IA_ROOT . '/addons/wz_tuan/source/Message.class.php';
						load() -> model('account');
						$access_token = WeAccount::token();
						$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
						$url2 = '';
						$sendmessage = new Message();
						$res = $sendmessage -> send_success($orderNo, $order['openid'], $expressName, $expressOrder, $this, $url1, $url2);
						/*发货提醒模板消息*/
					}else{
							$error_result += 1;
					}
			    } 
			    fclose($handle); //关闭指针 
			}else{
				echo "文件后缀格式必须为xls或csv";
				exit ;
			}
		} else {
			echo "文件名不能为空!";
			exit ;
		}
	}
	message('导入发货订单操作成功！成功' . $succ_result . '条，失败' . $error_result . '条', referer(), 'success');
} elseif ($operation == 'output') {
	$status = $_GPC['status'];
	$keyword = $_GPC['keyword'];
	$member = $_GPC['member'];
	$time = $_GPC['time'];

	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']);
	$condition = " uniacid={$_W['uniacid']}";
	if ($status!='') {
		if ($status == 1) {
			$condition .= " AND is_tuan=1 ";
		} else {
			$condition .= " AND is_tuan=0 ";
		}
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

//	$orders = pdo_fetchall("select * from" . tablename('wz_tuan_order') . "where $condition and status=2");
	$orders = pdo_fetchall("select DISTINCT address from" . tablename('wz_tuan_order') . "where $condition and status=2 and is_hexiao=0");
	if ($status == '0') {
		$str = '单独购买待发货订单_' . time();
	}
	if ($status == '1') {
		$str = '团购成功待发货订单_' . time();
	}
	if ($status == '') {
		$str = '全部待发货订单' . time();
	}

	/* 输入到CSV文件 */
	$html = "\xEF\xBB\xBF";
	/* 输出表头 */
	$filter = array('aa' => '订单编号', 'bb' => '姓名', 'cc' => '电话', 'dd' => '总价(元)', 'ee' => '状态', 'ff' => '下单时间', 'gg' => '商品名称', 'hh' => '收货地址', 'ii' => '微信订单号', 'jj' => '快递单号', 'kk' => '快递名称');
	foreach ($filter as $key => $title) {
		$html .= $title . "\t,";
	}
	$html .= "\n";
	foreach ($orders as $k => $vv) {
		$order_same = pdo_fetchall("select * from" . tablename('wz_tuan_order') . "where address='{$vv['address']}' and status=2 and $condition and is_hexiao=0");
		foreach ($order_same as $kk => $v) {
			$thistatus = '待发货';
			$goods = pdo_fetch("select * from" . tablename('wz_tuan_goods') . "where id = '{$v['g_id']}' and uniacid='{$_W['uniacid']}'");
			$time = date('Y-m-d H:i:s', $v['createtime']);
			$orders[$k]['aa'] = $v['orderno'];
			$orders[$k]['bb'] = $v['addname'];
			$orders[$k]['cc'] = $v['mobile'];
			$orders[$k]['dd'] = $v['price'];
			$orders[$k]['ee'] = $thistatus;
			$orders[$k]['ff'] = $time;
			$orders[$k]['gg'] = $goods['gname'];
			$orders[$k]['hh'] = $v['address'];
			$orders[$k]['ii'] = $v['transid'];
			$orders[$k]['jj'] = $v['expresssn'];
			$orders[$k]['kk'] = $v['express'];
			foreach ($filter as $key => $title) {
				$html .= $orders[$k][$key] . "\t,";
			}
			$html .= "\n";
		}
		$html .= "\n";
	}
	/* 输出CSV文件 */
	header("Content-type:text/csv");
	header("Content-Disposition:attachment; filename={$str}.csv");
	echo $html;
	exit();
}
include $this -> template('web/import');
?>