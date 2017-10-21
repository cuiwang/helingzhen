<?php

global $_GPC;
global $_W;
$op = empty($_GPC['op']) ? 'displaying' : $_GPC['op'];
$url = $this->createWebUrl('seckill_stage');
if ($op == 'displaying') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = $this->psize;
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND datetime>=:time ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid, ':time' => strtotime(date('Y-m-d'))));
    $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND datetime>=:time ORDER BY id DESC ';
    $total = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':time' => strtotime(date('Y-m-d'))));
    $pager = pagination($total, $pindex, $psize);
    include $this->template('stage_display');
    return 1;
}
if ($op == 'displayed') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = $this->psize;
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND datetime<:time ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid, ':time' => strtotime(date('Y-m-d'))));
    $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND datetime>:time ORDER BY id DESC ';
    $total = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':time' => strtotime(date('Y-m-d'))));
    $pager = pagination($total, $pindex, $psize);
    include $this->template('stage_display');
    return 1;
}
if ($op == 'post') {
    if ($_POST) {
        $id = $_GPC['id'];
        $data = array('uniacid' => $this->uniacid, 'datetime' => strtotime($_GPC['datetime']), 'timestart' => $_GPC['timestart'], 'timeend' => $_GPC['timeend'], 'goods' => implode(',', $_GPC['goods']), 'status' => $_GPC['status']);
        if ($id) {
            $res = pdo_update('hetu_seckill_stage', $data, array('id' => $id, 'uniacid' => $this->uniacid));
            if ($result === false) {
                message('场次信息更新失败!', referer(), 'error');
                return 1;
            }
            message('场次信息更新成功!', $url, 'success');
            return 1;
        }
        $res = pdo_insert('hetu_seckill_stage', $data);
        if ($res) {
            message('场次信息保存成功!', $url, 'success');
            return 1;
        }
        message('场次信息保存失败!', '', 'error');
        return 1;
    }
    $id = $_GPC['id'];
    if ($id) {
        $sql = ' SELECT * FROM ' . tablename('hetu_seckill_stage') . ' WHERE uniacid=:uniacid AND id=:id ';
        $item = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $id));
    }
    $sql = ' SELECT * FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND status=1 ORDER BY displayorder desc';
    $goods_list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid));
    include $this->template('stage_post');
    return 1;
}
if ($op == 'status') {
    $id = $_GPC['id'];
    $status = $_GPC['status'];
    $data = array('status' => $status);
    $res = pdo_update('hetu_seckill_stage', $data, array('uniacid' => $this->uniacid, 'id' => $id));
    if (!empty($res)) {
        message('更新场次信息成功!', $url, 'success');
        return 1;
    }
    message('更新场次信息失败!', '', 'error');
    return 1;
}
if ($op == 'delete') {
    $id = $_GPC['id'];
    $res = pdo_delete('hetu_seckill_stage', array('uniacid' => $this->uniacid, 'id' => $id));
    if (!empty($res)) {
        message('删除场次信息成功!', $url, 'success');
        return 1;
    }
    message('删除场次信息失败!', '', 'error');
}