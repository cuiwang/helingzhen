<?php

$dopost  = $_GPC['dopost'];



if($dopost=='save'){
    $kname = $_GPC['kname'];
    $kmou = $_GPC['kmou'];
    $kprecent = $_GPC['kprecent'];
    $id = $_GPC['id'];
    $data = array(
      'kname'    => $_GPC['kname'],
      'kmou'     => $_GPC['kmou'],
      'kprecent' => $_GPC['kprecent']/100,
    );

    if(!empty($id)){
        $a = pdo_update(GARCIA_PREFIX.'kids',$data,array('id'=>$id));

    }else{
      $data['upbdate'] = time();
      $data['weid'] = $this->weid;
       $a = pdo_Insert(GARCIA_PREFIX.'kids',$data);
    }
    message('保存成功',$this->createWebUrl('kids'),'success');
    exit;
}else if($dopost=='edit'){
   $config = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."kids")." where weid=".$this->weid." and id=".$_GPC['id']);
}else if($dopost=='del'){
  pdo_delete(GARCIA_PREFIX."kids",array('id'=>$_GPC['id']));
  message('删除成功',$this->createWebUrl('kids'),'success');
}


$list  = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."kids")." where weid=".$this->weid);
include $this->template('web/kids');
 ?>
