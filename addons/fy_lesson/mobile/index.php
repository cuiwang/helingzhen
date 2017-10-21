<?php
/**
 * 微课堂首页
 */

$uid = intval($_GPC['uid']);
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));
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
$setting = pdo_fetch("SELECT banner,logo,sitename,sharelink,footnav,lessonshow,copyright,isfollow,qrcode,front_color,stock_config,teacherlist,category_ico,index_lazyload FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 延迟加载 */
$lazyload = unserialize($setting['index_lazyload']);
$lazyload_image = $lazyload['lazyload_image']?$_W['attachurl'].$lazyload['lazyload_image']:MODULE_URL."template/mobile/images/grey.gif";

/* 粉丝信息 */
$fans = pdo_fetch("SELECT follow FROM " .tablename('mc_mapping_fans'). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");

/* 分享设置 */
load()->model('mc');
$shareuid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$shareuid));

/* 焦点图 */
$banner = unserialize($setting['banner']);

/* 文章公告 */
$articlelist = pdo_fetchall("SELECT id,title,addtime FROM " .tablename($this->table_article). " WHERE uniacid='{$uniacid}'  AND isshow=1 ORDER BY displayorder DESC,id DESC");

/* 课程分类 */
$category_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_category). " WHERE uniacid='{$uniacid}' ORDER BY displayorder DESC LIMIT 9");
$allCategoryIco = $setting['category_ico']?$_W['attachurl'].$setting['category_ico']:MODULE_URL.'template/mobile/images/ico-allcategory.png';

/* 板块课程 */
$list = pdo_fetchall("SELECT id AS recid,rec_name FROM " .tablename($this->table_recommend). " WHERE uniacid='{$uniacid}' AND is_show=1 ORDER BY displayorder DESC,id DESC");
foreach($list as $key=>$rec){
	$list[$key]['lesson'] = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND status=1 AND (recommendid='{$rec['recid']}' OR (recommendid LIKE '{$rec['recid']},%') OR (recommendid LIKE '%,{$rec['recid']}') OR (recommendid LIKE '%,{$rec['recid']},%')) ORDER BY displayorder DESC, id DESC");
	foreach($list[$key]['lesson'] as $k=>$val){
		$list[$key]['lesson'][$k]['count'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE parentid='{$val['id']}' ");
		$list[$key]['lesson'][$k]['evaluate'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_evaluate) . " WHERE lessonid='{$val['id']}' ");
		$list[$key]['lesson'][$k]['visit'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_history) . " WHERE lessonid='{$val['id']}' ");
	}
	if(empty($list[$key]['lesson'])){
		unset($list[$key]);
	}

}

include $this->template('index');


?>