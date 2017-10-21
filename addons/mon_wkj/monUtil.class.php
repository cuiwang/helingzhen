<?php

/**
 * Class MonUtil
 * 工具类
 */
   class MonUtil{

      public static $DEBUG=false;
      /**
       * author: codeMonkey QQ:631872807
       * @param $url
       * @return string
       */
      public static function str_murl($url)
      {
          global $_W;

          return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

      }


      /**
       * author: codeMonkey QQ:631872807
       * 检查手机
       */
      public static function  checkmobile()
      {

          if (!MonUtil::$DEBUG) {
              $user_agent = $_SERVER['HTTP_USER_AGENT'];
              if (strpos($user_agent, 'MicroMessenger') === false) {
                  echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
                  exit();
              }
          }


      }





      /**
       * author:codeMonkey QQ 631872807
       * 获取哟规划信息
       * @return array|mixed|stdClass
       */
      public static function  getClientCookieUserInfo($cookieKey)
      {
          global $_GPC;
          $session = json_decode(base64_decode($_GPC[$cookieKey]), true);
          return $session;

      }


      /**
       * author: codeMonkey QQ:631872807
       * @param $openid
       * @param $accessToken
       * @return unknown
       * cookie保存用户信息
       */
      public static  function setClientCookieUserInfo($userInfo=array(),$cookieKey)
      {

          if (!empty($userInfo)&&!empty($userInfo['openid'])) {
              $cookie = array();
              $cookie['openid'] = $userInfo['openid'];
              $cookie['nickname'] = $userInfo['nickname'];
              $cookie['headimgurl'] = $userInfo['headimgurl'];
              $session = base64_encode(json_encode($cookie));

              isetcookie($cookieKey, $session, 24 * 3600 * 1);

          }else{

              message("获取用户信息错误");
          }


      }


       public static  function getpicurl($url) {
           global $_W;
           return $_W ['attachurl'] . $url;

       }


       public static function  emtpyMsg($obj,$msg){
           if(empty($obj)){
               message($msg);
           }
       }



  }