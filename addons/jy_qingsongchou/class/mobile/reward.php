  <?php

   $dopost = $_GPC['dopost'];
   $fid = $_GPC['fid'];
    $tid = $_GPC['tid'];
    $mid = $this->_gmodaluserid();
    $tdo = $_GPC['tdo'];
  $reward = pdo_fetchcolumn('SELECT reward from '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." AND id=".$fid);

  if($dopost=='save'){

    $data = array(
      'supportNumber'=>$_GPC['supportNumber'],
      'supportContent'=>$_GPC['supportContent'],
      'thumb'=>$_GPC['thumb'],
      'places'=>$_GPC['places'],
    );
    if(empty($reward)){
        $data['tid']=time();
        $data = array(time()=>$data);
        $data = json_encode($data);
        pdo_update(GARCIA_PREFIX.'fabu',array('reward'=>$data),array('id'=>$fid));
    }else{
      if(!empty($_GPC['tid'])){
          $data['tid']=$_GPC['tid'];
        $reward = json_decode($reward,true);
        $reward[$_GPC['tid']] = $data;
            $reward = json_encode($reward);
      }else{
          $data['tid']=time();
        $reward = json_decode($reward,true);
         $_c = count($reward);
        $reward[time()]=$data;

        $reward = json_encode($reward);
      }

    // var_dump($reward);
       pdo_update(GARCIA_PREFIX.'fabu',array('reward'=>$reward),array('id'=>$fid));
    }
    if($tdo=='editor'){
      $url = $this->createMobileUrl('editor',array('id'=>$fid,'not'=>'yes'));

    }else{
        $url = $this->createMobileUrl('fabu',array('id'=>$_GPC['pid'],'not'=>'yes'));
    }


      echo "<script language='javascript'
      type='text/javascript'>";
      echo "window.location.href='$url'";
      echo "</script>";
    // var_dump($_GPC['fid']);

    exit;
  }else if($dopost=='del'){
     $id = $_GPC['fid'];
     $tid = $_GPC['tid'];
     $pid = $_GPC['pid'];
     $reward = json_decode($reward,true);
     foreach ($reward as $key => $value) {
         if($key==$tid){
            continue;
         }else{
           $temp[$key] = $value;
         }
     }
     $reward = json_encode($temp);
  //  echo $id;
     pdo_update(GARCIA_PREFIX."fabu",array('reward'=>$reward),array('id'=>$id));
         $url = $this->createMobileUrl('editor',array('id'=>$id,'not'=>'yes','do'=>'editor'));
     message('删除成功',$url,'success');
  }




  $_reward = json_decode($reward,true);
  $_reward = $_reward[$_GPC['tid']];


  include $this->template('reward');
 ?>
