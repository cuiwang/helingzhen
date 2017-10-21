<?php

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$url = $this->createWebUrl('seckill_order');
$time = time();
if ($op == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = $this->psize;
    $seckilldate = $_GPC['seckilldate'];
    if (empty($createtime)) {
        $createtime['start'] = date('Y-m-d', TIMESTAMP - 86400);
        $createtime['end'] = date('Y-m-d');
    }
    $seckilldate = empty($_GPC['seckilldate']) ? date('Y-m-d') : $_GPC['seckilldate'];
    $btime = strtotime($seckilldate);
    $etime = $btime + 86400;
    $member = $_GPC['member'];
    $where = ' WHERE uniacid=:uniacid ';
    $params = array(':uniacid' => $this->uniacid);
    if ($member) {
        $where .= ' AND member= :member';
        $params[':member'] = $member;
    } else {
        $where .= ' AND order_time>= :seckilldate AND order_time <= :etime';
        $params[':seckilldate'] = $btime;
        $params[':etime'] = $etime;
    }
    $stage_id = $_GPC['stage_id'];
    if ($stage_id) {
        $where .= ' AND stage_id= :stage_id';
        $params[':stage_id'] = $_GPC['stage_id'];
    }
    $order_status = $_GPC['order_status'];
    if ($order_status) {
        $where .= ' AND status= :status';
        $params[':status'] = $order_status;
    }
    $order_no = $_GPC['order_no'];
    if ($order_no) {
        $str_name = preg_replace('/([\\x{4e00}-\\x{9fa5}])/u', '$1%', $order_no);
        $where .= ' AND order_no like \'%:order_no%\' ';
        $params[':order_no'] = $str_name;
    }
    $goods_id = $_GPC['goods_id'];
    if ($goods_id) {
        $where .= ' AND goods_id = :goods_id';
        $params[':goods_id'] = $goods_id;
    }
    $sql = ' SELECT * FROM ' . tablename($this->order) . $where . ' ORDER BY order_time DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn(' SELECT COUNT(*) FROM ' . tablename($this->order) . $where, $params);
    $pager = pagination($total, $pindex, $psize);
    foreach ($list as $k => $v) {
        $member_info = $this->get_member_info($v['member'], 'id');
        $list[$k]['member_info'] = $member_info;
    }
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND status=1 AND datetime=:datetime ORDER BY datetime DESC ';
    $stage_list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid, ':datetime' => strtotime($seckilldate)));
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND status=1 ORDER BY displayorder desc';
    $goods_list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid));
    include $this->template('order_display');
    return 1;
}
if ($op == 'del') {
    $order_id = $_GPC['order_id'];
    $res = pdo_delete($this->order, array('uniacid' => $this->uniacid, 'order_id' => $order_id));
    if (!empty($res)) {
        message('删除订单信息成功!', $url, 'success');
        return 1;
    }
    message('删除订单信息失败!', '', 'error');
    return 1;
}
if ($op == 'post') {
    $order_id = $_GPC['order_id'];
    $sql = ' SELECT * FROM' . tablename($this->order) . ' WHERE uniacid=:uniacid AND order_id = :order_id';
    $item = pdo_fetch($sql, array('uniacid' => $this->uniacid, 'order_id' => $order_id));
    include $this->template('order_post');
    return 1;
}
if ($op == 'order_fahuo') {
    $order_id = $_GPC['order_id'];
    if ($_POST) {
        $data = array('status' => 3, 'kd_no' => $_GPC['kd_no'], 'delivery_time' => $time);
        $res = pdo_update($this->order, $data, array('order_id' => $order_id, 'uniacid' => $this->uniacid));
        if ($res === false) {
            message('订单发货失败!', referer(), 'error');
            return 1;
        }
        $this->send_temp_info($order_id);
        message('订单发货成功!', $url, 'success');
        return 1;
    }
    include $this->template('order_fahuo');
    return 1;
}
if ($op == 'date_ajax') {
    $seckilldate = $_GPC['seckilldate'];
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND status=1 AND datetime=:datetime ORDER BY datetime DESC ';
    $stage_list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid, ':datetime' => strtotime($seckilldate)));
    $html = '<option value="0">全部场次</option>';
    foreach ($stage_list as $k => $v) {
        $html .= '<option value="' . $v['id'] . '">' . $v['timestart'] . ' 至 ' . $v['timeend'] . '</option>';
    }
    die(json_encode($html));
    return 1;
}
if ($op == 'exportinfo') {
    $seckilldate = $_GPC['seckilldate'];
    if (empty($createtime)) {
        $createtime['start'] = date('Y-m-d', TIMESTAMP - 86400);
        $createtime['end'] = date('Y-m-d');
    }
    $seckilldate = empty($_GPC['seckilldate']) ? date('Y-m-d') : $_GPC['seckilldate'];
    $btime = strtotime($seckilldate);
    $etime = $btime + 86400;
    $member = $_GPC['member'];
    $where = ' WHERE uniacid=:uniacid ';
    $params = array(':uniacid' => $this->uniacid);
    if ($member) {
        $where .= ' AND member= :member';
        $params[':member'] = $member;
    } else {
        $where .= ' AND order_time>= :seckilldate AND order_time <= :etime';
        $params[':seckilldate'] = $btime;
        $params[':etime'] = $etime;
    }
    $stage_id = $_GPC['stageid'];
    if ($stage_id) {
        $where .= ' AND stage_id= :stage_id';
        $params[':stage_id'] = $_GPC['stageid'];
    }
    $order_status = $_GPC['order_status'];
    if ($order_status) {
        $where .= ' AND status= :status';
        $params[':status'] = $order_status;
    }
    $order_no = $_GPC['order_no'];
    if ($order_no) {
        $str_name = preg_replace('/([\\x{4e00}-\\x{9fa5}])/u', '$1%', $order_no);
        $where .= ' AND order_no like \'%:order_no%\' ';
        $params[':order_no'] = $str_name;
    }
    $goods_id = $_GPC['goods_id'];
    if (goods_id) {
        $where .= ' AND goods_id= :goods_id';
        $params[':goods_id'] = $goods_id;
    }
    $sql = ' SELECT * FROM ' . tablename($this->order) . $where . ' ORDER BY order_time DESC ';
    $list = pdo_fetchall($sql, $params);
    $data = array();
    foreach ($list as $k => $val) {
        $data[$k]['order_id'] = $val['order_id'];
        $sql = ' select datetime,timestart,timeend from ' . tablename($this->stage) . ' where uniacid=:uniacid and id= :id';
        $stage = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $val['stage_id']));
        $data[$k]['stage'] = date('Y-m-d ', $stage['datetime']) . $stage['timestart'] . '-' . $stage['timeend'];
        $data[$k]['uniacid'] = $val['uniacid'];
        $data[$k]['order_no'] = $val['order_no'];
        $user_list = $this->get_member_info($val['member'], 'id');
        $data[$k]['nickname'] = $user_list['nickname'];
        $data[$k]['goods_name'] = $this->get_goods_name($val['goods_id']);
        $data[$k]['goods_nature'] = $val['goods_nature'];
        $data[$k]['goods_number'] = $val['goods_number'];
        $data[$k]['goods_seksillprice'] = $val['goods_seksillprice'];
        $data[$k]['order_yunfei'] = $val['order_yunfei'];
        $data[$k]['order_totalprice'] = $val['order_totalprice'];
        $data[$k]['address'] = $val['address'];
        if (1 < $val['status']) {
            if ($val['peis']) {
                $data[$k]['peis'] = $this->get_peis_name($val['peis']);
            } else {
                $data[$k]['peis'] = '自提';
            }
        } else {
            $data[$k]['peis'] = '未支付';
        }
        if ($val['kd_no']) {
            $data[$k]['kd_no'] = $val['kd_no'];
        } else {
            $data[$k]['kd_no'] = '无';
        }
        $data[$k]['order_time'] = date('Y-m-d H:i:s', $val['order_time']);
        if ($val['delivery_time']) {
            $data[$k]['delivery_time'] = date('Y-m-d H:i:s', $val['delivery_time']);
        } else {
            $data[$k]['delivery_time'] = '未发货';
        }
        if ($val['sign_ttime']) {
            $data[$k]['sign_ttime'] = date('Y-m-d H:i:s', $val['sign_ttime']);
        } else {
            $data[$k]['sign_ttime'] = '未签收';
        }
        if ($val['qx_ttime']) {
            $data[$k]['qx_ttime'] = date('Y-m-d H:i:s', $val['qx_ttime']);
        } else {
            $data[$k]['qx_ttime'] = '未取消';
        }
        if ($val['status'] == 1) {
            $data[$k]['status'] = '未支付';
        } else {
            if ($val['status'] == 2) {
                $data[$k]['status'] = '已支付';
            } else {
                if ($val['status'] == 3) {
                    $data[$k]['status'] = '已签收';
                } else {
                    if ($val['status'] == 4) {
                        $data[$k]['status'] = '已取消';
                    }
                }
            }
        }
    }
    $title = array('订单编号', '秒杀日期时段', '公众号id', '订单号', '抢购人微信昵称', '商品名称', '商品属性', '商品数量', '商品秒杀价', '订单运费', '订单总价格', '收货地址', '配送方式', '快递单号', '下单时间', '发货时间', '签收时间', '取消时间', '订单状态');
    $this->exportexcel($data, $title, '订单导出');
}