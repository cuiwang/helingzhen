<?php
/**
 * 我的课程
 * ============================================================================
 */

$status = trim($_GPC['status']);
$uid = intval($_GPC['uid']);
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('mylesson', array('status'=>$status,'uid'=>$uid));
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
$setting = pdo_fetch("SELECT sitename,sharelink,footnav,copyright,autogood,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$shareuid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$shareuid));


$title = '我的课程';
$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$condition = " b.uniacid='{$uniacid}' AND b.openid='{$openid}' ";
if($status=='0'){
	$condition .= " AND b.status=0 ";
}elseif($status=='1'){
	$condition .= " AND b.status=1 ";
}

$mylessonlist = pdo_fetchall("SELECT a.images,b.* FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_order). " b ON a.id=b.lessonid WHERE {$condition} ORDER BY b.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
foreach($mylessonlist as $key=>$value){
	if($value['status']=='-1'){
		$mylessonlist[$key]['statusname'] = '已关闭';
	}elseif($value['status']=='0'){
		$mylessonlist[$key]['statusname'] = '待付款';
	}elseif($value['status']=='1'){
		$mylessonlist[$key]['statusname'] = '已付款';
	}elseif($value['status']=='2'){
		$mylessonlist[$key]['statusname'] = '已评价';
	}
	
	if($value['status']>=1 && $value['validity']>0){
		$mylessonlist[$key]['validity'] = date('Y-m-d H:i:s', $value['validity']);
	}
	$mylessonlist[$key]['addtime'] = date('Y-m-d', $value['addtime']);
}

/* 检查超时未评价课程 */
if($setting['autogood']>0){
	$paytime = time()-$setting['autogood']*86400;
	$order = pdo_fetchall("SELECT a.id,a.ordersn,a.uid,a.openid,a.lessonid,a.bookname,b.nickname FROM " .tablename($this->table_order). " a LEFT JOIN " .tablename("mc_members"). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.status=1 AND a.openid='{$openid}' AND a.paytime<'{$paytime}'");

	foreach($order as $value){
		$evaluate = array(
			'uniacid'  => $uniacid,
			'orderid'  => $value['id'],
			'ordersn'  => $value['ordersn'],
			'lessonid' => $value['lessonid'],
			'bookname' => $value['bookname'],
			'openid'   => $openid,
			'uid'      => $value['uid'],
			'nickname' => $value['nickname'],
			'grade'    => 1,
			'content'  => "用户未及时做出评价,系统默认好评!",
			'addtime'  => time(),
		);
		if(pdo_insert($this->table_evaluate, $evaluate)){
			/* 更新订单状态 */
			pdo_update($this->table_order, array('status'=>2), array('id'=>$value['id']));

			/* 课程总评论数 */
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND lessonid='{$value['lessonid']}'");
			/* 课程好评数 */
			$good = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND lessonid='{$value['lessonid']}' AND grade=1");
			/* 更新课程好评率 */
			pdo_update($this->table_lesson_parent, array('score'=>round($good/$total,2)), array('id'=>$value['lessonid']));
		}
	}
}

if($op=='display'){
	include $this->template('mylesson');
}elseif($op=='cancle'){
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT id,coupon FROM " .tablename($this->table_order). " WHERE uniacid=:uniacid AND id=:id AND uid=:uid ", array(':uniacid'=>$uniacid, ':id'=>$orderid, ':uid'=>$shareuid));
	if(empty($order)){
		message("该课程不存在!");
	}
	if($order['status'] != 0){
		message("该课程状态不允许取消!");
	}
	if(pdo_update($this->table_order, array('status'=>'-1'), array('id'=>$orderid))){
		if(!empty($order['coupon'])){
			$upcoupon = array(
				'is_use'	=> 0,
				'nickname'	=> "",
				'uid'		=> "",
				'openid'	=> "",
				'use_time'	=> "",
			);
			pdo_update($this->table_coupon, $upcoupon, array('card_id'=>$order['coupon']));
		}
		message("取消成功", $this->createMobileUrl('mylesson'), "success");
	}else{
		message("取消失败", "", "error");
	}
}elseif($op=='ajaxgetlist'){
	echo json_encode($mylessonlist);
}

?>