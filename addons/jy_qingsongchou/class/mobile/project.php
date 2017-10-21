<?php

   if($this->sys['is_h5']&&$this->modal == 'phone'){
     if(!$this->_login){
        header('Location:'.$this->createMobileUrl('login'));
     }
     $this->_checkMobile();
     if(!$this->_OauthMobile){
       $this->_TplHtml('请先绑定联系手机',$this->createMobileUrl('member',array('display'=>'phone')),'error');
       exit;
     }
     $dopost = $_GPC['dopost'];
     $pre_id = $_GPC['pre_id'];
     if(!empty($pre_id)){
        $_projectlist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$pre_id." order by rank");
     }else{
        $_projectlist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=0 order by rank");
     }

     foreach ($_projectlist as $key => $value) {
      foreach ($value as $k => $v) {
        if($k=='project_logo'){
           $value[$k] = tomedia($v);
        }
      }
       if(empty($pre_id)){
        $value['son'] = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$value['id']);
      }
        if($value['son']>0){
          $value['url'] = $this->createMobileUrl('project',array('pre_id'=>$value['id']));
        }else{
          $value['url'] = $this->createMobileUrl('fabu',array('id'=>$value['id']));
        }

        $data[] = $value;
     }
     $this->config['list'] = $data;
   $this->h5t('project/index');
   }else{
      $this->_checkMobile();
     if(!$this->_OauthMobile){
       // message('请先认证联系手机',$this->createMobileUrl('member',array('display'=>'phone')),'error');
       $this->_TplHtml('请先绑定联系手机',$this->createMobileUrl('member',array('display'=>'phone')),'error');
       exit;
     }
        $dopost = $_GPC['dopost'];
        $pre_id = $_GPC['pre_id'];
        if(!empty($pre_id)){
           $_projectlist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$pre_id." order by rank");
        }else{
           $_projectlist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=0 order by rank");
        }

        foreach ($_projectlist as $key => $value) {
         foreach ($value as $k => $v) {
           if($k=='project_logo'){
              $value[$k] = tomedia($v);
           }
         }
          if(empty($pre_id)){
           $value['son'] = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$value['id']);
         }
           if($value['son']>0){
             $value['url'] = $this->createMobileUrl('project',array('pre_id'=>$value['id']));
           }else{
             $value['url'] = $this->createMobileUrl('fabu',array('id'=>$value['id']));
           }

           $data[] = $value;
        }
   include $this->template('project');
   }

 ?>
