<?php
/**
 * 评价课程订单
 */
 
/* 课程定的那id */
$orderid = intval($_GPC['orderid']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('evaluate', array('orderid'=>$orderid));
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
$setting = pdo_fetch("SELECT sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$fansinfo = mc_fansinfo($openid, $_W['acid'], $_W['uniacid']);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$fansinfo['uid']));

$title = "评价订单";

if($op=='display'){
	$order = pdo_fetch("SELECT a.id,a.ordersn,a.uid,a.lessonid,a.status, b.bookname,b.images,b.price, c.nickname FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename($this->table_lesson_parent). " b ON a.lessonid=b.id LEFT JOIN " .tablename("mc_members"). " c ON a.uid=c.uid WHERE a.uniacid='{$uniacid}' AND a.id='{$orderid}' AND a.openid='{$openid}'");
	if(empty($order)){
		message("该订单不存在或已被删除！", "", "error");
	}

	if($order['status']==2){
		message("该订单已评价！", $this->createMobileUrl('mylesson'), "warning");
	}

	/* 提交评价 */
	if(checksubmit('submit')){
		$grade   = intval($_GPC['grade']);
		$content = $_GPC['content']?trim($_GPC['content']):'好评';

		$evaluate = array(
			'uniacid'  => $uniacid,
			'orderid'  => $orderid,
			'ordersn'  => $order['ordersn'],
			'lessonid' => $order['lessonid'],
			'bookname' => $order['bookname'],
			'teacherid'=> $order['teacherid'],
			'openid'   => $openid,
			'uid'      => $order['uid'],
			'nickname' => $order['nickname'],
			'grade'    => $grade,
			'content'  => $content,
			'addtime'  => time(),
		);
		$result = pdo_insert($this->table_evaluate, $evaluate);
		if($result){
			/* 更新订单状态 */
			pdo_update($this->table_order, array('status'=>2), array('id'=>$order['id']));

			/* 课程总评论数 */
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND lessonid='{$order['lessonid']}'");
			/* 课程好评数 */
			$good = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND lessonid='{$order['lessonid']}' AND grade=1");
			/* 更新课程好评率 */
			pdo_update($this->table_lesson_parent, array('score'=>round($good/$total,2)), array('id'=>$order['lessonid']));

			message("评价成功！", $this->createMobileUrl('mylesson'), "success");
		}else{
			message("评价失败！", $this->createMobileUrl('mylesson'), "error");
		}
	}

}elseif($op=='freeorder'){
	$lessonid = intval($_GPC['lessonid']);
	$lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND id='{$lessonid}' LIMIT 1");
	if(empty($lesson)){
		message("该课程不存在或已被删除！", "", "error");
	}

	$already_evaluate = pdo_fetch("SELECT id FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lessonid='{$lessonid}' AND orderid=0 ");
	if(!empty($already_evaluate)){
		message("您已评价该课程，无需重复评价！", "", "warning");
	}

	$order = array(
		'images'   => $lesson['images'],
		'bookname' => $lesson['bookname'],
		'price'    => $lesson['price'],
		'uid'	   => $fansinfo['uid'],
		'nickname' => $fansinfo['nickname'],
	);

	/* 提交评价 */
	if(checksubmit('submit')){
		$grade   = intval($_GPC['grade']);
		$content = $_GPC['content']?trim($_GPC['content']):'好评';

		$evaluate = array(
			'uniacid'  => $uniacid,
			'orderid'  => '',
			'ordersn'  => '',
			'lessonid' => $lessonid,
			'bookname' => $order['bookname'],
			'teacherid'=> $lesson['teacherid'],
			'openid'   => $openid,
			'uid'      => $order['uid'],
			'nickname' => $order['nickname'],
			'grade'    => $grade,
			'content'  => $content,
			'addtime'  => time(),
		);
		$result = pdo_insert($this->table_evaluate, $evaluate);
		if($result){
			/* 课程总评论数 */
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND lessonid='{$lessonid}'");
			/* 课程好评数 */
			$good = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND lessonid='{$lessonid}' AND grade=1");
			/* 更新课程好评率 */
			pdo_update($this->table_lesson_parent, array('score'=>round($good/$total,2)), array('id'=>$lessonid));

			message("评价成功！", $this->createMobileUrl('lesson',array('id'=>$lessonid)), "success");
		}else{
			message("评价失败！", $this->createMobileUrl('lesson',array('id'=>$lessonid)), "error");
		}
	}
}

include $this->template('evaluate');

?>