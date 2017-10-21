<?php
/**
 * 推荐板块管理
 */
 
if ($operation == 'display') {
	if (checksubmit('submit')) { /* 排序 */
		if (is_array($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $key => $val) {
				$data = array('displayorder' => intval($_GPC['displayorder'][$key]));
				pdo_update($this->table_recommend, $data, array('id' => $key));
			}
		}
		message("操作成功!",$this->createWebUrl('recommend'),"success");
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	
	$condition = " uniacid='{$uniacid}' ";
	$recommend = pdo_fetchall("SELECT * FROM " . tablename($this->table_recommend) . " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_recommend) . " WHERE {$condition} ");
	$pager = pagination($total, $pindex, $psize);

}elseif($operation == 'post') {
	$id = intval($_GPC['id']); /* 当前板块id */

	if (!empty($id)) {
		$recommend = pdo_fetch("SELECT * FROM " . tablename($this->table_recommend) . " WHERE uniacid = '{$uniacid}' AND id = '$id'");
		if(empty($recommend)){
			message("该板块不存在或已被删除！", "", "error");
		}
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['rec_name'])) {
			message("抱歉，请输入板块名称！");
		}

		$data = array(
			'uniacid'      => $_W['uniacid'],
			'rec_name'	   => trim($_GPC['rec_name']),
			'displayorder' => intval($_GPC['displayorder']),
			'is_show'      => intval($_GPC['is_show']),
			'addtime'	   => time(),
		);

		if (!empty($id)) {
			unset($data['addtime']);
			$res = pdo_update($this->table_recommend, $data, array('id' => $id));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 1, "推荐板块", "新增ID:{$id}的课程推荐板块");
			}
		} else {
			pdo_insert($this->table_recommend, $data);
			$id = pdo_insertid();
			if($id){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "推荐板块", "编辑ID:{$id}的课程推荐板块");
			}
		}
		message("更新板块成功！", $this->createWebUrl('recommend', array('op' => 'display')), "success");
	}

}elseif ($operation == 'details') {
	$id = intval($_GPC['recid']);
	$recommend = pdo_fetch("SELECT id,rec_name FROM " . tablename($this->table_recommend) . " WHERE uniacid = '{$uniacid}' AND id = '{$id}'");
	if (empty($recommend)) {
		message("抱歉，板块不存在或是已经被删除！", $this->createWebUrl('recommend', array('op' => 'display')), "error");
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	
	$condition = " b.uniacid='{$uniacid}' AND ((b.recommendid='{$id}') OR (b.recommendid LIKE '{$id},%') OR (b.recommendid LIKE '%,{$id}') OR (b.recommendid LIKE '%,{$id},%')) ";

	$list = pdo_fetchall("SELECT a.teacher, b.id,b.bookname,b.price,b.status FROM " .tablename($this->table_teacher). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.id=b.teacherid LEFT JOIN " .tablename($this->table_recommend). " c ON b.recommendid=c.id WHERE {$condition} ORDER BY b.displayorder DESC, b.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " b LEFT JOIN " .tablename($this->table_recommend). " c ON b.recommendid=c.id WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

	/* 批量取消推荐课程 */
	if($_GPC['cancleRec']==1){
		$idarr = $_GPC['id'];
		$recid = intval($_GPC['recid']);

		if(is_array($idarr) && !empty($idarr)){
			foreach($idarr as $value){
				$lesson = pdo_fetch("SELECT recommendid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$value));
				$recdata = array('recommendid'=>trim(str_replace($recid,"",$lesson['recommendid']),","));
				pdo_update($this->table_lesson_parent, $recdata, array('id'=>$value));
			}
			message("批量取消课程成功！", referer, "success");
		}
	}

}elseif ($operation == 'removerec') {
	$id = intval($_GPC['id']);
	$lesson = pdo_fetch("SELECT recommendid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$id}'");

	if(empty($lesson)){
		message("该课程不存在或已被删除！", "", "error");
	}

	pdo_update($this->table_lesson_parent, array('recommendid'=>0), array('uniacid'=>$uniacid, 'id'=>$id));

	message("移除课程成功！", referer, "success");

}elseif ($operation == 'addtorec') {
	/* 推荐板块列表 */
	$rec_list = pdo_fetchall("SELECT id,rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid = '{$uniacid}'");
	/* 课程分类列表 */
	$category_list = pdo_fetchall("SELECT id,name FROM " .tablename($this->table_category). " WHERE uniacid = '{$uniacid}'");

	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;

	$bookname = trim($_GPC['bookname']);
	$cid	  = intval($_GPC['cid']);
	$recid	  = trim($_GPC['recid']);
	$is_free  = trim($_GPC['is_free']);

	$condition = " uniacid = '{$uniacid}' ";
	if(!empty($bookname)){
		$condition .= " AND bookname LIKE '%{$bookname}%' ";
	}
	if($cid>0){
		$condition .= " AND cid='{$cid}' ";
	}
	if($recid=='norec'){
		$condition .= " AND recommendid=0 ";
	}else{
		if(intval($recid)>0){
			$condition .= " AND ((recommendid='{$recid}') OR (recommendid LIKE '{$recid},%') OR (recommendid LIKE '%,{$recid}') OR (recommendid LIKE '%,{$recid},%')) ";
		}
	}
	if(in_array($is_free, array('0','1'))){
		if(in_array($is_free, array('0'))){
			$condition .= " AND price =0 ";
		}elseif(in_array($is_free, array('1'))){
			$condition .= " AND price > 0 ";
		}
	}
	
	$lesson_list = pdo_fetchall("SELECT id,bookname,price,recommendid,status,addtime FROM" .tablename($this->table_lesson_parent). " WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach($lesson_list as $key=>$value){
		$recidarr = explode(",", $value['recommendid']);
		foreach($recidarr as $rid){
			$tmp_rec = pdo_fetch("SELECT rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid='{$uniacid}' AND id='{$rid}'");
			$rec_name .= $tmp_rec['rec_name']."<br/>";
		}
		$lesson_list[$key]['rec_name'] = trim($rec_name,"<br/>");
		unset($rec_name);
	}
	
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

}elseif ($operation == 'recpost') {
	$idarr = $_GPC['id'];
	$recid = intval($_GPC['recid']);
	$posttype = trim($_GPC['posttype']);

	if(is_array($idarr) && !empty($idarr)){
		foreach($idarr as $value){
			$lesson = pdo_fetch("SELECT recommendid FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$value}'");
			if($posttype=='cancel'){
				$recdata = array('recommendid'=>0);
			}else{
				if(!empty($lesson['recommendid'])){
					$recdata = array('recommendid'=>$lesson['recommendid'].','.$recid);
				}else{
					$recdata = array('recommendid'=>$recid);
				}
			}
			pdo_update($this->table_lesson_parent, $recdata, array('id'=>$value));
		}

		if($posttype=='cancel'){
			$succword = "批量取消课程成功！";
		}else{
			$succword = "批量推荐课程成功！";
		}

		message($succword, referer, "success");

	}else{
		message("参数错误，系统已自动修复，请重试！", referer, "error");
	}

}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$recommend = pdo_fetch("SELECT id FROM " . tablename($this->table_recommend) . " WHERE uniacid = '{$uniacid}' AND id = '{$id}'");
	if (empty($recommend)) {
		message("抱歉，板块不存在或是已经被删除！", $this->createWebUrl('recommend', array('op' => 'display')), "error");
	}

	$res = pdo_delete($this->table_recommend, array('uniacid' => $uniacid, 'id' => $id));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "推荐板块", "删除ID:{$id}的课程推荐板块");
	}

	message("板块删除成功！", $this->createWebUrl('recommend', array('op' => 'display')), "success");
}

include $this->template('recommend');

?>