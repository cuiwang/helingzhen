<?php
global $_GPC, $_W;
load()->func('tpl');
$action = 'area';
$title = '区域管理';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_area, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('更新排序成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
    }

    $children = array();
    $area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid DESC, displayorder DESC");
    foreach ($area as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($area[$index]);
        }
    }
} elseif ($operation == 'post') {
    $parentid = intval($_GPC['parentid']);
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_area) . " WHERE id = '$id'");
    } else {
        $item = array(
            'displayorder' => 0,
        );
    }

    if (!empty($item)) {

    }

    if (!empty($parentid)) {
        $parent = pdo_fetch("SELECT id, name FROM " . tablename($this->table_area) . " WHERE id = '$parentid' ORDER BY displayorder DESC,id DESC");
        if (empty($parent)) {
            message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
        }
    }
    if (checksubmit('submit')) {
        if (empty($_GPC['catename'])) {
            message('抱歉，请输入分类名称！');
        }

        $data = array(
            'weid' => $_W['uniacid'],
            'name' => $_GPC['catename'],
            'displayorder' => intval($_GPC['displayorder']),
            'parentid' => intval($parentid),
        );

        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update($this->table_area, $data, array('id' => $id));
        } else {
            pdo_insert($this->table_area, $data);
            $id = pdo_insertid();
        }
        message('更新分类成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_area) . " WHERE id = '$id'");
    if (empty($item)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('area', array('op' => 'display')), 'error');
    }
    pdo_delete($this->table_area, array('id' => $id, 'parentid' => $id), 'OR');
    message('分类删除成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
}
include $this->template('area');