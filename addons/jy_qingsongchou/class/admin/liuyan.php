<?php


  $_list  = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar,b.openid FROM '.tablename(GARCIA_PREFIX."liuyan")." a
  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
  where a.weid=".$this->weid);

  if($_GPC['dopost']=='del'){
      pdo_delete(GARCIA_PREFIX."liuyan",array('id'=>$_GPC['id']));
      message('删除成功',referer(),'success');
  }

    include $this->template('admin/liuyan/index');

 ?>
