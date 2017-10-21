<?php
global $_W;
define('CORE_PATH', str_replace('init.php', '', str_replace('\\', '/', __FILE__)));

if(!function_exists('mload')){
	include CORE_PATH . 'mload.class.php';
}
mload()->classs('imeepos_core');
mload()->func('common');

mload()->model('frame');
global $frames_nav;
if(function_exists('getModuleFrames')){
	$frames_nav = getModuleFrames('meepo_fen');
}

$m = M('setting');
