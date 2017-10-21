<?php


class JSSDK {

    private $appId;
    private $appSecret;

     public function get_sysset(){
          $path =  "/addons/amouse_job";
          $filename = IA_ROOT . $path."/data/sysset_".$this->weid. ".txt";
          if(is_file($filename)){
              $content = file_get_contents($filename);
              if(empty($content)){
                  return false;
              }
             return   json_decode(base64_decode($content),true);
          }
         return pdo_fetch("SELECT * FROM " . tablename('amouse_sysset') . " WHERE weid = :weid limit 1", array(':weid' => $this->weid));
    }
    private $weid;
    public function __construct() {
        
        global $_W;
        $this->weid = $_W['uniacid'];
        load()->model('account');
        $_W['account'] = account_fetch($_W['uniacid']);
        $this->appId =$_W['account']['key'];
        $this->appSecret =$_W['account']['secret'];
        //借用了分享 
        $set = $this->get_sysset();
         if(!empty($set['appid_share']) && !empty($set['appsecret_share'])) {
			$this->appId = $set['appid_share'];
			$this->appSecret = $set['appsecret_share'];
        }
      
        $_W['account']['appid_share'] = $this->appId;
        $_W['account']['appsecret_share'] = $this->appSecret;
       
    }

    public function getSignPackage() {
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
        if (IMS_VERSION >= 0.6) {
            load()->func('cache');
        }
        $api = cache_load("amouse.job.api_share.json::".$this->weid, true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$this->appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$this->appSecret){
            $new = true;
        }
        
        $data = cache_load("amouse.job.jsapi_ticket.json::".$this->weid, true);

        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $accessToken = $this->getAccessToken();
            $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data['expire_time'] = time() + 7000;
                $data['jsapi_ticket'] = $ticket;
                cache_write("amouse.job.jsapi_ticket.json::".$this->weid, iserializer($data));
                cache_write("amouse.job.api_share.json::".$this->weid, iserializer(array("appid"=>$this->appId,"appsecret"=>$this->appSecret)));
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }

        return $ticket;
    }

    private function getAccessToken() {
        if (IMS_VERSION >= 0.6) {
            load()->func('cache');
        }
         $api = cache_load("amouse.job.api_share.json::".$this->weid, true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$this->appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$this->appSecret){
            $new = true;
        }
        
        $data = cache_load("amouse.job.access_token.json::".$this->weid, true);
     
        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data['expire_time'] = time() + 7000;
                $data['access_token'] = $access_token;
                cache_write("amouse.job.access_token.json::".$this->weid, iserializer($data));
                cache_write("amouse.job.api_share.json::".$this->weid, iserializer(array("appid"=>$this->appId,"appsecret"=>$this->appSecret)));
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
