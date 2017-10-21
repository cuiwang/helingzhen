<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('getnum', 'addnum', 'index');
$do = in_array($do, $dos) ? $do : 'index';
$id = intval($_GPC['id']);

if($do == 'getnum'){
	$goodnum = pdo_get('site_page', array('id' => $id), array('goodnum'));
	message(error('0', array('goodnum' => $goodnum['goodnum'])), '', 'ajax');
} elseif($do == 'addnum'){
	if(!isset($_GPC['__havegood']) || (!empty($_GPC['__havegood']) && !in_array($id, $_GPC['__havegood']))) {
		$goodnum = pdo_get('site_page', array('id' => $id), array('goodnum'));
		if(!empty($goodnum)){
			$updatesql = pdo_update('site_page', array('goodnum' => $goodnum['goodnum'] + 1), array('id' => $id));
			if(!empty($updatesql)) {
				isetcookie('__havegood['.$id.']', $id, 86400*30*12);
				message(error('0', ''), '', 'ajax');
			}else { 
				message(error('1', ''), '', 'ajax');
			}
		}		
	}
} else {
	$footer_off = true;
	template_page($id);
}
