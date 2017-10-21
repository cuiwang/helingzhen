<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
class hy_qiaodao_User{
    private $sessionid;
    public function __construct(){
        global $_W; 
        $this -> sessionid = "__cookie_hyqiandao_cur_auto_201611230000_{$_W['uniacid']}";
    }
   function getOpenid(){
		
        $dephp_0 = $this -> getInfo(false, true);
		
        return $dephp_0['openid'];
    }    
    function getInfo($dephp_17 = false, $dephp_18 = false){
        global $_W, $_GPC;
        $dephp_0 = array();        
            load() -> model('mc');
            if (empty($_GPC['directopenid'])){
                $dephp_0 = mc_oauth_userinfo();
            }else{
                $dephp_0 = array('openid' => $this -> getPerOpenid());
            }
            $dephp_19 = true;
            if ($_W['container'] != 'wechat'){
                if($_GPC['do'] == 'order' && $_GPC['p'] == 'pay'){
                    $dephp_19 = false;
                }
                if($_GPC['do'] == 'member' && $_GPC['p'] == 'recharge'){
                    $dephp_19 = false;
                }
                if($_GPC['do'] == 'plugin' && $_GPC['p'] == 'article' && $_GPC['preview'] == '1'){
                    $dephp_19 = false;
                }
            }
            if(empty($dephp_0['openid']) && $dephp_19){
                die('<!DOCTYPE html>
                <html>
                    <head>
                        <meta name=\'viewport\' content=\'width=device-width, initial-scale=1, user-scalable=0\'>
                        <title>抱歉，出错了</title><meta charset=\'utf-8\'><meta name=\'viewport\' content=\'width=device-width, initial-scale=1, user-scalable=0\'><link rel=\'stylesheet\' type=\'text/css\' href=\'https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css\'>
                    </head>
                    <body>
                    <div class=\'page_msg\'><div class=\'inner\'><span class=\'msg_icon_wrp\'><i class=\'icon80_smile\'></i></span><div class=\'msg_content\'><h4>请在微信客户端打开链接</h4></div></div></div>
                    </body>
                </html>');
            }
       
        if ($dephp_17){
            return urlencode(base64_encode(json_encode($dephp_0)));
        }
        return $dephp_0;
    }
        
     
}
