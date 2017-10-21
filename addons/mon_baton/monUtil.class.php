<?php

/**
 * Class MonUtil
 * 工具类
 */
   class MonUtil{

      public static $DEBUG=false;

      public static $IMG_INDEX_BANNER=1;

      public static  $IMG_MY_BANNER=2;

      public static $IMG_RY_BANNER=3;

      public static $IMG_SUCCESS_BANNER=4;

      public static $IMG_DEFAULT_LOGO=5;
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
              foreach ($userInfo as $key=>$value)
              $cookie[$key] = $value;
              $session = base64_encode(json_encode($cookie));
              isetcookie($cookieKey, $session, 3 * 3600 * 24);
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


       public static function defaultImg($img_type) {
           switch($img_type) {
               case MonUtil::$IMG_INDEX_BANNER:
                   $img_name="34538d2fb961faee.png";
                   break;
               case MonUtil::$IMG_MY_BANNER:
                   $img_name="4a79ef1a710a659e.gif";
                   break;
               case MonUtil::$IMG_RY_BANNER:
                   $img_name="5e30759816c76d10.gif";
                   break;
               case MonUtil::$IMG_SUCCESS_BANNER:
                   $img_name="d4c85c7ddd0d648f.gif";
                   break;
               case MonUtil::$IMG_DEFAULT_LOGO:
                   $img_name="timg.gif";
                   break;
           }

           return "../addons/mon_baton/images/".$img_name;

       }


  }