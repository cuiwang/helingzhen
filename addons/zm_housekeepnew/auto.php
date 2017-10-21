<?php
/**
*
* 自动加载
*
*/
defined('IN_IA') or exit('Access Denied');

global $_W,$_GPC;
function auto($class){
include MODULE_ROOT.'/class/'.$class.'.class.php';
}

spl_autoload_register('auto');