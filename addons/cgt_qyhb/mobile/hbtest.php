<?php

 function  send($record) {
        global $_W;
       
    }
  


 //获得用户信息，一个文件 表示一个模块，利于多人开发。
  $fromUserJson=json_decode($this->getFromUser(),true);
  
  $fromUser=$fromUserJson["user_id"];
  
  
   $uniacid = $_W['uniacid'];
        $api = $this->module['config']['api'];
       

        $fee =1;

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars = array();
        $pars['mch_appid'] = "wx9490a278bc3cdb49";
        $pars['mchid'] = "1230957002";
        $pars['nonce_str'] = random(32);
        $pars['partner_trade_no'] = "1230957002". date('Ymd') . sprintf('%010d', "232");
  
     
        $pars['openid'] =$fromUser;
        $pars['check_name'] = "NO_CHECK";
        $pars['amount'] = 1;
        $pars['desc'] = "现金红包";
        $pars['spbill_create_ip'] ="120.24.163.234";

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key=000e9606f5aa2540c38835c594e824e2";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
     //   $uniacid="1";
        $extras['CURLOPT_CAINFO'] = MB_ROOT . '/cert/rootca.pem.' . $uniacid;
        $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/cert/apiclient_cert.pem.' . $uniacid;
        $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/cert/apiclient_key.pem.' . $uniacid;

        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }

        if(is_error($procResult)) {
          print_r($procResult);
        } else {
          echo "suceess";
        }
   

  
  
  
   // include $this->template('index');