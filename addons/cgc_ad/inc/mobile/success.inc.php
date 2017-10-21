<?php
    global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']); 
    $quan=$this->get_quan(); 
   
    $member=$this->get_member();
    $from_user=$member['openid'];

    $mid=$member['id'];
    $op=$_GPC['op'];
    if($op=='pay'){
      $msg="发布成功";
      $err_title=$adv['aname'];
      $label='success';
      $redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('detail', array('quan_id' => $quan_id,'id'=>$id)), 2);
      include $this->template('error');
      exit();
     }
     
     if ($op=='shenhe') {
      $msg="提交成功,请耐心等待管理员审核通过！";
      $err_title=$adv['aname'];
      $label='success';
    /*  $cgc_ad_adv=new cgc_ad_adv();
      $adv=$cgc_ad_adv->getOne($id);
      $this->check_msg($this->settings,$quan,$adv);*/
      $redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('index', array('quan_id' => $quan_id)), 2);
      include $this->template('error');
      exit();
     }
     
     if ($op == 'vip_recharge'){
     	$msg="vip充值成功！";
     	$err_title=$adv['aname'];
     	$label='success';
     	$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('index', array('quan_id' => $quan_id)), 2);
     	include $this->template('error');
     	exit();
     }

	