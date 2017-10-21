<?php
defined('IN_IA') or exit('Access Denied');
require WELIAM_INDIANA_INC.'template.php';
global $_W, $_GPC;

$fun = check_webcon($name,$dir);
$getlistFrames = 'get'.$fun['nav'].'Frames';
$frames = $getlistFrames();
$top_menus = get_top_menus();

$file = $fun['dir'] . $fun['fun'] . '.inc.php';
if(!file_exists($file)) {
	header("Location: index.php?c=site&a=entry&m=weliam_indiana&do=goods");
	exit;
}

require $file;