<?php
global $_GPC, $_W;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$grouplist = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUP)." WHERE weid = {$_W['uniacid']}");
	foreach($grouplist as $k=>$v){
		$grouplist[$k]['member'] = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$v['id']} AND status = 1");
		$grouplist[$k]['chatlist'] = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND groupid = {$v['id']} ORDER BY time DESC");
		$grouplist[$k]['url'] = $_W['siteroot'].'app/'.str_replace('./','',$this->createMobileUrl('groupchatdetail',array('groupid'=>$v['id'])));
	}
	include $this->template('web/group');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$group = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
	}
	$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 ORDER BY displayorder ASC");
	if (checksubmit('submit')) {
		if (empty($_GPC['groupname'])) {
			message('抱歉，请输入群聊名称！');
		}
		if (empty($_GPC['thumb'])) {
			message('抱歉，请上传群聊头像！');
		}
		if (empty($_GPC['admin'])) {
			message('抱歉，请选择群聊管理员！');
		}				
		$data = array(
			'weid' => $_W['uniacid'],
			'groupname' => trim($_GPC['groupname']),
			'thumb' => trim($_GPC['thumb']),
			'time'=>TIMESTAMP,
			'autoreply'=>trim($_GPC['autoreply']),
			'admin'=>trim($_GPC['admin']),
			'quickcon'=>trim($_GPC['quickcon']),
			'isguanzhu'=>intval($_GPC['isguanzhu']),
			'maxnum'=>intval($_GPC['maxnum']),
			'jinyan'=>intval($_GPC['jinyan']),
			'isshenhe'=>intval($_GPC['isshenhe']),
		);
		if (!empty($id)) {
			pdo_update(BEST_GROUP, $data, array('id' => $id, 'weid' => $_W['uniacid']));
		} else {
			pdo_insert(BEST_GROUP,$data);
			$id = pdo_insertid();
		}
		$hasgroupmember = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$id} AND openid = '{$_GPC['admin']}'");
		if(empty($hasgroupmember)){
			$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_GPC['admin']}'");
			$datamember['weid'] = $_W['uniacid'];
			$datamember['groupid'] = $id;
			$datamember['openid'] = trim($_GPC['admin']);
			$datamember['nickname'] = $cservice['name'];
			$datamember['avatar'] = tomedia($cservice['thumb']);
			$datamember['type'] = 2;
			$datamember['status'] = 1;
			$datamember['intime'] = TIMESTAMP;
			pdo_insert(BEST_GROUPMEMBER,$datamember);
		}
		message('操作成功！', $this->createWebUrl('group', array('op' => 'display')), 'success');
	}
	include $this->template('web/group');
}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$group = pdo_fetch("SELECT id FROM " . tablename(BEST_GROUP) . " WHERE id = {$id}");
	if (empty($group)) {
		message('抱歉，不存在该群聊或是已经被删除！', $this->createWebUrl('group', array('op' => 'display')), 'error');
	}
	pdo_delete(BEST_GROUP, array('id' => $id));
	pdo_delete(BEST_GROUPMEMBER, array('groupid' => $id));
	pdo_delete(BEST_GROUPCONTENT, array('groupid' => $id));
	message('删除群聊成功！', $this->createWebUrl('group', array('op' => 'display')), 'success');
}elseif ($operation == 'deleteall') {
	if (!empty($_GPC['groupid'])) {
		foreach ($_GPC['groupid'] as $id => $groupid) {
			pdo_delete(BEST_GROUPCONTENT, array('groupid' => $groupid));
		}
		message('删除聊天记录成功！', $this->createWebUrl('group', array('op' => 'display')), 'success');
	}else{
		message("请选择一条记录！");
	}
}elseif ($operation == 'delmember') {
	$id = intval($_GPC['id']);
	$groupmember = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUPMEMBER) . " WHERE id = {$id}");
	if (empty($groupmember)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '抱歉，不存在该群成员或是已经被删除！';
		echo json_encode($resarr);
		exit();
	}
	pdo_delete(BEST_GROUPMEMBER, array('id' => $id));
	pdo_delete(BEST_GROUPCONTENT, array('groupid' => $groupmember['groupid'],'openid'=>$groupmember['openid']));
	$resarr['error'] = 0;
	$resarr['msg'] = '删除群成员成功！';
	echo json_encode($resarr);
	exit();
}elseif ($operation == 'deletedu') {
	$id = intval($_GPC['id']);
	$chat = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND id = {$id}");
	if (empty($chat)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '不存在该聊天记录！';
		echo json_encode($resarr);
		exit();
	}
	pdo_delete(BEST_GROUPCONTENT,array('id'=>$id));
	$resarr['error'] = 0;
	$resarr['msg'] = '删除成功！';
	echo json_encode($resarr);
	exit();
}elseif ($operation == 'changenickname') {
	$id = intval($_GPC['id']);
	$groupmember = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND id = {$id}");
	if (empty($groupmember)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '不存在该群成员！';
		echo json_encode($resarr);
		exit();
	}
	$dataup['nickname'] = trim($_GPC['nickname']);
	pdo_update(BEST_GROUPMEMBER,$dataup,array('id'=>$id));
	$resarr['error'] = 0;
	$resarr['msg'] = '修改昵称成功！';
	echo json_encode($resarr);
	exit();
}
?>