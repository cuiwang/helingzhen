<?php

    load()->func('tpl');


    $dopost = $_GPC['dopost'];

    if($dopost=='save'){
        $sid = $_GPC['sid'];


      /**
       * 保存证书操作
       */

      $this->wapi->createPem($_GPC['apiclient_cert'],$_GPC['apiclient_key'],$_GPC['rootca']);
       $data = array(
          'share_title'=>$_GPC['share_title'],
          'share_desc'=>$_GPC['share_desc'],
          'share_img'=>$_GPC['share_img'],
          'temp_id'=>$_GPC['temp_id'],
          'weid'=>$this->weid,
          'shanghumiyao'=>$_GPC['shanghumiyao'],
          'appid'=>$_GPC['appid'],
          'wishing'=>$_GPC['wishing'],
          'act_name'=>$_GPC['act_name'],
          'remark'=>$_GPC['remark'],
          'mch_id'=>$_GPC['mch_id'],
          'shanghuname'=>$_GPC['shanghuname'],
          'turl'=>$_GPC['turl'],
       );
       if(empty($sid)){
           $data['upbdate']= time();
           $a = pdo_Insert(GARCIA_PREFIX.'setting',$data);
       }else{
          $a = pdo_update(GARCIA_PREFIX.'setting',$data,array('id'=>$sid));
       }
       message('保存成功',referer(),'success');
       exit;
    }
    include $this->template('web/setting');

 ?>
