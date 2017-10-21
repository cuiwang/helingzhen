<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('list', 'post');
$do = in_array($do, $dos) ? $do : 'list';
		global $_W, $_GPC;
		load()->func('tpl');
        $mid    = $_GPC['mid'];
        $id     = $_GPC['id'];
        $pindex = max(1, intval($_GPC['page']));
		if (!empty($_GPC['keyword'])){
			$condition=" AND title LIKE :title";
		}
        $psize  = 2;
        if ($do == 'list') {
			$_W['page']['title'] = '编辑模块-模块列表';
            $modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " where 1" . $condition, array(':title'=>"%{$_GPC['keyword']}%"), 'name');
            $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('modules') . " WHERE 1" . $condition);

			if(empty($taocmd)){
					$taocmd=array('default');
				}
            $pager   = pagination($total, $pindex, $psize);
        } elseif ($do == 'post') {
            $modules = pdo_fetch("SELECT * FROM " . tablename('modules') . "where mid=:mid", array(
                ':mid' => $mid
            ), 'name');
			}
		if(checksubmit('submit')) {
			$update = array();
			!empty($_GPC['title']) && $update['title'] = $_GPC['title'];
			!empty($_GPC['type']) && $update['type'] = $_GPC['type'];
			!empty($_GPC['price']) && $update['price'] = $_GPC['price'];
			!empty($_GPC['name']) && $update['name'] = $_GPC['name'];
			!empty($_GPC['ability']) && $update['ability'] = $_GPC['ability'];
			!empty($_GPC['description']) && $update['description'] = $_GPC['description'];
			if(!empty($update)) {
				pdo_update('modules', $update, array('name' => $modules['name']));
			}
            message('设置成功！', url('shop/module', array(
                'op' => 'list'
            )), 'success');
        }
        template('shop/module');
	