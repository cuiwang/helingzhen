<?php


     if($action=='getlist'){
         $banner = pdo_fetchall("SELECT id,thumb FROM ".tablename(GARCIA_PREFIX."banner")." where weid=".$this->weid." order by thumb_rank asc");
         $md5 = pdo_fetchcolumn('SELECT group_concat(thumb) FROM '.tablename(GARCIA_PREFIX."banner")." where weid=".$this->weid." order by thumb_rank asc");
         $md5 = md5($md5);
         _success(array('list'=>$banner,'md5'=>$md5));
     }
     else if($action=='checkmd5'){
       $md5 = pdo_fetchcolumn('SELECT group_concat(thumb) FROM '.tablename(GARCIA_PREFIX."banner")." where weid=".$this->weid." order by thumb_rank asc");
       $md5 = md5($md5);
      _success(array('md5'=>$md5));
     }
     else{
         _fail(array('msg'=>'not found function'));
     }



 ?>
