<?php

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$member_list = $this->get_member_info($this->openid, 'openid');
if ($op == 'display') {
    $status = intval($_GPC['status']);
    $where = ' o.uniacid = ' . $this->uniacid;
    $where .= ' AND o.member = ' . $member_list['id'];
    if ($config['bb_show'] == 1) {
        if (!$status) {
            $status = 1;
        }
    }
    if ($status) {
        $where .= ' AND o.status =' . $status;
    }
    $sql = ' SELECT o.*,g.name,g.thumb FROM ' . tablename($this->order) . '  o left join ' . tablename($this->goods) . ' g ON g.id = o.goods_id  WHERE ' . $where . ' ORDER BY order_time desc';
    $order_list = pdo_fetchall($sql);
    if ($config['bb_show'] == 1) {
        include $this->template('seckill_user_bd');
        return 1;
    }
    include $this->template('seckill_user');
    return 1;
}
if ($op == 'address') {
    $sql = ' SELECT address FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
    $address_json = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
    $address = json_decode($address_json, true);
    include $this->template('user_address');
    return 1;
}
if ($op == 'change_status') {
    $id = $_GPC['id'];
    $sql = ' SELECT address FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
    $address_json = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
    $address = json_decode($address_json, true);
    foreach ($address as $k => $v) {
        if ($v['id'] == $id) {
            $address[$k]['status'] = 1;
        } else {
            $address[$k]['status'] = 0;
        }
    }
    $update = array('address' => json_encode($address));
    $result = pdo_update('hetu_seckill_user', $update, array('uniacid' => $this->uniacid, 'openid' => $this->openid));
    if ($result === false) {
        echo 0;
        return 1;
    }
    echo 1;
    return 1;
}
if ($op == 'edit_address') {
    $id = $_GPC['id'];
    $sql = ' SELECT address FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
    $address_json = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
    $address = json_decode($address_json, true);
    $item = array();
    foreach ($address as $k => $v) {
        if ($v['id'] == $id) {
            $item = $v;
            break;
        }
    }
    include $this->template('edit_address');
    return 1;
}
if ($op == 'save_address') {
    $id = $_GPC['address_id'];
    $sql = ' SELECT address FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
    $address_json = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
    $address = json_decode($address_json, true);
    if (empty($id)) {
        $new_id = intval(count($address));
        $address[$new_id]['id'] = intval($new_id) + 1;
        $address[$new_id]['username'] = $_GPC['name'];
        $address[$new_id]['phone'] = $_GPC['mobile'];
        $address[$new_id]['province'] = $_GPC['birth']['province'];
        $address[$new_id]['city'] = $_GPC['birth']['city'];
        $address[$new_id]['district'] = $_GPC['birth']['district'];
        $address[$new_id]['address'] = $_GPC['address'];
        $update = array('address' => json_encode($address));
        pdo_update('hetu_seckill_user', $update, array('uniacid' => $this->uniacid, 'openid' => $this->openid));
        header('Location:' . $this->createMobileUrl('Seckill_user', array('op' => 'address')));
        return 1;
    }
    foreach ($address as $k => $v) {
        if ($v['id'] == $id) {
            $address[$k]['username'] = $_GPC['name'];
            $address[$k]['phone'] = $_GPC['mobile'];
            $address[$k]['province'] = $_GPC['birth']['province'];
            $address[$k]['city'] = $_GPC['birth']['city'];
            $address[$k]['district'] = $_GPC['birth']['district'];
            $address[$k]['address'] = $_GPC['address'];
            $update = array('address' => json_encode($address));
            pdo_update('hetu_seckill_user', $update, array('uniacid' => $this->uniacid, 'openid' => $this->openid));
            header('Location:' . $this->createMobileUrl('Seckill_user', array('op' => 'address')));
        }
    }
    return 1;
}
if ($op == 'del_address') {
    $id = $_GPC['id'];
    $sql = ' SELECT address FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
    $address_json = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
    $address = json_decode($address_json, true);
    foreach ($address as $k => $v) {
        if ($v['id'] == $id) {
            $arr_id = intval($id) - 1;
            unset($address[$arr_id]);
        }
    }
    $update = array('address' => json_encode($address));
    $result = pdo_update('hetu_seckill_user', $update, array('uniacid' => $this->uniacid, 'openid' => $this->openid));
    if ($result === false) {
        echo 0;
        return 1;
    }
    echo 1;
    return 1;
}
if ($op == 'confirm') {
    $pass = $_GPC['pass'];
    $orderid = $_GPC['orderid'];
    $sql = ' SELECT g.supplier_pass FROM ' . tablename('hetu_seckill_goods') . ' g LEFT JOIN ' . tablename('hetu_seckill_order') . ' o ON g.id=o.goods_id WHERE o.uniacid=:uniacid AND o.order_id=:order_id ';
    $supplier_pass = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':order_id' => $orderid));
    if (empty($supplier_pass)) {
        echo 2;
        return 1;
    }
    if ($supplier_pass == $pass) {
        pdo_update('hetu_seckill_order', array('status' => 4), array('uniacid' => $this->uniacid, 'order_id' => $orderid));
        echo 1;
        return 1;
    }
    echo 3;
    return 1;
}
if ($op == 'add_address') {
    $orderid = $_GPC['id'];
    $sql = ' SELECT address FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid AND openid=:openid ';
    $add_str = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':openid' => $this->openid));
    $add_arr = explode(' ', $add_str);
    include $this->template('address');
    return 1;
}
if ($op == 'save_add') {
    $orderid = $_GPC['id'];
    $contact_name = $_GPC['contact_name'];
    $contact_phone = $_GPC['contact_phone'];
    $contact_dis = $_GPC['contact_dis'];
    $contact_add = $_GPC['contact_add'];
    $add = $contact_name . ' ' . $contact_phone . ' ' . $contact_dis . ' ' . $contact_add;
    pdo_update('hetu_seckill_user', array('address' => $add), array('uniacid' => $this->uniacid, 'openid' => $this->openid));
    header('Location:' . $this->createMobileUrl('Seckill_goods', array('op' => 'order_queding', 'order_id' => $orderid)));
}