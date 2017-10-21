<?php


$mid = $this->_gmodaluserid();
$openid = $this->memberinfo['openid'];
$avatar = $this->memberinfo['headimgurl'];
$platrrom = $this->_gplatfromuser($openid);
$tel = $this->_GetMemberTel($mid);
$uid = $platrrom['uid'];
$payment = pdo_fetchcolumn('SELECT payment FROM '.tablename('uni_settings')." where uniacid=".$this->weid);
  $payment = iunserializer($payment);
  $payment =$payment['alipay'];
  if(!$payment['switch']){
      $this->_TplHtml('没有开启支付宝支付',referer(),'error');
  }
  $tid=date('YmdHhs',TIMESTAMP).TIMESTAMP.$this->weid;
  $fee = $_GPC['fee'];
  $fid = $_GPC['fid'];
  $params = array(
      'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
      'uniontid'=>$tid,
      'ordersn' => 1,  //收银台中显示的订单号
      'title' => $tid,          //收银台中显示的标题
      'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0
      'user' =>'',     //付款用户, 付款的用户名(选填项)
      'type' =>'alipay'
  );
  $params=$this->pay($params);

   $alipay = array(
       'switch'  => 1,
       'account' => $payment['account'],
       'partner' => $payment['partner'],
       'secret'  => $payment['secret'],
   );
  $msg = empty($_GPC['msg'])?"支持":$_GPC['msg'];
   $data = array(
     'weid'=>$this->weid,
     'tid'=>$tid,
     'uid'=>$uid,
     'avatar'=>$avatar,
     'upbdate'=>time(),
     'fid'=>$fid,
     'fee'=>$fee,
     'msg'=>$msg,
     'mid'=>$mid,
     'address_id'=>$_GPC['address_id'],
     'reid'=>$_GPC['reid'],
     'dream_id'=>$_GPC['dream_id'],
     'count'=>$_GPC['count'],
     'wantSupportTel'=>$_GPC['wantSupportTel'],
     'type'=>6
   );
  pdo_insert(GARCIA_PREFIX."paylog",$data);
  load()->model('payment');
  load()->func('communication');
  $ret = alipay_build($params, $alipay);

   if ($ret['url']) {
       echo '<script type="text/javascript" src="../payment/alipay/ap.js"></script><script type="text/javascript">_AP.pay("' . $ret['url'] . '")</script>';
       exit();

   }


 ?>
