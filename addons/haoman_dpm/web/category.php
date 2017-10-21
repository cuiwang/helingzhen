<?php
    global $_GPC, $_W;

    load()->func('tpl');
    $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
     $rid = $_GPC['rid'];
    if ($operation == 'display') {
        if (!empty($_GPC['bianhao'])) {

            foreach ($_GPC['bianhao'] as $id => $bianhao) {
                pdo_update('haoman_dpm_shop_category', array('bianhao' => $bianhao), array('id' => $id));
            }
            message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display','rid'=>$rid)), 'success');
        }

        $category = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_shop_category') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY parentid ASC, bianhao DESC");


        include $this->template('category');
    } elseif ($operation == 'post') {
            $parentid = intval($_GPC['parentid']);
            $id = intval($_GPC['id']);
            if (!empty($id)) {
            $category = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_shop_category') . " WHERE id = '$id'");
            } else {
            $category = array(
            'displayorder' => 0,
            );
            }

            if (checksubmit('submit')) {
            if (empty($_GPC['catename'])) {
            message('抱歉，请输入分类名称！');
            }
            $data = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['catename'],
            'enabled' => $_GPC['enabled'],
            );
            if (!empty($id)) {
            pdo_update('haoman_dpm_shop_category', $data, array('id' => $id));

            } else {
            pdo_insert('haoman_dpm_shop_category', $data);
            $id = pdo_insertid();
            }
            message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display','rid'=>$rid)), 'success');
            }
            include $this->template('category');
    } elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $category = pdo_fetch("SELECT id FROM " . tablename('haoman_dpm_shop_category') . " WHERE id = '$id'");
    if (empty($category)) {
    message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display','rid'=>$rid)), 'error');
    }
    pdo_delete('haoman_dpm_shop_category', array('id' => $id, 'parentid' => $id), 'OR');
    message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display','rid'=>$rid)), 'success');
    }
