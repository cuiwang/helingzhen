<?php
/**
 * [WeEngine System] Copyright (c) 2013 weizancms.com
 * $sn$
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('display', 'post', 'logo');
$do = in_array($do, $dos) ? $do : 'post';
$accounts = uni_accounts(); //todo:需要做权限判断

if($do == 'post') {
	load()->func('tpl');
	load()->model('coupon');



}
template('wechat/coupon');