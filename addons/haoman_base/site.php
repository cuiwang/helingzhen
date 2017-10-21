<?php

defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', str_replace('site.php', '', str_replace('\\', '/', __FILE__)));

class Haoman_qjbModuleSite extends WeModuleSite {
    

    public function gethome() {
        global $_W;
        $articles = pdo_fetchall("SELECT id,rid, title FROM " . tablename('haoman_qjb_reply') . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($articles)) {
            foreach ($articles as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('index', array('id' => $row['rid'])));
            }
            return $urls;
        }
    }



   

    public function doMobileShare() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            message('本页面仅支持微信访问!非微信浏览器禁止浏览!', '', 'error');
        }

        //网页授权借用开始

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_qjb_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) { //如果是借用的，那么$from_user、$avatar、$nickname不能使用全局的信息，只能使用Cookie的信息，这样才不会出现没有一开始没有关系，后面又关注的人，他之前的数据都没有的情况
          $from_user = $cookie['openid'];
          $avatar = $cookie['avatar'];
          $nickname = $cookie['nickname'];
        }else{
          $from_user = $_W['fans']['from_user'];
          $avatar = $_W['fans']['tag']['avatar'];
          $nickname = $_W['fans']['nickname'];
        }

        $code = $_GPC['code'];
        $urltype = $_GPC['from_user'];
        if (empty($from_user) || empty($avatar)) {
          if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid'])) {
            $userinfo = $this->get_UserInfo($rid, $code, $urltype); //如果$from_user或是$avatar其中一个为空，并且cookie里面也没有信息，那么就调用高级权限去获取，这个如果本身是认证服务号的话，不会弹出授权界面，借用的会弹出授权界面
            $nickname = $userinfo['nickname'];
            $avatar = $userinfo['headimgurl'];
            $from_user = $userinfo['openid'];
          } else {
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
            $from_user = $cookie['openid'];
          }
        }

        //网页授权借用结束

        $reply = pdo_fetch("select sharenumtop,sharenum from " . tablename('haoman_qjb_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));


        load()->model('mc');

        if(empty($from_user)){
            $from_user=$this->get_openid($rid,$_GPC['from_user']);
        }

        if ($from_user != $fromuser) {
          //  message($from_user, '', 'error');//调试代码

            $sharedata = pdo_fetch("select id from " . tablename('haoman_qjb_data') . " where rid = '" . $rid . "' and from_user = '" . $from_user . "' and fromuser = '" . $fromuser . "' limit 1");
            //记录分享
            $insert = array(
                'rid' => $rid,
                'uniacid' => $_W['uniacid'],
                'from_user' => $from_user,
                'fromuser' => $fromuser,
                'visitorsip' => CLIENT_IP,
                'visitorstime' => TIMESTAMP,
                'viewnum' => 1

            );

            $fans = pdo_fetch("SELECT sharenum,last_time FROM " . tablename('haoman_qjb_fans') . " WHERE rid = " . $rid . " and from_user='" . $fromuser . "'");

            if (empty($sharedata) && ($reply['sharenumtop'] > $fans['sharenum'])){

                //更新当日次数
                $nowtime = mktime(0, 0, 0);

                $share_num = $reply['sharenum'];

                pdo_insert('haoman_qjb_data', $insert);


                if ($fans['last_time'] < $nowtime) {
                 //   message($fans['sharenum'], '', 'error');//调试代码
                    $fans['sharenum'] = 0;
                    pdo_update('haoman_qjb_fans', array('sharenum' => $fans['sharenum'] + $share_num, 'last_time' => $nowtime), array('from_user' => $fromuser,'rid'=>$rid));
                }else{

                    pdo_update('haoman_qjb_fans', array('sharenum' => $fans['sharenum'] + $share_num), array('from_user' => $fromuser,'rid'=>$rid));
                }


            }
            //记录分享
        }
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $this->createMobileUrl('index', array('rid' => $rid)) . "");
        exit();
    }


    







    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
   


    

      public function get_openid($rid, $code, $urltype) { //第二步或是OpenID和AccessToken，注意借用获取到的OpenID是认证服务号的OpenID
          global $_GPC, $_W;
          load()->func('communication');
          load()->model('account');
          $_W['account'] = account_fetch($_W['acid']);
          $appid = $_W['account']['key'];
          $appsecret = $_W['account']['secret'];

          if ($_W['account']['level'] != 4) {
                //不是认证服务号
                $set = $this->get_sysset();
                if (!empty($set['appid']) && !empty($set['appsecret'])) {
                    $appid = $set['appid'];
                    $appsecret = $set['appsecret'];
                }  else{
                    //如果没有借用，判断是否认证服务号
                    message('请使用认证服务号进行活动，或借用其他认证服务号权限!');
                 }
          }
          if (empty($appid) || empty($appsecret)) {
               message('请到管理后台设置完整的 AppID 和AppSecret !');
          }

          if (!isset($code)) {
              $this->get_code($rid, $appid,$urltype);
          }
          $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";
          $content = ihttp_get($oauth2_code);
          $token = @json_decode($content['content'], true);
          if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
              message('未获取到 openid , 请刷新重试!',refeer(),'error');
          }
          return $token;
      }





    

}
