<?php
/**
 * 钻石投票模块-投票数据
 *
 * @author 天涯织梦
 * @url http://bbs.we7.cc/
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$id=intval($_GPC['id']);
$uniacid=$_W['uniacid'];
$type=intval($_GPC['type']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if(empty($_W['isfounder'])){
	message('没有权限！', '', 'error');
}

if($op=='display'){

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	if (!empty($_GPC['keyword'])) {
		$condition .= "AND CONCAT(`user`,`qq`) LIKE '%{$_GPC['keyword']}%'";
	}
	$condition .=" ORDER BY id DESC ";
	$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablesetmeal)." WHERE  id != '' $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");

	if (!empty($list)) {
		 $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablesetmeal) . " WHERE  id != '' $condition");
		 $pager = pagination($total, $pindex, $psize); 
	 }
}
if($op=='add'){
    $id = intval($_GPC['id']);
	if($_W['ispost']){
		$user = trim($_GPC['user']);
		$userinfo = user_single(array('username' => $user));
		if (!empty($userinfo)) {
			if ($userinfo['status'] != 2) {
				message('用户未通过审核或不存在！', '', 'error');
			}
			$data = array(
				'type' => '0',
				'userid' =>$userinfo['uid'],
				'user' =>$_GPC['user'],
				'count' =>intval($_GPC['count']),
				'price' =>$_GPC['price'],
				'qq' =>$_GPC['qq'],
				'remark' =>trim($_GPC['remark']),
				'status' =>intval($_GPC['status']),
				'starttime' =>strtotime($_GPC['time']['start']),
				'endtime' =>strtotime($_GPC['time']['end']),
				'createtime'=>time()
			);	
		    if (!empty($id)) {
		    	unset($data['createtime']);
				$re=pdo_update($this->tablesetmeal, $data, array('id' => $id));
			} else {
				$re=pdo_insert($this->tablesetmeal, $data);
			}		
			if($re){
				message('设置成功！', $this->createWebUrl('setmeal'), 'success');
			}else{
				message('更新失败', '', 'error');
			}
		} else  {
			message('参数错误，请刷新重试！', '', 'error');
		}
	}
	if(empty($id)){
		$list['count']=9;
		$list['price']=1000;
		$list['createtime']=time();
        $list['endtime']=time() + 30 * 84400;
	}else{
		$list = pdo_fetch("SELECT * FROM " . tablename($this->tablesetmeal) . " WHERE  id = :id ", array(':id' => $id));
	}
}

if($op=='delete'){
	$id=intval($_GPC['id']);
	$re=pdo_delete($this->tablesetmeal,array('id' => $id));
	if($re){
		message('删除成功！', $this->createWebUrl('setmeal', array('name' => 'tyzm_diamondvote')), 'success');
	}else{
		message('删除失败，不存在该名单！', $this->createWebUrl('setmeal', array('name' => 'tyzm_diamondvote')), 'error');
	}
}

include $this->template('setmeal');

