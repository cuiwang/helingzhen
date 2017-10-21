<?php
global $_W, $_GPC;
$modulename = $this->modulename;
$action = 'feedback';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$storeid = intval($_GPC['storeid']);

if ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_feedback) . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            message('抱歉，数据不存在或是已经删除！！', '', 'error');
        }
    } else {
        $item = array(
            'dateline' => TIMESTAMP,
            'status' => 1,
        );
    }

    if (checksubmit('submit')) {
        $data = array(
            'weid' => intval($_W['uniacid']),
            'storeid' => $storeid,
            'nickname' => trim($_GPC['nickname']),
            'content' => trim($_GPC['content']),
            'top' => intval($_GPC['top']),
            'status' => intval($_GPC['status']),
            'displayorder' => intval($_GPC['displayorder']),
            'dateline' => TIMESTAMP,
        );

        if (empty($data['nickname'])) {
            message('请输入昵称！');
        }

        if (empty($storeid)) {
            unset($data['storeid']);
        }

        if (empty($id)) {
            pdo_insert($this->table_feedback, $data);
        } else {
            unset($data['dateline']);
            pdo_update($this->table_feedback, $data, array('id' => $id));
        }
        message('数据更新成功！', $this->createWebUrl('feedback', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }
} elseif ($operation == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_feedback, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('排序更新成功！', $this->createWebUrl('feedback', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $condition = " WHERE weid = '{$_W['uniacid']}' ";

    if (!empty($storeid)) {
        $condition .= "  AND storeid={$storeid} ";
    }

    if (isset($_GPC['status'])) {
        $condition .= " AND status = '" . intval($_GPC['status']) . "'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_feedback) . " $condition ORDER BY status , id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    foreach($list as $key => $value) {
        $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id=:id LIMIT 1", array
        (':id' => $value['storeid'] ));
        $list[$key]['shopname'] = $store['title'];
    }

    if (!empty($list)) {
        $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_feedback) . " $condition");
        $pager = pagination($total, $pindex, $psize);
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT * FROM " . tablename($this->table_feedback) . " WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，数据不存在或是已经被删除！');
    }
    pdo_delete($this->table_feedback, array('id' => $id));
    message('删除成功！', referer(), 'success');

} elseif ($operation == 'check') { //审核
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_feedback, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('排序更新成功！', $this->createWebUrl('feedback', array('op' => 'display', 'storeid' => $storeid)), 'success');
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

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_feedback) . " WHERE weid = '{$_W['uniacid']}' AND mode=1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_feedback) . " WHERE weid = '{$_W['uniacid']}' $condition");

    $pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'deleteall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $feedback = pdo_fetch("SELECT * FROM " . tablename($this->table_feedback) . " WHERE id = :id", array(':id' => $id));
            if (empty($feedback)) {
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_feedback, array('id' => $id, 'weid' => $_W['uniacid']));
            $rowcount++;
        }
    }
    $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
} elseif ($operation == 'checkall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $feedback = pdo_fetch("SELECT * FROM " . tablename($this->table_feedback) . " WHERE id = :id", array(':id' => $id));
            if (empty($feedback)) {
                $notrowcount++;
                continue;
            }

            $data = empty($feedback['status']) ? 1 : 0;
            pdo_update($this->table_feedback, array('status' => $data), array("id" => $id, "weid" => $_W['uniacid']));
            $rowcount++;
        }
    }
    $this->message("操作成功！共审核{$rowcount}条数据,{$notrowcount}条数据不能删除!!", '', 0);
}
include $this->template('feedback');