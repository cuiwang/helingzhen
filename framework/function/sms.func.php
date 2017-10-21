<?php

/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . "/framework/library/sms/SMSUtil.php";

function sms_send($mobile = '', $content = '', $signature = '',$check = true) {

        global $_W;
     
        $setting = setting_load('sms');
        
        if(empty($setting['sms'])){
               return error(1, "未设置短信接口，无法发送短信!");
        }
        if(!empty($setting['sms'])  && $setting['sms']['type']=='yimei'){
            if(empty($setting['sms']['url'])|| empty($setting['sms']['password'])|| empty($setting['sms']['serialNumber'])|| empty($setting['sms']['sessionKey'])){
                   return error(1, "亿美短信接口配置不完整，无法发送短信!");
            }
        }
        if(!empty($setting['sms'])  && $setting['sms']['type']=='dxton'){
            if(empty($setting['sms']['dxton_username'])|| empty($setting['sms']['dxton_pwd'])){
                   return error(1, "短信通接口配置不完整，无法发送短信!");
            }
        }
        
        $config = $setting['sms'];
        
        $smsconfig=array("balance"=>0,"signature"=>$_W['setting']['sitename']);
        $row = pdo_fetch("SELECT `notify` FROM " . tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
        $row['notify'] = @iunserializer($row['notify']);
        if (!empty($row['notify']) && !empty($row['notify']['sms'])) {
              $smsconfig = $row['notify']['sms'];
        }
       if($check){
           if (intval($smsconfig['balance'])<=0){
               return error(1, "短信条数不足，请联系客服人员");
           }
       }
        if(empty($signature)){
            $signature = "";
        }
   
       
        if( $config['type']=='yimei'){
            $content = "【".$signature."】".$content;
            $sms = new SMSUtil($config['url'], $config['serialNumber'], $config['password'], $config['sessionKey'], array(
                    "proxyhost" => $config['proxyhost'],
                    "proxyport" => $config['proxyport'],
                    "proxyusername" => $config['proxyusername'],
                    "proxypassword" => $config['proxypassword'],
             ), $config['timeout'], $config['response_timeout']);
             $err = $sms->send($mobile, $content);
             if (empty($err)) {
                     $row['notify']['sms']['balance'] = intval($row['notify']['sms']['balance']) -1;
                     pdo_update('uni_settings',array('notify'=>  iserializer($row['notify'])), array('uniacid' => $_W['uniacid']));
                     return true;
             } else {
                     return error(1, $err);
             }
        
        } else if($config['type']=='dxton'){
    
             $username = $config['dxton_username'];
             $pwd = $config['dxton_pwd'];
             $target = "http://www.dxton.com/webservice/sms.asmx/Submit";
             $post_data = "account=" . $username . "&password=" . $pwd . "&mobile=" . $mobile . "&content=" . rawurlencode($content);
             $result = ihttp_request($target, $post_data);
             $xml = simplexml_load_string($result['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
             $result = (string)$xml->result;
             $message = (string)$xml->message;
             if($result=='100'){
                  $row['notify']['sms']['balance'] = intval($row['notify']['sms']['balance']) -1;
                  pdo_update('uni_settings',array('notify'=>  iserializer($row['notify'])), array('uniacid' => $_W['uniacid']));
                  return true;
             }
             return error(1,$message);
        }
     
}
