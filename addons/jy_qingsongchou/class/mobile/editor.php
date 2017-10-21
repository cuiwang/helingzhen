<?php


  $dopost = $_GPC['dopost'];
if($dopost=='save_project'){

  $tar_monet = $_GPC['tar_monet'];
  $_tar = pdo_fetchcolumn('SELECT tar_monet FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['fid']);//原来的筹款金额
  $_mid = pdo_fetchcolumn('SELECT mid FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['fid']);//原来的筹款金额
  $pid = $_GPC['pid'];
  $fid = $_GPC['fid'];
  $wxId = $_GPC['wxId'];
  $dream_money = $_GPC['dream_money'];
  $dream_content = $_GPC['dream_content'];
  $dream_id = $_GPC['dream_id'];
  if(is_array($dream_money)){
        $tar_monet = 0;
        foreach ($dream_money as $key => $value) {
            $tar_monet =$tar_monet+$value;
            $_dream[$key]['money']= $value;
            $_dream[$key]['content']= $dream_content[$key];
            $_dream[$key]['dream_id']= $dream_id[$key];
        }
        $_dream = json_encode($_dream);
  }
  if($tar_monet<1){
      message('目标金额不能少于1',referer(),'error');
  }

  if(is_array($wxId)){

    foreach ($wxId as $key => $value) {
       if($key<4){
         $cover_thumb[$key] = $value;
       }
    }
      $wxId = json_encode($wxId);
      $cover_thumb = json_encode($cover_thumb);
  }
  $data = array(
    'tar_monet'=>$tar_monet,
    'project_texdesc'=>$_GPC['project_texdesc'],
    'name'=>$_GPC['name'],
    'use'=>$_GPC['use'],
    'thumb'=>$wxId,
    'detail'=>mb_substr(strip_tags(htmlspecialchars_decode($_GPC['project_texdesc'])),0,30,'utf8'),
    'cover_thumb'=>$cover_thumb,
    'is_secret' => $_GPC['is_secret'],
    'has_sh' => $_GPC['has_sh'],
    'yunfei' => $_GPC['yunfei'],
    'fhsj' => $_GPC['fhsj'],
    'dream'=>$_dream,
    // 'status'=>1
  );
  if($tar_monet!=$_tar){
    //更新操作 修改金钱
    pdo_insert(GARCIA_PREFIX."update",array('weid'=>$this->weid,'mid'=>$_mid,'type'=>2,'content'=>$tar_monet,'upbdate'=>time()));
  }else{
    pdo_insert(GARCIA_PREFIX."update",array('weid'=>$this->weid,'mid'=>$_mid,'type'=>3,'content'=>'内容修改','upbdate'=>time()));
  }

  pdo_update(GARCIA_PREFIX.'fabu',$data,array('id'=>$fid));

  // message('编辑成功',,'success');
  echo "<script language='javascript'
  type='text/javascript'>";
  echo "window.location.href='".$this->createMobileUrl('fsuccess',array('dopost'=>'success','fid'=>$fid))."'";
  echo "</script>";

  exit;

}else if($dopost=='del'){

    pdo_update(GARCIA_PREFIX."fabu",array('status'=>'6'),array('id'=>$fid));
  echo "<script language='javascript'
  type='text/javascript'>";
  echo "window.location.href='".$this->createMobileUrl('fsuccess',array('dopost'=>'del','fid'=>$fid))."'";
  echo "</script>";
  // exit;
}else if($dopost=='update'){
    include $this->template('update');

    exit;
}else if($dopost=='save_up'){
  $fid = $_GPC['fid'];
  $wxId = $_GPC['wxId'];
  $content = $_GPC['content'];
    $mid = $this->_gmodaluserid();
  if(is_array($wxId)){

    foreach ($wxId as $key => $value) {
       if($key<4){
         $cover_thumb[$key] = $value;
       }
    }
      $thumb= json_encode($wxId);

  }
  $data = array(
    'weid'=>$this->weid,
    'mid'=>$mid,
    'fid'=>$fid,
    'content'=>$content,
    'thumb'=>$thumb,
    'upbdate'=>time()
  );
  pdo_insert(GARCIA_PREFIX."update",$data);
  echo "<script language='javascript'
  type='text/javascript'>";
  echo "window.location.href='".$this->createMobileUrl('fsuccess',array('dopost'=>'update','fid'=>$fid))."'";
  echo "</script>";
  exit;
}else if($dopost=='end'){
    $fid = $_GPC['id'];
    $status = $_GPC['status'];
    //判断是否已经申请提前借宿
     $is_early = pdo_fetchcolumn('SELECT early FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$fid);
     if($is_early==1){
           $this->_TplHtml('已在审核中，请不要重新申请',referer(),'success');
     }else{
       include $this->template('early');
       exit;
     }

    // pdo_update(GARCIA_PREFIX."fabu",array('status'=>$status),array('id'=>$id));
    // message('项目已结束',$this->createMobileUrl('index'),'success');

}else if($dopost=='save_early'){
  $fid = $_GPC['fid'];
  $content = $_GPC['content'];
  $mid = pdo_fetchcolumn('SELECT mid FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id = ".$fid);
  $data = array(
    'weid'=>$this->weid,
    'mid'=>$mid,
    'fid'=>$fid,
    'content'=>$content,
    'upbdate'=>time(),
    'type'=>1,
    'status'=>$_GPC['status'],
  );

  $openids = $this->_gManers();
  $nickname = $this->_GetMemberName($mid);
  $message =$this->sys['sitename']."系统消息:\n项目id:[".$fid."],昵称:[".$nickname."]的用户在".date('Y-m-d H:i:s')." 申请了提前结束，请到尽快到后台进行审核!";
  $this->_SendTxts($message,$openids);

  pdo_insert(GARCIA_PREFIX."update",$data);
  pdo_update(GARCIA_PREFIX."fabu",array('early'=>1),array('id'=>$fid));
  $this->_TplHtml('审核提交成功~',$this->createMobileUrl('detail',array('id'=>$fid)),'success');
  exit;

}


  $mid = $this->_gmodaluserid();
  $fid = $_GPC['id'];
  $openid = $this->memberinfo['openid'];
  $platrrom = $this->_gplatfromuser($openid);


  $uid = $platrrom['uid'];
  $_checkfb = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."fabu")." where id=".$fid." and weid=".$this->weid);
  $pid = $_checkfb['pid'];
  $project = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." AND id=".$_checkfb['pid']);


   $index_url = $this->createMobileUrl('index');

   $ids = json_decode($_checkfb['thumb'],true);
   foreach ($ids as $k3 => $v3) {
     if($k3==0){
        $_id.=$v3;
     }else{
        $_id.=",".$v3;
     }
   }
   if(!empty($_id)){
     $_th = pdo_fetchall("SELECT id,thumb FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_id.")");
   }


   foreach ($_th as $k4 => $v4) {
      $_thb[$k4]['thumb'] = $v4['thumb'];
      $_thb[$k4]['id'] = $v4['id'];
   }
   $thumb = $_thb;

      // $_checkfb['thumb']  = json_encode($_th);

  include $this->template('editor');

 ?>
