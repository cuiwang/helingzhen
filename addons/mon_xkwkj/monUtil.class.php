<?php

/**
 * Class MonUtil
 * 工具类
 */
   class MonUtil{

      public static $DEBUG = false;

       public static $IMG_SHARE_BG = 1;
       public static $IMG_INDEX_KJ_TOP = 2;
      /**
       * author: 
       * @param $url
       * @return string
       */
      public static function str_murl($url)
      {
          global $_W;

          return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

      }


      /**
       * author: weizan 
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
       * author:weizan QQ 631872807
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
       * author: weizan 
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

       public static function defaultImg($img_type,$xkwkj='')
       {
           switch ($img_type) {
               //首页

               case MonUtil::$IMG_SHARE_BG:
                   if (!empty($xkwkj)&&!empty($xkwkj['share_bg'])) {
                       return MonUtil::getpicurl($xkwkj['share_bg']);
                   }
                   $img_name = "bargain_help.png";
                   break;
               case MonUtil::$IMG_INDEX_KJ_TOP:
                   if (!empty($xkwkj) && !empty($xkwkj['banner_bg'])) {
                       return MonUtil::getpicurl($xkwkj['banner_bg']);
                   }
                   $img_name = "kj_top.jpg";
                   break;
           }
           return "../addons/mon_xkwkj/images/" . $img_name;

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