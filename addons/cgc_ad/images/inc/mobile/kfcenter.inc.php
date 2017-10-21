<?php

 global $_W, $_GPC;

 $weid = $_W['uniacid'];
 $quan_id = intval($_GPC['quan_id']);
 $member = $this -> get_member();
 $from_user = $member['openid'];
 $quan=$this->get_quan();
 $mid = $member['id'];
 $config = $this ->settings;
 $op = empty($_GPC['op'])?"display":$_GPC['op'];
 $weid=$_W['uniacid'];
 $adv=$this->get_adv();
 
 if (empty($member['is_kf'])){
  $this->returnError("没权限");
 }	
 
 if($_GPC['dopost']=='top'){
   $top_level=$_GPC['status'];
   if ($top_level==1){
     $top_level=$adv['total_amount'] * 100;
   }
   pdo_update('cgc_ad_adv',array('top_level'=>$top_level),array('id'=>$_GPC['id'],'weid'=>$weid,'quan_id'=>$_GPC['quan_id']));
   message('操作成功',$this->createMobileUrl('detail',array('id'=>$_GPC['id'],'quan_id'=>$_GPC['quan_id'])),'success');
  }else if($_GPC['dopost']=='bad'){
   pdo_update('cgc_ad_adv',array('status'=>'2'),array('id'=>$_GPC['id'],'weid'=>$weid,'quan_id'=>$_GPC['quan_id']));
   message('操作成功',$this->createMobileUrl('index',array('quan_id'=>$_GPC['quan_id'])),'success');
  }else if($_GPC['dopost']=='is_open'){
   pdo_update('cgc_ad_adv',array('is_open'=>$_GPC['status']),array('id'=>$_GPC['id'],'weid'=>$weid,'quan_id'=>$_GPC['quan_id']));
   message('操作成功',$this->createMobileUrl('detail',array('id'=>$_GPC['id'],'quan_id'=>$_GPC['quan_id'])),'success');
  }

