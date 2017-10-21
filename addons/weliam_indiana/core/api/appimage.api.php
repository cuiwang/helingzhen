<?php
	// +----------------------------------------------------------------------
	// | Copyright (c) 2015-2020 http://www.startingline.com.cn All rights reserved.
	// +----------------------------------------------------------------------
	// | Describe: app处理上传图片
	// +----------------------------------------------------------------------
	// | Author: startingline<800083075@qq.com>
	// +----------------------------------------------------------------------
	require '../../../../framework/bootstrap.inc.php';
	require IA_ROOT. '/addons/weliam_indiana/defines.php';
	require WELIAM_INDIANA_INC.'function.php';
	
	load()->func('communication');
	$data = file_get_contents('php://input');
	return $data;