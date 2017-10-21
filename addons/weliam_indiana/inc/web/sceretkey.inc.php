<?php 
	// 
	//  sceretkey.inc.php
	//  <project>
	//  密匙分配
	//  Created by haoran on 2016-01-26.
	//  Copyright 2016 haoran. All rights reserved.
	// 
	global $_W,$_GPC;
	$op = !empty($_GPC['op'])?$_GPC['op']:'notused';
	if($op == 'ontused'){
		$period = pdo_fetchall("select * from ".tablename('')."where keystatus = 1");
		include $this->template('secretkey');
		exit;
	}else if($op == 'create'){
		
	}
	
	?>