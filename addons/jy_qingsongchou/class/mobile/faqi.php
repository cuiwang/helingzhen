<?php


  if(!$is_login){
    $this->_TplHtml('请先登陆',$_W['siteroot']."app/".substr($this->createMobileUrl('index'),2),'error');
    exit;
  }


  if(!$this->conf['user']['openid']){
     header('Location:'.$this->createMobileUrl('bangding'));
    exit;
  }

    load()->func('tpl');
  $display = empty($_GPC['display'])?'index':$_GPC['display'];

  $dopost = $_GPC['dopost'];

  if($dopost=='save'){
    $mid = $this->conf['user']['mid'];
    $id = $_GPC['id'];

     $is_shenhe = pdo_fetchcolumn("SELECT is_shenhe FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$id);


     if($is_shenhe==1){
        $status=4;
     }else{
       $status=1;
     }
     $wxId = $_GPC['wxId'];
     if($wxId){
       foreach ($wxId as $key => $value) {
          if($key<4){
            $cover_thumb[$key] = $value;
          }
       }
         $cover_thumb = json_encode($cover_thumb);
         $wxId = json_encode($wxId);
     }
     $reward_thumb = $_GPC['reward_thumb'];
     $reward_content = $_GPC['reward_content'];
     $reward_fee = $_GPC['reward_fee'];
     $reward_number = $_GPC['reward_number'];
     $reward_id = $_GPC['reward_id'];
     $reward = '';
     $_dream = '';
     if($reward_thumb){
         foreach ($reward_thumb as $key => $value) {
             $reward [$reward_id[$key]]= array(
               'supportNumber'=>$reward_fee[$key],
               'supportContent'=>$reward_content[$key],
               'thumb'=>$reward_thumb[$key],
               'places'=>$reward_number[$key],
             );
         }
         $reward = json_encode($reward);
     }
     $dream_money = $_GPC['list_fee'];
     $dream_content = $_GPC['list_use'];
     $dream_id = $_GPC['list_id'];
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

    $data = array(
      'tar_monet'=>$_GPC['tar_monet'],
      'rand_day'=>$_GPC['rand_day'],
      'project_texdesc'=>$_GPC['detail'],
      'pid'=>$_GPC['id'],
      'name'=>$_GPC['name'],
      'weid'=>$this->weid,
      'mid'=>$this->conf['user']['mid'],
      'cur_day'=> $this->diffDate(date('Y-m-d',time()),$_GPC['rand_day']),
      'thumb'=>$wxId,
      'detail'=>mb_substr(strip_tags($_GPC['project_texdesc']),0,20,'utf8'),
      'cover_thumb'=>$cover_thumb,
      'is_secret' => $_GPC['is_secret'],
      'has_sh' => $_GPC['has_sh'],
      'yunfei' => $_GPC['yunfei'],
      'deliveryTime' => $_GPC['deliveryTime'],
      'upbdate'=>time(),
      'reward'=>$reward,
      'status'=>$status,
      'dream'=>$_dream,
    );

      pdo_insert(GARCIA_PREFIX."fabu",$data);
      $inserid = pdo_insertid();
               header('Location:'.$this->createMobileUrl('faqi',array('display'=>'set2','id'=>$inserid)));
      // $this->_TplHtml('发布成功',$this->createMobileUrl('detail',array('id'=>$inserid)),'success');
  }
  else if($dopost=='save_editor'){

    $mid = $this->conf['user']['mid'];
    $fid = $_GPC['fid'];
    $id =  pdo_fetchcolumn('SELECT pid FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$fid);

     $is_shenhe = pdo_fetchcolumn("SELECT is_shenhe FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$id);



     $wxId = $_GPC['wxId'];
     if($wxId){
       foreach ($wxId as $key => $value) {
          if($key<4){
            $cover_thumb[$key] = $value;
          }
       }
         $cover_thumb = json_encode($cover_thumb);
         $wxId = json_encode($wxId);
     }
     $reward_thumb = $_GPC['reward_thumb'];
     $reward_content = $_GPC['reward_content'];
     $reward_fee = $_GPC['reward_fee'];
     $reward_number = $_GPC['reward_number'];
     $reward_id = $_GPC['reward_id'];
     $reward = '';
     $_dream = '';
     if($reward_thumb){
         foreach ($reward_thumb as $key => $value) {
             $reward [$reward_id[$key]]= array(
               'supportNumber'=>$reward_fee[$key],
               'supportContent'=>$reward_content[$key],
               'thumb'=>$reward_thumb[$key],
               'places'=>$reward_number[$key],
             );
         }
         $reward = json_encode($reward);
     }
     $dream_money = $_GPC['list_fee'];
     $dream_content = $_GPC['list_use'];
     $dream_id = $_GPC['list_id'];
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

    $data = array(
      'tar_monet'=>$_GPC['tar_monet'],
      'rand_day'=>$_GPC['rand_day'],
      'project_texdesc'=>$_GPC['detail'],
      'name'=>$_GPC['name'],
      'weid'=>$this->weid,
      'cur_day'=> $this->diffDate($_GPC['today'],$_GPC['rand_day']),
      'thumb'=>$wxId,
      'detail'=>mb_substr(strip_tags($_GPC['project_texdesc']),0,20,'utf8'),
      'cover_thumb'=>$cover_thumb,
      'is_secret' => $_GPC['is_secret'],
      'has_sh' => $_GPC['has_sh'],
      'yunfei' => $_GPC['yunfei'],
      'deliveryTime' => $_GPC['deliveryTime'],
      'reward'=>$reward,
      'dream'=>$_dream,
    );

    // var_dump($data);
    pdo_update(GARCIA_PREFIX."fabu",$data,array('id'=>$fid));
    $data = array(
                  'title'=>'操作成功',
                  'desc'=>'项目更新成功',
                  'type'=>1,'btn'=>'返回项目管理',
                  'url'=>$this->createMobileUrl('fmanger',array('id'=>$fid))
                );
    $this->_WebWait($data);
    exit;
  }
  else if($dopost=='save_set3'){
    $sid = $_GPC['sid'];
      $data = array(
          'weid'  => $this->weid,
          'creator_phone'=>$_GPC['tel'],
          'idcar' => $_GPC['reid'],
          'creator_name' => $_GPC['name'],
          'creator_id' =>$_GPC['idcard'],
          'fid'=>$_GPC['fid'],
          'upbdate'=>time(),
          'type'=>0
      );

      if($sid){
        $datap['status']= 0;
          pdo_update(GARCIA_PREFIX."shouchishenfenz",$data,array('id'=>$sid));
      }else{
        $a = pdo_insert(GARCIA_PREFIX."shouchishenfenz",$data);
      }

      // var_dump($a);

      header('Location:'.$this->createMobileUrl('fmanger',array('id'=>$_GPC['fid'])));
     exit;
  }
  else if($dopost=='save_set4'){
      $sid = $_GPC['sid'];
      $data = array(
          'weid'  => $this->weid,
          'creator_phone'=>$_GPC['tel'],
          'zhuzhi' => $_GPC['reid1'],
          'zheng,' => $_GPC['reid2'],
          'creator_name' => $_GPC['name'],
          'creator_id' =>$_GPC['idcard'],
          'fid'=>$_GPC['fid'],
          'upbdate'=>time(),
          'type'=>1
      );
      if($sid){
          $datap['status']= 0;
          pdo_update(GARCIA_PREFIX."shouchishenfenz",$data,array('id'=>$sid));
      }else{
        $a = pdo_insert(GARCIA_PREFIX."shouchishenfenz",$data);
      }

      header('Location:'.$this->createMobileUrl('fmanger',array('id'=>$_GPC['fid'])));
     exit;
  }
  if($display=='index'){

    $project = pdo_fetchall('SELECT id,project_name,project_logo,project_shuoming FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=0 and is_p");
    // var_dump($project);
  }

  else if($display=='editor'){

      if($_GPC['action']=='editor'){
          $id = pdo_fetchcolumn('SELECT pid FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['fid']);
          $fabu = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['fid']);
           $reward = $fabu['reward'];
           $reward = json_decode($reward,true);
           $dream = $fabu['dream'];
           $dream = json_decode($dream,true);

          $ids = json_decode($fabu['thumb'],true);
          foreach ($ids as $k3 => $v3) {
            if(empty($v3))continue;
            if($k3==0){
               $_id.=$v3;
            }else{
               $_id.=",".$v3;
            }
          }
          if(!empty($_id)){
            $_th = pdo_fetchall("SELECT thumb,pic,id FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_id.")");
             foreach ($_th as $k4 => $v4) {
                $_thb[$k4]['pic'] = $v4['pic'];
                $_thb[$k4]['thumb'] = $v4['thumb'];
                $_thb[$k4]['id'] = $v4['id'];
             }
             $thumb = $_thb;
          }

      }else{
         $id = $_GPC['id'];

      }
      $sql = "SELECT id,project_min as minday,project_max as maxday,title_placeholder as placeholder ,project_plus1 as is_address,
            project_plus2 as is_secret,project_plus3 as is_list,project_plus4 as is_reward ,project_shuoming as detail,
            project_mstips as tips,project_texdesc as textdesc,project_name as name,is_use,project_desc as tips FROM ".tablename(GARCIA_PREFIX."project")."
            where weid=".$this->weid." and id=".$id;
     $conf = pdo_fetch($sql);
     if($_GPC['action']=='editor'){
        $minday = $fabu['cur_day'];
        $today = date('Y-m-d',strtotime('-'.$fabu['cur_day'].' day',strtotime($fabu['rand_day'])));
        $lday = $fabu['rand_day'];
     }else{
       $minday = $conf['minday'];
       $today = date('Y-m-d',time());
       $lday = date('Y-m-d',strtotime('+'.$conf['minday'].' day'));

     }


  }else if($display=='set3'){
      $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." and fid=".$_GPC['id']." and type=0";
      $conf = pdo_fetch($sql);
      $id = pdo_fetchcolumn("SELECT id FROM ".tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." and fid=".$_GPC['id']);
  }
  else if($display=='set4'){
      $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." and fid=".$_GPC['id']." and type=1";
      $conf = pdo_fetch($sql);
      $id = pdo_fetchcolumn("SELECT id FROM ".tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." and fid=".$_GPC['id']);

  }
  include $this->template('web/faqi/'.$display);



  function _getson($id){
     global $_W;
     $son = "SELECT id,project_name,project_logo,project_shuoming FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$_W['uniacid']." and pre_id=".$id;
     return pdo_fetchall($son);

  }
 ?>
