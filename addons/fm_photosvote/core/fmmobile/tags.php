<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
if ($rdisplay['ipannounce'] == 1) {
	$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this->table_announce) . " WHERE uniacid= '{$uniacid}' AND rid= '{$rid}' ORDER BY id DESC");
}

//赞助商
if ($rdisplay['isdes'] == 1) {
	$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this->table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND uniacid= '{$uniacid}'  AND rid= '{$rid}' ORDER BY displayorder ASC");
}
if(!empty($from_user)) {
    $mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));

}

$tagid = $_GPC['tagid'];
$ptag = $_GPC['tagpid'];
$tagtid = $_GPC['tagtid'];
$tagname = $this->gettagname($tagid,$ptag,$tagtid,$rid);
$tags = pdo_fetchall("SELECT id,parentid,title FROM ".tablename($this->table_tags)." WHERE rid = '{$rid}' ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
$parent = array();
$children = array();

if (!empty($tags)) {
	$children = '';
	foreach ($tags as $cid => $cate) {
		$cate['name'] = $cate['title'];
		if (!empty($cate['parentid'])) {
			$children[$cate['parentid']][] = $cate;
		} else {
			$parent[$cate['id']] = $cate;
		}
	}
}


if (!empty($rbody)) {
	$rbody_tags = iunserializer($rbody['rbody_tags']);
}
$title = $rbasic['title'];

if (!empty($rshare['sharelink'])) {
	$_share['link'] = $rshare['sharelink'];
}else{
$_share['link'] = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'fromuser' => $from_user));//分享URL
}
 $_share['title'] = $this->get_share($uniacid,$rid,$from_user,$rshare['sharetitle']);
$_share['content'] =  $this->get_share($uniacid,$rid,$from_user,$rshare['sharecontent']);
$_share['imgUrl'] = toimage($rshare['sharephoto']);


$templatename = $rbasic['templates'];
$toye = $this->templatec($templatename,$_GPC['do']);
include $this->template($toye);
