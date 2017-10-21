<?php 
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$order = tablename('yike_guess_order');
$guess = tablename('yike_guess_guess');
$members = tablename('mc_members');
	$page = max(1, intval($_GPC['page']));
    $size = 10;
    $condition = ' and user.uniacid=:uniacid';
    $params = array(
        ':uniacid' => $_W['uniacid']
    );
// var_dump($operation);
if ($operation == 'display') {
	if (!empty($_GPC['id'])||!empty($_GPC['user_id'])) {
		if (!empty($_GPC['user_id'])) {
			$user_id=$_GPC['user_id'];
			$id=$_GPC['id'];
			// var_dump($user_id);
			$total = pdo_fetchcolumn("SELECT count(*) FROM".$order." as A"." left join ".$members." as B on B.uid=A.user_id"." WHERE A.id like '%".$id."%' and B.nickname like '%".$user_id."%' or B.uid like '%".$user_id."%'");
			// var_dump($total);
			$sql = "SELECT A.*, B.nickname FROM".$order." as A"." left join ".$members." as B on B.uid=A.user_id"." WHERE A.id like '%".$id."%' and B.nickname like '%".$user_id."%' or B.uid like '%".$user_id."%' ORDER BY A.id DESC";
			$sql .= " limit " . ($page - 1) * $size . ',' . $size;
			$all = pdo_fetchall($sql, $params);
			// var_dump($all);
			$pager = pagination($total, $page, $size);
		}else{
			$id=$_GPC['id'];
			$total = pdo_fetchcolumn("SELECT count(*) FROM".$order." as A"." left join ".$members." as B on B.uid=A.user_id"." WHERE A.id like '%".$id."%' ORDER BY A.id DESC");
			// $all = pdo_fetchall();
			$sql = "SELECT A.*, B.nickname FROM".$order." as A"." left join ".$members." as B on B.uid=A.user_id"." WHERE A.id like '%".$id."%' ORDER BY A.id DESC";
			$sql .= " limit " . ($page - 1) * $size . ',' . $size;
			$all = pdo_fetchall($sql, $params);
			// var_dump($all);
			$pager = pagination($total, $page, $size);
		}
			foreach ($all as $key => $value) {
				$guess_id=$value['guess_id'];
				// 换算时间戳
				$all[$key]['time']=date("Y-m-d H:i:s",$value['buy_time']);
			}
			$al = pdo_fetch("SELECT name FROM".$guess."WHERE  id=:guess_id",array(
					':guess_id'=>$guess_id
			));
			foreach ($all as $key => $value) {
				// 查询用户昵称
				$all[$key]['nickname'] = pdo_fetch("SELECT nickname FROM" .$members."WHERE uid=:uid",array(
					':uid'=>$value['user_id']
				));
			}
	} else {
		$total= pdo_fetchcolumn("SELECT count(*) FROM" . $order);

		// $all = pdo_fetchall("SELECT A.*, B.name, B.is_open FROM ".$order." A left join ".$guess." B on A.guess_id=B.id");
		// var_dump($all);

		$sql = "SELECT user.*,f.is_open,f.name from".$order." as user "." left join ".$guess." as f on f.id =user.guess_id and f.uniacid={$_W['uniacid']}" . " where 1 {$condition} ORDER BY user.id DESC";
    	$sql .= " limit " . ($page - 1) * $size . ',' . $size;
    	$all = pdo_fetchall($sql, $params);
    	
		foreach ($all as $key => $value) {
			//时间戳转换为时间
			$all[$key]['time']=date("Y-m-d H:i",$value['buy_time']);
			// 查询用户昵称
			$all[$key]['nickname'] = pdo_fetch("SELECT nickname FROM" .$members."WHERE uid=:uid",array(
				':uid'=>$value['user_id']
			));
		}
		// var_dump($all);
		//分页
		$total = pdo_fetchcolumn("SELECT count(*) from".$order." as user "." left join ".$guess." as f on f.id =user.guess_id and f.uniacid={$_W['uniacid']}" . " where 1 {$condition} ", $params);
    	$pager = pagination($total, $page, $size);

	}
}else if($operation == 'delete'){
	$id = intval($_GPC['id']);

	$examine = pdo_fetch("SELECT * FROM".$order."WHERE id = :id limit 1",array(
		':id' => $id
		));
	if (empty($examine)) {
        message('抱歉，会员不存在或是已经被删除！', $this->createWebUrl('indent',array('op'=>'display')), 'error');
    }else{
    	pdo_delete('yike_guess_order', array(
        'id' => $_GPC['id']
    	));
    	message('删除成功！', $this->createWebUrl('indent',array('op'=>'display')), 'success');
	}
}else if($operation == 'detail'){
	$id=$_GPC['id'];

	$examine = pdo_fetch("SELECT * FROM".$order."WHERE id = :id limit 1",array(
		':id' => $id
		));
	$all = pdo_fetchall("SELECT A.id, B.* FROM ".$order." A left join ".$guess." B on A.guess_id=B.id WHERE A.id=:id",array(
			':id'=>$id
		));
	$examine['buy_time']=date("Y-m-d  H:i",$examine['buy_time']);
}
include $this->template('web/indent');