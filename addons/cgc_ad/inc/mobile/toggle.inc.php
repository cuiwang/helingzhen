<?php

global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan=$this->get_quan();
$config = $this ->settings;
$quan_id=$quan['id'];

if($_GPC['dopost']=='ajax'){
	$city = $_GPC['city'];
	$con = '';
	if(!empty($city) && $city!="全国"){
		$con = " AND city like '%$city%' ";
	}
	$list=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." $con AND del=0 ");//AND id <>".$quan_id."
	$ht ='';
	if(!empty($list)){
		$ht.= '<ul class="am-avg-sm-3 rmcs">';
		foreach ($list as $key => $item) {
			$ht.='<li><a href="'.$this->createMobileUrl('index',array('quan_id'=>$item['id'])).'"><button type="button" class="am-btn am-btn-default am-radius">';
			if(empty($item['city'])){
				$ht.='全国';
			}else{
				$ht.=$item['city'];
			}
			$ht.='</button></a></li>';
		}
		$ht.= '</ul>';
	}
	
	if(!empty($list)){
		echo json_encode(array('status'=>1,'log'=>$ht));
	}else{
		echo json_encode(array('status'=>0,'log'=>'未找到城市'));
	}
	exit;
}


$list=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid."  AND del=0 ");//AND id <>".$quan_id."

include $this->template('toggle');
