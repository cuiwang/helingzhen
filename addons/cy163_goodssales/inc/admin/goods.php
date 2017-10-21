<?php
global $_GPC, $_W;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'recyclebin') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = ' WHERE `weid` = :weid AND `deleted` = :deleted';
	$params = array(':weid' => $_W['uniacid'], ':deleted' => '1');
	if (!empty($_GPC['keyword'])) {
		$condition .= ' AND `title` LIKE :title';
		$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
	}
	$condition .= ' AND `merchant_id` = :merchant_id';
	$params[':merchant_id'] = intval($_SESSION['merchant_user']);
	$sql = 'SELECT COUNT(*) FROM ' . tablename('cygoodssale_goods') . $condition;
	$total = pdo_fetchcolumn($sql, $params);
	if (!empty($total)) {
		$sql = 'SELECT * FROM ' . tablename('cygoodssale_goods') . $condition . ' ORDER BY `status` DESC, `displayorder` DESC,
				`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$pager = pagination($total, $pindex, $psize);
	}
}elseif ($operation == 'post') {
	$cservicelist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE weid = '{$_W['uniacid']}' AND merchant_id = {$_SESSION['merchant_user']} ORDER BY displayorder ASC");
	$id = intval($_GPC['id']);
	$fuzhi = intval($_GPC['fuzhi']);
	$goodscservicearr = pdo_fetchall("SELECT cserviceid FROM " . tablename('cygoodssale_goodscservice') . " WHERE weid = '{$_W['uniacid']}' AND goodsid = {$id}");
	$goodscservice = array();
	foreach($goodscservicearr as $k=>$v){
		$goodscservice[] = $v['cserviceid'];
	}
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods') . " WHERE merchant_id = {$_SESSION['merchant_user']} AND id = :id", array(':id' => $id));
		if (empty($item)) {
			message('抱歉，商品不存在或是已经删除！', '', 'error');
		}
		$allspecs = pdo_fetchall("SELECT * FROM " . TABLENAME('cygoodssale_spec')." WHERE goodsid=:id ORDER BY displayorder ASC",array(":id"=>$id));
		foreach ($allspecs as $sk=>$s) {
			$allspecs[$sk]['items'] = pdo_fetchall("select * from " . tablename('cygoodssale_spec_item') . " where specid=:specid order by displayorder asc", array(":specid" => $s['id']));
		}
		$params = pdo_fetchall("select * from " . tablename('cygoodssale_goods_param') . " where goodsid=:id order by displayorder asc", array(':id' => $id));
		$piclist1 = unserialize($item['thumb_url']);
		$piclist = array();
		if(is_array($piclist1)){
			foreach($piclist1 as $p){
				$piclist[] = is_array($p)?$p['attachment']:$p;
			}
		}
		//处理规格项
		$html = "";
		$options = pdo_fetchall("select * from " . tablename('cygoodssale_goods_option') . " where goodsid=:id order by id asc", array(':id' => $id));
		//排序好的specs
		$specs = array();
		//找出数据库存储的排列顺序
		if (count($options) > 0) {
			$specitemids = explode("_", $options[0]['specs'] );
			foreach($specitemids as $itemid){
				foreach($allspecs as $ss){
					$items = $ss['items'];
					foreach($items as $it){
						if($it['id']==$itemid){
							$specs[] = $ss;
							break;
						}
					}
				}
			}
			$html = '';
			$html .= '<table class="table table-bordered">';
			$html .= '<thead>';
			$html .= '<tr>';
			$len = count($specs);
			$newlen = 1; //多少种组合
			$h = array(); //显示表格二维数组
			$rowspans = array(); //每个列的rowspan
			for ($i = 0; $i < $len; $i++) {
				//表头
				$html .= "<th width='10%'>" . $specs[$i]['title'] . "</th>";
				//计算多种组合
				$itemlen = count($specs[$i]['items']);
				if ($itemlen <= 0) {
					$itemlen = 1;
				}
				$newlen *= $itemlen;
				//初始化 二维数组
				$h = array();
				for ($j = 0; $j < $newlen; $j++) {
					$h[$i][$j] = array();
				}
				//计算rowspan
				$l = count($specs[$i]['items']);
				$rowspans[$i] = 1;
				for ($j = $i + 1; $j < $len; $j++) {
					$rowspans[$i]*= count($specs[$j]['items']);
				}
			}
			$html .= '<th width="15%"><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-prepend input-append"><input type="text" class="option_stock_all"  VALUE=""/><span class="add-on"><a href="javascript:;" class="icon-hand-down" title="批量设置" onclick="setCol(\'option_stock\');"></a></span></div></th>';
			$html .= '<th width="15%"><div style="padding-bottom:10px;text-align:center;font-size:16px;">售价</div><div class="input-prepend input-append"><input type="text" class="option_normalprice_all"  VALUE=""/><span class="add-on"><a href="javascript:;" class="icon-hand-down" title="批量设置" onclick="setCol(\'option_normalprice\');"></a></span></div></th>';
			$html .= '<th width="15%"><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div><div class="input-prepend input-append"><input type="text" class="option_weight_all"  VALUE=""/><span class="add-on"><a href="javascript:;" class="icon-hand-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></th>';
			$html .= '</tr></thead>';
			for ($m = 0; $m < $len; $m++) {
				$k = 0;
				$kid = 0;
				$n = 0;
				for ($j = 0; $j < $newlen; $j++) {
					$rowspan = $rowspans[$m];
					if ($j % $rowspan == 0) {
						$h[$m][$j] = array("html" => "<td rowspan='" . $rowspan . "'>" . $specs[$m]['items'][$kid]['title'] . "</td>", "id" => $specs[$m]['items'][$kid]['id']);
					} else {
						$h[$m][$j] = array("html" => "", "id" => $specs[$m]['items'][$kid]['id']);
					}
					$n++;
					if ($n == $rowspan) {
						$kid++;
						if ($kid > count($specs[$m]['items']) - 1) {
							$kid = 0;
						}
						$n = 0;
					}
				}
			}
			$hh = "";
			for ($i = 0; $i < $newlen; $i++) {
				$hh.="<tr>";
				$ids = array();
				for ($j = 0; $j < $len; $j++) {
					$hh.=$h[$j][$i]['html'];
					$ids[] = $h[$j][$i]['id'];
				}
				$ids = implode("_", $ids);
				$val = array("id" => "","title"=>"", "stock" => "", "normalprice" => "", "weight" => "");
				foreach ($options as $o) {
					if ($ids === $o['specs']) {
						$val = array(
							"id" => $o['id'],
							"title" =>$o['title'],
							"stock" => $o['stock'],
							"normalprice"=> $o['normalprice'],
							"weight" => $o['weight']
						);
						break;
					}
				}
				$hh .= '<td>';
				$hh .= '<input name="option_stock_' . $ids . '[]"  type="text" class="option_stock option_stock_' . $ids . '" value="' . $val['stock'] . '"/></td>';
				$hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
				$hh .= '<input name="option_ids[]"  type="hidden" class="option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
				$hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
				$hh .= '</td>';
				$hh .= '<td><input name="option_normalprice_' . $ids . '[]" type="text" class="option_normalprice option_normalprice_' . $ids . '" value="' . $val['normalprice'] . '"/></td>';
				$hh .= '<td><input name="option_weight_' . $ids . '[]" type="text" class="option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
				$hh .= '</tr>';
			}
			$html .= $hh;
			$html .= "</table>";
		}
	}

	if ($_GPC['submit']) {
		if (empty($_SESSION['merchant_user'])) {
			message('不存在商户！');
		}
		if (empty($_GPC['goodsname'])) {
			message('请输入商品名称！');
		}
		if(empty($_GPC['thumb'])){
			message('请上传商品图片！');
		}
		if(empty($_GPC['thumbs'])){
			$_GPC['thumbs'] = array();
		}
		$setting = $this->setting;
		if($setting['isshenhe'] == 1){
			$status = 0;
		}else{
			$status = 1;
		}
		$isdistribution = intval($_GPC['isdistribution']);
		if($isdistribution == 1){
			$fenxiaoprice = $_GPC['fenxiaoprice'];
		}else{
			$fenxiaoprice = 0;
		}
		$data = array(			
			'weid' => intval($_W['uniacid']),
			'displayorder' => intval($_GPC['displayorder']),
			'title' => $_GPC['goodsname'],
			'thumb'=>$_GPC['thumb'],
			'description' => $_GPC['description'],
			'content' => htmlspecialchars_decode($_GPC['content']),
			'goodssn' => $_GPC['goodssn'],
			'createtime' => TIMESTAMP,
			'total' => intval($_GPC['total']),
			'normalprice' => $_GPC['normalprice'],
			'weight' => $_GPC['weight'],
			'originalprice' => $_GPC['originalprice'],
			'yunfei' => $_GPC['yunfei'],
			'maxbuy' => intval($_GPC['maxbuy']),
			'hasoption' => intval($_GPC['hasoption']),
			'sales' => intval($_GPC['sales']),
			'status' => $status,
			'ishexiao' => intval($_GPC['ishexiao']),
			'hexiaocon' => trim($_GPC['hexiaocon']),
			'cannotpay' => intval($_GPC['cannotpay']),
			'btntext' => trim($_GPC['btntext']),
			'isneedshouhuo' => intval($_GPC['isneedshouhuo']),
			'merchant_id' => intval($_SESSION['merchant_user']),
			'viewcount' => intval($_GPC['viewcount']),
			'istime' => intval($_GPC['istime']),
			'timestart' => strtotime($_GPC['timestart']),
			'timeend' => strtotime($_GPC['timeend']),
			'totalcnf' => intval($_GPC['totalcnf']),
			'xiangounum' => intval($_GPC['xiangounum']),
			'isparamshow' => intval($_GPC['isparamshow']),
			'fenxiaoprice' => $fenxiaoprice,
			'isdistribution'=>$isdistribution,
			'distributiontext' => trim($_GPC['distributiontext']),
			'isthumbsshow'=> intval($_GPC['isthumbsshow']),
		);
		if ($data['total'] === -1) {
			$data['total'] = 0;
		}

		if(is_array($_GPC['thumbs'])){
			$data['thumb_url'] = serialize($_GPC['thumbs']);
		}
		if (empty($id) || $fuzhi == 1) {
			pdo_insert('cygoodssale_goods', $data);
			$id = pdo_insertid();
		} else {
			unset($data['createtime']);
			pdo_update('cygoodssale_goods', $data, array('id' => $id));
			pdo_delete('cygoodssale_goodscservice',array('goodsid' => $id));
		}
		//处理客服
		if(!empty($_GPC['cservice'])){
			foreach($_GPC['cservice'] as $k=>$v){
				$datacservice['weid'] = $_W['uniacid'];
				$datacservice['goodsid'] = $id;
				$datacservice['cserviceid'] = $v;
				pdo_insert('cygoodssale_goodscservice', $datacservice);
			}
		}
		$totalstocks = 0;
		//处理自定义参数
		$param_ids = $_POST['param_id'];
		$param_titles = $_POST['param_title'];
		$param_values = $_POST['param_value'];
		$param_displayorders = $_POST['param_displayorder'];
		$len = count($param_ids);
		$paramids = array();
		for ($k = 0; $k < $len; $k++) {
			$param_id = "";
			$get_param_id = $param_ids[$k];
			$a = array(
				"title" => $param_titles[$k],
				"value" => $param_values[$k],
				"displayorder" => $k,
				"goodsid" => $id,
			);
			if (!is_numeric($get_param_id)) {
				pdo_insert("cygoodssale_goods_param", $a);
				$param_id = pdo_insertid();
			} else {
				pdo_update("cygoodssale_goods_param", $a, array('id' => $get_param_id));
				$param_id = $get_param_id;
			}
			$paramids[] = $param_id;
		}
		if (count($paramids) > 0) {
			pdo_query("delete from " . tablename('cygoodssale_goods_param') . " where goodsid=$id and id not in ( " . implode(',', $paramids) . ")");
		}else{
			pdo_query("delete from " . tablename('cygoodssale_goods_param') . " where goodsid=$id");
		}
//				if ($totalstocks > 0) {
//					pdo_update("cygoodssale_goods", array("total" => $totalstocks), array("id" => $id));
//				}
		//处理商品规格
		$files = $_FILES;
		$spec_ids = $_POST['spec_id'];
		$spec_titles = $_POST['spec_title'];
		$specids = array();
		$len = count($spec_ids);
		$specids = array();
		$spec_items = array();
		for ($k = 0; $k < $len; $k++) {
			$spec_id = "";
			$get_spec_id = $spec_ids[$k];
			$a = array(
				"weid" => $_W['uniacid'],
				"goodsid" => $id,
				"displayorder" => $k,
				"title" => $spec_titles[$get_spec_id]
			);
			if (is_numeric($get_spec_id)) {
				pdo_update("cygoodssale_spec", $a, array("id" => $get_spec_id));
				$spec_id = $get_spec_id;
			} else {
				pdo_insert("cygoodssale_spec", $a);
				$spec_id = pdo_insertid();
			}
			//子项
			$spec_item_ids = $_POST["spec_item_id_".$get_spec_id];
			$spec_item_titles = $_POST["spec_item_title_".$get_spec_id];
			$spec_item_shows = $_POST["spec_item_show_".$get_spec_id];
			$spec_item_thumbs = $_POST["spec_item_thumb_".$get_spec_id];
			$spec_item_oldthumbs = $_POST["spec_item_oldthumb_".$get_spec_id];
			$itemlen = count($spec_item_ids);
			$itemids = array();
			for ($n = 0; $n < $itemlen; $n++) {
				$item_id = "";
				$get_item_id = $spec_item_ids[$n];
				$d = array(
					"weid" => $_W['uniacid'],
					"specid" => $spec_id,
					"displayorder" => $n,
					"title" => $spec_item_titles[$n],
					"show" => $spec_item_shows[$n],
					"thumb"=>$spec_item_thumbs[$n]
				);
				$f = "spec_item_thumb_" . $get_item_id;
				if (is_numeric($get_item_id)) {
					pdo_update("cygoodssale_spec_item", $d, array("id" => $get_item_id));
					$item_id = $get_item_id;
				} else {
					pdo_insert("cygoodssale_spec_item", $d);
					$item_id = pdo_insertid();
				}
				$itemids[] = $item_id;
				//临时记录，用于保存规格项
				$d['get_id'] = $get_item_id;
				$d['id']= $item_id;
				$spec_items[] = $d;
			}
			//删除其他的
			if(count($itemids)>0){
				 pdo_query("delete from " . tablename('cygoodssale_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(",", $itemids) . ")");
			}
			else{
				 pdo_query("delete from " . tablename('cygoodssale_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id");
			}
			//更新规格项id
			pdo_update("cygoodssale_spec", array("content" => serialize($itemids)), array("id" => $spec_id));
			$specids[] = $spec_id;
		}
		//删除其他的
		if( count($specids)>0){
			pdo_query("delete from " . tablename('cygoodssale_spec') . " where weid={$_W['uniacid']} and goodsid=$id and id not in (" . implode(",", $specids) . ")");
		}
		else{
			pdo_query("delete from " . tablename('cygoodssale_spec') . " where weid={$_W['uniacid']} and goodsid=$id");
		}
		//保存规格
		$option_idss = $_POST['option_ids'];
		$option_normalprice = $_POST['option_normalprice'];
		$option_stocks = $_POST['option_stock'];
		$option_weights = $_POST['option_weight'];
		$len = count($option_idss);
		$optionids = array();
		for ($k = 0; $k < $len; $k++) {
			$option_id = "";
			$ids = $option_idss[$k]; $idsarr = explode("_",$ids);
			$get_option_id = $_GPC['option_id_' . $ids][0];
			$newids = array();
			foreach($idsarr as $key=>$ida){
				foreach($spec_items as $it){
					if($it['get_id']==$ida){
						$newids[] = $it['id'];
						break;
					}
				}
			}
			$newids = implode("_",$newids);
			$a = array(
				"title" => $_GPC['option_title_' . $ids][0],
				"normalprice" => $_GPC['option_normalprice_' . $ids][0],
				"stock" => $_GPC['option_stock_' . $ids][0],
				"weight" => $_GPC['option_weight_' . $ids][0],
				"goodsid" => $id,
				"specs" => $newids
			);
			$totalstocks+=$a['stock'];
			if (empty($get_option_id)) {
				pdo_insert("cygoodssale_goods_option", $a);
				$option_id = pdo_insertid();
			} else {
				pdo_update("cygoodssale_goods_option", $a, array('id' => $get_option_id));
				$option_id = $get_option_id;
			}
			$optionids[] = $option_id;
		}
		if (count($optionids) > 0) {
			pdo_query("delete from " . tablename('cygoodssale_goods_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ")");
		}
		else{
			pdo_query("delete from " . tablename('cygoodssale_goods_option') . " where goodsid=$id");
		}
		//总库存
		if ($totalstocks > 0) {
			pdo_update("cygoodssale_goods", array("total" => $totalstocks), array("id" => $id));
		}
		if (empty($id) || $fuzhi == 1) {
			message('添加商品成功！', $this->createMobileUrl('admingoods', array('op' => 'display')), 'success');
		}else{
			message('更新商品成功-&#25240;&#82;&#32764;&#70;&#22825;&#84;&#20351;&#72;&#36164;&#78;&#28304;&#86;&#31038;&#67;&#21306;&#68;&#25552;&#83;&#20379;！', $this->createMobileUrl('admingoods', array('op' => 'post', 'id' => $id)), 'success');
		}
	}
} elseif ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = ' WHERE `weid` = :weid AND `deleted` = :deleted';
	$params = array(':weid' => $_W['uniacid'], ':deleted' => '0');
	if (!empty($_GPC['keyword'])) {
		$condition .= ' AND `title` LIKE :title';
		$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
	}
	if (isset($_GPC['status'])) {
		$condition .= ' AND `status` = :status';
		$params[':status'] = intval($_GPC['status']);
	}
	$condition .= ' AND `merchant_id` = :merchant_id';
	$params[':merchant_id'] = intval($_SESSION['merchant_user']);
	$sql = 'SELECT COUNT(*) FROM ' . tablename('cygoodssale_goods') . $condition;
	$total = pdo_fetchcolumn($sql, $params);
	if (!empty($total)) {
		$sql = 'SELECT * FROM ' . tablename('cygoodssale_goods') . $condition . ' ORDER BY `status` DESC, `displayorder` DESC,
				`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$pager = pagination($total, $pindex, $psize);
	}
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_goods') . " WHERE merchant_id = {$_SESSION['merchant_user']} AND id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，商品不存在或是已经被删除！');
	}
	pdo_update("cygoodssale_goods", array("deleted" => 1), array('id' => $id));
	message('删除成功，可在商品回收站中恢复！', referer(), 'success');
}elseif ($operation == 'huifu') {
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_goods') . " WHERE merchant_id = {$_SESSION['merchant_user']} AND id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，商品不存在或是已经被删除！');
	}
	pdo_update("cygoodssale_goods", array("deleted" => 0), array('id' => $id));
	message('恢复商品成功！', referer(), 'success');
}

include $this->template('admin/goods');
?>