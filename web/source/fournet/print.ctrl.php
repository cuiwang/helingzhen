<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('fournet_print');
$dos=array('print','printep','post','list','printlist','set','cs');
$do=in_array($do,$dos) ? $do : 'printlist';
$api="http://addons.weizancms.com/web/index.php?c=api&a=print";
$sql = 'SELECT name,title FROM ' . tablename('modules') . ' WHERE `type` <> :type';
$modules = pdo_fetchall($sql, array(':type' => 'system'), 'name');
load()->func('communication');
if($do == 'print'){
	if(!empty($_GPC['id'])){
		$id=intval($_GPC['id']);
		$print=pdo_get('printer',array('uniacid' => $_W['uniacid'],'id'=>$id));
	}
	if(checksubmit('submit')) {
		$print = array(
				'uniacid' => $_W['uniacid'],
				'name' => $_GPC['name'],
				'type' => intval($_GPC['type']),
				'module'=>trim($_GPC['module']),
				'isdefault' =>  intval($_GPC['isdefault']),
				'token' => $_GPC['tokend'],
				'apikey' => $_GPC['apikey'],
				'dtuid' => $_GPC['dtuid'],
				'imei' => $_GPC['imei'],
				'top' => $_GPC['top'],
				'bottom' => $_GPC['bottom']
			);
        if(!empty($_GPC['id'])){
			pdo_update('printer',$print,array('id'=>intval($_GPC['id'])));
		}else{
			pdo_insert('printer',$print);
		}
		message('添加打印机成功！', url('fournet/print'));
	}
}
if($do == 'printlist'){
	$list=pdo_getall('printer',array('uniacid'=>$_W['uniacid']));
	$sql = 'SELECT COUNT(*) FROM ' . tablename('printer');
	$total = pdo_fetchcolumn($sql);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$pager = pagination($total, $pindex, $psize);
	$id=intval($_GPC['id']);
	if($id){
		pdo_delete('printer',array('id'=>$id));
		message('打印机删除成功！',url('fournet/print/printlist'));
	}
	if(checksubmit('submit')) {
		$ids=$_GPC['ids'];
		if(!empty($ids)){
			foreach($ids as $id){
				$id=intval($id);
				pdo_delete('printer',array('id'=>$id));
			}
		}
		message('批量删除打印机成功！',url('fournet/print/printlist'));
		
	}

}
if($do == 'list'){
	$id=intval($_GPC['id']);
	if($id){
		pdo_delete('printep',array('id'=>$id));
		message('模版删除成功！',url('fournet/print/list'));
	}
	if(checksubmit('submit')) {
		$ids=$_GPC['ids'];
		if(!empty($ids)){
			foreach($ids as $id){
				$id=intval($id);
				pdo_delete('printep',array('id'=>$id));
			}
		}
		message('批量删除模版成功！',url('fournet/print/list'));
		
	}
	$name = trim($_GPC['name']);
	$module = trim($_GPC['module']);
	$condition='';
	$params = array();
	$condition .=' and `uniacid`='.$_W['uniacid'];
	if(!empty($name)){
		$condition .=' and `name` LIKE :name';
		$params[':name']='%'.$name.'%';
	}
	if(!empty($module)){
		$condition .=' and `module`=:module';
		$params[':module']=$module;
	}
	$sql='select * from '. tablename('printep').' where 1 '.$condition;
	$list=pdo_fetchall($sql,$params);
	$sql = 'SELECT COUNT(*) FROM ' . tablename('printep').' where 1 '.$condition;
	$total = pdo_fetchcolumn($sql);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$pager = pagination($total, $pindex, $psize);

}
if($do == 'printep'){
	cache_load('printdata');
	cache_load('printdatatime');
	if(!empty($_W['cache']['printdata'])) {
		$data = $_W['cache']['printdata'];
	}
	if(!empty($_W['cache']['printdata'])) {
		$datatime = $_W['cache']['printdatatime'];
	}
	if(empty($data) || TIMESTAMP - $datatime >= 3600){
		$data=ihttp_request($api);
		$data=$data['content'];
		cache_write('printdata',$data);
		cache_write('printdatatime',TIMESTAMP);
	}
	$list= json_decode(base64_decode($data),true);

		//下载模版
	$id=intval($_GPC['id']);
	if($id){
		$data=array(
			'uniacid'=>$_W['uniacid'],
			'tepid'=>$list[$id]['tepid'],
			'name'=>$list[$id]['name'],
			'module'=>$list[$id]['module'],
			'content'=>$list[$id]['content'],
		);
		$pep=pdo_get('printep',array('tepid'=>$list[$id]['tepid']));
		if(empty($pep)){
			pdo_insert('printep',$data);
			message('模版下载成功！');
		}else{
			pdo_update('printep',$data,array('tepid'=>$list[$id]['tepid']));
			message('模版更新成功！');
		}
	}
	foreach($list as $k=>$v){
		if(!empty($_GPC['name'])){
		    $name=trim($_GPC['name']);
			if(strpos($v['name'],$name)===false){
				array_splice($list,$k,1);
				continue;
			}
	    }
		if(!empty($_GPC['module'])){
		    $module=trim($_GPC['module']);
			if($v['module']!=$module){
				array_splice($list,$k,1);
				continue;
			}
	    }
	}
	$total=count($list);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$pager = pagination($total, $pindex, $psize);
	$bendi=pdo_fetchall('select `tepid` from '. tablename('printep').' where `uniacid`=:uniacid',array(':uniacid'=>$_W['uniacid']),'tepid');
}
if($do == 'post'){
	if(!empty($_GPC['id'])){
		$id=intval($_GPC['id']);
		$printep=pdo_get('printep',array('id'=>$id));
		$printep['content']=base64_decode($printep['content']);
	}

	if(checksubmit('post')) {
		//新增、编辑模版
		$data = array();
		$data['name']=trim($_GPC['name']);
		$data['module']=trim($_GPC['module']);
		$data['defaul']=trim($_GPC['defaul']);
		$data['content']=base64_encode($_GPC['content']);
		$tepid = trim($_GPC['tepid']);
		if(empty($tepid)){
			$tepid=ihttp_request($api.'&do=update',array('data'=>base64_encode(json_encode($data))));
			$tepid=trim($tepid['content']);
			if(!$tepid){
				message('模版新增失败！');
			}
			$data['tepid']=$tepid;
			$data['uniacid']=$_W['uniacid'];
			pdo_insert('printep',$data);
		}else{
			pdo_update('printep',$data,array('tepid'=>$tepid));
			ihttp_request($api.'&do=update',array('data'=>base64_encode(json_encode($data)),'tepid'=>$tepid));
		}
		message('模版编辑成功！',url('fournet/print/list'));

	}
}
if($do == 'set'){
$row = pdo_fetchcolumn("SELECT `print` FROM ".tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
$print = iunserializer($row);
if(checksubmit('submit')) {
	$print = array(
			'name' => $_GPC['name'],
			'type' => $_GPC['type'],
			'use' => $_GPC['use'],
			'appkey' => $_GPC['appkey'],
			'dis' => $_GPC['dis'],
			'code' => $_GPC['code'],
			'num' => $_GPC['num'],
		);
		$row = array();
		$row['print'] = iserializer($print);
		pdo_update('uni_settings', $row, array('uniacid' => $_W['uniacid']));
		message('更新设置成功！', url('fournet/print'));
	}
}
if($do == 'cs'){
	$printers=pdo_getall('printer',array('uniacid'=>$_W['uniacid']));
	if(checksubmit('submit')) {
		
			$results="<S3>打印测试</S3>";
			$results=printer($results,'',$_GPC['printid']);
			if ($results){
				$results = '打印机对接成功！';
			}else{
				$results = '打印机对接失败：可能没有设置参数好！';
			}
			$row['print'] = iserializer($print);
			message('测试结果：'.$results, url('fournet/print/cs'));
	}

}
template('fournet/print');
