<?php
/**
 * 服务项目
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
 
$action = 'goods';
$title = $this->actions_titles[$action];

if(empty($setting)){
	message("请先配置相关数据！", $this->createWebUrl('setting'), "error");
}

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$storeid = intval($_GPC['storeid']);
if(!empty($storeid)){
	$store = pdo_fetch("SELECT title FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND id='{$storeid}'");
}
if ($operation == 'post') {
	load()->func('tpl');
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id = :id", array(':id' => $id));
		if (empty($item)) {
			message('抱歉，项目不存在或是已经删除！', '', 'error');
		} else {
			if (!empty($item['thumb_url'])) {
				$item['thumbArr'] = explode('|', $item['thumb_url']);
			}
			if (!empty($item['onlycard'])) {
				$item['onlycard'] = $item['onlycard'];
			}
		}
	}

	//洗车卡套餐列表
	$tao_list = pdo_fetchall("SELECT DISTINCT onlycard,onlycard_name FROM " . tablename($this->table_onecard) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
	//项目分类
	$category = pdo_fetchall("SELECT id,name FROM " .tablename($this->table_category). " WHERE weid='{$weid}' AND parentid=0");

	if (checksubmit('submit')) {
		$data = array(
			'weid'         => intval($_W['uniacid']),
			'storeid'      => $storeid?$storeid:'0',
			'title'        => trim($_GPC['goodsname']),
			'cid'          => intval($_GPC['cid']),
			'thumb'        => trim($_GPC['thumb']),
			'unitname'     => trim($_GPC['unitname']),
			'content'      => $_GPC['content'],
			'description'  => trim($_GPC['description']),
			'productprice' => trim($_GPC['productprice']),
			'integral'     => intval($_GPC['integral'])?intval($_GPC['integral']):0,
			'subcount'     => intval($_GPC['subcount']),
			'status'       => intval($_GPC['status']),
			'recommend'    => intval($_GPC['recommend']),
			'displayorder' => intval($_GPC['displayorder']),
			'onlycard'     => trim($_GPC['onlycard']),
			'free_card'    => trim($_GPC['free_card']),
			'dateline'     => TIMESTAMP,
		);

		if (empty($data['title'])) {
			message('请输入项目名称！');
		}
		if (empty($data['cid'])) {
			message('请选择项目分类！');
		}

		if(trim($_GPC['free_card'])=='1'){
			$isexit = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE free_card=1");
			if(!empty($isexit) && $_GPC['id']!=$isexit['id']){
				message('每个服务点只能存在一个首次免费项目');
			}
		}

		if (!empty($_FILES['thumb']['tmp_name'])) {
			load()->func('file');
			file_delete($_GPC['thumb_old']);
			$upload = file_upload($_FILES['thumb']);
			if (is_error($upload)) {
				message($upload['message'], '', 'error');
			}
			$data['thumb'] = $upload['path'];
		}
		if (empty($id)) {
			pdo_insert($this->table_goods, $data);
		} else {
			unset($data['dateline']);
			pdo_update($this->table_goods, $data, array('id' => $id));
		}
		message('项目更新成功！', $this->createWebUrl('goods', array('op' => 'display', 'storeid' => $storeid)), 'success');
	}
} elseif ($operation == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update($this->table_goods, array('displayorder' => $displayorder), array('id' => $id));
		}
		message('排序更新成功！', $this->createWebUrl('goods', array('op' => 'display')), 'success');
	}

	if($setting['store_model']==2 && empty($storeid)){
		message('请选择要管理的服务点！', $this->createWebUrl('store', array('op' => 'display')), 'error');
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 8;
	$condition = '';
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
	}

	if (isset($_GPC['status'])) {
		$condition .= " AND status = '" . intval($_GPC['status']) . "'";
	}

	//加盟模式/多店模式
	if($setting['store_model']==1){
		$condition .= " AND storeid = 0 ";
	}elseif($setting['store_model']==2){
		$condition .= " AND storeid = '{$storeid}' ";
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = '{$_W['uniacid']}' $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_goods) . " WHERE weid = '{$_W['uniacid']}' $condition");
	foreach($list as $key=>$value){
		$list[$key]['cname'] = pdo_fetch("SELECT name FROM " .tablename($this->table_category). " WHERE id='{$value['cid']}'");
	}

	$pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_goods) . " WHERE id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，项目不存在或是已经被删除！');
	}
	if (!empty($row['thumb'])) {
		load()->func('file');
		file_delete($row['thumb']);
	}
	pdo_delete($this->table_goods, array('id' => $id));
	message('删除成功！', referer(), 'success');
} elseif ($operation == 'deleteall') {
	$rowcount = 0;
	$notrowcount = 0;
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		if (!empty($id)) {
			$goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id = :id", array(':id' => $id));
			if (empty($goods)) {
				$notrowcount++;
				continue;
			}
			pdo_delete($this->table_goods, array('id' => $id, 'weid' => $_W['uniacid']));
			$rowcount++;
			//$goods['thumb'];
		}
	}
	$this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
}elseif ($operation == 'ajaxcategory') {
	$pid = intval($_GPC['pid']);
	$catlist = pdo_fetchall("SELECT id,name FROM " .tablename($this->table_category). " WHERE weid='{$weid}' AND parentid='{$pid}'");

	echo json_encode(array('catlist'=>$catlist));
    exit();
}

include $this->template('goods');