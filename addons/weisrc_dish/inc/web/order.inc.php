<?php
global $_W, $_GPC;
$weid = $this->_weid;
$setting = $this->getSetting();
load()->func('tpl');
$action = 'order';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);
$GLOBALS['frames'] = $this->getNaveMenu($storeid,$action);
$returnid = $this->checkPermission($storeid);
$cur_store = $this->getStoreById($storeid);
if (empty($cur_store)) {
    message('门店不存在!');
}


$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'fengniaolist') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;

    $commoncondition = " weid = '{$_W['uniacid']}' ";
    if ($storeid != 0) {
        $commoncondition .= " AND storeid={$storeid} ";
    }
    $list = pdo_fetchall("SELECT * FROM " . tablename("weisrc_dish_fengniao") . " WHERE $commoncondition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename("weisrc_dish_fengniao") . " WHERE $commoncondition");
    $pager = pagination($total, $pindex, $psize);
}elseif ($operation == 'display') {
    $commoncondition = " weid = '{$_W['uniacid']}' ";
    if ($storeid != 0) {
        $commoncondition .= " AND storeid={$storeid} ";
    }
    $ispay = intval($_GPC['ispay']);
    if (isset($_GPC['ispay']) && $_GPC['ispay'] != '') {
        $commoncondition .= " AND ispay={$ispay} ";
    }

    if (!empty($_GPC['time'])) {
        $starttime = strtotime($_GPC['time']['start']);
        $endtime = strtotime($_GPC['time']['end']);
        $commoncondition .= " AND dateline >= :starttime AND dateline <= :endtime ";
        $paras[':starttime'] = $starttime;
        $paras[':endtime'] = $endtime;
    }

    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime = time();
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;

    if (!empty($_GPC['ordersn'])) {
        $commoncondition .= " AND ordersn LIKE '%{$_GPC['ordersn']}%' ";
    }
    if (!empty($_GPC['dining_mode'])) {
        $commoncondition .= " AND dining_mode = '" . intval($_GPC['dining_mode']) . "' ";
    }

    $tablesid = $_GPC['tableid'];
    $table = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " where weid = :weid AND title=:title LIMIT 1", array(':weid' => $weid, ':title' => $tablesid));
    if (!empty($table)) {
        $commoncondition .= " AND tables = '" . $table['id'] . "' ";
    }

    if (isset($_GPC['status']) && $_GPC['status'] != '') {
        $commoncondition .= " AND status = '" . intval($_GPC['status']) . "'";
    }

    if (isset($_GPC['paytype']) && $_GPC['paytype'] != '') {
        $commoncondition .= " AND paytype = '" . intval($_GPC['paytype']) . "'";
    }

//    if ($_GPC['out_put'] == 'output') {
//        $sql = "select * from " . tablename($this->table_order)
//            . " WHERE $commoncondition ORDER BY status DESC, dateline DESC ";
//        $list = pdo_fetchall($sql, $paras);
//        $orderstatus = array(
//            '-1' => array('css' => 'default', 'name' => '已取消'),
//            '0' => array('css' => 'danger', 'name' => '待处理'),
//            '1' => array('css' => 'info', 'name' => '已确认'),
//            '2' => array('css' => 'warning', 'name' => '已付款'),
//            '3' => array('css' => 'success', 'name' => '已完成')
//        );
//
//        $paytypes = array(
//            '0' => array('css' => 'danger', 'name' => '未支付'),
//            '1' => array('css' => 'info', 'name' => '余额支付'),
//            '2' => array('css' => 'warning', 'name' => '微信支付'),
//            '3' => array('css' => 'success', 'name' => '现金支付'),
//            '4' => array('css' => 'warning', 'name' => '支付宝'),
//        );
//
//        $i = 0;
//        foreach ($list as $key => $value) {
//            $arr[$i]['ordersn'] = "'".$value['ordersn'];
//            $arr[$i]['transid'] = "'".$value['transid'];
//            $arr[$i]['paytype'] = $paytypes[$value['paytype']]['name'];
//            $arr[$i]['status'] = $orderstatus[$value['status']]['name'];
//            $arr[$i]['totalprice'] = $value['totalprice'];
//            $arr[$i]['goodsprice'] = $value['goodsprice'];
//            $arr[$i]['dispatchprice'] = $value['dispatchprice'];
//            $arr[$i]['packvalue'] = $value['packvalue'];
//            $arr[$i]['tea_money'] = $value['tea_money'];
//            $arr[$i]['service_money'] = $value['service_money'];
//            $arr[$i]['username'] = $value['username'];
//            $arr[$i]['tel'] = $value['tel'];
//            $arr[$i]['address'] = $value['address'];
//            $arr[$i]['dateline'] = date('Y-m-d H:i:s', $value['dateline']);
//            $i++;
//        }
//
//        $this->exportexcel($arr, array('订单号', '商户订单号', '支付方式', '状态', '总价', '商品价格', '配送费', '打包费', '茶位费', '服务费', '真实姓名', '电话号码', '地址', '时间'), time());
//        exit();
//    }
    if ($_GPC['out_put'] == 'output') {
        $commoncondition .= " AND ismerge = 0 ";
        $this->out_order($commoncondition, $paras);
    } else if($_GPC['out_put'] == 'out_goods') {
        $commoncondition .= " AND ismerge = 0 ";
        $this->out_goods($commoncondition, $paras);
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE $commoncondition ORDER BY id desc, dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE $commoncondition", $paras);
    $pager = pagination($total, $pindex, $psize);

    $order_count = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE weid=:weid AND status=0 AND storeid=:storeid LIMIT 1", array(':weid' => $this->_weid, ':storeid' => $storeid));

    if (!empty($list)) {
        foreach ($list as $key => $value) {
            $userids[$row['from_user']] = $row['from_user'];
            if ($value['dining_mode'] == 1 || $value['dining_mode'] == 3) {
                $tablesid = intval($value['tables']);
                $table = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " where id=:id LIMIT 1", array(':id' => $tablesid));
                if (!empty($table)) {
                    $table_title = $table['title'];
                    $list[$key]['table'] = $table_title;
                }
            }
        }
    }

    $order_price = pdo_fetchcolumn("SELECT sum(totalprice) FROM " . tablename($this->table_order) . " WHERE $commoncondition ", $paras);
    $order_price = sprintf('%.2f', $order_price);

    //打印数量
    $print_order_count = pdo_fetchall("SELECT orderid,COUNT(1) as count FROM " . tablename($this->table_print_order) . "  GROUP BY orderid,weid having weid = :weid", array(':weid' => $_W['uniacid']), 'orderid');

    //门店列表
    $storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid", array(':weid' => $_W['uniacid']), 'id');
} elseif ($operation == 'detail') {
    //流程 第一步确认付款 第二步确认订单 第三步，完成订单
    $id = intval($_GPC['id']);
    $order = $this->getOrderById($id);
    $fans = $this->getFansByOpenid($order['from_user']);

    if ($order['dining_mode'] == 1) {
        $tablelist = $this->getAllTableByStoreid($order['storeid']);
    }

    $orderlog = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_log) . " WHERE orderid=:orderid ORDER BY id desc, dateline DESC", array(':orderid' => $id));

    if (!empty($_GPC['confirmtable'])) {
        $tableid = intval($_GPC['tableid']);
        $table = $this->getTableById($tableid);
        if (!empty($table)) {
            $tablezones = $this-> getTablezonesById($table['tablezonesid']);
            $tablezonesid = $tablezones['id'];
        }
        pdo_update($this->table_order, array('tables' => $tableid, 'tablezonesid' => $tablezonesid), array('id' => $id));
        message('操作成功！', referer(), 'success');
    }
    //改价
    if (!empty($_GPC['confirmprice'])) {
        if ($setting['is_operator_pwd'] == 1) {
            $operator_pwd = trim($_GPC['operator_pwd']);
            if ($setting['operator_pwd'] != $operator_pwd) {
                message('操作密码错误，请重新输入!');
            }
        }
        pdo_update($this->table_order, array('totalprice' => $_GPC['updateprice']), array('id' => $id));
        $paylog = pdo_fetch("SELECT * FROM " . tablename('core_paylog') . " WHERE tid=:tid AND uniacid=:uniacid AND status=0 AND module='weisrc_dish'
ORDER BY plid
DESC LIMIT 1", array(':tid' => $id, ':uniacid' => $this->_weid));
        if (!empty($paylog)) {
            pdo_update('core_paylog', array('fee' => $_GPC['updateprice']), array('plid' => $paylog['plid']));
        }
        $this->addOrderLog($id, $_W['user']['username'], 2, 2, 7, $order['totalprice'], $_GPC['updateprice']);
        message('改价成功！', referer(), 'success');
    }
    if (checksubmit('discount_submit')) {
        $rebate = round(floatval($_GPC['discount_rebate']), 2);
        if (empty($rebate)) {
            message("你输入的折扣率有误", referer(), 'error');
        }
    }
	//加菜查询begin
    if ($_POST['selectDish']) {
        $dishCondition = '';
        if ($_POST['addDishName']) {
            $dishCondition = "AND pcate=" . intval($_POST['addDishName']);
        }
        $allGoods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE storeid=:storeid AND weid=:weid  AND deleted=0 " . $dishCondition, array(":storeid" => $storeid, ":weid" => $this->_weid));
        foreach($allGoods as $key => $value) {
            $allGoods[$key]['thumb'] = tomedia($value['thumb']);
        }
        exit(json_encode($allGoods));
    }
    if ($_POST['addDish'] && !empty($_POST['dish'])) {
        $dish = $_POST['dish'];
        $dishInfo = pdo_fetchall("SELECT goodsid,price,total FROM " . tablename($this->table_order_goods) . " WHERE weid=:weid AND storeid=:storeid AND orderid=:orderid", array(":weid" => $weid, ":storeid" => $storeid, ":orderid" => $id));
        foreach ($dishInfo as $v) {
            $dishid[] = $v['goodsid'];
        }
        foreach ($dish as $k => $v) {
            if ($v['status'] != "己选择" || empty($v['num'])) {
                unset($dish[$k]);
            } else {
                if (!empty($dish)) {
                    if (in_array($k, $dishid)) {
                        $dishParm = array("total" => "total+{$v['num']}", "dateline" => time);
                        $dishCon = array(":weid" => $weid,":storeid"=>$storeid,":orderid"=>$id,":goodsid"=>$k);
                        $sql="UPDATE ".tablename($this->table_order_goods)." SET total=total+{$v['num']},dateline=".time()." WHERE weid=:weid AND storeid=:storeid AND orderid=:orderid AND goodsid=:goodsid";
                        pdo_query($sql,$dishCon);
                    } else {
                        $parm = array("weid" => $weid, "storeid" => $storeid, "orderid" => $id, "goodsid" => $k, "price" => $v['price'], "total" => $v['num'], 'dateline' => time());
                        pdo_insert($this->table_order_goods, $parm);
                    }

                    $add_goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid=:weid AND id=:id ORDER by id DESC LIMIT 1", array(':weid' => $this->_weid, ':id' => $k));
                    $touser = $_W['user']['username'] . '&nbsp;加菜：' . $add_goods['title'] . "*" . $v['num'] . ",";
                    $this->addOrderLog($id, $touser, 2, 2, 1);
                }
            }
        }
        $newOrder=pdo_fetchall("SELECT price,total FROM ".tablename($this->table_order_goods)." WHERE orderid=:id",array(":id"=>$id));
        foreach($newOrder as $v){
            $dishTotal['num']+=$v['total'];
            $dishTotal['price']+=(number_format(floatval($v['price']),2) * $v['total']);
        }

        $newtotalprice = 0;
        $newtotalprice = $dishTotal['price'] + floatval($order['tea_money']) + floatval($order['service_money']) + floatval($order['dispatchprice']) + floatval($order['packvalue']);
        pdo_update($this->table_order, array("totalnum" => $dishTotal['num'], "totalprice" => $newtotalprice, "goodsprice" => $dishTotal['price']), array("id" => $id));

        message('操作成功！', referer(), 'success');
    }

    if (checksubmit('confrimpay')) {
        pdo_update($this->table_order, array('ispay' => 1), array('id' => $id));
        $this->addOrderLog($id, $_W['user']['username'], 2, 2, 2);
        message('操作成功！', referer(), 'success');
    }

    if (checksubmit('confrimsign')) {
        pdo_update($this->table_order, array('reply' => $_GPC['reply']), array('id' => $id));
        message('操作成功！', referer(), 'success');
    }

    $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $order['storeid'], ':weid' => $weid));

    if (!empty($_GPC['finish'])) {
        //isfinish
        if ($order['isfinish'] == 0) {
            //计算积分
            $this->setOrderCredit($order['id']);
            pdo_update($this->table_order, array('isfinish' => 1), array('id' => $id));
            pdo_update($this->table_fans, array('paytime' => TIMESTAMP), array('id' => $fans['id']));
            if ($order['dining_mode'] == 1) {
                pdo_update($this->table_tables, array('status' => 0), array('id' => $order['tables']));
            }
            $this->set_commission($id);

            //奖励配送员
            $delivery_money = floatval($order['delivery_money']);//配送佣金
            $delivery_id = intval($order['delivery_id']);//配送员
            if ($delivery_money > 0) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'storeid' => $order['storeid'],
                    'orderid' => $order['id'],
                    'delivery_id' => $delivery_id,
                    'price' => $delivery_money,
                    'dateline' => TIMESTAMP,
                    'status' => 0
                );
                pdo_insert("weisrc_dish_delivery_record", $data);
            }
        }
        pdo_update($this->table_order, array('status' => 3, 'finishtime' => TIMESTAMP), array('id' => $id, 'weid' => $weid));
        $this->addOrderLog($id, $_W['user']['username'], 2, 2, 4);
        $this->updateFansData($order['from_user']);
        $this->updateFansFirstStore($order['from_user'], $order['storeid']);
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $id, ':weid' => $this->_weid));
        $this->sendOrderNotice($order, $store, $setting);
        message('订单操作成功！', referer(), 'success');
    }

    if (!empty($_GPC['confirm'])) {
        pdo_update($this->table_order, array('status' => 1, 'confirmtime' => TIMESTAMP), array('id' => $id, 'weid' => $weid));
        $this->addOrderLog($id, $_W['user']['username'], 2, 2, 3);
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $id, ':weid' => $this->_weid));
        $this->sendOrderNotice($order, $store, $setting);
        message('确认订单操作成功！', referer(), 'success');
    }
    if (!empty($_GPC['cancel'])) {
        pdo_update($this->table_order, array('status' => -1), array('id' => $id, 'weid' => $weid));
        $this->addOrderLog($id, $_W['user']['username'], 2, 2, 5);
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $id, ':weid' => $this->_weid));
        $this->sendOrderNotice($order, $store, $setting);
        message('订单关闭操作成功！', referer(), 'success');
    }
    if (!empty($_GPC['open'])) {
        pdo_update($this->table_order, array('status' => 0), array('id' => $id, 'weid' => $weid));
        $this->addOrderLog($id, $_W['user']['username'], 2, 2, 8);
        message('开启订单操作成功！', referer(), 'success');
    }

    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $id));
    $goods = pdo_fetchall("SELECT a.goodsid,a.price, b.credit, a.total,b.thumb,b.title,b.id ,b.pcate FROM " . tablename($this->table_order_goods) . " a INNER JOIN " . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE a.orderid = :id", array(':id' => $id));
    $discount = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid=:weid and storeid=:storeid", array(":weid" => $weid,":storeid"=>$storeid));
    if ($item['dining_mode'] == 1 || $item['dining_mode'] == 3) {
        $tablesid = intval($item['tables']);
        $table = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $tablesid));
        if (!empty($table)) {
            $tablezones = pdo_fetch("SELECT * FROM " . tablename($this->table_tablezones) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $table['tablezonesid']));
            $table_title = $tablezones['title'] . '-' . $table['title'];
        }
    }

    if ($item['couponid'] != 0) {
        $coupon = pdo_fetch("SELECT a.* FROM " . tablename($this->table_coupon) . "
        a INNER JOIN " . tablename($this->table_sncode) . " b ON a.id=b.couponid
 WHERE a.weid = :weid AND b.id=:couponid ORDER BY b.id
 DESC LIMIT 1", array(':weid' => $weid, ':couponid' => $item['couponid']));
        if (!empty($coupon)) {
            if ($coupon['type'] == 2) {
                $coupon_info = "抵用金额" . $order['discount_money'];
            } else {
                $coupon_info = $coupon['title'];
            }
        }
    }
} else if ($operation == 'print') {
    $id = $_GPC['id'];//订单id
    $flag = false;

    $prints = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE weid = :weid AND storeid=:storeid", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));

    if (empty($prints)) {
        message('请先添加打印机或者开启打印机！');
    }

    foreach ($prints as $key => $value) {
        if ($value['print_status'] == 1 && $value['type'] == 'hongxin') {
            $data = array(
                'weid' => $_W['uniacid'],
                'orderid' => $id,
                'print_usr' => $value['print_usr'],
                'print_status' => -1,
                'dateline' => TIMESTAMP
            );
            pdo_insert('weisrc_dish_print_order', $data);
        }
    }
    $this->feieSendFreeMessage($id);
    $this->feiyinSendFreeMessage($id);
    $this->_365SendFreeMessage($id);
    $this->_yilianyunSendFreeMessage($id);
	$this->_jinyunSendFreeMessage($id); //进云物联
    message('操作成功！', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)), 'success');
} elseif ($operation == 'printall') {
    $position_type = intval($_GPC['position_type']);
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $order = $this->getOrderById($id);
            $prints = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE weid = :weid AND storeid=:storeid", array(':weid' => $weid, ':storeid' => $order['storeid']));

            if (empty($prints)) {
                $notrowcount++;
                continue;
            }

            foreach ($prints as $key => $value) {
                if ($value['print_status'] == 1 && $value['type'] == 'hongxin') {
                    $data = array(
                        'weid' => $weid,
                        'orderid' => $id,
                        'print_usr' => $value['print_usr'],
                        'print_status' => -1,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert($this->table_print_order, $data);
                }
            }
            $this->feieSendFreeMessage($id, $position_type);
            $this->feiyinSendFreeMessage($id, $position_type);
            $this->_365SendFreeMessage($id , $position_type);
			$this->_jinyunSendFreeMessage($id, $position_type); //进云物联
            $this->_yilianyunSendFreeMessage($id, $position_type);
            $rowcount++;
        }
    }
    $this->message("操作成功！", '', 0);
} elseif ($operation == 'payall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $order = $this->getOrderById($id);
            if ($order) {
                pdo_update($this->table_order, array('ispay' => 1, 'paytime' => TIMESTAMP), array('id' => $id, 'weid' => $weid));
                $this->addOrderLog($id, $_W['user']['username'], 2, 2, 2);
                $rowcount++;
            }
        }
    }
    $this->message("操作成功,共操作{$rowcount}条数据!", '', 0);
} elseif ($operation == 'confirmall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $order = $this->getOrderById($id);
            if ($order) {
                pdo_update($this->table_order, array('status' => 1, 'confirmtime' => TIMESTAMP), array('id' => $id, 'weid' => $weid));
                $this->addOrderLog($id, $_W['user']['username'], 2, 2, 3);
                $rowcount++;
            }
        }
    }
    $this->message("操作成功,共操作{$rowcount}条数据!", '', 0);
} elseif ($operation == 'cancelall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $order = $this->getOrderById($id);
            if ($order) {
                if ($order['ispay'] == 1) {
                    $coin = floatval($order['totalprice']);
                    if ($order['paytype'] == 1) {
                        $this->setFansCoin($order['from_user'], $coin, "码上点餐单号{$order['ordersn']}退款");
                    }
                }
                pdo_update($this->table_order, array('status' => -1), array('id' => $id, 'weid' => $weid));
                $this->addOrderLog($id, $_W['user']['username'], 2, 2, 5);
                $order = $this->getOrderById($id);
                $this->sendOrderNotice($order, $store, $setting);
                $rowcount++;
            }
        }
    }
    $this->message("操作成功,共操作{$rowcount}条数据!", '', 0);
} elseif ($operation == 'finishall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $order = $this->getOrderById($id);
            if ($order) {
                if ($order['isfinish'] == 0) {
                    //计算积分
                    $this->setOrderCredit($order['id']);
                    pdo_update($this->table_order, array('isfinish' => 1), array('id' => $id));
                    pdo_update($this->table_fans, array('paytime' => TIMESTAMP), array('id' => $fans['id']));
                    if ($order['dining_mode'] == 1) {
                        pdo_update($this->table_tables, array('status' => 0), array('id' => $order['tables']));
                    }
                    $this->set_commission($id);

                    //奖励配送员
                    $delivery_money = floatval($order['delivery_money']);//配送佣金
                    $delivery_id = intval($order['delivery_id']);//配送员
                    if ($delivery_money > 0) {
                        $data = array(
                            'weid' => $_W['uniacid'],
                            'storeid' => $order['storeid'],
                            'orderid' => $order['id'],
                            'delivery_id' => $delivery_id,
                            'price' => $delivery_money,
                            'dateline' => TIMESTAMP,
                            'status' => 0
                        );
                        pdo_insert("weisrc_dish_delivery_record", $data);
                    }
                }
                pdo_update($this->table_order, array('status' => 3, 'finishtime' => TIMESTAMP), array('id' => $id, 'weid' => $weid));
                $this->addOrderLog($id, $_W['user']['username'], 2, 2, 4);
                $this->updateFansData($order['from_user']);
                $this->updateFansFirstStore($order['from_user'], $order['storeid']);
                $order = $this->getOrderById($id);
                $store = $this->getStoreById($order['storeid']);
                $this->sendOrderNotice($order, $store, $setting);
                $rowcount++;
            }
        }
    }
    $this->message("操作成功,共操作{$rowcount}条数据!", '', 0);
} elseif ($operation == 'noticeall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $order = $this->getOrderById($id);
            $store = $this->getStoreById($order['storeid']);
            if ($order) {
                $this->sendOrderNotice($order, $store, $setting);
                $rowcount++;
            }
        }
    }
    $this->message("操作成功,共操作{$rowcount}条数据!", '', 0);
} elseif ($operation == 'refund') {
    if ($setting['is_operator_pwd'] == 1) {
        $operator_pwd = trim($_GPC['operator_pwd']);
        if (empty($operator_pwd)) {
            message('请输入操作密码!');
        }
        if ($setting['operator_pwd'] != $operator_pwd) {
            message('操作密码错误，请重新输入!');
        }
    }

    $url = $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid));
    $id = $_GPC['id'];
    $order = $this->getOrderById($id);

    if (empty($order)) {
        message('订单不存在！', '', 'error');
    }
    $this->addOrderLog($id, $_W['user']['username'], 2, 2, 6);
    if ($order['ispay'] == 1 || $order['ispay'] == 2 || $order['ispay'] == 4) { //已支付和待退款的可以退款
        if ($order['paytype'] == 2) { //微信支付
            $result = $this->refund($id);
            if ($result == 1) {
                message('退款成功！', $url, 'success');
            } else {
                message('退款失败！', $url, 'error');
            }
        } else if ($order['paytype'] == 1) {
            $coin = floatval($order['totalprice']);
            $this->setFansCoin($order['from_user'], $coin, "码上点餐单号{$order['ordersn']}退款");
            pdo_update($this->table_order, array('ispay' => 3), array('id' => $id));
            message('操作成功！', $url, 'success');
        } else {
            pdo_update($this->table_order, array('ispay' => 3), array('id' => $id));
            message('操作成功！', $url, 'success');
        }
    } else {
        message('操作失败！', '', 'error');
    }
} elseif ($operation == 'mergeall') {
    $rowcount = 0;
    $notrowcount = 0;
    //1.判断店内订单，未支付的订单;
    //2.以最初的单子为主，生成新订单，合并金额
    //3.先判断商品是否存在，存在的话增加数量

    //订单金额
    $totalprice = 0;
    $totalnum = 0;
    $goodsprice = 0;
    $teavalue = 0;
    $service_money = 0;
    $discount_money = 0;
    $counts = 0;
    $sid = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $order = $this->getOrderById($id);
            if ($order) {
                if ($sid != 0 && $sid != $order['storeid']) {
                    $this->message("不同门店的订单不允许合并!", '', 0);
                }
                if ($order['dining_mode'] != 1) {
                    $this->message("您设置的订单编号{$order['id']}不是店内订单", '', 0);
                }
                if ($order['ismerge'] == 1) {
                    $this->message("您设置的订单编号{$order['id']}是并单，不能合并", '', 0);
                }
                $totalprice = $totalprice + floatval($order['totalprice']);
                $totalnum = $totalnum + intval($order['totalnum']);
                $counts = $counts + intval($order['counts']);
                $goodsprice = $goodsprice + floatval($order['goodsprice']);
                $teavalue = $teavalue + floatval($order['tea_money']);
                $service_money = $service_money + floatval($order['service_money']);
                $discount_money = $discount_money + floatval($order['discount_money']);
                $sid = $order['storeid'];
            }
        }
    }

    $data = array(
        'weid' => $weid,
        'from_user' => $order['from_user'],
        'storeid' => $order['storeid'],
        'couponid' => $order['couponid'],
        'discount_money' => $discount_money,
        'ordersn' => date('Ymd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99)),
        'totalnum' => $totalnum, //产品数量
        'totalprice' => $totalprice, //总价
        'goodsprice' => $goodsprice,
        'tea_money' => $teavalue,
        'service_money' => $service_money,
        'dispatchprice' => 0,
        'packvalue' => 0,
        'paytype' => 3, //付款类型
        'username' => $order['username'],
        'tel' => $order['tel'],
        'meal_time' => '',
        'counts' => $counts,
        'seat_type' => '',
        'tables' => $order['tables'],
        'tablezonesid' => $order['tablezonesid'],
        'carports' => 0,
        'dining_mode' => 1, //订单类型
        'remark' => $order['remark'], //备注
        'address' => $order['address'], //地址
        'status' => 0, //状态
        'rechargeid' => 0,
        'lat' => 0,
        'lng' => 0,
        'isvip' => $order['isvip'],
        'isfinish' => 1,
        'ismerge' => 1,
        'is_append' => 0,
        'dateline' => TIMESTAMP
    );

    //保存订单
    pdo_insert($this->table_order, $data);
    $neworderid = pdo_insertid();

    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $id));
            foreach ($list as $key => $val) {
                $goodsid = intval($val['goodsid']);
                $order_goods = pdo_fetch("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid AND goodsid=:goodsid", array(':orderid' => $neworderid, ':goodsid' => $goodsid));
                if ($order_goods) {
                    $goodsprice = floatval($order_goods['price']);
                    $goodstotal = intval($order_goods['total']);
                    $goodstotal = intval($val['total']) + $goodstotal;

                    pdo_update($this->table_order_goods, array(
                        'weid' => $_W['uniacid'],
                        'storeid' => $val['storeid'],
                        'goodsid' => $val['goodsid'],
                        'orderid' => $neworderid,
                        'price' => $goodsprice,
                        'total' => $goodstotal,
                        'dateline' => TIMESTAMP,
                    ), array('id' => $order_goods['id']));
                } else {
                    $goodsprice = floatval($val['price']);
                    $goodstotal = intval($val['total']);

                    pdo_insert($this->table_order_goods, array(
                        'weid' => $_W['uniacid'],
                        'storeid' => $val['storeid'],
                        'goodsid' => $val['goodsid'],
                        'orderid' => $neworderid,
                        'price' => $goodsprice,
                        'total' => $goodstotal,
                        'dateline' => TIMESTAMP,
                    ));
                }
            }
        }
    }
    $this->message("操作成功!", '', 0);
}elseif($operation == 'sendfengniao') {
    $id = intval($_GPC['id']);
    $order = $this->getOrderById($id);
    $this->sendfengniao($order, $cur_store, $setting);
    message("操作成功");
}
include $this->template('web/order');