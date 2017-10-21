<?php
class Oauth2
{
    public static $SCOPE_BASE = "snsapi_base";
    public static $SCOPE_USERINFO = "snsapi_userinfo";
    private $appid = "";
    private $secret = "";
    private $party=null;
    private $current_account=null;
    
    function __construct($current_account)
    {

        $this->appid = $current_account['key'];
        $this->secret = $current_account['secret'];
        $this->current_account=$current_account;
        /*如果是授权方式的话需要加载微信公众平台第三方类*/
        if($current_account['type'] == 4){
	        load()->classs("thirdparty.account");
	        $this->party=new ThirdPartyAccount();
        }
        /*如果是授权方式的话需要加载微信公众平台第三方类*/
        if($current_account['type'] == 3){
	        load()->classs('weixin.platform');
	        $this->party=new WeiXinPlatform($current_account);
        }
    }

    public function authorization_code($redirect_uri, $scope, $state)
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appid . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=" . $scope . "&state=" . $state . "#wechat_redirect";
        if($this->current_account['type']==3){
        	$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appid . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=" . $scope . "&state=" . $state . "&component_appid={$this->party->appid}#wechat_redirect";
        }
        if($this->current_account['type']==4){
        	 $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appid . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=" . $scope . "&state=" . $state . "&component_appid={$this->party->appid}#wechat_redirect";
        }
        header("location: $url");
    }

    public function  getOauthAccessToken($code)
    {
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->appid . "&secret=" . $this->secret . "&code=" . $code . "&grant_type=authorization_code";
        
        if($this->current_account['type'] == 3){
        	$oauth2_code = "https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$this->appid}&code={$code}&grant_type=authorization_code&component_appid={$this->party->appid}&component_access_token={$this->party->getComponentAccesstoken()}";
        }   
        
        if($this->current_account['type']==4){
        	$url = "https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$this->appid}&code={$code}&grant_type=authorization_code&component_appid={$this->party->appid}&component_access_token={$this->party->get_component_token()}";
        }
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {

            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit();
        }
        
        return $token;
    }

    public function getOauthUserInfo($openid, $accessToken)
    {
        load()->func('communication');
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid . "&lang=zh_CN";
        $content = ihttp_get($tokenUrl);
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }

    public function  getUserInfo($access_token, $openid)
    {
        load()->func('communication');
        $api_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        $content = ihttp_get($api_url);
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }
    
    public static  function setClientCookieUserInfo($userInfo=array(),$cookieKey)
    {
    
         if (!empty($userInfo)&&!empty($userInfo['openid'])) {
    		$cookie = array();
    		$cookie['openid'] = $userInfo['openid'];
    		$cookie['uid'] = $userInfo['uid'];
    		$cookie['nickname'] = $userInfo['nickname'];
    		$cookie['headimgurl'] = $userInfo['headimgurl'];
    		//$cookie['headimgurl']=str_replace("/0","/132",$cookie['headimgurl']);
    		$session = base64_encode(json_encode($cookie));
    		isetcookie($cookieKey, $session, 48 * 3600 * 1); 
    		return $session; 
    	}
    }
}