<?php
/**
 * 收藏课程或讲师
 */
//基本设置
$setting = pdo_fetch("SELECT sitename,footnav,copyright,front_color FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

$ctype = trim($_GPC['ctype']);
$id = intval($_GPC['id']);
$openid = trim($_GPC['openid']);

load()->model('mc');
$uid = mc_openid2uid($openid);

if($ctype=='lesson'){
	$collect = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_collect). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND outid='{$id}' AND ctype=1 LIMIT 1");
	if(empty($collect)){
		$insertdata = array(
			'uniacid' => $uniacid,
			'uid'	  => $uid,
			'openid'  => $openid,
			'outid'   => $id,
			'ctype'   => 1,
			'addtime' => time(),
		);
		pdo_insert($this->table_lesson_collect, $insertdata);
		echo '1';
	}else{
		pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'openid'=>$openid,'outid'=>$id,'ctype'=>1));
		echo '2';
	}

}elseif($ctype=='teacher'){
	$collect = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_collect). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND outid='{$id}' AND ctype=2 LIMIT 1");
	if(empty($collect)){
		$insertdata = array(
			'uniacid' => $uniacid,
			'uid'	  => $uid,
			'openid'  => $openid,
			'outid'   => $id,
			'ctype'   => 2,
			'addtime' => time(),
		);
		pdo_insert($this->table_lesson_collect, $insertdata);
		echo '1';
	}else{
		pdo_delete($this->table_lesson_collect, array('uniacid'=>$uniacid,'openid'=>$openid,'outid'=>$id,'ctype'=>2));
		echo '2';
	}

}


?>