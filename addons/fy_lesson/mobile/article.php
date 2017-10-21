<?php
/**
 * 文章公告
 */
 
/* 课程定的那id */
$aid = intval($_GPC['aid']);
$uid = intval($_GPC['uid']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('article', array('aid'=>$aid,'uid'=>$uid));
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

/* 更新微课程会员信息 */
$this->updatelessonmember($openid,$uid);

/* 基本设置 */
$setting = pdo_fetch("SELECT sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

load()->model('mc');
$shareuid = mc_openid2uid($openid);

if($op=='display'){
	$aid = intval($_GPC['aid']);
	$article = pdo_fetch("SELECT * FROM " .tablename($this->table_article). " WHERE uniacid='{$uniacid}' AND id='{$aid}'");
	if(empty($article)){
		message("该文章公告不存在！", "", "error");
	}
	$title = $article['title'];

	/* 增加访问量 */
	pdo_update($this->table_article, array('view'=>$article['view']+1), array('uniacid'=>$uniacid,'id'=>$aid));

	/* 分享设置 */
	$sharelink = unserialize($setting['sharelink']);
	$article['desc'] = substr(strip_tags(htmlspecialchars_decode($article['content'])), 0, 240);
	$article['images'] = $article['images']?$article['images']:$sharelink['images'];
	$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('article', array('aid'=>$aid,'uid'=>$shareuid));

}elseif($op=='list'){
	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_article) . " WHERE uniacid='{$uniacid}' ORDER BY displayorder DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize);
	foreach($list as $key=>$value){
		$list[$key]['addtime'] = date("Y-m-d H:i:s", $value['addtime']);
	}

	if($_GPC['method']=='ajaxgetlist'){
		echo json_encode($list);
	}

	/* 分享设置 */
	$sharelink = unserialize($setting['sharelink']);
	$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$shareuid));
}

if($_W['isajax']!='true'){
	include $this->template('article');
}

?>