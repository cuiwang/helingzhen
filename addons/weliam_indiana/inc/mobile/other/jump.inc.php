<?php 
	/*
	 * 各个页面无参数跳转
	 * 2016-4-31
	 */
	 global $_W,$_GPC;
	 
	 $template = !empty($_GPC['template'])?$_GPC['template']:'no';
	 switch($template){
	 	case 'newannounce' : $url = 'goods/newannounce';break;
		default : echo "未找到".$template."对应路径文件!";exit;break;
	 }
	 
	 include $this->template($url);
	?>