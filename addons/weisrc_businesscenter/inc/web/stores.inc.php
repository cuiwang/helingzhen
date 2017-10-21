<?php
global $_W, $_GPC;
load()->func('tpl');
$modulename = $this->modulename;
$action = 'stores';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$children = array();
$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC", array(), 'id');
if (!empty($category)) {
    $children = array();
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
        }
    }
} else {
    message('请先添加分类！', $this->createWebUrl('category', array('op' => 'post')), 'success');
}
$areas = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC", array(), 'id');

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            $item['vip_start'] = TIMESTAMP;
            $item['vip_end'] = TIMESTAMP;
            message('抱歉，商家不存在或是已经删除！', '', 'error');
        } else {
            if (empty($item['vip_start'])) {
                $item['vip_start'] = TIMESTAMP;
            }
            if (empty($item['vip_end'])) {
                $item['vip_end'] = TIMESTAMP;
            }
        }
    } else {
        $item['vip_start'] = TIMESTAMP;
        $item['vip_end'] = TIMESTAMP;
    }

    if (!empty($item)) {
        $logo = tomedia($item['logo']);
        $qrcode = tomedia($item['qrcode']);
    }

    if (checksubmit('submit')) {
        $data = array(
            'weid' => intval($_W['uniacid']),
            //'cityid' => intval($_GPC['cityid']),
            'displayorder' => intval($_GPC['displayorder']),
            'title' => trim($_GPC['title']),
            'description' => trim($_GPC['description']),
            'content' => trim($_GPC['content']),
            'pcate' => intval($_GPC['pcate']),
            'aid' => intval($_GPC['aid']),
            'ccate' => intval($_GPC['ccate']),
            'hours' => trim($_GPC['hours']),
            'starttime' => $_GPC['starttime'],
            'endtime' => $_GPC['endtime'],
            'services' => trim($_GPC['services']),
            'discount' => trim($_GPC['discount']),
            'discounts' => trim($_GPC['discounts']),
            'qrcode_url' => trim($_GPC['qrcode_url']),
            'qrcode_description' => trim($_GPC['qrcode_description']),
            'consume' => trim($_GPC['consume']),
            'level' => intval($_GPC['level']),
            'tel' => trim($_GPC['tel']),
            'address' => trim($_GPC['address']),
            'discount_url' => trim($_GPC['discount_url']),
            'location_p' => trim($_GPC['location_p']),
            'location_c' => trim($_GPC['location_c']),
            'location_a' => trim($_GPC['location_a']),
            'place' => trim($_GPC['place']),
            'lng' => trim($_GPC['baidumap']['lng']),
            'lat' => trim($_GPC['baidumap']['lat']),
            'status' => intval($_GPC['status']),
            'isfirst' => intval($_GPC['isfirst']),
            'top' => intval($_GPC['top']),
            'dateline' => TIMESTAMP,
            'shop_url' => trim($_GPC['shop_url']),
            'site_name' => trim($_GPC['site_name']),
            'site_url' => trim($_GPC['site_url']),
            'shop_name' => trim($_GPC['shop_name']),
            'time_enable1' => intval($_GPC['time_enable1']),
            'time_enable2' => intval($_GPC['time_enable2']),
            'time_enable3' => intval($_GPC['time_enable3']),
            'starttime2' => $_GPC['starttime2'],
            'endtime2' => $_GPC['endtime2'],
            'starttime3' => $_GPC['starttime3'],
            'endtime3' => $_GPC['endtime3'],
            'share_title' => $_GPC['share_title'],
            'share_desc' => $_GPC['share_desc'],
            'share_cancel' => $_GPC['share_cancel'],
            'share_url' => $_GPC['share_url'],
            'isvip' => intval($_GPC['isvip']),
            'vip_start' => strtotime($_GPC['datelimit']['start']),
            'vip_end' => strtotime($_GPC['datelimit']['end']),
            'follow_url' => $_GPC['follow_url']
        );

        if (!empty($_GPC['logo'])) {
            $data['logo'] = $_GPC['logo'];
        }

        $data['qrcode'] = $_GPC['qrcode'];

        if (empty($data['title'])) {
            message('请输入商家名称！');
        }
        if (empty($data['pcate'])) {
            message('请选择商家分类！');
        }
        if (!$this->checkDatetime($data['starttime'])) {
            message('请输入正确的时间格式！');
        }
        if (!$this->checkDatetime($data['endtime'])) {
            message('请输入正确的时间格式！');
        }

        if (empty($id)) {
            pdo_insert($this->table_stores, $data);
        } else {
            unset($data['dateline']);
            pdo_update($this->table_stores, $data, array('id' => $id));
        }
        message('数据更新成功！', $this->createWebUrl('stores', array('op' => 'display')), 'success');
    }
} elseif ($operation == 'display') {
    $type = intval($_GPC['type']);
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_stores, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('排序更新成功！', $this->createWebUrl('stores', array('op' => 'display')), 'success');
    }

    $check_shop_count1 = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = :weid AND mode=1", array(':weid' => $this->_weid));
    $check_shop_count2 = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = :weid AND mode=1 AND checked=0", array(':weid' => $this->_weid));
    $check_feedback_count = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_feedback) . " WHERE weid = :weid AND status=0", array(':weid' => $this->_weid));
    $totalcount = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = :weid ", array(':weid' => $this->_weid));
    $endcount = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = :weid AND isvip = 1  AND to_days(from_UNIXTIME(`vip_end`,'%Y-%m-%d %H:%i:%S'))<to_days(now())  ", array(':weid' => $this->_weid));

    $news = pdo_fetchall("SELECT storeid,COUNT(1) as count FROM " . tablename($this->modulename . '_news') . "  GROUP BY storeid,weid having weid = :weid", array(':weid' => $_W['uniacid']), 'storeid');
    $feedback = pdo_fetchall("SELECT storeid,COUNT(1) as count FROM " . tablename($this->table_feedback) . "  GROUP BY storeid,weid having weid = :weid", array(':weid' => $_W['uniacid']), 'storeid');

    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $condition = '';
    if (!empty($_GPC['keyword'])) {
        $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
    }

    if (!empty($_GPC['pcate'])) {
        $pcateid = intval($_GPC['pcate']);
        $condition .= " AND pcate = '{$pcateid}'";
    }
    if (!empty($_GPC['ccate'])) {
        $ccateid = intval($_GPC['ccate']);
        $condition .= " AND ccate = '{$ccateid}'";
    }

    if (isset($_GPC['status'])) {
        $condition .= " AND status = '" . intval($_GPC['status']) . "'";
    }

    if ($type == 1) {
        $condition .= " AND isfirst = 1 ";
    } else if ($type == 2) {
        $condition .= " AND top = 1 ";
    } else if ($type == 3) {
        $condition .= " AND isvip = 1  AND to_days(from_UNIXTIME(`vip_end`,'%Y-%m-%d %H:%i:%S'))<to_days(now()) ";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = '{$_W['uniacid']}' $condition ORDER BY status DESC, displayorder DESC, isfirst DESC, top DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    if (!empty($list)) {
        $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = '{$_W['uniacid']}' $condition");
        $pager = pagination($total, $pindex, $psize);
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，数据不存在或是已经被删除！');
    }

    pdo_delete($this->table_stores, array('id' => $id));
    message('删除成功！', $this->createWebUrl('stores', array('op' => 'display')), 'success');
} elseif ($operation == 'check') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_stores, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('排序更新成功！', $this->createWebUrl('stores', array('op' => 'display')), 'success');
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $condition = '';
    if (!empty($_GPC['keyword'])) {
        $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
    }

    if (!empty($_GPC['category_id'])) {
        $cid = intval($_GPC['category_id']);
        $condition .= " AND pcate = '{$cid}'";
    }

    if (isset($_GPC['status'])) {
        $condition .= " AND status = '" . intval($_GPC['status']) . "'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = '{$_W['uniacid']}' AND mode=1 $condition ORDER BY checked, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    if (!empty($list)) {
        $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = '{$_W['uniacid']}' AND mode=1 $condition");
        $pager = pagination($total, $pindex, $psize);
    }
} else if ($operation == 'checkdetail') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            message('抱歉，商家不存在或是已经删除！', '', 'error');
        }
    }
    if (checksubmit('submit')) {
        $data = array(
            'checked' => intval($_GPC['checked']),
            'status' => intval($_GPC['status']),
        );
        pdo_update($this->table_stores, $data, array('id' => $id));
        message('数据更新成功！', $this->createWebUrl('stores', array('op' => 'check')), 'success');
    }
}
include $this->template('stores');