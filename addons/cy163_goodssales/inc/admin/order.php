<?php
global $_W, $_GPC;
load()->func('tpl');
$express = array(
	0=>array(
		'pinyin'=>'yunda',
		'value'=>'韵达快递',
	),
	1=>array(
		'pinyin'=>'yuantong',
		'value'=>'圆通速递',
	),
	2=>array(
		'pinyin'=>'shentong',
		'value'=>'申通速递',
	),
	3=>array(
		'pinyin'=>'shunfeng',
		'value'=>'顺丰速递',
	),
	4=>array(
		'pinyin'=>'tiantian',
		'value'=>'天天快递',
	),
	5=>array(
		'pinyin'=>'youzhengguonei',
		'value'=>'邮政包裹',
	),
	6=>array(
		'pinyin'=>'ems',
		'value'=>'中通快递',
	),
	7=>array(
		'pinyin'=>'zhongtong',
		'value'=>'EMS',
	),
	8=>array(
		'pinyin'=>'quanfengkuaidi',
		'value'=>'全峰快递',
	),
	9=>array(
		'pinyin'=>'huitongkuaidi',
		'value'=>'百世快递',
	),
);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$goodslist = pdo_fetchall("SELECT id,title FROM ".tablename('cygoodssale_goods')." WHERE weid = {$_W['uniacid']} AND merchant_id = {$_SESSION['merchant_user']}");
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$status = $_GPC['status'];
	if($status == ''){
		$status = 100;
	}
	$condition = " o.weid = :weid";
	$paras = array(':weid' => $_W['uniacid']);

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = TIMESTAMP;
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
	}
	$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
	$paras[':starttime'] = $starttime;
	$paras[':endtime'] = $endtime;
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
	}
	if (!empty($_GPC['member'])) {
		$condition .= " AND o.address LIKE '%{$_GPC['member']}%'";
	}
	if (!empty($_GPC['goods_id'])) {
		$goods_id = intval($_GPC['goods_id']);
		$ordergoodslist = pdo_fetchall("SELECT orderid FROM ".tablename('cygoodssale_order_goods')." WHERE weid = {$_W['uniacid']} AND goodsid = {$goods_id}");
		$orderidarr = '(';
		if(!empty($ordergoodslist)){
			foreach($ordergoodslist as $k=>$v){
				$orderidarr .= $v['orderid'].",";
			}
		}
		$orderidarr = substr($orderidarr,0,-1).")";
		if(!empty($orderidarr)){
			$condition .= " AND o.id in {$orderidarr}";
		}
	}
	if ($status != 100) {
		if($status == 3){
			$condition .= " AND o.status = 4";
		}else{
			$condition .= " AND o.status = '" . intval($status) . "'";
		}
	}
	$condition .= " AND o.merchant_id = " . $_SESSION['merchant_user'];
	$sql = 'SELECT COUNT(*) FROM ' . tablename('cygoodssale_order') . ' AS `o` WHERE ' . $condition;
	$total = pdo_fetchcolumn($sql, $paras);
	if ($total > 0) {
		if ($_GPC['export'] != '') {
			$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		}else{
			$limit = '';
		}
		$sql = 'SELECT * FROM ' . tablename('cygoodssale_order') . ' AS `o` WHERE ' . $condition . ' ORDER BY `o`.`createtime` DESC ' . $limit;
		$list = pdo_fetchall($sql,$paras);
		$pager = pagination($total, $pindex, $psize);
		
		if ($_GPC['export'] == 'export') {	
			$data = array();
			foreach ($list as $k => $v) {
				$address_arr = explode("|",$v['address']);
				$data[$k]['ordersn'] = $v['ordersn'];
				$data[$k]['realname'] = $address_arr[0];
				$data[$k]['telphone'] = $address_arr[1];
				$data[$k]['price'] = $v['price'];
				$data[$k]['createtime'] = date("Y-m-d H:i:s",$v['createtime']);
				$data[$k]['zipcode'] = $address_arr[2];
				$data[$k]['address'] = $address_arr[3].$address_arr[4].$address_arr[5];
			}
			exportexcel($data,$title = array('订单号','姓名','电话','总价','状态','下单时间','邮政编码','收货地址信息'),'','',$filename='订单数据');
			exit();
		}
		
		if ($_GPC['export'] == 'beihuo' || $_GPC['export'] == 'fahuodan') {
			if(empty($list)){
				message('没有订单数据！');
			}
		}
		
		if ($_GPC['export'] == 'beihuo'){
			$content = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"
							xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
							xmlns="http://www.w3.org/TR/REC-html40">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>'.$_SESSION['merchant_name'].'</title>
							<style>
							@font-face {
							font-family:"Times New Roman";
							}
							@font-face {
							font-family:"&#23435;&#20307;";
							}
							@font-face {
							font-family:"Arial";
							}
							table{border-collapse:collapse;border-color:#000;width:100%;margin:0 auto;}
							td{ border-color:#000; padding:10px 5px; vertical-align:middle;}
							h1{ text-align:center}
							</style>
							<!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View><w:TrackMoves>false</w:TrackMoves><w:TrackFormatting/><w:ValidateAgainstSchemas/><w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid><w:IgnoreMixedContent>false</w:IgnoreMixedContent><w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText><w:DoNotPromoteQF/><w:LidThemeOther>EN-US</w:LidThemeOther><w:LidThemeAsian>ZH-CN</w:LidThemeAsian><w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript><w:Compatibility><w:BreakWrappedTables/><w:SnapToGridInCell/><w:WrapTextWithPunct/><w:UseAsianBreakRules/><w:DontGrowAutofit/><w:SplitPgBreakAndParaMark/><w:DontVertAlignCellWithSp/><w:DontBreakConstrainedForcedTables/><w:DontVertAlignInTxbx/><w:Word11KerningPairs/><w:CachedColBalance/><w:UseFELayout/></w:Compatibility><w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel><m:mathPr><m:mathFont m:val="Cambria Math"/><m:brkBin m:val="before"/><m:brkBinSub m:val="--"/><m:smallFrac m:val="off"/><m:dispDef/><m:lMargin m:val="0"/> <m:rMargin m:val="0"/><m:defJc m:val="centerGroup"/><m:wrapIndent m:val="1440"/><m:intLim m:val="subSup"/><m:naryLim m:val="undOvr"/></m:mathPr></w:WordDocument></xml><![endif]-->
							</head>
							<body>
							<h1>'.$_SESSION['merchant_name'].'备货单</h1>
							<table border="1" cellpadding="3" cellspacing="0">
							<tr>
								<td>下单人：</td>
								<td colspan="3"></td>
								<td>日期：</td>
								<td colspan="2">'.date("Y年m月d日 H:i:s",time()).'</td>
							</tr>
							<tr>
								<td width="10%" valign="center">序号</td>
								<td width="35%" valign="center">货品名称</td>
								<td width="10%" valign="center">货品编号</td>
								<td width="15%" valign="center">规格</td>
								<td width="10%" valign="center">数量</td>
								<td width="10%" valign="center">单价</td>
								<td width="10%" valign="center">金额</td>
							</tr>';
							$now = 1;
							$alltotal = 0;
							$allprice = 0;
							foreach($list as $korder=>$vorder){
								$order_goods_list = pdo_fetchall("SELECT a.*,b.title,b.goodssn FROM ".tablename('cygoodssale_order_goods')." as a,".tablename('cygoodssale_goods')." as b WHERE a.orderid = {$vorder['id']} AND a.goodsid = b.id");
								foreach($order_goods_list as $ogk=>$ogv){
									$content .= '<tr>
													<td>'.$now.'</td>
													<td>'.$ogv['title'].'</td>
													<td>'.$ogv['goodssn'].'</td>
													<td>'.$ogv['optionname'].'</td>
													<td>'.$ogv['total'].'</td>
													<td>'.$ogv['price'].'</td>
													<td>'.($ogv['total']*$ogv['price']).'</td>
												</tr>';
									$alltotal += $ogv['total'];
									$allprice += $ogv['total']*$ogv['price'];
									$now++;
								}
								pdo_update('cygoodssale_order',array('isbei' => 1),array('id' => $vorder['id']));
							}

							$content .= '<tr>
							<td>合计</td>
							<td colspan="2"></td>
							<td>数量</td>
							<td>'.$alltotal.'</td>
							<td>金额</td>
							<td>'.$allprice.'</td>
							</tr><tr>
								<td>备注</td>
								<td colspan="6"></td>
							</tr>
							<tr>
								<td colspan="5" align="right">签字：</td>
								<td colspan="2"></td>
							</tr>
							</table>
							</body>
							</html>';
			header("Content-Type: application/doc");
			header("Content-Disposition: attachment; filename=".$_SESSION['merchant_name']."备货单.doc");
			echo $content;
		}
		
		
		if ($_GPC['export'] == 'fahuodan'){
			$content = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"
							xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
							xmlns="http://www.w3.org/TR/REC-html40">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>'.$_SESSION['merchant_name'].'发货清单</title>
							<style>
							@font-face {
							font-family:"Times New Roman";
							}
							@font-face {
							font-family:"&#23435;&#20307;";
							}
							@font-face {
							font-family:"Arial";
							}
							table{border-collapse:collapse;border-color:#000;width:100%;margin:0 auto;}
							td{ border-color:#000; padding:10px 5px; vertical-align:middle;}
							h1{ text-align:center}
							</style>
							<!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View><w:TrackMoves>false</w:TrackMoves><w:TrackFormatting/><w:ValidateAgainstSchemas/><w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid><w:IgnoreMixedContent>false</w:IgnoreMixedContent><w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText><w:DoNotPromoteQF/><w:LidThemeOther>EN-US</w:LidThemeOther><w:LidThemeAsian>ZH-CN</w:LidThemeAsian><w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript><w:Compatibility><w:BreakWrappedTables/><w:SnapToGridInCell/><w:WrapTextWithPunct/><w:UseAsianBreakRules/><w:DontGrowAutofit/><w:SplitPgBreakAndParaMark/><w:DontVertAlignCellWithSp/><w:DontBreakConstrainedForcedTables/><w:DontVertAlignInTxbx/><w:Word11KerningPairs/><w:CachedColBalance/><w:UseFELayout/></w:Compatibility><w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel><m:mathPr><m:mathFont m:val="Cambria Math"/><m:brkBin m:val="before"/><m:brkBinSub m:val="--"/><m:smallFrac m:val="off"/><m:dispDef/><m:lMargin m:val="0"/> <m:rMargin m:val="0"/><m:defJc m:val="centerGroup"/><m:wrapIndent m:val="1440"/><m:intLim m:val="subSup"/><m:naryLim m:val="undOvr"/></m:mathPr></w:WordDocument></xml><![endif]-->
							</head>
							<body>';
							$now = 1;
							$alltotal;
							foreach($list as $korder=>$vorder){
								$address_arr = explode("|",$vorder['address']);
								$content .= '<h1>'.$_SESSION['merchant_name'].'发货清单</h1><table border="1" cellpadding="3" cellspacing="0">';
								$content .= '<tr><td>流水号：</td><td></td><td>订单号：</td><td>'.$vorder['ordersn'].'</td><td>日期：</td><td colspan="2">'.date("Y年m月d日",time()).'</td></tr>';
								$content .= '<tr><td>买家</td><td colspan="3">'.$address_arr[0].' '.$address_arr[1].' '.$address_arr[3].$address_arr[4].$address_arr[5].$address_arr[6].'</td><td>订单时间：</td><td colspan="2">'.date("Y年m月d日 H:i:s",$vorder['createtime']).'</td></tr>';
								$content .= '<tr>
												<td width="10%" valign="center">序号</td>
												<td width="35%" valign="center">货品名称</td>
												<td width="10%" valign="center">货品编号</td>
												<td width="15%" valign="center">规格</td>
												<td width="10%" valign="center">数量</td>
												<td width="10%" valign="center">单价</td>
												<td width="10%" valign="center">金额</td>
											</tr>';
								$order_goods_list = pdo_fetchall("SELECT a.*,b.title,b.goodssn FROM ".tablename('cygoodssale_order_goods')." as a,".tablename('cygoodssale_goods')." as b WHERE a.orderid = {$vorder['id']} AND a.goodsid = b.id");
								foreach($order_goods_list as $ogk=>$ogv){
									$content .= '<tr>
													<td>'.$now.'</td>
													<td>'.$ogv['title'].'</td>
													<td>'.$ogv['goodssn'].'</td>
													<td>'.$ogv['optionname'].'</td>
													<td>'.$ogv['total'].'</td>
													<td>'.$ogv['price'].'</td>
													<td>'.($ogv['total']*$ogv['price']).'</td>
												</tr>';
									$alltotal += $ogv['total'];
									$now++;
								}
								$content .= '<tr>
												<td>合计</td>
												<td colspan="3"></td>
												<td>数量：'.$alltotal.'</td>
												<td>邮费：'.$vorder['dispatchprice'].'</td>
												<td>实付：'.$vorder['price'].'</td>
											</tr>
											<tr>
												<td>商户</td>
												<td colspan="2"></td>
												<td>发货人：</td>
												<td colspan="3"></td>
											</tr>
											</table>
											<h4 style="margin-bottom:20px;">订单备注：'.$vorder['remark'].' </h4>';
								pdo_update('cygoodssale_order',array('isfa' => 1),array('id' => $vorder['id']));
							}
			$content .= '</body></html>';		
			header("Content-Type: application/doc");
			header("Content-Disposition: attachment; filename=".$_SESSION['merchant_name']."发货单.doc");
			echo $content;
		}

		foreach ($list as &$value) {
			// 收货地址信息
			list($value['username'], $value['mobile'], $value['zipcode']) = explode('|', $value['address']);
		}
	}
} elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$item = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_order') . " WHERE id = :id AND merchant_id = :merchant_id AND weid = :weid", array(':id' => $id,':merchant_id'=>$_SESSION['merchant_user'], ':weid' => $_W['uniacid']));
	if (empty($item)) {
		message("抱歉，您没有该订单!", referer(), "error");
	}
	if (checksubmit('confirmsend')) {
		if (empty($_GPC['expresscom']) || empty($_GPC['express']) || empty($_GPC['expresssn'])) {
			message('请选择选择快递公司并且输入快递单号！');
		}
		if($item['status'] != 1 && $item['status'] != 2){
			message('订单当前状态不能使用该操作！');
		}
		pdo_update(
			'cygoodssale_order',
			array(
				'status' => 2,
				'express' => $_GPC['express'],
				'expresscom' => $_GPC['expresscom'],
				'expresssn' => $_GPC['expresssn'],
			),
			array('id' => $id)
		);
		$tpllist = pdo_fetch("SELECT id FROM".tablename('cygoodssale_tplmessage_tpllist')." WHERE tplbh = 'OPENTM200565259' AND uniacid = {$_W['uniacid']}");
		$setting = $this->setting;
		if(!empty($tpllist) && $setting['istplon'] == 1){
			$arrmsg = array(
				'openid'=>$item['from_user'],
				'topcolor'=>'#980000',
				'first'=>'订单发货通知',
				'firstcolor'=>'',
				'keyword1'=>$item['ordersn'],
				'keyword1color'=>'',
				'keyword2'=>$_GPC['express'],
				'keyword2color'=>'',
				'keyword3'=>$_GPC['expresssn'],
				'keyword3color'=>'',
				'remark'=>'',
				'remarkcolor'=>'',
				'url'=>$_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("myorder")),
			);
			$this->sendtemmsg($tpllist['id'],$arrmsg);
		}
		message('操作成功！', referer(), 'success');
	}
	if (checksubmit('finish')) {
		message('商家不能自己完成订单！');
		if($item['status'] != 2){
			message('订单当前状态不能使用该操作！');
		}
		pdo_update('cygoodssale_order', array('status' => 4), array('id' => $id, 'weid' => $_W['uniacid']));
		$dataaccount = array(
			'weid'=>$_W['uniacid'],
			'merchant_id'=>$item['merchant_id'],
			'price'=>$item['price'],
			'time'=>TIMESTAMP,
			'remark'=>"订单号为".$item['ordersn']."获得",
		);
		pdo_insert('cygoodssale_merchantaccount',$dataaccount);
		message('订单操作成功！', referer(), 'success');
	}
	if (checksubmit('confrimpay')) {
		if($item['status'] != 0){
			message('订单当前状态不能使用该操作！');
		}
		pdo_update('cygoodssale_order', array('status' => 1), array('id' => $id, 'weid' => $_W['uniacid']));
		//设置库存
		message('确认订单付款操作成功！', referer(), 'success');
	}
	// 订单取消
	if (checksubmit('cancelorder')) {
		if($item['status'] != 0){
			message('订单当前状态不能使用该操作！');
		}
		pdo_update('cygoodssale_order', array('status' => '-1'), array('id' => $item['id']));
		message('订单取消操作成功！', referer(), 'success');
	}
	// 收货地址信息
	$item['user'] = explode('|', $item['address']);

	$goods = pdo_fetchall("SELECT g.*, o.total,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('cygoodssale_order_goods') .
			" o left join " . tablename('cygoodssale_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
	$item['goods'] = $goods;
} elseif ($operation == 'delete') {
	/*订单删除*/
	$orderid = intval($_GPC['id']);
	$item = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_order') . " WHERE id = :id AND merchant_id = :merchant_id AND weid = :weid", array(':id' => $orderid,':merchant_id'=>$_SESSION['merchant_user'], ':weid' => $_W['uniacid']));
	if (empty($item)) {
		message("抱歉，您没有该订单!", referer(), "error");
	}
	if (pdo_delete('cygoodssale_order', array('id' => $orderid, 'weid' => $_W['uniacid']))) {
		pdo_delete('cygoodssale_order_goods', array('orderid' => $orderid));
		message('订单删除成功', $this->createMobileUrl('order', array('op' => 'display')), 'success');
	} else {
		message('订单不存在或已被删除', $this->createMobileUrl('order', array('op' => 'display')), 'error');
	}
}
include $this->template('admin/order');

function exportexcel($data=array(),$title=array(),$header,$footer,$filename='report'){
	header("Content-type:application/octet-stream");
	header("Accept-Ranges:bytes");
	header("Content-type:application/vnd.ms-excel");  
	header("Content-Disposition:attachment;filename=".$filename.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	$header = iconv("UTF-8", "GB2312",$header);
	echo $header;
	if (!empty($title)){
		foreach ($title as $k => $v) {
			$title[$k]=iconv("UTF-8", "GB2312",$v);
		}
		$title= implode("\t", $title);
		echo "$title\n";
	}
	if (!empty($data)){
		foreach($data as $key=>$val){
			foreach ($val as $ck => $cv) {
				$data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
			}
			$data[$key]=implode("\t", $data[$key]);
			
		}
		echo implode("\n",$data);
	}
	$footer = iconv("UTF-8", "GB2312",$footer);
	echo $footer;
}

?>