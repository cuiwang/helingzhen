<?php

	global $_W, $_GPC;
  	load()->func('tpl');
  	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
  	$class = !empty($_GPC['class']) ? $_GPC['class'] : 'this';
  	$uniacid=$_W["uniacid"];
  	$id=$_GPC['id'];
  	$pay_id=$_GPC['pay_id'];
  	if(!empty($pay_id))
  	{
  		$where.=" and wxpay_id=$pay_id ";
  	}
  	if ($op=='display') {
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
		$list = pdo_fetchall("SELECT *  from ".tablename('gzredbag_wxpay_order')."  where uniacid=$uniacid $where  order by id desc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('gzredbag_wxpay_order')."  where uniacid=$uniacid $where");
		$pager = pagination($total, $pindex, $psize);
  	}
		
	if ($op=='delete') {
		pdo_delete('gzredbag_wxpay_order', array('id' => $id));
        message('删除成功！', $this->createWebUrl('wxpay_item', array('op' => 'display','pay_id'=> $pay_id)), 'success');
		}

	if ($op=='delete_all') {
		if(!empty($pay_id)){
  			pdo_delete('gzredbag_wxpay_order',array('wxpay_id' => $pay_id));
  		}
  		else{
  			pdo_delete('gzredbag_wxpay_order');
  		}
		
        message('删除成功！', $this->createWebUrl('wxpay_item', array('op' => 'display','pay_id'=> $pay_id)), 'success');
		}

  	include $this->template('wxpay_item');