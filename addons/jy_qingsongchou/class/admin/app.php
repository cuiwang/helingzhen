<?php
    //  echo $_W['sitename'];
   $index = empty($_GPC['display'])?'app':$_GPC['display'];

   include GARCIA_PATH."/class/admin/app/".$index.".php";
    include $this->template('admin/app/'.$index);
 ?>
