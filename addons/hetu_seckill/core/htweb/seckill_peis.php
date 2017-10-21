<?php

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$url = $this->createWebUrl('seckill_peis');
switch ($op) {
    case 'display':
        $pindex = max(1, intval($_GPC['page']));
        $psize = $this->psize;
        $sql = 'SELECT * FROM ' . tablename($this->peis) . ' WHERE uniacid = :uniacid ORDER BY compositor desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql, array(':uniacid' => $this->uniacid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->peis) . ' WHERE uniacid =' . $this->uniacid);
        $pager = pagination($total, $pindex, $psize);
        include $this->template('peis_display');
        break;
    case 'post':
        if ($_POST) {
            $data = array('uniacid' => $this->uniacid, 'name' => $_GPC['name'], 'status' => $_GPC['status'], 'delivery_fee' => $_GPC['delivery_fee'], 'compositor' => $_GPC['compositor'], 'peis_type' => $_GPC['peis_type']);
            $id = $_GPC['id'];
            $sql = 'SELECT id FROM' . tablename($this->peis) . ' WHERE  uniacid=:uniacid AND name = :name';
            $peis_id = pdo_fetchcolumn($sql, array(':uniacid' => $this->uniacid, ':name' => $data['name']));
            if ($id) {
                if ($peis_id) {
                    if (!$peis_id === $id) {
                        message('该配送方式已存在!', referer(), 'error');
                    }
                }
                $res = pdo_update($this->peis, $data, array('id' => $id, 'uniacid' => $this->uniacid));
                if ($result === false) {
                    message('配送方式更新失败!', referer(), 'error');
                } else {
                    message('配送方式更新成功!', $url, 'success');
                }
            }
            if ($peis_id) {
                message('该配送方式已存在!', referer(), 'error');
            } else {
                if (pdo_insert($this->peis, $data)) {
                    message('配送方式保存成功!', $url, 'success');
                } else {
                    message('配送方式保存失败', referer(), 'error');
                }
            }
        } else {
            $id = $_GPC['id'];
            if ($id) {
                $sql = 'SELECT * FROM' . tablename($this->peis) . ' WHERE  uniacid=:uniacid AND id= :id';
                $item = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $id));
                if (empty($item)) {
                    message('该信息不存在或已删除', referer(), 'error');
                }
            }
            include $this->template('peis_post');
        }
        break;
    case 'delete':
        $id = $_GPC['id'];
        $res = pdo_delete($this->peis, array('uniacid' => $this->uniacid, 'id' => $id));
        if (!empty($res)) {
            message('删除配送信息成功!', $url, 'success');
        } else {
            message('删除配送信息失败!', '', 'error');
        }
        break;
    case 'status':
        $id = $_GPC['id'];
        $status = $_GPC['status'];
        $data = array('status' => $status);
        $res = pdo_update($this->peis, $data, array('uniacid' => $this->uniacid, 'id' => $id));
        if (!empty($res)) {
            message('操作成功!', $url, 'success');
        } else {
            message('操作失败!', '', 'error');
        }
        break;
}