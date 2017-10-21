<?php
// +----------------------------------------------------------------------
// | JuhePHP [ NO ZUO NO DIE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2015 http://juhe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Juhedata <info@juhe.cn>
// +----------------------------------------------------------------------
 
//----------------------------------
// 聚合数据-手机话费充值API调用类
//----------------------------------
class recharge {
 
    private $appkey;
    private $openid;
    private $telCheckUrl = 'http://op.juhe.cn/ofpay/mobile/telcheck'; 
    private $telQueryUrl = 'http://op.juhe.cn/ofpay/mobile/telquery'; 
    private $submitUrl = 'http://op.juhe.cn/ofpay/mobile/onlineorder'; 
    private $staUrl = 'http://op.juhe.cn/ofpay/mobile/ordersta';
	private $flowUrl = 'http://v.juhe.cn/flow/recharge';
	private $listUrl = 'http://v.juhe.cn/flow/list';
	private $flowtelcheckUrl = 'http://v.juhe.cn/flow/telcheck';
	private $batchqueryUrl = 'http://v.juhe.cn/flow/batchquery';
	private $orderlistUrl = 'http://v.juhe.cn/flow/orderlist';
	private $operatorstateUrl = 'http://v.juhe.cn/flow/operatorstate';
	private $sendUrl = 'http://v.juhe.cn/sms/send';
 
    public function __construct($appkey,$openid){
        $this->appkey = $appkey;
        $this->openid = $openid;
    }
 
    /**
     * 手机话费充值 ：根据手机号码及面额查询是否支持充值
     * @param  string $mobile   [手机号码]
     * @param  int $pervalue [充值金额]
     * @return  boolean
     */
    public function telcheck($mobile,$pervalue){
        $params = 'key='.$this->appkey.'&phoneno='.$mobile.'&cardnum='.$pervalue;
        $content = $this->juhecurl($this->telCheckUrl,$params);
        $result = $this->_returnArray($content);
        if($result['error_code'] == '0'){
            return true;
        }else{
            return false;
        }
    }
 
    /**
     * 手机话费充值 ：根据手机号码和面额获取商品信息
     * @param  string $mobile   [手机号码]
     * @param  int $pervalue [充值金额]
     * @return  array
     */
    public function telquery($mobile,$pervalue){
        $params = 'key='.$this->appkey.'&phoneno='.$mobile.'&cardnum='.$pervalue;
        $content = $this->juhecurl($this->telQueryUrl,$params);
        return $this->_returnArray($content);
    }
 
    /**
     * 手机话费充值 ：提交话费充值
     * @param  [string] $mobile   [手机号码]
     * @param  [int] $pervalue [充值面额]
     * @param  [string] $orderid  [自定义单号]
     * @return  [array]
     */
    public function telcz($mobile,$pervalue,$orderid){
        $sign = md5($this->openid.$this->appkey.$mobile.$pervalue.$orderid);//校验值计算
        $params = array(
            'key' => $this->appkey,
            'phoneno'   => $mobile,
            'cardnum'   => $pervalue,
            'orderid'   => $orderid,
            'sign' => $sign
        );
        $content = $this->juhecurl($this->submitUrl,$params,1);
        return $this->_returnArray($content);
    }
 
    /**
     * 手机话费充值 ：查询订单的充值状态
     * @param  [string] $orderid [自定义单号]
     * @return  [array]
     */
    public function sta($orderid){
        $params = 'key='.$this->appkey.'&orderid='.$orderid;
        $content = $this->juhecurl($this->staUrl,$params);
        return $this->_returnArray($content);
    }
	
	/**
     * 流量直充：全部流量套餐列表
     * @param  [string] $key [接口key]
     * @return  [array]
     */
    public function flowlist(){
        $params = 'key='.$this->appkey;
        $content = $this->juhecurl($this->listUrl,$params);
        return $this->_returnArray($content);
    }
	
	/**
     * 流量直充：检测号码支持的流量套餐
     * @param  [string] $phone [电话号码]
     * @return  [array]
     */
    public function flowtelcheck($phone){
        $params = 'phone='.$phone.'&key='.$this->appkey;
        $content = $this->juhecurl($this->flowtelcheckUrl,$params);
        return $this->_returnArray($content);
    }
	
	/**
     * 流量直充：提交流量充值
     * @param  [string] $phone [电话号码]
	 * @param  [string] $pid [套餐ID]
	 * @param  [string] $orderid [自定义订单号]
     * @return  [array]
     */
    public function flowcz($phone,$pid,$orderid){
        $sign = md5($this->openid.$this->appkey.$phone.$pid.$orderid);//校验值计算
        $params = array(
            'phone'   => $phone,
            'pid'   => $pid,
            'orderid'   => $orderid,
			'key' => $this->appkey,
            'sign' => $sign
        );
        $content = $this->juhecurl($this->flowUrl,$params,1);
        return $this->_returnArray($content);
    }
	
	/**
     * 流量直充：订单状态查询
     * @param  [string] $orderid [用户订单号，多个以英文逗号隔开，最大支持50组]
     * @return  [array]
     */
    public function batchquery($orderid){
        $params = array(
            'orderid' => $orderid,
            'key' => $this->appkey
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($this->batchqueryUrl,$paramstring);
        return $this->_returnArray($content);
    }
	
	/**
     * 流量直充：充值订单列表
     * @param  [string] $pagesize [每页返回条数，最大200，默认50]
	 * @param  [string] $page [页数，默认1]
	 * @param  [string] $phone [指定要查询的手机号码]
     * @return  [array]
     */
    public function orderlist($pagesize = 50,$page = 1,$phone = ''){
        $params = array(
            'pagesize' => $pagesize,
            'page' => $page,
            'phone' => $phone,
            'key' => $this->appkey
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($this->orderlistUrl,$paramstring);
        return $this->_returnArray($content);
    }
	
	/**
     * 流量直充：运营商状态查询
     * @param  [string] $pagesize [每页返回条数，最大200，默认50]
	 * @param  [string] $page [页数，默认1]
	 * @param  [string] $phone [指定要查询的手机号码]
     * @return  [array]
     */
    public function operatorstate($pagesize = 50,$page = 1,$phone = ''){
        $params = 'key='.$this->appkey;
        $content = $this->juhecurl($this->operatorstateUrl,$params);
        return $this->_returnArray($content);
    }
	
	/**
     * 发送短信
     * @param  [string] $mobile [手机号码]
	 * @param  [string] $tpl_id [短信模板ID]
	 * @param  [string] $tpl_value [发送内容]
     * @return  [array]
     */
    public function send($mobile,$tpl_id,$tpl_value){
        $smsConf = array(
            'key'   => $this->appkey,
            'mobile'    => $mobile,
            'tpl_id'    => $tpl_id,
            'tpl_value' => $tpl_value
        );
		$content = $this->juhecurl($this->sendUrl,$smsConf,1);
        return $this->_returnArray($content);
    }
 
    /**
     * 将JSON内容转为数据，并返回
     * @param string $content [内容]
     * @return array
     */
    public function _returnArray($content){
        return json_decode($content,true);
    }
 
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
 
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo 'cURL Error: ' . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}