<?php
global $_W  ,$_GPC;

    load()->func('tpl');

    $sql = 'SELECT * FROM ' . tablename('haoman_dpm_shop_category') . ' WHERE `uniacid` = :uniacid ORDER BY `parentid`, `displayorder` DESC';

    $category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']), 'id');

    $rid =$_GPC['rid'];
    $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

    if ($operation == 'post') {
        $id = intval($_GPC['id']);


        //修改进入
        if (!empty($id)) {
            $item = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_shop_goods') . " WHERE id = :id", array(':id' => $id));
            if (empty($item)) {
                message('抱歉，商品不存在或是已经删除！', '', 'error');
            }
            $categoryss = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_shop_category') . " WHERE uniacid = :uniacid and id = :id", array(':uniacid' => $_W['uniacid'],':id'=>$item['categoryid']));

        }

        if (empty($category)) {
            message('抱歉，请您先添加商品分类！', $this->createWebUrl('category', array('op' => 'post','rid'=>$rid)), 'error');
        }

        if (checksubmit('submit')) {
            ini_set('max_execution_time', '0');
            if (empty($_GPC['goodsname'])) {
                message('请输入商品名称！');
            }

            $goodssn =empty($_GPC['goodssn'])?$_W['uniacid'].sprintf('%d', time()).$_GPC['category']:$_GPC['goodssn'];

            $data = array(
                'rid' => intval($rid),
                'uniacid' => intval($_W['uniacid']),
                'title' => $_GPC['goodsname'],
                'categoryid' => intval($_GPC['category']),
                'thumb'=>$_GPC['thumb'],
                'goodssn'=>$goodssn,
                'originalprice'=>$_GPC['originalprice'],
                'productprice'=>$_GPC['productprice'],
                'stock'=>$_GPC['stock'],
                'company'=>$_GPC['company'],
                'give_type'=>$_GPC['give_type'],
                'give_note'=>$_GPC['give_note'],
                'seng_money' => $_GPC['seng_money'],
                'full_money' => $_GPC['full_money'],
                'deleted' => 0,
                'status' => 1,
                'createtime' => time(),
            );

            if (empty($id)) {
                pdo_insert('haoman_dpm_shop_goods', $data);
                $id = pdo_insertid();
            } else {
                unset($data['createtime']);

                pdo_update('haoman_dpm_shop_goods', $data, array('id' => $id));
            }

            message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'display', 'rid' => $rid)), 'success');
        }
    } elseif ($operation == 'display') {
        $pindex = max(1, intval($_GPC['page']));
        $psize = 15;
        $condition = ' WHERE rid = :rid and `uniacid` = :uniacid and deleted = :deleted';
        $params = array(':rid'=>$rid,':uniacid' => $_W['uniacid'],':deleted'=>0);
        if (!empty($_GPC['keyword'])) {
            $condition .= ' AND `title` LIKE :title';
            $params[':title'] = '%' . trim($_GPC['keyword']) . '%';
        }

        if (!empty($_GPC['category'])) {
            $condition .= ' AND `categoryid` = :categoryid';
            $params[':categoryid'] = intval($_GPC['category']);
        }
        if (isset($_GPC['status'])) {
            $condition .= ' AND `status` = :status';
            $params[':status'] = intval($_GPC['status']);
        }
        $sql = 'SELECT COUNT(*) FROM ' . tablename('haoman_dpm_shop_goods') . $condition;
        $total = pdo_fetchcolumn($sql, $params);

        if (!empty($total)) {
            $sql = 'SELECT * FROM ' . tablename('haoman_dpm_shop_goods') . $condition . ' ORDER BY `id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
            $list = pdo_fetchall($sql, $params);
            $pager = pagination($total, $pindex, $psize);
        }
        foreach ($list as &$v){
            $v['categoryid']  = pdo_fetchcolumn('SELECT name FROM ' . tablename('haoman_dpm_shop_category') . ' WHERE `uniacid` = :uniacid and rid =:rid and id =:id',array(':uniacid'=>$_W['uniacid'],':rid'=>$rid,':id'=>$v['categoryid'])) ;
        }
        unset($v);

    } elseif ($operation == 'delete') {
        $id = intval($_GPC['id']);

        $row = pdo_fetch("SELECT id, goodssn FROM " . tablename('haoman_dpm_shop_goods') . " WHERE id = :id", array(':id' => $id));
        if (empty($row)) {
            message('抱歉，商品不存在或是已经被删除！');
        }

        //修改成不直接删除，而设置deleted=1

        pdo_update("haoman_dpm_shop_goods", array("deleted" => 1), array('id' => $id));
        message('删除成功！', referer(), 'success');
    }
    include $this->template('goods');
