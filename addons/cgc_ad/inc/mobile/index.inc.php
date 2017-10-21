<?php

global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan=$this->get_quan();
$member=$this->get_member();
$type_list =$this-> get_type_list($quan['id'],1);
$subscribe=$member['follow'];
$from_user=$member['openid'];
$config = $this ->settings;
$settings = $this->module['config'];

$mid=$member['id'];
$quan_id=$quan['id'];
$rob_next_time=$this->get_rob_next_time($quan,$member);

$con='';

if($quan['is_page_addr']){
  if(!empty($member['last_city'])){
	$arr = explode('|',$member['last_city']);
	$prov = $arr[0];
	$city = $arr[1];
	$distract = $arr[2];
  }

  $addr_con="";
  //县城
  if (!empty($distract)){
     $addr_con .= " or a.city like '%$distract%'";
  }
  

   if (!empty($city)){
     $con .= " and (a.city like '%$city%' or a.city='' $addr_con or  INSTR('{$member['last_city']}', a.city)>0) ";
   } else {   
     $con .= " and (a.city like '%$prov%' or a.city='' $addr_con or INSTR('{$member['last_city']}', a.city)>0) ";
   }
   
  
}


if(!empty($_GPC['info_type'])){
  $con .= ' and a.info_type_id='.intval($_GPC['info_type']);
}

$order = empty($_GPC['order'])?"create_time":$_GPC['order'];


if($_GPC['dopost']=='ajax'){
	$__pages = $_GPC['page'];
	$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid,a.city,substring_index(a.city,',',1) city1,REPLACE(a.city,',','') as city2  FROM ".tablename('cgc_ad_adv')." as a
			left join ".tablename('cgc_ad_member')." as b on a.mid=b.id 
			WHERE a.weid=".$weid."  AND a.quan_id=".$quan_id." AND del=0 AND a.status=1 ".$con." ORDER BY rob_status asc,a.top_level DESC,a.".$order." desc,a.id DESC limit  ".$__pages.",10");
	
	$ht = '';
	if(empty($quan['templet_id'])){
		foreach ($list as $key => $item) {
			
			$item['nickname']=subtext($item['nickname'], 15);
			$ht.='<a  onclick="$(\'#toast_loading\').show();" href="'.$this->createMobileUrl('detail',array('quan_id'=>$quan_id,'id'=>$item['id'])).'" class="piece weui_cell">'
			.'<div class="sdleft">'
			.'<span class="sdavatar">';
	
			$ht.='<img src="'.tomedia($item['headimgurl']).'"/>';
			
			$ht.='</span>
	
			</div>
	
			<div class="sdmain">
	
			<dl class="sdwhat">
	
			<dt class="sdtitle"><span class="sdnick">';
	        $ht.=$item['nickname'];
	        $ht.='</span> <span class="sdviews">'.$item['views'].'</span>';
	        /*//已抢,未抢
			 $is_redbag = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=". $item['id'] ." AND mid=".$mid);
			 $ht.=empty($is_redbag)?'':'已抢';
			 //已抢,未抢*/
			 $ht.='</dt>
	
			<dd class="sdcont">';
			
			if ($item['title']){
        	  $item['content']=$item['title'];
        	}
			
		    if ($item['summary']){
        	  $item['content']=$item['summary'];
        	}
        	
	          $item['content']=str_replace("\r\n", '<br/>', $item['content']);
		      $item['content']=str_replace("\n", '<br/>', $item['content']);
			  $item['content']=htmlspecialchars_decode($item['content']);
			  $ht.=$item['content'];
			
	
			$ht.='</dd>
	
			<dd style="height:5px"></dd>
	
			<dd>';
	
			if( !empty($item['images'])){
	
				$ht.='<ul class="sdimgs">';
	
				$item['imgs']=iunserializer($item['images']);
	
				if(count($item['imgs'])==1){
					$ht.='<li class="sdimg c1" ><img src="'.tomedia($item['imgs'][0]).'"/></li>';
				}
	
				else{
					$j=0;
					foreach ($item['imgs'] as $key => $i) {
						$ht.=	'<li class="sdimg c3" ><img src="'.tomedia($item['imgs'][$j]).'"/></li>';
						$j++;
					}
				}
	
				$ht.='</ul>';
	
			}
	
	
			$ht.='</dd>  <dd class="sdtail">';
	
			if($item['rob_status']>0){
	
				$ht.='<span class="sdmoney over">'.$item['total_amount'].$config['unit_text'].'</span>';
				
			
	
				$ht.='</span>';
				
				if ($quan['is_page_addr']){
           	       $item['city2']=empty($item['city2'])?"全国":$item['city2'];
           		   $ht.='<span class="show-citiy_icon"><i>'.$item['city2'].'</i></span>';												
           		}
				
				
				if($item['is_kouling']==1){
					$ht.='<span class="sdmoney2 over"><i>令</i></span>';
				}
				$ht.='<span class="sdover">已抢完</span>';
	
			}
	
			else{
	
				$ht.='<span class="sdmoney">'.$item['total_amount'].$config['unit_text'];
	
		
		       if($item['top_level']>0){
					$ht.='<i>顶</i>';
				}
	
				$ht.='</span>';
				
				
			    if ($quan['is_page_addr']){
           	       $item['city']=empty($item['city'])?"全国":$item['city'];
           		   $ht.='<span class="show-citiy_icon"><i>'.$item['city'].'</i></span>';												
           		}
				
				
				if($item['is_kouling']==1){
					$ht.='<span class="sdmoney2 over"><i>令</i></span>';
				}
				$ht.='<span class="sdtimer" data-time="'.$item['rob_start_time'].'"><span style=\'color:red;\'>抢钱进行中……</span></span>';
			}
	
			$ht.='</dd></dl></div></a>';
		}
	}
	else{//新模板
		foreach ($list as $key => $item) {
		    $item['nickname']=subtext($item['nickname'], 15);
        	$ht.='<a href="'.$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('detail',array('quan_id'=>$quan_id,'id'=>$item['id'],'model'=>$item['model'])), 2).'"   class="piece weui_cell">';
        	$ht.='<div class="am-panel-bd" style=" border-bottom:1px solid #d8d8d8; padding-bottom:.9rem;">';
        	$ht.='<div class="grxx"  style=" padding-bottom:10px;"><div class="grxxl">';
			
			if($item['type']==1){
				$ht.='<a href="'.$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('home_page',array('quan_id'=>$quan_id,'mid'=>$item['mid'])), 2).'">';
				$ht.='<img class="am-circle" src="'.tomedia($item['headimgurl']).'" width="25" height="25" /></a>';
			}else{
				$ht.='<img class="am-circle" src="'.tomedia($item['headimgurl']).'" width="25" height="25" />';
			}
			$ht.=$item['nickname'].'<span id="dengji" style="display:none"> VIP2</span><span class="sdmoney2 over am-badge am-badge-danger am-round">'.$this->index_model[$item['model']]['title'].'</span>';
			$ht.=$item['top_level']>0?'<i>顶</i>':'';
			if($item['model']!=3){
				$ht.='<span class="xxright"><i class="am-icon-money"></i>'.$item['total_amount'].$config['unit_text'];
				$ht.=$item['rob_status']>0?'已抢完':$config['rush_text'].'正在进行中';
				$ht.='</span>';
			}
        	
        	 $ht.='</div></div>';
        	
        	
           
           
           $ht.="<a href='".$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('detail',array('quan_id'=>$quan_id,'id'=>$item['id'],'model'=>$item['model'])), 2)."'>";
        	
			$ht.='<div class="bt" style="color: #333">';
			
			
			if ($item['title']){
        	  $item['content']=$item['title'];
        	}
			
			if ($item['summary']){
        	  $item['content']=$item['summary'];
        	}
        	
        	
        	$item['content']=str_replace("\r\n", '<br/>', $item['content']);
		    $item['content']=str_replace("\n", '<br/>', $item['content']);
			$item['content']=htmlspecialchars_decode($item['content']);
			$ht.=!empty($item['content'])?$item['content']:$item['title'];
			
            $ht.='</div>';
			if( !empty($item['images'])){
				$item['imgs']=iunserializer($item['images']);
				if(count($item['imgs'])==1){
					$ht.='<ul class="am-avg-sm-1 am-thumbnails">';
					$ht.='<li><img class="am-thumbnail" src="'.tomedia($item['imgs'][0]).'"/></li>';
					$ht.='</ul>';
				}
				else{
					$j=0;
					$ht.='<ul class="am-avg-sm-3 am-thumbnails">';
					foreach ($item['imgs'] as $key => $i) {
						$ht.='<li><img class="am-thumbnail" src="'.tomedia($item['imgs'][$j]).'"/></li>';
						$j++;
						if($j>=3){break;}
					}
					$ht.='</ul>';
				}
			}
			$ht.='</a>';
	       $ht.='<div class="grxx" style="padding-bottom:0px; margin:0;">';
			
			 $ht.=' <div class="grxxl"><span class="xxright dbnr">';
				
            	 $ht.=' <div class="grxxl"><span class="xxright dbnr">';
            	        
                 /*//已抢,未抢
				 $is_redbag = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=". $item['id'] ." AND mid=".$mid);
				 $ht.=empty($is_redbag)?'':'<i style=" color:red;">已抢</i>';
				 //已抢,未抢*/
				
				 $ht.='<i class="am-icon-hand-pointer-o" style=" color:#cccccc; "></i> <i style=" color:#cccccc;font-style:normal;">点击:'.$item['views'].'</i>&nbsp;&nbsp;&nbsp;&nbsp; <i class="am-icon-history" style=" color:#cccccc"></i> <i style=" color:#cccccc;font-style:normal;">'.time_diff($item['create_time']).'</i>';		
				$ht.='</span></div></div>';
			
			$ht.='</div></div></a>';
		}
	}

	if(!empty($list)){
		echo json_encode(array('status'=>1,'log'=>$ht));
	}else{
		echo json_encode(array('status'=>0,'log'=>$ht));
	}
	exit;
}

$_psize = 10;

$_pindex = max(1, intval($_GPC['page']));

$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid,a.city,substring_index(a.city,',',1) as city1,REPLACE(a.city,',','') as city2  FROM ".tablename('cgc_ad_adv')." as a
		left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
		WHERE a.weid=".$weid."  AND a.quan_id=".$quan_id." AND del=0 AND a.status=1 ".$con."
		ORDER BY rob_status asc,a.top_level desc,a.".$order." desc,a.id DESC
		LIMIT " . ($_pindex -1) * $_psize . ",{$_psize}");

$_total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('cgc_ad_adv') . " a WHERE a.weid=".$weid."  AND a.quan_id=".$quan_id." AND del=0 AND a.status=1 ".$con);

$pager = $this->adv_pagination($_total, $_pindex, $_psize,'',array('before' => 1, 'after' => 1, 'ajaxcallback' => ''));

//是否显示已抢,未抢
if (!empty($settings['rob_diff'])){
	if($list){
		foreach ($list as $key => $item) {
			if($list[$key]['model']=='8'){//卡券
				$list[$key]['is_redbag']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=". $item['id'] ." AND mid=".$mid);
			}
			else{
				$list[$key]['is_redbag']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_couponc')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=". $item['id'] ." AND mid=".$mid);
			}
			
		}
	}
}

$banner=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_banner')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND status=1 ORDER BY displayorder DESC,id ASC");

//统计人数
$_usertotal = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('cgc_ad_member')." where weid=".$weid." AND quan_id=".$quan_id);

$total = pdo_fetch("SELECT SUM(total_amount)  total_amount,SUM(views)  views FROM ".tablename('cgc_ad_adv')." where weid=".$weid." AND quan_id=".$quan_id." AND status=1");

//总撒钱
$_stotal=$total['total_amount']+$quan['init_fee'];

//总人气
$_pvtotal=$total['views']+$quan['views'];

//统计人数
$_usertotal = $_usertotal+$quan['init_user_num'];

if(empty($quan['templet_id'])){
  include $this->template('index');
} else{
  $quan_count=pdo_fetchcolumn("SELECT count(1)  FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid."  AND del=0 ");
  include $this->template('index2');
}
