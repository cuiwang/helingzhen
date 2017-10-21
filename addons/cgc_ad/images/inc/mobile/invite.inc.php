<?php
global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan_id=intval($_GPC['quan_id']);
$quan=$this->get_quan();
$member=$this->get_member();
      
$mid=$member['id'];
$op=$_GPC['op'];

$config = $this ->settings;




if($op=='invite'){
  $pid=$_GPC['pid'];
  if(($mid!=$pid) && (empty($member['inviter_id']))){
  	$inviter=pdo_fetch("select * from ".tablename('cgc_ad_member')." where weid=$weid and id=$pid");
  	if ($inviter['inviter_id']!=$mid){
        pdo_update("cgc_ad_member",array('inviter_id'=>$pid),array('id'=>$mid));
  	}
  }
}



if($quan['is_follow']==1){
  if($member['follow']==0){
    $this->returnError('请先关注才能参与抢钱！',$quan['follow_url']);
  }
}


header("location:".	$this->createMobileUrl('index', array('quan_id' => $quan_id)));

