<?php
/**
* 女神来了模块定义
*
* @author 微赞科技
* @url http://bbs.012wz.com/
*/
defined('IN_IA') or exit('Access Denied');

//赞助商
if ($rdisplay['isindex'] == 1) {
	$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this->table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND rid= '{$rid}' ORDER BY displayorder ASC");
}

$user = pdo_fetch('SELECT * FROM '.tablename($this->table_users).' WHERE rid = :rid AND from_user = :from_user ORDER BY `id` DESC LIMIT 1', array(':rid' => $rid,':from_user' => $tfrom_user));
if (empty($user)) {
	echo "未找到该用户";
	exit;
}
$user['description'] = $this->emotion($user['description']);
$level = $this->fmvipleavel($rid, $uniacid, $user['from_user']);
$picarrs =  pdo_fetchall("SELECT id, photos,from_user FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user AND rid = :rid ORDER BY isfm DESC", array(':from_user' => $user['from_user'],':rid' => $rid));
//评论开始
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$m = ($pindex-1) * $psize+1;
	//取得用户列表
	//$where .= " AND status = '1'";


	$comments = pdo_fetchall('SELECT * FROM '.tablename($this->table_bbsreply).' WHERE rid = :rid AND tfrom_user = :tfrom_user AND is_del = 0 AND status = 1 ORDER BY `createtime` ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(':rid' => $rid,':tfrom_user' => $tfrom_user));
	$total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename($this->table_bbsreply).' WHERE rid = :rid AND tfrom_user = :tfrom_user AND is_del = 0 AND status = 1 ', array(':rid' => $rid,':tfrom_user' => $tfrom_user));
	$total_pages = ceil($total/$psize);
	$pager = paginationm($total, $pindex, $psize, '', array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));

	$zan = pdo_fetch('SELECT zan FROM '.tablename($this->table_bbsreply).' WHERE rid = :rid AND tfrom_user = :tfrom_user AND from_user = :from_user AND zan = 1 AND is_del = 0 AND status = 1  LIMIT 1' , array(':rid' => $rid,':tfrom_user' => $tfrom_user,':from_user' => $from_user));
//回复数
$tcommentnum = $this->getcommentnum($rid, $uniacid,$tfrom_user);
//$reply['sharetitle'] = $this->get_share($uniacid,$rid,$from_user,$reply['sharetitle']);
//$reply['sharecontent'] = $this->get_share($uniacid,$rid,$from_user,$reply['sharecontent']);

//整理数据进行页面显示
$regurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('reg', array('rid' => $rid));//关注或借用直接注册页
$title = $rbasic['title'] . ' ';

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

