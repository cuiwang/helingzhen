<?php

    $op = $_GPC['op'];
	$pindex = max(1, intval($_GPC['page']));

	$psize = 20;

	if($op == 'delete'){
		    $memberid = intval($_GPC['id']);
			
			if(!empty($memberid)){
                 if(pdo_delete('tg_member', array('id' => $memberid))){
                 	message('删除粉丝成功', $this->createWebUrl('member'), 'success');
                 }	

			}
	}

	$members = pdo_fetchall("SELECT * FROM ".tablename('tg_member')." WHERE uniacid = {$_W['uniacid']} ORDER BY id asc LIMIT ".($pindex - 1) * $psize.','.$psize);

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_member') . " WHERE uniacid = {$_W['uniacid']} ");

	

	$pager = pagination($total, $pindex, $psize);



	include $this->template('member');

?>