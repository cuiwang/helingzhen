	<?php
	/**
	 * 红包大放送模块微站定义
	 *
	 * @author 
	 * @url http://bbs.we7.cc/
	 */
	defined('IN_IA') or exit('Access Denied');
	include_once("CommonUtil.php");
	include_once("PZHSendMoney.php");
	define('MB_ROOT', IA_ROOT . '/addons/pzh_money2');
	class Pzh_money2ModuleSite extends WeModuleSite 
	{
		var $parameters;

	    //保存参数
		public function doWebSavenum()
		{
			global $_GPC,$_W;
	            // message('保存成功','','error');

			load()->func('file');
			mkdirs(MB_ROOT . '/zhengshu');
			if(!empty($_GPC['secret']))
			{
				$this->module['config']['secret'] = $_GPC['secret'];
			}
			if(!empty($_GPC['appid']))
			{
				$this->module['config']['appid'] = $_GPC['appid'];
			}
			if(!empty($_GPC['mchid']))
			{
				$this->module['config']['mchid'] = $_GPC['mchid'];
			}
			if(!empty($_GPC['password']))
			{
				$this->module['config']['password'] = $_GPC['password'];
			}
			if(!empty($_GPC['ip']))
			{
				$this->module['config']['ip'] = $_GPC['ip'];
			}
			if(!empty($_GPC['gongzhonghao']))
			{
				$this->module['config']['gongzhonghao'] = $_GPC['gongzhonghao'];
			}
			$result=true;
			if(!empty($_GPC['ca'])) {

				$ret =  file_put_contents(MB_ROOT . '/zhengshu/rootca.pem.'. $_W['uniacid'], trim($_GPC['ca']));

				$result = $ret && $result;
			}
			if(!empty($_GPC['cert'])) {

				$ret =  file_put_contents(MB_ROOT . '/zhengshu/apiclient_cert.pem.'. $_W['uniacid'], trim($_GPC['cert']));
				$result = $ret && $result;

			} 
			if(!empty($_GPC['key'])) {

				$ret =  file_put_contents(MB_ROOT . '/zhengshu/apiclient_key.pem.'. $_W['uniacid'], trim($_GPC['key']));
				$result = $ret && $result;
			} 
			$this->saveSettings($this->module['config']);
			if(!$result)
			{
				message('证书保存失败，请确认'.MB_ROOT .'/zhengshu 文件夹有写入权限','','error');

			}
			message('数据保存成功！','','success');



		}
	    //发送单个红包信息
		public function doWebSendsinglered()
		{
			global $_W,$_GPC;
			$this->init();
			if(!empty($_GPC['re_openid'])) 
			{
				$this->module['config']['single']['re_openid'] = $_GPC['re_openid'];
			}
			if(!empty($_GPC['total_amount'])) 
			{
				$this->module['config']['single']['total_amount'] = $_GPC['total_amount'];
			}
			if(!empty($_GPC['nick_name'])) 
			{
				$this->module['config']['single']['nick_name'] = $_GPC['nick_name'];
			}
			if(!empty($_GPC['send_name'])) 
			{
				$this->module['config']['single']['send_name'] = $_GPC['send_name'];
			}
			if(!empty($_GPC['wishing'])) 
			{
				$this->module['config']['single']['wishing'] = $_GPC['wishing'];
			}
			if(!empty($_GPC['act_name'])) 
			{
				$this->module['config']['single']['act_name'] = $_GPC['act_name'];
			}
			if(!empty($_GPC['remark'])) 
			{
				$this->module['config']['single']['remark'] = $_GPC['remark'];
			}
			$this->saveSettings($this->module['config']);
			if($_GPC['submit'] == '保存')
			{
				message('保存成功！','','success');
				return ;
			}

			

			@require "pay.php";
			$packet = new Packet();
	     // message('hello','','success');
	    // pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$db=null)
			$result= $this->pay($_GPC['re_openid'],$_GPC['nick_name'],$_GPC['send_name'],$_GPC['total_amount'],$_GPC['wishing'],$_GPC['act_name'],$_GPC['remark']);
			if($result->return_code == 'FAIL')
			{
				message($result->return_msg,'','error');
			}
			else
			{
				 //单发红包记录数据
				$time = date('Y-m-d H:i:s',time());
				$sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`) values ('.
					strval($_W['uniacid']).',\''.$_GPC['re_openid'].'\','.strval($_GPC['total_amount']/100.0).',\''.$time.'\',\'single\',\'success\')'; 
	pdo_query($sql);
	message($result->return_msg,'','success');  	
}
}
public function doWebRukou7()
{
	global $_GPC,$_W;
			//群发红包设置

	load()->func('tpl');
	include $this->template('masssend');


}
public function doWebSendgroup()
{
			//群发红包
	global $_W,$_GPC;
	if(!empty($_GPC['groupId'])) 
	{
		$this->module['config']['group']['groupId'] = $_GPC['groupId'];
	}
	if(!empty($_GPC['total_amount'])) 
	{
		$this->module['config']['group']['total_amount'] = $_GPC['total_amount'];
	}
	if(!empty($_GPC['nick_name'])) 
	{
		$this->module['config']['group']['nick_name'] = $_GPC['nick_name'];
	}
	if(!empty($_GPC['send_name'])) 
	{
		$this->module['config']['group']['send_name'] = $_GPC['send_name'];
	}
	if(!empty($_GPC['wishing'])) 
	{
		$this->module['config']['group']['wishing'] = $_GPC['wishing'];
	}
	if(!empty($_GPC['act_name'])) 
	{
		$this->module['config']['group']['act_name'] = $_GPC['act_name'];
	}
	if(!empty($_GPC['remark'])) 
	{
		$this->module['config']['group']['remark'] = $_GPC['remark'];
	}
	$this->saveSettings($this->module['config']);
	if($_GPC['submit'] == '保存')
	{
		message('保存成功！','','success');
		return ;
	}
			//从数据库中获得openid
	$sql = 'SELECT openid FROM '.tablename('mc_mapping_fans').' WHERE `uniacid` = :acid and `groupid` = :groupid ';
	$params = array(':acid' => $_W['uniacid'],':groupid'=>$_GPC['groupId']);
	$result=pdo_fetchall($sql, $params);
	$openidNum=count($result);
         	//message($result[3]['openid'],'','success');
	load()->classs('weixin.account');
        //发送数据

	print_r('发送开始 <br/>');
	for ($k=0; $k <$openidNum; $k++) 
	{
		$touser=$result[$k]['openid'];
		$postresult= $this->pay($touser,$_GPC['nick_name'],$_GPC['send_name'],$_GPC['total_amount'],$_GPC['wishing'],$_GPC['act_name'],$_GPC['remark']);
		if($postresult->result_code == 'FAIL')
		{
			print_r('用户:'.$touser.'发送失败 失败原因:'.$postresult->err_code_des.'<br/>');
		}
		else
		{
			$time = date('Y-m-d H:i:s',time());
			$sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`) values ('.
				strval($_W['uniacid']).',\''.$touser.'\','.strval($_GPC['total_amount']/100.0).',\''.$time.'\',\'qunfa\',\'success\')'; 
	pdo_query($sql);
	print_r('用户:'.$touser.'  发送成功<br/>');
}
}
print_r('发送结束 请按返回键返回<br/>');



}

public function doWebRukou5()
{

global $_GPC,$_W;
    	//随机红包设置
	load()->func('tpl');
	include $this->template('suiji');
}
public function doWebRukou6()
{
	global $_GPC,$_W;
	$this -> init();
	$kind = $_GPC['kind'];
	$startTime = $_GPC['begainDate'];
	$endTime = $_GPC['endDate'];

	$sql = 'SELECT openid ,moneyCount ,time, type ,state FROM ' . tablename('pzh_record') . ' WHERE `uniacid` = \''.$_W['uniacid'].'\' ';
	if(!empty($startTime))
	{
		$sql=$sql.'and `time` >= \''.$startTime.'\' and `time` <= \''.$endTime.'\' ';
	}
	else 
	{
		$startTime = date("Y-m-d",time());
		$endTime = date("Y-m-d",strtotime("+1 day"));
		$sql=$sql.'and `time` >= \''.$startTime.'\' and `time` <= \''.$endTime.'\' ';
	}
	if(!empty($kind)&&$kind!='全部')
	{
		if($kind == '图文')
			$type = 'tuwen';
		if($kind == '关注')
			$type = 'guanzhu';
		if($kind == '菜单')
			$type= 'caidan';
		if($kind == '随机')
			$type = 'suiji';
		if($kind == '单发')
			$type = 'single';
		if($kind == '群发')
			$type = 'qunfa';
		if($kind == '分享')
			$type = 'fenxiang';
		$sql = $sql.'and `type` = \''.$type.'\'';
	}
   
	
	$account = pdo_fetchall($sql, $params);
	$result = array();
	for ($i=0; $i <count($account) ; $i++) 
	{ 

		array_push($result, array_merge(array('id'=>$i+1),$account[$i]));
	}

	load()->func('tpl');
	include $this->template('search');

}
public function doWebSuijisave()
{
	    //保存随机红包参数
	global $_W,$_GPC;


	$this->module['config']['suiji']['successUrl']=  $_GPC['successUrl'];
	$this->module['config']['suiji']['successMsg']=  $_GPC['successMsg'];
    //领取失败
	$this->module['config']['suiji']['errorMsg']=  $_GPC['errorMsg'];
	$this->module['config']['suiji']['errorUrl']=  $_GPC['errorUrl'];
    //用户领取达到限制
	$this->module['config']['suiji']['limitMsg']=  $_GPC['limitMsg'];
	$this->module['config']['suiji']['limitUrl']=  $_GPC['limitUrl'];
    //红包已经发完
	$this->module['config']['suiji']['sendAllMsg']=  $_GPC['sendAllMsg'];
	$this->module['config']['suiji']['sendAllUrl']=  $_GPC['sendAllUrl'];
    //领取频繁文案
	$this->module['config']['suiji']['havegetMsg']=  $_GPC['havegetMsg'];
	$this->module['config']['suiji']['havegetUrl']=  $_GPC['havegetUrl'];
    //活动时间
	$this->module['config']['suiji']['nobegin'] = $_GPC['nobegin'];
	$this->module['config']['suiji']['haveend'] = $_GPC['haveend'];
	$this->module['config']['suiji']['begin_time'] = $_GPC['begin_time'];
	$this->module['config']['suiji']['end_time'] = $_GPC['end_time'];
	$this->module['config']['suiji']['haveendUrl']=$_GPC['haveendUrl'];
	$this->module['config']['suiji']['nobeginUrl'] = $_GPC['nobeginUrl'];
	if(!empty($_GPC['sub'])) 
	{
		$this->module['config']['suiji']['sub'] = $_GPC['sub'];
	}
	else 
	{
		$this->module['config']['suiji']['sub'] ='否';
	}

	if(!empty($_GPC['time'])) 
	{
		$this->module['config']['suiji']['time'] = $_GPC['time'];
	}
	else 
	{
		$this->module['config']['suiji']['time'] =180;
	}
	if(!empty($_GPC['key'])) 
	{
		$this->module['config']['suiji']['key'] = $_GPC['key'];
	}
	if(!empty($_GPC['money_list'])) 
	{


		$this->module['config']['suiji']['money_list'] =  $_GPC['money_list'];
	}
	if(!empty($_GPC['rand_list'])) 
	{


		$this->module['config']['suiji']['rand_list'] =  $_GPC['rand_list'];
	}

	if(!empty($_GPC['small'])) 
	{
	       	//随机金额小数部分
		$this->module['config']['suiji']['small'] = $_GPC['small'];
	}

	if(!empty($_GPC['nick_name'])) 
	{
		$this->module['config']['suiji']['nick_name'] = $_GPC['nick_name'];
	}
	if(!empty($_GPC['send_name'])) 
	{
		$this->module['config']['suiji']['send_name'] = $_GPC['send_name'];
	}
	if(!empty($_GPC['wishing'])) 
	{
		$this->module['config']['suiji']['wishing'] = $_GPC['wishing'];
	}
	if(!empty($_GPC['act_name'])) 
	{
		$this->module['config']['suiji']['act_name'] = $_GPC['act_name'];
	          // message($_GPC['act_name'],'','success');
	}

	if(!empty($_GPC['remark'])) 
	{
		$this->module['config']['suiji']['remark'] = $_GPC['remark'];
	}
	if(!empty($_GPC['maxCount'])) 
	{
		$this->module['config']['suiji']['maxCount'] = $_GPC['maxCount'];
	}
	if(!empty($_GPC['addressLimit'])) 
	{
		$this->module['config']['suiji']['addressLimit'] = $_GPC['addressLimit'];
	}
	else
	{
		$this->module['config']['suiji']['addressLimit'] = '';
	}



	if(!empty($_GPC['maxRedCount'])) 
	{

		$this->module['config']['suiji']['maxRedCount'] = $_GPC['maxRedCount'];	
	}
	else 
	{
		$this->module['config']['suiji']['maxRedCount'] =0 ;
	}

	$this->saveSettings($this->module['config']);



	message('保存成功！','','success');
	return ;
}
public function doMobileSendsuiji()
{
	  //发送随机红包
	global $_GPC,$_W;
            //判断是否在时间内
	$time = date('Y-m-d H:i:s',time());
	if($time< $this->module['config']['suiji']['begin_time'] )
	{
		if(!empty($this->module['config']['suiji']['nobeginUrl']))
		{
			header("Location: ".$this->module['config']['suiji']['nobeginUrl']); 
			exit ;
		}
		if(empty($this->module['config']['suiji']['nobegin']))
		{
			$errorMsg='活动未开始';
		}
		else 
		{
			$errorMsg = $this->module['config']['suiji']['nobegin'];
		}
		include $this->template('fail');
		return;
	}
	if($time> $this->module['config']['suiji']['end_time'] )
	{
		if(!empty($this->module['config']['suiji']['haveendUrl']))
		{
			header("Location: ".$this->module['config']['suiji']['haveendUrl']); 
			exit ;
		}
		if(empty($this->module['config']['suiji']['haveend']))
		{
			$errorMsg='活动已结束';
		}
		else 
		{
			$errorMsg = $this->module['config']['suiji']['haveend'];
		}
		include $this->template('fail');
		return;
	}


	$weid = $_W['uniacid'];
             //判断是否关注过
	if($this->module['config']['suiji']['sub']=='否')
	{
		$follow = true;
	}
	else
	{
		$follow = $this->judgeFollow();
	}
	if($follow==false)
	{
		$errorMsg='请先关注公众号:'.$this->module['config']['gongzhonghao'];
		include $this->template('fail');
		return;
	}

	if(empty($_COOKIE['pzh_openid'.$weid]))
	{
		$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=xoauth";
		$xoauthURL = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=sendsuiji";
		setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));
		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->module['config']['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
		header("location:$oauth2_code");
	}
	else 
	{
		$this ->init();
		$addressLimit = $this->module['config']['suiji']['addressLimit'];
		if(!empty($addressLimit))
		{
	        $url = 'http://www.ip138.com/ips1388.asp?ip='.CLIENT_IP.'&action=2'; //这儿填页面地址
	        $info=file_get_contents($url);
	         // message(json_encode($url),'','error');
	        $content=iconv("GBK", "UTF-8//IGNORE", $info);
	        preg_match('|<li>(.*?)<\/li>|i',$content,$userAddress);
	        
	        $limit = explode('，',$addressLimit); 
            // message(json_encode( $limit),'','error');
	        $flag = 0;
	        for ($i=0; $i < count($limit); $i++) 
	        { 

	        	if(strpos($userAddress[1],$limit[$i]))
	        	{
	        		$flag=1;
	        		break;
	        	}
	        }
	        if($flag == 0)
	        {

	        	$errorMsg='您的位置不在本活动范围内哦~';
	        	include $this->template('fail');
	        	return;
	         // message('您的位置不在本活动范围内哦~','','error');
	        }

	    } 



	    //*************************************************************************************
	    $re_openid   =  $_COOKIE['pzh_openid'.$weid];
	    $nick_name   =  $this->module['config']['suiji']['nick_name'];
	    $send_name   =  $this->module['config']['suiji']['send_name'];
	    $wishing     =  $this->module['config']['suiji']['wishing'];
	    $remark      =  $this->module['config']['suiji']['remark'];
	    $act_name    =  $this->module['config']['suiji']['act_name'];
	    $maxCount    =  $this->module['config']['suiji']['maxCount'];

	    $rand_list   =  $this->module['config']['suiji']['rand_list'];
	    $money_list  =  $this->module['config']['suiji']['money_list'];
	    $small       =  $this->module['config']['suiji']['small'];
	    $money_list = explode('，',$money_list); 
	    $rand_list = explode('，',$rand_list); 

	    $maxRedCount = $this ->module['config']['suiji']['maxRedCount'];


	    if(count($rand_list)==0||count($rand_list)!=count($money_list))
	    {
	       	//金额数和权值个数对不上

	    	$errorMsg='金额数和权值数不一致或空';
	    	include $this->template('fail');
	    	return ;
	    }
			//获得金额小数部分
	    $smallNum = rand(0,$small);
	    $all_quan = 0;
	       //计算随机金额
	    for ($i=0; $i <count($rand_list) ; $i++) 
	    { 
	    	$all_quan = $all_quan + $rand_list[$i];
	    }
	    if($all_quan==0)
	    {
	    	$errorMsg='总权值是0';
	    	include $this->template('fail');
	    	return ;
	    }
	    $seed = rand(1,$all_quan);
	    for ($i=0; $i <count($rand_list) ; $i++) 
	    { 
	    	if($seed > $rand_list[$i] )
	    	{
	    		$seed = $seed - $rand_list[$i];
	    	}
	    	else
	    	{
	    		break;
	    	}
	    }

	    $total_amount =  $money_list[$i] + $smallNum;


	    if($maxRedCount <= 0)
	    {
	    	//红包已领完
	    	if(!empty($this->module['config']['suiji']['sendAllUrl']))
	    	{
	    		header("Location: ".$this->module['config']['suiji']['sendAllUrl']); 
	    		exit ;
	    	}
	    	if(!empty($this->module['config']['suiji']['sendAllMsg']))
	    	{
	    		$errorMsg = $this->module['config']['suiji']['sendAllMsg'];
	    	}
	    	else 
	    	{
	    		$errorMsg='红包已领完';
	    	}
	    	include $this->template('fail');
	    	return;

	         // return $this->respText('红包已领完~');
	    }
        //先减一
	    $this ->module['config']['suiji']['maxRedCount'] = $this ->module['config']['suiji']['maxRedCount']-1;
	    $this->saveSettings($this->module['config']);
	    $sql = 'SELECT redPackCount,lastTime FROM ' . tablename('pzh_packet2') . ' WHERE `uniacid` = :uniacid and `type` = :type and `remark` = :trueopenid';
	    $params = array(':uniacid' => $_W['uniacid'],':type' => 'suiji' , ':trueopenid' => $_W['openid']);
	    $account = pdo_fetch($sql, $params);
	    if(!$account)
	    {
	        //如果查询不到该用户
	    	$sql = 'INSERT INTO'.tablename('pzh_packet2') .' (`uniacid`,`openid`,`redPackCount`,`lastTime`,`type`,`remark`) values ('.
	    		strval($_W['uniacid']).',\''.$re_openid.'\',0,'.strval($_W['timestamp']).',\'suiji\' ,\''.$_W['openid'].'\')'; 
	$result = pdo_query($sql);
	         // return $this->respText($sql);
}
else
{
	        //曾经拿过红包
	if($_W['timestamp']-$account['lastTime']<=$this->module['config']['suiji']['time'])
	{
		  //失败加一
		$this ->module['config']['suiji']['maxRedCount'] = $this ->module['config']['suiji']['maxRedCount']+1;
		$this->saveSettings($this->module['config']);
	          // return $this->respText('您刚领取过红包哦~');
		if(!empty($this->module['config']['suiji']['havegetUrl']))
		{
			header("Location: ".$this->module['config']['suiji']['havegetUrl']); 
			exit ;
		}
		if(!empty($this->module['config']['suiji']['havegetMsg']))
		{
			$errorMsg = $this->module['config']['suiji']['havegetMsg'];
		}
		else 
		{
			$errorMsg='您刚领取过红包哦';
		}
		
		include $this->template('fail');
		return;

	}
	else if($account['redPackCount']>=$maxCount)
	{
	          //红包个数超过设定值
		 //失败加一
		$this ->module['config']['suiji']['maxRedCount'] = $this ->module['config']['suiji']['maxRedCount']+1;
		$this->saveSettings($this->module['config']);
	          // return $this->respText('您的红包已领完~');
		        //用户红包个数超过设定值
		if(!empty($this->module['config']['suiji']['limitUrl']))
		{
			header("Location: ".$this->module['config']['suiji']['limitUrl']); 
			exit ;
		}
		if(!empty($this->module['config']['suiji']['limitMsg']))
		{
			$errorMsg = $this->module['config']['suiji']['limitMsg'];
		}
		else 
		{
			$errorMsg='您的红包已领完';
		}
		include $this->template('fail');
		return;

	}


}  


$result = $this->pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$db=null);

if($result->result_code == 'FAIL' || $result ==  'fail')
{


   //领取失败
	 //失败加一
	$this ->module['config']['suiji']['maxRedCount'] = $this ->module['config']['suiji']['maxRedCount']+1;
	$this->saveSettings($this->module['config']);
	if(!empty($this->module['config']['suiji']['errorUrl']))
	{
		header("Location: ".$this->module['config']['suiji']['errorUrl']); 
		exit ;
	}
	if(!empty($this->module['config']['suiji']['errorMsg']))
	{
		$errorMsg = $this->module['config']['suiji']['errorMsg'];
	}
	else 
	{
		$errorMsg=$result->err_code_des;
	}
	
	include $this->template('fail');
	return;
}
else
{
	


	$sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = ' .strval($account['redPackCount']+1) . 
	' ,`lastTime`= ' . strval($_W['timestamp']). ' WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \'suiji\' and `remark` = \''.$_W['openid'].'\'  ';

	$result = pdo_query($sql);
      //随机红包记录数据
	$time = date('Y-m-d H:i:s',time());
	$sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`,`remark`) values ('.
		strval($_W['uniacid']).',\''.$re_openid.'\','.strval($total_amount/100.0).',\''.$time.'\',\'suiji\',\'success\',\''.$_W['openid'].'\')'; 
	pdo_query($sql);
           //发送成功  
	if(!empty($this->module['config']['suiji']['successUrl']))
	{
		header("Location: ".$this->module['config']['suiji']['successUrl']); 
		exit ;
	}
	if(!empty($this->module['config']['suiji']['successMsg']))
	{
		$successMsg = $this->module['config']['suiji']['successMsg'];
	}
	else 
	{
		$successMsg = '恭喜你获得一个红包~请到服务通知或者聊天窗查看哦';
	}

	include $this->template('success');
	return;

}
	      // return $this->respText('红包还没准备好哦');
$errorMsg='红包还没准备好哦~';
include $this->template('fail');
return;

	//************************************************************************************************************************************
}

}



public function doWebRukou4()
{
	global $_GPC,$_W;
	    //图文红包设置入口
	    // message('hello','','success');
	load()->func('tpl');
	include $this->template('tuwen');


}
public function doWebTuwensave()
{
	    //保存图文红包信息
	global $_W,$_GPC;
    //领取成功
	$this->module['config']['tuwen']['successUrl']=  $_GPC['successUrl'];
	$this->module['config']['tuwen']['successMsg']=  $_GPC['successMsg'];
    //领取失败
	$this->module['config']['tuwen']['errorMsg']=  $_GPC['errorMsg'];
	$this->module['config']['tuwen']['errorUrl']=  $_GPC['errorUrl'];
    //用户领取达到限制
	$this->module['config']['tuwen']['limitMsg']=  $_GPC['limitMsg'];
	$this->module['config']['tuwen']['limitUrl']=  $_GPC['limitUrl'];
    //红包已经发完
	$this->module['config']['tuwen']['sendAllMsg']=  $_GPC['sendAllMsg'];
	$this->module['config']['tuwen']['sendAllUrl']=  $_GPC['sendAllUrl'];

       //领取频繁文案
	$this->module['config']['tuwen']['havegetMsg']=  $_GPC['havegetMsg'];
	$this->module['config']['tuwen']['havegetUrl']=  $_GPC['havegetUrl'];
     //活动时间
	$this->module['config']['tuwen']['nobegin'] = $_GPC['nobegin'];
	$this->module['config']['tuwen']['haveend'] = $_GPC['haveend'];
	$this->module['config']['tuwen']['begin_time'] = $_GPC['begin_time'];
	$this->module['config']['tuwen']['end_time'] = $_GPC['end_time'];
	$this->module['config']['tuwen']['haveendUrl']=$_GPC['haveendUrl'];
	$this->module['config']['tuwen']['nobeginUrl'] = $_GPC['nobeginUrl'];
	if(!empty($_GPC['sub'])) 
	{
		$this->module['config']['tuwen']['sub'] = $_GPC['sub'];
	}
	else 
	{
		$this->module['config']['tuwen']['sub'] ='否';
	}
	if(!empty($_GPC['time'])) 
	{
		$this->module['config']['tuwen']['time'] = $_GPC['time'];
	}
	else 
	{
		$this->module['config']['tuwen']['time'] =180;
	}
	if(!empty($_GPC['key'])) 
	{
		$this->module['config']['tuwen']['key'] = $_GPC['key'];
	}
	if(!empty($_GPC['total_amount'])) 
	{
		$this->module['config']['tuwen']['total_amount'] = $_GPC['total_amount'];
	}
	if(!empty($_GPC['nick_name'])) 
	{
		$this->module['config']['tuwen']['nick_name'] = $_GPC['nick_name'];
	}
	if(!empty($_GPC['send_name'])) 
	{
		$this->module['config']['tuwen']['send_name'] = $_GPC['send_name'];
	}
	if(!empty($_GPC['wishing'])) 
	{
		$this->module['config']['tuwen']['wishing'] = $_GPC['wishing'];
	}
	if(!empty($_GPC['act_name'])) 
	{
		$this->module['config']['tuwen']['act_name'] = $_GPC['act_name'];
	          // message($_GPC['act_name'],'','success');
	}
	if(!empty($_GPC['remark'])) 
	{
		$this->module['config']['tuwen']['remark'] = $_GPC['remark'];
	}
	if(!empty($_GPC['maxCount'])) 
	{
		$this->module['config']['tuwen']['maxCount'] = $_GPC['maxCount'];
	}
	if(!empty($_GPC['addressLimit'])) 
	{
		$this->module['config']['tuwen']['addressLimit'] = $_GPC['addressLimit'];
	}
	else
	{
		$this->module['config']['tuwen']['addressLimit'] = '';
	}

	if(!empty($_GPC['maxRedCount'])) 
	{
		$this->module['config']['tuwen']['maxRedCount'] = $_GPC['maxRedCount'];

	}
	else 
	{
		$this->module['config']['tuwen']['maxRedCount'] =0 ;

	}
	$this->saveSettings($this->module['config']);

	message('保存成功！','','success');
	return ;

}
public function doMobileRedmoney() 
{
	  	//发送图文红包
	// load()->func('tpl');
	// include $this->template('test');
	// return ;
	global $_GPC,$_W;
     //判断是否在时间内
	$time = date('Y-m-d H:i:s',time());
	if($time< $this->module['config']['tuwen']['begin_time'] )
	{
		if(!empty($this->module['config']['tuwen']['nobeginUrl']))
		{
			header("Location: ".$this->module['config']['tuwen']['nobeginUrl']); 
			exit ;
		}
		if(empty($this->module['config']['tuwen']['nobegin']))
		{
			$errorMsg='活动未开始';
		}
		else 
		{
			$errorMsg = $this->module['config']['tuwen']['nobegin'];
		}
		include $this->template('fail');
		return;
	}
	if($time> $this->module['config']['tuwen']['end_time'] )
	{
		if(!empty($this->module['config']['tuwen']['haveendUrl']))
		{
			header("Location: ".$this->module['config']['tuwen']['haveendUrl']); 
			exit ;
		}
		if(empty($this->module['config']['tuwen']['haveend']))
		{
			$errorMsg='活动已结束';
		}
		else 
		{
			$errorMsg = $this->module['config']['tuwen']['haveend'];
		}
		include $this->template('fail');
		return;
	}


	$weid = $_W['uniacid'];
	         //判断是否关注过
	if($this->module['config']['tuwen']['sub']=='否')
	{
		$follow = true;
	}
	else
	{
		$follow = $this->judgeFollow();
	}
	if($follow==false)
	{
		$errorMsg='请先关注公众号:'.$this->module['config']['gongzhonghao'];
		include $this->template('fail');
		return;
	}
	if(empty($_COOKIE['pzh_openid'.$weid]))
	{

		$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=xoauth";

		$xoauthURL = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=redmoney";
		setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));

		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->module['config']['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
		header("location:$oauth2_code");
	}
	else 
	{


		$this ->init();


		$addressLimit = $this->module['config']['tuwen']['addressLimit'];
		if(!empty($addressLimit))
		{


	        $url = 'http://www.ip138.com/ips1388.asp?ip='.CLIENT_IP.'&action=2'; //这儿填页面地址
	        $info=file_get_contents($url);
	         // message(json_encode($url),'','error');
	        $content=iconv("GBK", "UTF-8//IGNORE", $info);
	        preg_match('|<li>(.*?)<\/li>|i',$content,$userAddress);
	        
	        $limit = explode('，',$addressLimit); 

	        $flag = 0;
	        for ($i=0; $i < count($limit); $i++) 
	        { 

	        	if(strpos($userAddress[1],$limit[$i]))
	        	{

	        		$flag=1;
	        		break;
	        	}
	        }
	        if($flag == 0)
	        {
	        	$errorMsg='您的位置不在本活动范围内哦~';
	        	include $this->template('fail');
	        	return;
	         // message('您的位置不在本活动范围内哦~','','error');
	        }

	    } 
	    


	    //*************************************************************************************
	    $re_openid =$_COOKIE['pzh_openid'.$weid];
	    $nick_name = $this->module['config']['tuwen']['nick_name'];
	    $send_name =  $this->module['config']['tuwen']['send_name'];
	    $total_amount =  $this->module['config']['tuwen']['total_amount'];
	    $wishing = $this->module['config']['tuwen']['wishing'];
	    $remark =  $this->module['config']['tuwen']['remark'];
	    $act_name =  $this->module['config']['tuwen']['act_name'];
	    $maxCount =  $this->module['config']['tuwen']['maxCount'];



	    $maxRedCount = $this ->module['config']['tuwen']['maxRedCount'];


	    if($maxRedCount <= 0)
	    {
	    	if(!empty($this->module['config']['tuwen']['sendAllUrl']))
	    	{
	    		header("Location: ".$this->module['config']['tuwen']['sendAllUrl']); 
	    		exit ;
	    	}
	    	if(!empty($this->module['config']['tuwen']['sendAllMsg']))
	    	{
	    		$errorMsg = $this->module['config']['tuwen']['sendAllMsg'];
	    	}
	    	else 
	    	{
	    		$errorMsg='红包已领完';
	    	}

	    	
	    	include $this->template('fail');
	    	return;
	         // return $this->respText('红包已领完~');
	    }
        //先减一
	    $this ->module['config']['tuwen']['maxRedCount'] = $maxRedCount-1;
	    $this->saveSettings($this->module['config']);
	    $sql = 'SELECT redPackCount,lastTime FROM ' . tablename('pzh_packet2') . ' WHERE `uniacid` = :uniacid and `type` = :type and `remark` = :trueopenid';
	    $params = array(':uniacid' => $_W['uniacid'],':type' => 'tuwen' , ':trueopenid' => $_W['openid']);
	    $account = pdo_fetch($sql, $params);
	    if(!$account)
	    {
	        //如果查询不到该用户
	    	$sql = 'INSERT INTO'.tablename('pzh_packet2') .' (`uniacid`,`openid`,`redPackCount`,`lastTime`,`type`,`remark`) values ('.
	    		strval($_W['uniacid']).',\''.$re_openid.'\',0,'.strval($_W['timestamp']).',\'tuwen\',\''.$_W['openid'].'\')'; 
	$result = pdo_query($sql);
	         // return $this->respText($sql);
}
else
{
	        //曾经拿过红包
	if($_W['timestamp']-$account['lastTime']<=$this->module['config']['tuwen']['time'])
	{
		 //失败加一
		$this ->module['config']['tuwen']['maxRedCount'] = $this ->module['config']['tuwen']['maxRedCount']+1;
		$this->saveSettings($this->module['config']);
	          // return $this->respText('您刚领取过红包哦~');
		if(!empty($this->module['config']['tuwen']['havegetUrl']))
		{
			header("Location: ".$this->module['config']['tuwen']['havegetUrl']); 
			exit ;
		}
		if(!empty($this->module['config']['tuwen']['havegetMsg']))
		{
			$errorMsg = $this->module['config']['tuwen']['havegetMsg'];
		}
		else 
		{
			$errorMsg='您刚领取过红包哦';
		}

		include $this->template('fail');
		return;

	}
	else if($account['redPackCount']>=$maxCount)
	{
	          //用户红包个数超过设定值
		//失败加一
		$this ->module['config']['tuwen']['maxRedCount'] = $this ->module['config']['tuwen']['maxRedCount']+1;
		$this->saveSettings($this->module['config']);
		if(!empty($this->module['config']['tuwen']['limitUrl']))
		{
			header("Location: ".$this->module['config']['tuwen']['limitUrl']); 
			exit ;
		}
		if(!empty($this->module['config']['tuwen']['limitMsg']))
		{
			$errorMsg = $this->module['config']['tuwen']['limitMsg'];
		}
		else 
		{
			$errorMsg='您的红包已领完';
		}
		include $this->template('fail');
		return;

	}


}  

@require "pay.php";
$packet = new Packet();
$result = $this->pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$db=null);

if($result->result_code == 'FAIL' || $result ==  'fail')
{
      //领取失败
	//失败加一
	$this ->module['config']['tuwen']['maxRedCount'] = $this ->module['config']['tuwen']['maxRedCount']+1;
	$this->saveSettings($this->module['config']);
	if(!empty($this->module['config']['tuwen']['errorUrl']))
	{
		header("Location: ".$this->module['config']['tuwen']['errorUrl']); 
		exit ;
	}
	if(!empty($this->module['config']['tuwen']['errorMsg']))
	{
		$errorMsg = $this->module['config']['tuwen']['errorMsg'];
	}
	else 
	{
		$errorMsg=$result->err_code_des;
	}

	include $this->template('fail');
	return;
}
else
{
	
	

	$sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = ' .strval($account['redPackCount']+1) . 
	' ,`lastTime`= ' . strval($_W['timestamp']). ' WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \'tuwen\' and `remark` = \''.$_W['openid'].'\'  ';

	$result = pdo_query($sql);
      //图文红包记录数据
	$time = date('Y-m-d H:i:s',time());
	$sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`,`remark`) values ('.
		strval($_W['uniacid']).',\''.$re_openid.'\','.strval($total_amount/100.0).',\''.$time.'\',\'tuwen\',\'success\',\''.$_W['openid'].'\')'; 
	pdo_query($sql);
	     // return $this->respText('恭喜您获得一个红包~');
	if(!empty($this->module['config']['tuwen']['successUrl']))
	{
		header("Location: ".$this->module['config']['tuwen']['successUrl']); 
		exit ;
	}
	if(!empty($this->module['config']['tuwen']['successMsg']))
	{
		$successMsg = $this->module['config']['tuwen']['successMsg'];
	}
	else 
	{
		$successMsg = '恭喜你获得一个红包~请到服务通知或者聊天窗查看哦';
	}

	
	include $this->template('success');
	return;

}
	      // return $this->respText('红包还没准备好哦');
$errorMsg='红包还没准备好哦~';
include $this->template('fail');
return;

	//************************************************************************************************************************************
}
	    // message($_W['openid'],'','success');
}



public function doMobileRedmoney2() 
{
	global $_W;
	message('hello2','','success');
}
public function doWebRukou1() 
{
	global $_GPC,$_W;
			//单发红包界面
	load()->func('tpl');
	include $this->template('send');
}


public function doWebRukou2() 
{
	global $_GPC,$_W;
			//关注送红包设置
	load()->func('tpl');
	include $this->template('guanzhu');

}
public function doWebSaveguanzhu()
{
			//保存关注送红包信息
	global $_W,$_GPC;
	if(!empty($_GPC['key'])) 
	{
		$this->module['config']['guanzhu']['key'] = $_GPC['key'];
	}
	if(!empty($_GPC['total_amount'])) 
	{
		$this->module['config']['guanzhu']['total_amount'] = $_GPC['total_amount'];
	}
	if(!empty($_GPC['nick_name'])) 
	{
		$this->module['config']['guanzhu']['nick_name'] = $_GPC['nick_name'];
	}
	if(!empty($_GPC['send_name'])) 
	{
		$this->module['config']['guanzhu']['send_name'] = $_GPC['send_name'];
	}
	if(!empty($_GPC['wishing'])) 
	{
		$this->module['config']['guanzhu']['wishing'] = $_GPC['wishing'];
	}
	if(!empty($_GPC['act_name'])) 
	{
		$this->module['config']['guanzhu']['act_name'] = $_GPC['act_name'];
	          // message($_GPC['act_name'],'','success');
	}
	if(!empty($_GPC['remark'])) 
	{
		$this->module['config']['guanzhu']['remark'] = $_GPC['remark'];
	}
	if(!empty($_GPC['maxCount'])) 
	{
		$this->module['config']['guanzhu']['maxCount'] = $_GPC['maxCount'];
	}
	       //   if(!empty($_GPC['addressLimit'])) 
	       // {
	       //  //地区限制
	       //    $this->module['config']['guanzhu']['addressLimit'] = $_GPC['addressLimit'];
	       // }
	if(!empty($_GPC['maxRedCount'])) 
	{
		$this->module['config']['guanzhu']['maxRedCount'] = $_GPC['maxRedCount'];
	}
	else 
	{
		$this->module['config']['guanzhu']['maxRedCount'] =0 ;
	}
	$this->saveSettings($this->module['config']);

	message('保存成功！','','success');
	return ;

}
public function doWebRukou3() 
{
	global $_GPC,$_W;
			//进入菜单送红包设置
	load()->func('tpl');
	include $this->template('caidan');

} 
public function doWebSavecaidan()
{
	    //菜单送红包设置保存
	global $_W,$_GPC;
	if(!empty($_GPC['key'])) 
	{
		$this->module['config']['caidan']['key'] = $_GPC['key'];
	}
	if(!empty($_GPC['total_amount'])) 
	{
		$this->module['config']['caidan']['total_amount'] = $_GPC['total_amount'];
	}
	if(!empty($_GPC['nick_name'])) 
	{
		$this->module['config']['caidan']['nick_name'] = $_GPC['nick_name'];
	}
	if(!empty($_GPC['send_name'])) 
	{
		$this->module['config']['caidan']['send_name'] = $_GPC['send_name'];
	}
	if(!empty($_GPC['wishing'])) 
	{
		$this->module['config']['caidan']['wishing'] = $_GPC['wishing'];
	}
	if(!empty($_GPC['act_name'])) 
	{
		$this->module['config']['caidan']['act_name'] = $_GPC['act_name'];
	          // message($_GPC['act_name'],'','success');
	}
	if(!empty($_GPC['remark'])) 
	{
		$this->module['config']['caidan']['remark'] = $_GPC['remark'];
	}
	if(!empty($_GPC['maxCount'])) 
	{
		$this->module['config']['caidan']['maxCount'] = $_GPC['maxCount'];
	}
	if(!empty($_GPC['maxRedCount'])) 
	{
		$this->module['config']['caidan']['maxRedCount'] = $_GPC['maxRedCount'];
	}
	else 
	{
		$this->module['config']['caidan']['maxRedCount'] =0 ;
	}
	$this->saveSettings($this->module['config']);

	message('保存成功！','','success');
	return ;

}


public function doMobileTest2()
{
	global $_GPC,$_W;
	 echo phpversion();
}
public function doWebReset()
{

	  ///数据清零
	$this -> init();
	global $_GPC,$_W;
	$type = $_GPC['typeName'];
	$sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = 0  WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \''.$type.'\'  ';
	$result = pdo_query($sql);
	message('活动重新开启!用户领取红包次数清零！以前领取过红包的用户可以再次领取红包！','','success');
}


function sign($content, $key) {

global $_GPC,$_W;
	if (null == $key) {
		message('签名key不能为空','','error');
	}
	if (null == $content) {
		message('签名内容不能为空','','error');

	}
	$signStr = $content . "&key=" . $key;

	return strtoupper(md5($signStr));
}
function get_sign()
{
	global $_GPC,$_W;
	define('PARTNERKEY',$this->module['config']['password'] );

	if (null == PARTNERKEY || "" == PARTNERKEY ) {
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
			// }catch (SDKRuntimeException $e)
			// {
			// 	die($e->errorMessage());
			// }

			}
			function check_sign_parameters(){
				global $_GPC,$_W;
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
				global $_GPC,$_W;
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
$tableName = $_W['config']['db']['tablepre'].'pzh_fansrecord';
      //       if(!$exists)
	    	// {
	    	// 	$sql = 'CREATE TABLE '.$tableName.' (
	    	// 		`uniacid` int(10)  NOT NULL,
	    	// 		`acid` varchar(35) NOT NULL,
	    	// 		`trueopenid` varchar(35) ,
	    	// 		`aouthopenid` varchar(35) ,
	    	// 		`time` varchar(50) ,
	    	// 		`username` varchar(50),
	    	// 		`remark`   varchar(50)
	    	// 		) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

	     //        pdo_run($sql);
      //       }

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


public function doMobileNewfun() {
	//      $url = 'http://www.ip138.com/ips1388.asp?ip='.CLIENT_IP.'&action=2'; //这儿填页面地址
	// $info=file_get_contents($url);
	// $content=iconv("GBK", "UTF-8//IGNORE", $info);
	// preg_match('|<li>(.*?)<\/li>|i',$content,$m);
	//  print_r($m[1]);
	global $_GPC,$_W;
	$weid = $_W['uniacid'];
	setcookie('pzh_openid'.$weid,$xoauthURL, time()-5);
	message('清除成功','','success');
	if(empty($_COOKIE['pzh_openid'.$weid]))
	{

		$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=xoauth";

		$xoauthURL = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=newfun";
		setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));





		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->module['config']['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
		header("location:$oauth2_code");
	}
	else 
	{
		message('有cookie'.$_COOKIE['pzh_openid'.$weid],'','success');
	}

	    // global $_GPC,$_W;
	    //  message($_W['openid'],'','error');

	    // $postUrl='http://wq.pzhss9.cc/app/index.php?i=2&c=entry&do=test2&m=pzh_money2&zmkm='.$_W['openid'].'';
	    // load()->func('communication');
	    // $json_data = array('zmkm'=>$_W['openid']);


	    // $json_data = json_encode($json_data);
	    // $result = ihttp_post($postUrl ,urldecode($json_data) );
	    // var_dump($result);
}
//分享样式设计
public function doWebFenxiangstyle(){
     global $_GPC,$_W;
 	$this->module['config']['fenxiang']['fenxiang_back_img']=  $_GPC['fenxiang_back_img'];
	$this->module['config']['fenxiang']['small_img']=  $_GPC['small_img'];
	$this->module['config']['fenxiang']['fenxiangtitle']=  $_GPC['fenxiangtitle'];
	$this->module['config']['fenxiang']['desc']=  $_GPC['desc'];
	$this->module['config']['fenxiang']['link']=  $_GPC['link'];
	
	$this->saveSettings($this->module['config']);



	message('保存成功！','','success');
	return ;

}
///分享红包样式入口
public function doMobileFenxiang(){

global $_W,$_GPC;
$weid = $_W['uniacid'];
	if(empty($_COOKIE['pzh_openid'.$weid]))
	{

		$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=xoauth";

		$xoauthURL = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=fenxiang";
		setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));

		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->module['config']['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
		header("location:$oauth2_code");
	}
	else{
		$link = $this->module['config']['fenxiang']['link'];
		$desc = $this->module['config']['fenxiang']['desc'];
		$small_img = $_W['attachurl'].$this->module['config']['fenxiang']['small_img'];
	    $fenxiangtitle =	$this->module['config']['fenxiang']['fenxiangtitle'];
	    $fenxiang_back_img = $_W['attachurl'].$this->module['config']['fenxiang']['fenxiang_back_img'];
        $openid = $_COOKIE['pzh_openid'.$weid];
		load()->func('tpl');
		include $this->template('test');
	}

}


//分享红包入口
public function doWebRukou8(){

global $_GPC,$_W;

	load()->func('tpl');
	include $this->template('fenxiang');
}
//保存分享红包信息
public function doWebFenxiangsave(){
	global $_W,$_GPC;
	$this->module['config']['fenxiang']['successUrl']=  $_GPC['successUrl'];
	$this->module['config']['fenxiang']['successMsg']=  $_GPC['successMsg'];
    //领取失败
	$this->module['config']['fenxiang']['errorMsg']=  $_GPC['errorMsg'];
	$this->module['config']['fenxiang']['errorUrl']=  $_GPC['errorUrl'];
    //用户领取达到限制
	$this->module['config']['fenxiang']['limitMsg']=  $_GPC['limitMsg'];
	$this->module['config']['fenxiang']['limitUrl']=  $_GPC['limitUrl'];
    //红包已经发完
	$this->module['config']['fenxiang']['sendAllMsg']=  $_GPC['sendAllMsg'];
	$this->module['config']['fenxiang']['sendAllUrl']=  $_GPC['sendAllUrl'];
    //领取频繁文案
	$this->module['config']['fenxiang']['havegetMsg']=  $_GPC['havegetMsg'];
	$this->module['config']['fenxiang']['havegetUrl']=  $_GPC['havegetUrl'];
    //活动时间
	$this->module['config']['fenxiang']['nobegin'] = $_GPC['nobegin'];
	$this->module['config']['fenxiang']['haveend'] = $_GPC['haveend'];
	$this->module['config']['fenxiang']['begin_time'] = $_GPC['begin_time'];
	$this->module['config']['fenxiang']['end_time'] = $_GPC['end_time'];
	$this->module['config']['fenxiang']['haveendUrl']=$_GPC['haveendUrl'];
	$this->module['config']['fenxiang']['nobeginUrl'] = $_GPC['nobeginUrl'];
	if(!empty($_GPC['sub'])) 
	{
		$this->module['config']['fenxiang']['sub'] = $_GPC['sub'];
	}
	else 
	{
		$this->module['config']['fenxiang']['sub'] ='否';
	}
//领取时间间隔
	if(!empty($_GPC['time'])) 
	{
		$this->module['config']['fenxiang']['time'] = $_GPC['time'];
	}
	else 
	{
		$this->module['config']['fenxiang']['time'] =180;
	}
//金额列表
	if(!empty($_GPC['money_list'])) 
	{

		$this->module['config']['fenxiang']['money_list'] =  $_GPC['money_list'];
	}
			//权值列表
	if(!empty($_GPC['rand_list'])) 
	{


		$this->module['config']['fenxiang']['rand_list'] =  $_GPC['rand_list'];
	}

	
	       	//随机金额小数部分
		$this->module['config']['fenxiang']['small'] = $_GPC['small'];
	
            //红包信息相关
	if(!empty($_GPC['nick_name'])) 
	{
		$this->module['config']['fenxiang']['nick_name'] = $_GPC['nick_name'];
	}
	if(!empty($_GPC['send_name'])) 
	{
		$this->module['config']['fenxiang']['send_name'] = $_GPC['send_name'];
	}
	if(!empty($_GPC['wishing'])) 
	{
		$this->module['config']['fenxiang']['wishing'] = $_GPC['wishing'];
	}
	if(!empty($_GPC['act_name'])) 
	{
		$this->module['config']['fenxiang']['act_name'] = $_GPC['act_name'];
	          // message($_GPC['act_name'],'','success');
	}

	if(!empty($_GPC['remark'])) 
	{
		$this->module['config']['fenxiang']['remark'] = $_GPC['remark'];
	}
	if(!empty($_GPC['maxCount'])) 
	{
		$this->module['config']['fenxiang']['maxCount'] = $_GPC['maxCount'];
	}
	if(!empty($_GPC['addressLimit'])) 
	{
		$this->module['config']['fenxiang']['addressLimit'] = $_GPC['addressLimit'];
	}
	else
	{
		$this->module['config']['fenxiang']['addressLimit'] = '';
	}



	if(!empty($_GPC['maxRedCount'])) 
	{

		$this->module['config']['fenxiang']['maxRedCount'] = $_GPC['maxRedCount'];	
	}
	else 
	{
		$this->module['config']['fenxiang']['maxRedCount'] =0 ;
	}

	$this->saveSettings($this->module['config']);



	message('保存成功！','','success');
	return ;
}

//分享成功发送
public function doMobileSendfenxiang(){
	global $_GPC,$_W;
            //判断是否在时间内
	
	$time = date('Y-m-d H:i:s',time());
	if($time< $this->module['config']['fenxiang']['begin_time'] )
	{
		if(!empty($this->module['config']['fenxiang']['nobeginUrl']))
		{
			header("Location: ".$this->module['config']['fenxiang']['nobeginUrl']); 
			exit ;
		}
		if(empty($this->module['config']['fenxiang']['nobegin']))
		{
			$errorMsg='活动未开始';
		}
		else 
		{
			$errorMsg = $this->module['config']['fenxiang']['nobegin'];
		}
		
		echo $errorMsg;
		return ;


	}
	if($time> $this->module['config']['fenxiang']['end_time'] )
	{
		if(!empty($this->module['config']['fenxiang']['haveendUrl']))
		{
			header("Location: ".$this->module['config']['fenxiang']['haveendUrl']); 
			exit ;
		}
		if(empty($this->module['config']['fenxiang']['haveend']))
		{
			$errorMsg='活动已结束';
		}
		else 
		{
			$errorMsg = $this->module['config']['fenxiang']['haveend'];
		}
		
		echo $errorMsg;
		return ;
	}
	

	$weid = $_W['uniacid'];
             //判断是否关注过
	if($this->module['config']['fenxiang']['sub']=='否')
	{
		$follow = true;
	}
	else
	{
		$follow = $this->judgeFollow();
	}
	if($follow==false)
	{
		$errorMsg='请先关注公众号:'.$this->module['config']['gongzhonghao'];
		
		echo $errorMsg;
		return ;
	}

	if(empty($_GPC['openid']))
	{

		 echo  "openid错误";
		 return;
	}
	else 
	{
		$this ->init();
		$addressLimit = $this->module['config']['fenxiang']['addressLimit'];
		// if(!empty($addressLimit))
		// {
	 //        $url = 'http://www.ip138.com/ips1388.asp?ip='.CLIENT_IP.'&action=2'; //这儿填页面地址
	 //        $info=file_get_contents($url);
	 //         // message(json_encode($url),'','error');
	 //        $content=iconv("GBK", "UTF-8//IGNORE", $info);
	 //        preg_match('|<li>(.*?)<\/li>|i',$content,$userAddress);
	        
	 //        $limit = explode('，',$addressLimit); 
  //           // message(json_encode( $limit),'','error');
	 //        $flag = 0;
	 //        for ($i=0; $i < count($limit); $i++) 
	 //        { 

	 //        	if(strpos($userAddress[1],$limit[$i]))
	 //        	{
	 //        		$flag=1;
	 //        		break;
	 //        	}
	 //        }
	 //        if($flag == 0)
	 //        {

	 //        	$errorMsg='您的位置不在本活动范围内哦~';
	 //        	include $this->template('fail');
	 //        	return;
	 //         // message('您的位置不在本活动范围内哦~','','error');
	 //        }

	 //    } 
	    
	    

	    //*************************************************************************************
	    $re_openid   =  $_GPC['openid'];
	    $nick_name   =  $this->module['config']['fenxiang']['nick_name'];
	    $send_name   =  $this->module['config']['fenxiang']['send_name'];
	    $wishing     =  $this->module['config']['fenxiang']['wishing'];
	    $remark      =  $this->module['config']['fenxiang']['remark'];
	    $act_name    =  $this->module['config']['fenxiang']['act_name'];
	    $maxCount    =  $this->module['config']['fenxiang']['maxCount'];
	    
	    $rand_list   =  $this->module['config']['fenxiang']['rand_list'];
	    $money_list  =  $this->module['config']['fenxiang']['money_list'];
	    $small       =  $this->module['config']['fenxiang']['small'];
	    $money_list = explode('，',$money_list); 
	    $rand_list = explode('，',$rand_list); 
	    
	    $maxRedCount = $this ->module['config']['fenxiang']['maxRedCount'];


	    if(count($rand_list)==0||count($rand_list)!=count($money_list))
	    {
	       	//金额数和权值个数对不上

	    	$errorMsg='金额数和权值数不一致或空';
	    	
	    	echo  $errorMsg;
	    	return ;
	    }
			//获得金额小数部分
	    $smallNum = rand(0,$small);
	    $all_quan = 0;
	       //计算随机金额
	    for ($i=0; $i <count($rand_list) ; $i++) 
	    { 
	    	$all_quan = $all_quan + $rand_list[$i];
	    }
	    if($all_quan==0)
	    {
	    	$errorMsg='总权值是0';
	    	
	    	echo  $errorMsg;
	    	return ;
	    }
	    $seed = rand(1,$all_quan);
	    for ($i=0; $i <count($rand_list) ; $i++) 
	    { 
	    	if($seed > $rand_list[$i] )
	    	{
	    		$seed = $seed - $rand_list[$i];
	    	}
	    	else
	    	{
	    		break;
	    	}
	    }

	    $total_amount =  $money_list[$i] + $smallNum;

	    
	    if($maxRedCount <= 0)
	    {
	    	//红包已领完
	    	if(!empty($this->module['config']['fenxiang']['sendAllUrl']))
	    	{
	    		header("Location: ".$this->module['config']['fenxiang']['sendAllUrl']); 
	    		exit ;
	    	}
	    	if(!empty($this->module['config']['fenxiang']['sendAllMsg']))
	    	{
	    		$errorMsg = $this->module['config']['fenxiang']['sendAllMsg'];
	    	}
	    	else 
	    	{
	    		$errorMsg='红包已领完';
	    	}
	    	
	    	echo  $errorMsg;
	    	return ;

	         // return $this->respText('红包已领完~');
	    }
        //先减一
	    $this ->module['config']['fenxiang']['maxRedCount'] = $this ->module['config']['fenxiang']['maxRedCount']-1;
	    $this->saveSettings($this->module['config']);
	    $sql = 'SELECT redPackCount,lastTime FROM ' . tablename('pzh_packet2') . ' WHERE `uniacid` = :uniacid and `type` = :type and `remark` = :trueopenid';
	    $params = array(':uniacid' => $_W['uniacid'],':type' => 'fenxiang' , ':trueopenid' => $_W['openid']);
	    $account = pdo_fetch($sql, $params);
	    if(!$account)
	    {
	        //如果查询不到该用户
	    	$sql = 'INSERT INTO'.tablename('pzh_packet2') .' (`uniacid`,`openid`,`redPackCount`,`lastTime`,`type`,`remark`) values ('.
	    		strval($_W['uniacid']).',\''.$re_openid.'\',0,'.strval($_W['timestamp']).',\'fenxiang\' ,\''.$_W['openid'].'\')'; 
	$result = pdo_query($sql);
	         // return $this->respText($sql);
        }
else
{
	        //曾经拿过红包
	if($_W['timestamp']-$account['lastTime']<=$this->module['config']['fenxiang']['time'])
	{
		  //失败加一
		$this ->module['config']['fenxiang']['maxRedCount'] = $this ->module['config']['fenxiang']['maxRedCount']+1;
		$this->saveSettings($this->module['config']);
	          // return $this->respText('您刚领取过红包哦~');
		if(!empty($this->module['config']['fenxiang']['havegetUrl']))
		{
			header("Location: ".$this->module['config']['fenxiang']['havegetUrl']); 
			exit ;
		}
		if(!empty($this->module['config']['fenxiang']['havegetMsg']))
		{
			$errorMsg = $this->module['config']['fenxiang']['havegetMsg'];
		}
		else 
		{
			$errorMsg='您刚领取过红包哦';
		}
		
		
		echo  $errorMsg;
	    	return ;

	}
	else if($account['redPackCount']>=$maxCount)
	{
	          //红包个数超过设定值
		 //失败加一
		$this ->module['config']['fenxiang']['maxRedCount'] = $this ->module['config']['fenxiang']['maxRedCount']+1;
		$this->saveSettings($this->module['config']);
	          // return $this->respText('您的红包已领完~');
		        //用户红包个数超过设定值
		if(!empty($this->module['config']['fenxiang']['limitUrl']))
		{
			header("Location: ".$this->module['config']['fenxiang']['limitUrl']); 
			exit ;
		}
		if(!empty($this->module['config']['fenxiang']['limitMsg']))
		{
			$errorMsg = $this->module['config']['fenxiang']['limitMsg'];
		}
		else 
		{
			$errorMsg='您的红包已领完';
		}
	
		echo  $errorMsg;
	    	return ;

	}


}  


$result = $this->pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$db=null);

if($result->result_code == 'FAIL' || $result ==  'fail')
{


   //领取失败
	 //失败加一
	$this ->module['config']['fenxiang']['maxRedCount'] = $this ->module['config']['fenxiang']['maxRedCount']+1;
	$this->saveSettings($this->module['config']);
	if(!empty($this->module['config']['fenxiang']['errorUrl']))
	{
		header("Location: ".$this->module['config']['fenxiang']['errorUrl']); 
		exit ;
	}
	if(!empty($this->module['config']['fenxiang']['errorMsg']))
	{
		$errorMsg = $this->module['config']['fenxiang']['errorMsg'];
	}
	else 
	{
		$errorMsg=$result->err_code_des;
	}
	
	echo  $errorMsg;
	    	return ;
}
else
{
	

	
	$sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = ' .strval($account['redPackCount']+1) . 
	' ,`lastTime`= ' . strval($_W['timestamp']). ' WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \'fenxiang\' and `remark` = \''.$_W['openid'].'\'  ';

	$result = pdo_query($sql);
      //随机红包记录数据
	$time = date('Y-m-d H:i:s',time());
	$sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`,`remark`) values ('.
		strval($_W['uniacid']).',\''.$re_openid.'\','.strval($total_amount/100.0).',\''.$time.'\',\'fenxiang\',\'success\',\''.$_W['openid'].'\')'; 
	pdo_query($sql);
           //发送成功  
	if(!empty($this->module['config']['fenxiang']['successUrl']))
	{
		header("Location: ".$this->module['config']['fenxiang']['successUrl']); 
		exit ;
	}
	if(!empty($this->module['config']['fenxiang']['successMsg']))
	{
		$successMsg = $this->module['config']['fenxiang']['successMsg'];
	}
	else 
	{
		$successMsg = '恭喜你获得一个红包~请到服务通知或者聊天窗查看哦';
	}

	
	echo  $successMsg;
	    	return ;

}
	      // return $this->respText('红包还没准备好哦');
$errorMsg='红包还没准备好哦~';
echo $errorMsg;
return ;

	//************************************************************************************************************************************
}
}

public function doMobileXoauth() 

{

	global $_W,$_GPC;

	if ($_GPC['code']=="authdeny" || empty($_GPC['code']))

	{

		exit("授权失败");

	}



	load()->func('communication');





	$appid=$this->module['config']['appid'];

	$secret=$this->module['config']['secret'];

	$state = $_GPC['state'];

	$code = $_GPC['code'];

	$oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";

	$content = ihttp_get($oauth2_code);

	$token = @json_decode($content['content'], true);

	if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) 

	{

		echo '<h1>获取微信公众号授权'.$code.'失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'].'<h1>';

		exit;

	}

	$from_user = $token['openid'];
	$weid = $_W['uniacid'];
	setcookie('pzh_openid'.$weid,$from_user, time()+3600*(24*5));


	  // $access_token =  WeAccount::token();

	  // $oauth2_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";

	  // $content = ihttp_get($oauth2_url);

	  // $info = @json_decode($content['content'], true);



	  // if(empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname']) || empty($info['headimgurl']) ) 

	  // {

	  // 	print_r($info);

	  //   echo '<h1>11获取微信公众号授权失败[无法取得info], 请稍后重试！<h1>';

	  //   exit;

	  // }

	$url=$_COOKIE["xoauthURL"];   

	  // $this->setCookieUserInfo($info);

	header("location:$url");

	exit();

}
//粉丝编号查询入口
public function doMobileFensi()
{
	global $_GPC,$_W;
	$weid = $_W['uniacid'];
	$this->init();
	if(empty($_COOKIE['pzh_openid'.$weid]))
	{
		$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=xoauth";

		$xoauthURL = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_money2&do=fensi";
		setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));

		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->module['config']['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
		header("location:$oauth2_code");
	}
	else
	{

		$successMsg = '您的编号为：'.$_COOKIE['pzh_openid'.$weid].'长按复制';
		// $sql = 'CREATE TABLE '.$tableName.' (
	 //    			`uniacid` int(10)  NOT NULL,
	 //    			`acid` varchar(35) NOT NULL,
	 //    			`trueopenid` varchar(35) ,
	 //    			`aouthopenid` varchar(35) ,
	 //    			`time` varchar(50) ,
	 //    			`username` varchar(50),
	 //    			`remark`   varchar(50)
	 //    			) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

	// $time = date('Y-m-d h:i:sa',time());

				// $sql = 'INSERT INTO'.tablename('pzh_fansrecord') .' (`uniacid`,`acid`,`trueopenid`,`time`,`aouthopenid`,`username`) values ('.
				// 	strval($_W['uniacid']).',\''.$_W['acid'].'\','.$_W['openid'].',\''.$time.'\',\''.$_COOKIE['pzh_openid'.$weid]).'\',\'success\')'; 
	   //          pdo_query($sql);
		include $this->template('success');
		return;
	}


} 
public function judgeFollow()
{
	global $_W,$_GPC;
    // message(json_encode($_W['fans']),'','');
	if($_W['fans']['follow']=='1')
	{
		return true;
	}
	else return false;

}




}