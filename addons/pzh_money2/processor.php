<?php
/**
 * 红包大放送模块处理程序
 *
 * @author pzh
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Pzh_money2ModuleProcessor extends WeModuleProcessor {
	public function respond() 
	{
        $this->init();
        global $_W;

		$content = $this->message['content'];
    

		if($content ==  $this->module['config']['guanzhu']['key'])
		{
			//红包参数

       $re_openid = $this->message['from'];
       $nick_name = $this->module['config']['guanzhu']['nick_name'];
       $send_name =  $this->module['config']['guanzhu']['send_name'];
       $total_amount =  $this->module['config']['guanzhu']['total_amount'];
       $wishing = $this->module['config']['guanzhu']['wishing'];
       $remark =  $this->module['config']['guanzhu']['remark'];
       $act_name =  $this->module['config']['guanzhu']['act_name'];
       $maxCount =  $this->module['config']['guanzhu']['maxCount'];
       $maxRedCount = $this ->module['config']['guanzhu']['maxRedCount'];
       $addressLimit = $this ->module['config']['guanzhu']['addressLimit'];

      
       if($maxRedCount <= 0)
       {
       	 return $this->respText('红包已领完~');
       }
        
       $sql = 'SELECT redPackCount,lastTime FROM ' . tablename('pzh_packet2') . ' WHERE `uniacid` = :uniacid and `type` = :type and `openid` = :openid';
       $params = array(':uniacid' => $_W['uniacid'],':type' => 'guanzhu' , ':openid' => $re_openid);
       $account = pdo_fetch($sql, $params);

       if(!$account)
       {

       	//如果查询不到该用户
          $sql = 'INSERT INTO'.tablename('pzh_packet2') .' (`uniacid`,`openid`,`redPackCount`,`lastTime`,`type`) values ('.
    strval($_W['uniacid']).',\''.$re_openid.'\',0,'.strval($_W['timestamp']).',\'guanzhu\')'; 
      $result = pdo_query($sql);
       	 // return $this->respText($sql);
       }
       else
       {

       	//曾经拿过红包
       	if($_W['timestamp']-$account['lastTime']<=5*60)
       	{
       		return $this->respText('您刚领取过红包哦~');
       	}
       	else if($account['redPackCount']>=$maxCount)
       	{
       		//红包个数超过设定值
          return $this->respText('您的红包已领完~');
       	}

        
       }  
  
	 @require "pay.php";
    $packet = new Packet();
    $result = $this->pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$db=null);
 
  if($result->return_code == 'FAIL' || $result ==  'fail')
  {
    
  	    // return $this->respText($result->return_msg);
  	return $this->respText($result->return_msg);
  }
  else
  {
  	 $this ->module['config']['guanzhu']['maxRedCount']  =$maxRedCount - 1 ;
  	 $this->saveSettings($this->module['config']);
     $sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = ' .strval($account['redPackCount']+1) . 
' ,`lastTime`= ' . strval($_W['timestamp']). ' WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \'guanzhu\' and `openid` = \''.$re_openid.'\'  ';
		
      $result = pdo_query($sql);
      //关注红包记录数据
        $time = date('Y-m-d h:i:sa',time());
        $sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`) values ('.
          strval($_W['uniacid']).',\''.$re_openid.'\','.strval($total_amount/100.0).',\''.$time.'\',\'guanzhu\',\'success\')'; 
              pdo_query($sql);
     return $this->respText('恭喜您获得一个红包~');

  }
      return $this->respText('红包还没准备好哦');
	}
//*************************************************************************************************************************************************************


    	if($content ==  $this->module['config']['caidan']['key'])
		{
			//菜单送红包
       $re_openid = $this->message['from'];
       $nick_name = $this->module['config']['caidan']['nick_name'];
       $send_name =  $this->module['config']['caidan']['send_name'];
       $total_amount =  $this->module['config']['caidan']['total_amount'];
       $wishing = $this->module['config']['caidan']['wishing'];
       $remark =  $this->module['config']['caidan']['remark'];
       $act_name =  $this->module['config']['caidan']['act_name'];
       $maxCount =  $this->module['config']['caidan']['maxCount'];
       $maxRedCount = $this ->module['config']['caidan']['maxRedCount'];
       if($maxRedCount <= 0)
       {
       	 return $this->respText('红包已领完~');
       }

       $sql = 'SELECT redPackCount,lastTime FROM ' . tablename('pzh_packet2') . ' WHERE `uniacid` = :uniacid and `type` = :type and `openid` = :openid';
       $params = array(':uniacid' => $_W['uniacid'],':type' => 'caidan' , ':openid' => $re_openid);
       $account = pdo_fetch($sql, $params);
       if(!$account)
       {
       	//如果查询不到该用户
        	$sql = 'INSERT INTO'.tablename('pzh_packet2') .' (`uniacid`,`openid`,`redPackCount`,`lastTime`,`type`) values ('.
		strval($_W['uniacid']).',\''.$re_openid.'\',0,'.strval($_W['timestamp']).',\'caidan\')'; 
      $result = pdo_query($sql);
       	 // return $this->respText($sql);
       }
       else
       {
       	//曾经拿过红包
       	if($_W['timestamp']-$account['lastTime']<=5*60)
       	{
       		return $this->respText('您刚领取过红包哦~');
       	}
       	else if($account['redPackCount']>=$maxCount)
       	{
       		//红包个数超过设定值
          return $this->respText('您的红包已领完~');
       	}


       }  

	 @require "pay.php";
    $packet = new Packet();
    $result = $this->pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$db=null);
  // $result= $this->pay($_GPC['re_openid'],$_GPC['nick_name'],$_GPC['send_name'],$_GPC['total_amount'],$_GPC['wishing'],$_GPC['act_name'],$_GPC['remark']);
  if($result->result_code == 'FAIL' || $result ==  'fail')
  {
      // return $this->respText($result->return_msg);
  	    return $this->respText($result->err_code_des);
  }
  else
  {
  	 $this ->module['config']['caidan']['maxRedCount']  =$maxRedCount - 1 ;
  	 $this->saveSettings($this->module['config']);
     $sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = ' .strval($account['redPackCount']+1) . 
' ,`lastTime`= ' . strval($_W['timestamp']). ' WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \'caidan\' and `openid` = \''.$re_openid.'\'  ';
		
      $result = pdo_query($sql);
    //菜单红包记录数据
        $time = date('Y-m-d h:i:sa',time());
        $sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`) values ('.
          strval($_W['uniacid']).',\''.$re_openid.'\','.strval($total_amount/100.0).',\''.$time.'\',\'caidan\',\'success\')'; 
              pdo_query($sql);

     return $this->respText('恭喜您获得一个红包~');	
  }
 return $this->respText('红包还没准备好哦');
	}
    

 return $this->respText('哎呀，迷路了。');

}


    function guanzhusong()
    {
    	
    }

	function sign($content, $key) {
	    
		    if (null == $key) {
			  return 'fail';
		    }
			if (null == $content) {
				 return 'fail';
			  
		    }
		    $signStr = $content . "&key=" . $key;
		
		    return strtoupper(md5($signStr));
		
	}
	function get_sign()
	{
		define('PARTNERKEY',$this->module['config']['password'] );
		
			if (null == PARTNERKEY || "" == PARTNERKEY ) {
				 return 'fail';
				return false;
			}
			if($this->check_sign_parameters() == false) 
			{   //检查生成签名参数
			   return 'fail';
			   return false;
		    }
			$commonUtil = new CommonUtil();
			ksort($this->parameters);
			$unSignParaString = $commonUtil->formatQueryParaMap($this->parameters, false);

			$md5SignUtil = new MD5SignUtil();
             
			return $this->sign($unSignParaString,$commonUtil->trimString(PARTNERKEY));
		// }catch (SDKRuntimeException $e)
		// {
		// 	die($e->errorMessage());
		// }

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
      function pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$db=null)
    {
        include_once('WxHongBaoHelper.php');
        $commonUtil = new CommonUtil();
       

        $this->setParameter("nonce_str", $this->great_rand());//随机字符串，丌长于 32 位
        $this->setParameter("mch_billno", $this->module['config']['mchid'].date('YmdHis').rand(1000, 9999));//订单号
        $this->setParameter("mch_id", $this->module['config']['mchid']);//商户号
        $this->setParameter("wxappid", $this->module['config']['appid']);
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

		return;
    }
    function create_hongbao_xml($retcode = 0, $reterrmsg = "ok"){
		
		    $this->setParameter('sign', $this->get_sign());

		    $commonUtil = new CommonUtil();
		    $tmp=  $commonUtil->arrayToXml($this->parameters);
		      
		   return  $tmp;
		

	}
    function setParameter($parameter, $parameterValue) {
		$this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
	}
	function getParameter($parameter) {
		return $this->parameters[$parameter];
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
	 public function great_rand(){
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
        $t1="";
        for($i=0;$i<30;$i++){
            $j=rand(0,35);
            $t1 = $t1. $str[$j];
        }
        return $t1;    
    }
    function init()
    {
    	//查看关注数据库是否存在
    	global $_W;
    	$tableName = $_W['config']['db']['tablepre'].'pzh_packet2';
    	$exists= pdo_tableexists('pzh_packet2');
    	if(!$exists)
    	{
    		$sql = 'CREATE TABLE '.$tableName.' (
		 `uniacid` int(10)  NOT NULL,
		 `openid` varchar(35) NOT NULL,
		 `redPackCount` int(10) NOT NULL,
		 `lastTime` int(50) ,
		 `type`  varchar(50),
		 `remark`   varchar(50)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
		
		pdo_run($sql);
    	}

       $tableName = $_W['config']['db']['tablepre'].'pzh_record';
        $exists= pdo_tableexists('pzh_record');
        if(!$exists)
        {
          $sql = 'CREATE TABLE '.$tableName.' (
            `uniacid` int(10)  NOT NULL,
            `openid` varchar(35) NOT NULL,
            `moneyCount` float(10) NOT NULL,
            `time` varchar(50) ,
            `type`  varchar(50),
            `state`  varchar(50),
            `remark`   varchar(50)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

              pdo_run($sql);
            }
    }


}