<?php


    $this->cookies->clear('userDatas');
    $this->cookies->clear('userStatus');
  $this->_TplHtml('退出成功',$this->createMobileUrl('index'),'success');
 ?>
