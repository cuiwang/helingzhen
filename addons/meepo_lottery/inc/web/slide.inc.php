<?php 
global $_W, $_GPC;
$ops = array('display', 'update', 'delete','add'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';

load()->func('db');
load()->func('pdo');
load()->func('tpl');
$table='meepo_lottery_slide_images';
if($op=='display'){
	$pageindex = max(intval($_GPC['page']), 1); // 当前页码
	$pagesize = 4; // 设置分页大小
	$sql = 'SELECT * FROM'.tablename($table);
	$slide_lists = pdo_fetchall($sql);
 	foreach($slide_lists as $key => $val){
 		if($val['uniacid']==0){
 			$data['uniacid'] = $_W['uniacid'];
 			$row = pdo_update($table,$data,array('slide_id'=>$val['slide_id']));
 		}	
 	}
	$sql = 'SELECT * FROM'.tablename($table)."WHERE uniacid = :uniacid ORDER BY slide_id desc LIMIT ".(($pageindex -1) * $pagesize).','. $pagesize;
	$sql1 = 'SELECT COUNT(*) FROM '.tablename($table).'WHERE uniacid = :uniacid';
	$params =array(':uniacid'=>$_W['uniacid']);
 	$slide = pdo_fetchall($sql,$params);
 	

 	$total = pdo_fetchcolumn($sql1,$params);
 	$pager = pagination($total, $pageindex, $pagesize);
}
if($op=='add'){
// 	$options =array('width'  => '280', // 上传后图片最大宽度
//       'height' => 180, // 上传后图片最大高度
//       'thumb'  => true,// true 或不设置, 则保存为指定 width / height 的缩略图; false: 上传图片不经任何处理.
//       'global' => true, // true：公共图库 ，false：公众号图库。
//       'tabs'   => array(   // 功能选项卡, 至少包含 browser, upload, remote 一个, 含有多个时, 只有一个可以赋值为 'active'(激活).
//              'browser' => '',  // 浏览服务器图片     
//              'upload'  => 'active',        // 上传图片
//              'remote'  => '',        // 上传图片到微信端
//       ),
//       'dest_dir'=>'test_aa/images',
   
// );       
	if(checksubmit('submit')){
		$images['slide_link_url']=$_GPC['link_url'];
		$images['slide_image_url']=$_GPC['image_url'];
		$images['slide_status']=$_GPC['status'];
		$images['uniacid']=$_W['uniacid'];
		$row = pdo_insert($table, $images);
		
		if($row){
			message('添加幻灯片成功！',$this->createWebUrl('slide'),array('op'=>'add'),'success');
		}else{
			message('添加幻灯片失败！',$this->createWebUrl('slide'),array('op'=>'add'),'error');
			}
		}
	}
if( $op=='update'){
	
	$id = intval($_GPC['id']);
	if(!empty($id)){
		$sql = 'SELECT * FROM '.tablename($table).' WHERE slide_id=:id AND uniacid = :uniacid';
		$params = array(':id'=>$id,':uniacid'=>$_W['uniacid']);
		$results = pdo_fetch($sql, $params);

	}
	if(checksubmit('submit')){
		if(empty($id)){
		message('未找到指定幻灯片');
	}
	$data['slide_link_url']=$_GPC['link_url'];
	$data['slide_image_url']=$_GPC['image_url'];
	$data['slide_status']=$_GPC['status'];
	$data['uniacid']=$_W['uniacid'];
	$resut = pdo_update($table, $data, array('slide_id'=>$id,':uniacid'=>$_W['uniacid']));
	if (!empty($resut)) {
			message('图片修改成功！', $this->createWebUrl('slide', array('op'=>'display')), 'success');
		} else {
			message('图片修改失败！');
		}
	}
	
}
if($op=='delete'){
	$id = intval($_GPC['id']);
			if(empty($id)){
				message('未找到指定图片！');
			}
			$result = pdo_delete($table, array('slide_id'=>$id,':uniacid'=>$_W['uniacid']));
			if(intval($result) == 1){
				message('删除图片成功！', $this->createWebUrl('slide'), 'success');
			} else {
				message('删除图片失败！');
			}
}


include $this->template('web/slide');