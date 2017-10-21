<?php
/**
 * 红包发送模块
 */
include_once("CommonUtil.php");
class PZHSend
{
    private $password='';
    var $parameters;
    public function pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$mchid,$appid,$password)
		{

			include_once('WxHongBaoHelper.php');

			$commonUtil = new CommonUtil();
			$this->password = $password;
			
			$this->setParameter("nonce_str", $this->great_rand());//随机字符串，丌长于 32 位

	        $this->setParameter("mch_billno", $mchid.date('YmdHis').rand(1000, 9999));//订单号
	        $this->setParameter("mch_id", $mchid);//商户号
	        $this->setParameter("wxappid", $appid);
	        $this->setParameter("nick_name", $nick_name);//提供方名称
	        $this->setParameter("send_name", $send_name);//红包发送者名称
	        $this->setParameter("re_openid", $re_openid);//相对于医脉互通的openid
	        $this->setParameter("total_amount",$total_amount);//付款金额，单位分
	        $this->setParameter("min_value", $total_amount);//最小红包金额，单位分
	        $this->setParameter("max_value", $total_amount);//最大红包金额，单位分
	        $this->setParameter("total_num", 1);//红包収放总人数
	        $this->setParameter("wishing", $wishing);//红包祝福诧
	        $this->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
	        $this->setParameter("act_name", $act_name);//活劢名称
	        $this->setParameter("remark", $remark);//备注信息
             
	        $postXml = $this->create_hongbao_xml();
           
	        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	         // $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
	        $responseXml = $this->curl_post_ssl($url, $postXml);
	        
	        $result= simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	         // var_dump($result);
	        return $result;


	    }
function create_hongbao_xml($retcode = 0, $reterrmsg = "ok")
{

    $this->setParameter('sign', $this->get_sign());

    $commonUtil = new CommonUtil();
    $tmp=  $commonUtil->arrayToXml($this->parameters);

    return  $tmp;


}

function get_sign()
{
    define('PARTNERKEY',$this->password );

    if (null == PARTNERKEY || "" == PARTNERKEY )
    {
        message('PARTNERKEY为空','','error');
        return false;
    }
    if($this->check_sign_parameters() == false)
    {   //检查生成签名参数
        message('签名参数有误','','error');
        return false;
    }
    $commonUtil = new CommonUtil();
    ksort($this->parameters);
    $unSignParaString = $commonUtil->formatQueryParaMap($this->parameters, false);

    $md5SignUtil = new MD5SignUtil();

    return $this->sign($unSignParaString,$commonUtil->trimString(PARTNERKEY));


}
function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
{
    global $_W;
    $ch = curl_init();
    //超时时间
    curl_setopt($ch,CURLOPT_TIMEOUT,$second);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    //这里设置代理，如果有的话
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

    //cert 与 key 分别属于两个.pem文件
    curl_setopt($ch,CURLOPT_SSLCERT,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'apiclient_cert.pem.'.$_W['uniacid']);
    curl_setopt($ch,CURLOPT_SSLKEY,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'apiclient_key.pem.'.$_W['uniacid']);
    curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'rootca.pem.'.$_W['uniacid']);


    if( count($aHeader) >= 1 ){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
    }

    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
    $data = curl_exec($ch);
    //message(json_encode($data),'','error');
    if($data){
        curl_close($ch);
        return $data;
    }
    else {
        $error = curl_errno($ch);
        curl_close($ch);

        message('证书错误','','error');
    }
}
    public function great_rand()
    {
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
        $t1="";
        for($i=0;$i<30;$i++){
            $j=rand(0,35);
            $t1 = $t1. $str[$j];
        }
        return $t1;
    }
    function check_sign_parameters(){
        // if($this->parameters["nonce_str"] == null ||
        // 	$this->parameters["mch_billno"] == null ||
        // 	$this->parameters["mch_id"] == null ||
        // 	$this->parameters["wxappid"] == null ||
        // 	$this->parameters["nick_name"] == null ||
        // 	$this->parameters["send_name"] == null ||
        // 	$this->parameters["re_openid"] == null ||
        // 	$this->parameters["total_amount"] == null ||
        // 	$this->parameters["max_value"] == null ||
        // 	$this->parameters["total_num"] == null ||
        // 	$this->parameters["wishing"] == null ||
        // 	$this->parameters["client_ip"] == null ||
        // 	$this->parameters["act_name"] == null ||
        // 	$this->parameters["remark"] == null ||
        // 	$this->parameters["min_value"] == null
        // 	)
        // {
        // 	var_dump($this->parameters);
        // 	message( json_encode($this->parameters),'','error');
        // 	return false;
        // }
        return true;

    }
    function sign($content, $key) 
    {

        if (null == $key) {
            message('签名key不能为空','','error');
        }
        if (null == $content) {
            message('签名内容不能为空','','error');

        }
        $signStr = $content . "&key=" . $key;

        return strtoupper(md5($signStr));
    }
    function setParameter($parameter, $parameterValue) {
    $this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
}
function getParameter($parameter) {
    return $this->parameters[$parameter];
}

}



