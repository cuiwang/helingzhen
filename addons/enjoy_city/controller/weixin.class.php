<?php
class class_weixin
{
    public $appid = '';//appid
    public $appsecret =  '';//是否debug的状态标示，方便我们在调试的时候记录一些中间数据

 

	public function __construct($appid = NULL, $appsecret = NULL)
	{
	
		 if($appid){
		 $this->appid = $appid;
		  }
		 if($appsecret){
		 $this->appsecret = $appsecret;
		 }

		 //hardcode
		 $this->lasttime = 1395049256;
		// $this->access_token = "i-YuwjAglqvx1bBsVCF2puhyyeIrkNPJ0z2B5t6yx8iTLUz869UpzTmw3f54uKBE6ExV132eWjOagSrSOpRTe_DzpoiYO0TnPDYdFzs9khc";

		 if (time() > ($this->lasttime + 7200)){
		 $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
		 
		 $res = $this->http_request($url);
		 $result = json_decode($res, true);
		 //save to Database or Memcache
		 $this->access_token = $result["access_token"];
		 $this->lasttime = time();
		/*	var_dump($this->access_token);
		  var_dump($this->lasttime);*/
		  }
	}
	 //发送客服消息，已实现发送文本，其他类型可扩展

    protected function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($curl);

        curl_close($curl);
        return $output;
    }

	 public function send_template_message($data)
	  {
		$tpl_url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s';

		$url = sprintf($tpl_url,$this->access_token);
		 $rep = $this ->http_request($url,$data);
		 return json_encode($rep,true);
	  }
	   //HTTP请求（支持HTTP/HTTPS，支持GET/POST）

	   public function getaccess_token()
	  {

		 return $this->access_token;
	  }

	public function dd(){
	
	}
	
	public  function tplMsg($content){
		global $_W,$_GPC;
		if(empty($content)){
			message('缺失必需参数');
		}
		if(!is_array($content)){
			message('传入的参数必须为数组 ');
		}
		$tpl_url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s';
		
		$token =$_W['account']['access_token']['token'];
		if(empty($token)){
			$token = account_weixin_token($_W['account']);
		}
		$url = sprintf($tpl_url,$this->access_token);
		$tpl = urldecode(json_encode($content));
		$data = ihttp_post($url,$tpl);
		
		if($data['code']!=200){
			message('网络堵塞..请稍后再试..');
		}
		$dat = @json_decode($data['content'],true);
		if($dat['errcode']!=0){
			
			return array('errno'=>1,'msg'=>account_weixin_code($dat['errcode']));
		}else{
			return array('errno'=>0,'msg'=>'发送成功...');
		}
	}
	

}
?>