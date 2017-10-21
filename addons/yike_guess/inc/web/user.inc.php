<?php 
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$sql = tablename('mc_members');
	$page = max(1, intval($_GPC['page']));
    $size = 5;
    $condition = ' and user.uniacid=:uniacid';
    $params = array(
        ':uniacid' => $_W['uniacid']
    );
if ($operation == 'display') {
	// 检索
	if (!empty($_GPC['realname'])||!empty($_GPC['mid'])) {
		if (!empty($_GPC['mid'])) {
			//用户ID
			$mid =$_GPC['mid'];
			$total = pdo_fetchcolumn("SELECT count(*) FROM".tablename('yike_members')." as A"." left join ".tablename('mc_members')." as B on B.uid=A.uid"." WHERE A.uniacid = :uniacid and B.uid like '%".$mid."%' and B.nickname like '%".$realname."%'",array(
	    		':uniacid' => $_W['uniacid']
		    ));
	    	$sql = "SELECT A.blacklist, B.* FROM".tablename('yike_members')." as A"." left join ".tablename('mc_members')." as B on B.uid=A.uid"." WHERE A.uniacid = :uniacid and B.uid like '%".$mid."%' and B.nickname like '%".$realname."%' ORDER BY A.uid DESC";
			$sql .= " limit " . ($page - 1) * $size . ',' . $size;
			$all = pdo_fetchall($sql, $params);
			// var_dump($all);
			$pager = pagination($total, $page, $size);
		}else{
			$realname =$_GPC['realname'];
			$total = pdo_fetchcolumn("SELECT count(*) FROM".tablename('yike_members')." as A"." left join ".tablename('mc_members')." as B on B.uid=A.uid"." WHERE A.uniacid=:uniacid and B.nickname like '%".$realname."%'",array(
		        ':uniacid' => $_W['uniacid']
		    ));
			// var_dump($total);
			$sql = "SELECT A.blacklist, B.* FROM".tablename('yike_members')." as A"." left join ".tablename('mc_members')." as B on B.uid=A.uid"." WHERE A.uniacid = :uniacid and B.nickname like '%".$realname."%' ORDER BY A.uid DESC";
			$sql .= " limit " . ($page - 1) * $size . ',' . $size;
			$all = pdo_fetchall($sql, $params);
			// var_dump($all);
			$pager = pagination($total, $page, $size);
			var_dump($pager);
		}
		foreach ($all as $key => $value) {
			$all[$key]['num'] = pdo_fetchcolumn("SELECT count(*) FROM" .tablename('yike_guess_order')."WHERE user_id=:user_id",array(
				':user_id'=>$value['uid']
			));
		}
    }else{
    	//总数
		$total = pdo_fetchcolumn("SELECT count(*) FROM".tablename('yike_members')." as A"." left join ".tablename('mc_members')." as B on B.uid=A.uid"." WHERE A.uniacid = :uniacid ORDER BY A.uid DESC",array(
				':uniacid'=>$_W['uniacid']
			));
		// 查询
		$sql = "SELECT A.blacklist, B.* from " . tablename('yike_members') . " as A" . " left join " . tablename('mc_members') . " as B on B.uid=A.uid and B.uniacid={$_W['uniacid']}" . " where 1 and A.uniacid=:uniacid ORDER BY A.uid DESC";
    	$sql .= " limit " . ($page - 1) * $size . ',' . $size;
    	$all = pdo_fetchall($sql, $params);
		// 订单数量
		foreach ($all as $key => $value) {
			$all[$key]['num'] = pdo_fetchcolumn("SELECT count(*) FROM" .tablename('yike_guess_order')."WHERE user_id=:user_id",array(
				':user_id'=>$value['uid']
			));
		}
		// 分页
		// $total = pdo_fetchcolumn("select count(*) from" . tablename('yike_members') . " as user " . " left join " . tablename('mc_members') . " as f on f.uid =user.uid and f.uniacid={$_W['uniacid']}" . " where 1 {$condition} ", $params);
    	$pager = pagination($total, $page, $size);
    }
} else if($operation == 'delete'){
	$id = intval($_GPC['id']);
	$examine = pdo_fetch("SELECT * FROM".$sql."WHERE uid = :id limit 1",array(
		':id' => $id
		));
	if (empty($examine)) {
        message('抱歉，会员不存在或是已经被删除！', $this->createWebUrl('user',array('op'=>'display')), 'error');
    }else{
    	pdo_delete('yike_members', array(
        	'uid' => $_GPC['id']
    	));
    	message('删除成功！', $this->createWebUrl('user',array('op'=>'display')), 'success');
	}
}else if($operation == 'blacklist'){
	$id = intval($_GPC['id']);
	$alter = pdo_fetch("SELECT * FROM".tablename('yike_members')."WHERE uid = :id limit 1",array(
		':id' => $id
		));
	$blacklist = intval($alter['blacklist']);
	if (empty($alter)) {
		message('会员不存在，无法添加到黑名单!', $this->createWebUrl('user',array('op'=>'display')), 'error');
	} else if($blacklist == '0'){
		$user_data = array('blacklist' => 1 );
		$result = pdo_update('yike_members', $user_data, array(
        	'uid' => $_GPC['id']
    	));
		if (!empty($result)) {
		    message('添加黑名单成功!!!', $this->createWebUrl('user',array('op'=>'display')), 'success');
		}
	}else if($blacklist == '1'){
		$user_data = array('blacklist' => 0 );
		$result = pdo_update('yike_members', $user_data, array(
        	'uid' => $_GPC['id']
    	));
		if (!empty($result)) {
		    message('移除黑名单成功!!!', $this->createWebUrl('user',array('op'=>'display')), 'success');
		}
	}
}
include $this->template('web/user');