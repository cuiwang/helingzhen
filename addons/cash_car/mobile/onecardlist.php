<?php
/**
 * 洗车卡管理
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
$from_user = $_W['fans']['from_user'];
$uid = $_W['fans']['uid'];
$this->_fromuser = $from_user;


//检查用户洗车卡有效期
$checkcard =  pdo_fetchall("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND number>0 AND validity<:validity", array(':uid' => $uid, ':weid' => $weid, ':validity'=>time()));
foreach($checkcard as $key=>$val){
	$data = array('number'=>0);
	$tiaojian = array(
		'uid'     => $uid,
		'weid'    => $weid,
		'onlycard'=> $val['onlycard'],
	);
	pdo_update($this->table_member_onecard, $data, $tiaojian);
}

if($op=='display'){
	$title = '购买洗车卡';

	//洗车卡列表
	$condition = "uniacid = '{$weid}' AND status=1";
	$card_list = pdo_fetchall("SELECT * FROM " . tablename($this->table_onecard) . " WHERE {$condition} ORDER BY soft DESC");

}elseif($op=='mycard'){
	$title = '我的洗车卡';

	//我的洗车卡
	$str = "weid = '{$weid}' AND uid = '{$uid}'";
	$str .= " AND number > 0 ";
	$mycard_list = pdo_fetchall("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE {$str}");
}

include $this->template('onecardlist');