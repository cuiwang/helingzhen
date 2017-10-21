<?php

ini_set('date.timezone','Asia/Shanghai');
require_once GARCIA_PATH."class/wechatDemo/lib/WxPay.Api.php";
include GARCIA_PATH."class/wechatDemo/WxPay.JsApiPay.php";
require_once GARCIA_PATH.'class/wechatDemo/log.php';



   if($_GPC['done']==1){
     $trade_no =  $_GPC['tid'];
     $input = new WxPayOrderQuery();
     $input->SetOut_trade_no($trade_no);
     $res = WxPayApi::orderQuery($input,$this->sys['pay_appid'],$this->sys['pay_number'],$this->sys['pay_miyao']);
     $data = array(
       'weid'=>$this->weid,
       'uniacid'=>$this->weid,
       'result'=>strtolower($res['return_code']),
       'from'=>'return',
       'tid'=>$trade_no,
       'uniontid'=>'',
       'user'=>$openid,
       'fee'=>$res['total_fee'],
       'tag'=>array(
       'transaction_id'=>$res['transaction_id'],
       ),
     );
     $this->payResult($data);
    // echo $res['transaction_id'];
     exit;
   }
   $mid = $this->_gmodaluserid();
   $openid = $this->memberinfo['openid'];
   $avatar = $this->memberinfo['headimgurl'];
   $platrrom = $this->_gplatfromuser($openid);
   $tel = $this->_GetMemberTel($mid);

      if($_GPC['dopost']=='hzcharge'){
        $tid=date('Ymd',TIMESTAMP).substr(time(),3)."_chargehuzhu";
        $fee = $_GPC['fee'];
        $id= $_GPC['id'];
        //①、获取用户openid
        $tools = new JsApiPay();
        $openid = $this->memberinfo['openid'];


         $total_fee = $fee*100;
         //②、统一下单
          $input = new WxPayUnifiedOrder();
          $input->SetAppid($this->sys['pay_appid']);
          $input->SetMch_id($this->sys['pay_number']);
          $input->SetBody(str_replace('_chargehuzhu','',$tid));
          $input->SetKey($this->sys['pay_miyao']);
          $input->SetOut_trade_no($tid);
          $input->SetTotal_fee($total_fee);
          $input->SetGoods_tag('支付');
          $input->SetNotify_url( $_W['siteroot'] . 'payment/wechat/notify.php');
          $input->SetTrade_type("JSAPI");
          $input->SetOpenid($_COOKIE['bro_openid']);

          $order = WxPayApi::unifiedOrder($input);
          $jsApiParameters = $tools->GetJsApiParameters($order,$this->sys['pay_miyao']);

            $data = array(
                'weid'=>$this->weid,
                'ids'=>$id,
                'upbdate'=>time(),
                'tid'=>$tid,
                'status'=>0,
                'hid'=>1,
                'mmm'=>$fee
            );

            pdo_insert(GARCIA_PREFIX."huzhu_pay",$data);
      }else if($_GPC['dopost']=='huzhu'){
          $tid=date('Ymd',TIMESTAMP).substr(time(),3)."_huzhu";
          $fee = $_GPC['fee'];
          $ids = $_GPC['ids'];
          $ids = explode(',',$ids);
          //①、获取用户openid
          $tools = new JsApiPay();
          $openid = $this->memberinfo['openid'];


           $total_fee = $fee*100;
           //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetAppid($this->sys['pay_appid']);
            $input->SetMch_id($this->sys['pay_number']);
            $input->SetBody(str_replace('_chargehuzhu','',$tid));
            $input->SetKey($this->sys['pay_miyao']);
            $input->SetOut_trade_no($tid);
            $input->SetTotal_fee($total_fee);
            $input->SetGoods_tag('支付');
            $input->SetNotify_url( $_W['siteroot'] . 'payment/wechat/notify.php');
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($_COOKIE['bro_openid']);

            $order = WxPayApi::unifiedOrder($input);
            $jsApiParameters = $tools->GetJsApiParameters($order,$this->sys['pay_miyao']);


            foreach ($ids as $key => $value) {
                $data = array(
                    'weid'=>$this->weid,
                    'ids'=>$value,
                    'upbdate'=>time(),
                    'tid'=>$tid,
                    'status'=>0,
                    'hid'=>0,
                    'mmm'=>$_fee
                );
                pdo_insert(GARCIA_PREFIX."huzhu_pay",$data);
            }

      }else{

        //①、获取用户openid
        $tools = new JsApiPay();
        $openid = $this->memberinfo['openid'];

        $tid=date('YmdHhs',TIMESTAMP).TIMESTAMP.$this->weid;
        $fee = $_GPC['fee'];
        $fid = $_GPC['fid'];

         $total_fee = $fee*100;
         //②、统一下单
          $input = new WxPayUnifiedOrder();
          $input->SetAppid($this->sys['pay_appid']);
          $input->SetMch_id($this->sys['pay_number']);
          $input->SetBody($tid);
          $input->SetKey($this->sys['pay_miyao']);
          $input->SetOut_trade_no($tid);
          $input->SetTotal_fee($total_fee);
          $input->SetGoods_tag('支付');
          $input->SetNotify_url( $_W['siteroot'] . 'payment/wechat/notify.php');
          $input->SetTrade_type("JSAPI");
          $input->SetOpenid($_COOKIE['bro_openid']);

          $order = WxPayApi::unifiedOrder($input);
          $jsApiParameters = $tools->GetJsApiParameters($order,$this->sys['pay_miyao']);


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
            'reid'=>$_GPC['reid'],
            'dream_id'=>$_GPC['dream_id'],
          );

          pdo_insert(GARCIA_PREFIX."paylog",$data);
      }
      include $this->template('pay/pay2');

 ?>
