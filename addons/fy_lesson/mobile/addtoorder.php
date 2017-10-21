<?php
/**
 * 增加课程订单
 * ============================================================================
 */
$id = intval($_GPC['id']); /* 课程id */

$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('addtoorder', array('id' => $id));
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
$setting = pdo_fetch("SELECT mustinfo,is_sale,self_sale,commission,level,stock_config,sitename,vipdiscount,footnav,copyright FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

if (empty($id)) {
    message("参数缺失！", "", "error");
}
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
        message("您还有该课程未付款订单，无需重复下单！", $this->createMobileUrl('mylesson', array('status' => 0)), "warning");
    }
}

/* 检查黑名单操作 */
$this->check_black_list();

$lesson = pdo_fetch("SELECT * FROM " . tablename($this->table_lesson_parent) . " WHERE uniacid='{$uniacid}' AND id='{$id}' AND status=1 LIMIT 1");
if (empty($lesson)) {
    message("课程不存在或已下架！", "", "error");
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
$mc_member = pdo_fetch("SELECT uid,nickname,mobile,realname FROM " . tablename('mc_members') . " WHERE uniacid='{$uniacid}' AND uid='{$uid}' ");
if ($setting['mustinfo'] == 1) {
    if (empty($mc_member['mobile']) || empty($mc_member['realname'])) {
        message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
    }
}

/* 检查会员是否享受折扣 */
$lessonmember = pdo_fetch("SELECT vip FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND openid='{$openid}' ");
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

$ordersn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
/* 判断是否使用优惠码支付 */
$couponPWD = trim($_GPC['coupon']);
if(!empty($couponPWD)){
	$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_coupon). " WHERE uniacid=:uniacid AND password=:password AND is_use=:is_use AND validity>=:validity ", array(':uniacid'=>$uniacid, ':password'=>$couponPWD, ':is_use'=>0, ':validity'=>time()));

	if(!empty($coupon) && $price >= $coupon['conditions']){
		$card_id = $coupon['card_id']; //优惠码编号
		$coupon_amount = $coupon['amount']; //优惠码面值

		if($price >= $coupon['amount']){
			$price -= $coupon['amount'];
		}else{
			$price = 0;
		}

		$upcoupon = array(
			'is_use'	=> 1,
			'nickname'	=> $mc_member['nickname'],
			'uid'		=> $mc_member['uid'],
			'openid'	=> $openid,
			'ordersn'	=> $ordersn,
			'use_time'	=> time(),
		);
		pdo_update($this->table_coupon, $upcoupon, array('card_id'=>$coupon['card_id']));
	}
}

$orderdata = array(
    'acid'		=> $_W['account']['acid'],
    'uniacid'	=> $uniacid,
    'ordersn'	=> $ordersn,
    'uid'		=> $uid,
    'openid'	=> $openid,
    'lessonid'	=> $id,
    'bookname'	=> $lesson['bookname'],
    'marketprice' => $lesson['price'],
	'coupon'	=> $card_id,
	'coupon_amount' => $coupon_amount,
    'price'		=> $price,
	'teacherid' => $lesson['teacherid'],
    'invoice'	=> trim($_GPC['invoice']),
	'integral'	=> $lesson['integral'],
	'validity'	=> $lesson['validity'],
    'addtime'	=> time(),
);

/* 检查课程是否存在讲师分成 */
$teacher = pdo_fetch("SELECT id,uid,openid FROM " . tablename($this->table_teacher) . " WHERE uniacid='{$uniacid}' AND id='{$lesson['teacherid']}'");
if ($lesson['teacher_income'] > 0 && !empty($teacher['openid'])) {
    $orderdata['teacher_income'] = $lesson['teacher_income'];
} else {
    $orderdata['teacher_income'] = 0;
}

/* 检查当前分销功能是否开启且课程价格大于0 */
if ($setting['is_sale'] == 1 && $lesson['price'] > 0) {
    $orderdata['commission1'] = 0;
    $orderdata['commission2'] = 0;
    $orderdata['commission3'] = 0;

    if ($setting['self_sale'] == 1) {
        /* 开启分销内购，一级佣金为购买者本人 */
        $orderdata['member1'] = $uid;
        $orderdata['member2'] = $this->getParentid($uid);
        $orderdata['member3'] = $this->getParentid($orderdata['member2']);
    } else {
        /* 关闭分销内购 */
        $orderdata['member1'] = $this->getParentid($uid);
        $orderdata['member2'] = $this->getParentid($orderdata['member1']);
        $orderdata['member3'] = $this->getParentid($orderdata['member2']);
    }

    $lessoncom = unserialize($lesson['commission']); /* 本课程佣金比例 */
    $settingcom = unserialize($setting['commission']); /* 全局佣金比例 */
    if ($orderdata['member1'] > 0) {
        if ($lessoncom['commission1'] > 0) {
            $orderdata['commission1'] = round($price * $lessoncom['commission1'] * 0.01, 2);
        } else {
            /* 查询用户是否属于其他分销代理级别 */
            $member1 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$orderdata['member1']}'");
            $com_level = pdo_fetch("SELECT commission1 FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' AND id='{$member1['agent_level']}'");

            if ($com_level['commission1'] > 0) {
                $orderdata['commission1'] = round($price * $com_level['commission1'] * 0.01, 2);
            } else {
                $orderdata['commission1'] = round($price * $settingcom['commission1'] * 0.01, 2);
            }
        }
    }
    if ($orderdata['member2'] > 0 && in_array($setting['level'], array('2', '3'))) {
        if ($lessoncom['commission2'] > 0) {
            $orderdata['commission2'] = round($price * $lessoncom['commission2'] * 0.01, 2);
        } else {
            /* 查询用户是否属于其他分销代理级别 */
            $member2 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$orderdata['member2']}'");
            $com_level = pdo_fetch("SELECT commission2 FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' AND id='{$member2['agent_level']}'");

            if ($com_level['commission2'] > 0) {
                $orderdata['commission2'] = round($price * $com_level['commission2'] * 0.01, 2);
            } else {
                $orderdata['commission2'] = round($price * $settingcom['commission2'] * 0.01, 2);
            }
        }
    }
    if ($orderdata['member3'] > 0 && $setting['level'] == 3) {
        if ($lessoncom['commission3'] > 0) {
            $orderdata['commission3'] = round($price * $lessoncom['commission3'] * 0.01, 2);
        } else {
            /* 查询用户是否属于其他分销代理级别 */
            $member3 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$orderdata['member3']}'");
            $com_level = pdo_fetch("SELECT commission3 FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' AND id='{$member3['agent_level']}'");

            if ($com_level['commission3'] > 0) {
                $orderdata['commission3'] = round($price * $com_level['commission3'] * 0.01, 2);
            } else {
                $orderdata['commission3'] = round($price * $settingcom['commission3'] * 0.01, 2);
            }
        }
    }
}

if (!empty($openid)) {
    pdo_insert($this->table_order, $orderdata);
    $orderid = pdo_insertid();
}

if ($orderid) {
    header("Location:" . $this->createMobileUrl('pay', array('orderid' => $orderid)));
} else {
    message("写入订单失败", "", "error");
}
?>