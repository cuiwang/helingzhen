<?php 
	// 
	//  notice.inc.php
	//  <project>
	// 	公告栏 
	//  Created by Administrator on 2016-06-28.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	
	global $_W,$_GPC;
	
	$id = $_GPC['id'];
	
	if(!empty($id)){
		$notice = pdo_fetch("select * from".tablename('weliam_indiana_notice')."where uniacid='{$_W['uniacid']}' and id='{$id}'");
		include $this->template('other/notice');
	}
