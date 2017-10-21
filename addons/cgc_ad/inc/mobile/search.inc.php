<?php

global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan=$this->get_quan();
$member=$this->get_member();
$subscribe=$member['follow'];
$from_user=$member['openid'];
$config = $this ->settings;
$quan_id=$quan['id'];
$_pages = 10;


if($_GPC['op']=='display'){
	$prov = '';
	if(!empty($member['last_city'])){
		$arr = explode('|',$member['last_city']);
		$prov = $arr[0];
	}
	
	$con='';
	if($quan['is_page_addr'] && !empty($prov)){
	  $con .= " and (a.city like '%$prov%' or a.city='') ";
	}

	if(!empty ($_GPC['keyword'])){
		$con = " AND a.content LIKE '%{$_GPC['keyword']}%' ";
	}
	
	$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid,a.city,substring_index(a.city,',',1) as city1,REPLACE(a.city,',','') as city2  FROM ".tablename('cgc_ad_adv')." as a
		left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
		WHERE a.weid=".$weid."  AND a.quan_id=".$quan_id." AND del=0 AND a.status=1 ".$con."
		ORDER BY rob_status asc,a.top_level desc,a.id DESC
		limit 0,".$_pages);
}

include $this->template('search');
