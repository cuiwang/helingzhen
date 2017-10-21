<?php
//微赞科技

include_once('SDKRuntimeException.php');
include_once('WxPay.Micropay.config.php');
class Common_util_micropay
{
    public $wxpayconfig;
    function __construct($wxpayconfig)
    {
        $this->wxpayconfig = $wxpayconfig;
    }
    function trimString($value)
    {
        $ret = null;
        if (null != $value) {
            $ret = $value;
            if (strlen($ret) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }
    public function createNoncestr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
    public function getSign($Obj)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        ksort($Parameters);
        $String  = $this->formatBizQueryParaMap($Parameters, false);
        $String  = $String . "&key=" . $this->wxpayconfig->key;
        $result_ = strtoupper(md5($String));
        return $result_;
    }
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }
    public function xmlToArray($xml)
    {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    public function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }
    function postXmlSSLCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, $this->wxpayconfig->sslcertpath);
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, $this->wxpayconfig->sslkeypath);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }
    function printErr($wording = '', $err = '')
    {
        print_r('<pre>');
        echo $wording . "</br>";
        var_dump($err);
        print_r('</pre>');
    }
}
class Wxpay_client_micropay extends Common_util_micropay
{
    var $parameters;
    public $response;
    public $result;
    var $url;
    var $curl_timeout;
    function setParameter($parameter, $parameterValue)
    {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }
    function createXml()
    {
        $this->parameters["appid"]     = $this->wxpayconfig->appid;
        $this->parameters["mch_id"]    = $this->wxpayconfig->mchid;
        $this->parameters["nonce_str"] = $this->createNoncestr();
        $this->parameters["sign"]      = $this->getSign($this->parameters);
        return $this->arrayToXml($this->parameters);
    }
    function postXml()
    {
        $xml            = $this->createXml();
        $this->response = $this->postXmlCurl($xml, $this->url, $this->curl_timeout);
        return $this->response;
    }
    function postXmlSSL()
    {
        $xml            = $this->createXml();
        $this->response = $this->postXmlSSLCurl($xml, $this->url, $this->curl_timeout);
        return $this->response;
    }
    function getResult()
    {
        $this->postXml();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}
class OrderQuery_micropay extends Wxpay_client_micropay
{
    function __construct()
    {
        $this->url          = "https://api.mch.weixin.qq.com/pay/orderquery";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["out_trade_no"] == null && $this->parameters["transaction_id"] == null) {
                throw new SDKRuntimeException("订单查询接口中，out_trade_no、transaction_id至少填一个！" . "<br>");
            }
            $this->parameters["appid"]     = $this->wxpayconfig->appid;
            $this->parameters["mch_id"]    = $this->wxpayconfig->mchid;
            $this->parameters["nonce_str"] = $this->createNoncestr();
            $this->parameters["sign"]      = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
}
class Refund_micropay extends Wxpay_client_micropay
{
    function __construct()
    {
        $this->url          = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["out_trade_no"] == null && $this->parameters["transaction_id"] == null) {
                throw new SDKRuntimeException("退款申请接口中，out_trade_no、transaction_id至少填一个！" . "<br>");
            } elseif ($this->parameters["out_refund_no"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数out_refund_no！" . "<br>");
            } elseif ($this->parameters["total_fee"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数total_fee！" . "<br>");
            } elseif ($this->parameters["refund_fee"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数refund_fee！" . "<br>");
            } elseif ($this->parameters["op_user_id"] == null) {
                throw new SDKRuntimeException("退款申请接口中，缺少必填参数op_user_id！" . "<br>");
            }
            $this->parameters["appid"]     = $this->wxpayconfig->appid;
            $this->parameters["mch_id"]    = $this->wxpayconfig->mchid;
            $this->parameters["nonce_str"] = $this->createNoncestr();
            $this->parameters["sign"]      = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
    function getResult()
    {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}
class RefundQuery_micropay extends Wxpay_client_micropay
{
    function __construct()
    {
        $this->url          = "https://api.mch.weixin.qq.com/pay/refundquery";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["out_refund_no"] == null && $this->parameters["out_trade_no"] == null && $this->parameters["transaction_id"] == null && $this->parameters["refund_id "] == null) {
                throw new SDKRuntimeException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！" . "<br>");
            }
            $this->parameters["appid"]     = $this->wxpayconfig->appid;
            $this->parameters["mch_id"]    = $this->wxpayconfig->mchid;
            $this->parameters["nonce_str"] = $this->createNoncestr();
            $this->parameters["sign"]      = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
    function getResult()
    {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}
class Entpayment_micropay extends Wxpay_client_micropay
{
    function __construct($wxpayconfig)
    {
        parent::__construct($wxpayconfig);
        $this->url          = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["openid"] == null) {
                throw new SDKRuntimeException("付款openid不能为空！" . "<br>");
            }
            $this->parameters["mch_appid"]        = $this->wxpayconfig->appid;
            $this->parameters["mchid"]            = $this->wxpayconfig->mchid;
            $this->parameters["nonce_str"]        = $this->createNoncestr();
            $this->parameters["spbill_create_ip"] = $_SERVER['SERVER_ADDR'];
            $this->parameters["sign"]             = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
    function getResult()
    {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}
class Entpayment_redpack extends Wxpay_client_micropay
{
    function __construct($wxpayconfig)
    {
        parent::__construct($wxpayconfig);
        $this->url          = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["re_openid"] == null) {
                throw new SDKRuntimeException("红包接受者openid不能为空！" . "<br>");
            }
            $this->parameters["wxappid"]   = $this->wxpayconfig->appid;
            $this->parameters["mch_id"]    = $this->wxpayconfig->mchid;
            $this->parameters["nonce_str"] = $this->createNoncestr();
            $this->parameters["client_ip"] = $_SERVER['SERVER_ADDR'];
            $this->parameters["sign"]      = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
    function getResult()
    {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}
class DownloadBill_micropay extends Wxpay_client_micropay
{
    function __construct()
    {
        $this->url          = "https://api.mch.weixin.qq.com/pay/downloadbill";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["bill_date"] == null) {
                throw new SDKRuntimeException("对账单接口中，缺少必填参数bill_date！" . "<br>");
            }
            $this->parameters["appid"]     = $this->wxpayconfig->appid;
            $this->parameters["mch_id"]    = $this->wxpayconfig->mchid;
            $this->parameters["nonce_str"] = $this->createNoncestr();
            $this->parameters["sign"]      = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
    function getResult()
    {
        $this->postXml();
        $this->result = $this->xmlToArray($this->result_xml);
        return $this->result;
    }
}
class Reverse_micropay extends Wxpay_client_micropay
{
    function __construct()
    {
        $this->url          = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["out_trade_no"] == null && $this->parameters["transaction_id"] == null) {
                throw new SDKRuntimeException("冲正接口中，transaction_id、out_trade_no至少填一个！" . "<br>");
            }
            $this->parameters["appid"]     = $this->wxpayconfig->appid;
            $this->parameters["mch_id"]    = $this->wxpayconfig->mchid;
            $this->parameters["nonce_str"] = $this->createNoncestr();
            $this->parameters["sign"]      = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
    function getResult()
    {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}
class MicropayCall extends Wxpay_client_micropay
{
    function __construct()
    {
        $this->url          = "https://api.mch.weixin.qq.com/pay/micropay";
        $this->curl_timeout = WxPayConf_micropay::CURL_TIMEOUT;
    }
    function createXml()
    {
        try {
            if ($this->parameters["out_trade_no"] == null) {
                throw new SDKRuntimeException("缺少被扫支付接口必填参数out_trade_no！" . "<br>");
            } elseif ($this->parameters["body"] == null) {
                throw new SDKRuntimeException("缺少被扫支付接口必填参数body！" . "<br>");
            } elseif ($this->parameters["total_fee"] == null) {
                throw new SDKRuntimeException("缺少被扫支付接口必填参数total_fee！" . "<br>");
            } elseif ($this->parameters["auth_code"] == null) {
                throw new SDKRuntimeException("缺少被扫支付接口必填参数auth_code！" . "<br>");
            }
            $this->parameters["appid"]            = $this->wxpayconfig->appid;
            $this->parameters["mch_id"]           = $this->wxpayconfig->mchid;
            $this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];
            $this->parameters["nonce_str"]        = $this->createNoncestr();
            $this->parameters["sign"]             = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        }
        catch (SDKRuntimeException $e) {
            exit($e->errorMessage());
        }
    }
}
?>
