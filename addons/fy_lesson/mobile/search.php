<?php
/**
 * 微课堂搜索页
 */
  
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('search');
if (isset($_COOKIE[$this->_auth2_openid])) {
	$openid = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$openid = $userinfo["openid"];
			$nickname = $userinfo["nickname"];
			$headimgurl = $userinfo["headimgurl"];
		} else {
			message('授权失败!');
		}
	} else {
		if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
	}
}

/* 基本设置 */
$setting = pdo_fetch("SELECT logo,stock_config,sitename,sharelink,footnav,copyright,front_color,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));


/* 分享设置 */
load()->model('mc');
$shareuid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$shareuid));

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

/* 课程分类 */
$categorylist = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE uniacid='{$uniacid}' AND parentid=0 ORDER BY displayorder DESC");

if($op=='display'){
	$keyword = trim($_GPC['keyword']);
	$cid     = intval($_GPC['cid']);
	$soft    = trim($_GPC['soft']);

	if(!empty($keyword)){
		$title = '"'.$keyword.'"搜索结果';
	}else{
		$title = '搜索';
	}

	$condition = " a.uniacid = '{$uniacid}' AND a.status=1 ";
	$order = " ORDER BY a.displayorder DESC, a.id DESC ";

	if(!empty($keyword)){
		$condition .= " AND (a.bookname LIKE '%{$keyword}%' OR b.teacher LIKE '%{$keyword}%') ";
	}
	if($cid>0){
		$condition .= " AND a.cid='{$cid}'";
		$nowcat = pdo_fetch("SELECT name FROM " . tablename($this->table_category) . " WHERE uniacid='{$uniacid}' AND id='{$cid}'");
		$catname = $nowcat['name'];
	}else{
		$catname = '全部分类';
	}

	if($soft=='free'){
		$condition .= " AND a.price=0";
		$softname = '免费课程';
	}elseif($soft=='price'){
		$order = " ORDER BY a.price ASC, a.displayorder DESC ";
		$condition .= " AND a.price>0";
		$softname = '价格优先';
	}elseif($soft=='hot'){
		$order = " ORDER BY (a.buynum+a.virtual_buynum) DESC, a.displayorder DESC ";
		$softname = '销量优先';
	}elseif($soft=='score'){
		$order = " ORDER BY a.score DESC, a.displayorder DESC ";
		$softname = '好评优先';
	}else{
		$softname = '默认排序';
	}

	$list = pdo_fetchall("SELECT a.* FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} {$order} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	foreach($list as $key=>$value){
		$list[$key]['soncount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE uniacid = '{$uniacid}' AND parentid = '{$value['id']}'");
		$list[$key]['evaluate'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_evaluate) . " WHERE lessonid='{$value['id']}' ");
		$list[$key]['visit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_history) . " WHERE lessonid='{$value['id']}' ");
	}

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} ");
	$pager = $this->mpagination($total, $pindex, $psize);

}elseif($op=='allcategory'){
}



include $this->template('search');

?>