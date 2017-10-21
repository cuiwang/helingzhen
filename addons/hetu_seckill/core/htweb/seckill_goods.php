<?php

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$url = $this->createWebUrl('seckill_goods');
switch ($op) {
    case 'display':
        $pindex = max(1, intval($_GPC['page']));
        $psize = $this->psize;
        $where = ' uniacid =' . $this->uniacid;
        $params = array();
        $keyword = $_GPC['keyword'];
        if ($keyword) {
            $where .= ' AND name like \'%' . $keyword . '%\' ';
        }
        $status = isset($_GET['status']) ? $_GET['status'] : 2;
        if ($status == 1) {
            $where .= ' AND status =:status ';
            $params[':status'] = $status;
        } else {
            if (empty($status)) {
                $where .= ' AND status =:status ';
                $params[':status'] = 0;
            }
        }
        $sql = ' SELECT * FROM ' . tablename('hetu_seckill_goods') . ' WHERE ' . $where . ' ORDER BY displayorder DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql, $params);
        foreach ($list as $k => $v) {
            $sql = ' SELECT goods_number FROM ' . tablename('hetu_seckill_order') . ' WHERE uniacid=:uniacid AND goods_id=:goods_id ';
            $goods_order_number = pdo_fetchall($sql, array(':uniacid' => $this->uniacid, ':goods_id' => $v['id']));
            if (empty($goods_order_number)) {
                $list[$k]['order_number'] = $v['total'];
            } else {
                $goods_number = 0;
                foreach ($goods_order_number as $key => $value) {
                    $goods_number = $goods_number + $value['goods_number'];
                }
                $list[$k]['order_number'] = $v['total'] - $goods_number;
            }
        }
        $sql = ' SELECT COUNT(*) FROM ' . tablename('hetu_seckill_goods') . ' WHERE ' . $where . ' ';
        $total = pdo_fetchcolumn($sql, $params);
        $pager = pagination($total, $pindex, $psize);
        include $this->template('goods_display');
        break;
    case 'post':
        if ($_POST) {
            $id = $_GPC['id'];
            $data = array('uniacid' => $this->uniacid, 'cardtype_id' => intval($_GPC['cardtype_id']), 'name' => $_GPC['name'], 'unit' => $_GPC['unit'], 'thumb' => $_GPC['thumb'], 'sn' => $_GPC['sn'], 'barcode' => $_GPC['barcode'], 'marketprice' => $_GPC['marketprice'], 'seksillprice' => $_GPC['seksillprice'], 'total' => $_GPC['total'], 'totalcnf' => $_GPC['totalcnf'], 'maxbuy' => $_GPC['maxbuy'], 'usermaxbuy' => $_GPC['usermaxbuy'], 'sales' => $_GPC['sales'], 'credit' => $_GPC['credit'], 'content' => htmlspecialchars_decode($_GPC['content']), 'status' => $_GPC['status'], 'displayorder' => $_GPC['displayorder'], 'supplier' => $_GPC['supplier'], 'supplier_pass' => $_GPC['supplier_pass'], 'since_address' => $_GPC['since_address']);
            $specifications_array = $_GPC['specifications'];
            if ($specifications_array) {
                $specifications = array();
                foreach ($specifications_array as $key => $val) {
                    if ($val != '') {
                        $specifications[] = array('value' => $val);
                    }
                }
                $data['specifications'] = empty($specifications) ? '' : iserializer($specifications);
            }
            if ($id) {
                $res = pdo_update('hetu_seckill_goods', $data, array('id' => $id, 'uniacid' => $this->uniacid));
                if ($result === false) {
                    message('商品信息更新失败!', referer(), 'error');
                } else {
                    message('商品信息更新成功!', $url, 'success');
                }
            } else {
                $res = pdo_insert('hetu_seckill_goods', $data);
                if ($res) {
                    message('商品信息保存成功!', $url, 'success');
                } else {
                    message('商品信息保存失败!', '', 'error');
                }
            }
        } else {
            $id = $_GPC['id'];
            if ($id) {
                $sql = ' SELECT * FROM ' . tablename('hetu_seckill_goods') . ' WHERE uniacid=:uniacid AND id=:id ';
                $item = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':id' => $id));
            }
            if ($item['specifications']) {
                $specifications = iunserializer($item['specifications']);
            }
            if ($config['wz_show']) {
                $cardtype_sql = ' SELECT cardtype_id,type FROM ' . tablename($this->cardtype) . ' WHERE uniacid=:uniacid AND status=1 ORDER BY sequence DESC ';
                $cardtype_list = pdo_fetchall($cardtype_sql, array(':uniacid' => $this->uniacid));
            }
            include $this->template('goods_post');
        }
        break;
    case 'delete':
        $id = $_GPC['id'];
        $res = pdo_delete('hetu_seckill_goods', array('uniacid' => $this->uniacid, 'id' => $id));
        if (!empty($res)) {
            message('删除商品信息成功!', $url, 'success');
        } else {
            message('删除商品信息失败!', '', 'error');
        }
        break;
    case 'status':
        $id = $_GPC['id'];
        $status = $_GPC['status'];
        $data = array('status' => $status);
        $res = pdo_update('hetu_seckill_goods', $data, array('uniacid' => $this->uniacid, 'id' => $id));
        if (!empty($res)) {
            message('更新商品信息成功!', $url, 'success');
        } else {
            message('更新商品信息失败!', '', 'error');
        }
        break;
    case 'cardtype_ajax':
        $cardtype_id = intval($_GPC['cardtype_id']);
        $sql = 'SELECT type,price FROM ' . tablename($this->cardtype) . ' WHERE uniacid= :uniacid AND status= 1 AND cardtype_id = :cardtype_id';
        $item = pdo_fetch($sql, array(':uniacid' => $this->uniacid, ':cardtype_id' => $cardtype_id));
        if (empty($item)) {
            $item['sm'] = '0';
        } else {
            $item['sm'] = '1';
        }
        echo json_encode($item);
        break;
}