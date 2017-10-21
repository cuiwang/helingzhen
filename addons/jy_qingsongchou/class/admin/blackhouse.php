<?php

    $dopost = $_GPC['dopost'];

    if($dopost=='bai'){
       pdo_update(GARCIA_PREFIX."member",array('status'=>0),array('id'=>$_GPC['id']));
         message('拉白成功',referer(),'success');
    }else if($dopost=='aways'){
       pdo_update(GARCIA_PREFIX."member",array('status'=>9),array('id'=>$_GPC['id']));
        message('已被终身封号',referer(),'success');
    }

   $list = pdo_fetchall('SELECT id,nickname,headimgurl as avatar,openid FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and status=3");
   include $this->template('admin/blackhouse/index');
 ?>
