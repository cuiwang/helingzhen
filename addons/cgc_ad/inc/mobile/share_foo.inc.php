<?php
global $_W, $_GPC;
$weid = $_W['uniacid'];
//这个判断入口的，不要改变位置，不然报错
$_SESSION['enter_control'] = true;
$quan = $this->get_quan();
$member = $this->get_member();
$adv = $this->get_adv();
$config = $this->settings;
$mid = $member['id'];
$quan_id = $quan['id'];
$id = $_GPC['id'];
$op = empty ($_GPC['op']) ? "display" : $_GPC['op'];
$pid = $_GPC['pid'];
$form = $_GPC['form'];
$id = $_GPC['id'];

$share_link=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('foo',array('form'=>'share_detail','op'=>'help','quan_id'=>$quan_id,'pid'=>$pid,'id'=>$adv['id'])), 2);
if (!empty($adv['link'])){
  $share_link=$adv['link'];
} 


if ($adv['mid']==$pid){ 
  header("location:" .$share_link);
  exit();
}

if (empty($adv['read_num'])){
  header("location:" .$share_link);
  exit();
} 

if ($adv['read_num']<=$adv['read_numed']){ 
  header("location:" .$share_link);
  exit();
}





$temp_help = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_read') . " WHERE weid=" . $weid . " AND quan_id=" . $quan_id . " AND advid=" . $id. " AND mid=" . $mid);

if (empty ($temp_help)) {
	$inviter_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND id=".$pid);
	$data10 = array (
			'weid' => $weid,
			'quan_id' => $quan_id,			
			'advid' => $id,			
			'share_openid'=>$inviter_member['openid'],
			'share_nickname'=>$inviter_member['nickname'],		
			'share_mid' => $pid,
			'mid' => $mid,
			'openid'=>$member['openid'],
			'nickname'=>$member['nickname'],	
			'avatar'=>$member['headimgurl'],
			'create_time' => TIMESTAMP,			
		);
		pdo_insert("cgc_ad_read", $data10);
		
	    $ret3=pdo_update("cgc_ad_adv",array('read_numed'=>$adv['read_numed']+1),array('id'=>$id));	

    $ret3=pdo_update("cgc_ad_member",array('rob'=>$inviter_member['rob']+$adv['read_unit_price'],'credit'=>$inviter_member['credit']+$adv['read_unit_price']),array('id'=>$pid));
	//提成信息
	send_read_fc($member,$inviter_member,$quan,$adv['read_unit_price'],$config); 
				
	
}

 header("location:" .$share_link);
  exit();
