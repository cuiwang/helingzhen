<?php





  if($this->modal=='pc'){

      if($_GPC['dopost']=='relogin'){
          $mid = $_GPC['mid'];
          $this->cookies->set('userStatus','1',(3600*24*7));
          $this->cookies->set('userDatas',json_encode(array('uid'=>$mid)),(3600*24*7));
               header('Location:'.$this->createMobileUrl('index'));
        exit;
      }

        $img =  $this->getBDqrcode($this->conf['user']['mid']);
       include $this->template('web/bangding/index');
  }else if($this->modal=='wechat'){


     $mid = $this->_gmodaluserid();
     $mid = empty($mid)?$_GPC['mid']:$mid;
     $uid = $_GPC['uid'];
     $tel = pdo_fetchcolumn('SELECT tel FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
     $tel2 = pdo_fetchcolumn('SELECT mobile FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$uid);
     if($_GPC['dopost']=='mksure'){

       $_phone  = pdo_fetchcolumn('SELECT tel FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and tel='".$tel2."'");

       if($_phone){
             $this->_TplHtml('该号码已被别人使用',$this->createMobileUrl('index'),'error');
             exit;
       }else{
         pdo_update(GARCIA_PREFIX."member",array('tel'=>$tel2,'mobile'=>$tel2),array('id'=>$mid));
          $this->_TplHtml('绑定成功',$this->createMobileUrl('index'),'success');
       }
       exit;
     }




     $nickname = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
     $nickname = urldecode($nickname);
     include $this->template('web/bangding/bindex');
    exit;
  }


 ?>
