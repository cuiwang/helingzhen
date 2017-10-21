<?php

global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan=$this->get_quan();
$member=$this->get_member();
$subscribe=$member['follow'];
$from_user=$member['openid'];
$config = $this ->settings;

$mid=$member['id'];
$quan_id=$quan['id'];
$rob_next_time=$this->get_rob_next_time($quan,$member);


if($_GPC['dopost']=='ajax'){
	$__pages = $_GPC['page'];
	$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid FROM ".tablename('cgc_ad_adv')." as a
			left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
			WHERE a.weid=".$weid."  AND a.quan_id=".$quan_id." AND del=0 AND a.status=1 ORDER BY rob_status asc,a.top_level DESC,a.create_time desc,a.id DESC limit  ".$__pages.",10");
	

	
	$ht = '';
	foreach ($list as $key => $item) {
		$ht.='<a  onclick="$(\'#toast_loading\').show();" href="'.$this->createMobileUrl('detail',array('quan_id'=>$quan_id,'id'=>$item['id'])).'" class="piece weui_cell">'
		.'<div class="sdleft">'
		.'<span class="sdavatar">';

		$ht.='<img src="'.tomedia($item['headimgurl']).'"/>';
		
		$ht.='</span>

		</div>

		<div class="sdmain">

		<dl class="sdwhat">

		<dt class="sdtitle"><span class="sdnick">';



		if($item['type']==1){

			$ht.=$item['nickname'];

		}else{

			$ht.=$item['nicheng'];

		}

		$ht.='</span> <span class="sdviews">'.$item['views'].'</span></dt>

		<dd class="sdcont">';





		if($item['type']==1){

			$ht.=$item['content'];

		}else{

			$ht.=$item['title'];

		}

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

		if($item['rob_end_time']>0){

			$ht.='<span class="sdmoney over">'.$item['total_amount'].$config['unit_text'].'</span>';
			if($item['top_level']>0){

				$ht.='<i>顶</i>';

			}

			$ht.='</span>';
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
			if($item['is_kouling']==1){
				$ht.='<span class="sdmoney2 over"><i>令</i></span>';
			}
			$ht.='<span class="sdtimer" data-time="'.$item['rob_start_time'].'"><span style=\'color:red;\'>抢钱进行中……</span></span>';

		}

		$ht.='</dd></dl></div></a>';

	}

	if(!empty($list)){

		echo json_encode(array('status'=>1,'log'=>$ht));

	}else{

		echo json_encode(array('status'=>0,'log'=>$ht));

	}

	exit;

}

$_pages = 10;
$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid FROM ".tablename('cgc_ad_adv')." as a
		left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
		WHERE a.weid=".$weid."  AND a.quan_id=".$quan_id." AND del=0 AND a.status=1
		ORDER BY rob_status asc,a.top_level desc,a.create_time desc,a.id DESC
		limit 0,".$_pages);
		
/*		$list=pdo_fetchall("SELECT a.*,b.type,b.thumb,b.nicheng,b.nickname,b.headimgurl,b.openid,c.money FROM ".tablename('cgc_ad_adv')." as a

		left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
			
		left join ".tablename('cgc_ad_red')." as c on a.id=c.advid AND c.mid=".$mid."

		WHERE a.weid=".$weid."  AND a.quan_id=".$quan_id." AND del=0 AND a.status=1

		ORDER BY rob_status asc,a.top_level desc,a.create_time desc,a.id DESC

		limit 0,".$_pages);*/
	
$banner=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_banner')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND status=1 ORDER BY displayorder DESC,id ASC");

//统计人数
$_usertotal = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('cgc_ad_member')." where weid=".$weid." AND quan_id=".$quan_id);

$total = pdo_fetch("SELECT SUM(total_amount)  total_amount,SUM(views)  views FROM ".tablename('cgc_ad_adv')." where weid=".$weid." AND quan_id=".$quan_id." AND status=1");

//总撒钱
$_stotal=$total['total_amount'];

//总人气
$_pvtotal=$total['views']+$quan['views'];

include $this->template('index');



