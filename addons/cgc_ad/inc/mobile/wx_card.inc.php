<?php
  global $_W,$_GPC;
  $weid=$_W['uniacid'];
  $quan=$this->get_quan();
  $member=$this->get_member();
  $mid=$member['id'];
  $quan_id=$quan['id'];
  $adv=$this->get_adv();
  
  if ($adv['hx_status']!=2 || empty($adv['wx_cardid'])){
   $this->returnError("条件不满足","referer");
  }
 
  $this->get_cardauth($quan,$adv,$member);
  

  
  $config = $this ->settings;
  $op=$_GPC['op'];

  load()->func('communication');
  load()->classs('weixin.account');
  $WeiXinAccountService = WeiXinAccount :: create($_W['acid']);
  $card_id= $adv['wx_cardid'];
  
  $access_token = $WeiXinAccountService->getAccessToken();
 
  
  $ticket=getCardTicket($access_token);
   
  if (is_error($ticket)){
    $this->returnError($ticket['message']);
  }
  
  $now = time();
  $timestamp = $now;
  $nonceStr = $this->createNonceStr();
  $openid = $member['openid'];
  $code=$openid;

  $arr = array($card_id, $code,$ticket, $nonceStr, $openid, $timestamp);
  asort($arr, SORT_STRING);
  $sortString = "";
  foreach ($arr as $temp) {
    $sortString = $sortString . $temp;
  }
  $signature = sha1($sortString);
  $cardArry = array('code' => $code, 'openid' => $openid, 'timestamp' => $now, 'signature' => $signature, 'cardId' => $card_id, 'ticket' => $ticket, 'nonceStr' => $nonceStr);
  
  include $this->template("wx_card");





 
