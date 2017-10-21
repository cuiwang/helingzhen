<?php
/**
 * 记录播放章节
 */

$lessonid  = intval($_GPC['lessonid']);
$sectionid = intval($_GPC['sectionid']);
$openid	   = trim($_GPC['openid']);

load()->model('mc');
$uid = mc_openid2uid($openid);

$record = pdo_fetch("SELECT * FROM " .tablename($this->table_playrecord). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lessonid='{$lessonid}' LIMIT 1");
$data = array(
	'uniacid'	 => $uniacid,
	'uid'		 => $uid,
	'openid'	 => $openid,
	'lessonid'   => $lessonid,
	'sectionid'  => $sectionid,
	'addtime'	 => time(),
);
if(empty($record)){
	pdo_insert($this->table_playrecord, $data);
}else{
	pdo_update($this->table_playrecord, $data, array('uniacid'=>$uniacid,'openid'=>$openid,'lessonid'=>$lessonid));
}

?>