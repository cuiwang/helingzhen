<?php




   $display = empty($_GPC['display'])?'list':$_GPC['display'];

   if($display=='detail'){
        $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."media")." where weid=".$this->weid." and id=".$_GPC['id']."";
        $conf = pdo_fetch($sql);
   }else if($display=='list'){
       $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."media")." where weid=".$this->weid." order by upbdate desc limit 0,10";
       $list = pdo_fetchall($sql);

   }
   include $this->template('web/media/'.$display);
 ?>
