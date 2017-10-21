<?php
global $_GPC, $_W;
load()->func('tpl');
$action = 'category';
$title = '商家类别';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    if ($_GPC['type'] == 'default') {
        $parentid = $this->insert_default_category('餐饮', 'canyin');
        $this->insert_default_category('美食', 'ms', '餐饮', 1);
        $parentid = $this->insert_default_category('娱乐', 'yule');
        $this->insert_default_category('KTV', 'ktv', '娱乐', 1);
        $parentid = $this->insert_default_category('购物', 'gouwu');
        $this->insert_default_category('数码电器', 'smdq', '购物', 1);

        $this->insert_default_category('便民服务', 'bianmin');
        $this->insert_default_category('生活服务', 'shenghuo');
        $this->insert_default_category('人物', 'renwu');
        $this->insert_default_category('汽车', 'qiche');

        $parentid = $this->insert_default_category('其他', 'other');
        $this->insert_default_category('微营销', 'wyx', '其他', 1);

        $this->insert_default_category('拍摄', 'paishe');
        $parentid = $this->insert_default_category('美容保健', 'meirong');
        $this->insert_default_category('丰胸', 'fx', '美容保健', 1);

        $this->insert_default_category('旅游', 'lvyou');
        $this->insert_default_category('酒业', 'jiuye');
        $this->insert_default_category('酒店', 'jiudian');

        $parentid = $this->insert_default_category('教育', 'jiaoyu');
        $this->insert_default_category('亲子教育', 'qzjy', '教育', 1);

        $parentid = $this->insert_default_category('婚庆', 'hunqing');
        $this->insert_default_category('婚纱', 'hs', '婚庆', 1);

        $parentid = $this->insert_default_category('房产', 'fangchan');
        $this->insert_default_category('楼盘', 'lp', '房产', 1);
    }

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_category, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
    }

    $children = array();
    $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid DESC, displayorder DESC");
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }
    }
} elseif ($operation == 'post') {
    $parentid = intval($_GPC['parentid']);
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE id = '$id'");
    } else {
        $item = array(
            'displayorder' => 0,
        );
    }

    if (!empty($item)) {
        if (!empty($item['logo'])) {
            $logo = $item['logo'];
        }
    }

    if (!empty($parentid)) {
        $parent = pdo_fetch("SELECT id, name FROM " . tablename($this->table_category) . " WHERE id = '$parentid' ORDER BY displayorder DESC,id DESC");
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
            'logo' => $_GPC['logo'],
            'url' => $_GPC['url'],
            'displayorder' => intval($_GPC['displayorder']),
            'isfirst' => intval($_GPC['isfirst']),
            'parentid' => intval($parentid),
        );

        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update($this->table_category, $data, array('id' => $id));
        } else {
            pdo_insert($this->table_category, $data);
            $id = pdo_insertid();
        }
        message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_category) . " WHERE id = '$id'");
    if (empty($item)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
    }
    pdo_delete($this->table_category, array('id' => $id, 'parentid' => $id), 'OR');
    message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
}
include $this->template('category');