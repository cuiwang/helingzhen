<?php

  $this->_wapi();
 $dopost = $_GPC['dopost'];
   $display = empty($_GPC['display'])?'index':$_GPC['display'];

   $id = $_GPC['id'];
   if($dopost=='save'){



       /**
        * 基本参数
        */
        if(is_array($_GPC['sitebanner'])){
          $_GPC['sitebanner']  = json_encode($_GPC['sitebanner']);
        }
        $data  = array(
          'sitename'=>$_GPC['sitename'],
          'sitebanner'=>$_GPC['sitebanner'],
          'share_title'=>$_GPC['share_title'],
          'share_desc'=>$_GPC['share_desc'],
          'share_img'=>$_GPC['share_img'],
          'share_url'=>$_GPC['share_url'],
          'weid'=>$this->weid,
          'head_title1'=>$_GPC['head_title1'],
          'project_fqtk'=>$_GPC['project_fqtk'],
          'about'=>$_GPC['about'],
          'tel'=>$_GPC['tel'],
          'logo'=>$_GPC['logo'],
          'detaillogo'=>$_GPC['detaillogo'],
          'project_header'=>$_GPC['project_header'],
          'help_back'=>$_GPC['help_back'],
          'liuyan'=>$_GPC['liuyan'],
          'yanzheng'=>$_GPC['yanzheng'],
          'faqishuomingwenzi'=>$_GPC['faqishuomingwenzi'],
          'mobileshuom'=>$_GPC['mobileshuom'],
          'mobileshuom_desc'=>$_GPC['mobileshuom_desc'],
          'ziliao_tips'=>$_GPC['ziliao_tips'],
          'web_logo' =>$_GPC['web_logo'],
          'web_logob' =>$_GPC['web_logob'],
          'zhengshishuoming'=>$_GPC['zhengshishuoming'],
          'supr_rank'=>$_GPC['supr_rank'],
          'follow_display'=>$_GPC['follow_display'],
          'follow_btn_envt'=>$_GPC['follow_btn_envt'],
          'follow_logo'=>$_GPC['follow_logo'],
          'follow_qrcode'=>$_GPC['follow_qrcode'],
          'follow_btn'=>$_GPC['follow_btn'],
          'follow_txt'=>$_GPC['follow_txt'],
          'follow_url'=>$_GPC['follow_url'],
          'follow_qrcode_txt'=>$_GPC['follow_qrcode_txt'],
          'user_img'=>$_GPC['user_img'],
          'is_h5'=>$_GPC['is_h5'],
          'dongtai_notice'=>$_GPC['dongtai_notice'],
          'is_memache'=>$_GPC['is_memache'],
          'memcachelink'=>$_GPC['memcachelink'],
          'memcacheprot'=>$_GPC['memcacheprot'],
          'copyright'=>$_GPC['copyright'],
          'is_fabu'=>$_GPC['is_fabu'],
          'service'=>$_GPC['service'],
          'telme'=>$_GPC['telme'],
          'pc_qrcode'=>$_GPC['pc_qrcode'],
          'default_img'=>$_GPC['default_img'],
          'default_img2'=>$_GPC['default_img2'],
          'youkuid'=>$_GPC['youkuid'],
          'weibourl'=>$_GPC['weibourl'],
          'webcolor'=>$_GPC['webcolor'],
          'is_mobile'=>$_GPC['is_mobile'],
          'index_use_pic'=>$_GPC['index_use_pic'],
        );

        if(empty($id)){
            $data['upbdate']=time();
            pdo_insert(GARCIA_PREFIX."setting",$data);
        }else{
            pdo_update(GARCIA_PREFIX."setting",$data,array('id'=>$id));
        }
         message('保存成功!',referer(),'success');
   }

   else if($dopost=='del_temp'){

     $id  = $_GPC['id'];
     pdo_delete(GARCIA_PREFIX."temp",array('id'=>$id));
     message('删除成功!',referer(),'success');
   }
else if($dopost=='save_media'){
   $data = array(
      'weid'=>$this->weid,
      'title'=>$_GPC['title'],
      'desction'=>$_GPC['desction'],
      'type'=>$_GPC['type'],
      'url'=>$_GPC['url'],
      'videourl'=>$_GPC['videourl'],
      'contents'=>$_GPC['contents'],
   );

   if($_GPC['id']){
      pdo_update(GARCIA_PREFIX."media",$data,array('id'=>$_GPC['id']));
   }else{
      $data['upbdate'] = time();
      pdo_insert(GARCIA_PREFIX."media",$data);
   }
         message('保存成功!',$this->createWebUrl('setting',array('display'=>'media')),'success');
  exit;
}
   else if($dopost=='save_file'){
       $data = array(
          'qniu_access'=>$_GPC['qniu_access'],
          'qniu_secret'=>$_GPC['qniu_secret'],
          'qniu_bucket'=>$_GPC['qniu_bucket'],
          'qniu_url'=>$_GPC['qniu_url'],
          'oss_access'=>$_GPC['oss_access'],
          'oss_secret'=>$_GPC['oss_secret'],
          'oss_bucket'=>$_GPC['oss_bucket'],
          'oss_url'=>$_GPC['oss_url'],
          'oss_endpoint'=>$_GPC['oss_endpoint'],
          'file_type'=>$_GPC['file_type'],
          'weid'=>$this->weid,
       );
       if(empty($id)){
           $data['upbdate']=time();
           pdo_insert(GARCIA_PREFIX."setting",$data);
       }else{
           pdo_update(GARCIA_PREFIX."setting",$data,array('id'=>$id));
       }
        message('保存成功!',referer(),'success');
   }else if($dopost=='save_pay'){
       $pay_type = $_GPC['pay_type'];
       $apiclient_cert = $_GPC['apiclient_cert'];
       $apiclient_key = $_GPC['apiclient_key'];
       $rootca = $_GPC['rootca'];

       $this->wapi->createPem($apiclient_cert,$apiclient_key,$rootca);
       $data = array(
              'pay_type'=>$pay_type,
              'weid'=>$this->weid,
              'pay_appid'=>trim($_GPC['pay_appid']),
              'pay_appsecret'=>trim($_GPC['pay_appsecret']),
              'pay_miyao'=>trim($_GPC['pay_miyao']),
              'pay_number'=>trim($_GPC['pay_number']),
              'pay_com_title'=>$_GPC['pay_com_title'],
              'pay_hb_wishing'=>$_GPC['pay_hb_wishing'],
              'pay_hb_send_name'=>$_GPC['pay_hb_send_name'],
              'pay_hb_act_name'=>$_GPC['pay_hb_act_name'],
              'pay_hb_remark'=>$_GPC['pay_hb_remark'],
              'ip_address'=>$_GPC['ip_address'],
              'weixn_width'=>$_GPC['weixn_width'],
              'tixian_suilv'=>$_GPC['tixian_suilv'],
              'bro_wx'=>empty($_GPC['bro_wx'])?0:$_GPC['bro_wx'],
              'alipay_type'=>$_GPC['alipay_type']
       );

       if(empty($id)){
           $data['upbdate']=time();
           pdo_insert(GARCIA_PREFIX."setting",$data);
       }else{
           pdo_update(GARCIA_PREFIX."setting",$data,array('id'=>$id));
       }
        message('保存成功!',referer(),'success');

   }else if($dopost=='save_share'){
       $data = array(
          'weibo_content'=>$_GPC['weibo_content'],
          'weibo_pic'=>$_GPC['weibo_pic'],
          'weibo_url'=>$_GPC['weibo_url'],
          'qqzon_content'=>$_GPC['qqzon_content'],
          'qqzon_pic'=>$_GPC['qqzon_pic'],
          'qqzon_url'=>$_GPC['qqzon_url'],
          'qqweibo_content'=>$_GPC['qqweibo_content'],
          'qqweibo_pic'=>$_GPC['qqweibo_pic'],
          'qqweibo_url'=>$_GPC['qqweibo_url'],
          'qq_content'=>$_GPC['qq_content'],
          'qq_pic'=>$_GPC['qq_pic'],
          'qq_url'=>$_GPC['qq_url'],
          'weid'=>$this->weid,
       );
       if(empty($id)){
           $data['upbdate']=time();
           pdo_insert(GARCIA_PREFIX."setting",$data);
       }else{
           pdo_update(GARCIA_PREFIX."setting",$data,array('id'=>$id));
       }
        message('保存成功!',referer(),'success');
   }else if($dopost=='save_msg'){





      $data = array(
        'dayu_appkey'=>$_GPC['dayu_appkey'],
        'dayu_secretkey'=>$_GPC['dayu_secretkey'],
        'dayu_sign'=>$_GPC['dayu_sign'],
        'dayu_temp'=>$_GPC['dayu_temp'],
        'weid'=>$this->weid,
      );

      if(is_array($_GPC['filename'])){
          foreach ($_GPC['filename'] as $k => $v) {
              $tmp[$k]= array(
                 'file'=>$v,
                 'value'=>$_GPC['filevalues'][$k]
              );
          }
          $data['dayu_files'] = json_encode($tmp);
      }
      if(empty($id)){
          $data['upbdate']=time();
          pdo_insert(GARCIA_PREFIX."setting",$data);
      }else{
          pdo_update(GARCIA_PREFIX."setting",$data,array('id'=>$id));
      }
     message('保存成功!',referer(),'success');
   }else if($dopost=='save_banner'){
       $data = array(
        'thumb_name'=>$_GPC['thumb_name'],
        'thumb'=>tomedia($_GPC['thumb']),
        'thumb_rank'=>$_GPC['thumb_rank'],
        'thumb_url'=>$_GPC['thumb_url'],
        'weid'=>$this->weid,
        'type'=>$_GPC['type'],
      );
      if(empty($id)){
          $data['upbdate']=time();
          pdo_insert(GARCIA_PREFIX."banner",$data);
            message('保存成功!',referer(),'success');
      }else{
          pdo_update(GARCIA_PREFIX."banner",$data,array('id'=>$id));
           message('保存成功!',$this->createWebUrl('setting',array('display'=>'banner')),'success');
      }

   }else if($dopost=='save_kf'){
       $data = array(
        'kf_sup_success'=>$_GPC['kf_sup_success'],
        'kf_sup_type'=>$_GPC['kf_sup_type'],
        'kf_sup_temp'=>$_GPC['kf_sup_temp'],
        'kf_pl_success'=>$_GPC['kf_pl_success'],
        'kf_pl_type'=>$_GPC['kf_pl_type'],
        'kf_pl_tmp'=>$_GPC['kf_pl_tmp'],
        'kf_news'=>$_GPC['kf_news'],
        'kf_news_type'=>$_GPC['kf_news_type'],
        'kf_news_tmp'=>$_GPC['kf_news_tmp'],
        'kf_cksuccess_type'=>$_GPC['kf_cksuccess_type'],
        'kf_cksuccess_content'=>$_GPC['kf_cksuccess_content'],
        'kf_cksuccess_tmp'=>$_GPC['kf_cksuccess_tmp'],
        'kf_ckfail_type'=>$_GPC['kf_ckfail_type'],
        'kf_ckfail_content'=>$_GPC['kf_ckfail_content'],
        'kf_ckfail_tmp'=>$_GPC['kf_ckfail_tmp'],
        'kf_tuikuan_type'=>$_GPC['kf_tuikuan_type'],
        'kf_tuikuan_content'=>$_GPC['kf_tuikuan_content'],
        'kf_tuikuan_tmp'=>$_GPC['kf_tuikuan_tmp'],
        'weid'=>$this->weid,
      );
      if(empty($id)){
          $data['upbdate']=time();
          pdo_insert(GARCIA_PREFIX."setting",$data);
            message('保存成功!',referer(),'success');
      }else{
          pdo_update(GARCIA_PREFIX."setting",$data,array('id'=>$id));
           message('保存成功!',$this->createWebUrl('setting',array('display'=>'kfxiaoxi')),'success');
      }
   }else if($dopost=='save_temp'){

     $parama=array();
     if(isset($_GPC['parama-key'])){
       foreach ($_GPC['parama-key'] as $index => $row) {
         if(empty($row))continue;
         $parama[$row]['value']=urlencode($_GPC['parama-val'][$index]);
         $parama[$row]['color']=$_GPC['parama-color'][$index];
       }
     }
     $data['parama']=urldecode(json_encode($parama));
     $data['weid'] = $this->weid;
     $data['catename'] = $_GPC['catename'];
     $data['tempid'] = $_GPC['tempid'];
     $data['url'] = $_GPC['url'];
     $data['content'] = $_GPC['content'];

     if (!empty($_GPC['id'])) {
       pdo_update(GARCIA_PREFIX.'temp', $data, array('id' => $_GPC['id']));
     } else {
          $data['upbdate'] =time();
       pdo_insert(GARCIA_PREFIX.'temp', $data);

     }
     	message('更新成功！',referer(), 'success');
     exit;
   }
   else if($dopost=='ajax_manger'){

      $openid = $_GPC['openid'];
      $_conf = pdo_fetch('SELECT headimgurl,nickname,openid FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and openid='".$openid."'");
      $data  = array(
          'thumb'=>$_conf['headimgurl'],
          'nickname'=>urldecode($_conf['nickname']),
          'openid'=>$_conf['openid'],
      );
     die(json_encode($data));
   }else if($dopost=='save_manger'){
       $allows = $_GPC['allows'];
       $allow = $_GPC['allow'];
       $openid = $_GPC['openid'];
       $_conf = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and openid='".$openid."'");
       if(is_array($allow)){
          foreach ($allow as $k => $v) {
            if($k==0){
               $_alliow.= $v;
            }else{
               $_alliow.= ",".$v;
            }
          }
       }
       if(empty($openid)){
         message('请输入openid',referer(),'error');
       }else if(empty($_alliow)&&empty($allows)){
         message('请选择项目',referer(),'error');
       }else if(empty($_conf)){
          message('没检测到该用户',referer(),'error');
       }
       pdo_update(GARCIA_PREFIX."member",array('allows'=>$allows,'allow'=>$_alliow,'is_manger'=>1),array('openid'=>$openid,'weid'=>$this->weid));
       message('操作成功',$this->createWebUrl('setting',array('display'=>'manger')),'success');
       exit;
   }
   else if($dopost=='del_manger'){
     $id = $_GPC['id'];
     pdo_update(GARCIA_PREFIX."member",array('is_manger'=>0),array('id'=>$id,'weid'=>$this->weid));
     message('操作成功',$this->createWebUrl('setting',array('display'=>'manger')),'success');
   }else if($dopost=='del_banner'){
     $id = $_GPC['id'];
     pdo_delete(GARCIA_PREFIX."banner",array('id'=>$id,'weid'=>$this->weid));
     message('删除成功',referer(),'success');
   }else if($dopost=='save_special'){
     $data = array(
        'weid'=>$this->weid,
        'verifypeer'=>$_GPC['verifypeer'],
     );
     if (!empty($_GPC['id'])) {
       pdo_update(GARCIA_PREFIX.'setting', $data, array('id' => $_GPC['id']));
     } else {
          $data['upbdate'] =time();
       pdo_insert(GARCIA_PREFIX.'setting', $data);

     }
       message('更新成功！',referer(), 'success');
     exit;
   }else if($dopost=='save_pc'){
        $id = $_GPC['id'];
      $data = array(
        'pc_1_pic' =>tomedia($_GPC['pc_1_pic']),
        'pc_2_pic' =>tomedia($_GPC['pc_2_pic']),
        'pc_3_pic' =>tomedia($_GPC['pc_3_pic']),
      );
      if (!empty($_GPC['id'])) {
        pdo_update(GARCIA_PREFIX.'setting', $data, array('id' => $_GPC['id']));
      } else {
           $data['upbdate'] =time();
        pdo_insert(GARCIA_PREFIX.'setting', $data);

      }
      message('更新成功！',referer(), 'success');
    exit;
  }else if($dopost=='save_huzhu'){

          if(!empty($_GPC['hz_title'])){


            foreach ($_GPC['hz_title'] as $key => $value) {
                 $_temp[]= array(
                     'title'=>$value,
                     'content'=>$_GPC['hz_content'][$key],
                     'rank'=>empty($_GPC['hz_rank'][$key])?1:$_GPC['hz_rank'][$key],
                 );
            }
              $hz_question = json_encode($_temp);

          }
         $data =array(
           'huzhu_desc'=>$_GPC['huzhu_desc'],
           'thirth_desc'=>$_GPC['thirth_desc'],
           'hz_share_desc'=>$_GPC['hz_share_desc'],
           'vip_desc'=>$_GPC['vip_desc'],
           'pan_desc'=>$_GPC['pan_desc'],
           'hz_question'=>$hz_question,
            'hz_img'=>$_GPC['hz_img'],
            'hz_img_index'=>$_GPC['hz_img_index'],
            'hz_switch'=>$_GPC['hz_switch'],
            'hz_money'=>$_GPC['hz_money'],
            'hz_gongyue'=>$_GPC['hz_gongyue'],
           'hz_jiangkang'=>$_GPC['hz_jiangkang'],
         );
         if (!empty($_GPC['id'])) {
           pdo_update(GARCIA_PREFIX.'setting', $data, array('id' => $_GPC['id']));
         } else {
              $data['upbdate'] =time();
           pdo_insert(GARCIA_PREFIX.'setting', $data);

         }
         message('更新成功！',referer(), 'success');
         exit;

  }




  /**
   * 显示页面
   */

   if($display=='banner'){
     if($_GPC['action']=='editor'){
        $banner = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."banner").' where weid='.$this->weid." and id=".$id);
     }
      else{
          $list  = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."banner")." where weid=".$this->weid);
      }
   }else if($display=='temp'){
      $liist = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid);
      if($_GPC['action']=='editor'){

          $item = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$_GPC['id']);
      }
   }else if($display=='kfxiaoxi'){
       $_temp = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid);
   }else if($display=='manger'){
      $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid);
     $_list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and is_manger=1");

     if($_GPC['action']=='editor'){
         $item = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id='".$_GPC['id']."'");
     }
   }else if($display=='huzhu'){
       $question = pdo_fetchcolumn('SELECT hz_question FROM '.tablename(GARCIA_PREFIX."setting")." where weid=".$this->weid);
       $question = json_decode($question,true);
       foreach ($question as $key => $value) {
            $_temp['list'][$value['rank']] = $value;
       }
       asort($_temp);
       $question= json_encode($_temp);
      //  $question = json_encode($question);
   }else if($display=='index'){

   }else if($display=='media'){
      $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."media")." where weid=".$this->weid;
      $list = pdo_fetchall($sql);
   }else if($display=='addmedia'){
     if($_GPC['id']){
        $sql = "SELECT *  FROM ".tablename(GARCIA_PREFIX."media")." where weid=".$this->weid." and id=".$_GPC['id'];
        $item = pdo_fetch($sql);
     }
   }

   include $this->template('admin/setting/'.$display);

 ?>
