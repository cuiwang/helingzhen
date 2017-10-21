<?php
global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan_id=intval($_GPC['quan_id']);
$id=intval($_GPC['id']);    
$member=$this->get_member();
$from_user=$member['openid'];
$quan=$this->get_quan();
$adv=$this->get_adv();
$mid=$member['id'];
$op=($_GPC['op']); 

$config = $this ->settings;

   //封装在get_adv函数
/*    if ($adv['model']=="2"){
      header("location:".$this->createMobileUrl('group_detail',array('quan_id'=>$quan_id,'id'=>$id,'model'=>$adv['model'])));
      exit();
    }else if ($adv['model']=="3"){
      header("location:".$this->createMobileUrl('free_detail',array('quan_id'=>$quan_id,'id'=>$id,'model'=>$adv['model'])));
      exit();
    }else if ($adv['model']=="4"){
      header("location:".$this->createMobileUrl('task_detail',array('quan_id'=>$quan_id,'id'=>$id,'model'=>$adv['model'])));
      exit();
    }
	*/

 

$quan['city']=str_replace("|", "或", $quan['city']);


$_page = $__page= 5;
$_kf = explode(',', $this->setting['kf_openid']);
if(in_array($from_user,$_kf)){
  $is_kf = 1;
}else{
  $is_kf = 0;
}

$adv['views']=$this->get_view($member,$adv);
 
$my=true;
 $_msglist = pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_message')." a
      left join ".tablename('cgc_ad_member')." b on a.mid=b.id
 WHERE a.weid=".$weid." AND a.advid=".$id." and a.status=1 order by upbdate desc limit 0,5");
  
 $_msgtotal = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_message')." WHERE weid=".$weid." AND status=1  and advid=".$_GPC['id']);
             
include $this->template('free_detail');


