<?php


  $fid = $_GPC['fid'];

  $is_share = pdo_fetchcolumn('SELECT is_share FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." AND id=".$fid);
  $_is_share = $is_share+1;
  pdo_update(GARCIA_PREFIX."fabu",array('is_share'=>$_is_share),array('id'=>$fid));


 ?>
