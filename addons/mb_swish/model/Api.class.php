<?php
 class Api{
    private $account = null;
    private $core = null;
    public function __construct($account, $core){
        global $_W;
        $account['appid'] = $_W['account']['key'];
        $account['secret'] = $_W['account']['secret'];
        $account['name'] = $_W['account']['account'];
        $this -> account = $account;
        $this -> core = $core;
    }
    private function fetchToken(){
        load() -> classs('weixin.account');
        $accObj = WeixinAccount :: create($this -> account['acid']);
        $access_token = $accObj -> fetch_token();
        return $access_token;
    }
    public function getAccount(){
        return $this -> account;
    }
    public function auth($scope = 'openid'){
        global $_W, $_GPC;
        session_start();
        $from = getenv('REQUEST_URI');
        $urls = parse_url($from);
        parse_str($urls['query'], $querys);
        unset($querys['code']);
        unset($querys['state']);
        $from = $urls['path'] . '?' . http_build_query($querys);
        if($scope == 'openid'){
            $openid = $_SESSION['__:api:openid'];
            if(!empty($openid)){
                return $openid;
            }
            $code = $_GPC['code'];
            if(!empty($code)){
                $fan = $this -> oAuthGetUser($code);
                if(empty($fan) || is_error($fan)){
                    return error(-1, '微信接口错误, 无法确认您的身份');
                }
                $_SESSION['__:api:openid'] = $fan['openid'];
                header('Location: ' . $from);
                return $fan['openid'];
            }
            $callback = $_W['siteroot'] . substr($from, 1);
            $callback = urlencode($callback);
            $state = '';
            $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->account['appid']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
            header('Location: ' . $forward);
            exit;
        }
    }
    private function oAuthGetUser($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->account['appid']}&secret={$this->account['secret']}&code={$code}&grant_type=authorization_code";
        $content = Net :: httpGet($url);
        if(is_error($content)){
            return error(-1, '微信通信失败(GetUserInfo), 请稍后重试！错误详情: ' . $content['message']);
        }
        $token = @json_decode($content, true);
        if(empty($token) || !is_array($token) || empty($token['openid'])){
            return error(-2, '微信通信失败(GetUserInfo), 请稍后重试！错误详情: ' . $content);
        }
        $user = array();
        $user['openid'] = $token['openid'];
        if($token['scope'] == 'snsapi_userinfo'){
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$user['openid']}&lang=zh_CN";
            $content = Net :: httpGet($url);
            $info = @json_decode($content, true);
            if(!empty($info) && is_array($info) && !empty($info['openid'])){
                $user['openid'] = $info['openid'];
                $user['unionid'] = $info['unionid'];
                $user['nickname'] = $info['nickname'];
                $user['gender'] = '保密';
                if($info['sex'] == '1'){
                    $user['gender'] = '男';
                }
                if($info['sex'] == '2'){
                    $user['gender'] = '女';
                }
                $user['city'] = $info['city'];
                $user['state'] = $info['province'];
                $user['avatar'] = $info['headimgurl'];
                $user['country'] = $info['country'];
                if(!empty($user['avatar'])){
                    $user['avatar'] = rtrim($user['avatar'], '0');
                    $user['avatar'] .= '132';
                }
                $user['original'] = $info;
            }
        }
        return $user;
    }
    public function downloadMedia($mediaid, $fname = ''){
        $token = $this -> fetchToken();
        if(is_error($token)){
            return $token;
        }
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$token}&media_id={$mediaid}";
        $media = Net :: httpGet($url);
        if(is_error($media)){
            return $media;
        }
        if(substr($media, 0, 1) == '{'){
            $error = @json_decode($media, true);
            if($error['errcode'] == '40001' || $error['errcode'] == '42001'){
                $cacheKey = "accesstoken:{$this->account['acid']}";
                cache_delete($cacheKey);
                return error(-1, '公众平台授权冲突, 已经解决这个问题, 请重新再试一次');
            }
            return error(-1, $error['errmsg']);
        }
        $qiniu = new Qiniu($this -> core -> module['config']['storage']);
        return $qiniu -> putContent($fname, $media);
    }
    public function shareDataCreate($forceUrl = ''){
        $ticket = $this -> core -> module['config']['jsticket'];
        if(is_array($ticket) && !empty($ticket['ticket']) && !empty($ticket['expire']) && $ticket['expire'] > TIMESTAMP){
            $t = $ticket['ticket'];
        }else{
            $token = $this -> fetchToken();
            if(is_error($token)){
                return $token;
            }
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
            $resp = Net :: httpGet($url);
            if(is_error($resp)){
                return error(-1, "访问公众平台接口失败, 错误: {$resp['message']}");
            }
            $result = @json_decode($resp, true);
            if(empty($result)){
                return error(-2, "接口调用失败, 错误信息: {$resp}");
            }elseif (!empty($result['errcode'])){
                if($result['errcode'] == '40001' || $result['errcode'] == '42001'){
                    $cacheKey = "accesstoken:{$this->account['acid']}";
                    cache_delete($cacheKey);
                    return error(-1, '公众平台授权冲突, 已经解决这个问题, 请重新再试一次');
                }
                return error($result['errcode'], "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}");
            }
            $record = array();
            $record['ticket'] = $result['ticket'];
            $record['expire'] = TIMESTAMP + $result['expires_in'];
            $setting = $this -> core -> module['config'];
            $setting['jsticket'] = $record;
            $this -> core -> module['config']['jsticket'] = $record;
            $this -> core -> saveSettings($setting);
            $t = $record['ticket'];
        }
        $share = array();
        $share['appid'] = $this -> account['appid'];
        $share['timestamp'] = TIMESTAMP;
        $share['nonce'] = util_random(32);
        if(empty($forceUrl)){
            $forceUrl = __HOST__ . $_SERVER['REQUEST_URI'];
        }
        $string1 = "jsapi_ticket={$t}&noncestr={$share['nonce']}&timestamp={$share['timestamp']}&url={$forceUrl}";
        $share['signature'] = sha1($string1);
        return $share;
    }
}
?>