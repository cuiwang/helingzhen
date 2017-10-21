<?php

class Oauth2
{
    public static $SCOPE_BASE = "snsapi_base";
    public static $SCOPE_USERINFO = "snsapi_userinfo";
    private $appid = "";
    private $secret = "";
    function __construct($appid, $secret)
    {
        $this->appid  = $appid;
        $this->secret = $secret;
    }
    public function authorization_code($redirect_uri, $scope, $state)
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appid . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=" . $scope . "&state=" . $state . "#wechat_redirect";
        header("location: $url");
    }
    public function getOauthAccessToke($code)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->appid . "&secret=" . $this->secret . "&code=" . $code . "&grant_type=authorization_code";
        $content     = ihttp_get($oauth2_code);
        $token       = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit();
        }
        return $token;
    }
    public function getOauthUserInfo($openid, $accessToken)
    {
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid . "&lang=zh_CN";
        $content  = ihttp_get($tokenUrl);
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }
    public function getAccessToken()
    {
        global $_W;
        $tokenUrl    = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->secret;
        $accessToken = CRUD::findUnique(CRUD::$table_sin_token, array(
            ":weid" => $_W['weid']
        ));
        load()->func('communication');
        if (!empty($accessToken)) {
            $expires_in = $accessToken['expires_in'];
            if (TIMESTAMP - $accessToken['createtime'] >= $expires_in - 200) {
                $content = ihttp_get($tokenUrl);
                $token   = @json_decode($content['content'], true);
                $data    = array(
                    'weid' => $_W['weid'],
                    'access_token' => $token['access_token'],
                    'expires_in' => $token['expires_in'],
                    'createtime' => TIMESTAMP
                );
                CRUD::updateById(CRUD::$table_sin_token, $data, $accessToken['id']);
                return $token['access_token'];
            } else {
                return $accessToken['access_token'];
            }
        } else {
            $content = ihttp_get($tokenUrl);
            $token   = @json_decode($content['content'], true);
            $data    = array(
                'weid' => $_W['weid'],
                'access_token' => $token['access_token'],
                'expires_in' => $token['expires_in'],
                'createtime' => TIMESTAMP
            );
            CRUD::create(CRUD::$table_sin_token, $data);
            return $token['access_token'];
        }
    }
    public function getUserInfo($access_token, $openid)
    {
        load()->func('communication');
        $api_url  = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        $content  = ihttp_get($api_url);
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }
}