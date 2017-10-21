<?php

    $dopost = $_GPC['dopost'];

    load()->func('tpl');
    if($this->modal == 'pc'){
      exit;
    }
   if($this->sys['is_h5']&&$this->modal == 'phone'){
       $mid = json_decode($this->cookies->get('userDatas'),true);
       $mid =$mid['uid'];
   }else{
       $mid = $this->_gmodaluserid();
      $this->_checkMobile();
      if(!$this->_OauthMobile){
          $this->_TplHtml('请先绑定联系手机',$this->createMobileUrl('member',array('display'=>'phone')),'error');
          exit;
      }

   }




   if($dopost=='caogao'){
    $tar_monet = $_GPC['tar_monet'];
    $pid = $_GPC['pid'];
    $fid = $_GPC['fid'];
    $wxId = $_GPC['wxId'];
    if(!empty($wxId)){
      $wxId = explode(',',$wxId);
      foreach ($wxId as $key => $value) {
         if($key<4){
           $cover_thumb[$key] = $value;
         }
      }


      $wxId = json_encode($wxId);
      $cover_thumb = json_encode($cover_thumb);
    }else{
      $wxId='';
      $cover_thumb='';
    }

    $data = array(
      'tar_monet'=>$tar_monet,
      'rand_time'=>$_GPC['rand_time'],
      'rand_day'=>$_GPC['rand_day'],
      'project_texdesc'=>$_GPC['project_texdesc'],
      'name'=>$_GPC['name'],
      'use'=>$_GPC['use'],
      'cur_day'=>$_GPC['cur_day'],
       'cover_thumb'=>$cover_thumb,
       'detail'=>mb_substr(strip_tags(htmlspecialchars_decode($_GPC['project_texdesc'])),0,90,'utf8'),
       'is_secret' => $_GPC['is_secret'],
       'has_sh' => $_GPC['has_sh'],
       'yunfei' => $_GPC['yunfei'],
       'deliveryTime' => $_GPC['deliveryTime'],
      'thumb'=>$wxId,
    );
    // echo $_GPC['name'];
    $isup = pdo_update(GARCIA_PREFIX.'fabu',$data,array('id'=>$fid));

    die(json_encode(array('msg'=>'保存成功','status_code'=>1)));
    exit;
  }else if($dopost=='save_project'){

    $is_diy  = pdo_fetchcolumn('SELECT project_plus5 as is_diy FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid."  and id=".$_GPC['pid']);



    if(strstr($_GPC['rand_day'],"1970")&&$is_diy!=1){
          message('保存失败请重试',referer(),'error');
    }

    //判断是否少于当天
     $_pd = strtotime($_GPC['rand_day']);

     $_cd = strtotime(date('Y-m-d',time()));
    if($_pd<=$_cd&&$is_diy!=1){
      $this->_TplHtml('时间设置出错请重试',referer(),'error');
    }
    $tar_monet = $_GPC['tar_monet'];
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
    if($tar_monet<1&&$is_diy!=1){
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

    $is_shenhe = pdo_fetchcolumn("SELECT is_shenhe FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$pid);
    $project_name = pdo_fetchcolumn("SELECT project_name FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$pid);
    $nickname = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
    $touser = pdo_fetchcolumn('SELECT openid FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);

    $nickname = urldecode($nickname);
    if($is_shenhe==1){
       $status=4;
    }else{
      $status=1;
    }

    $data = array(
      'tar_monet'=>$tar_monet,
      'rand_time'=>$_GPC['rand_time'],
      'rand_day'=>$_GPC['rand_day'],
      'project_texdesc'=>$_GPC['project_texdesc'],
      'name'=>$_GPC['name'],
      'use'=>$_GPC['use'],
      'cur_day'=>$_GPC['cur_day'],
      'thumb'=>$wxId,
      'detail'=>mb_substr(strip_tags($_GPC['project_texdesc']),0,20,'utf8'),
      'cover_thumb'=>$cover_thumb,
      'is_secret' => $_GPC['is_secret'],
      'has_sh' => $_GPC['has_sh'],
      'yunfei' => $_GPC['yunfei'],
      'deliveryTime' => $_GPC['deliveryTime'],
      'upbdate'=>time(),
      'status'=>$status,
      'dream'=>$_dream,
    );



    /**
     * 获取管理员
     */
     $_d = array(
         "{nickname}",
         "{desc}",
         "{time}",
         "{url}",
         "{title}",

     );
     $_m = array(
         urldecode($nickname),
         mb_substr(strip_tags($_GPC['project_texdesc']),0,20,'utf8'),
         date('Y-m-d H:i:s',time()),
         "<a href='".$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2)."'>进入项目</a>",
         $_GPC['name'],
     );

     $acc_json = $this->jsonfile;

     $token = $this->wapi->getAccessToken($acc_json);
     $sql = "SELECT allow,allows,openid FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and is_manger=1 and openid!=''";
     $_man = pdo_fetchall($sql);

     foreach ($_man as $key => $value) {
        if($value['allows']==999){

            if($this->sys['kf_news_type']==1){
                $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_news_tmp']);
                 $temp_id = $temp['tempid'];
                 $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
                 $url =  str_replace("{url}",$_url,$temp['url']);
                $parama = json_decode($temp['parama'],true);

                foreach ($parama as $key => $value) {
                   foreach ($value as $k => $v) {
                        if($k=='value'){
                             $parama[$key][$k] = str_replace($_d,$_m,$v);
                        }else if($k=='color'){
                            if(empty($v)){
                               $parama[$key][$k] = '#333333';
                            }
                        }
                   }
                }
                  $a = $this->wapi->sendTemplate($value['openid'],$temp_id,$url,$token,$parama);
            }else{
                $message = str_replace($_d,$_m,$this->sys['kf_news']);
                $a = $this->wapi->sendText($value['openid'],urlencode($message),$token);
            }
        }else{
            $_all = explode(',', $value['allow']);
            if(in_array($pid,$_all)){
              if($this->sys['kf_news_type']==1){
                  $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_news_tmp']);
                   $temp_id = $temp['tempid'];
                   $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
                   $url =  str_replace("{url}",$_url,$temp['url']);
                  $parama = json_decode($temp['parama'],true);
                  foreach ($parama as $key => $value) {
                     foreach ($value as $k => $v) {
                          if($k=='value'){
                               $parama[$key][$k] = str_replace($_d,$_m,$v);
                          }else if($k=='color'){
                              if(empty($v)){
                                 $parama[$key][$k] = '#333333';
                              }
                          }
                     }
                  }
                    $a = $this->wapi->sendTemplate($value['openid'],$temp_id,$url,$token,$parama);
              }else{
                  $message = str_replace($_d,$_m,$this->sys['kf_news']);
                   $_res = $this->wapi->sendText($value['openid'],urlencode($message),$token);
              }
            }
        }
     }


    pdo_update(GARCIA_PREFIX.'fabu',$data,array('id'=>$fid));
    if($is_shenhe==1){
      echo "<script language='javascript'
      type='text/javascript'>";
      echo "window.location.href='".$this->createMobileUrl('fsuccess',array('dopost'=>'shenhe','fid'=>$fid))."'";
      echo "</script>";
    }else{
      echo "<script language='javascript'
      type='text/javascript'>";
      echo "window.location.href='".$this->createMobileUrl('fsuccess',array('dopost'=>'success','fid'=>$fid))."'";
      echo "</script>";
    }
    exit;

  }


  /**
   * 创建发布项目草稿
   */
   $draft = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."fabu")." where pid=".$_GPC['id']." and weid=".$this->weid." and status=0  and mid=".$mid);
  if(!$draft){
     $data = array(
       'weid'=>$this->weid,
       'upbdate'=>time(),
       'mid'=>$mid,
       'pid'=>$_GPC['id']
     );
     if(!empty($mid)){
       pdo_insert(GARCIA_PREFIX."fabu",$data);
       $fid = pdo_insertid();
     }
  }else{
     $fid = $draft;
     $_checkfb = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$fid);
  }

  $index_url = $this->createMobileUrl('index');
  $project = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." AND id=".$_GPC['id']);
  $pid = $project['id'];
  $_id = json_decode($_checkfb['thumb'],true);
  foreach ($_id as $k => $v) {
      if($k==0){
         $__id = $v;
      }else{
         $__id.=",".$v;
      }
  }

  if(!empty($__id)){
    $_s = explode(',',$__id);
    if(!empty($_s[0])){
        $thumb = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$__id.")");
    }

  }

  include $this->template('fabu');

 ?>
