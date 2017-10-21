<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('buy');
$do = in_array($_GPC['do'], $dos)? $do : 'buy' ;
global $_W,$_GPC;
checklogin();
$user      = $_W['user'];
if ($do == 'buy') {
$group     = pdo_get('users_group',array('id'=>$user['groupid']));
$usergroups=pdo_fetchall("SELECT * FROM".tablename('users_group') ."where price >= 1 AND  price != 1001 order by price asc",array(':price'=>$group['price']),'id');
	if($_GPC['groupid']){
		$groupid=$_GPC['groupid'];
		if(empty($usergroups[$groupid])){
			itoast('访问错误',url('shop/buypackage'),error);
		}
		if(empty($user['endtime'])){
			itoast('获取有效期失败，永久会员不能升级！','' ,error);
		}
		if($groupid == $group['id']){
			$credit  = $user['credit2'] - $group['price'];
			$endtime = $user['endtime'] + 365*86400;
			if($credit<0){
				itoast('余额不足，您当前还需充值'.(-$credit).'元方能成功续费',url('shop/member'),error);
			}
			else{
				pdo_update('users',array('credit2'=>$credit,'endtime'=>$endtime),array('uid'=>$user['uid']));
				pdo_insert('users_credits_record',array('uid'=>$user['uid'],'credittype'=>'credit2','num'=>-$group['price'],'createtime'=>TIMESTAMP,'remark'=>'会员续费'));
				itoast('续费成功！',url('shop/buypackage'),success);
			}
		}else{
			$price  = sprintf("%.2f",($usergroups[$groupid]['price'] - $group['price'])*($user['endtime']- TIMESTAMP)/31536000);
			$credit = $user['credit2'] - $price;
			if($credit<0){
				itoast('余额不足，您当前还需充值'.(-$credit).'元（计算公式：(剩余天数/365)*套餐差价）方能成功升级套餐',url('shop/member'),error);
			}
			else{
				pdo_update('users',array('groupid'=>$groupid,'credit2'=>$credit),array('uid'=>$user['uid']));
				pdo_insert('users_credits_record',array('uid'=>$user['uid'],'credittype'=>'credit2','num'=>-$price,'createtime'=>TIMESTAMP,'remark'=>'会员升级'));
				itoast('升级成功！',url('shop/buypackage'),success);
			}
		}
	}
}
template('shop/buypackage');