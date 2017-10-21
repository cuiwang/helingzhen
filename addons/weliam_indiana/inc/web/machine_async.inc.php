<?php 
	// 
	//  machine_async.inc.php
	//  <project>
	//  异步执行机器人
	//  Created by Administrator on 2016-03-24.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	global $_W,$_GPC;
	$period_number = $_GPC['period_number'];
	$timebucket = $_GPC['timebucket'];
	$code_num = $_GPC['code_num'];
	file_put_contents(WELIAM_INDIANA."/machine.log", var_export($period_number.'||'.$timebucket.'||'.$code_num, true).PHP_EOL, FILE_APPEND);
	m('machine')->marchine_cir($period_number , $timebucket , $code_num);
	?>