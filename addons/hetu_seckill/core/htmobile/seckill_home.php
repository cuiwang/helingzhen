<?php

$stage = empty($_GPC['stage']) ? 0 : $_GPC['stage'];
$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$time = time();
$today = strtotime(date('Y-m-d', time()));
$mody = $today + 24 * 60 * 60;
if ($op == 'display') {
    $sql = ' SELECT id,timestart,timeend FROM ' . tablename($this->stage) . ' WHERE uniacid=:uniacid AND status=1  AND datetime >=:today AND  datetime < :mody  ORDER BY timestart ASC ';
    $stage_list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid, ':today' => $today, ':mody' => $mody));
    $list = array();
    foreach ($stage_list as $k => $v) {
        $timebegin = strtotime($v['timestart']);
        $timeend = strtotime($v['timeend']);
        if ($timestart <= $time) {
            if ($timeend < $time) {
                $v['first_status'] = 2;
            } else {
                $v['first_status'] = 1;
            }
        } else {
            $v['first_status'] = 0;
        }
        if (time() < $timeend) {
            $list[] = $v;
        }
    }
    $temp_key = 0;
    $list = array();
    foreach ($stage_list as $k => $val) {
        $timestart = strtotime($val['timestart']);
        $timeend = strtotime($val['timeend']);
        if ($timestart <= $time) {
            if ($timeend < $time) {
                $val['first_status'] = 2;
            } else {
                $val['first_status'] = 1;
            }
        } else {
            $val['first_status'] = 0;
        }
        $list[$k] = $val;
    }
    if (empty($_GPC['id'])) {
        foreach ($list as $k => $v) {
            if ($v['first_status'] == 1) {
                $id = $v['id'];
                $stage = $k;
                $current_status = 1;
                break;
            }
        }
        if (empty($id)) {
            foreach ($list as $k => $v) {
                if ($v['first_status'] == 0) {
                    $id = $v['id'];
                    $stage = $k;
                    $current_status = 0;
                    break;
                }
            }
        }
    } else {
        $id = $_GPC['id'];
        $stage = empty($_GPC['stage']) ? 0 : $_GPC['stage'];
        $current_status = $list[$stage]['first_status'];
    }
    include $this->template('seckill_home');
    return 1;
}
if ($op == 'show_ajax') {
    $page = $_GPC['page'];
    $id = $_GPC['id'];
    $num = 4;
    $pageStart = $page * $num;
    $sql = ' SELECT goods ,timestart,timeend FROM ' . tablename($this->stage) . ' WHERE uniacid=:uniacid AND id=:id AND status= 1 AND  datetime >=:today AND  datetime < :mody ';
    $goods_list = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $id, ':today' => $today, ':mody' => $mody));
    $goods_id_arr = explode(',', $goods_list['goods']);
    $timestart = strtotime($goods_list['timestart']);
    $timeend = strtotime($goods_list['timeend']);
    if ($timestart <= $time) {
        if ($timeend < $time) {
            $first_status = 2;
        } else {
            $first_status = 1;
        }
    } else {
        $first_status = 0;
    }
    $list = array();
    if ($goods_list) {
        if ($config['wz_show']) {
            if (!$this->get_modules()) {
                $wh = ' AND cardtype_id = 0';
            }
        }
        $k = $pageStart;
        while ($k < $pageStart + $num) {
            if ($k < count($goods_id_arr)) {
                $sql = ' SELECT * FROM ' . tablename($this->goods) . ' WHERE uniacid=:uniacid AND id=:id ' . $wh;
                $goods = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $goods_id_arr[$k]));
                $total = $goods['total'];
                $totalcnf = intval($goods['totalcnf']);
                if ($totalcnf == 2) {
                    $process = 0;
                } else {
                    if ($totalcnf == 0) {
                        $where = ' ';
                    } else {
                        if ($totalcnf == 1) {
                            $where = ' AND status in (1, 2, 3) ';
                        }
                    }
                    $where = ' AND stage_id = ' . $id;
                    $sql = ' SELECT COUNT(*) FROM ' . tablename($this->order) . ' WHERE uniacid=:uniacid AND goods_id=:goods_id ' . $where;
                    $order_num = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':goods_id' => $goods['id'])) + $goods['sales'];
                    if ($order_num == $total) {
                        $process = 100;
                    } else {
                        $process = $order_num / $total * 100;
                    }
                }
                $goods['process'] = intval($process);
                $goods['first_status'] = $first_status;
                $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_remind') . ' WHERE uniacid=:uniacid AND stageid=:stageid AND goodsid=:goodsid AND userid=:userid ';
                $member_info = $this->get_member_info($this->openid, 'openid');
                $userid = $member_info['id'];
                $goods['remind_status'] = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':stageid' => $id, ':goodsid' => $goods['id'], ':userid' => $userid));
                $list[] = $goods;
            }
            ++$k;
        }
    }
    echo json_encode($list);
    return 1;
}
if ($op == 'remind_ajax') {
    global $_GPC;
    global $_W;
    $goodsid = $_GPC['goods_id'];
    $stageid = $_GPC['stage_id'];
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_remind') . ' WHERE uniacid=:uniacid AND userid=:userid AND stageid=:stageid AND goodsid=:goodsid';
    $member_info = $this->get_member_info($this->openid, 'openid');
    $userid = $member_info['id'];
    $remind_info = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':userid' => $userid, ':stageid' => $stageid, ':goodsid' => $goodsid));
    if (!empty($remind_info)) {
        $result = pdo_delete('hetu_seckill_remind', array('id' => $remind_info['id']));
        if (!empty($result)) {
            echo 3;
            return 1;
        }
        echo 4;
        return 1;
    }
    $data = array('uniacid' => $this->uniacid, 'userid' => $userid, 'stageid' => $stageid, 'goodsid' => $goodsid, 'createtime' => time());
    $result = pdo_insert('hetu_seckill_remind', $data);
    if (!empty($result)) {
        echo 1;
        return 1;
    }
    echo 2;
}