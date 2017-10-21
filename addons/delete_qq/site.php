<?php
/**
 * QQ号码测凶吉模块微站定义
 *
 * @author delete
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Delete_qqModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W;
		$title=  $this->module['config']['title'];
	    $pic=  $this->module['config']['pic'];
	    $desc=  $this->module['config']['desc'];
		include $this->template('index');
	}

	public function doMobileQq(){
		global $_GPC;
		header("Content-Type:text/html;charset=UTF-8");

	    date_default_timezone_set("PRC");
	    $appid=  $this->module['config']['appid'];
	    $secret=  $this->module['config']['secret'];
	    
	    if($appid){
	    	$showapi_appid = $appid;
	    }else{
	    	$showapi_appid = '40463';  //替换此值,在官网的"我的应用"中找到相关值
	    }
	    if($secret){
	    	$showapi_secret = $secret;
	    }else{
		    $showapi_secret = '00c4d18e8e1848d3b0ec238fbfa53145';  //替换此值,在官网的"我的应用"中找到相关值
	    }
	    $page = rand(1,100);
	    $paramArr = array(
	         'showapi_appid'=> $showapi_appid,
         	 'qq'=> $_GPC['val']	         //添加其他参数
	    );
	     
	    //创建参数(包括签名的处理)
	    function createParam ($paramArr,$showapi_secret) {
	         $paraStr = "";
	         $signStr = "";
	         ksort($paramArr);
	         foreach ($paramArr as $key => $val) {
	             if ($key != '' && $val != '') {
	                 $signStr .= $key.$val;
	                 $paraStr .= $key.'='.urlencode($val).'&';
	             }
	         }
	         $signStr .= $showapi_secret;//排好序的参数加上secret,进行md5
	         $sign = strtolower(md5($signStr));
	         $paraStr .= 'showapi_sign='.$sign;//将md5后的值作为参数,便于服务器的效验
	         return $paraStr;
	    }
	     
	    $param = createParam($paramArr,$showapi_secret);
	    $url = 'http://route.showapi.com/863-1?'.$param; 
	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$notice = curl_exec($ch);
		curl_close($ch);
	    $result = json_decode($notice,true);
	    return json_encode($result);
	}

}