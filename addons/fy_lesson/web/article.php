<?php
/**
 * 文章公告管理
 */
if ($operation == 'display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " uniacid='{$uniacid}' ";
	if (!empty($_GPC['title'])) {
		$condition .= " AND title LIKE '%{$_GPC['title']}%' ";
	}
	if (!empty($_GPC['author'])) {
		$condition .= " AND author LIKE '%{$_GPC['author']}%' ";
	}
	if ($_GPC['isshow']!='') {
		$condition .= " AND isshow='{$_GPC['isshow']}' ";
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

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_article). " WHERE {$condition} ORDER BY displayorder DESC, id DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_article). " WHERE {$condition}");
	$pager = pagination($total, $pindex, $psize);

}elseif ($operation == 'post'){
	$aid = intval($_GPC['aid']);
	if($aid>0){
		$article = pdo_fetch("SELECT * FROM " .tablename($this->table_article). " WHERE uniacid='{$uniacid}' AND id='{$aid}'");
		if(empty($article)){
			message("该文章公告不存在！", "", "error");
		}
	}

	if(checksubmit('submit')){
		$data = array(
			'uniacid'		=> $uniacid,
			'title'			=> trim($_GPC['title']),
			'author'		=> trim($_GPC['author']),
			'content'		=> $_GPC['content'],
			'linkurl'		=> trim($_GPC['linkurl']),
			'images'		=> $_GPC['images'],
			'isshow'		=> intval($_GPC['isshow']),
			'displayorder'  => intval($_GPC['displayorder']),
		);

		if($aid>0){
			$res = pdo_update($this->table_article, $data, array('uniacid'=>$uniacid, 'id'=>$aid));
			if($res){
				$this->addSysLog($_W['uid'], $_W['username'], 3, "文章公告", "编辑ID:{$aid}的文章公告");
			}
			message("更新成功", $this->createWebUrl('article'), "success");
		}else{
			$data['addtime'] = time();
			pdo_insert($this->table_article, $data);
			$id = pdo_insertid();

			if($id>0){
				$this->addSysLog($_W['uid'], $_W['username'], 1, "文章公告", "新增ID:{$id}的文章公告");
				
			}
			message("添加成功", $this->createWebUrl('article'), "success");
		}
	}
	
}else if ($operation == 'delete'){
	$aid = intval($_GPC['aid']);
	if($aid>0){
		$article = pdo_fetch("SELECT * FROM " .tablename($this->table_article). " WHERE uniacid='{$uniacid}' AND id='{$aid}'");
		if(empty($article)){
			message("该文章公告不存在！", "", "error");
		}
	}

	$res = pdo_delete($this->table_article, array('uniacid'=>$uniacid, 'id'=>$aid));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "文章公告", "删除ID:{$aid}的文章公告");
	}
	message("删除成功", $this->createWebUrl('article'), "success");
}

include $this->template('article');

?>