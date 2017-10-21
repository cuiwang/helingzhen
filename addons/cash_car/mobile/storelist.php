<?php
/**
 * 服务点列表
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
$weid = $this->_weid;
$from_user = $this->_fromuser;

$title = '服务点列表';
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('storelist');
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

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$chooseurl = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$cid = intval($_GPC['cid']); //项目分类
$storetype = intval($_GPC['storetype']); //服务点类型
if($cid>0){
	$catname = pdo_fetch("SELECT name FROM " .tablename($this->table_category). " WHERE id='{$cid}'");
}
$cname = $catname['name']?$catname['name']:"全部服务";

$lng = trim($_GPC['lng'])?trim($_GPC['lng']):$_SESSION['lng'];
$lat = trim($_GPC['lat'])?trim($_GPC['lat']):$_SESSION['lat'];
$coords_time = time()-$_SESSION['coords_time'];
if((!empty($lng) && !empty($lat)) || $coords_time>$setting['coords_time']){
	$storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_store) . " WHERE weid = :weid AND is_show=1", array(':weid' => $weid));

	foreach($storelist as $key=>$shop){
		$storelist[$key]['distance'] = $this->getDistance($lat,$lng,$shop['lat'],$shop['lng']);
		if($setting['store_model']==2){
			$condition = " weid='{$weid}' AND storeid='{$shop['id']}'";
			if($cid>0){
				$condition .= " AND cid='{$cid}'";
			}

			$storelist[$key]['goods'] = pdo_fetchall("SELECT id,title,productprice FROM " .tablename($this->table_goods). " WHERE {$condition} ORDER BY productprice ASC");
			if(empty($storelist[$key]['goods'])){
				unset($storelist[$key]);
			}else{
				$dprice = 99999;
				foreach($storelist[$key]['goods'] as $goods){
					$dprice = $goods['productprice']<$dprice?$goods['productprice']:$dprice;
				}
				$storelist[$key]['dprice'] = $dprice;
			}

			if($storetype>0){
				if($shop['store_type']!=$storetype){
					unset($storelist[$key]);
				}
			}
		}
	}

	$soft = intval($_GPC['soft']); //项目排序
	if(empty($soft)){
		$softname = "智能排序";
		$storelist = $this->array_sort($storelist, 'displayorder', 'decs');
	}elseif($soft==1){
		$softname = "离我最近";
		$storelist = $this->array_sort($storelist, 'distance');
	}elseif($soft==2){
		$softname = "价格优先";
		$storelist = $this->array_sort($storelist, 'dprice');
	}

	
	session_start();
	$_SESSION['lat'] = $lat;
	$_SESSION['lng'] = $lng;
	$_SESSION['coords_time'] = time();
}

//banner图
$banner = unserialize($setting['banner']);
//分类列表
$category = pdo_fetchall("SELECT id,name FROM " .tablename($this->table_category). " WHERE weid='{$weid}'");

include $this->template('storelist');