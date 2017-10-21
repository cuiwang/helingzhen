<?php
/**
 * 模块小程序接口定义
 */

defined('IN_IA') or exit('Access Denied');
class Wdl_shoppingModuleWxapp extends WeModuleWxapp
{
	public function doPageTest()
    {
		global $_GPC, $_W;
		$errno = 0;
		$message = 'test返回消息';
		$data = array();
		return $this->result($errno, $message, $data);
	}
   public function doPagelist() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('shopping_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('shopping_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$recommandcategory = array();
		foreach ($category as &$c) {
			if ($c['isrecommand'] == 1) {
				$c['list'] = pdo_fetchall("SELECT * FROM " . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 AND status = '1'  and pcate='{$c['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$c['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}'");
				$c['pager'] = pagination($c['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
				$recommandcategory[] = $c;
			}
			if (!empty($children[$c['id']])) {
				foreach ($children[$c['id']] as &$child) {
					if ($child['isrecommand'] == 1) {
						$child['list'] = pdo_fetchall("SELECT * FROM " . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1'  and pcate='{$c['id']}' and ccate='{$child['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
						$child['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}' and ccate='{$child['id']}' ");
						$child['pager'] = pagination($child['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
						$recommandcategory[] = $child;
					}
				}
				unset($child);
			}
		}
		unset($c);
		$carttotal = $this->getCartTotal();
		//首页推荐
		$rpindex = max(1, intval($_GPC['rpage']));
		$rpsize = 4;
		$condition = ' and isrecommand=1';
		$rlist = pdo_fetchall("SELECT * FROM " . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($rpindex - 1) * $rpsize . ',' . $rpsize);

		return $this->result(0, '成功', $rlist);
	}
	public function doPageadv() {
		global $_GPC, $_W;
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('shopping_adv') . " where enabled=1 and weid= '{$_W['uniacid']}' order by displayorder asc");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);

		return $this->result(0, '成功', $advs);
	}
	public function doPagelistmore_rec() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = ' and isrecommand=1 ';
		$list = pdo_fetchall("SELECT * FROM " . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		return $this->result(0, '成功', $list);
	}
	public function doPagelistmore() {
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


		$sql = 'SELECT * FROM ' . tablename('shopping_goods') . ' WHERE `weid` = :weid AND `deleted` = :deleted AND `status` = :status ' . $condition .
				' ORDER BY `displayorder` DESC, `sales` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$params[':deleted'] = 0;
		$params[':status'] = 1;
		$list = pdo_fetchall($sql, $params);

		return $this->result(0, '成功', $list);

	}
	public function doPageMaingoods() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC["page"]));
		$psize = 10;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('shopping_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
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
		
		$list = pdo_fetchall("SELECT * FROM " . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY $sortfield LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		foreach ($list as &$r) {
			if ($r['istime'] == 1) {
				$arr = $this->time_tran($r['timeend']);
				$r['timelaststr'] = $arr[0];
				$r['timelast'] = $arr[1];
			}
		}
		unset($r);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' $condition");
		$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
		$carttotal = $this->getCartTotal();
		return $this->result(0, '成功', $list);
	}
	
	public function doPageCategorysub() {
		global $_GPC, $_W;
		$condition = '';
		if (!empty($_GPC['pcate'])) {
			$pcate = intval($_GPC['pcate']);
			$condition .= " AND parentid = '{$pcate}'";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('shopping_category') . " WHERE weid = '{$_W['uniacid']}' $condition and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		return $this->result(0, '成功', $category);
	}
	public function doPageCategorytop() {
		global $_GPC, $_W;
		$category = pdo_fetchall("SELECT * FROM " . tablename('shopping_category') . " WHERE weid = '{$_W['uniacid']}' AND parentid = 0 ORDER BY displayorder DESC", array(), 'id');
		return $this->result(0, '成功', $category);
	}
	
	public function doPageAjaxdelete() {
		global $_GPC;
		$delurl = $_GPC['pic'];
		if (file_delete($delurl)) {
			echo 1;
		} else {
			echo 0;
		}
	}

	public function doPageOrder() {
		global $_W, $_GPC;

		$orderId = intval($_GPC['orderid']);
		$status = intval($_GPC['status']);
		$referStatus = intval($_GPC['curtstatus']);
		$sql = 'SELECT `id` FROM ' . tablename('shopping_order') . ' WHERE `id` = :id AND `weid` = :weid AND `from_user`
				= :from_user';
		$params = array(':id' => $orderId, ':weid' => $_W['uniacid'], ':from_user' => $_W['fans']['from_user']);
		$orderId = pdo_fetchcolumn($sql, $params);
		$redirect = $this->createMobileUrl('myorder', array('status' => $referStatus));
		if (empty($orderId)) {
			return $this->result(1, '订单不存在或已经被删除');
		}

		if ($_GPC['op'] == 'delete') {
			pdo_delete('shopping_order', array('id' => $orderId));
			pdo_delete('shopping_order_goods', array('orderid' => $orderId));
			return $this->result(0, '订单已经成功删除！',$redirect);
		} else {
			pdo_update('shopping_order', array('status' => $status), array('id' => $orderId));
			$order = pdo_get('shopping_order_goods', array('weid' => $_W['uniacid'], 'orderid' => $orderId));
			$goodid = $order['goodsid'];
			$good = pdo_get('shopping_goods', array('weid' => $_W['uniacid'], 'id' => $goodid));
			if ($good['totalcnf'] == 0 && $status == -1) {
				pdo_update('shopping_goods', array('sales' => $good['sales'] -1),array('weid' => $_W['uniacid'], 'id' => $goodid));
			}
			return $this->result(0, '订单已经成功取消',$redirect);
		}
	}
	public function doPageMyCart() {
		global $_W, $_GPC;
		$this->checkAuth();
		$op = $_GPC['op'];
		if ($op == 'add') {
			$goodsid = intval($_GPC['id']);
			$total = intval($_GPC['total']);
			$total = empty($total) ? 1 : $total;
			$optionid = intval($_GPC['optionid']);
			$goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('shopping_goods') . " WHERE id = :id", array(':id' => $goodsid));
			if (empty($goods)) {
				return $this->result(1, '抱歉，该商品不存在或是已经被删除！',$goods);
			}
			$marketprice = $goods['marketprice'];
			if (!empty($optionid)) {
				$option = pdo_fetch("select marketprice from " . tablename('shopping_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
				if (!empty($option)) {
					$marketprice = $option['marketprice'];
				}
			}
			$row = pdo_fetch("SELECT id, total FROM " . tablename('shopping_cart') . " WHERE from_user = :from_user AND weid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['fans']['from_user'], ':goodsid' => $goodsid,':optionid'=>$optionid));
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
				pdo_insert('shopping_cart', $data);
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
				pdo_update('shopping_cart', $data, array('id' => $row['id']));
			}
			//返回数据
			$carttotal = $this->getCartTotal();
			$result = array(
				'result' => 1,
				'total' => $carttotal
			);
			die(json_encode($result));
		} else if ($op == 'clear') {
			pdo_delete('shopping_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid']));
			die(json_encode(array("result" => 1)));
		} else if ($op == 'remove') {
			$id = intval($_GPC['id']);
			pdo_delete('shopping_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid'], 'id' => $id));
			die(json_encode(array("result" => 1, "cartid" => $id)));
		} else if ($op == 'update') {
			$id = intval($_GPC['id']);
			$num = intval($_GPC['num']);
			$sql = "update " . tablename('shopping_cart') . " set total=$num where id=:id";
			pdo_query($sql, array(":id" => $id));
			die(json_encode(array("result" => 1)));
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('shopping_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$totalprice = 0;
			if (!empty($list)) {
				foreach ($list as &$item) {
					$goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('shopping_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,stock from " . tablename("shopping_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
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
			return $this->result(0, '成功', $list);
		}
	}
	public function doPageConfirm() {
		global $_W, $_GPC;
		$this->checkauth();
		$totalprice = 0;
		$allgoods = array();
		$id = intval($_GPC['id']);
		$optionid = intval($_GPC['optionid']);
		$total = intval($_GPC['total']);
		if ( (empty($total)) || ($total < 1) ) {
			$total = 1;
		}
		$direct = false; //是否是直接购买
		$returnUrl = ''; //当前连接
		if (!empty($id)) {
			$sql = 'SELECT `id`, `thumb`, `title`, `weight`, `marketprice`, `total`, `type`, `totalcnf`, `sales`, `unit`, `istime`, `timeend`, `usermaxbuy`
					FROM ' .tablename('shopping_goods') . ' WHERE `id` = :id';
			$item = pdo_fetch($sql, array(':id' => $id));

			if (empty($item)) {
				return $this->result(1, '商品不存在或已经下架', $this->createMobileUrl('detail', array('id' => $id)));
			}
			if ($item['istime'] == 1) {
				if (time() > $item['timeend']) {
					$backUrl = $this->createMobileUrl('detail', array('id' => $id));
					$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
					return $this->result(1, '抱歉，商品限购时间已到，无法购买了！', $backUrl);
				}
			}
			if ($item['total'] - $total < 0) {
				return $this->result(1, '抱歉，[' . $item['title'] . ']库存不足！', $this->createMobileUrl('confirm'));
			}

			if (!empty($optionid)) {
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("shopping_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
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
			$totalprice += $item['totalprice'];
			if ($item['type'] == 1) {
				$needdispatch = true;
			}
			$direct = true;

			// 检查用户最多购买数量
			$sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename('shopping_order_goods') . ' AS `og` JOIN ' . tablename('shopping_order') .
				' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`from_user` = :from_user';
			$params = array(':goodsid' => $id, ':from_user' => $_W['fans']['from_user']);
			$orderTotal = pdo_fetchcolumn($sql, $params);
			if ( (($orderTotal + $item['total']) > $item['usermaxbuy']) && (!empty($item['usermaxbuy']))) {
				return $this->result(1, '您已经超过购买数量了', $this->createMobileUrl('detail', array('id' => $id)));
			}

			$returnUrl = urlencode($_W['siteurl']);
		}
		if (!$direct) {
			//如果不是直接购买（从购物车购买）
			$goodids = $_GPC['goodids'];
			$condition =  empty($goodids) ? '' : 'AND id IN ('.$goodids.")";
			$list = pdo_fetchall("SELECT * FROM " . tablename('shopping_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' {$condition}");
			if (!empty($list)) {
				foreach ($list as &$g) {
					$item = pdo_fetch("select id,thumb,title,weight,marketprice,total,type,totalcnf,sales,unit from " . tablename("shopping_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("shopping_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
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
		$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("shopping_dispatch") . " WHERE weid = {$_W['uniacid']} order by displayorder desc");
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
                $address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
            if ($_GPC['goodstype'] != '2') {
                if (empty($address)) {
                    message('抱歉，请您填写收货地址！');
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
            } else {
                $sendtype = '3 ';
            }
            // 商品价格
            $goodsprice = 0;
            foreach ($allgoods as $row) {
                $goodsprice += $row['totalprice'];
            }

			$data = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'ordersn' => date('md') . random(4, 1),
				'price' => $goodsprice + $dispatchprice,
				'dispatchprice' => $dispatchprice,
				'goodsprice' => $goodsprice,
				'status' => 0,
				'sendtype' => intval($sendtype),
				'dispatch' => $dispatchid,
				'goodstype' => intval($item['type']),
				'remark' => $_GPC['remark'],
				'address' =>  $address['username'] . '|' . $address['mobile'] . '|' . $address['zipcode']
							. '|' . $address['province'] . '|' . $address['city'] . '|' .
							$address['district'] . '|' . $address['address'],
				'createtime' => TIMESTAMP
			);
			pdo_insert('shopping_order', $data);
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
				$o = pdo_fetch("select title from " . tablename('shopping_goods_option') . " where id=:id limit 1", array(":id" => $row['optionid']));
				if (!empty($o)) {
					$d['optionname'] = $o['title'];
				}
				pdo_insert('shopping_order_goods', $d);
			}
			// 清空购物车
			if (!$direct) {
				pdo_delete("shopping_cart", array("weid" => $_W['uniacid'], "from_user" => $_W['fans']['from_user']));
			}
			// 变更商品库存
			if (empty($item['totalcnf'])) {
				$this->setOrderStock($orderid);
			}
			return $this->result(0, '提交订单成功,现在跳转到付款页面...', $this->createMobileUrl('pay', array('orderid' => $orderid)));
		}
		$carttotal = $this->getCartTotal();
		$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
		$row = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['member']['uid']));
		return $this->result(0, '成功', $row);
	}
	public function doPagePay() {
		global $_W, $_GPC;
		$this->checkAuth();
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('shopping_order') . " WHERE id = :id AND weid = :weid", array(':id' => $orderid, ':weid' => $_W['uniacid']));
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
		}
		if (checksubmit('codsubmit')) {
			$ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('shopping_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			if (!empty($ordergoods)) {
				$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('shopping_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
			}
			//邮件提醒
			if (!empty($this->module['config']['noticeemail'])) {
//				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$address = explode('|', $order['address']);
				$body = "<h3>购买商品清单</h3> <br />";
				if (!empty($goods)) {
					foreach ($goods as $row) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("shopping_goods_option") . " where id=:id limit 1", array(":id" => $ordergoods[$row['id']]['optionid']));
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
			pdo_update('shopping_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid, 'uniacid' => $_W['uniacid']));
			message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('myorder'), 'success');
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
		$sql = 'SELECT `goodsid` FROM ' . tablename('shopping_order_goods') . " WHERE `orderid` = :orderid";
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('shopping_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));

		$params['tid'] = $orderid;
		$params['user'] = $_W['fans']['from_user'];
		$params['title'] = $goodsTitle;
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;
		load() -> model('card');
		if (!function_exists('card_discount_fee')) {
			$params['fee'] = $order['price'];
		} else {
			$params['fee'] = card_discount_fee($order['price']);
		}
		return $this->result(0, '成功', $order);
	}

	public function doPageContactUs() {
		global $_W;
		$cfg = $this->module['config'];
		return $this->result(0, '成功', $cfg);
	}
	public function doPageMyOrder() {
		global $_W, $_GPC;
		$this->checkAuth();
		$op = $_GPC['op'];
		if ($op == 'confirm') {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename('shopping_order') . " WHERE id = :id AND from_user = :from_user AND weid = :weid", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user'], ':weid' => $_W['uniacid']));
			if (empty($order)) {
				return $this->result(1, '抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'));
			}
			pdo_update('shopping_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));
			return $this->result(0, '确认收货完成！', $this->createMobileUrl('myorder'));
		} else if ($op == 'detail') {
			$orderid = intval($_GPC['orderid']);
			$item = pdo_fetch("SELECT * FROM " . tablename('shopping_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' limit 1");
			if (empty($item)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
			}
			$goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('shopping_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid FROM " . tablename('shopping_order_goods')
					. " o left join " . tablename('shopping_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
			foreach ($goods as &$g) {
				//属性
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("shopping_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
				if ($option) {
					$g['title'] = "[" . $option['title'] . "]" . $g['title'];
					$g['marketprice'] = $option['marketprice'];
				}
			}
			unset($g);
			$dispatch = pdo_fetch("SELECT id,dispatchname,enabled FROM " . tablename('shopping_dispatch') . ' WHERE id=:id ', array(":id" => $item['dispatch']));
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
			$list = pdo_fetchall("SELECT * FROM " . tablename('shopping_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('shopping_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					$goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('shopping_order_goods') . " WHERE orderid = '{$row['id']}'", array(), 'goodsid');
					$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total,o.optionid FROM " . tablename('shopping_order_goods') . " o left join " . tablename('shopping_goods') . " g on o.goodsid=g.id "
							. " WHERE o.orderid='{$row['id']}'");
					foreach ($goods as &$item) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("shopping_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
						if ($option) {
							$item['title'] = "[" . $option['title'] . "]" . $item['title'];
							$item['marketprice'] = $option['marketprice'];
						}
					}
					unset($item);
					$row['goods'] = $goods;
					$row['total'] = $goodsid;
					$row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('shopping_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
				}
			}
			return $this->result(0, '成功', $list);
		}
	}
	public function doPageDetail() {
		global $_W, $_GPC;
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('shopping_goods') . " WHERE id = :id AND weid = :weid", array(':id' => $goodsid, ':weid' => $_W['uniacid']));
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
		pdo_query("update " . tablename('shopping_goods') . " set viewcount=viewcount+1 where id=:id and weid='{$_W['uniacid']}' ", array(":id" => $goodsid));
		
		$marketprice = $goods['marketprice'];
		$productprice= $goods['productprice'];
		$originalprice = $goods['originalprice'];
		$stock = $goods['total'];
		//规格及规格项
		$allspecs = pdo_fetchall("select * from " . tablename('shopping_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $goodsid));
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('shopping_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
		}
		unset($s);
		//处理规格项
		$options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice, stock,weight,specs from " . tablename('shopping_goods_option') . " where goodsid=:id order by id asc", array(':id' => $goodsid));
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
		$params = pdo_fetchall("SELECT * FROM " . tablename('shopping_goods_param') . " WHERE goodsid=:goodsid order by displayorder asc", array(":goodsid" => $goods['id']));
		$carttotal = $this->getCartTotal();
		return $this->result(0, '成功', $goods);
	}
	public function doPagePiclist(){
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('shopping_goods') . " WHERE id = :id AND weid = :weid", array(':id' => $goodsid, ':weid' => $_W['uniacid']));
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
		return $this->result(0, '成功', $piclist);
	}

	public function doPageAddress() {
		global $_W, $_GPC;
		$this->checkAuth();
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
				pdo_update('mc_members', array('address' => $data['province'].$data['city'].$data['district'].$data['address']), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				$id = pdo_insertid();
				if (!empty($id)) {
					message($id, '', 'ajax');
				} else {
					message(0, '', 'ajax');
				}
			}
		} elseif ($operation == 'default') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid
					 AND `uid` = :uid';
			$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
			$address = pdo_fetch($sql, $params);

			if (!empty($address) && empty($address['isdefault'])) {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				pdo_update('mc_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid'], 'id' => $id));
				pdo_update('mc_members', array('address' => $address['province'].$address['city'].$address['district'].$address['address']), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
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
			return $this->result(0, '成功', $addresses);
		}
	}
}

?>