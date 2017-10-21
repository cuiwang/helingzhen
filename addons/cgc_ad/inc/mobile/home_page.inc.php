<?php

global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan=$this->get_quan();
$config = $this ->settings;
$mid=$_GPC['mid'];
$quan_id=$quan['id'];

if(empty($mid)){
	$this->returnError('访问错误，缺少参数');
}

$member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member').
    " WHERE weid=$weid AND quan_id=$quan_id and id=$mid and openid!=''");
    
if(empty($member)){
	$this->returnError('访问错误，未找到用户信息');
}

//$model_array = array("", "", "团", "免","任","文","语","令","卡");

$order = empty($_GPC['order'])?"create_time":$_GPC['order'];

$con='';

if($_GPC['dopost']=='ajax'){
	$__pages = $_GPC['page'];
	$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid,a.city,substring_index(a.city,',',1) city1,REPLACE(a.city,',','') as city2  FROM ".tablename('cgc_ad_adv')." as a
			left join ".tablename('cgc_ad_member')." as b on a.mid=b.id 
			WHERE a.weid=$weid AND a.mid=$mid  AND a.quan_id=$quan_id AND del=0 AND a.status=1 $con ORDER BY rob_status asc,a.top_level DESC,a.$order desc,a.id DESC limit  ".$__pages.",10");
	
	$ht = '';
	
	foreach ($list as $key => $item) {
	 	$item['content']=str_replace("\r\n", '<br/>', $item['content']);
	    $item['content']=str_replace("\n", '<br/>', $item['content']);
		$item['content']=htmlspecialchars_decode($item['content']);
				
    	$ht.='<a href="'.$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('detail',array('quan_id'=>$quan_id,'id'=>$item['id'],'model'=>$item['model'])), 2).'\'" >';
					
    	$ht.='<li class="am-g am-list-item-desced pet_list_one_block">';
    	$ht.='<div class="pet_list_one_info"><div class="pet_list_one_info_l"><div class="pet_list_one_info_ico">';
    	$ht.='<img src="'.tomedia($item['headimgurl']).'" alt="">';
    	$ht.='</div>';
    	$ht.='<div class="pet_list_one_info_name">'.$item['nickname'].'</div>';
    	$ht.='</div>';
    	$ht.='<div class="pet_list_one_info_r">';
    	if($item['rob_status']>0){
    		$ht.='<div class="pet_list_tag pet_list_tag_stj"><i class="am-icon-money"></i>'.$item['total_amount'].$config['unit_text'].'已抢完...</div>';
    	}else{
    		$ht.='<div class="pet_list_tag pet_list_tag_zzs"><i class="am-icon-money"></i>'.$item['total_amount'].$config['unit_text'].$config['rush_text'].'进行中</div>';
    	}
    	$ht.='</div></div>';
    	
    	$ht.='<div class=" am-list-main"><h3 class="am-list-item-hd pet_list_one_bt">';
    	$ht.='<a href="'.$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('detail',array('quan_id'=>$quan_id,'id'=>$item['id'],'model'=>$item['model'])), 2).'\'" >';
    	if(!empty($item['content'])){
    		$ht.=$item['content'];
    	}else{
    		$ht.=$item['title'];
    	}
    	$ht.='</h3>';
    	
    	if( !empty($item['images'])){
			$item['imgs']=iunserializer($item['images']);
			$j=0;
			$ht.='<ul data-am-widget="gallery" class="am-gallery am-avg-sm-3
  am-avg-md-3 am-avg-lg-3 am-gallery-default pet_list_one_list">';
			foreach ($item['imgs'] as $key => $i) {
				$ht.='<li><div class="am-gallery-item"><img class="am-thumbnail" src="'.tomedia($item['imgs'][$j]).'"/></div></li>';
				$j++;
				if($j>=3){break;}
			}
			$ht.='</ul>';
		}
		$ht.='</div></li></a>';
	}

	if(!empty($list)){
		echo json_encode(array('status'=>1,'log'=>$ht));
	}else{
		echo json_encode(array('status'=>0,'log'=>$ht));
	}
	exit;
}



$_pages = 10;

$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid,a.city,substring_index(a.city,',',1) as city1,REPLACE(a.city,',','') as city2  FROM ".tablename('cgc_ad_adv')." as a
		left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
		WHERE a.weid=$weid AND a.mid=$mid  AND a.quan_id=$quan_id AND del=0 AND a.status=1 $con
		ORDER BY rob_status asc,a.top_level desc,a.$order desc,a.id DESC
		limit 0,".$_pages);
		
//小弟
$_usertotal = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('cgc_ad_member')." where weid=$weid AND quan_id=$quan_id AND inviter_id=$mid");

$total = pdo_fetch("SELECT SUM(total_amount) total_amount,COUNT(id) adv_total FROM ".tablename('cgc_ad_adv')." where weid=$weid AND quan_id=$quan_id AND mid=$mid AND status=1");

//总撒钱
$_stotal=$total['total_amount'];

//广告数
$_advtotal=$total['adv_total'];

include $this->template('home_page');
