<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
 defined('IN_IA') or exit('Access Denied');
        global $_W, $_GPC;
        $dos = array('modlist', 'paylist');
		$do = in_array($do, $dos) ? $do : 'modlist';
        $uid = $_GPC['uid'];
        if (!empty($uid)) {
            $condition .= "where uid=" . $uid;
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
if ($do == 'modlist') {
		checklogin();
		$uid=$_W['uid'];
		$_W['page']['title'] = "消费记录";
			if($_W['isfounder']){
				$list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record") . " ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$total = pdo_fetchcolumn('SELECT * FROM '. tablename('users_credits_record'));
			}
			else{
				$list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record")." WHERE uid=:uid AND uniacid=:weid ORDER BY createtime LIMIT ". ($pindex - 1) * $psize . ',' . $psize,array(':uid'=>$_W['uid'],':weid' => $_W['uniacid']));
				$total = pdo_fetchcolumn("SELECT * FROM ".tablename("users_credits_record")." WHERE uid=:uid AND uniacid=:weid ",array(':uid'=>$_W['uid'],':weid' => $_W['uniacid']));
				}
			$uni = pdo_fetchall("SELECT name,uniacid FROM ".tablename("account_wechats"),array(),'uniacid');
			$user =pdo_fetchall("SELECT username,uid FROM ".tablename("users"),array(),'uid');

}
if ($do == 'paylist') {
			checklogin();
			$uid=$_W['uid'];
			if($_W['isfounder']){
				$list = pdo_fetchall("SELECT * FROM " . tablename('uni_payorder') . " ORDER BY order_time LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$total = pdo_fetchcolumn('SELECT * FROM ' . tablename('uni_payorder'));
			}
			else{
				$list = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid  ORDER BY order_time LIMIT ". ($pindex - 1) * $psize . ',' . $psize,array(':uid'=>$_W['uid']));
				$total = pdo_fetchcolumn("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid  ",array(':uid'=>$_W['uid']));
				}
			
			$user =pdo_fetchall("SELECT username,uid FROM ".tablename("users"),array(),'uid');

}
$pager = pagination($total, $pindex, $psize);
template('members/record');