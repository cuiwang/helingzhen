<?php
/**
 * 确认下单
 */
 
/* 课程id */
$id = intval($_GPC['id']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('confirm', array('id'=>$id,'uid'=>$uid));
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
$setting = pdo_fetch("SELECT sitename,vipdiscount,footnav,stock_config,copyright,front_color,teacherlist,is_invoice FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

$title = "确认下单";
$lessonurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('lesson', array('id'=>$id));

/* 更新微课程会员信息 */
$this->updatelessonmember($openid,$uid);

$lessonorder = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE uniacid=:uniacid AND lessonid=:id AND status>=0 AND openid=:openid  LIMIT 1", array(':uniacid'=>$uniacid, ':openid'=>$openid, ':id'=>$id));
if (!empty($lessonorder)) {
    if ($lessonorder['status'] == 1) {
		if ($lessonorder['validity']==0) {
			message("您已购买该课程，无需重复购买！", $this->createMobileUrl('lesson', array('id' => $id)), "error");
		}else{
			if($lessonorder['validity'] > time()){
				message("您已购买该课程，无需重复购买！", $this->createMobileUrl('lesson', array('id' => $id)), "error");
			}
		}
    } elseif ($lessonorder['status'] == 0) {
        message("您还有该课程未付款订单！", $this->createMobileUrl('mylesson', array('status' => 0)), "warning");
    }
}

/* 检查黑名单操作 */
$this->check_black_list();

$lesson = pdo_fetch("SELECT a.*,b.teacher,b.teacherphoto FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid=:uniacid AND a.id=:id AND a.status=1 LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id));

if (empty($lesson)) {
    message("课程不存在或已下架！", "", "error");
}

if(!empty($lesson['teacherphoto'])){
	$teacherphoto = $_W['attachurl'].$lesson['teacherphoto'];
}else{
	$teacherphoto = MODULE_URL."template/mobile/images/default_avatar.gif";
}

/* 检查是否开启库存 */
if($setting['stock_config']==1){
	if($lesson['stock'] <=0 ){
		message("该课程已售罄，下次记得早点来哦~", "", "error");
	}
}

load()->model('mc');
$uid = mc_openid2uid($openid);
/* 检查用户是否完善手机号码/姓名 */
$mc_member = pdo_fetch("SELECT mobile,realname FROM " . tablename('mc_members') . " WHERE uniacid=:uniacid AND uid=:uid ", array(':uniacid'=>$uniacid, ':uid'=>$uid));
if ($setting['mustinfo'] == 1) {
    if (empty($mc_member['mobile']) || empty($mc_member['realname'])) {
        message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
    }
}

/* 检查会员是否享受折扣 */
$lessonmember = pdo_fetch("SELECT vip FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND openid=:openid ", array(':uniacid'=>$uniacid, ':openid'=>$openid));
if ($lessonmember['vip'] == 1 && $setting['vipdiscount'] > 0) { /* 折扣开启 */
    if ($lesson['price'] > 0) {
        if ($lesson['isdiscount'] == 1) {/* 课程开启折扣 */
            if ($lesson['vipdiscount'] > 0) {/* 使用课程单独折扣 */
                $price = round($lesson['price'] * $lesson['vipdiscount'] * 0.01, 2);
            } else {/* 使用课程全局折扣 */
                $price = round($lesson['price'] * $setting['vipdiscount'] * 0.01, 2);
            }
        } else {
            $price = $lesson['price']; /* 课程单方面关闭折扣 */
        }
    } else {
        $price = 0;
    }
} else {
    $price = $lesson['price']; /* 课程全局关闭折扣 */
}

$vipCoupon = $lesson['price'] - $price;


include $this->template('confirm');

?>