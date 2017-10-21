<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

$answer = $this->get_answer($rid);
$answers = iunserializer($answer['answer']);
$fmimage = $this->getpicarr($uniacid,$rid, $tfrom_user,1);
$tbg = $this->getphotos($fmimage['photos'],$this->getname($rid, $tfrom_user, '0' , 'avatar'),  $rbasic['picture']);
//查询自己是否参与活动
if (!empty($from_user)) {
	$mygift = pdo_fetch("SELECT * FROM " . tablename($this -> table_users) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user, ':rid' => $rid));
	$voteer = pdo_fetch("SELECT * FROM " . tablename($this -> table_voteer) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user, ':rid' => $rid));

if ($_GPC['tijiao'] == 1) {
	$answer_id = $_GPC['answer_id'];
	$chose_answer = $_GPC['chose_answer'];
	$right_answer = $_GPC['right_answer'];
	if ($voteer['is_user_chance'] > 0) {
		if ($right_answer == $chose_answer) {
			pdo_update($this->table_voteer, array('chance +=' => $rvote['answer_times_ps'], 'is_user_chance -=' => 1, 'lasttime' => time()), array('rid' => $rid, 'from_user'=>$from_user));//写入答题
			$fmdata = array(
				"success" => 1,
				"msg" => '恭喜你获得'.$rvote['answer_times_ps'].'次投票机会！',
			);
			echo json_encode($fmdata);
			exit;
		}else{
			pdo_update($this->table_voteer, array('is_user_chance -=' => 1, 'lasttime' => time()), array('rid' => $rid, 'from_user'=>$from_user));//写入答题
			$fmdata = array(
				"success" => -1,
				"flag" => 1,
				"msg" => '很遗憾，你选择错了',
			);
			echo json_encode($fmdata);
			exit;
		}
	}else{
		$fmdata = array(
			"success" => -1,
			"flag" => 2,
			"msg" => '您今天已经没有答题机会了！',
		);
		echo json_encode($fmdata);
		exit;

	}
}


$title = '答题获取更多投票机会 ' . $rbasic['title'] . ' ';

$fmimage = $this -> getpicarr($uniacid, $rid, $tfrom_user, 1);
if (!empty($rshare['sharelink'])) {
	$_share['link'] = $rshare['sharelink'];
}else{

	$_share['link'] = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('shareuserview', array('rid' => $rid, 'fromuser' => $from_user, 'tfrom_user' => $from_user));
}

//分享URL
$_share['title'] = $this -> get_share($uniacid, $rid, $from_user, $rshare['sharetitle']);
$_share['content'] = $this -> get_share($uniacid, $rid, $from_user, $rshare['sharecontent']);
$_share['imgUrl'] = tomedia($tbg);
}
$templatename = $rbasic['templates'];
if ($templatename != 'default' && $templatename != 'stylebase') {
	require FM_CORE . 'fmmobile/tp.php';
}
$toye = $this -> templatec($templatename, $_GPC['do']);
include $this -> template($toye);
