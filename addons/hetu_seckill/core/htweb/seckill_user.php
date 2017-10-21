<?php

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$url = $this->createWebUrl('seckill_user');
if ($op == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = $this->psize;
    $member_name = $_GPC['member_name'];
    $phone = $_GPC['phone'];
    $where = '';
    if (!empty($member_name)) {
        $str_name = preg_replace('/([\\x{4e00}-\\x{9fa5}])/u', '$1%', $member_name);
        $where .= ' AND (nickname LIKE \'%' . $str_name . '%\' OR realname LIKE \'%' . $str_name . '%\') ';
    }
    if (!empty($phone)) {
        $str_name = preg_replace('/([\\x{4e00}-\\x{9fa5}])/u', '$1%', $phone);
        $where .= ' AND phone like \'%' . $str_name . '%\' ';
    }
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid ' . $where . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid));
    foreach ($list as $k => $v) {
        $add_str = $v['address'];
        if (empty($add_str)) {
            $list[$k]['default_add'] = '';
        } else {
            $add_arr = json_decode($add_str, true);
            foreach ($add_arr as $key => $value) {
                if ($value['status'] == 1) {
                    $list[$k]['default_add'] = $value;
                }
            }
        }
        $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_order') . ' WHERE uniacid=:uniacid AND member=:member ';
        $order_num = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':member' => $v['id']));
        $list[$k]['order_num'] = $order_num;
    }
    $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_user') . ' WHERE uniacid=:uniacid ' . $where . ' ORDER BY id DESC ';
    $total = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid));
    $pager = pagination($total, $pindex, $psize);
    include $this->template('user_display');
    return 1;
}
if ($op == 'status') {
    $id = $_GPC['id'];
    $status = $_GPC['status'];
    $data = array('status' => $status);
    $res = pdo_update('hetu_seckill_user', $data, array('uniacid' => $this->uniacid, 'id' => $id));
    if (!empty($res)) {
        message('改变会员状态成功!', $url, 'success');
        return 1;
    }
    message('改变会员状态失败!', '', 'error');
}