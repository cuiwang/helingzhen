<?php

  $dopost = $_GPC['dopost'];

  if($dopost=='del'){
     $id = $_GPC['id'];
     pdo_delete(GARCIA_PREFIX."member",array('id'=>$id));
     message('删除成功',referer(),'success');
     exit;
  }

  if($dopost=='search'){

     if($_GPC['nickname']){
       $search.= "  AND  nickname like '%".urldecode($_GPC['nickname'])."%'   ";
     }
     if($_GPC['mobile']){
       $search.= " AND mobile like '%".$_GPC['mobile']."%' ";
     }
     if($_GPC['name']){
       $search.= " AND name like '%".$_GPC['name']."%' " ;
     }

  }else{
    $search='';
  }

  $sql2 = "SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid;
  $_result = $this->_pager($_GPC['page'],20,$sql2);

    $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." ".$search.$_result['limit'];
  $list = pdo_fetchall($sql);

  include $this->template('web/member');
 ?>
