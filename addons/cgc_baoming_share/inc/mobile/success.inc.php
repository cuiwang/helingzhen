<?php
    global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $id=intval($_GPC['id']); 
    $op=$_GPC['op'];
    if($op=='pay'){
      $msg="报名成功";
      $err_title="报名成功";
      $label='success';
      $redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('share', array('id' => $id,'sign' => time())), 2);
      include $this->template('error');
      exit();
     }
