<?php

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$member_list = $this->get_member_info($this->openid, 'openid');
$time = time();
if ($op == 'display') {
    $goods_id = intval($_GPC['goods_id']);
    $stage_id = intval($_GPC['stage_id']);
    $sql = 'SELECT * FROM ' . tablename($this->goods) . ' WHERE id = :id and uniacid = :uniacid and status= 1';
    $goods_list = pdo_fetch($sql, array(':id' => $goods_id, ':uniacid' => $this->uniacid));
    $seksillprice_array = explode('.', $goods_list['seksillprice']);
    $specifications_array = iunserializer($goods_list['specifications']);
    $sql = 'SELECT * FROM ' . tablename($this->stage) . ' WHERE uniacid=:uniacid AND id=:id ';
    $stage_list = pdo_fetch($sql, array(':id' => $stage_id, ':uniacid' => $this->uniacid));
    $timestart = strtotime($stage_list['timestart']);
    $timeend = strtotime($stage_list['timeend']);
    if ($timestart <= $time) {
        if ($timeend < $time) {
            $first_status = 2;
        } else {
            $first_status = 1;
        }
    } else {
        $first_status = 0;
    }
    $share_desc = $goods_list['name'] . '低至' . $goods_list['seksillprice'] . '元，小伙伴赶快来抢购吧！';
    include $this->template('seckill_goods');
    return 1;
}
if ($op == 'order_ajax') {
    $goods_id = intval($_GPC['goods_id']);
    $stage_id = intval($_GPC['stage_id']);
    $sql = 'SELECT order_id FROM ' . tablename($this->order) . ' where uniacid = :uniacid  AND goods_id = :goods_id AND stage_id=:stage_id AND member=:member';
    $order_list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid, ':goods_id' => $goods_id, ':stage_id' => $stage_id, ':member' => $member_list['id']));
    $sql = 'SELECT * FROM ' . tablename($this->goods) . ' WHERE id = :id and uniacid = :uniacid and status= 1';
    $item = pdo_fetch($sql, array(':id' => $goods_id, ':uniacid' => $this->uniacid));
    if ($item['usermaxbuy'] <= count($order_list)) {
        $res['status'] = 3;
    } else {
        $number = intval($_GPC['number']);
        $count_kc = $this->get_available_num($goods_id, $stage_id, 1);
        if ($count_kc < $number) {
            $res['status'] = 2;
            $res['count_kc'] = $count_kc;
        } else {
            $order_totalprice = $number * $item['seksillprice'];
            $data = array('uniacid' => $this->uniacid, 'order_no' => $this->get_order_num($goods_id), 'member' => $member_list['id'], 'goods_id' => $goods_id, 'stage_id' => $stage_id, 'goods_nature' => $_GPC['nature'], 'goods_number' => $number, 'goods_seksillprice' => $item['seksillprice'], 'order_totalprice' => $order_totalprice, 'order_time' => $time);
            $result = pdo_insert($this->order, $data);
            if (!empty($result)) {
                $order_id = pdo_insertid();
                $res['url'] = $this->createmobileurl('seckill_goods', array('op' => 'order_queding', 'order_id' => $order_id));
                $res['status'] = 1;
            } else {
                $res['status'] = 0;
            }
        }
    }
    echo json_encode($res);
    return 1;
}
if ($op == 'order_queding') {
    $order_id = intval($_GPC['order_id']);
    $sql = ' SELECT * FROM ' . tablename($this->order) . ' WHERE order_id = :order_id and uniacid = :uniacid   and status = 1';
    $order_list = pdo_fetch($sql, array(':order_id' => $order_id, ':uniacid' => $this->uniacid));
    if (empty($order_list)) {
        message('该订单信息不存在！');
    }
    $sql = 'SELECT * FROM ' . tablename($this->goods) . ' WHERE id = :id and uniacid = :uniacid and status= 1';
    $goods_list = pdo_fetch($sql, array(':id' => $order_list['goods_id'], ':uniacid' => $this->uniacid));
    $count_kc = $this->get_available_num($order_list['goods_id'], $order_list['stage_id'], 2);
    if ($count_kc < $number) {
        pdo_update($this->order, array('status' => 5, 'qx_ttime' => $time), array('uniacid' => $this->uniacid, 'order_id' => $order_id));
        message('该商品已经抢购完，系统自动取消该订单!', $this->createmobileurl('seckill_user'), 'error');
    }
    $address_array = json_decode($member_list['address'], true);
    foreach ($address_array as $k => $val) {
        if ($val['status'] == 1) {
            $address = $val;
        }
    }
    $sql = ' SELECT address FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
    $add_str = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
    $add_arr = explode(' ', $add_str);
    if ($config['bb_show'] == 2) {
        $peis_sql = ' SELECT * FROM ' . tablename($this->peis) . ' WHERE uniacid = :uniacid AND status = 1 ORDER BY compositor desc';
        $peis_list = pdo_fetchall($peis_sql, array(':uniacid' => $this->uniacid));
    }
    include $this->template('seckill_order');
    return 1;
}
if ($op == 'peis_ajax') {
    $peis_id = intval($_GPC['id']);
    $order_totalprice = $_GPC['order_totalprice'];
    $sql = 'SELECT delivery_fee FROM ' . tablename($this->peis) . ' WHERE uniacid = :uniacid AND id = :id';
    $delivery_fee = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':id' => $peis_id));
    if ($delivery_fee == '0.00') {
        $data['sm'] = '包邮';
        $data['status'] = 0;
    } else {
        $y_order_totalprice = $order_totalprice + $delivery_fee;
        $data['sm'] = $y_order_totalprice;
        $data['delivery_fee'] = $delivery_fee;
        $data['status'] = 1;
    }
    echo json_encode($data);
    return 1;
}
if ($op == 'order_save') {
    $order_id = intval($_GPC['order_id']);
    $order_sql = ' select * from ' . tablename($this->order) . ' where uniacid = :uniacid and order_id = :order_id and status = 1';
    $item = pdo_fetch($order_sql, array(':uniacid' => $this->uniacid, ':order_id' => $order_id));
    if (empty($item)) {
        message('订单支付链接错误，无法查找此订单!', $this->createmobileurl('seckill_home'), 'error');
    }
    $data = array('peis' => $_GPC['peis'], 'order_yunfei' => $_GPC['order_yunfei'], 'address' => $_GPC['address']);
    $save = pdo_update($this->order, $data, array('uniacid' => $this->uniacid, 'order_id' => $order_id));
    if ($save === false) {
        message('订单支付链接错误，无法查找此订单!', referer(), 'error');
        return 1;
    }
    $item = pdo_fetch($order_sql, array(':uniacid' => $this->uniacid, ':order_id' => $order_id));
    if ('0.00' < $item['order_yunfei']) {
        $item['order_totalprice'] = $item['order_totalprice'] + $item['order_yunfei'];
    }
    $fee = floatval($item['order_totalprice']);
    if ($fee <= '0.00') {
        message('支付错误, 金额小于0');
    }
    $params['tid'] = $item['order_no'];
    $params['user'] = $_W['fans']['from_user'];
    $params['fee'] = $fee;
    $params['title'] = '秒杀--' . $this->get_goods_name($item['goods_id']);
    $params['ordersn'] = $item['order_no'];
    $params['virtual'] = true;
    $this->pay($params);
    return 1;
}
if ($op == 'order_status') {
    $order_id = $_GPC['order_id'];
    $status = $_GPC['status'];
    $time = time();
    $data['status'] = $status;
    if ($status == 4) {
        $data['sign_ttime'] = $time;
        $sm = '签收';
    }
    if ($status == 5) {
        $data['qx_ttime'] = $time;
        $sm = '取消';
    }
    $save = pdo_update($this->order, $data, array('uniacid' => $this->uniacid, 'order_id' => $order_id));
    if ($save === false) {
        message('订单' . $sm . '失败');
        return 1;
    }
    message('订单' . $sm . '成功！', $this->createmobileurl('seckill_user'), 'success');
}