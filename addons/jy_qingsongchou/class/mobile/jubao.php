<?php

  if($this->sys['is_h5']&&$this->modal == 'phone'){
    if(!$this->_login){
       header('Location:'.$this->createMobileUrl('login'));
    }


  }else{
    $mid = $this->_gmodaluserid();
    $dopost = $_GPC['dopost'];
  }



  if($dopost=='save'){
      $wxid =  $_GPC['wxid'];
      $fid = $_GPC['fid'];
      $report_reason = $_GPC['report_reason'];
      $thumb =  '';
      if(is_array($wxid)){
          $thumb = json_encode($wxid);
      }
      $data = array(
         'weid'=>$this->weid,
         'fid'=>$fid,
         'report_reason'=>$report_reason,
         'thumb'=>$thumb,
         'upbdate'=>time(),
         'mid'=>$mid
      );
      pdo_insert(GARCIA_PREFIX."jubao",$data);
      message('举报成功',$this->createMobileUrl('index'),'seccess');
     exit;
  }



  include $this->template('jubao/index');
 ?>
