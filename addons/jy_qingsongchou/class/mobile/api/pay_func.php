<?php

    if($action=='getField'){
      require_once GARCIA_PATH."class/wechatDemo/lib/WxPay.Api.php";
      include GARCIA_PATH."class/wechatDemo/WxPay.JsApiPay.php";
      require_once GARCIA_PATH.'class/wechatDemo/log.php';
      $fee = $_GPC['fee'];
      $openid = $_GPC['openid'];
      $total_fee = $fee;
      $tid=date('YmdHhs',TIMESTAMP).TIMESTAMP.$this->weid.rand(000000,999999);
      $tid = "$tid";

      $msg = empty($_GPC['msg'])?"支持":$_GPC['msg'];
      $params=$this->pay($params);
      $params = base64_encode(json_encode($params));
      $data = array(
        'weid'=>$this->weid,
        'tid'=>$tid,
        'upbdate'=>time(),
        'fid'=>$_GPC['pid'],
        'fee'=>$total_fee,
        'msg'=>$msg,
        'mid'=>$_GPC['mid'],
        'address_id'=>$_GPC['address_id'],
        'reid'=>$_GPC['reid'],
        'dream_id'=>$_GPC['dream_id'],
        'count'=>$_GPC['count'],
      );
      $params = array(

        'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
        'ordersn' => $tid,  //收银台中显示的订单号
        'title' => $tid,          //收银台中显示的标题
        'fee' => $total_fee,      //收银台中显示需要支付的金额,只能大于
        'user' =>'',     //付款用户, 付款的用户名(选填项)
        'uniontid'=>$tid
      );
        $params=$this->pay($params);
        pdo_insert(GARCIA_PREFIX."paylog",$data);
      // ②、统一下单
       $input = new WxPayUnifiedOrder();
       $input->SetAppid('wxae268152ebc2c43e');

       $input->SetMch_id('1388227102');

       $input->SetBody($tid);
       $input->SetKey('yidajiazhon350624198902176057APP');
       $input->SetOut_trade_no($tid."0");
       $input->SetTotal_fee($total_fee*100);
      //  $input->SetGoods_tag('支付');
       $input->SetNotify_url( $_W['siteroot'] . 'payment/wechat/notify.php');
       $input->SetTrade_type("APP");
       $order = WxPayApi::unifiedOrder($input);
       if($order['return_code']=='SUCCESS'){
         $data = array(
          //  'apikey'=>'wxae268152ebc2c43e',
           'tradeNo'=>$tid,
          //  'mchId'=>'1388227102',
           'nonceStr'=>$order['nonce_str'],
           'timeStamp'=>time(),
           'sign'=>$order['sign'],
           'totalFee'=>$total_fee*100,
           'description'=>'宜达家',
           'notifyUrl'=>$_W['siteroot'] . 'payment/wechat/notify.php',
           'attach'=>$this->weid
         );
               _success(array('res'=>$data));
       }else{
           _fail(array('msg'=>$order['return_msg']));
       }
    }else if($action=='done'){
       pdo_insert('abc',array('weid'=>$this->weid,'tid'=>$_GPC['tid']));
    }

    else if($action=='getorder'){
      $msg = empty($_GPC['msg'])?"支持":$_GPC['msg'];
      $fee = $_GPC['fee'];
      // $openid = $_GPC['openid'];

      $total_fee = $fee;
      $tid = $_GPC['tid'];
      if(!empty($tid)){
         $tid= $_GPC['tid'];
      }else{
        $tid=date('YmdHhs',TIMESTAMP).TIMESTAMP.$this->weid.rand(000000,999999);
      }

      $tid = "$tid";

      $msg = empty($_GPC['msg'])?"支持":$_GPC['msg'];
      $params=$this->pay($params);
      $params = base64_encode(json_encode($params));
      $data = array(
        'weid'=>$this->weid,
        'tid'=>$tid,
        'upbdate'=>time(),
        'fid'=>$_GPC['fid'],
        'fee'=>$total_fee,
        'msg'=>$msg,
        'mid'=>$_GPC['mid'],
        'address_id'=>$_GPC['address_id'],
        'reid'=>$_GPC['reid'],
        'dream_id'=>$_GPC['dream_id'],
        'count'=>$_GPC['count'],
        'weixinid'=>$_GPC['weixinid'],
      );
      $params = array(

        'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
        'ordersn' => $tid,  //收银台中显示的订单号
        'title' => $tid,          //收银台中显示的标题
        'fee' => $total_fee,      //收银台中显示需要支付的金额,只能大于
        'user' =>'',     //付款用户, 付款的用户名(选填项)
        'uniontid'=>$tid
      );

        $params=$this->pay($params);
        $params['time'] = date('Y-m-d H:i:s',time());
        $params['fee'] = number_format($params['fee'],2);
        $params['img'] = $this->_SetQRcode($tid);
        if(empty($_GPC['tid'])){
              pdo_insert(GARCIA_PREFIX."paylog",$data);
        }

        _success(array('msg'=>$params));
    }else if($action=='getstatus'){
      require_once GARCIA_PATH."class/wechatDemo/lib/WxPay.Api.php";
      include GARCIA_PATH."class/wechatDemo/WxPay.JsApiPay.php";
      require_once GARCIA_PATH.'class/wechatDemo/log.php';

       $tid = $_GPC['tid'];

        $input = new WxPayOrderQuery();
        $input->SetOut_trade_no($tid);
        $res = WxPayApi::orderQuery($input,$this->sys['pay_appid'],$this->sys['pay_number'],$this->sys['pay_miyao']);
        // if($res[])
        if($res['trade_state']=='SUCCESS'){
             _success(array('res'=>1));
        }else{
          _success(array('res'=>0));
        }

      //  $sql = "SELECT status FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and tid='".$tid."'";
      //  $config = pdo_fetch($sql);
      //  if($config['status']==1){
      //    _success(array('res'=>1));
      //    exit;
      //  }else{
      //    _success(array('res'=>0));
      //  }

        exit;
    }
    else if($action=='alipay'){
        $payment = pdo_fetchcolumn('SELECT payment FROM '.tablename('uni_settings')." where uniacid=".$this->weid);
        $payment = iunserializer($payment);
        $payment =$payment['alipay'];
        $tid=date('YmdHhs',TIMESTAMP).TIMESTAMP.$this->weid;
        $fee = $_GPC['fee'];
        $fid = $_GPC['fid'];
        $title = pdo_fetchcolumn('SELECT name FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$fid);
        $mid = $_GPC['mid'];
        $params = array(
            'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'uniontid'=>$tid,
            'ordersn' => 1,  //收银台中显示的订单号
            'title' => $title,          //收银台中显示的标题
            'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0
            'user' =>'',     //付款用户, 付款的用户名(选填项)
            'type' =>'alipay'
        );
        $params=$this->pay($params);

         $params['return_url'] =  $_W['siteroot']."app/".substr($this->createMobileUrl('pay',array('display'=>'paydone','tid'=>$tid,'fid'=>$fid)),2);
         $params['notify_url'] =  $_W['siteroot']."addons/jy_qingsongchou/class/apilay/notify.php";
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
          $ret = alipay_build2($params, $alipay);

          $ret = base64_encode(json_encode($ret));

          _success(array('res'=>$ret));
    }
    else{
        _fail(array('msg'=>'not found function'));
    }

define('ALIPAY_GATEWAY', 'https://mapi.alipay.com/gateway.do');
function alipay_build2($params, $alipay = array()) {
	global $_W;

  $alipay_config['partner']		= $alipay['partner'];

  //收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
  $alipay_config['seller_id']	= $alipay_config['partner'];

  // MD5密钥，安全检验码，由数字和字母组成的32位字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
  $alipay_config['key']			= $alipay['secret'];

  // 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
  $alipay_config['notify_url'] = $params['notify_url'];

  // 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
  $alipay_config['return_url'] =$params['return_url'];

  //签名方式
  $alipay_config['sign_type']    = strtoupper('MD5');

  //字符编码格式 目前支持 gbk 或 utf-8
  $alipay_config['input_charset']= strtolower('utf-8');

  $alipay_config['transport']    = 'http';

  // 支付类型 ，无需修改
  $alipay_config['payment_type'] = "1";

  // 产品类型，无需修改
  $alipay_config['service'] = "create_direct_pay_by_user";

  $alipay_config['anti_phishing_key'] = "";

  // 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
  $alipay_config['exter_invoke_ip'] = "";

  //商户订单号，商户网站订单系统中唯一订单号，必填
  $alipay_config['WIDout_trade_no']= $params['tid'];

  //订单名称，必填
  $alipay_config['WIDsubject'] = $params['title'];

  //付款金额，必填
  $alipay_config['WIDtotal_fee'] = $params['fee'];

  //商品描述，可空
  $alipay_config['WIDbody'] = $_W['uniacid'];

	return $alipay_config;
}

 ?>
