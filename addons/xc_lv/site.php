<?php
/**
 * 微旅游模块微站定义
 */
defined('IN_IA') or exit('Access Denied');

session_start();
include 'model.php';

class Xc_lvModuleSite extends WeModuleSite {

	public $settings;

	public function __construct() {
		global $_W;
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'xc_lv'));
		$this->settings = iunserializer($settings);
	}

	public function doWebCategory() { 
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('xc_lv_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('xc_lv_category') . " WHERE id = '$id'");
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM " . tablename('xc_lv_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['catename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
					'thumb' => $_GPC['thumb'],
					'catetitle'=>trim($_GPC['catetitle']),
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('xc_lv_category', $data, array('id' => $id));
					load()->func('file');
					file_delete($_GPC['thumb_old']);
				} else {
					pdo_insert('xc_lv_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			include $this->template('category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('xc_lv_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			pdo_delete('xc_lv_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
		}
	}


	public function doWebSetGoodsProperty() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xc_lv_goods", array("is" . $type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xc_lv_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('type'))) {
			$data = ($data==1?'2':'1');
			pdo_update("xc_lv_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}


	public function doWebGoods() {
		global $_GPC, $_W;
		load()->func('tpl');

		$sql = 'SELECT * FROM ' . tablename('xc_lv_category') . ' WHERE `weid` = :weid ORDER BY `parentid`, `displayorder` DESC';
		$category = pdo_fetchall($sql, array(':weid' => $_W['uniacid']), 'id');
		if (!empty($category)) {
			$parent = $children = array();
			foreach ($category as $cid => $cate) {
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][] = $cate;
				} else {
					$parent[$cate['id']] = $cate;
				}
			}
		}

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('抱歉，商品不存在或是已经删除！', '', 'error');
				}
				$allspecs = pdo_fetchall("SELECT * FROM " . TABLENAME('xc_lv_spec')." WHERE goodsid=:id ORDER BY displayorder ASC",array(":id"=>$id));
				foreach ($allspecs as &$s) {
					$s['items'] = pdo_fetchall("select * from " . tablename('xc_lv_spec_item') . " where specid=:specid order by displayorder asc", array(":specid" => $s['id']));
				}
				unset($s);
				$params = pdo_fetchall("select * from " . tablename('xc_lv_goods_param') . " where goodsid=:id order by displayorder asc", array(':id' => $id));
				$piclist1 = unserialize($item['thumb_url']);
				$piclist = array();
				if(is_array($piclist1)){
					foreach($piclist1 as $p){
						$piclist[] = is_array($p)?$p['attachment']:$p;
					}
				}
				//处理规格项
				$html = "";
				$options = pdo_fetchall("select * from " . tablename('xc_lv_goods_option') . " where goodsid=:id order by id asc", array(':id' => $id));
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
					$html .= '<table class="table table-bordered table-condensed">';
					$html .= '<thead>';
					$html .= '<tr class="active">';
					$len = count($specs);
					$newlen = 1; //多少种组合
					$h = array(); //显示表格二维数组
					$rowspans = array(); //每个列的rowspan
					for ($i = 0; $i < $len; $i++) {
						//表头
						$html .= "<th style='width:80px;'>" . $specs[$i]['title'] . "</th>";
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
					$html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-group"><input type="text" class="form-control option_stock_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_stock\');"></a></span></div></div></th>';
					$html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">销售价格</div><div class="input-group"><input type="text" class="form-control option_marketprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_marketprice\');"></a></span></div></div></th>';
					$html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div><div class="input-group"><input type="text" class="form-control option_productprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productprice\');"></a></span></div></div></th>';
					$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">成本价格</div><div class="input-group"><input type="text" class="form-control option_costprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_costprice\');"></a></span></div></div></th>';
					$html .= '<th class="info" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div><div class="input-group"><input type="text" class="form-control option_weight_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></div></th>';
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
						$val = array("id" => "","title"=>"", "stock" => "", "costprice" => "", "productprice" => "", "marketprice" => "", "weight" => "");
						foreach ($options as $o) {
							if ($ids === $o['specs']) {
								$val = array(
									"id" => $o['id'],
									"title" =>$o['title'],
									"stock" => $o['stock'],
									"costprice" => $o['costprice'],
									"productprice" => $o['productprice'],
									"marketprice" => $o['marketprice'],
									"weight" => $o['weight']
								);
								break;
							}
						}
						$hh .= '<td class="info">';
						$hh .= '<input name="option_stock_' . $ids . '[]"  type="text" class="form-control option_stock option_stock_' . $ids . '" value="' . $val['stock'] . '"/></td>';
						$hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
						$hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
						$hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
						$hh .= '</td>';
						$hh .= '<td class="success"><input name="option_marketprice_' . $ids . '[]" type="text" class="form-control option_marketprice option_marketprice_' . $ids . '" value="' . $val['marketprice'] . '"/></td>';
						$hh .= '<td class="warning"><input name="option_productprice_' . $ids . '[]" type="text" class="form-control option_productprice option_productprice_' . $ids . '" " value="' . $val['productprice'] . '"/></td>';
						$hh .= '<td class="danger"><input name="option_costprice_' . $ids . '[]" type="text" class="form-control option_costprice option_costprice_' . $ids . '" " value="' . $val['costprice'] . '"/></td>';
						$hh .= '<td class="info"><input name="option_weight_' . $ids . '[]" type="text" class="form-control option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
						$hh .= '</tr>';
					}
					$html .= $hh;
					$html .= "</table>";
				}
			}
			if (empty($category)) {
				message('抱歉，请您先添加商品分类！', $this->createWebUrl('category', array('op' => 'post')), 'error');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['goodsname'])) {
					message('请输入商品名称！');
				}
				if (empty($_GPC['category']['parentid'])) {
					message('请选择商品分类！');
				}
				if(empty($_GPC['thumbs'])){
					$_GPC['thumbs'] = array();
				}
				$birth = $_GPC['birth'];
				
				$datime = $_GPC['detime'];
					
				  foreach($datime[0] as $key=>$vo){
        	  	
        	  	   $stime[] = strtotime($vo);
        	    }
				  
				 asort($stime);
				 foreach($stime as $key=>$v){
        	  
        	   	    	
        	   	    	$arr[0][] = date('Y-m-d',$v);
        	   	    	$arr[1][] = $datime[1][$key];
        	 
        	        }
		
			   $detime = json_encode($arr);
			   
			   $zhi = $_GPC['zhi'];

			   $han = $_GPC['han'];
			
		   
			  $location = $_GPC['location'];//位置信息
			  
			  $location = json_encode($location);
			   $han = json_encode($han);
			   $zhi = json_encode($zhi);
			
			   
				$data = array(
				
				  //位置信息
				    'location'=>$location,
				    'xingcheng'=>$_GPC['xingcheng'],
					'weid' => intval($_W['uniacid']),
					'displayorder' => intval($_GPC['displayorder']),
					'title' => $_GPC['goodsname'],
					'pcate' => intval($_GPC['category']['parentid']),
					'ccate' => intval($_GPC['category']['childid']),
					'thumb'=>$_GPC['thumb'],
					'type' => intval($_GPC['type']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'ishot' => intval($_GPC['ishot']),
					'isnew' => intval($_GPC['isnew']),
					'isdiscount' => intval($_GPC['isdiscount']),
					'istime' => intval($_GPC['istime']),
					'timestart' => strtotime($_GPC['timestart']),
					'timeend' => strtotime($_GPC['timeend']),
					'description' => $_GPC['description'],
					'content' => $content,
					'goodssn' => $_GPC['goodssn'],
					'unit' => $_GPC['unit'],
					'time2'=>$_GPC['time2'],
					'xingji'=>$_GPC['xingji'],
					'createtime' => TIMESTAMP,
					'detime'=>$detime,
					'total' => intval($_GPC['total']),
					'totalcnf' => intval($_GPC['totalcnf']),
					'marketprice' => $_GPC['marketprice'],
					'weight' => $_GPC['weight'],
					'costprice' => $_GPC['costprice'],
					'originalprice' => $_GPC['originalprice'],
					'productprice' => $_GPC['productprice'],
					'productsn' => $_GPC['productsn'],
					'credit' => sprintf('%.2f', $_GPC['credit']),
					'maxbuy' => intval($_GPC['maxbuy']),
					'usermaxbuy' => intval($_GPC['usermaxbuy']),
					'hasoption' => intval($_GPC['hasoption']),
					'sales' => intval($_GPC['sales']),
					'status' => intval($_GPC['status']),
					'starttime'=> strtotime($birth['start']),
					'endtime'=>strtotime($birth['end']),
					'zhi'=>$zhi,
					'han'=>$han,
				);
				if ($data['total'] === -1) {
					$data['total'] = 0;
					$data['totalcnf'] = 2;
				}

				if(is_array($_GPC['thumbs'])){
					$data['thumb_url'] = serialize($_GPC['thumbs']);
				}
				if (empty($id)) {
					pdo_insert('xc_lv_goods', $data);
					$id = pdo_insertid();
				} else {
					unset($data['createtime']);
					pdo_update('xc_lv_goods', $data, array('id' => $id));
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
						pdo_insert("xc_lv_goods_param", $a);
						$param_id = pdo_insertid();
					} else {
						pdo_update("xc_lv_goods_param", $a, array('id' => $get_param_id));
						$param_id = $get_param_id;
					}
					$paramids[] = $param_id;
				}
				if (count($paramids) > 0) {
					pdo_query("delete from " . tablename('xc_lv_goods_param') . " where goodsid=$id and id not in ( " . implode(',', $paramids) . ")");
				}
				else{
					pdo_query("delete from " . tablename('xc_lv_goods_param') . " where goodsid=$id");
				}
//				if ($totalstocks > 0) {
//					pdo_update("xc_lv_goods", array("total" => $totalstocks), array("id" => $id));
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
						pdo_update("xc_lv_spec", $a, array("id" => $get_spec_id));
						$spec_id = $get_spec_id;
					} else {
						pdo_insert("xc_lv_spec", $a);
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
							pdo_update("xc_lv_spec_item", $d, array("id" => $get_item_id));
							$item_id = $get_item_id;
						} else {
							pdo_insert("xc_lv_spec_item", $d);
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
						 pdo_query("delete from " . tablename('xc_lv_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(",", $itemids) . ")");
					}
					else{
						 pdo_query("delete from " . tablename('xc_lv_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id");
					}
					//更新规格项id
					pdo_update("xc_lv_spec", array("content" => serialize($itemids)), array("id" => $spec_id));
					$specids[] = $spec_id;
				}
				//删除其他的
				if( count($specids)>0){
					pdo_query("delete from " . tablename('xc_lv_spec') . " where weid={$_W['uniacid']} and goodsid=$id and id not in (" . implode(",", $specids) . ")");
				}
				else{
					pdo_query("delete from " . tablename('xc_lv_spec') . " where weid={$_W['uniacid']} and goodsid=$id");
				}
				//保存规格
				$option_idss = $_POST['option_ids'];
				$option_productprices = $_POST['option_productprice'];
				$option_marketprices = $_POST['option_marketprice'];
				$option_costprices = $_POST['option_costprice'];
				$option_stocks = $_POST['option_stock'];
				$option_weights = $_POST['option_weight'];
				$len = count($option_idss);
				$optionids = array();
				for ($k = 0; $k < $len; $k++) {
					$option_id = "";
					$get_option_id = $_GPC['option_id_' . $ids][0];
					$ids = $option_idss[$k]; $idsarr = explode("_",$ids);
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
						"productprice" => $_GPC['option_productprice_' . $ids][0],
						"costprice" => $_GPC['option_costprice_' . $ids][0],
						"marketprice" => $_GPC['option_marketprice_' . $ids][0],
						"stock" => $_GPC['option_stock_' . $ids][0],
						"weight" => $_GPC['option_weight_' . $ids][0],
						"goodsid" => $id,
						"specs" => $newids
					);
					$totalstocks+=$a['stock'];
					if (empty($get_option_id)) {
						pdo_insert("xc_lv_goods_option", $a);
						$option_id = pdo_insertid();
					} else {
						pdo_update("xc_lv_goods_option", $a, array('id' => $get_option_id));
						$option_id = $get_option_id;
					}
					$optionids[] = $option_id;
				}
				if (count($optionids) > 0) {
					pdo_query("delete from " . tablename('xc_lv_goods_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ")");
				}
				else{
					pdo_query("delete from " . tablename('xc_lv_goods_option') . " where goodsid=$id");
				}
				//总库存
				if ( ($totalstocks > 0) && ($data['totalcnf'] != 2) ) {
					pdo_update("xc_lv_goods", array("total" => $totalstocks), array("id" => $id));
				}
				message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'display', 'id' => $id)), 'success');
			}
		} elseif ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `weid` = :weid AND `deleted` = :deleted';
			$params = array(':weid' => $_W['uniacid'], ':deleted' => '0');
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			if (!empty($_GPC['category']['childid'])) {
				$condition .= ' AND `ccate` = :ccate';
				$params[':ccate'] = intval($_GPC['category']['childid']);
			}
			if (!empty($_GPC['category']['parentid'])) {
				$condition .= ' AND `pcate` = :pcate';
				$params[':pcate'] = intval($_GPC['category']['parentid']);
			}
			if (isset($_GPC['status'])) {
				$condition .= ' AND `status` = :status';
				$params[':status'] = intval($_GPC['status']);
			}

			$sql = 'SELECT COUNT(*) FROM ' . tablename('xc_lv_goods') . $condition;
			$total = pdo_fetchcolumn($sql, $params);
			if (!empty($total)) {
				$sql = 'SELECT * FROM ' . tablename('xc_lv_goods') . $condition . ' ORDER BY `status` DESC, `displayorder` DESC,
						`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, thumb FROM " . tablename('xc_lv_goods') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
//			if (!empty($row['thumb'])) {
//				file_delete($row['thumb']);
//			}
//			pdo_delete('xc_lv_goods', array('id' => $id));
			//修改成不直接删除，而设置deleted=1
			pdo_update("xc_lv_goods", array("deleted" => 1), array('id' => $id));
			message('删除成功！', referer(), 'success');
		} elseif ($operation == 'productdelete') {
			$id = intval($_GPC['id']);
			pdo_delete('xc_lv_product', array('id' => $id));
			message('删除成功！', '', 'success');
		}
		include $this->template('goods');
	}

	public function doWebfirm() {
	   global $_GPC, $_W;
	   	$pindex = max(1, intval($_GPC["page"]));
		$psize = 5;
		$con = " and status= 1 order by stime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	
		$list = pdo_fetchall("SELECT * FROM ".tablename('xc_lv_firm')."where uniacid='{$_W['uniacid']}' ".$con);
		
      $total = pdo_fetchall("SELECT * FROM ".tablename('xc_lv_firm')."where uniacid='{$_W['uniacid']}'");
      $total = count($total);
	   
	  $pager = pagination($total, $pindex, $psize);
	
		include $this->template('firm');
	}


	public function doWebOrder() {
		global $_W, $_GPC;
		load()->func('tpl');
		$set = $this->settings;
		if($set['ispay']==1){
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$status = $_GPC['status'];
			$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
			$condition = " o.weid = :weid";
			$paras = array(':weid' => $_W['uniacid']);

			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = TIMESTAMP;
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}

			if (!empty($_GPC['paytype'])) {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND o.address LIKE '%{$_GPC['member']}%'";
			}
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}
			if (!empty($sendtype)) {
				$condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND status != '3'";
			}

			$sql = 'SELECT COUNT(*) FROM ' . tablename('xc_lv_order') . ' AS `o` WHERE ' . $condition;
			$total = pdo_fetchcolumn($sql, $paras);

			if ($total > 0) {
				if ($_GPC['export'] != 'export') {
					$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				} else {
					$limit = '';
					$condition = " o.weid = :weid";
					$paras = array(':weid' => $_W['uniacid']);
				}

				$sql = 'SELECT * FROM ' . tablename('xc_lv_order') . ' AS `o` WHERE ' . $condition . ' ORDER BY
						`o`.`status` DESC, `o`.`createtime` DESC ' . $limit;

				$list = pdo_fetchall($sql,$paras);
				$pager = pagination($total, $pindex, $psize);

				$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款')
				);
				$orderstatus = array (
					'-1' => array('css' => 'default', 'name' => '已取消'),
					'0' => array('css' => 'danger', 'name' => '待付款'),
					'1' => array('css' => 'info', 'name' => '待发货'),
					'2' => array('css' => 'warning', 'name' => '待收货'),
					'3' => array('css' => 'success', 'name' => '已完成'),
					'5' => array('css' => 'warning', 'name' => '待审核'),
					'6' => array('css' => 'success', 'name' => '审核成功'),
			
				);

				foreach ($list as &$value) {
					$s = $value['status'];
					$value['statuscss'] = $orderstatus[$value['status']]['css'];
					$value['status'] = $orderstatus[$value['status']]['name'];
					$value['dispatch'] = pdo_fetchcolumn("SELECT `dispatchname` FROM " . tablename('xc_lv_dispatch') . " WHERE id = :id", array(':id' => $value['dispatch']));

					// 收货地址信息
					list($value['username'], $value['mobile'], $value['zipcode']) = explode('|', $value['address']);

					if ($s < 1) {
						$value['css'] = $paytype[$s]['css'];
						$value['paytype'] = $paytype[$s]['name'];
						continue;
					}
					$value['css'] = $paytype[$value['paytype']]['css'];
					if ($value['paytype'] == 2) {
						if (empty($value['transid'])) {
							$value['paytype'] = '支付宝支付';
						} else {
							$value['paytype'] = '微信支付';
						}
					} else {
						$value['paytype'] = $paytype[$value['paytype']]['name'];
					}
				}

				if ($_GPC['export'] != '') {
					/* 输入到CSV文件 */
					$html = "\xEF\xBB\xBF";

					/* 输出表头 */
					$filter = array(
						'ordersn' => '订单号',
						'username' => '姓名',
						'mobile' => '电话',
						'paytype' => '支付方式',
						'dispatch' => '配送方式',
						'dispatchprice' => '运费',
						'price' => '总价',
						'status' => '状态',
						'createtime' => '下单时间',
						'zipcode' => '邮政编码',
						'address' => '收货地址信息'
					);

					foreach ($filter as $key => $title) {
						$html .= $title . "\t,";
					}
					$html .= "\n";

					foreach ($list as $k => $v) {
						foreach ($filter as $key => $title) {
							if ($key == 'createtime') {
								$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
							} else {
								$html .= $v[$key] . "\t, ";
							}
						}
						$html .= "\n";
					}


					/* 输出CSV文件 */
					header("Content-type:text/csv");
					header("Content-Disposition:attachment; filename=全部数据.csv");
					echo $html;
					exit();

				}

			}

		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			
		 	     global $_GPC, $_W;
				
			
			$item = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
			$address = explode('|',$item['address']);
			
			
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
					message('请输入快递单号！');
				}
				$item = pdo_fetch("SELECT transid FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 1);
				}
				pdo_update(
					'xc_lv_order',
					array(
						'status' => 2,
						'remark' => $_GPC['remark'],
						'express' => $_GPC['express'],
						'expresscom' => $_GPC['expresscom'],
						'expresssn' => $_GPC['expresssn'],
					),
					array('id' => $id)
				);
				message('发货操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelsend')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
				}
				pdo_update(
					'xc_lv_order',
					array(
						'status' => 1,
						'remark' => $_GPC['remark'],
					),
					array('id' => $id)
				);
				message('取消发货操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				
				pdo_update('xc_lv_order', array('status' => 6, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单审核操作成功！'. $item['from_user'].$address[0], referer(), 'success');
			}
			if (checksubmit('cancel')) {
				pdo_update('xc_lv_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			
			if (checksubmit('shenhe')) {
				pdo_update('xc_lv_order', array('status' => 6, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelpay')) {
				pdo_update('xc_lv_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id, false);
				//减少积分
				$this->setOrderCredit($id, false);

				message('取消订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				pdo_update('xc_lv_order', array('status' => 1, 'paytype' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id);
				//增加积分
				$this->setOrderCredit($id);
				message('确认订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('close')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['reson']);
				}
				pdo_update('xc_lv_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单关闭操作成功！', referer(), 'success');
			}
			if (checksubmit('open')) {
				pdo_update('xc_lv_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				message('开启订单操作成功！', referer(), 'success');
			}
			// 订单取消
			if (checksubmit('cancelorder')) {
				if ($item['status'] == 1) {
					load()->model('mc');
					$memberId = mc_openid2uid($item['from_user']);
					mc_credit_update($memberId, 'credit2', $item['price'], array($_W['uid'], '微商城取消订单退款说明'));
				}
				pdo_update('xc_lv_order', array('status' => '-1'), array('id' => $item['id']));
				message('订单取消操作成功！', referer(), 'success');
			}

			$dispatch = pdo_fetch("SELECT * FROM " . tablename('xc_lv_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
			if (!empty($dispatch) && !empty($dispatch['express'])) {
				$express = pdo_fetch("select * from " . tablename('xc_lv_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
			}

			// 收货地址信息
			$item['user'] = explode('|', $item['address']);

			$goods = pdo_fetchall("SELECT g.*, o.total,o.ettotal,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('xc_lv_order_goods') .
					" o left join " . tablename('xc_lv_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
			$item['goods'] = $goods;
		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			if (pdo_delete('xc_lv_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}
		include $this->template('order');
		
		
		}
		//不用支付的情况
		else{
			
			
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$status = $_GPC['status'];
			$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
			$condition = " o.weid = :weid";
			$paras = array(':weid' => $_W['uniacid']);

			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = TIMESTAMP;
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}

			if (!empty($_GPC['paytype'])) {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND o.address LIKE '%{$_GPC['member']}%'";
			}
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}
			if (!empty($sendtype)) {
				$condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND status != '3'";
			}

			$sql = 'SELECT COUNT(*) FROM ' . tablename('xc_lv_order') . ' AS `o` WHERE ' . $condition;
			$total = pdo_fetchcolumn($sql, $paras);

			if ($total > 0) {
				if ($_GPC['export'] != 'export') {
					$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				} else {
					$limit = '';
					$condition = " o.weid = :weid";
					$paras = array(':weid' => $_W['uniacid']);
				}

				$sql = 'SELECT * FROM ' . tablename('xc_lv_order') . ' AS `o` WHERE ' . $condition . ' ORDER BY
						`o`.`status` DESC, `o`.`createtime` DESC ' . $limit;

				$list = pdo_fetchall($sql,$paras);
				$pager = pagination($total, $pindex, $psize);

			
				$orderstatus = array (
					'-1' => array('css' => 'default', 'name' => '已取消'),
						'5' => array('css' => 'danger', 'name' => '等待审核'),
			        '6' => array('css' => 'success', 'name' => '审核成功'),
			
				);

				foreach ($list as &$value) {
					$s = $value['status'];
					$value['statuscss'] = $orderstatus[$value['status']]['css'];
					$value['status'] = $orderstatus[$value['status']]['name'];
					$value['dispatch'] = pdo_fetchcolumn("SELECT `dispatchname` FROM " . tablename('xc_lv_dispatch') . " WHERE id = :id", array(':id' => $value['dispatch']));

					// 收货地址信息
					list($value['username'], $value['mobile'], $value['zipcode']) = explode('|', $value['address']);

					if ($s < 1) {
						$value['css'] = $paytype[$s]['css'];
						$value['paytype'] = $paytype[$s]['name'];
						continue;
					}
					$value['css'] = $paytype[$value['paytype']]['css'];
					if ($value['paytype'] == 2) {
						if (empty($value['transid'])) {
							$value['paytype'] = '支付宝支付';
						} else {
							$value['paytype'] = '微信支付';
						}
					} else {
						$value['paytype'] = $paytype[$value['paytype']]['name'];
					}
				}

				if ($_GPC['export'] != '') {
					/* 输入到CSV文件 */
					$html = "\xEF\xBB\xBF";

					/* 输出表头 */
					$filter = array(
						'ordersn' => '订单号',
						'username' => '姓名',
						'mobile' => '电话',
						'paytype' => '支付方式',
						'dispatch' => '配送方式',
						'dispatchprice' => '运费',
						'price' => '总价',
						'status' => '状态',
						'createtime' => '下单时间',
						'zipcode' => '邮政编码',
						'address' => '收货地址信息'
					);

					foreach ($filter as $key => $title) {
						$html .= $title . "\t,";
					}
					$html .= "\n";

					foreach ($list as $k => $v) {
						foreach ($filter as $key => $title) {
							if ($key == 'createtime') {
								$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
							} else {
								$html .= $v[$key] . "\t, ";
							}
						}
						$html .= "\n";
					}


					/* 输出CSV文件 */
					header("Content-type:text/csv");
					header("Content-Disposition:attachment; filename=全部数据.csv");
					echo $html;
					exit();

				}

			}

		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
					message('请输入快递单号！');
				}
				$item = pdo_fetch("SELECT transid FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 1);
				}
				pdo_update(
					'xc_lv_order',
					array(
						'status' => 2,
						'remark' => $_GPC['remark'],
						'express' => $_GPC['express'],
						'expresscom' => $_GPC['expresscom'],
						'expresssn' => $_GPC['expresssn'],
					),
					array('id' => $id)
				);
				message('发货操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelsend')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
				}
				pdo_update(
					'xc_lv_order',
					array(
						'status' => 1,
						'remark' => $_GPC['remark'],
					),
					array('id' => $id)
				);
				message('取消发货操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				pdo_update('xc_lv_order', array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancel')) {
				pdo_update('xc_lv_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			
			if (checksubmit('shenhe')) {
				pdo_update('xc_lv_order', array('status' => 6, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelpay')) {
				pdo_update('xc_lv_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id, false);
				//减少积分
				$this->setOrderCredit($id, false);

				message('取消订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				pdo_update('xc_lv_order', array('status' => 1, 'paytype' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id);
				//增加积分
				$this->setOrderCredit($id);
				message('确认订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('close')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['reson']);
				}
				pdo_update('xc_lv_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单关闭操作成功！', referer(), 'success');
			}
			if (checksubmit('open')) {
				pdo_update('xc_lv_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				message('开启订单操作成功！', referer(), 'success');
			}
			// 订单取消
			if (checksubmit('cancelorder')) {
				if ($item['status'] == 1) {
					load()->model('mc');
					$memberId = mc_openid2uid($item['from_user']);
					mc_credit_update($memberId, 'credit2', $item['price'], array($_W['uid'], '微商城取消订单退款说明'));
				}
				pdo_update('xc_lv_order', array('status' => '-1'), array('id' => $item['id']));
				message('订单取消操作成功！', referer(), 'success');
			}

			$dispatch = pdo_fetch("SELECT * FROM " . tablename('xc_lv_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
			if (!empty($dispatch) && !empty($dispatch['express'])) {
				$express = pdo_fetch("select * from " . tablename('xc_lv_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
			}

			// 收货地址信息
			$item['user'] = explode('|', $item['address']);

			$goods = pdo_fetchall("SELECT g.*, o.total,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('xc_lv_order_goods') .
					" o left join " . tablename('xc_lv_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
			$item['goods'] = $goods;
		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			if (pdo_delete('xc_lv_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}
		include $this->template('order');
		
		
		
			
			}
	}
	//设置订单商品的库存 minus  true 减少  false 增加
	private function setOrderStock($id = '', $minus = true) {
		$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,g.total as goodstotal,o.total,o.optionid,g.sales FROM " . tablename('xc_lv_order_goods') . " o left join " . tablename('xc_lv_goods') . " g on o.goodsid=g.id "
				. " WHERE o.orderid='{$id}'");
		foreach ($goods as $item) {
			if ($minus) {
				//属性
				if (!empty($item['optionid'])) {
					pdo_query("update " . tablename('xc_lv_goods_option') . " set stock=stock-:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
				}
				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] - $item['total'];
				}
				$data['sales'] = $item['sales'] + $item['total'];
				pdo_update('xc_lv_goods', $data, array('id' => $item['id']));
			} else {
				//属性
				if (!empty($item['optionid'])) {
					pdo_query("update " . tablename('xc_lv_goods_option') . " set stock=stock+:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
				}
				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] + $item['total'];
				}
				$data['sales'] = $item['sales'] - $item['total'];
				pdo_update('xc_lv_goods', $data, array('id' => $item['id']));
			}
		}
	}

	public function doWebNotice() {
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
		$operation = in_array($operation, array('display')) ? $operation : 'display';
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		if (!empty($_GPC['date'])) {
			$starttime = strtotime($_GPC['date']['start']);
			$endtime = strtotime($_GPC['date']['end']) + 86399;
		} else {
			$starttime = strtotime('-1 month');
			$endtime = time();
		}
		$where = " WHERE `weid` = :weid AND `createtime` >= :starttime AND `createtime` < :endtime";
		$paras = array(
			':weid' => $_W['uniacid'],
			':starttime' => $starttime,
			':endtime' => $endtime
		);
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where .= " AND `feedbackid`=:feedbackid";
			$paras[':feedbackid'] = $keyword;
		}
		$type = empty($_GPC['type']) ? 0 : $_GPC['type'];
		$type = intval($type);
		if ($type != 0) {
			$where .= " AND `type`=:type";
			$paras[':type'] = $type;
		}
		$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
		$status = intval($status);
		if ($status != -1) {
			$where .= " AND `status` = :status";
			$paras[':status'] = $status;
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xc_lv_feedback') . $where, $paras);
		$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_feedback') . $where . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
		$pager = pagination($total, $pindex, $psize);
		$transids = array();
		foreach ($list as $row) {
			$transids[] = $row['transid'];
		}
		if (!empty($transids)) {
			$sql = "SELECT * FROM " . tablename('xc_lv_order') . " WHERE weid='{$_W['uniacid']}' AND transid IN ( '" . implode("','", $transids) . "' )";
			$orders = pdo_fetchall($sql, array(), 'transid');
		}
//		$addressids = array();
//		if(is_array($orders)){
//			foreach ($orders as $transid => $order) {
//				$addressids[] = $order['addressid'];
//			}
//		}
//		$addresses = array();
//		if (!empty($addressids)) {
//			$sql = "SELECT * FROM " . tablename('mc_member_address') . " WHERE uniacid='{$_W['uniacid']}' AND id IN ( '" . implode("','", $addressids) . "' )";
//			$addresses = pdo_fetchall($sql, array(), 'id');
//		}
		foreach ($list as &$feedback) {
			$transid = $feedback['transid'];
			$order = $orders[$transid];
			$feedback['order'] = $order;
//			$addressid = $order['addressid'];
//			$feedback['address'] = $addresses[$addressid];
		}
		include $this->template('notice');
	}

	public function getCartTotal() {
		global $_W;
		$cartotal = pdo_fetchcolumn("select sum(total) from " . tablename('xc_lv_cart') . " where weid = '{$_W['uniacid']}' and from_user='{$_W['fans']['from_user']}'");
		return empty($cartotal) ? 0 : $cartotal;
	}
	private function getFeedbackType($type) {
		$types = array(1 => '维权', 2 => '告警');
		return $types[intval($type)];
	}
	private function getFeedbackStatus($status) {
		$statuses = array('未解决', '用户同意', '用户拒绝');
		return $statuses[intval($status)];
	}
	public function doMobilelist() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$set = $this->settings;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('xc_lv_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$recommandcategory = array();
		foreach ($category as &$c) {
			if ($c['isrecommand'] == 1) {
				$c['list'] = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 AND status = '1'  and pcate='{$c['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$c['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}'");
				$c['pager'] = pagination($c['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
				$recommandcategory[] = $c;
			}
			if (!empty($children[$c['id']])) {
				foreach ($children[$c['id']] as &$child) {
					if ($child['isrecommand'] == 1) {
						$child['list'] = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1'  and pcate='{$c['id']}' and ccate='{$child['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
						$child['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}' and ccate='{$child['id']}' ");
						$child['pager'] = pagination($child['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
						$recommandcategory[] = $child;
					}
				}
				unset($child);
			}
		}
		unset($c);
		$carttotal = $this->getCartTotal();
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('xc_lv_adv') . " where enabled=1 and weid= '{$_W['uniacid']}' order by displayorder asc");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
		//首页推荐
		$rpindex = max(1, intval($_GPC['rpage']));
		$rpsize = 4;
		$condition = ' and isrecommand=1';
		$rlist = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($rpindex - 1) * $rpsize . ',' . $rpsize);

		include $this->template('list');
	}
	public function doMobilelistmore_rec() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = ' and isrecommand=1 ';
		$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		include $this->template('list_more');
	}
	public function doMobilelistmore() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = '';
		$params = array(':weid' => $_W['uniacid']);
		$cid = intval($_GPC['ccate']);
		if (empty($cid)) {
			return NULL;
		}

		$catePid = $_GPC['pcate'];
		if (empty($catePid)) {
			$condition .= ' AND `pcate` = :pcate';
			$params[':pcate'] = $cid;
		} else {
			$condition .= ' AND `ccate` = :ccate';
			$params[':ccate'] = $cid;
		}


		$sql = 'SELECT * FROM ' . tablename('xc_lv_goods') . ' WHERE `weid` = :weid AND `deleted` = :deleted AND `status` = :status ' . $condition .
				' ORDER BY `displayorder` DESC, `sales` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$params[':deleted'] = 0;
		$params[':status'] = 1;
		$list = pdo_fetchall($sql, $params);

		include $this->template('list_more');

	}
	

	public function doMobilelist2() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC["page"]));
		$psize = 10000;
		$condition = '';
		
		//ajax提交信息
	
		
		
	
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('xc_lv_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$sort = empty($_GPC['sort']) ? 0 : $_GPC['sort'];
		$sortfield = "displayorder asc";
		$sortb0 = empty($_GPC['sortb0']) ? "desc" : $_GPC['sortb0'];
		$sortb1 = empty($_GPC['sortb1']) ? "desc" : $_GPC['sortb1'];
		$sortb2 = empty($_GPC['sortb2']) ? "desc" : $_GPC['sortb2'];
		$sortb3 = empty($_GPC['sortb3']) ? "asc" : $_GPC['sortb3'];
		if ($sort == 0) {
			$sortb00 = $sortb0 == "desc" ? "asc" : "desc";
			$sortfield = "createtime " . $sortb0;
			$sortb11 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 1) {
			$sortb11 = $sortb1 == "desc" ? "asc" : "desc";
			$sortfield = "sales " . $sortb1;
			$sortb00 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 2) {
			$sortb22 = $sortb2 == "desc" ? "asc" : "desc";
			$sortfield = "viewcount " . $sortb2;
			$sortb00 = "desc";
			$sortb11 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 3) {
			$sortb33 = $sortb3 == "asc" ? "desc" : "asc";
			$sortfield = "marketprice " . $sortb3;
			$sortb00 = "desc";
			$sortb11 = "desc";
			$sortb22 = "desc";
		}
		$sorturl = $this->createMobileUrl('list2', array("keyword" => $_GPC['keyword'], "pcate" => $_GPC['pcate'], "ccate" => $_GPC['ccate']), true);
		if (!empty($_GPC['isnew'])) {
			$condition .= " AND isnew = 1";
			$sorturl.="&isnew=1";
		}
		if (!empty($_GPC['ishot'])) {
			$condition .= " AND ishot = '1'";
			$sorturl.="&ishot=1";
		}
		if (!empty($_GPC['isdiscount'])) {
			$condition .= " AND isdiscount = 1";
			$sorturl.="&isdiscount=1";
		}
		if (!empty($_GPC['istime'])) {
			$condition .= " AND istime = 1 and " . time() . ">=timestart and " . time() . "<=timeend";
			$sorturl.="&istime=1";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		
		
		
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
			
			
				unset($category[$index]);
			}
		}
		
		$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY $sortfield LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		
		foreach ($list as &$r) {
			if ($r['istime'] == 1) {
				$arr = $this->time_tran($r['timeend']);
				$r['timelaststr'] = $arr[0];
				$r['timelast'] = $arr[1];
			}
		}
		unset($r);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' $condition");
		$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
		$carttotal = $this->getCartTotal();
		include $this->template('list2');
	}


	public function doMobilehuodong() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC["page"]));
		$psize = 10;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('xc_lv_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$sort = empty($_GPC['sort']) ? 0 : $_GPC['sort'];
		$sortfield = "displayorder asc";
		$sortb0 = empty($_GPC['sortb0']) ? "desc" : $_GPC['sortb0'];
		$sortb1 = empty($_GPC['sortb1']) ? "desc" : $_GPC['sortb1'];
		$sortb2 = empty($_GPC['sortb2']) ? "desc" : $_GPC['sortb2'];
		$sortb3 = empty($_GPC['sortb3']) ? "asc" : $_GPC['sortb3'];
		if ($sort == 0) {
			$sortb00 = $sortb0 == "desc" ? "asc" : "desc";
			$sortfield = "createtime " . $sortb0;
			$sortb11 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 1) {
			$sortb11 = $sortb1 == "desc" ? "asc" : "desc";
			$sortfield = "sales " . $sortb1;
			$sortb00 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 2) {
			$sortb22 = $sortb2 == "desc" ? "asc" : "desc";
			$sortfield = "viewcount " . $sortb2;
			$sortb00 = "desc";
			$sortb11 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 3) {
			$sortb33 = $sortb3 == "asc" ? "desc" : "asc";
			$sortfield = "marketprice " . $sortb3;
			$sortb00 = "desc";
			$sortb11 = "desc";
			$sortb22 = "desc";
		}
		$sorturl = $this->createMobileUrl('huodong', array("keyword" => $_GPC['keyword'], "pcate" => $_GPC['pcate'], "ccate" => $_GPC['ccate']), true);
		if (!empty($_GPC['isnew'])) {
			$condition .= " AND isnew = 1";
			$sorturl.="&isnew=1";
		}
		if (!empty($_GPC['ishot'])) {
			$condition .= " AND ishot = 1";
			$sorturl.="&ishot=1";
		}
		if (!empty($_GPC['isdiscount'])) {
			$condition .= " AND isdiscount = 1";
			$sorturl.="&isdiscount=1";
		}
		if (!empty($_GPC['istime'])) {
			$condition .= " AND istime = 1 and " . time() . ">=timestart and " . time() . "<=timeend";
			$sorturl.="&istime=1";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY $sortfield LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		foreach ($list as &$r) {
			if ($r['istime'] == 1) {
				$arr = $this->time_tran($r['timeend']);
				$r['timelaststr'] = $arr[0];
				$r['timelast'] = $arr[1];
			}
		}

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_lv_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' $condition");
		$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));


		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('xc_lv_adv') . " where enabled=1 and weid= '{$_W['uniacid']}' order by displayorder asc");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
		include $this->template('huodong');
	}

	public function doMobilefirm() {
		global $_GPC, $_W;
	    $set = $this->settings;
		$list = pdo_fetchall("SELECT * FROM ".tablename('xc_lv_firm')."where uniacid='{$_W['uniacid']}' ");
		
	 
		
		if($_POST){
			
	          $cutime = date('Y-m-d H:i:s',time());
		      $time = strtotime($_GPC['stime']);
			  $stime = date('Y-m-d H:i:s',$time);
		      $data = array(
		 
		      'city'=>$_GPC['city'],
		      'city2'=>$_GPC['city2'],
		      'stime'=>$stime,
		      'xq'=>$_GPC['xq'],
		      'mark'=>$_GPC['mark'],  
		      'xingming'=>$_GPC['xingming'],
		      'mobile'=>$_GPC['tel'],
		      'haoma'=>$_GPC['haoma'],
		      'openid'=>$_W['openid'],
		      'uniacid'=>$_W['uniacid'],
		    
		 );
		 
	      $res =  	 pdo_insert('xc_lv_firm',$data);
		
		 	
		if($res){
						
			//发送模板消息
			$sendurl='';
			$dztemid = $set['dztemid'];
			$glopenid = $set['glopenid'];
			
			
			$content = array('first' => array(
			                  'value' => '您好，详细信息如下。', 
							  ), 
							  'keyword1' => array(
							          'value' => '从出发城市'.$_GPC['city'].'到目的地城市'.$_GPC['city2'].',出行人数：'.$_GPC['xq'].',留言：'.$_GPC['remark'].',姓名：'.$_GPC['xingming'].',联系方式：'.$_GPC['tel'].',预约时间'.$cutime, 
									  
									  ), 
							  'keyword2' => array(
							            'value' => $stime, 
										), 
							 
							   'remark' => array(
							            'value' =>'有任何问题请随时与我们联系，谢谢。', 
										)
								);
								
		   $this->sendtpl($glopenid, $sendurl, $dztemid, $content);
			message('成功',$this->createMobileurl('firm'),'success');
			
	
   					
			
			
		}else{
			
			message('失败',$this->createMobileurl('firm'),'error');
		}
		

			
		}
		include $this->template('firm');  
	}


	function time_tran($the_time) {
		$timediff = $the_time - time();
		$days = intval($timediff / 86400);
		if (strlen($days) <= 1) {
			$days = "0" . $days;
		}
		$remain = $timediff % 86400;
		$hours = intval($remain / 3600);
		;
		if (strlen($hours) <= 1) {
			$hours = "0" . $hours;
		}
		$remain = $remain % 3600;
		$mins = intval($remain / 60);
		if (strlen($mins) <= 1) {
			$mins = "0" . $mins;
		}
		$secs = $remain % 60;
		if (strlen($secs) <= 1) {
			$secs = "0" . $secs;
		}
		$ret = "";
		if ($days > 0) {
			$ret.=$days . " 天 ";
		}
		if ($hours > 0) {
			$ret.=$hours . ":";
		}
		if ($mins > 0) {
			$ret.=$mins . ":";
		}
		$ret.=$secs;
		return array("倒计时 " . $ret, $timediff);
	}
	public function doMobileMyCart() {
		global $_W, $_GPC;
		//$this->checkAuth();
		$op = $_GPC['op'];
		if ($op == 'add') {
			$goodsid = intval($_GPC['id']);
			$total = intval($_GPC['total']);
			$total = empty($total) ? 1 : $total;
			$optionid = intval($_GPC['optionid']);
			$goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('xc_lv_goods') . " WHERE id = :id", array(':id' => $goodsid));
			if (empty($goods)) {
				$result['message'] = '抱歉，该商品不存在或是已经被删除！';
				message($result, '', 'ajax');
			}
			$marketprice = $goods['marketprice'];
			if (!empty($optionid)) {
				$option = pdo_fetch("select marketprice from " . tablename('xc_lv_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
				if (!empty($option)) {
					$marketprice = $option['marketprice'];
				}
			}
			$row = pdo_fetch("SELECT id, total FROM " . tablename('xc_lv_cart') . " WHERE from_user = :from_user AND weid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['fans']['from_user'], ':goodsid' => $goodsid,':optionid'=>$optionid));
			if ($row == false) {
				//不存在
				$data = array(
					'weid' => $_W['uniacid'],
					'goodsid' => $goodsid,
					'goodstype' => $goods['type'],
					'marketprice' => $marketprice,
					'from_user' => $_W['fans']['from_user'],
					'total' => $total,
					'optionid' => $optionid
				);
				pdo_insert('xc_lv_cart', $data);
			} else {
				//累加最多限制购买数量
				$t = $total + $row['total'];
				if (!empty($goods['maxbuy'])) {
					if ($t > $goods['maxbuy']) {
						$t = $goods['maxbuy'];
					}
				}
				//存在
				$data = array(
					'marketprice' => $marketprice,
					'total' => $t,
					'optionid' => $optionid
				);
				pdo_update('xc_lv_cart', $data, array('id' => $row['id']));
			}
			//返回数据
			$carttotal = $this->getCartTotal();
			$result = array(
				'result' => 1,
				'total' => $carttotal
			);
			die(json_encode($result));
		} else if ($op == 'clear') {
			pdo_delete('xc_lv_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid']));
			die(json_encode(array("result" => 1)));
		} else if ($op == 'remove') {
			$id = intval($_GPC['id']);
			pdo_delete('xc_lv_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid'], 'id' => $id));
			die(json_encode(array("result" => 1, "cartid" => $id)));
		} else if ($op == 'update') {
			$id = intval($_GPC['id']);
			$num = intval($_GPC['num']);
			$sql = "update " . tablename('xc_lv_cart') . " set total=$num where id=:id";
			pdo_query($sql, array(":id" => $id));
			die(json_encode(array("result" => 1)));
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$totalprice = 0;
			if (!empty($list)) {
				foreach ($list as &$item) {
					$goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('xc_lv_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
					if ($option) {
						$goods['title'] = $goods['title'];
						$goods['optionname'] = $option['title'];
						$goods['marketprice'] = $option['marketprice'];
						$goods['total'] = $option['stock'];
					}
					$item['goods'] = $goods;
					$item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
					$totalprice += $item['totalprice'];
				}
				unset($item);
			}
			include $this->template('cart');
		}
	}
	public function doMobileConfirm() {
		global $_W, $_GPC;
		//checkauth();
		$totalprice = 0;
		$allgoods = array();
		$id = intval($_GPC['id']);
		$optionid = intval($_GPC['optionid']);
		$total = intval($_GPC['total']);
		$ettotal = intval($_GPC['ettotal']);
	
		 $retime = $_GET['retime'];
		
		 $retime = strtotime($retime);
		 
	
		 $retime = date('Y-m-d',$retime);    
		 $qian = intval($_GET['price']);
		 
	  
		
	   
		if ( (empty($total)) || ($total < 1) ) {
			$total = 1;
		}
		$direct = false; //是否是直接购买
		$returnUrl = ''; //当前连接
		if (!empty($id)) {
			$sql = 'SELECT `id`, `thumb`, `title`, `weight`, `marketprice`,`productprice`, `total`, `type`, `totalcnf`, `sales`, `unit`, `istime`, `timeend`, `usermaxbuy`,`detime`
					FROM ' .tablename('xc_lv_goods') . ' WHERE `id` = :id';
			$item = pdo_fetch($sql, array(':id' => $id));
            
			        
			//判断日期是否存在
		    $detime = json_decode($item['detime'],true);
			
			//print_r($detime);
			


		$arr = array($retime,$qian);

     
			foreach($detime[0] as $key=>$v){
				
				$av[] = array($detime[0][$key],$detime[1][$key]);	
		       	}

					  if(in_array($arr, $av)){
					  	
						
					  }
					  else{
					  	
					   message('不存在该日期和价格', $this->createMobileUrl('detail', array('id' => $id)), 'error');
						
					  }
					  
					
			if (empty($item)) {
				message('商品不存在或已经下架', $this->createMobileUrl('detail', array('id' => $id)), 'error');
			}
			if ($item['istime'] == 1) {
				if (time() > $item['timeend']) {
					$backUrl = $this->createMobileUrl('detail', array('id' => $id));
					$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
					message('抱歉，商品限购时间已到，无法购买了！', $backUrl, "error");
				}
			}


			if (!empty($optionid)) {
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
				if ($option) {
					$item['optionid'] = $optionid;
					$item['title'] = $item['title'];
					$item['optionname'] = $option['title'];
					$item['marketprice'] = $option['marketprice'];
					$item['weight'] = $option['weight'];
				}
			}
			$item['stock'] = $item['total'];
			$item['total'] = $total;
			$item['ettotal'] = $ettotal;
	        
			
			
			//这个是原时间
			//$item['totalprice'] = $total * $item['marketprice']+$ettotal*$item['productprice'];
			//现在修改的时间
			$item['totalprice'] = $total * $qian+$ettotal*$qian*0.5;
			
			$allgoods[] = $item;
			$totalprice += $item['totalprice'];
			if ($item['type'] == 1) {
				$needdispatch = true;
			}
			$direct = true;

			// 检查用户最多购买数量
			$sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename('xc_lv_order_goods') . ' AS `og` JOIN ' . tablename('xc_lv_order') .
				' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`from_user` = :from_user';
			$params = array(':goodsid' => $id, ':from_user' => $_W['fans']['from_user']);
			$orderTotal = pdo_fetchcolumn($sql, $params);
			if ( (($orderTotal + $item['total']) > $item['usermaxbuy']) && (!empty($item['usermaxbuy']))) {
				message('您已经超过购买数量了', $this->createMobileUrl('detail', array('id' => $id)), 'error');
			}

			$returnUrl = urlencode($_W['siteurl']);
		}
		if (!$direct) {
			//如果不是直接购买（从购物车购买）
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			if (!empty($list)) {
				foreach ($list as &$g) {
					$item = pdo_fetch("select id,thumb,title,weight,marketprice,total,type,totalcnf,sales,unit from " . tablename("xc_lv_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
					if ($option) {
						$item['optionid'] = $g['optionid'];
						$item['title'] = $item['title'];
						$item['optionname'] = $option['title'];
						$item['marketprice'] = $option['marketprice'];
						$item['weight'] = $option['weight'];
					}
					$item['stock'] = $item['total'];
					$item['total'] = $g['total'];
					$item['totalprice'] = $g['total'] * $item['marketprice'];
					$allgoods[] = $item;
					$totalprice += $item['totalprice'];
					if ($item['type'] == 1) {
						$needdispatch = true;
					}
				}
				unset($g);
			}
			$returnUrl = $this->createMobileUrl("confirm");
		}
		if (count($allgoods) <= 0) {
			header("location: " . $this->createMobileUrl('myorder'));
			exit();
		}

		//配送方式
		$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("xc_lv_dispatch") . " WHERE weid = {$_W['uniacid']} order by displayorder desc");
		foreach ($dispatch as &$d) {
			$weight = 0;
			foreach ($allgoods as $g) {
				$weight += $g['weight'] * $g['total'];
			}
			$price = 0;
			if ($weight <= $d['firstweight']) {
				$price = $d['firstprice'];
			} else {
				$price = $d['firstprice'];
				$secondweight = $weight - $d['firstweight'];
				if ($secondweight % $d['secondweight'] == 0) {
					$price += (int)($secondweight / $d['secondweight']) * $d['secondprice'];
				} else {
					$price += (int)($secondweight / $d['secondweight'] + 1) * $d['secondprice'];
				}
			}
			$d['price'] = $price;
		}
		unset($d);

		if (checksubmit('submit')) {
			// 是否自提
			$sendtype = 1;
			$address = pdo_fetch("SELECT * FROM " . tablename('xc_lv_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
			if (empty($address)) {
				message('抱歉，请您填写收货地址！');
			}
			// 商品价格
			$goodsprice = 0;
			foreach ($allgoods as $row) {
				$goodsprice += $row['totalprice'];
			}
			// 运费
			$dispatchid = intval($_GPC['dispatch']);
			$dispatchprice = 0;
			foreach ($dispatch as $d) {
				if ($d['id'] == $dispatchid) {
					$dispatchprice = $d['price'];
					$sendtype = $d['dispatchtype'];
				}
			}

			$data = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'ordersn' => date('md') . random(4, 1),
				'price' => $goodsprice + $dispatchprice,
				'dispatchprice' => $dispatchprice,
				'goodsprice' => $goodsprice,
				'status' => 0,
				'retime' =>$retime,
				'sendtype' => intval($sendtype),
				'dispatch' => $dispatchid,
				'goodstype' => intval($item['type']),
				'remark' => $_GPC['remark'],
				'address' =>  $address['username'] . '|' . $address['mobile'] . '|' . $address['zipcode']
							. '|' . $address['province'] . '|' . $address['city'] . '|' .
							$address['district'] . '|' . $address['address'],
				'createtime' => TIMESTAMP
			);

			pdo_insert('xc_lv_order', $data);
			$orderid = pdo_insertid();
			//插入订单商品
			foreach ($allgoods as $row) {
				if (empty($row)) {
					continue;
				}
				$d = array(
					'weid' => $_W['uniacid'],
					'goodsid' => $row['id'],
					'orderid' => $orderid,
					'total' => $row['total'],
					'price' => $qian,
			        'ettotal'=>$row['ettotal'],
					'productprice'=>$qian*0.5,
					'createtime' => TIMESTAMP,
					'optionid' => $row['optionid']
				);
				$o = pdo_fetch("select title from " . tablename('xc_lv_goods_option') . " where id=:id limit 1", array(":id" => $row['optionid']));
				if (!empty($o)) {
					$d['optionname'] = $o['title'];
				}
				pdo_insert('xc_lv_order_goods', $d);
			}
			// 清空购物车
			if (!$direct) {
				pdo_delete("xc_lv_cart", array("weid" => $_W['uniacid'], "from_user" => $_W['fans']['from_user']));
			}
			// 变更商品库存
			if (empty($item['totalcnf'])) {
				$this->setOrderStock($orderid);
			}
			message('提交订单成功,现在跳转到付款页面...', $this->createMobileUrl('pay', array('orderid' => $orderid)), 'success');
		}
		$carttotal = $this->getCartTotal();
		$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
		$row = pdo_fetch("SELECT * FROM " . tablename('xc_lv_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['fans']['uid']));
		include $this->template('confirm');
	}
	
	
	//报名提交页面
		public function doMobileBook() {
		global $_W, $_GPC;
		$set = $this->settings;
		
		
		//checkauth();
		$totalprice = 0;
		$allgoods = array();
		$id = intval($_GPC['id']);
		$optionid = intval($_GPC['optionid']);
		$total = intval($_GPC['total']);
		$ettotal = intval($_GPC['ettotal']);
	    $retime = $_GET['retime'];
		$qian = intval($_GET['price']);
		
		
		
		

		if ( (empty($total)) || ($total < 1) ) {
			$total = 1;
		}
		$direct = false; //是否是直接购买
		$returnUrl = ''; //当前连接
		
		if (!empty($id)) {
			$sql = 'SELECT `id`, `thumb`, `title`, `weight`, `marketprice`, `productprice`,`total`, `type`, `totalcnf`, `sales`, `unit`, `istime`, `timeend`, `usermaxbuy`,`detime`
					FROM ' .tablename('xc_lv_goods') . ' WHERE `id` = :id';
			$item = pdo_fetch($sql, array(':id' => $id));
			//判断日期是否存在
		    $detime = json_decode($item['detime'],true);
			$arr = array($retime,$qian);
			foreach($detime[0] as $key=>$v){
				
				$av[] = array($detime[0][$key],$detime[1][$key]);	
		       	}

					  if(in_array($arr, $av)){}
					  else{
					  	
					   message('不存在该日期和价格', $this->createMobileUrl('detail', array('id' => $id)), 'error');
						
					  }
		
			if (empty($item)) {
				message('商品不存在或已经下架', $this->createMobileUrl('detail', array('id' => $id)), 'error');
			}
			if ($item['istime'] == 1) {
				if (time() > $item['timeend']) {
					$backUrl = $this->createMobileUrl('detail', array('id' => $id));
					$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
					message('抱歉，商品限购时间已到，无法购买了！', $backUrl, "error");
				}
			}
	

			if (!empty($optionid)) {
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
				if ($option) {
					$item['optionid'] = $optionid;
					$item['title'] = $item['title'];
					$item['optionname'] = $option['title'];
					$item['marketprice'] = $option['marketprice'];
					$item['weight'] = $option['weight'];
				}
			}
			$item['stock'] = $item['total'];
			$item['total'] = $total;
			$item['ettotal'] = $ettotal;
	
			
			//这个是原时间
			//$item['totalprice'] = $total * $item['marketprice']+$ettotal*$item['productprice'];
			//现在修改的时间
			$item['totalprice'] = $total * $qian+$ettotal*$qian*0.5;
			$allgoods[] = $item;
			$totalprice += $item['totalprice'];
		
		
			if ($item['type'] == 1) {
				$needdispatch = true;
			}
			$direct = true;

			// 检查用户最多购买数量
			$sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename('xc_lv_order_goods') . ' AS `og` JOIN ' . tablename('xc_lv_order') .
				' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`from_user` = :from_user';
			$params = array(':goodsid' => $id, ':from_user' => $_W['fans']['from_user']);
			$orderTotal = pdo_fetchcolumn($sql, $params);
			if ( (($orderTotal + $item['total']) > $item['usermaxbuy']) && (!empty($item['usermaxbuy']))) {
				message('您已经超过购买数量了', $this->createMobileUrl('detail', array('id' => $id)), 'error');
			}

			$returnUrl = urlencode($_W['siteurl']);
		}

		if (count($allgoods) <= 0) {
			header("location: " . $this->createMobileUrl('myorder'));
			exit();
		}



		if (checksubmit('submit')) {
			// 是否自提
			$sendtype = 1;
			$address = pdo_fetch("SELECT * FROM " . tablename('xc_lv_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
			if (empty($address)) {
				message('抱歉，请您填写收货地址！');
			}
			// 商品价格
			$goodsprice = 0;
			foreach ($allgoods as $row) {
				$goodsprice += $row['totalprice'];
			}
			// 运费
			$dispatchid = intval($_GPC['dispatch']);
			$dispatchprice = 0;
			foreach ($dispatch as $d) {
				if ($d['id'] == $dispatchid) {
					$dispatchprice = $d['price'];
					$sendtype = $d['dispatchtype'];
				}
			}

			$data = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'ordersn' => date('md') . random(4, 1),
				'price' => $goodsprice + $dispatchprice,
				'dispatchprice' => $dispatchprice,
				'goodsprice' => $goodsprice,
				'status' => 5,
				'sendtype' => intval($sendtype),
				'dispatch' => $dispatchid,
				'retime' =>$retime,
				'goodstype' => intval($item['type']),
				'remark' => $_GPC['remark'],
				'address' =>  $address['username'] . '|' . $address['mobile'] . '|' . $address['zipcode']
							. '|' . $address['province'] . '|' . $address['city'] . '|' .
							$address['district'] . '|' . $address['address'],
				'createtime' => TIMESTAMP
			);

			pdo_insert('xc_lv_order', $data);
			$orderid = pdo_insertid();
			//插入订单商品
			foreach ($allgoods as $row) {
				if (empty($row)) {
					continue;
				}
				$d = array(
					'weid' => $_W['uniacid'],
					'goodsid' => $row['id'],
					'orderid' => $orderid,
					'total' => $row['total'],
					'price' => $qian,
			        'ettotal'=>$row['ettotal'],
					'productprice'=>$qian*0.5,
					'createtime' => TIMESTAMP,
					'optionid' => $row['optionid']
				);
				$o = pdo_fetch("select title from " . tablename('xc_lv_goods_option') . " where id=:id limit 1", array(":id" => $row['optionid']));
				if (!empty($o)) {
					$d['optionname'] = $o['title'];
				}
				pdo_insert('xc_lv_order_goods', $d);
			}
			// 清空购物车
			if (!$direct) {
				pdo_delete("xc_lv_cart", array("weid" => $_W['uniacid'], "from_user" => $_W['fans']['from_user']));
			}
			// 变更商品库存
			if (empty($item['totalcnf'])) {
				$this->setOrderStock($orderid);
			}

			//报名成功发送模板消息
			
			$sendurl = '';
			$succtemid = $set['succtemid'];
			$content = array(
			
			
			'first'=>array(
			    'value'=>'您已经成功报名',
			),
			'keyword1'=>array(
			
			    'value'=>$item['title'],
			    
			),
			'keyword2'=>array(
			
			   'value'=>'无',
			   
			),
			'keyword3'=>array(
			
	             'value'=>$address['username'],		
			),
			
			'keyword4'=>array(
			  'value'=>$address['mobile'],
			),
			'keyword5'=>array(
			
			   'value'=>$data['ordersn'],
			
			),
			
			'remark'=>array(
			  'value'=>$_GPC['remark'],
			)

			
			);
			
			
			$this->sendtpl($_W['fans']['from_user'], $sendurl, $succtemid, $content);
			
			message('报名成功', $this->createMobileUrl('myorder',array('status'=>5)), 'success');
		}
		$carttotal = $this->getCartTotal();
		$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
		$row = pdo_fetch("SELECT * FROM " . tablename('xc_lv_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['fans']['uid']));
		include $this->template('book');
	}

	//设置订单积分
	public function setOrderCredit($orderid, $add = true) {
		global $_W;
		$order = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE id = :id limit 1", array(':id' => $orderid));
		if (empty($order)) {
			return false;
		}
		$sql = 'SELECT `goodsid`, `total` FROM ' . tablename('xc_lv_order_goods') . ' WHERE `orderid` = :orderid';
		$orderGoods = pdo_fetchall($sql, array(':orderid' => $orderid));
		if (!empty($orderGoods)) {
			$credit = 0.00;
			$sql = 'SELECT `credit` FROM ' . tablename('xc_lv_goods') . ' WHERE `id` = :id';
			foreach ($orderGoods as $goods) {
				$goodsCredit = pdo_fetchcolumn($sql, array(':id' => $goods['goodsid']));
				$credit += $goodsCredit * floatval($goods['total']);
			}
		}
		//增加积分
		if (!empty($credit)) {
			load()->model('mc');
			load()->func('compat.biz');
			$uid = mc_openid2uid($order['from_user']);
			$fans = fans_search($uid, array("credit1"));
			if (!empty($fans)) {
				if (!empty($add)) {
					mc_credit_update($_W['member']['uid'], 'credit1', $credit, array('0' => $_W['member']['uid'], '购买商品赠送'));
				} else {
					mc_credit_update($_W['member']['uid'], 'credit1', 0 - $credit, array('0' => $_W['member']['uid'], '微商城操作'));
				}
			}
		}
	}
	//进入付款页面
	public function doMobilePay() {
		global $_W, $_GPC;
	//	$this->checkAuth();
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $orderid));
	
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
		}
		if (checksubmit('codsubmit')) {
		         // echo 111111111111;
			$ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('xc_lv_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			if (!empty($ordergoods)) {
				$goods = pdo_fetchall("SELECT id, title, productprice,ettotal,thumb, marketprice, unit, total,credit FROM " . tablename('xc_lv_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
			}
			//邮件提醒
			if (!empty($this->module['config']['noticeemail'])) {
//				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$address = explode('|', $order['address']);
				$body = "<h3>购买商品清单</h3> <br />";
				if (!empty($goods)) {
					foreach ($goods as $row) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $ordergoods[$row['id']]['optionid']));
						if ($option) {
							$row['title'] = "[" . $option['title'] . "]" . $row['title'];
						}
						$body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
					}
				}
				$paytype = $order['paytype']=='3'?'货到付款':'已付款';
				$body .= "<br />总金额：{$order['price']}元 （{$paytype}）<br />";
				$body .= "<h3>购买用户详情</h3> <br />";
				$body .= "真实姓名：$address[0] <br />";
				$body .= "地区：$address[3] - $address[4] - $address[5]<br />";
				$body .= "详细地址：$address[6] <br />";
				$body .= "手机：$address[1] <br />";
				load()->func('communication');
				ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
			}
			pdo_update('xc_lv_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid));
			message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('myorder'), 'success');
		}
		if (checksubmit()) {
			//echo 'fsdfsdf11111';
			if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
				message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'weid' => $_W['uniacid'])), 'error');
			}
			if ($order['price'] == '0') {
				$this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
				exit;
			}
		}
		// 商品编号
		$sql = 'SELECT `goodsid` FROM ' . tablename('xc_lv_order_goods') . " WHERE `orderid` = :orderid";
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('xc_lv_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));

		$params['tid'] = $orderid;
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['title'] = $goodsTitle;
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;

		include $this->template('pay');
	}


	//报名成功
	public function doMobileSignup() {
		global $_W, $_GPC;
	//	$this->checkAuth();
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE id = :id", array(':id' => $orderid));
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
		}
		if (checksubmit('codsubmit')) {
		
			$ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('xc_lv_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			if (!empty($ordergoods)) {
				$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('xc_lv_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
			}
			//邮件提醒
			if (!empty($this->module['config']['noticeemail'])) {
//				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$address = explode('|', $order['address']);
				$body = "<h3>购买商品清单</h3> <br />";
				if (!empty($goods)) {
					foreach ($goods as $row) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $ordergoods[$row['id']]['optionid']));
						if ($option) {
							$row['title'] = "[" . $option['title'] . "]" . $row['title'];
						}
						$body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
					}
				}
				$paytype = $order['paytype']=='3'?'货到付款':'已付款';
				$body .= "<br />总金额：{$order['price']}元 （{$paytype}）<br />";
				$body .= "<h3>购买用户详情</h3> <br />";
				$body .= "真实姓名：$address[0] <br />";
				$body .= "地区：$address[3] - $address[4] - $address[5]<br />";
				$body .= "详细地址：$address[6] <br />";
				$body .= "手机：$address[1] <br />";
				load()->func('communication');
				ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
			}
			pdo_update('xc_lv_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid));
			message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('myorder'), 'success');
		}
		if (checksubmit()) {
			//echo 'fsdfsdf11111';
			if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
				message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'weid' => $_W['uniacid'])), 'error');
			}
			if ($order['price'] == '0') {
				$this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
				exit;
			}
		}
		// 商品编号
		$sql = 'SELECT `goodsid` FROM ' . tablename('xc_lv_order_goods') . " WHERE `orderid` = :orderid";
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('xc_lv_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));

		$params['tid'] = $orderid;
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['title'] = $goodsTitle;
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;

		include $this->template('pay');
	}
	public function doMobileContactUs() {
		global $_W;
		$cfg = $this->module['config'];
		include $this->template('contactus');
	}
	public function doMobileMyOrder() {
		global $_W, $_GPC;
		//$this->checkAuth();
		$op = $_GPC['op'];
		if ($op == 'confirm') {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE id = :id AND from_user = :from_user", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user']));
			if (empty($order)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
			}
			pdo_update('xc_lv_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));
			message('确认收货完成！', $this->createMobileUrl('myorder'), 'success');
		} else if ($op == 'detail') {
			$orderid = intval($_GPC['orderid']);
			$item = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' limit 1");
			if (empty($item)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
			}
			$goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('xc_lv_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid FROM " . tablename('xc_lv_order_goods')
					. " o left join " . tablename('xc_lv_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
			foreach ($goods as &$g) {
				//属性
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
				if ($option) {
					$g['title'] = "[" . $option['title'] . "]" . $g['title'];
					$g['marketprice'] = $option['marketprice'];
				}
			}
			unset($g);
			$dispatch = pdo_fetch("select id,dispatchname from " . tablename('xc_lv_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));
			include $this->template('order_detail');
		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = intval($_GPC['status']);
			$where = " weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'";
			if ($status == 2) {
				$where.=" and ( status=1 or status=2 )";
			} else {
				$where.=" and status=$status";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_lv_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					$goodsid = pdo_fetchall("SELECT goodsid,total,ettotal FROM " . tablename('xc_lv_order_goods') . " WHERE orderid = '{$row['id']}'", array(), 'goodsid');
					$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.ettotal,o.productprice,o.total,o.optionid FROM " . tablename('xc_lv_order_goods') . " o left join " . tablename('xc_lv_goods') . " g on o.goodsid=g.id "
							. " WHERE o.orderid='{$row['id']}'");
					foreach ($goods as &$item) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xc_lv_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
						if ($option) {
							$item['title'] = "[" . $option['title'] . "]" . $item['title'];
							$item['marketprice'] = $option['marketprice'];
							$item['productprice'] = $option['productprice'];
						}
					}
					unset($item);
					$row['goods'] = $goods;
					$row['total'] = $goodsid;
					$row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('xc_lv_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
				}
			}
			include $this->template('order');
		}
	}
	public function doMobileDetail() {
		$set = $this->settings;
		
		global $_W, $_GPC;
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('xc_lv_goods') . " WHERE id = :id", array(':id' => $goodsid));
		$op = $_GPC['op']?$_GPC['op']:'';
		if($op=='laqu'){
			
			$detime = $goods['detime'];
			
		    $detime = json_decode($detime,true);
			
//			echo "<pre>";
//			print_r($detime);
//			echo "</pre>";
		
		
		  foreach ($detime[0] as $key=>$v){
		   	   $arr[$key]['Date'] = $detime[0][$key];
			
			   $arr[$key]['Price'] = $detime[1][$key];
			  
		   }
			
	      $res = json_encode($arr,true);
		  echo $res;
	
		}else if($op==''){
	
	
		if (empty($goods)) {
			message('抱歉，商品不存在或是已经被删除！');
		}
		if ($goods['istime'] == 1) {
			$backUrl = $this->createMobileUrl('list');
			$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
			if (time() < $goods['timestart']) {
				message('抱歉，还未到购买时间, 暂时无法购物哦~', $backUrl, "error");
			}
			if (time() > $goods['timeend']) {
				message('抱歉，商品限购时间已到，不能购买了哦~', $backUrl, "error");
			}
		}
		$title = $goods['title'];
		//浏览量
		pdo_query("update " . tablename('xc_lv_goods') . " set viewcount=viewcount+1 where id=:id and weid='{$_W['uniacid']}' ", array(":id" => $goodsid));
		$piclist1 = array(array("attachment" => $goods['thumb']));
		$piclist = array();
		if (is_array($piclist1)) {
			foreach($piclist1 as $p){
				$piclist[] = is_array($p)?$p['attachment']:$p;
			}
		}
		if ($goods['thumb_url'] != 'N;') {
			$urls = unserialize($goods['thumb_url']);
			if (is_array($urls)) {
				foreach($urls as $p){
					$piclist[] = is_array($p)?$p['attachment']:$p;
				}
			}
		}
		$marketprice = $goods['marketprice'];
		$productprice= $goods['productprice'];
		$originalprice = $goods['originalprice'];
		$stock = $goods['total'];
		//规格及规格项
		$allspecs = pdo_fetchall("select * from " . tablename('xc_lv_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $goodsid));
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('xc_lv_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
		}
		unset($s);
		//处理规格项
		$options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice, stock,weight,specs from " . tablename('xc_lv_goods_option') . " where goodsid=:id order by id asc", array(':id' => $goodsid));
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
		}
		$params = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_goods_param') . " WHERE goodsid=:goodsid order by displayorder asc", array(":goodsid" => $goods['id']));
		$carttotal = $this->getCartTotal();
		
		
		include $this->template('detail');
		
		}
	}

	public function doMobileAddress() {
		global $_W, $_GPC;
		//$this->checkAuth();
		$operation = $_GPC['op'];

		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $_W['fans']['uid'],
				'username' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
				'province' => $_GPC['province'],
				'city' => $_GPC['city'],
				'district' => $_GPC['area'],
				'address' => $_GPC['address'],
			);
			if (empty($data['username']) || empty($data['mobile']) || empty($data['address'])) {
				message('请输完善您的资料！');
			}
			if (!empty($id)) {
				unset($data['uniacid']);
				unset($data['uid']);
				pdo_update('mc_member_address', $data, array('id' => $id));
				message($id, '', 'ajax');
			} else {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				$data['isdefault'] = 1;
				pdo_insert('mc_member_address', $data);
				$id = pdo_insertid();
				if (!empty($id)) {
					message($id, '', 'ajax');
				} else {
					message(0, '', 'ajax');
				}
			}
		} elseif ($operation == 'default') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid
					 AND `uid` = :uid';
			$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
			$address = pdo_fetch($sql, $params);

			if (!empty($address) && empty($address['isdefault'])) {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				pdo_update('mc_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid'], 'id' => $id));
			}
			message(1, '', 'ajax');
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id';
			$row = pdo_fetch($sql, array(':id' => $id));
			message($row, '', 'ajax');
		} elseif ($operation == 'remove') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$where = ' AND `uniacid` = :uniacid AND `uid` = :uid';
				$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id' . $where;
				$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
				$address = pdo_fetch($sql, $params);

				if (!empty($address)) {
					pdo_delete('mc_member_address', array('id' => $id));
					// 如果删除的是默认地址，则设置是新的为默认地址
					if ($address['isdefault'] > 0) {
						$sql = 'SELECT MAX(id) FROM ' . tablename('mc_member_address') . ' WHERE 1 ' . $where;
						unset($params[':id']);
						$maxId = pdo_fetchcolumn($sql, $params);
						if (!empty($maxId)) {
							pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $maxId));
							die(json_encode(array("result" => 1, "maxid" => $maxId)));
						}
					}
				}
			}
			die(json_encode(array("result" => 1, "maxid" => 0)));
		} else {
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid';
			$params = array(':uniacid' => $_W['uniacid']);
			if (empty($_W['member']['uid'])) {
				$params[':uid'] = $_W['fans']['openid'];
			} else {
				$params[':uid'] = $_W['member']['uid'];
			}
			$addresses = pdo_fetchall($sql, $params);
			$carttotal = $this->getCartTotal();
			include $this->template('address');
		}
	}
	
	
	public function doMobileLianxi() {
		global $_W, $_GPC;
		//$this->checkAuth();
		$operation = $_GPC['op'];
       $set = $this->settings;
		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $_W['fans']['uid'],
				'username' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
			
			);
			if (empty($data['username']) || empty($data['mobile']) ) {
				message('请输完善您的资料！');
			}
			if (!empty($id)) {
				unset($data['uniacid']);
				unset($data['uid']);
				pdo_update('xc_lv_member_address', $data, array('id' => $id));
				message($id, '', 'ajax');
			} else {
				pdo_update('xc_lv_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				$data['isdefault'] = 1;
				pdo_insert('xc_lv_member_address', $data);
				$id = pdo_insertid();
				if (!empty($id)) {
					message($id, '', 'ajax');
				} else {
					message(0, '', 'ajax');
				}
			}
		} elseif ($operation == 'default') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT `isdefault` FROM ' . tablename('xc_lv_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid
					 AND `uid` = :uid';
			$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
			$address = pdo_fetch($sql, $params);

			if (!empty($address) && empty($address['isdefault'])) {
				pdo_update('xc_lv_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				pdo_update('xc_lv_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid'], 'id' => $id));
			}
			message(1, '', 'ajax');
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT * FROM ' . tablename('xc_lv_member_address') . ' WHERE `id` = :id';
			$row = pdo_fetch($sql, array(':id' => $id));
			message($row, '', 'ajax');
		} elseif ($operation == 'remove') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$where = ' AND `uniacid` = :uniacid AND `uid` = :uid';
				$sql = 'SELECT `isdefault` FROM ' . tablename('xc_lv_member_address') . ' WHERE `id` = :id' . $where;
				$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
				$address = pdo_fetch($sql, $params);

				if (!empty($address)) {
					pdo_delete('xc_lv_member_address', array('id' => $id));
					// 如果删除的是默认地址，则设置是新的为默认地址
					if ($address['isdefault'] > 0) {
						$sql = 'SELECT MAX(id) FROM ' . tablename('xc_lv_member_address') . ' WHERE 1 ' . $where;
						unset($params[':id']);
						$maxId = pdo_fetchcolumn($sql, $params);
						if (!empty($maxId)) {
							pdo_update('xc_lv_member_address', array('isdefault' => 1), array('id' => $maxId));
							die(json_encode(array("result" => 1, "maxid" => $maxId)));
						}
					}
				}
			}
			die(json_encode(array("result" => 1, "maxid" => 0)));
		} else {
			$sql = 'SELECT * FROM ' . tablename('xc_lv_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid';
			$params = array(':uniacid' => $_W['uniacid']);
			if (empty($_W['member']['uid'])) {
				$params[':uid'] = $_W['fans']['openid'];
			} else {
				$params[':uid'] = $_W['member']['uid'];
			}
			$addresses = pdo_fetchall($sql, $params);
			$carttotal = $this->getCartTotal();
			include $this->template('lianxi');
		}
	}


	/*private function checkAuth() {
		global $_W;
		checkauth();
	}*/
	
	
	private function changeWechatSend($id, $status, $msg = '') {
		global $_W;
		$paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
		if (!empty($paylog['openid'])) {
			$paylog['tag'] = iunserializer($paylog['tag']);
			$acid = $paylog['tag']['acid'];
			$account = account_fetch($acid);
			$payment = uni_setting($account['uniacid'], 'payment');
			if ($payment['payment']['wechat']['version'] == '2') {
				return true;
			}
			$send = array(
					'appid' => $account['key'],
					'openid' => $paylog['openid'],
					'transid' => $paylog['tag']['transaction_id'],
					'out_trade_no' => $paylog['plid'],
					'deliver_timestamp' => TIMESTAMP,
					'deliver_status' => $status,
					'deliver_msg' => $msg,
			);
			$sign = $send;
			$sign['appkey'] = $payment['payment']['wechat']['signkey'];
			ksort($sign);
			$string = '';
			foreach ($sign as $key => $v) {
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$send['app_signature'] = sha1(rtrim($string, '&'));
			$send['sign_method'] = 'sha1';
			$account = WeAccount::create($acid);
			$response = $account->changeOrderStatus($send);
			if (is_error($response)) {
				message($response['message']);
			}
		}
	}
	public function payResult($params) {
		global $_W;
		$set = $this->settings;
        $params['from']='return';
		$fee = intval($params['fee']);
	
		$data = array('status' => $params['result'] == 'success' ? 5 : 0);
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');

		// 卡券代金券备注
		if (!empty($params['is_usecard'])) {
			$cardType = array('1' => '微信卡券', '2' => '系统代金券');
			$data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . ($params['fee'] - $params['card_fee']);
			$data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
		}

		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
		}
		$goods = pdo_fetchall("SELECT `goodsid`, `total` FROM " . tablename('xc_lv_order_goods') . " WHERE `orderid` = :orderid", array(':orderid' => $params['tid']));
		if (!empty($goods)) {
			$row = array();
			foreach ($goods as $row) {
				$goodsInfo = pdo_fetch("SELECT `total`, `totalcnf`, `sales` FROM " . tablename('xc_lv_goods') . " WHERE `id` = :id", array(':id' => $row['goodsid']));
				$goodsupdate = array();
				if ($goodsInfo['totalcnf'] == '1' && !empty($goodsInfo['total'])) {
					$goodsupdate['total'] = $goodsInfo['total'] - $row['total'];
					$goodsupdate['total'] = ($goodsupdate['total'] < 0) ? 0 : $goodsupdate['total'];
				}
				$goodsupdate['sales'] = $goodsInfo['sales'] + $row['total'];
				pdo_update('xc_lv_goods', $goodsupdate, array('id' => $row['goodsid']));
			}
		}
		pdo_update('xc_lv_order', $data, array('id' => $params['tid']));
		if ($params['from'] == 'return') {

			//积分变更
			$this->setOrderCredit($params['tid']);

			if (!empty($this->module['config']['noticeemail']) || !empty($this->module['config']['mobile'])) {
				$order = pdo_fetch("SELECT `ordersn`, `price`, `paytype`, `from_user`, `address`, `createtime` FROM " . tablename('xc_lv_order') . " WHERE id = '{$params['tid']}'");
				$ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename('xc_lv_order_goods') . " WHERE orderid = '{$params['tid']}'", array(), 'goodsid');
				$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total FROM " . tablename('xc_lv_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
//				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$address = explode('|', $order['address']);

				// 邮件提醒
				if (!empty($this->module['config']['noticeemail'])) {
					$body = "<h3>购买商品清单</h3> <br />";
					if (!empty($goods)) {
						foreach ($goods as $row) {
							$body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
						}
					}
					$paytype = $order['paytype'] == '3' ? '货到付款' : '已付款' . '<br />';
					$body .= '总金额：' . $order['price'] . '元' . $paytype . '<br />';
					$body .= '<h3>购买用户详情</h3> <br />';
					$body .= '真实姓名：' . $address[0] . '<br />';
					$body .= '地区：' . $address[3] . ' - ' . $address[4] . ' - ' . $address[5] . '<br />';
					$body .= '详细地址：' . $address[6] . '<br />';
					$body .= '手机：' . $address[1]  . '<br />';

					load()->func('communication');
					ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
				}
				// 短信提醒
				if (!empty($this->module['config']['mobile'])) {
					load()->model('cloud');
					cloud_prepare();

					$body = '用户' . $address[0] . ',电话:' . $address[1] . '于' . date('m月d日H:i') . '成功支付订单' . $order['ordersn']
							. ',总金额' . $order['price'] . '元' . '.' . random(3);

					cloud_sms_send($this->module['config']['mobile'], $body);
				}
			}
			
			$order = pdo_fetch("SELECT `ordersn`, `price`, `paytype`, `from_user`, `address`, `createtime` FROM " . tablename('xc_lv_order') . " WHERE id = '{$params['tid']}'");
			$address = explode('|', $order['address']);
			    //文本消息提醒
			 	 global $_GPC, $_W;
				 load()->model('mc');
				 load()->func('tpl');
 
				$info = mc_oauth_userinfo();  //获取粉丝资料
				$acc = WeAccount::create($_W['account']['acid']);
				$text= '用户' . $address[0] . ',电话:' . $address[1] . '于' . date('m月d日H:i') . '成功支付订单' . $order['ordersn']
							. ',总金额' . $params['fee'] . '元' . '请耐心等待商家电话联系.祝您旅行快乐' ;
				$send = array(
					"touser" => $_W['openid'],
					"msgtype" => "text",
					"text" => array(
					"content" => urlencode($text)
                )
            );
            $res = $acc->sendCustomNotice($send);//文本消
            
            
            //支付成功模板消息提醒
            
            			//报名成功发送模板消息
			
			$sendurl = '';
			$succtemid = $set['succtemid'];
			$content = array(
			
			
			'first'=>array(
			    'value'=>'您已经支付成功',
			),
			'keyword1'=>array(
			
			    'value'=>$params['title'],
			    
			),
			'keyword2'=>array(
			
			   'value'=>$params['fee'],
			   
			),
			'keyword3'=>array(
			
	             'value'=>$address[0],		
			),
			
			'keyword4'=>array(
			  'value'=>$address[1],
			),
			'keyword5'=>array(
			
			   'value'=>$order['ordersn'],
			
			),
			
			'remark'=>array(
			  'value'=>'有问题可以与我们联系',
			)

			
			);
			
			
			$this->sendtpl($_W['fans']['from_user'], $sendurl, $succtemid, $content);

			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				message('支付成功！', $this->createMobileUrl('myorder',array('status'=>5)), 'success');
			} else {
				message('支付成功！', '../../app/' . $this->createMobileUrl('myorder',array('status'=>5)), 'success');
			}
		}
	}
	public function doWebOption() {
		$tag = random(32);
		global $_GPC;
		include $this->template('option');
	}
	public function doWebSpec() {
		global $_GPC;
		$spec = array(
			"id" => random(32),
			"title" => $_GPC['title']
		);
		include $this->template('spec');
	}
	public function doWebSpecItem() {
		global $_GPC;
		load()->func('tpl');
		$spec = array(
			"id" => $_GPC['specid']
		);
		$specitem = array(
			"id" => random(32),
			"title" => $_GPC['title'],
			"show" => 1
		);
		include $this->template('spec_item');
	}
	public function doWebParam() {
		$tag = random(32);
		global $_GPC;
		include $this->template('param');
	}
	public function doWebExpress() {
		global $_W, $_GPC;
		// pdo_query('DROP TABLE ims_xc_lv_express');
		//pdo_query("CREATE TABLE IF NOT EXISTS `ims_xc_lv_express` ( `id` int(10) unsigned NOT NULL AUTO_INCREMENT, `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',  `express_name` varchar(50) NOT NULL COMMENT '分类名称',  `express_price` varchar(10) NOT NULL DEFAULT '0',  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',  `express_area` varchar(50) NOT NULL COMMENT '配送区域',  `enabled` tinyint(1) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");
		//pdo_query("ALTER TABLE  `ims_xc_lv_order` ADD  `expressprice` VARCHAR( 10 ) NOT NULL AFTER  `totalnum` ;");
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				if (empty($_GPC['express_name'])) {
					message('抱歉，请输入物流名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'displayorder' => intval($_GPC['displayorder']),
					'express_name' => $_GPC['express_name'],
					'express_url' => $_GPC['express_url'],
					'express_area' => $_GPC['express_area'],
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('xc_lv_express', $data, array('id' => $id));
				} else {
					pdo_insert('xc_lv_express', $data);
					$id = pdo_insertid();
				}
				message('更新物流成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
			}
			//修改
			$express = pdo_fetch("SELECT * FROM " . tablename('xc_lv_express') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$express = pdo_fetch("SELECT id  FROM " . tablename('xc_lv_express') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($express)) {
				message('抱歉，物流方式不存在或是已经被删除！', $this->createWebUrl('express', array('op' => 'display')), 'error');
			}
			pdo_delete('xc_lv_express', array('id' => $id));
			message('物流方式删除成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('express', TEMPLATE_INCLUDEPATH, true);
	}
	public function doWebDispatch() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_dispatch') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'displayorder' => intval($_GPC['displayorder']),
					'dispatchtype' => intval($_GPC['dispatchtype']),
					'dispatchname' => $_GPC['dispatchname'],
					'express' => $_GPC['express'],
					'firstprice' => $_GPC['firstprice'],
					'firstweight' => $_GPC['firstweight'],
					'secondprice' => $_GPC['secondprice'],
					'secondweight' => $_GPC['secondweight'],
					'description' => $_GPC['description']
				);
				if (!empty($id)) {
					pdo_update('xc_lv_dispatch', $data, array('id' => $id));
				} else {
					pdo_insert('xc_lv_dispatch', $data);
					$id = pdo_insertid();
				}
				message('更新配送方式成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
			}
			//修改
			$dispatch = pdo_fetch("SELECT * FROM " . tablename('xc_lv_dispatch') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
			$express = pdo_fetchall("select * from " . tablename('xc_lv_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$dispatch = pdo_fetch("SELECT id FROM " . tablename('xc_lv_dispatch') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($dispatch)) {
				message('抱歉，配送方式不存在或是已经被删除！', $this->createWebUrl('dispatch', array('op' => 'display')), 'error');
			}
			pdo_delete('xc_lv_dispatch', array('id' => $id));
			message('配送方式删除成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('dispatch', TEMPLATE_INCLUDEPATH, true);
	}
	public function doWebAdv() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_lv_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb']
				);
				if (!empty($id)) {
					pdo_update('xc_lv_adv', $data, array('id' => $id));
				} else {
					pdo_insert('xc_lv_adv', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('xc_lv_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('xc_lv_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
			}
			pdo_delete('xc_lv_adv', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('adv', TEMPLATE_INCLUDEPATH, true);
	}
	public function doMobileAjaxdelete() {
		global $_GPC;
		$delurl = $_GPC['pic'];
		if (file_delete($delurl)) {
			echo 1;
		} else {
			echo 0;
		}
	}
	
	
		//发送文本消息
	
	public function sendtext($text,$openid){
		
		    global $_GPC, $_W;
			load() -> func('tpl');
			load() -> model('mc');
			$acc = WeAccount::create($_W['account']['acid']);
		
			$send = array("touser" =>$openid, "msgtype" => "text", "text" => array("content" => urlencode($text)));
			$res = $acc -> sendCustomNotice($send);
		
	}
	
		//模板消息提醒
	private function sendtpl($openid, $url, $template_id, $content) {

		global $_GPC, $_W;

		load() -> classs('weixin.account');

		load() -> func('communication');

		$obj = new WeiXinAccount();

		$access_token = $obj -> fetch_available_token();

		$data = array(
		              'touser' => $openid, 
		              'template_id' => $template_id, 
		              'url' => $url, 
		              'topcolor' => "#FF0000", 
		              'data' => $content, 
					  
					  );

		$json = json_encode($data);

		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;

		$ret = ihttp_post($url, $json);
	}
	

	public function doMobileOrder() {
		global $_W, $_GPC;
        $set = $this->settings;
	  	         load()->model('mc');
				$info = mc_oauth_userinfo();  //获取粉丝资料
				$acc = WeAccount::create($_W['account']['acid']);
				 load()->func('tpl');
		$orderId = intval($_GPC['orderid']);
		$status = intval($_GPC['status']);
		$referStatus = intval($_GPC['curtstatus']);
		$sql = 'SELECT `id` FROM ' . tablename('xc_lv_order') . ' WHERE `id` = :id AND `weid` = :weid AND `from_user`
				= :from_user';
		$params = array(':id' => $orderId, ':weid' => $_W['uniacid'], ':from_user' => $_W['fans']['from_user']);
		$orderId = pdo_fetchcolumn($sql, $params);
		$redirect = $this->createMobileUrl('myorder', array('status' => $referStatus));
		if (empty($orderId)) {
			message('订单不存在或已经被删除', $redirect , 'error');
		}
		$order = pdo_fetch("SELECT * FROM " . tablename('xc_lv_order') . " WHERE id = '{$_GPC['orderid']}'");
			
		
			 $address = explode('|',$order['address']);

		if ($_GPC['op'] == 'delete') {
			
			
			  //文本消息提醒
		
 
			
				$text= '尊敬的用户' . $address[0] . ',电话:' . $address[1] . '于' . date('m月d日H:i') . '成功删除订单' . $order['ordersn']
							 ;
				$send = array(
					"touser" => $order['from_user'],
					"msgtype" => "text",
					"text" => array(
					"content" => urlencode($text)
                )
            );
            $res = $acc->sendCustomNotice($send);//文本消息
			
			pdo_delete('xc_lv_order', array('id' => $orderId));
			pdo_delete('xc_lv_order_goods', array('orderid' => $orderId));
			message('订单已经成功删除！', $redirect, 'success');
		} else {
			
			
			
				$text= '尊敬的用户' . $address[0] . ',电话:' . $address[1] . '于' . date('m月d日H:i') . '成功取消订单' . $order['ordersn'].'祝您生活愉快';
							 ;
				$send = array(
					"touser" => $order['from_user'],
					"msgtype" => "text",
					"text" => array(
					"content" => urlencode($text)
                )
            );
            $res = $acc->sendCustomNotice($send);//文本消
			
			pdo_update('xc_lv_order', array('status' => $status), array('id' => $orderId));
			message('订单已经成功取消！', $redirect, 'success');
		}
	}
}
