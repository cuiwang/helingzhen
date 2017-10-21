<?php 
	$weid = $_W['uniacid'];
	$rid = intval ( $_GPC ['replyid'] );

	$sql = "delete from ".tablename( $this->table_user )." where uniacid='{$weid}' and replyid = '{$rid}'";
	pdo_query($sql);
	message('测试数据删除成功!',$this->createWebUrl('activity'),'success');