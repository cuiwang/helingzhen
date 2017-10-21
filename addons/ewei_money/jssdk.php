<?php

/**
 * 数钱数到手抽筋
 *
 * @author ewei 012wz.com
 * @url 
 */
class JSSDK {

    private $appId;
    private $appSecret;
 
    public function __construct($appId,$appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getSignPackage() {
 
        global $_W;
        $jsapiTicket = $this->getJsApiTicket();
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
 
        
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        global $_W,$_GPC;
        load()->func('cache');
        $api = cache_load("ewei.money.api_share.json::".$_W['uniacid'], true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$this->appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$this->appSecret){
            $new = true;
        }
        
        $data = cache_load("ewei.money.jsapi_ticket.json::".$_W['uniacid'], true);

        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $accessToken = $this->getAccessToken();
          
            $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data['expire_time'] = time() + 7000;
                $data['jsapi_ticket'] = $ticket;
                cache_write("ewei.money.jsapi_ticket.json::".$_W['uniacid'], iserializer($data));
                cache_write("ewei.money.api_share.json::".$_W['uniacid'], iserializer(array("appid"=>$this->appId,"appsecret"=>$this->appSecret)));
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }

        return $ticket;
    }

    private function getAccessToken() {
        global $_W,$_GPC;
        load()->func('cache');
         $api = cache_load("ewei.money.api_share.json::".$_W['uniacid'], true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$this->appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$this->appSecret){
            $new = true;
        }
        
        $data = cache_load("ewei.money.access_token.json::".$_W['uniacid'], true);
     
        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data['expire_time'] = time() + 7000;
                $data['access_token'] = $access_token;
                cache_write("ewei.money.access_token.json::".$_W['uniacid'], iserializer($data));
                cache_write("ewei.money.api_share.json::".$_W['uniacid'], iserializer(array("appid"=>$this->appId,"appsecret"=>$this->appSecret)));
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

}
