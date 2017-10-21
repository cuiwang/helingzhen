<?php
/**
 * 购物车
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
session_start();

$weid = $this->_weid;
$from_user = $this->_fromuser;

$title = '购物车';
$this->check_black_list();

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('cashcar', true);
if (isset($_COOKIE[$this->_auth2_openid])) {
	$from_user = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$from_user = $userinfo["openid"];
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
if (empty($from_user)) {
	message('会话已过期，请重新发送关键字!');
}

$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where weid = :weid and from_user=:from_user ORDER BY dateline DESC LIMIT 1", array(':weid' => $weid, 'from_user'=>$from_user));
//全部服务点
$storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_store) . " where weid = :weid and is_show=1", array(':weid' => $weid));
//当前服务点
$storeid = $_GPC['storeid']?intval($_GPC['storeid']):$_SESSION['storeid'];
$storeid = $storeid?$storeid:$storelist[0]['id'];
$_SESSION['storeid'] = $storeid;

$stores = pdo_fetch("SELECT * FROM " . tablename($this->table_store) . " WHERE id=:id", array(':id' => $storeid));

$user = fans_search($from_user);

//购物车
$condition = " a.weid='{$weid}' AND a.from_user='{$from_user}' AND a.total>0 ";
if($setting['store_model']==1){
	$condition .= " AND a.storeid=0 ";
}elseif($setting['store_model']==2){
	$condition .= " AND a.storeid='{$storeid}' ";
}

$carts = pdo_fetchall("SELECT *, a.id AS cartid, a.integral AS cintegral FROM " . tablename($this->table_cart) . " a LEFT JOIN " . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE {$condition} ");

if(empty($carts)){
	message("您的购物车为空~", $this->createMobileUrl('goodslist', array('storeid'=>$storeid,'from_user'=>$from_user)), "warning");
}

foreach($carts as $goods){
	if($goods['status']==0){
		pdo_delete($this->table_cart,array('id'=>$goods['cartid']));
	}
	if($goods['price'] != $goods['productprice']){
		pdo_update($this->table_cart, array('price'=>$goods['productprice']), array('id'=>$goods['cartid']));
	}
	if($goods['cintegral'] != $goods['integral']){
		pdo_update($this->table_cart, array('integral'=>$goods['integral']), array('id'=>$goods['cartid']));
	}
}


$cart = pdo_fetchall("SELECT *, a.id AS cartid, a.integral AS cintegral  FROM " . tablename($this->table_cart) . " a LEFT JOIN " . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE {$condition} ");

foreach($cart as $key=>$value){
	$cart_goods[$key] = $value['goodsid'];
}

//检查用户洗车卡有效期
$checkcard =  pdo_fetchall("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND number>0 AND validity<:validity", array(':uid' => $user['uid'], ':weid' => $weid, ':validity'=>time()));
foreach($checkcard as $key=>$val){
	$data = array('number'=>0);
	$conditon = array(
		'uid' => $user['uid'],
		'weid' => $weid,
		'onlycard' => $val['onlycard'],
	);
	pdo_update($this->table_member_onecard, $data, $conditon);
}


$worktime = unserialize($stores['hours']); //服务点工作时间
$meal_date = trim($_GPC['meal_date'])?strtotime(trim($_GPC['meal_date'])):strtotime("today"); //预约时间
$dayoff = explode("/", $stores['dayoff']); //服务点休息日
$daytimes = date("d", $meal_date); //当前日期

$residue = 0;
foreach($worktime as $key=>$time){
	$tmp = explode("~", $time);
	$starttime = $meal_date + intval($tmp[0])*3600;
	$ordertotal =  pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND status>0 AND storeid='{$storeid}' AND meal_timestamp='{$starttime}'");
	$worktimes[$key]['time'] = $time;

	if(in_array($daytimes, $dayoff)){
		$worktimes[$key]['remain'] = "休息日/不接单";
	}else{
		if($ordertotal==$stores['hours_time'] || time()>$starttime+(60-$stores['bookingtime'])*60){
			$worktimes[$key]['remain'] = "已满";
		}elseif($ordertotal < $stores['hours_time']){
			$worktimes[$key]['remain'] = "余".($stores['hours_time']-$ordertotal)."位";
			if($residue==0){
				$worktimes[$key]['selected'] = "selected";
				$residue++;
			}
		}
	}

}

//查询用户洗车卡
$totalAmount = 0;
$totalIntegral = 0;
foreach($cart as $k=>$v){
	if(!empty($v['onlycard'])){
		$membercard =  pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND onlycard=:onlycard AND number>0 AND validity>:validity", array(':uid' => $user['uid'], ':weid' => $weid, 'onlycard'=>$v['onlycard'], ':validity'=>time()));
		if($membercard){
			$cart[$k]['cartnumber'] = $membercard['number'];
			$cardtotal += $v['price'];
		}else{
			if($user['free_number']=='1' && $v['free_card']=='1'){
				$cardtotal += $v['price'];
				$remark = 'free_card'; //标识免费试用
			}
		}
	}
	$totalAmount += $v['price'];
	$totalIntegral += $v['cintegral'];
}
$totalAmount = $totalAmount?number_format($totalAmount,2):'0.00';

if($stores['map_type']==1){
	include $this->template('cashcar_tx');
}elseif($stores['map_type']==2){
	include $this->template('cashcar_bd');
}