<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端小区超市
 */
defined('IN_IA') or exit('Access Denied');
	
	global $_GPC,$_W;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'display';
	//判断是否注册，只有注册后，才能进入
	$member = $this->changemember();
	if($op == 'list'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('xcommunity_shopping_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$recommandcategory = array();
		foreach ($category as &$c) {
			if ($c['isrecommand'] == 1) {
				$c['list'] = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 AND status = '1'  and pcate='{$c['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$c['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}'");
				$c['pager'] = pagination($c['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
				$recommandcategory[] = $c;
			}
			if (!empty($children[$c['id']])) {
				foreach ($children[$c['id']] as &$child) {
					if ($child['isrecommand'] == 1) {
						$child['list'] = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1'  and pcate='{$c['id']}' and ccate='{$child['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
						$child['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}' and ccate='{$child['id']}' ");
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
		$advs = pdo_fetchall("select * from " . tablename('xcommunity_shopping_slide') . " where enabled=1 and weid= '{$_W['uniacid']}'");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
		//首页推荐
		$rpindex = max(1, intval($_GPC['rpage']));
		$rpsize = 10;
		$condition = ' and isrecommand=1';
		$rlist = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($rpindex - 1) * $rpsize . ',' . $rpsize);


		include $this->template('shopping_list');
	}elseif($op == 'list2'){
		$pindex = max(1, intval($_GPC["page"]));
		$psize = 10;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('xcommunity_shopping_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
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
		$sorturl = $this->createMobileUrl('shopping', array('op' => 'list2',"keyword" => $_GPC['keyword'], "pcate" => $_GPC['pcate'], "ccate" => $_GPC['ccate']), true);
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
		$category = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY $sortfield LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		foreach ($list as &$r) {
			if ($r['istime'] == 1) {
				$arr = $this->time_tran($r['timeend']);
				$r['timelaststr'] = $arr[0];
				$r['timelast'] = $arr[1];
			}
		}
		unset($r);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' $condition");
		$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
		$carttotal = $this->getCartTotal();
		include $this->template('shopping_list2');
	}elseif ($op == 'detail') {
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_goods') . " WHERE id = :id", array(':id' => $goodsid));
		if (empty($goods)) {
			message('抱歉，商品不存在或是已经被删除！');
		}
		if ($goods['istime'] == 1) {
			$backUrl = $this->createMobileUrl('shopping',array('op' => 'list'));
			$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
			if (time() < $goods['timestart']) {
				message('抱歉，还未到购买时间, 暂时无法购物哦~', $backUrl, "error");
			}
			if (time() > $goods['timeend']) {
				message('抱歉，商品限购时间已到，不能购买了哦~', $backUrl, "error");
			}
		}
		//浏览量
		pdo_query("update " . tablename('xcommunity_shopping_goods') . " set viewcount=viewcount+1 where id=:id and weid='{$_W['uniacid']}' ", array(":id" => $goodsid));
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
		$allspecs = pdo_fetchall("select * from " . tablename('xcommunity_shopping_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $goodsid));
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('xcommunity_shopping_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
		}
		unset($s);
		//处理规格项
		$options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice, stock,weight,specs from " . tablename('xcommunity_shopping_goods_option') . " where goodsid=:id order by id asc", array(':id' => $goodsid));
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
		//修改成只显示默认价格，
//		if (!empty($goods['hasoption'])) {
//			$options = pdo_fetchall("SELECT * FROM " . tablename('shopping_goods_option') . " WHERE goodsid=:goodsid order by thumb asc,displayorder asc", array(":goodsid" => $goods['id']));
//			foreach ($options as $o) {
//				if ($marketprice >= $o['marketprice']) {
//					$marketprice = $o['marketprice'];
//				}
//				if ($productprice >= $o['productprice']) {
//					$productprice = $o['productprice'];
//				}
//				if ($stock <= $o['stock']) {
//					$stock = $o['stock'];
//				}
//			}
//		}
		$params = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_goods_param') . " WHERE goodsid=:goodsid order by displayorder asc", array(":goodsid" => $goods['id']));
		$carttotal = $this->getCartTotal();

		include $this->template('shopping_detail');
	}elseif ($op == 'mycart') {
		if ($operation == 'add') {
			$goodsid = intval($_GPC['id']);
			//print_r($goodsid);exit();
			$total = intval($_GPC['total']);
			$total = empty($total) ? 1 : $total;
			$optionid = intval($_GPC['optionid']);
			$goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('xcommunity_shopping_goods') . " WHERE id = :id", array(':id' => $goodsid));
			if (empty($goods)) {
				$result['message'] = '抱歉，该商品不存在或是已经被删除！';
				message($result, '', 'ajax');
			}
			$marketprice = $goods['marketprice'];
			if (!empty($optionid)) {
				$option = pdo_fetch("select marketprice from " . tablename('xcommunity_shopping_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
				if (!empty($option)) {
					$marketprice = $option['marketprice'];
				}
			}
			$row = pdo_fetch("SELECT id, total FROM " . tablename('xcommunity_shopping_cart') . " WHERE from_user = :from_user AND weid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['fans']['from_user'], ':goodsid' => $goodsid,':optionid'=>$optionid));
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
				pdo_insert('xcommunity_shopping_cart', $data);
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
				pdo_update('xcommunity_shopping_cart', $data, array('id' => $row['id']));
			}
			//返回数据
			$carttotal = $this->getCartTotal();
			$result = array(
				'result' => 1,
				'total' => $carttotal
			);

			die(json_encode($result));
		} else if ($operation == 'clear') {
			pdo_delete('xcommunity_shopping_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid']));
			die(json_encode(array("result" => 1)));
		} else if ($op == 'remove') {
			$id = intval($_GPC['id']);
			pdo_delete('xcommunity_shopping_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid'], 'id' => $id));
			die(json_encode(array("result" => 1, "cartid" => $id)));
		} else if ($operation == 'update') {
			$id = intval($_GPC['id']);
			$num = intval($_GPC['num']);
			$sql = "update " . tablename('xcommunity_shopping_cart') . " set total=$num where id=:id";
			pdo_query($sql, array(":id" => $id));
			die(json_encode(array("result" => 1)));
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$totalprice = 0;
			if (!empty($list)) {
				foreach ($list as &$item) {
					$goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('xcommunity_shopping_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,stock from " . tablename("xcommunity_shopping_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
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
		}
		include $this->template('shopping_cart');
	}elseif ($op == 'confirm') {
		// checkauth();
		$totalprice = 0;
		$allgoods = array();
		$id = intval($_GPC['id']);
		$optionid = intval($_GPC['optionid']);
		$total = intval($_GPC['total']);
		if ( (empty($total)) || ($total < 1) ) {
			$total = 1;
		}
		$direct = false; //是否是直接购买
		$returnurl = ""; //当前连接
		if (!empty($id)) {
			$item = pdo_fetch("select id,thumb,title,weight,marketprice,total,type,totalcnf,sales,unit,istime,timeend from " . tablename("xcommunity_shopping_goods") . " where id=:id limit 1", array(":id" => $id));
			if ($item['istime'] == 1) {
				if (time() > $item['timeend']) {
					$backUrl = $this->createMobileUrl('shopping', array('op' => 'detail','id' => $id));
					$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
					message('抱歉，商品限购时间已到，无法购买了！', $backUrl, "error");
				}
			}
			if (!empty($optionid)) {
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xcommunity_shopping_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
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
			$item['totalprice'] = $total * $item['marketprice'];
			$allgoods[] = $item;
			$totalprice+= $item['totalprice'];
			if ($item['type'] == 1) {
				$needdispatch = true;
			}
			$direct = true;
			$returnurl = $this->createMobileUrl("shopping", array('op' => 'confirm',"id" => $id, "optionid" => $optionid, "total" => $total));
		}
		if (!$direct) {
			//如果不是直接购买（从购物车购买）
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			if (!empty($list)) {
				foreach ($list as &$g) {
					$item = pdo_fetch("select id,thumb,title,weight,marketprice,total,type,totalcnf,sales,unit from " . tablename("xcommunity_shopping_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xcommunity_shopping_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
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
					$totalprice+= $item['totalprice'];
					if ($item['type'] == 1) {
						$needdispatch = true;
					}
				}
				unset($g);
			}
			$returnurl = $this->createMobileUrl("shopping",array('op' => 'confirm'));
		}
		if (count($allgoods) <= 0) {
			header("location: " . $this->createMobileUrl('shopping' ,array('op' => 'myorder')));
			exit();
		}
		//配送方式
		$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("xcommunity_shopping_dispatch") . " WHERE weid = {$_W['uniacid']} order by displayorder desc");
		foreach ($dispatch as &$d) {
			$weight = 0;
			foreach ($allgoods as $g) {
				$weight+=$g['weight'] * $g['total'];
			}
			$price = 0;
			if ($weight <= $d['firstweight']) {
				$price = $d['firstprice'];
			} else {
				$price = $d['firstprice'];
				$secondweight = $weight - $d['firstweight'];
				if ($secondweight % $d['secondweight'] == 0) {
					$price+= (int) ( $secondweight / $d['secondweight'] ) * $d['secondprice'];
				} else {
					$price+= (int) ( $secondweight / $d['secondweight'] + 1 ) * $d['secondprice'];
				}
			}
			$d['price'] = $price;
		}
		unset($d);
		if (checksubmit('submit')) {
			//是否自提
			$sendtype=1;
			$address = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
			if (empty($address)) {
				message('抱歉，请您填写收货地址！');
			}
			//商品价格
			$goodsprice = 0;
			foreach ($allgoods as $row) {
				if ($item['stock'] != -1 && $row['total'] > $item['stock']) {
					message('抱歉，“' . $row['title'] . '”此商品库存不足！', $this->createMobileUrl('shopping',array('op' => 'confirm')), 'error');
				}
				$goodsprice+= $row['totalprice'];
			}
			//运费
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
				'sendtype' =>intval($sendtype),
				'dispatch' => $dispatchid,
				'goodstype' => intval($cart['type']),
				'remark' => $_GPC['remark'],
				'addressid' => $address['id'],
				'createtime' => TIMESTAMP
			);
			pdo_insert('xcommunity_shopping_order', $data);
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
					'price' => $row['marketprice'],
					'createtime' => TIMESTAMP,
					'optionid' => $row['optionid']
				);
				$o = pdo_fetch("select title from ".tablename('xcommunity_shopping_goods_option')." where id=:id limit 1",array(":id"=>$row['optionid']));
				if(!empty($o)){
					$d['optionname'] = $o['title'];
				}
				pdo_insert('xcommunity_shopping_order_goods', $d);
			}
			//清空购物车
			if (!$direct) {
				pdo_delete("xcommunity_shopping_cart", array("weid" => $_W['uniacid'], "from_user" => $_W['fans']['from_user']));
			}
			//变更商品库存
			if (empty($item['totalcnf'])) {
				$this->setOrderStock($orderid);
			}
			message('提交订单成功,现在跳转到付款页面...',$this->createMobileUrl('shopping', array('op' => 'pay','orderid' => $orderid)),'success');
		}
		$carttotal = $this->getCartTotal();
		$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
		$row = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_address') . " WHERE isdefault = 1 and openid = :openid limit 1", array(':openid' => $_W['fans']['from_user']));
		include $this->template('shopping_confirm');
	}elseif ($op == 'pay') {
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id", array(':id' => $orderid));
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'error');
		}
		if (checksubmit('codsubmit')) {
			$ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('xcommunity_shopping_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			if (!empty($ordergoods)) {
				$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('xcommunity_shopping_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
			}
			//邮件提醒
			if (!empty($this->module['config']['noticeemail'])) {
				$address = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$body = "<h3>购买商品清单</h3> <br />";
				if (!empty($goods)) {
					foreach ($goods as $row) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xcommunity_shopping_goods_option") . " where id=:id limit 1", array(":id" => $ordergoods[$row['id']]['optionid']));
						if ($option) {
							$row['title'] = "[" . $option['title'] . "]" . $row['title'];
						}
						$body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
					}
				}
				$paytype = $order['paytype']=='3'?'货到付款':'已付款';
				$body .= "<br />总金额：{$order['price']}元 （{$paytype}）<br />";
				$body .= "<h3>购买用户详情</h3> <br />";
				$body .= "真实姓名：$address[realname] <br />";
				$body .= "地区：$address[province] - $address[city] - $address[area]<br />";
				$body .= "详细地址：$address[address] <br />";
				$body .= "手机：$address[mobile] <br />";
				load()->func('communication');
				ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
			}
			pdo_update('xcommunity_shopping_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid));
			message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'success');
		}
		if (checksubmit()) {
			if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
				message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'weid' => $_W['uniacid'])), 'error');
			}
			if ($order['price'] == '0') {
				$this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
				exit;
			}
		}
		// 商品编号
		$sql = 'SELECT `goodsid` FROM ' . tablename('xcommunity_shopping_order_goods') . " WHERE `orderid` = :orderid";
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('xcommunity_shopping_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));

		$params['tid'] = $orderid;
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['title'] = $goodsTitle;
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;
		// print_r($params);exit();
		include $this->template('shopping_pay');
	}elseif ($op == 'address') {
		$from = $_GPC['from'];
		$returnurl = urldecode($_GPC['returnurl']);
		//$this->checkAuth();
		// $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'post';

		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$data = array(
				'weid' => $_W['uniacid'],
				'openid' => $_W['fans']['from_user'],
				'realname' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
				'province' => $_GPC['province'],
				'city' => $_GPC['city'],
				'area' => $_GPC['area'],
				'address' => $_GPC['address'],
			);
			if (empty($_GPC['realname']) || empty($_GPC['mobile']) || empty($_GPC['address'])) {
				message('请输完善您的资料！');
			}
			if (!empty($id)) {
				unset($data['weid']);
				unset($data['openid']);
				pdo_update('xcommunity_shopping_address', $data, array('id' => $id));
				message($id, '', 'ajax');
			} else {
				pdo_update('xcommunity_shopping_address', array('isdefault' => 0), array('weid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user']));
				$data['isdefault'] = 1;
				pdo_insert('xcommunity_shopping_address', $data);
				$id = pdo_insertid();
				if (!empty($id)) {
					message($id, '', 'ajax');
				} else {
					message(0, '', 'ajax');
				}
			}
		} elseif ($operation == 'default') {
			$id = intval($_GPC['id']);
			$address = pdo_fetch("select isdefault from " . tablename('xcommunity_shopping_address') . " where id='{$id}' and weid='{$_W['uniacid']}' and openid='{$_W['fans']['from_user']}' limit 1 ");
			if(!empty($address) && empty($address['isdefault'])){
				pdo_update('xcommunity_shopping_address', array('isdefault' => 0), array('weid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user']));
				pdo_update('xcommunity_shopping_address', array('isdefault' => 1), array('weid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user'], 'id' => $id));
			}
			message(1, '', 'ajax');
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, realname, mobile, province, city, area, address FROM " . tablename('xcommunity_shopping_address') . " WHERE id = :id", array(':id' => $id));
			message($row, '', 'ajax');
		} elseif ($operation == 'remove') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$address = pdo_fetch("select isdefault from " . tablename('xcommunity_shopping_address') . " where id='{$id}' and weid='{$_W['uniacid']}' and openid='{$_W['fans']['from_user']}' limit 1 ");
				if (!empty($address)) {
					//pdo_delete("shopping_address", array('id'=>$id, 'weid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user']));
					//修改成不直接删除，而设置deleted=1
					pdo_update("xcommunity_shopping_address", array("deleted" => 1, "isdefault" => 0), array('id' => $id, 'weid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user']));
					if ($address['isdefault'] == 1) {
						//如果删除的是默认地址，则设置是新的为默认地址
						$maxid = pdo_fetchcolumn("select max(id) as maxid from " . tablename('xcommunity_shopping_address') . " where weid='{$_W['uniacid']}' and openid='{$_W['fans']['from_user']}' limit 1 ");
						if (!empty($maxid)) {
							pdo_update('xcommunity_shopping_address', array('isdefault' => 1), array('id' => $maxid, 'weid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user']));
							die(json_encode(array("result" => 1, "maxid" => $maxid)));
						}
					}
				}
			}
			die(json_encode(array("result" => 1, "maxid" => 0)));
		} else {
			$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
			$address = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_address') . " WHERE deleted=0 and openid = :openid", array(':openid' => $_W['fans']['from_user']));
			$carttotal = $this->getCartTotal();
			include $this->template('shopping_address');
		}
	}elseif ($op == 'myorder') {
		if ($operation == 'confirm') {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id AND from_user = :from_user", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user']));
			if (empty($order)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'error');
			}
			pdo_update('xcommunity_shopping_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));
			message('确认收货完成！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'success');
		} else if ($operation == 'detail') {
			$orderid = intval($_GPC['orderid']);
			$item = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' limit 1");
			if (empty($item)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'error');
			}
			$goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('xcommunity_shopping_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid FROM " . tablename('xcommunity_shopping_order_goods')
					. " o left join " . tablename('xcommunity_shopping_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
			foreach ($goods as &$g) {
				//属性
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xcommunity_shopping_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
				if ($option) {
					$g['title'] = "[" . $option['title'] . "]" . $g['title'];
					$g['marketprice'] = $option['marketprice'];
				}
			}
			unset($g);
			$dispatch = pdo_fetch("select id,dispatchname from " . tablename('xcommunity_shopping_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));
			include $this->template('shopping_order_detail');
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
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_shopping_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					$goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('xcommunity_shopping_order_goods') . " WHERE orderid = '{$row['id']}'", array(), 'goodsid');
					$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total,o.optionid FROM " . tablename('xcommunity_shopping_order_goods') . " o left join " . tablename('xcommunity_shopping_goods') . " g on o.goodsid=g.id "
							. " WHERE o.orderid='{$row['id']}'");
					foreach ($goods as &$item) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xcommunity_shopping_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
						if ($option) {
							$item['title'] = "[" . $option['title'] . "]" . $item['title'];
							$item['marketprice'] = $option['marketprice'];
						}
					}
					unset($item);
					$row['goods'] = $goods;
					$row['total'] = $goodsid;
					$row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('xcommunity_shopping_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
				}
			}
			include $this->template('shopping_order');
		}
	}























