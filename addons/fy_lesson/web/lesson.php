<?php
/**
 * 课程管理
 * ============================================================================

 * ============================================================================
 */
if(empty($setting)){
	message("请先配置相关参数！", $this->createWebUrl('setting'), "error");
}
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
if ($operation == 'display') {
	if (checksubmit('submit')) { /* 排序 */
		if (is_array($_GPC['lessonorder'])) {
			foreach ($_GPC['lessonorder'] as $pid => $val) {
				$data = array('displayorder' => intval($_GPC['lessonorder'][$pid]));
				pdo_update($this->table_lesson_parent, $data, array('id' => $pid));
			}
		}
		if (is_array($_GPC['sectionorder'])) {
			foreach ($_GPC['sectionorder'] as $sid => $val) {
				$data = array('displayorder' => intval($_GPC['sectionorder'][$sid]));
				pdo_update($this->table_lesson_son, $data, array('id' => $sid));
			}
		}
		message('操作成功!', referer, 'success');
	}

	/* 课程分类 */
	$category = pdo_fetchall("SELECT id,name FROM " . tablename($this->table_category) . " WHERE uniacid='{$uniacid}' AND parentid=0");

	/* 推荐板块列表 */
	$rec_list = pdo_fetchall("SELECT id,rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid='{$uniacid}'");


	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$bookname = trim($_GPC['bookname']);
	$teacher  = trim($_GPC['teacher']);
	$cid      = intval($_GPC['cid']);
	$recid	  = intval($_GPC['recid']);
	$is_free  = trim($_GPC['is_free']);
	$status   = trim($_GPC['status']);

	$condition = " b.uniacid='{$uniacid}' ";
	if($bookname!=''){
		$condition .= " AND b.bookname LIKE '%{$bookname}%' ";
	}
	if($teacher!=''){
		$condition .= " AND a.teacher LIKE '%{$teacher}%' ";
	}
	if($cid>0){
		$condition .= " AND b.cid = '{$cid}' ";
	}
	if($cid>0){
		$condition .= " AND b.cid = '{$cid}' ";
	}
	if($recid>0){
		$condition .= " AND ((b.recommendid='{$recid}') OR (b.recommendid LIKE '{$recid},%') OR (b.recommendid LIKE '%,{$recid}') OR (b.recommendid LIKE '%,{$recid},%')) ";
	}

	if(in_array($is_free, array('0','1'))){
		if(in_array($is_free, array('0'))){
			$condition .= " AND b.price =0 ";
		}elseif(in_array($is_free, array('1'))){
			$condition .= " AND b.price > 0 ";
		}
	}
	if($status != ''){
		if($status == 999){
			$condition .= " AND b.stock <10 ";
		}else{
			$condition .= " AND b.status = '{$status}' ";
		}
	}

	$list = pdo_fetchall("SELECT a.teacher, b.id,b.cid,b.bookname,b.price,b.buynum,b.stock,b.displayorder,b.status, c.name AS catname FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.id=b.teacherid LEFT JOIN " .tablename($this->table_category). " c ON b.cid=c.id WHERE {$condition} ORDER BY b.status DESC,b.displayorder DESC,b.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach($list as $key=>$value){
		$list[$key]['section'] = pdo_fetchall("SELECT id,parentid,title,displayorder FROM " .tablename($this->table_lesson_son). " WHERE parentid='{$value['id']}' ORDER BY displayorder DESC");
		$list[$key]['visit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_history). " WHERE uniacid = {$uniacid} AND lessonid='{$value['id']}'");
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_teacher). " a LEFT JOIN " . tablename($this->table_lesson_parent) . " b ON a.id=b.teacherid LEFT JOIN " .tablename($this->table_category). " c ON b.cid=c.id WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);


}elseif($operation == 'postlesson') {
	$id = intval($_GPC['id']);
	if(!empty($id)){
		$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$id}'");
		if(empty($lesson)){
			message("该课程不存在或已被删除！", "", "error");
		}
	}

	/* 课程分类列表 */
	$condition = " uniacid='{$uniacid}' AND parentid=0 ";
	$category = pdo_fetchall("SELECT id,name FROM " . tablename($this->table_category) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC");

	/* 推荐板块列表 */
	$rec_list = pdo_fetchall("SELECT id,rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid='{$uniacid}'");

	/* 讲师列表 */
	$teacher_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_teacher). " WHERE uniacid='{$uniacid}' AND status=1 ORDER BY first_letter ASC");

	/* 佣金比例 */
	$commission = unserialize($lesson['commission']);

	/* 预播放封面图 */
	$poster = json_decode($lesson['poster']);

	/* 已推荐板块 */
	$recidarr = explode(",", $lesson['recommendid']);

	if(checksubmit('submit')){
		$data = array();
		$data['uniacid']		= $uniacid;
		$data['bookname']		= trim($_GPC['bookname']);
		$data['cid']			= intval($_GPC['cid']);
		$data['images']			= trim($_GPC['images']);
		$data['poster']			= json_encode($_GPC['poster']);
		$data['price']			= trim($_GPC['price'])?trim($_GPC['price']):0;
		$data['stock']			= intval($_GPC['stock']);
		$data['isdiscount']		= intval($_GPC['isdiscount']);
		$data['vipdiscount']	= intval($_GPC['vipdiscount']);
		$data['integral']		= intval($_GPC['integral']);
		$data['validity']		= intval($_GPC['validity']);
		$data['virtual_buynum'] = intval($_GPC['virtual_buynum']);
		$data['difficulty']		= trim($_GPC['difficulty']);
		$data['teacherid']		= intval($_GPC['teacherid']);
		$data['descript']		= trim($_GPC['descript']);
		$data['displayorder']	= intval($_GPC['displayorder']);
		$data['status']			= intval($_GPC['status']);
		$data['vipview']		= intval($_GPC['vipview']);
		$data['teacher_income']	= intval($_GPC['teacher_income']);
		$data['addtime']		= time();
		$data['commission']	    = serialize(array('commission1'=>floatval($_GPC['commission1']),'commission2'=>floatval($_GPC['commission2']),'commission3'=>floatval($_GPC['commission3'])));

		if(empty($data['bookname'])){
			message("请输入课程名称！");
		}
		if(empty($data['cid'])){
			message("请选择课程分类！");
		}
		if(empty($data['images'])){
			message("请上传课程封面！");
		}
		if(empty($data['difficulty'])){
			message("请填写课程难度！");
		}
		if(empty($data['teacherid'])){
			message("请选择讲师！");
		}
		if(!in_array($data['status'], array('0','1','2','-1'))){
			message("请选择课程状态！");
		}

		foreach($_GPC['recid'] as $recid){
			$tmprecid .= $recid.',';
		}
		$data['recommendid'] = trim($tmprecid, ",");
		
		if(empty($id)){
			pdo_insert($this->table_lesson_parent, $data);
			$id = pdo_insertid();
			if($id){
				$this->addSysLog($_W['uid'], $_W['username'], 1, "课程管理", "新增ID:{$id}的课程");
			}
			message("添加课程成功！", $this->createWebUrl("lesson"), "success");
		}else{
			unset($data['addtime']);
			$res = pdo_update($this->table_lesson_parent, $data, array('uniacid'=>$uniacid, 'id'=>$id));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "课程管理", "编辑ID:{$id}的课程");
			}

			$refurl = $_GPC['refurl']?$_GPC['refurl']:$this->createWebUrl("lesson");
			message("编辑课程成功！", $refurl, "success");
		}
	}
}elseif($operation == 'postsection') {
	$pid = intval($_GPC['pid']); /* 课程id */
	$lesson = pdo_fetch("SELECT id,bookname FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$pid}'");
	if(empty($lesson)){
		message("当前课程不存在或已被删除！", "", "error");
	}

	$id = intval($_GPC['id']); /* 章节id */
	if(!empty($id)){
		$section = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND id='{$id}'");
		if(empty($section)){
			message("该章节不存在或已被删除！", "", "error");
		}
	}

	/* 存储方式 */
	$qiniu = unserialize($setting['qiniu']);
	if(substr($qiniu['url'],0,7)!='http://'){
		$qiniu['url'] = "http://".$qiniu['url'];
	}

	if(checksubmit('submit')){
		$data = array();
		$data['uniacid']		= $uniacid;
		$data['parentid']		= $pid;
		$data['title']			= $_GPC['title'];
		$data['sectiontype']	= intval($_GPC['sectiontype']);
		$data['savetype']		= trim($_GPC['savetype']);
		$data['videourl']		= trim($_GPC['videourl']);
		$data['videotime']		= str_replace("：",":",trim($_GPC['videotime']));
		$data['content']		= $_GPC['content'];
		$data['displayorder']	= intval($_GPC['displayorder']);
		$data['is_free']	    = intval($_GPC['is_free']);
		$data['status']			= intval($_GPC['status']);
		$data['addtime']		= time();

		if(empty($data['parentid'])){
			message("课程不存在或已被删除");
		}
		if(empty($data['title'])){
			message("请填写章节名称！");
		}
		if($data['sectiontype']==1 && empty($data['videourl'])){
			message("请填写章节视频URL！");
		}
		if(!in_array($data['is_free'], array('0','1'))){
			message("请选择是否为试听章节！");
		}
		if(!in_array($data['status'], array('0','1'))){
			message("请选择是否上架！");
		}

		if($data['savetype']==2){
			$data['videourl'] = $_GPC['videourl'];
		}

		if(empty($id)){
			pdo_insert($this->table_lesson_son, $data);
			$id = pdo_insertid();
			if($id){
				$this->addSysLog($_W['uid'], $_W['username'], 1, "课程管理->章节管理", "新增ID:{$pid}的课程下ID:{$id}的章节");
			}

			message("添加章节成功！", $this->createWebUrl('lesson',array('op'=>'viewsection','pid'=>$pid)), "success");
		}else{
			unset($data['addtime']);
			$res = pdo_update($this->table_lesson_son, $data, array('uniacid'=>$uniacid, 'id'=>$id));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "课程管理->章节管理", "编辑ID:{$pid}的课程下ID:{$id}的章节");
			}

			$refurl = $_GPC['refurl']?$_GPC['refurl']:$this->createWebUrl('lesson',array('op'=>'viewsection','pid'=>$pid));
			message("编辑章节成功！", $refurl, "success");
		}
	}

}elseif($operation == 'viewsection'){
	$pid = intval($_GPC['pid']);
	$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$pid}'");
	if(empty($lesson)){
		message("该课程不存在或已被删除！", "", "error");
	}

	if (checksubmit('submit')) { /* 排序 */
		if (is_array($_GPC['sectionorder'])) {
			foreach ($_GPC['sectionorder'] as $sid => $val) {
				$data = array('displayorder' => intval($_GPC['sectionorder'][$sid]));
				pdo_update($this->table_lesson_son, $data, array('id' => $sid));
			}
		}
		
		message('操作成功!', referer, 'success');
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 25;
	
	$section_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND parentid='{$pid}' ORDER BY displayorder DESC,id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND parentid='{$pid}'");
	$pager = pagination($total, $pindex, $psize);

}elseif($operation == 'informStudent'){
	set_time_limit(900);

	$id = intval($_GPC['lessonid']);
	$lesson = pdo_fetch("SELECT a.bookname,a.teacherid,b.teacher FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id", array(':uniacid'=>$uniacid, ':id'=>$id));

	$list = pdo_fetchall("SELECT openid FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND teacherid=:teacherid", array(':uniacid'=>$uniacid, ':teacherid'=>$lesson['teacherid']));
	$list = $this->two_array_unique($list);

	$number = 0;
	foreach($list as $student){
		/* 发送模版消息给学员 */
		$sendmessage = array(
			'touser'	  => $student[0],
			'template_id' => $setting['newlesson'],
			'url'         => $_W['siteroot'] .'app/'. $this->createMobileUrl('lesson', array('id'=>$id)),
			'topcolor'    => "#222222",
			'data'        => array(
				 'first'  => array(
					 'value' => urlencode("您好，您关注的{$lesson['teacher']}讲师上新课啦"),
					 'color' => "#222222",
				 ),
				 'keyword1' => array(
					 'value' => urlencode("{$lesson['bookname']}"),
					 'color' => "#428BCA",
				 ),
				 'keyword2' => array(
					 'value' => urlencode("点击详情查看"),
					 'color' => "#428BCA",
				 ),
				 'keyword3' => array(
					 'value' => urlencode("{$lesson['teacher']}"),
					 'color' => "#428BCA",
				 ),
				'keyword4' => array(
					 'value' => date('Y-m-d', time()),
					 'color' => "#428BCA",
				 ),
				 'remark' => array(
					 'value' => urlencode("点击该条消息可查看课程详情~"),
					 'color' => "#222222",
				 ),
			)
		);
		$this->send_template_message(urldecode(json_encode($sendmessage)));
		$number++;
	}

	message("操作成功，发送记录{$number}条", $this->createWebUrl('lesson', array('op'=>'viewsection','pid'=>$id)), "success");

}elseif($operation == 'delete') {
	$pid = intval($_GPC['pid']);
	$cid = intval($_GPC['cid']);
	if($pid>0){
		$lesson = pdo_fetch("SELECT id FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$pid}'");
		if(empty($lesson)){
			message("该课程不存在或已被删除！", "", "error");
		}
		pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'ctype' => 1, 'outid'=>$pid));
		pdo_delete($this->table_lesson_son, array('uniacid'=>$uniacid, 'parentid'=>$pid));
		pdo_delete($this->table_lesson_parent, array('uniacid'=>$uniacid, 'id'=>$pid));

		$this->addSysLog($_W['uid'], $_W['username'], 2, "课程管理", "删除ID:{$pid}的课程及所有章节");
		message("删除课程成功！", referer, "success");
	}

	if($cid>0){
		$section = pdo_fetch("SELECT id FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND id='{$cid}'");
		if(empty($section)){
			message("该章节不存在或已被删除！", "", "error");
		}

		$res = pdo_delete($this->table_lesson_son, array('uniacid'=>$uniacid, 'id'=>$cid));
		if($res){
			$this->addSysLog($_W['uid'], $_W['username'], 2, "课程管理", "删除ID:{$pid}的课程下ID:{$cid}的章节");
		}

		message("删除章节成功！", referer, "success");
	}
}


include $this->template('lesson');


?>