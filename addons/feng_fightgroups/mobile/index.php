<?php
	$pindex = max(1, intval($_GPC['page'])); //当前页码
	$psize = 1;	//设置分页大小                                                               
	$condition = 'AND isshow = 1'; 
	$params = array(
		':uniacid'=>$_W['uniacid']
	);

	include $this->template('index');
?>