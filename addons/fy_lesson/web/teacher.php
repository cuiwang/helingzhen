<?php
/**
 * 讲师管理
 */
 
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
if ($operation == 'display') {
	
	$condition = " uniacid='{$uniacid}' ";
	$teacher = $_GPC['teacher'];
	$letter  = $_GPC['letter'];
	$status  = $_GPC['status'];
	$teachertype  = $_GPC['teachertype'];

	if(!empty($teacher)){
		$condition .= " AND teacher LIKE '%{$teacher}%'";
	}
	if(!empty($letter)){
		$condition .= " AND first_letter LIKE '%{$letter}%'";
	}
	if($status!=''){
		$condition .= " AND status='{$status}'";
	}
	if($teachertype==1){
		$condition .= " AND uid =0 ";
	}elseif($teachertype==2){
		$condition .= " AND uid!=0";
	}
	

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_teacher) . " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach($list as $key=>$value){
		$list[$key]['member'] = pdo_fetch("SELECT nopay_lesson,pay_lesson FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND openid='{$value['openid']}'");
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_teacher) . " WHERE {$condition} ");
	$pager = pagination($total, $pindex, $psize);

}elseif($operation == 'post') {
	$id = intval($_GPC['id']); /* 当前讲师id */
	$letter = array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","T","U","V","W","X","Y","Z");

	if (!empty($id)) {
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teacher) . " WHERE uniacid = '$uniacid' AND id = '$id'");
		if(empty($teacher)){
			message("该讲师不存在或已被删除！", "", "error");
		}
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['teacher'])) {
			message("抱歉，请输入讲师名称！");
		}
		if (empty($_GPC['first_letter'])) {
			message("抱歉，请输入首拼音字母！");
		}
		if (empty($_GPC['status'])) {
			message("抱歉，请选择讲师状态！");
		}

		$data = array(
			'uniacid'      => $_W['uniacid'],
			'teacher'      => trim($_GPC['teacher']),
			'qq'		   => trim($_GPC['qq']),
			'qqgroup'      => trim($_GPC['qqgroup']),
			'qqgroupLink'  => trim($_GPC['qqgroupLink']),
			'weixin_qrcode'=> trim($_GPC['weixin_qrcode']),
			'first_letter' => trim($_GPC['first_letter']),
			'teacherdes'   => trim($_GPC['teacherdes']),
			'teacherphoto' => trim($_GPC['teacherphoto']),
			'status'	   => intval($_GPC['status']),
			'upload'	   => intval($_GPC['upload']),
			'addtime'      => time(),
		);

		$isexist = pdo_fetch("SELECT id FROM " .tablename($this->table_teacher). " WHERE uniacid='{$uniacid}' AND teacher='{$data['teacher']}' LIMIT 1");

		if (!empty($id)) {
			unset($data['addtime']);
			if($isexist && $id!=$isexist['id']){
				message("抱歉，该讲师名称已存在！");
			}
			$res = pdo_update($this->table_teacher, $data, array('id' => $id));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "讲师管理", "编辑ID:{$id}的讲师");
			}
		} else {
			if(!empty($isexist)){
				message("抱歉，该讲师已存在！");
			}
			pdo_insert($this->table_teacher, $data);
			$id = pdo_insertid();
			if($id){
				$this->addSysLog($_W['uid'], $_W['username'], 1, "讲师管理", "新增ID:{$id}的讲师");
			}
		}
		message("更新讲师成功！", $this->createWebUrl('teacher', array('op' => 'display')), "success");
	}

}elseif ($operation == 'income') {
	$condition = " uniacid='{$uniacid}' ";
	$teacher = $_GPC['teacher'];
	$lesson  = $_GPC['lesson'];
	$ordersn = $_GPC['ordersn'];
	if(!empty($teacher)){
		$condition .= " AND teacher LIKE '%{$teacher}%'";
	}
	if(!empty($lesson)){
		$condition .= " AND bookname LIKE '%{$lesson}%'";
	}
	if($ordersn!=''){
		$condition .= " AND ordersn='{$ordersn}'";
	}

	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
		$condition .= " AND addtime >= '{$starttime}' AND addtime <= '{$endtime}' ";
	}
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher_income). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_teacher_income). " WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

	/* 导出excel表格 */
	if($_GPC['export']==1){
		$i = 0;
		foreach ($list as $key => $value) {
			$arr[$i]['id']				= $value['id'];
			$arr[$i]['teacher']         = $value['teacher'];
			$arr[$i]['openid']          = $value['openid'];
			$arr[$i]['ordersn']         = $value['ordersn'];
			$arr[$i]['bookname']        = $value['bookname'];
			$arr[$i]['orderprice']      = $value['orderprice']."元";
			$arr[$i]['teacher_income']  = $value['teacher_income']."%";
			$arr[$i]['income_amount']   = $value['income_amount']."元";
			$arr[$i]['addtime']         = date('Y-m-d H:i:s', $value['addtime']);
			$i++;
		}
	 
		$this->exportexcel($arr, array('ID', '讲师名称','讲师openid', '订单编号', '课程名称', '课程售价', '讲师分成', '讲师收入', '添加时间'), "讲师收入明细-".date("Y-m-d",time()));
		exit();
	}
	
}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$teacher = pdo_fetch("SELECT id FROM " . tablename($this->table_teacher) . " WHERE uniacid = '$uniacid' AND id = '{$id}'");
	if (empty($teacher)) {
		message("抱歉，讲师不存在或是已经被删除！", $this->createWebUrl('teacher', array('op' => 'display')), "error");
	}

	$lesson = pdo_fetchall("SELECT id FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND teacherid='{$id}'");
	if($lesson){
		message("该讲师还存在课程，请删除或转移课程后重试！", "", "error");
	}

	pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'ctype' => 2, 'outid'=>$id));
	$res = pdo_delete($this->table_teacher, array('uniacid'=>$uniacid,'id' => $id));

	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "讲师管理", "删除ID:{$id}的讲师");
	}
	message("删除讲师成功！", $this->createWebUrl('teacher', array('op' => 'display')), "success");
}

include $this->template('teacher');


?>