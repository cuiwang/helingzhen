<?php


 $display = empty($_GPC['display'])?'index':$_GPC['display'];
 $dopost = $_GPC['dopost'];

   if($dopost=='save_member'){
     $data = array(
        'headimgurl'=>$_GPC['headimgurl'],
        'nickname'=>$_GPC['nickname'],
     );
     pdo_update(GARCIA_PREFIX."member",$data,array('id'=>$_GPC['mid']));
    //  var_dump($data);
    $this->_TplHtml('保存成功！',referer(),'success');
     exit;
   }
   else if($dopost=='save_address'){
      $data = array(
          'name'=>$_GPC['name'],
          'tel'=>$_GPC['tel'],
          'province'=>$_GPC['province'],
          'city'=>$_GPC['city'],
          'area'=>$_GPC['area'],
          'address'=>$_GPC['address'],
          'is_def' => $_GPC['is_def']
      );
      if(empty($_GPC['id'])){
         $data['weid'] = $this->weid;
         $data['mid'] = $this->conf['user']['mid'];

         if($_GPC['is_def']==1){
             pdo_update(GARCIA_PREFIX."address",array('is_def'=>0),array('mid'=>$this->conf['user']['mid']));
         }
         pdo_insert(GARCIA_PREFIX."address",$data);
        // var_dump($data);
        // exit;
      }
      else{
          if($_GPC['is_def']==1){
              pdo_update(GARCIA_PREFIX."address",array('is_def'=>'0'),array('mid'=>$this->conf['user']['mid']));
          }
          pdo_update(GARCIA_PREFIX."address",$data,array('id'=>$_GPC['id']));
      }
        $this->_TplHtml('保存成功！',$this->createMobileUrl('msetting',array('display'=>'address')),'success');
      exit;
   }else if($dopost=='save_bank'){
       $data = array(
              'type'=>$_GPC['type'],
              'holder'=>$_GPC['holder'],
              'cardNo'=>$_GPC['cardNo'],
              'bank'=>$_GPC['bank'],
              'mid'=>$this->conf['user']['mid'],
              'weid'=>$this->weid,
       );

       if($_GPC['id']){
          pdo_update(GARCIA_PREFIX."bank",$data,array('id'=>$_GPC['id']));
       }else{
          pdo_insert(GARCIA_PREFIX."bank",$data);
       }
     $this->_TplHtml('保存成功！',$this->createMobileUrl('msetting',array('display'=>'bank')),'success');
      //  var_dump($data);
       exit;
   }


   if($display=='index'){
       $member = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$this->conf['user']['mid']);
   }
   else if($display=='address'){
     $sql = "SELECT *  from ".tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and mid=".$this->conf['user']['mid']." order by is_def desc";
     $address = pdo_fetchall($sql);
   }

   else if($display=='editor_address'){
       $id =$_GPC['id'];
       if(!empty($id)){
         $sql = "SELECT *  FROM ".tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and id =".$id;
         $address = pdo_fetch($sql);
       }

   }

   else if($display=='bank'){
        $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."bank")." where weid=".$this->weid." and mid = ".$this->conf['user']['mid'];
        $list = pdo_fetchall($sql);

   }else if($display=='editor_bank'){
       $id =$_GPC['id'];

       if(!empty($id)){
          $conf = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX.'bank')." where weid=".$this->weid." and id = ".$_GPC['id']);
       }
   }
  include $this->template('web/setting/'.$display);
 ?>
