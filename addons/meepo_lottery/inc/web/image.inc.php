<?php
global $_W,$_GPC;

$ops = array('display', 'update', 'delete','add','setting_price','price_list','update_price'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';

$tablename = 'meepo_lottery_images';
load()->func('db');
load()->func('pdo');
load()->func('tpl');

if($op == 'display'){
	$sql = 'SELECT * FROM'.tablename($tablename);
	$images_lists = pdo_fetchall($sql);
 	foreach($images_lists as $key => $val){
 		if($val['uniacid']==0){
 			$data['uniacid'] = $_W['uniacid'];
 			$row = pdo_update($tablename,$data,array('images_id'=>$val['images_id']));
 		}	
 	}
	$sql = ' SELECT * FROM '.tablename($tablename).'WHERE uniacid = :uniacid ORDER BY images_number asc';
	$params =array(':uniacid'=>$_W['uniacid']);
	$results = pdo_fetchall($sql,$params);
}
if($op == 'add'){
	$sql = ' SELECT count(*) FROM '.tablename($tablename).'WHERE images_status = :status AND uniacid = :uniacid';
	$params = array(
		':status' => 0,
		':uniacid'=>$_W['uniacid']
	);
	$total = pdo_fetchcolumn($sql,$params);
	if($total>=8){
		message('显示的图片大于最大能显示的数目',$this->createWebUrl('image'),array('op'=>'display'),'error');
	}
	if(checksubmit('submit')){
		if(!empty($_GPC['title'])){
			$data['images_title'] = $_GPC['title'];
			$data['images_number'] = $_GPC['number'];
			$data['images_thumbnail'] = $_GPC['thumbnail'];
			$data['images_status'] = $_GPC['status'];
			$data['images_total'] = $_GPC['price_total'];
			$data['price_status'] = $_GPC['price_status'];
			$data['price_percent'] = $_GPC['price_percent'];
			$data['price_thanks'] = $_GPC['price_thanks'];
			$data['uniacid'] = $_W['uniacid'];
			$row = pdo_insert($tablename,$data);
			if($row){
				message('添加图片成功！',$this->createWebUrl('image'),array('op'=>'display'),'success');
			}else{
				message('添加图片失败！',$this->createWebUrl('image'),array('op'=>'add'),'error');
			}
		}
	}
}
if($op =='update'){
	$id = intval($_GPC['id']);
	if(!empty($id)){
		$sql = ' SELECT * FROM '.tablename($tablename).'WHERE images_id = :id AND uniacid = :uniacid';
		$params = array(
			':id' => $id,
			':uniacid'=>$_W['uniacid']
		);
		$result = pdo_fetch($sql,$params);
		if(!empty($result)){
			if(checksubmit('submit')){
				$data['images_title'] = $_GPC['title'];
				$data['images_thumbnail'] = $_GPC['thumbnail'];
				$data['images_status'] = $_GPC['status'];
				$data['images_number'] = $_GPC['number'];
				$data['images_total'] = $_GPC['price_total'];
				$data['price_status'] = $_GPC['price_status'];
				$data['price_percent'] = $_GPC['price_percent'];
				$data['price_thanks'] = $_GPC['price_thanks'];
				$data['uniacid'] = $_W['uniacid'];
				$row = pdo_update($tablename,$data,array('images_id'=>$id,'uniacid'=>$_W['uniacid']));
				if($row){
					message('奖品图片更新成功！',$this->createWebUrl('image'),array('op'=>'display'),'success');
				}else{
					message('奖品图片更新失败！',$this->createWebUrl('image'),array('op'=>'display'),'success');
				}
			}
		}
	}
}
if($op == 'delete'){
	$id = intval($_GPC['id']);
	if(!empty($id)){
		$row = pdo_delete($tablename,array('images_id'=>$id,'uniacid'=>$_W['uniacid']));
		if($row){
		message('删除图片成功！',$this->createWebUrl('image'),array('op'=>'display'),'success');

	}else{
		message('添加图片失败！',$this->createWebUrl('image'),array('op'=>'display'),'error');

		}
	}
}
if($op == 'setting_price'){
	$data =array();
	if(!empty($_GPC['price_number']) && !empty($_GPC['price_percent'])){
		$price_number = $_GPC['price_number'];
		
		$price_percent = $_GPC['price_percent'];

		foreach($price_number as $key=>$val){
			foreach($price_percent as $key1=>$val1){
				if($key == $key1){
					$data['price_percent'] = $val1;
					$data['price_status'] = 1;
					$row = pdo_update($tablename,$data,array('images_number'=>$val,'uniacid'=>$_W['uniacid']));
					if($row){
						message('设置中奖成功！',$this->createWebUrl('image'),array('op'=>'price_list'),'success');
					 	}else{
					 	message('设置中奖成功！',$this->createWebUrl('image'),array('op'=>'setting_price'),'success');
					 	}
					}
				}
			}
		}
 	}


include $this->template('web/image');