<?php
  global $_W,$_GPC;
  
  if(!empty($_GPC['dopost'])){
    $id = $_GPC['id'];
	if($_GPC['dopost']=='shenhe'){
	  $status = $_GPC['status'];
	  pdo_update('cgc_ad_message',array('status'=>$status),array('id'=>$id));
	  message('操作成功',referer(),'success');
	 }else if($_GPC['dopost']=='del'){
	  pdo_delete('cgc_ad_message',array('id'=>$id));
	  message('操作成功',referer(),'success');
	 }
     exit;
	}
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = "SELECT COUNT(id) FROM ".tablename('cgc_ad_message')." where weid=".$_W['uniacid'];
	$_count = pdo_fetchcolumn($sql);
	$sql = "SELECT a.*,b.from_user as oppenid,b.nickname,b.headimgurl  as avatar FROM ".tablename('cgc_ad_message')." a
	LEFT JOIN ".tablename('cgc_ad_member')." b on a.mid=b.id
	where a.weid=".$_W['uniacid']." order by id desc LIMIT ".($pindex - 1) * $psize.",".$psize;
	$list  = pdo_fetchall($sql);
	$pager = pagination($_count, $pindex, $psize);
	include $this->template('web/message');