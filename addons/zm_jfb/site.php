<?php
/**
 * 线下积分宝模块微站定义
 *
 * @author wenjing
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');ini_set('date.timezone','Asia/Shanghai');
define('OD_ROOT', IA_ROOT . '/addons/zm_jfb');
include IA_ROOT.'/framework/library/phpexcel/PHPExcel.php';
include IA_ROOT.'/framework/library/qrcode/phpqrcode.php';
	

class Zm_jfbModuleSite extends WeModuleSite {
	public $weid;
		
	public $imgs;
	
	public $acid;
	
	public $scene_id;
	
	public $ticketstr;
	
	public $urlstr;
	
	public $mdsyjifen;
	
	public $Showyue;
	
	public $yueimgs;
	
	public $yueStr;
	
	
	function __construct(){
	
		global $_W;
	
		$this->weid = $_W['uniacid'];
	}
	
	public function Get_openid_byindex($appid,$appsecret){
		global $_W,$_GPC;
	
		if(empty($appid)){
			message('请到管理后台设置完整的 AppID 和AppSecret !',"error");
			exit();
		}
	
		if(empty($_COOKIE['useropenid_zm_jfb'.$_GPC['i']])){
				
			if (!isset($_GET['code'])){
				$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
	
				$urlObj["appid"] = $appid;
				$urlObj["redirect_uri"] = $baseUrl;
				$urlObj["response_type"] = "code";
				$urlObj["scope"] = "snsapi_userinfo";
				$urlObj["state"] = "STATE"."#wechat_redirect";
	
				$bizString = $this->ToUrlParams($urlObj);
	
				$url =  "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
				Header("Location: $url");
				exit();
			} else {
				$urlObj["appid"] = $appid;
				$urlObj["secret"] = $appsecret;
				$urlObj["code"] = $_GET['code'];
				$urlObj["grant_type"] = "authorization_code";
					
				$biz1String = $this->ToUrlParams($urlObj);
				$url =  "https://api.weixin.qq.com/sns/oauth2/access_token?".$biz1String;
				$token_ary = $this->request_get($url);
				$token_ary = json_decode($token_ary,true);
	
	
				$userurl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token_ary['access_token'].'&openid='.$token_ary['openid'].'&lang=zh_CN';
	
	
				$usersinfo = $this->request_get($userurl);
				$user_obj = json_decode($usersinfo,true);
	
				
				setcookie("useropenid_zm_jfb".$_GPC['i'], $user_obj['openid'], time()+3600*24*30);
				return $user_obj['openid'];
			}
		}else{
			return $_COOKIE['useropenid_zm_jfb'.$_GPC['i']];
		}
	}		
	public function doMobileIndex(){
		global $_W,$_GPC;
	
		
		$sql = "SELECT title,footerimg,hssupper FROM " . tablename('xjfb_setting') . " WHERE weid = :weid";
		$setting = pdo_fetch($sql,array(':weid'=>$_GPC['i']));
		
		$openid = $this->Get_openid_byindex($_W['account']['key'],$_W['account']['secret']);
		if(empty($openid))
		  $openid = $this->Getopenid($_W['account']['key'],$_W['account']['secret']);
		
		
		if(empty($_GPC['id'])){
			$dysql1 = "SELECT y.id as yid,m.id as mid,y.weixin FROM " . tablename('xjfb_yuangong') . " as y left join " . tablename('xjfb_mendian') . " as m on m.id = y.mendian WHERE y.weid = :weid ";
			$dylist1 = pdo_fetchall($dysql1,array(':weid'=>$_GPC['i']));
							
			foreach ($dylist1 as $key =>$value){
				if($value['weixin'] == $openid){
					$yid = $value['yid'];
					$mid = $value['mid'];
				}
			}	
				
			
			if(empty($mid)||empty($yid)){
				message('请联系商户绑定店员身份！',$this->createMobileUrl("index"),'error');
				exit();
			}
		
			$url = "http://".$_SERVER['HTTP_HOST']."/app/index.php?i=".$_GPC['i']."&id=".$yid."&mid=".$mid."&c=entry&do=index&m=".$this->module['name'];
		
			Header("Location: $url");
		
		}		
		$dysql = "SELECT y.weixin,y.addyue,y.hsjifen,y.hsyue FROM " . tablename('xjfb_yuangong') . " as y left join " . tablename('xjfb_mendian') . " as m on m.id = y.mendian WHERE y.weid = :weid and y.id = :id";
		$dylist = pdo_fetch($dysql,array(':weid'=>$_GPC['i'],':id'=>$_GPC['id']));
		
		
		if($dylist['weixin'] != $openid){
		    
			message('您不是店员！',$this->createMobileUrl("index"),'error');
			exit();
		}
		

	
		$imgs = "";
		if(!empty($_GPC['show'])|| !empty($_GPC['type'])){
			$imgs = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$_GPC['ticket'];
		}
			
	
	
		/*
		$jflist = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype = 0 and jftype = 0 and dianyuan = :dianyuan",array(":weid"=>$_GPC['i'],":dianyuan"=>$_GPC['id']));
				
		$yelist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0  and codetype = 1 and jftype = 0 and dianyuan = :dianyuan",array(":weid"=>$_GPC['i'],":dianyuan"=>$_GPC['id']));
				
		if(!empty($jflist)){
			$jifencount = $jflist;
		}
		else {
			$jifencount = 0;
		}		
		if(!empty($yelist)){
			$yuecount = $yelist;
		}
		else {
			$yuecount = 0;
		}
	
		$syjifen = $this->Getsyjifen($_GPC['mid']);		
		$syyue = $this->Getsyyue($_GPC['mid']);
		*/
	
		if (checksubmit('submit')) {
							
		    if($_GPC['buttype'] == 0){
				if(!empty($_GPC['jifen'])){
					if(is_numeric($_GPC['jifen'])){

					    
					    if($_GPC['jftype']!=1){
							$dqjifen = $this->mendiannumber($_GPC['mid'],$_GPC['jifen']);
			
							
							if($dqjifen == 1){
								message('输入的积分超过限额或该门店积分已经达到限制！',$this->createMobileUrl("index"),"error");
								exit();
							}elseif($dqjifen == 3){
							    message('请联系商家充值！',$this->createMobileUrl("index"),"error");
							    exit();
							}						
					    }else{
					        if($dylist['hsjifen'] == 0){
					            message('请联系商家开启积分回收！',$this->createMobileUrl("index"),"error");
					            exit();
					        }
					        if($setting['hssupper']<$_GPC['jifen']){
					            message('积分回收达到上限！',$this->createMobileUrl("index"),"error");
					            exit();
					        }
					    }												
							
						
						$max = 50000;
							
						
						load()->model('account');
						$acid = pdo_fetchcolumn('select acid from '.tablename('account')." where uniacid={$_GPC['i']}");
						$max = pdo_fetchcolumn('select qrcid from '.tablename("qrcode")." where acid = {$acid} and model=1 and qrcid > 50000 AND qrcid < 10000000 order by qrcid desc limit 1");
						if (empty($max)) $max = 50000;
						
						$uniacccount = WeAccount::create($acid);
						$barcode['action_name'] = 'QR_SCENE';
						$barcode['expire_seconds'] = 30*24*3600;
						$time = $barcode['expire_seconds'];
						$max = intval($max) + 1;
						$barcode['action_info']['scene']['scene_id'] = $max;
						$result = $uniacccount->barCodeCreateDisposable($barcode);
						if(empty($result['url']))
							die('生成二维码失败!原因:'.json_encode($result));
							
							
						$imgs = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$result['ticket'];
						$scene_id = $barcode['action_info']['scene']['scene_id'];
						$ticketstr = $result['ticket'];
						$urlstr = $result['url'];
		
						setcookie("acid", $acid, time()+3600*24*30);
						setcookie("scene_id", $scene_id, time()+3600*24*30);
						setcookie("ticketstr", $ticketstr, time()+3600*24*30);
						setcookie("urlstr", $urlstr, time()+3600*24*30);
		
						$this->createerweima($_GPC['jifen'],$_GPC['jftype'],$_GPC['content'],$acid,$scene_id,$ticketstr,$urlstr);
							
						$url = "http://".$_SERVER['HTTP_HOST']."/app/index.php?i=".$_GPC['i']."&show=true&jifen=".$_GPC['jifen']."&jftype=".$_GPC['jftype']."&ticket=".$ticketstr."&id=".$_GPC['id']."&mid=".$_GPC['mid']."&c=entry&do=index&m=".$this->module['name'];
			
						Header("Location: $url");
							
					}else {
						message('请输入数字类型的积分！',$this->createMobileUrl("index"),'error');
					}
				}else{
					message('请输入积分,才能生成二维码！',$this->createMobileUrl("index"),'error');
				}			
		    }else{				
		        if($dylist['addyue'] == 0){					
		            message('当前店员没有开通充值权限',$this->createMobileUrl("index"),'error');					
		            exit();				
		        }
								
				if(!empty($_GPC['jifen'])){
					if(is_numeric($_GPC['jifen'])){
										
					    if($_GPC['jftype']!=1){
							$dqjifen = $this->mendiannumber1($_GPC['mid'],$_GPC['jifen']);
							
							if($dqjifen == 1){
								message('输入的余额超过限额或该门店余额已经达到限制！',$this->createMobileUrl("index"),'error');
								exit();
							}elseif($dqjifen == 3){
							    message('请联系商家充值！',$this->createMobileUrl("index"),'error');
							    exit();
							}					
					    }else{
					        if($dylist['hsyue'] == 0){
					            message('请联系商家开启余额回收！',$this->createMobileUrl("index"),'error');
					            exit();
					        }
					        if($setting['hssupper']<$_GPC['jifen']){
					            message('积分回收达到上限！',$this->createMobileUrl("index"),'error');
					            exit();
					        }
					    }	
										
						$max = 100000;
				
						
						load()->model('account');
						$acid = pdo_fetchcolumn('select acid from '.tablename('account')." where uniacid={$_GPC['i']}");
						$max = pdo_fetchcolumn('select qrcid from '.tablename("qrcode")." where acid = {$acid} and model=1 and qrcid > 100000 AND qrcid < 100000000 order by qrcid desc limit 1");
						if (empty($max)) $max = 100000;
						
						$uniacccount = WeAccount::create($acid);
						$barcode['action_name'] = 'QR_SCENE';
						$barcode['expire_seconds'] = 30*24*3600;
						$time = $barcode['expire_seconds'];
						$max = intval($max) + 1;
						$barcode['action_info']['scene']['scene_id'] = $max;
						$result = $uniacccount->barCodeCreateDisposable($barcode);
						if(empty($result['url']))
							die('生成二维码失败!原因:'.json_encode($result));

						
				
						$imgs = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$result['ticket'];
						$scene_id = $barcode['action_info']['scene']['scene_id'];
						$ticketstr = $result['ticket'];
						$urlstr = $result['url'];
				
						setcookie("acid_1", $acid, time()+3600*24*30);
						setcookie("scene_id_1", $scene_id, time()+3600*24*30);
						setcookie("ticketstr_1", $ticketstr, time()+3600*24*30);
						setcookie("urlstr_1", $urlstr, time()+3600*24*30);
										
						$this->createerweima1($_GPC['jifen'],$_GPC['jftype'],$acid,$scene_id,$ticketstr,$urlstr);
						$url = "http://".$_SERVER['HTTP_HOST']."/app/index.php?i=".$_GPC['i']."&jifen=".$_GPC['jifen']."&show=true&jftype={$_GPC['jftype']}&ticket=".$ticketstr."&id=".$_GPC['id']."&mid=".$_GPC['mid']."&c=entry&type=1&do=index&m=".$this->module['name'];
				
							
						Header("Location: $url");
					}else {
						message('请输入数字类型的额度！',$this->createMobileUrl("index"),'error');
					}
				}else{
					message('请输入额度,才能生成二维码！',$this->createMobileUrl("index"),'error');
				}							}
		}
	
		
		include $this->template('index');
	}
	
	public function createcard($backimg,$headimg,$title,$subtitle){
        global $_W,$_GPC;
        
	   
	    $asstonek = $this->getToken($_W['account']['key'], $_W['account']['secret']); //$this->getAccessToken();
	    $url = "https://api.weixin.qq.com/card/create?access_token=".$asstonek;
	    $pjson ='{
           "card": {
               "card_type": "MEMBER_CARD",
               "member_card": {
	               "background_pic_url": "",
                   "base_info": {
                       "logo_url": "'.$headimg.'",
                       "brand_name": "'.$title.'",
                       "code_type": "CODE_TYPE_QRCODE",
                       "title": "'.$subtitle.'",
                       "sub_title": "'.$subtitle.'",
                       "color": "Color010",
                       "notice": "使用时向服务员出示此券",
                       "description": "不可与其他优惠同享",
                       "date_info": {
                           "type": "DATE_TYPE_PERMANENT"
                       },
                       "sku": {
                           "quantity": 50000000
                       },
                       "get_limit": 1,
                       "use_custom_code": true,
                       "can_give_friend": true,
                       "location_id_list": [
                           123,
                           12321,
                           345345
                       ],
                       "custom_url_name": "查看详情",
                       "custom_url": "http://'.$_SERVER['HTTP_HOST'].'/app/index.php?i='.$_GPC['i'].'&c=entry&do=uindex&m='.$this->module['name'].'",
                       "custom_url_sub_title": "会员详情",
                       "need_push_on_view": true
                   },
                   "supply_bonus": true,
                   "supply_balance": true,
                   "prerogative": "特权说明",
                   "auto_activate": false,
                   "custom_field1": {
                       "name_type": "FIELD_NAME_TYPE_LEVEL",
                       "url": "http://'.$_SERVER['HTTP_HOST'].'/app/index.php?i='.$_GPC['i'].'&c=entry&do=uindex&m='.$this->module['name'].'"
                   },
                   "activate_url": "http://'.$_SERVER['HTTP_HOST'].'/app/index.php?i='.$_GPC['i'].'&c=entry&auto=1&do=uindex&m='.$this->module['name'].'",
                   
                   "bonus_rule": {
                       "cost_money_unit": 100,
                       "increase_bonus": 1,
                       "max_increase_bonus": 200,
                       "init_increase_bonus": 0
                   },
                   "discount": 0
               }
           }
        }';
	     
	    
	    $re3 = ihttp_post($url, $pjson);
	    //$re3 = $this->request_post($url,$pjson);
	    $re3arr = json_decode($re3['content'],true);
	    
	    return  $re3arr['card_id'];
	    
	    //$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$asstonek."&type=wx_card";
	    //$re3 = $this->request_get($url);
	    //print_r($re3);
	    
	    //$ticket = $this->getJsApiTicket();
	     
	}
	public function activateCard($cardnumber,$codeid,$cardid,$bonus,$balance,$group){
	    global $_W;
	     
	     
	    $asstonek = $this->getToken($_W['account']['key'], $_W['account']['secret']); //$this->getAccessToken();
	    $url = "https://api.weixin.qq.com/card/membercard/activate?access_token=".$asstonek;
	     
	     $data = '{
                "init_bonus": '.$bonus.',
                "init_bonus_record":"积分余额同步",
                "init_balance": '.($balance*100).',
                "membership_number": "'.$cardnumber.'",
                "code": "'.$codeid.'",
                "card_id": "'.$cardid.'",
                "background_pic_url": "https://mmbiz.qlogo.cn/mmbiz/0?wx_fmt=jpeg",
                "init_custom_field_value1": "'.$group.'"
            }';
	     
	     
	    //$re3 = $this->request_post($url,$data);
	    $re3 = ihttp_post($url, $data);
	    $re3arr = json_decode($re3['content'],true);
	
	    return  $re3arr;
	}
	
	public function updateCard($cardnumber,$cardid,$bonus,$balance,$group){
	    global $_W;
	    
	    
	    $asstonek = $this->getToken($_W['account']['key'], $_W['account']['secret']); //$this->getAccessToken();
	    $url = "https://api.weixin.qq.com/card/membercard/updateuser?access_token=".$asstonek;
	    
	    $data = '{
            "code": "'.$cardnumber.'",
    	    "card_id": "'.$cardid.'",
    	    "background_pic_url": "https://mmbiz.qlogo.cn/mmbiz/0?wx_fmt=jpeg",
    	    "bonus": '.$bonus.',
    	    "add_bonus":0,
    	    "balance": '.($balance*100).',
    	    "add_balance":0,
    	    "custom_field_value1": "'.$group.'",
    	    "notify_optional": {
        	    "is_notify_bonus": true,
        	    "is_notify_balance": true,
        	    "is_notify_custom_field1":false
    	    }
	    }';
	    
	    
	    
	    //$re3 = $this->request_post($url,$data);
	    $re3 = ihttp_post($url, $data);
	    $re3arr = json_decode($re3['content'],true);
	     
	    return  $re3arr;
	}
	
    private function get_php_file($filename) {
	    return trim(substr(file_get_contents($filename), 15));
	}
	private function set_php_file($filename, $content) {
	    $fp = fopen($filename, "w");
	    fwrite($fp, "<?php exit();?>" . $content);
	    fclose($fp);
	}
	private function getAccessToken() {
	    global $_W;
	    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	    $data = json_decode($this->get_php_file("access_token.php"));
	    
	    if ($data->expire_time < time()) {
	        // 如果是企业号用以下URL获取access_token
	        // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
	        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$_W['account']['key']."&secret=".$_W['account']['secret'];
	        $res = json_decode($this->request_get($url));
	        $access_token = $res->access_token;
	        if ($access_token) {
	            $data->expire_time = time() + 7000;
	            $data->access_token = $access_token;
	            $this->set_php_file("access_token.php", json_encode($data));
	        }
	    } else {
	        $access_token = $data->access_token;
	    }
	    return $access_token;
	}
	private function getJsApiTicket() {
	    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	    $data = json_decode($this->get_php_file("jsapi_ticket.php"));
	    
	    if ($data->expire_time < time()) {
	        $accessToken = $this->getAccessToken();
	        // 如果是企业号用以下 URL 获取 ticket
	        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
	        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=wx_card&access_token=$accessToken";
	        $res = json_decode($this->request_get($url));
	        $ticket = $res->ticket;
	        if ($ticket) {
	            $data->expire_time = time() + 7000;
	            $data->jsapi_ticket = $ticket;
	            $this->set_php_file("jsapi_ticket.php", json_encode($data));
	        }
	    } else {
	        $ticket = $data->jsapi_ticket;
	    }
	
	    return $ticket;
	}
	function getRandChar($length){
	    $str = null;
	    $strPol = "0123456789";
	    $max = strlen($strPol)-1;
	
	    for($i=0;$i<$length;$i++){
	        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	    }
	
	    return $str;
	}
	function SendCardByCode($cardid,$code,$openid){
	    global $_W;
	    
	    $data = '{
            "action_name": "QR_CARD",
            "action_info": {
                "card": {
                "card_id": "'.$cardid.'", 
                "code": "'.$code.'",
                "openid": "'.$openid.'",
                "is_unique_code": false ,
                "outer_id" : 1
              }
             }
            }';
	    
	 
	    $asstonek = $this->getToken($_W['account']['key'], $_W['account']['secret']);
	    $url = "https://api.weixin.qq.com/card/qrcode/create?access_token=".$asstonek;
	    
	    
	    //$re3 = $this->request_post($url,$data);
	    $re3 = ihttp_post($url, $data);
	    $re3arr = json_decode($re3['content'],true);
	    
	    
	    return $re3arr;
	}
	public function doMobileUindex(){
	    global $_W,$_GPC;
	    
	    
	    $sql = "SELECT * FROM " . tablename('xjfb_setting') . " WHERE weid = :weid";
	    $setting = pdo_fetch($sql,array(':weid'=>$_GPC['i']));
	     
	    $openid = $this->Get_openid_byindex($_W['account']['key'],$_W['account']['secret']);
	    if(empty($openid))
	       $openid = $this->Getopenid($_W['account']['key'],$_W['account']['secret']);
	    
	   
	    //$topfans = pdo_fetch('select m.uid,m.nickname,m.realname,m.mobile,m.avatar,m.credit1,m.credit2,j.id,j.iflqhy from '.tablename('mc_mapping_fans').' as f left join '.tablename('mc_members').' as m on f.uid = m.uid left join '.tablename('xjfb_jifenjilu').' as j on m.uid = j.mcid where f.openid = :openid  limit 1',array(":openid" => $openid));
	    //if(!empty($topfans))
	    //$topfans = pdo_fetch("select m.uid,m.nickname,m.realname,m.mobile,m.avatar,m.credit1,m.credit2,j.* from ".tablename('mc_mapping_fans')." as f left join ".tablename('mc_members')." as m on f.uid = m.uid left join".tablename('xjfb_member')." as j on j.uid = m.uid where j.weid = ".$_GPC['i']." and f.openid = '".$openid."' limit 1");
	    $topfans = pdo_fetch("select m.uid,m.nickname,m.realname,m.mobile,m.avatar,m.credit1,m.credit2 from ".tablename('mc_mapping_fans')." as f left join ".tablename('mc_members')." as m on f.uid = m.uid where m.uniacid = ".$_GPC['i']." and f.openid = '".$openid."' limit 1");
	     
	    
	    $uid = $topfans['uid'];
	    if(!empty($topfans))
	       $xjfbmember = pdo_fetch("select * from ".tablename('xjfb_member')." where weid = ".$_GPC['i']." and uid = ".$topfans['uid']);
	    
	    $cardlist = pdo_fetch("select * from ".tablename('xjfb_cardlist')." where weid=".$_GPC['i']." limit 1");
	    //print_r($cardlist);
	    
	    if(empty($xjfbmember)){
	        $_member = array(
	            'weid' => $_GPC['i'],
	             
	            'uid' => empty($topfans)?0:$topfans['uid'],
	             
	            'credit1' => empty($topfans)?0:$topfans['credit1'],
	             
	            'credit2' => empty($topfans)?0:$topfans['credit2'],
	             
	            'iflqhy' => 0,
	             
	            'channel' => 1,
	             
	            'addtime' => time()
	        );
	         
	        pdo_insert('xjfb_member',$_member);
	    }
	    
	    $groups = pdo_fetchall("select g.*,i.topimg from ".tablename('mc_groups')." as g left join ".tablename('xjfb_group')." as i on g.groupid = i.groupid where uniacid = ".$_GPC['i']." order by g.credit desc");
	    
	    foreach ($groups as $row){
	        if($topfans['credit1'] >= $row['credit']){
	            $group_str = $row['title'];
	            $topimg = $row['topimg'];
	            break;
	        }
	    }
	    
	    $sql = "SELECT * FROM " . tablename('xjfb_czzs') . " WHERE weid = :weid";
	    $_list = pdo_fetchall($sql,array(':weid'=>$_GPC['i']));
	    $cztext = $setting['cztext'];
	    
	    $avatar = $topfans['avatar'];
	    $nickname = $topfans['nickname'];
	    
	    $jifen_num = $topfans['credit1'];
	    $yue_num = $topfans['credit2'];
	     
	    $realname = $topfans['realname'];
	    $telephone = $topfans['mobile'];
	    
	    $lqhy = $xjfbmember['iflqhy'];
	    
	    //if(empty($lqhy)){
	    //    $lqhy = $xmember['iflqhy'];
	    //}
	    
	    
	    if($_GPC['auto'] == 1){
	       
	        $ret = $this->activateCard($xjfbmember['codeid'],$xjfbmember['codeid'],$cardlist['cardid'],empty($topfans)?0:$topfans['credit1'],empty($topfans)?0:$topfans['credit2'],$group_str);
	        
	    }
	 

	    
	    if($_GPC['opp'] == "getcard"){
	        if(!empty($xjfbmember)){
	            if($xjfbmember['iflqkb'] == 1 && $xjfbmember['qrcodetime'] > strtotime(date("Y-m-d",strtotime("-1 year")))){
	                
	                 echo json_encode(array('url'=>$xjfbmember['qrcode'],'lq'=>$xjfbmember['iflqkb'])) ;
	                 exit();
	            }else{
	                
	                if(empty($cardlist['cardid'])){
	                    
	                    $acid = pdo_fetchcolumn("select default_acid from ".tablename('uni_account')." where uniacid = ".$_GPC['i']);
	                    $avatar = tomedia('headimg_'.$acid.'.jpg');
	                    
	                    $card = $this->createcard($topimg,$avatar,empty($cardlist['card_title'])?$setting['title']:$cardlist['card_title'],empty($cardlist['card_subtitle'])?$setting['title']:$cardlist['card_subtitle']);
	                   
	                    
	                    if(empty($cardlist)){
    	                    $carddata = array(
    	                    
    	                        'weid' => $_GPC['i'],
    	                    
    	                        'cardid' => $card,
    	                    
    	                        'addtime' => time()
    	                    );
    	                    pdo_insert('xjfb_cardlist',$carddata);
	                    }else{
	                        pdo_update('xjfb_cardlist',array('cardid'=>$card),array('id'=>$cardlist['id']));
	                    }
	                }else{
	                    $card = $cardlist['cardid'];
	                }
	                
    	                if(!empty($card)){
    	                    
    	                    $qrcodetime = time();
    	                    $codeid = time().$this->getRandChar(2);
    	                    $qrcode = $this->SendCardByCode($card,$codeid,$openid);
    	                    
    	                    //$ret = $this->activateCoard($codeid,$card,$topfans['credit1'],$topfans['credit2'],$group_str);
    	                    
    	                   
        	                if(!empty($qrcode['show_qrcode_url'])){
        	                    pdo_update('xjfb_member',array('iflqkb'=>1,'qrcode'=>$qrcode['show_qrcode_url'],'qrcodetime'=>$qrcodetime,'cardid'=>$card,'codeid'=>$codeid),array("uid"=>$topfans['uid']));
        	                    
        	                    echo json_encode(array('url'=>$qrcode['show_qrcode_url'],'lq'=>1)) ;
        	                    exit();
        	                }
    	                }else{
    	                    echo json_encode(array('url'=>"../addons/zm_jfb/befor.png",'lq'=>0)) ;
    	                    exit();
    	                }
	                }
	        }else{
	            
	            echo "../addons/zm_jfb/befor.png";
	            exit();
	        }
	        exit();
	    }elseif($_GPC['opp'] == "showhy"){
	        
	        if($setting['openhy'] == 1){
    	        if(!empty($lqhy)){
    	            $card = pdo_fetchcolumn("select count(*) from ".tablename('mc_card_members')." where uid = ".$topfans['uid']." and uniacid = ".$_GPC['i']);
    	            if($lqhy == 1){
    	                if($card > 0){
        	                echo json_encode(array("hy"=>true,"lq"=>$xjfbmember['iflqkb']));
        	                exit;
    	                }else{
    	                    echo json_encode(array("hy"=>false,"lq"=>$xjfbmember['iflqkb']));
    	                    exit;
    	                }
    	            }
    	            else{
    	                
    	                if($card > 0){
    	                    
    	                    mc_update($topfans['uid'],array('realname'=>$topfans['realname'],'mobile'=>$topfans['mobile']));
    	                    pdo_update('xjfb_jifenjilu',array('iflqhy'=>1),array('id'=>$xjfbmember['id']));
    	                    pdo_update('xjfb_member',array('iflqhy'=>1),array('uid'=>$topfans['uid']));
    	                    echo json_encode(array("hy"=>true,"lq"=>$xjfbmember['iflqkb']));
    	                    exit;
    	                }else{
    	                    echo json_encode(array("hy"=>false,"lq"=>0));
        	                exit;
    	                }
    	            }
    	             
    	        }else{
    	            echo json_encode(array("hy"=>false,"lq"=>$xjfbmember['iflqkb']));
    	            exit;
    	        }
	        }else {
	            echo json_encode(array("hy"=>true,"lq"=>$xjfbmember['iflqkb']));
	            exit;
	        }
	        
	        
	    }elseif($_GPC['opp'] == "vip"){
	        load()->model('mc');
	        $name = $_GPC['name'];
	        $phone = $_GPC['phone'];
	         
	        if(!empty($topfans)){
	            
	            if($setting["openpay"] == 1){
    	            require_once "WxPay/WxPay.JsApiPay.php";
    	             
    	            require_once "WxPay/WxPay.Exception.php";
    	             
    	            require_once "WxPay/WxPay.Config.php";
    	             
    	            require_once "WxPay/WxPay.Data.php";
    	             
    	            $tools = new JsApiPay();
    	             
    	            
    	            //②、统一下单
    	             
    	            $input = new WxPayUnifiedOrder();
    	             
    	            $input->SetBody("云会员充值");
    	             
    	            $input->SetAttach("云会员充值");
    	             
    	             
    	             
    	            $input->SetAppid($_W['account']['key']);//公众账号ID
    	            
    	             
    	            $input->SetMch_id($setting['mchid']);//商户号
    	             
    	            $input->SetOut_trade_no($setting['mchid'].date("YmdHis"));
    	             
    	            $input->SetTotal_fee($setting["paymoney"]==0?1:$setting["paymoney"]*100);
    	             
    	            $input->SetTime_start(date("YmdHis"));
    	             
    	            $input->SetTime_expire(date("YmdHis", time() + 600));
    	             
    	            $input->SetGoods_tag("云会员充值");
    	             
    	            $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
    	             
    	            $input->SetTrade_type("JSAPI");
    	             
    	            $input->SetOpenid($openid);
    	             
    	             
    	             
    	            $order = WxPayApi::unifiedOrder($input,$setting['mchid'],$setting['apikey']);
    	             
    	             
    	            $jsApiParameters = $tools->GetJsApiParameters($order,$setting['apikey']);
    	            
    	            echo  $jsApiParameters;
    	            exit();
    	             
	            }
	            
	            mc_update($topfans['uid'],array('realname'=>$name,'mobile'=>$phone));
	            pdo_update('xjfb_jifenjilu',array('iflqhy'=>1),array('id'=>$xjfbmember['id']));
	             
	            $card = pdo_fetchcolumn("select count(*) from ".tablename('mc_card_members')." where uid = ".$topfans['uid']." and uniacid = ".$_GPC['i']);
	            if($card <= 0){
    	            $card = array(
    	                'uniacid' => $_GPC['i'],
    	                 
    	                'uid' => $topfans['uid'],
    	                 
    	                'cardsn' => $phone,
    	                 
    	                'status' => 1,
    	                 
    	                'createtime' => time(),
    	                 
    	                'openid' => $openid,
    	                 
    	                'endtime' => time()
    	            );
    	             
    	            pdo_insert('mc_card_members',$card);
	            }
	            if(empty($xjfbmember)){
	                $_member = array(
	                    'weid' => $_GPC['i'],
	                
	                    'uid' => $topfans['uid'],
	                
	                    'credit1' => $topfans['credit1'],
	                
	                    'credit2' => $topfans['credit2'],
	                
	                    'iflqhy' => 1,
	                
	                    'channel' => 1,
	                
	                    'addtime' => time()
	                );
	                
	                pdo_insert('xjfb_member',$_member);
	            }else{
	                pdo_update('xjfb_member',array("iflqhy"=>1),array("uid"=>$topfans['uid']));
	            }
	            
	            
	            if($setting['gethy'] == 0){
	                
	                if($setting['xhjifen']!=0){
	                    if($setting['xhjifen'] > $topfans['credit1']){
	                        echo "credit1";
	                        exit();
	                    }
	                    mc_credit_update($topfans['uid'],'credit1','-'.$setting['xhjifen'],array($topfans['uid'],"云会员领取会员卡减少积分".$setting['xhjifen']));
	                    $jifen_num = $topfans['credit1']-$setting['xhjifen'];
	                }
	                if($setting['xhyue']!=0){
	                    if($setting['xhyue'] > $topfans['credit2']){
	                        echo "credit2";
	                        exit();
	                    }
	                    mc_credit_update($topfans['uid'],'credit2','-'.$setting['xhyue'],array($topfans['uid'],"云会员领取会员卡减少余额".$setting['xhyue']));
	                    $yue_num = $topfans['credit2']-$setting['xhyue'];
	                }
	                
	            }else{
	                
	                if($setting['xhjifen']!=0){
	                    
	                    mc_credit_update($topfans['uid'],'credit1',$setting['xhjifen'],array($topfans['uid'],"云会员领取会员卡增加积分".$setting['xhjifen']));
	                    $jifen_num = $topfans['credit1']+$setting['xhjifen'];
	                }
	                if($setting['xhyue']!=0){
	                    
	                    mc_credit_update($topfans['uid'],'credit2',$setting['xhyue'],array($topfans['uid'],"云会员领取会员卡增加余额".$setting['xhyue']));
	                    $yue_num = $topfans['credit2']+$setting['xhyue'];
	                }
	                
	            }
	            
	           if($setting["openpay"] == 1){
	               
	               echo  $jsApiParameters;
	           }
	           else 
	               echo  json_encode(array("ret"=>true));
	        }else{
	            echo json_encode(array("ret"=>false));
	        }
	        
	        exit;
	    }elseif($_GPC['opp'] == "vippay"){
	        mc_update($topfans['uid'],array('realname'=>$name,'telephone'=>$phone));
	        pdo_update('xjfb_jifenjilu',array('iflqhy'=>1),array('id'=>$topfans['id']));
	        
	        $card = pdo_fetchcolumn("select count(*) from ".tablename('mc_card_members')." where uid = ".$topfans['uid']." and uniacid = ".$_GPC['i']);
	        if($card <= 0){
	            $card = array(
	                'uniacid' => $_GPC['i'],
	        
	                'uid' => $topfans['uid'],
	        
	                'cardsn' => $phone,
	        
	                'status' => 1,
	        
	                'createtime' => time(),
	        
	                'openid' => $openid,
	        
	                'endtime' => time()
	            );
	        
	            pdo_insert('mc_card_members',$card);
	        }
	        if(empty($xjfbmember)){
	            $_member = array(
	                'weid' => $_GPC['i'],
	                 
	                'uid' => $topfans['uid'],
	                 
	                'credit1' => $topfans['credit1'],
	                 
	                'credit2' => $topfans['credit2'],
	                 
	                'iflqhy' => 1,
	                 
	                'channel' => 1,
	                 
	                'addtime' => time()
	            );
	             
	            pdo_insert('xjfb_member',$_member);
	        }else{
	            pdo_update('xjfb_member',array("iflqhy"=>1),array("uid"=>$topfans['uid']));
	        }
	         
	         
	        if($setting['gethy'] == 0){
	             
	            if($setting['xhjifen']!=0){
	                if($setting['xhjifen'] > $topfans['credit1']){
	                    echo "credit1";
	                    exit();
	                }
	                mc_credit_update($topfans['uid'],'credit1','-'.$setting['xhjifen'],array($topfans['uid'],"云会员领取会员卡减少积分".$setting['xhjifen']));
	                $jifen_num = $topfans['credit1']-$setting['xhjifen'];
	            }
	            if($setting['xhyue']!=0){
	                if($setting['xhyue'] > $topfans['credit2']){
	                    echo "credit2";
	                    exit();
	                }
	                mc_credit_update($topfans['uid'],'credit2','-'.$setting['xhyue'],array($topfans['uid'],"云会员领取会员卡减少余额".$setting['xhyue']));
	                $yue_num = $topfans['credit2']-$setting['xhyue'];
	            }
	             
	        }else{
	             
	            if($setting['xhjifen']!=0){
	                 
	                mc_credit_update($topfans['uid'],'credit1',$setting['xhjifen'],array($topfans['uid'],"云会员领取会员卡增加积分".$setting['xhjifen']));
	                $jifen_num = $topfans['credit1']+$setting['xhjifen'];
	            }
	            if($setting['xhyue']!=0){
	                 
	                mc_credit_update($topfans['uid'],'credit2',$setting['xhyue'],array($topfans['uid'],"云会员领取会员卡增加余额".$setting['xhyue']));
	                $yue_num = $topfans['credit2']+$setting['xhyue'];
	            }
	             
	        }
	        
	        echo json_encode(array("ret"=>true));
	        exit();
	    }elseif($_GPC['opp'] == "pay"){
	        /*
	        require_once "WxPay/WxPay.JsApiPay.php";
	        
	        require_once "WxPay/WxPay.Exception.php";
	        
	        require_once "WxPay/WxPay.Config.php";
	        
	        require_once "WxPay/WxPay.Data.php";
	        
	        $tools = new JsApiPay();
	        
	       
	        //②、统一下单
	        
	        $input = new WxPayUnifiedOrder();
	        
	        $input->SetBody("云会员充值");
	        
	        $input->SetAttach("云会员充值");
	        
	        
	        
	        $input->SetAppid($_W['account']['key']);//公众账号ID
	        
	        $input->SetMch_id($setting['mchid']);//商户号
	        
	        $input->SetOut_trade_no($setting['mchid'].date("YmdHis"));
	        
	        $input->SetTotal_fee($_GPC['price']*100);
	        
	        $input->SetTime_start(date("YmdHis"));
	        
	        $input->SetTime_expire(date("YmdHis", time() + 600));
	        
	        $input->SetGoods_tag("云会员充值");
	        
	        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
	        
	        $input->SetTrade_type("JSAPI");
	        
	        $input->SetOpenid($openid);
	        
	        
	        
	        $order = WxPayApi::unifiedOrder($input,$setting['mchid'],$setting['apikey']);
	        
	        
	        $jsApiParameters = $tools->GetJsApiParameters($order,$setting['apikey']);
	        
	        echo  $jsApiParameters;
	        exit;
	        */
	        
	        
	    }elseif($_GPC['opp'] == "insert"){
	        /*
	        $price = $_GPC['price'];
	        $zsprice = $_GPC['zsprice'];
	        $status = $_GPC['ret'];
	        
	        $_data = array(
	            'weid' => $_GPC['i'],
	            
	            'czmoney' => $price,
	            
	            'zsmoney' => $zsprice,
	            
	            'uid' => $topfans['uid'],
	            
	            'orderid' => '',
	            
	            'status' => $status,
	            
	            'addtime' => time()
	        );
	        
	        pdo_insert("xjfb_hycz",$_data);
	        
	        mc_credit_update($topfans['uid'],'credit2',($price+$zsprice),array($topfans['uid'],"云会员充值".$price."送".$zsprice));
	        
	        exit();
	        */
	    }elseif($_GPC['opp'] == "card"){
	        $name = $_GPC['name'];
	        $phone = $_GPC['phone'];
	        
	        	        
	        $entity = pdo_fetch("select * from ".tablename('xjfb_entity')." where weid = ".$_GPC['i']." and name='".$name."' and phone='".$phone."'");
	        
	        
	        
	        if(empty($entity)){
	            $data = array(
	                'weid' => $_GPC['i'],
	             
    	            'name' => $name,
    	             
    	            'phone' => $phone,
    	             
    	            'cardmember' => $phone,
    	             
    	            'credit1' => empty($xjfbmember)?$topfans['credit1']:$xjfbmember['credit1'],
    	             
    	            'credit2' => empty($xjfbmember)?$topfans['credit2']:$xjfbmember['credit2'],
	                
	                'group' => $group_str,
	                
	                'banding' => 1,
	                
	                'uid' => $topfans['uid'],
    	             
    	            'addtime' => time()
	           );
	         
	           pdo_insert('xjfb_entity',$data);
	        }else{
	            pdo_update('xjfb_entity',array('name'=>$name,'phone'=>$phone,'banding'=>1,'uid'=>$topfans['uid']),array('id'=>$entity['id']));
	        }
	        if(empty($xjfbmember)){
	            
	                $data = array(
	                    'weid' => $_GPC['i'],
	                     
	                    'uid' => $topfans['uid'],
	                     
	                    'credit1' => $topfans['credit1'],
	                     
	                    'credit2' => $topfans['credit2'],
	                     
	                    'iflqhy' => 1,
	                     
	                    'channel' => 1,
	                     
	                    'addtime' => time()
	                );
	                 
	                pdo_insert('xjfb_member',$_member);
	            
	        }else{
	            pdo_update('xjfb_member',array("iflqhy"=>1),array("uid"=>$topfans['uid']));
	        }
	        mc_update($topfans['uid'],array('realname'=>$name,'mobile'=>$phone));
	         
	        $card = pdo_fetchcolumn("select count(*) from ".tablename('mc_card_members')." where uid = ".$topfans['uid']." and uniacid = ".$_GPC['i']);
	        if($card <= 0){
	            $card = array(
	                'uniacid' => $_GPC['i'],
	                 
	                'uid' => $topfans['uid'],
	                 
	                'cardsn' => $phone,
	                 
	                'status' => 1,
	                 
	                'createtime' => time(),
	                 
	                'openid' => $openid,
	                 
	                'endtime' => time()
	            );
	             
	            pdo_insert('mc_card_members',$card);
	        }
	        
	            echo json_encode(array("ret"=>true));
	            exit;
	    }
	    
	     
	    include $this->template('uindex');
	}
	
	public function doMobileChongzhi(){
	    global $_W,$_GPC;
	    
	    $setting = pdo_fetch("select title,footerimg,footerCopyright,cztext,czhead from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	    $sql = "SELECT * FROM " . tablename('xjfb_czzs') . " WHERE weid = :weid";
	    $_list = pdo_fetchall($sql,array(':weid'=>$_GPC['i']));
	    
	    
	    
	    include $this->template('chongzhi');
	}
	public function doMobileCzok(){
	    global $_W,$_GPC;
	    
	    $czmoney = $_GPC['cz'];
	    $zsmoney = $_GPC['zs'];
	    
	    
	    $params = array(
	         
	        'tid' => TIMESTAMP,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
	         
	        'ordersn' => TIMESTAMP,  //收银台中显示的订单号
	         
	        'title' => "云会员自主充值",          //收银台中显示的标题
	         
	        'fee' => $czmoney,      //收银台中显示需要支付的金额,只能大于 0
	         
	        'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
	         
	    );
	    $this->payMent($zsmoney,3,$params);
	    
	}
	
	
	public function doMobileCindex(){
	    global $_W,$_GPC;
	    
	    
	    
	    $openid = $_W['openid'];
	    
	    $mdlist = pdo_fetch("select id,name,weixin,number,number1 from ".tablename('xjfb_mendian')." where weid = ".$_GPC['i']." and weixin = '".$openid."'");
	    if(empty($mdlist)){
	         message("你没有被绑定店员或门店管理员！",$this->createMobileUrl('uindex'),'error');
	         exit();
	    }
	     
	     
	    $setting = pdo_fetch("select title,footerimg,footerCopyright from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	    
	    if(!empty($mdlist)){
	    $jflist = pdo_fetchcolumn("SELECT SUM(jifen) FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and mendian = :mendian and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype=0  and jftype = 0",array(':weid'=>$_GPC['i'],':mendian'=>$mdlist['id']));
	      
	    $yelist = pdo_fetchcolumn("SELECT SUM(yuenum) FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and mendian = :mendian and DATEDIFF(from_unixtime(addtime1,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype=1  and jftype = 0",array(':weid'=>$_GPC['i'],':mendian'=>$mdlist['id']));
	     
	    $jflist1 = pdo_fetchcolumn("SELECT SUM(jifen) FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and mendian = :mendian and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype=0  and jftype = 1",array(':weid'=>$_GPC['i'],':mendian'=>$mdlist['id']));
	        	
	    $yelist1 = pdo_fetchcolumn("SELECT SUM(yuenum) FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and mendian = :mendian and DATEDIFF(from_unixtime(addtime1,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype=1  and jftype = 1",array(':weid'=>$_GPC['i'],':mendian'=>$mdlist['id']));
	    }
	    
	    
	    
	    $jf_list = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 0 and jftype = 0 and jifen>0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdlist['id']));
	    
	    $mdsyjifen = $mdlist['number'] - $jf_list;
	    
	    $yelist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 1 and jftype = 0 and yuenum>0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdlist['id']));
	    
	    $mdsyyue = $mdlist['number1'] - $yelist;
	    
	    
	    $zongjifen = $mdsyjifen;
	    $totaljifen = 0;
	    $totalshjf = 0;
	    
	    
	    if(!empty($jflist))
	        $totaljifen = $jflist;
	    
	    if(!empty($jflist1))
	        $totalshjf = $jflist1;
	    
	    $zongyue = $mdsyyue;
	    $totalyue = 0;
	    $totalshye = 0;
	    
	    
	    if(!empty($yelist))
	        $totalyue = $yelist;
	    
	    if(!empty($yelist1))
	        $totalshye = $yelist1;
	    
	    include $this->template('cindex');
	}
	
	public function doMobileMdcz(){
	    global $_W,$_GPC;
	     
	    $openid = $_W['openid'];
	     
	    $mdlist = pdo_fetch("select id,name,weixin,number,number1,mincz from ".tablename('xjfb_mendian')." where weid = ".$_GPC['i']." and weixin = '".$openid."'");
	    if(empty($mdlist)){
	        message("你没有被绑定店员或门店管理员！",$this->createMobileUrl('uindex'),'error');
	        exit();
	    }
	
	
	    $setting = pdo_fetch("select title,footerimg,footerCopyright,jifen_ratio,yue_ratio from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	    
	    
	    $jf_list = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 0 and jftype = 0 and jifen>0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdlist['id']));
	     
	    $mdsyjifen = $mdlist['number'] - $jf_list;
	     
	    $yelist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 1 and jftype = 0 and yuenum>0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdlist['id']));
	     
	    $mdsyyue = $mdlist['number1'] - $yelist;
	    
	   
	    include $this->template('mdcz');
	} 
	
	public function doMobileMdczok(){
	    global $_W,$_GPC;
	
	    
	    $price = $_GPC['cznum'];
	    $type = $_GPC['buttype'];
	    $mid = $_GPC['mid'];
	     
	    
	    $params = array(
	    
	        'tid' => TIMESTAMP,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
	    
	        'ordersn' => TIMESTAMP,  //收银台中显示的订单号
	    
	        'title' => "云会员充值",          //收银台中显示的标题
	    
	        'fee' => $price,      //收银台中显示需要支付的金额,只能大于 0
	    
	        'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
	    
	    );
	    $this->payMent($mid,$type,$params);
	    
	    
	}
	
	protected function payMent($mid,$jytype,$params = array(), $mine = array()){
	
	    global $_W;
	
	    load()->model('activity');
	
	    activity_coupon_type_init();
	
	    if(!$this->inMobile) {
	
	        message('支付功能只能在手机上使用');
	
	    }
	
	    $params['module'] = $this->module['name'];
	
	    if($params['fee'] <= 0) {
	
	        $pars = array();
	
	        $pars['from'] = 'return';
	
	        $pars['result'] = 'success';
	
	        $pars['type'] = '';
	
	        $pars['tid'] = $params['tid'];
	
	        $site = WeUtility::createModuleSite($pars[':module']);
	
	        $method = 'payResult';
	
	        if (method_exists($site, $method)) {
	
	            exit($site->$method($pars));
	
	        }
	
	    }
	
	    $log =pdo_fetch("select * from ".tablename('core_paylog')." where uniacid=:uniacid and module=:module and tid=:tid",array(":uniacid"=>$_W['uniacid'],":module"=>$params['module'],":tid"=>$params['tid']));
	
	    if (empty($log)) {
	
	        $log = array(
	
	            'uniacid' => $_W['uniacid'],
	
	            'acid' => $_W['acid'],
	
	            'openid' => $_W['member']['uid'],
	
	            'module' => $this->module['name'],
	
	            'tid' => $params['tid'],
	
	            'fee' => $params['fee'],
	
	            'card_fee' => $params['fee'],
	
	            'status' => '0',
	
	            'is_usecard' => '0',
	
	        );
	
	        pdo_insert('core_paylog', $log);
	
	    }
	    
	    $paylog =pdo_fetch("select * from ".tablename('xjfb_store_paylog')." where weid=:weid and tid=:tid",array(":weid"=>$_W['uniacid'],":tid"=>$params['tid']));
	    
	    if (empty($paylog)) {
	    
	        $paylog = array(
	    
	            'weid' => $_W['uniacid'],
	    
	            'tid' => $params['tid'],
	    
	            'number' => $params['fee'],
	    
	            'mendian' => $mid,
	    
	            'jytype' => $jytype,
	    
	            'scoure' => '1',
	            
	            'status' => '0',
	            
	            'uid' => $_W['member']['uid'],
	            
	            'addtime' => TIMESTAMP
	    
	        );
	    
	        pdo_insert('xjfb_store_paylog', $paylog);
	    
	    }
	
	    if($log['status'] == '1') {
	
	        message('这个订单已经支付成功, 不需要重复支付.');
	
	    }
	
	    $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
	
	    if(!is_array($setting['payment'])) {
	
	        message('没有有效的支付方式, 请联系网站管理员.');
	
	    }
	
	    $pay = $setting['payment'];
	
	    $cards = activity_paycenter_coupon_available();
	
	    if (!empty($cards)) {
	
	        foreach ($cards as $key => &$val) {
	
	            if ($val['type'] == '1') {
	
	                $val['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $val['extra']['discount'] * 0.01));
	
	                $coupon[$key] = $val;
	
	            } else {
	
	                $val['discount_cn'] = sprintf("%.2f", $val['extra']['reduce_cost'] * 0.01);
	
	                $token[$key] = $val;
	
	                if ($log['fee'] < $val['extra']['least_cost'] * 0.01) {
	
	                    unset($token[$key]);
	
	                }
	
	            }
	
	            unset($val['icon']);
	
	        }
	
	    }
	
	    $cards_str = json_encode($cards);
	
	    if (empty($_W['member']['uid'])) {
	
	        $pay['credit']['switch'] = false;
	
	    }
	
	    if ($params['module'] == 'paycenter') {
	
	        $pay['delivery']['switch'] = false;
	
	        $pay['line']['switch'] = false;
	
	    }
	
	    if (!empty($pay['credit']['switch'])) {
	
	        $credtis = mc_credit_fetch($_W['member']['uid']);
	
	    }
	
	    $you = 0;
	
	    include $this->template('pay');
	
	}
	
	public function payResult($ret) {
	
	    global $_W,$_GPC;
	
	    $paylog = pdo_fetch("select * from ".tablename('xjfb_store_paylog')." where weid=:weid and tid=:tid",array(":weid"=>$this->weid,":tid"=>$ret['tid']));
	     
	    if ($ret['result'] == 'success' && $ret['from'] == 'notify') {
	
	        $user = pdo_fetch("select * from ".tablename('core_paylog')." where uniacid=:uniacid and tid=:tid",array(":uniacid"=>$this->weid,":tid"=>$ret['tid']));
	        
	       
	        if(!empty($user)){
	            pdo_update('core_paylog',array('status'=>1),array('uniacid'=>$this->weid,'tid'=>$ret['tid']));
	            pdo_update('xjfb_store_paylog',array('status'=>1),array('weid'=>$this->weid,'tid'=>$ret['tid']));
	            
	            pdo_delete('core_paylog',array('status'=>0,'uniacid'=>$this->weid));
	            pdo_delete('xjfb_store_paylog',array('status'=>0,'weid'=>$this->weid));
	            
                if(!empty($paylog)){
    	            
    	            $setting = pdo_fetch("select title,footerimg,footerCopyright,jifen_ratio,yue_ratio from ".tablename('xjfb_setting')." where weid = ".$this->weid);
    	            
    	            if($paylog['jytype'] == 1){
    	               
    	                $jifennum = pdo_fetch('select name,number,number1 from '.tablename('xjfb_mendian').' where weid = :weid and id = :id',array(':weid' => $this->weid,':id' => $paylog['mendian']));
    	                
    	                if(!empty($jifennum))
    	                    $jifennum = $jifennum['number1'] + ($paylog['number']*$setting['yue_ratio']);
    	            
    	                $data = array(
    	                    'weid' => $this->weid,
    	            
    	                    'mendian' => $paylog['mendian'],
    	            
    	                    'number' => $paylog['number'],
    	            
    	                    'scoure' => 1,
    	                    
    	                    'status' => 1,
    	            
    	                    'numtime' => TIMESTAMP
    	                );
    	            
    	                pdo_insert('xjfb_yuecz',$data);
    	                $czid = pdo_insertid();
    	            
    	                pdo_update('xjfb_mendian',array('number1'=>$jifennum,'numtime'=>TIMESTAMP),array('id'=>$paylog['mendian']));
    	            
    	                $h_data = array(
    	                    
    	                    'weid' => $this->weid,
    	                    'lid' => $czid,
    	                    'mendian' => $paylog['mendian'],
    	                    'jytype' => 1,
    	                    'cttype' => 0,
    	                    'number' => $paylog['number'],
    	                    'scoure' => 1,
    	                    'uid' => $paylog['uid'],
    	                    'status' => 1,
    	                    'context' => "门店记录:余额充值",
    	                    'addtime' => TIMESTAMP
    	                );
    	                pdo_insert('xjfb_md_history',$h_data);
    	                
    	                exit;
    	                
    	                //echo json_encode(array('ret'=>"yue"));
    	                //exit;
    	            }elseif($paylog['jytype'] == 0){
    	                $jifennum = pdo_fetch('select name,number,number1 from '.tablename('xjfb_mendian').' where weid = :weid and id = :id',array(':weid' => $this->weid,':id' => $paylog['mendian']));
    	                 
    	                if(!empty($jifennum))
    	                    $jifennum = $jifennum['number'] + ($paylog['number']*$setting['jifen_ratio']);
    	            
    	                $data = array(
    	                    'weid' => $this->weid,
    	            
    	                    'mendian' => $paylog['mendian'],
    	            
    	                    'number' => $paylog['number'],
    	                     
    	                    'scoure' => 1,
    	                    
    	                    'status' => 1,
    	            
    	                    'numtime' => TIMESTAMP
    	                );
    	            
    	                pdo_insert('xjfb_jifencz',$data);
    	                $czid = pdo_insertid();
    	                
    	                
    	                pdo_update('xjfb_mendian',array('number'=>$jifennum,'numtime'=>TIMESTAMP),array('id'=>$paylog['mendian']));
    	                 
    	                $h_data = array(
    	                     
    	                    'weid' => $this->weid,
    	                    'lid' => $czid,
    	                    'mendian' => $paylog['mendian'],
    	                    'jytype' => 0,
    	                    'cttype' => 0,
    	                    'number' => $paylog['number'],
    	                    'scoure' => 1,
    	                    'uid' => $paylog['uid'],
    	                    'status' => 1,
    	                    'context' => "门店记录:积分充值",
    	                    'addtime' => TIMESTAMP
    	                );
    	                pdo_insert('xjfb_md_history',$h_data);
    	                
    	                exit;
    	                //echo json_encode(array('ret'=>'jifen'));
    	                //exit;
    	            }elseif($paylog['jytype'] == 3){
    	                $price = $paylog['number'];
    	                $zsprice = $paylog['mendian'];
    	                
    	                $_data = array(
    	                    'weid' => $this->weid,
    	                     
    	                    'czmoney' => $price,
    	                     
    	                    'zsmoney' => $zsprice,
    	                     
    	                    'uid' => $paylog['uid'],
    	                     
    	                    'orderid' => '',
    	                     
    	                    'status' => 1,
    	                     
    	                    'addtime' => time()
    	                );
    	                 
    	                pdo_insert("xjfb_hycz",$_data);
    	                 
    	                load()->model('mc');
    	                 
    	                mc_credit_update($paylog['uid'],'credit2',($price+$zsprice),array($paylog['uid'],"云会员自主充值".$price."送".$zsprice));
    	                
    	                exit;
    	            }
                }
                
	        }
	
	    }
	
	    
	    if($ret['result'] == 'success') {
	        if($paylog['jytype'] == 3)
	            message('充值成功',$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=uindex&m=".$this->module['name'],'success');
	        else 
	            message('充值成功',$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=cindex&m=".$this->module['name'],'success');
	             
	    }
	    
	   
	
	
	}
	

	public function doMobileMdtx(){
	    global $_W,$_GPC;
	
	    if(empty($_GPC['mid'])){
	        header("Location:".$_W['siteroot']."app/index.php?i=".$_GPC['i']."&c=entry&do=cindex&m=".$this->module['name']);
	        exit;
	    }
	    
	    
	    $openid = $_W['openid'];
	
	    $mdlist = pdo_fetch("select id,name,weixin,number,number1,mintx from ".tablename('xjfb_mendian')." where weid = ".$_GPC['i']." and weixin = '".$openid."'");
	    if(empty($mdlist)){
	        message("你没有被绑定店员或门店管理员！",$this->createMobileUrl('uindex'),'error');
	        exit();
	    }
	
	    $jf_list = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 0 and jftype = 0 and jifen>0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdlist['id']));
	    
	    $mdsyjifen = $mdlist['number'] - $jf_list;
	    
	    $yelist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 1 and jftype = 0 and yuenum>0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdlist['id']));
	    
	    $mdsyyue = $mdlist['number1'] - $yelist;
	    
	
	    $setting = pdo_fetch("select title,footerimg,footerCopyright,jifen_ratio,yue_ratio,tx_rate from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	     
	    $mdyue = $mdsyjifen/$setting['jifen_ratio'];
	    $mdyue1 = $mdsyyue/$setting['yue_ratio'];
	    
	    $yhlist = pdo_fetchall("select * from ".tablename('xjfb_yinhanglist')." where weid = ".$_GPC['i']." order by sort asc");
	     
	
	    if($_GPC['opp'] == 'tixian'){
	        
	        $price = $_GPC['price'];
	        $type = $_GPC['type'];
	        $mid = $_GPC['mid'];
	         
	        $txtype = $_GPC['txtype'];
	        $alipay = $_GPC['alipay'];
	        $kaihu = $_GPC['kaihu'];
	        $card = $_GPC['card'];
	        $suoshu = $_GPC['suoshu'];
	      
	      
	        
	        $data = array(
	            'weid' => $_GPC['i'],
	            'mendian' => $mid,
	            'number' => $price - ($price * $setting['tx_rate']),
	            'txtype' => $txtype,
	            'price' => $price,
	            'jytype' => $type,
	            'alipay' => $alipay,
	            'yhcard' => $card,
	            'yhsuoshu' => $suoshu,
	            'yhkaihu' => $kaihu,
	            'openid' => $_W['openid'],
	            'addtime' => time()
	                  
	        );
	            
	        pdo_insert('xjfb_yuetx',$data);
	        $txid = pdo_insertid();
	        
	        $h_data = array(
	             
	            'weid' => $this->weid,
	            'lid' => $txid,
	            'mendian' => $mid,
	            'jytype' => $type,
	            'cttype' => 0,
	            'number' => $price,
	            'scoure' => 1,
	            'uid' => $_W['member']['uid'],
	            'status' => 0,
	            'context' => "门店记录:提现",
	            'addtime' => TIMESTAMP
	        );
	        pdo_insert('xjfb_md_history',$h_data);
	       
	        if($type == 0){
	            $number = $setting['jifen_ratio']*$price;
	            pdo_update('xjfb_mendian',array('number'=>$mdlist['number']-$number),array('id'=>$mid));
	        }
	        else {
	            $number = $setting['yue_ratio']*$price;
	            pdo_update('xjfb_mendian',array('number1'=>$mdlist['number1']-$number),array('id'=>$mid));
	        }
	         
	        
	        if($type == 1){
	            echo json_encode(array('ret'=>'yue','num'=>($mdlist['number1']-$number)/$setting['yue_ratio']));
	            exit;
	        }else{
	            echo json_encode(array('ret'=>'jifen','num'=>($mdlist['number']-$number)/$setting['jifen_ratio']));
	            exit;
	        }
	        
	        echo json_encode(array('ret'=>false));
	        exit;
	    }
	    
	    include $this->template('mdtx');
	}
	
	public function doMobileTxjilu(){
	    global $_W,$_GPC;
	
	    if(empty($_GPC['mid'])){
	        header("Location:".$_W['siteroot']."app/index.php?i=".$_GPC['i']."&c=entry&do=cindex&m=".$this->module['name']);
	        exit;
	    }
	    
	    $openid = $_W['openid'];
	
	    $mdlist = pdo_fetch("select id,name,weixin,number,number1 from ".tablename('xjfb_mendian')." where weid = ".$_GPC['i']." and weixin = '".$openid."'");
	    if(empty($mdlist)){
	        message("你没有被绑定店员或门店管理员！",$this->createMobileUrl('uindex'),'error');
	        exit();
	    }
	
	
	    $setting = pdo_fetch("select title,footerimg,footerCopyright from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	
	
	    
	    $list = pdo_fetchall("select c.price,c.addtime,c.jytype,c.status from ".tablename('xjfb_yuetx')." as c where c.weid = :weid and c.mendian = :mendian and c.jytype = :jytype order by c.addtime desc LIMIT 0,8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid'],":jytype"=>$_GPC['type']));
	       
	
	    include $this->template('txjilu');
	}
	public function doMobileTxjilu_item(){
	    global $_W,$_GPC;
	    
	    
	    $pindex = max(1, intval($_GPC['page']));
	    
	    $pages = ($pindex - 1) * 8;
	    
	    $list = pdo_fetchall("select c.price,c.addtime,c.jytype,c.status from ".tablename('xjfb_yuetx')." as c where c.weid = :weid and c.mendian = :mendian  and c.jytype = :jytype order by c.addtime desc LIMIT ".$pages.",8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid'],":jytype"=>$_GPC['type']));
	    
	    
	   if(empty($list)) {
	
			die("nodata");
	
		}
	    include $this->template('txjilu_item');
	}
	public function doMobileCzjilu(){
	    global $_W,$_GPC;
	
	    if(empty($_GPC['mid'])){
	        header("Location:".$_W['siteroot']."app/index.php?i=".$_GPC['i']."&c=entry&do=cindex&m=".$this->module['name']);
	        exit;
	    }
	     
	    $openid = $_W['openid'];
	
	    $mdlist = pdo_fetch("select id,name,weixin,number,number1 from ".tablename('xjfb_mendian')." where weid = ".$_GPC['i']." and weixin = '".$openid."'");
	    if(empty($mdlist)){
	        message("你没有被绑定店员或门店管理员！",$this->createMobileUrl('uindex'),'error');
	        exit();
	    }
	
	
	    $setting = pdo_fetch("select title,footerimg,footerCopyright from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	
	
	    if($_GPC['type'] == 0){
	        $list = pdo_fetchall("select c.number,c.numtime from ".tablename('xjfb_jifencz')." as c where c.weid = :weid and c.mendian = :mendian and c.scoure = 1 order by c.numtime desc LIMIT 0,8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid']));
	    }else{
	        $list = pdo_fetchall("select c.number,c.numtime,c.status from ".tablename('xjfb_yuecz')." as c where c.weid = :weid and c.mendian = :mendian  order by c.numtime desc LIMIT 0,8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid']));
	
	    }
	
	    include $this->template('czjilu');
	}
	public function doMobileCzjilu_item(){
	    global $_W,$_GPC;
	     
	     
	    $pindex = max(1, intval($_GPC['page']));
	     
	    $pages = ($pindex - 1) * 8;
	     
	    if($_GPC['type'] == 0){
	        $list = pdo_fetchall("select c.number,c.numtime from ".tablename('xjfb_jifencz')." as c where c.weid = :weid and c.mendian = :mendian and c.scoure = 1 order by c.numtime desc LIMIT ".$pages.",8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid']));
	    }else{
	        $list = pdo_fetchall("select c.number,c.numtime from ".tablename('xjfb_yuecz')." as c where c.weid = :weid and c.mendian = :mendian and c.scoure = 1 order by c.numtime desc LIMIT ".$pages.",8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid']));
	         
	    }
	     
	    if(empty($list)) {
	
	        die("nodata");
	
	    }
	    include $this->template('czjilu_item');
	}
	
	public function doMobileYuangong(){
	    global $_W,$_GPC;
	    
	    
	    if(empty($_GPC['mid'])){
	        header("Location:".$_W['siteroot']."app/index.php?i=".$_GPC['i']."&c=entry&do=cindex&m=".$this->module['name']);
	        exit;
	    }
	    
	    $setting = pdo_fetch("select title,footerimg,footerCopyright from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	    
	    
	    $list = pdo_fetchall("select y.name,y.weixin,m.avatar from ".tablename('xjfb_yuangong')." as y left join ".tablename('mc_mapping_fans')." as f on f.openid = y.weixin left join ".tablename('mc_members')." as m on f.uid = m.uid where y.mendian = :mendian and y.weid = :weid",array(":mendian"=>$_GPC['mid'],":weid"=>$_GPC['i']));
	    
	   
	    
	    include $this->template('yuangong');
	}
	
	
	public function doMobileHistorys(){
	    global $_W,$_GPC;
	     
	   
	    $setting = pdo_fetch("select title,footerimg,footerCopyright from ".tablename('xjfb_setting')." where weid = ".$_GPC['i']);
	    
	    
	    $list = pdo_fetchall("select h.jytype,h.cttype,h.number,h.addtime,m.realname from ".tablename('xjfb_md_history')." as h left join ".tablename('mc_members')." as m on h.uid = m.uid where weid = :weid and h.mendian = :mendian and scoure=1 and status=1 order by h.addtime desc LIMIT 0,8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid']));
	     
	    
	    include $this->template('historys');
	}
	
	public function doMobileHi_item(){
	    global $_W,$_GPC;
	    
	    $pindex = max(1, intval($_GPC['page']));
	    
	    $pages = ($pindex - 1) * 8;
	    
	    
	    $list = pdo_fetchall("select h.jytype,h.cttype,h.number,h.addtime,m.realname from ".tablename('xjfb_md_history')." as h left join ".tablename('mc_members')." as m on h.uid = m.uid where weid = :weid and h.mendian = :mendian and scoure=1 and status=1 order by h.addtime desc LIMIT ".$pages.",8",array(":weid"=>$_GPC['i'],":mendian"=>$_GPC['mid']));
	    
	    
	    if(empty($list)) {
	    
	        die("nodata");
	    
	    }
	    
	    include $this->template('hi_item');
	}
	
	
	public function Getopenid($appid,$appSecret){
	    global $_W,$_GPC;
	    
		if(empty($_COOKIE['useropenid_zm_jfb'.$_GPC['i']])){
			if($this->exists_tokenBytxt($appid)){
				if($this->exprise_tokenBytxt($appid)){
					$token = $this->getToken($appid, $appSecret);
	
					unlink($appid.'.txt');
	
					file_put_contents($appid.'.txt', $token);
				}else {
					$token = file_get_contents($appid.'.txt');
				}
			}else{
				$token = $this->getToken($appid, $appSecret);
				file_put_contents($appid.'.txt', $token);
			}
	
			$openidStr = $this->Get_Openid($appid,$appSecret);
			setcookie("useropenid_zm_jfb".$_GPC['i'], $openidStr, time()+3600*24*30);						
		}else{
			$openidStr = $_COOKIE['useropenid_zm_jfb'.$_GPC['i']];
		}		
	
		return $openidStr;
	}
	public function Get_Openid($appid,$appsecret)
	{
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
			$url = $this->__CreateOauthUrlForCode($appid,$baseUrl);
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取openid
			//print("2<br/>");
			//print('code:'.$_GET['code']);
			$code = $_GET['code'];
			$openid = $this->getOpenidFromMp($appid,$appsecret,$code);			
			return $openid;
		}
	}
	private function __CreateOauthUrlForCode($appid,$redirectUrl)
	{
		$urlObj["appid"] = $appid;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	public function GetOpenidFromMp($appid,$appsecret,$code)
	{
		$url = $this->__CreateOauthUrlForOpenid($appid,$appsecret,$code);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		/*if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
		 && WxPayConfig::CURL_PROXY_PORT != 0){
		curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
		curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
		}*/
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
	
		$this->data = $data;
		$openid = $data['openid'];
		return $openid;
	}
	private function __CreateOauthUrlForOpenid($appid,$appsecret,$code)
	{
		$urlObj["appid"] = $appid;
		$urlObj["secret"] = $appsecret;
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
	
		$buff = trim($buff, "&");
		return $buff;
	}
	
	
    public function mendiannumber($mdid,$jifen){
		global $_GPC;
	
	    $number = 0;
		$sql = "SELECT number,numtime FROM " . tablename('xjfb_mendian') . " WHERE weid = :weid and id = :id";
		$mdlist = pdo_fetch($sql,array(':weid'=>$_GPC['i'],':id'=>$mdid));
	
	
		if(!empty($mdlist)){
			if(!empty($mdlist['number'])){
			    
				if($mdlist['number'] == 0){
				    $number = 3;
				}
				
				$jflist = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 0 and jftype = 0 and jifen>0",array(":weid"=>$_GPC['i'],":mendian"=>$mdid));
				$syjifen = $mdlist['number']-$jflist;					
	
				
				if( $mdlist['number'] <= $jflist || $syjifen < $jifen){
					 $number = 1;
				}
					
			}else
				$number = 3;
		}else
			$number = 3;
		
		return $number;
		
	}
	
	public function mendiannumber1($mdid,$jifen){
		global $_GPC;
	
		$number = 0;
		$sql = "SELECT number1,numtime FROM " . tablename('xjfb_mendian') . " WHERE weid = :weid and id = :id";
		$mdlist = pdo_fetch($sql,array(':weid'=>$_GPC['i'],':id'=>$mdid));
	
	
		if(!empty($mdlist)){
			if(!empty($mdlist['number1'])){
			    
				if($mdlist['number1'] == 0){
			         $number = 3;
				 }
	
					$jflist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 1 and jftype = 0 and yuenum>0",array(":weid"=>$_GPC['i'],":mendian"=>$mdid));
	
					
					$syjifen = $mdlist['number1']-$jflist;

					if($mdlist['number1'] <= $jflist || $syjifen < $jifen){
					    $number = 1;
					}
					
			}else
				$number = 3;
		}else
			$number = 3;
		
		return $number;
	}
	
	public function createerweima($jifen,$jftype,$content,$acid,$scene_id,$ticketstr,$urlstr){
		global $_W,$_GPC;
	
	
		$rule_data = array(
				'uniacid' => $_GPC['i'],
				'name' => "zm_jfb",
				'module' => "zm_jfb",
				'status' => 1,
				'displayorder' => 254,
		);
	
		pdo_insert('rule',$rule_data);
		$rid = pdo_insertid();
	
		$list_data =array(
				'weid' => $_GPC['i'],
				'mendian' => $_GPC['mid'],
				'dianyuan' => $_GPC['id'],
				'jifennumber' => $jifen,
				'ruleid' => $rid,
				'jftype' => $jftype,
				'content' => $content,
				'addtime' => TIMESTAMP,
		);
		pdo_insert('xjfb_jifenlist',$list_data);
		$listid = pdo_insertid();
	
		$rule_key = array(
				'uniacid' => $_GPC['i'],
				'module' => 'zm_jfb',
				'content' => 'zm_jfb'.$listid,
				'type' => '1',
				'displayorder' => 254,
				'status' => 1
		);
	
		$rule_key['rid'] = $rid;
		pdo_insert('rule_keyword',$rule_key);
	
	
	
		$qrcode = array(
				'uniacid'=>$_GPC['i'],
				'acid'=>$acid,
				'qrcid'=>$scene_id,
				'name'=>'zm_jfb',
				'keyword'=>"zm_jfb".$listid,
				'model'=>1,
				'ticket'=>$ticketstr,
				'expire'=>30*24*3600,
				'createtime'=>time(),
				'status'=>1,
				'url'=>$urlstr,
		);
		pdo_insert('qrcode',$qrcode);
		pdo_insert('xjfb_qrcode',array('weid'=>$_GPC['i'],'sceneid'=>$scene_id,'ticket'=>$ticketstr,'rid'=>$listid,'url'=>$urlstr));
	
	}
	
	public function createerweima1($jifen,$jftype,$acid,$scene_id,$ticketstr,$urlstr){
		global $_W,$_GPC;
	
	
		$rule_data = array(
				'uniacid' => $_GPC['i'],
				'name' => "zm_jfb",
				'module' => "zm_jfb",
				'status' => 1,
				'displayorder' => 254,
		);
	
		pdo_insert('rule',$rule_data);
		$rid = pdo_insertid();
	
	
	
		$list_data =array(
				'weid' => $_GPC['i'],
				'mendian' => $_GPC['mid'],
				'dianyuan' => $_GPC['id'],
				'jifennumber' => $jifen,
				'ruleid' => $rid,				
		        'jftype' => $jftype,
				'codetype' => 1,
				'addtime' => TIMESTAMP,
		);
		pdo_insert('xjfb_jifenlist',$list_data);
		$listid = pdo_insertid();
	
	
		$rule_key = array(
				'uniacid' => $_GPC['i'],
				'module' => 'zm_jfb',
				'content' => 'zm_jfb'.$listid,
				'type' => '1',
				'displayorder' => 254,
				'status' => 1
		);
		$rule_key['rid'] = $rid;
		pdo_insert('rule_keyword',$rule_key);
	
	
	
	
		$qrcode = array(
				'uniacid'=>$_GPC['i'],
				'acid'=>$acid,
				'qrcid'=>$scene_id,
				'name'=>'zm_jfb',
				'keyword'=>"zm_jfb".$listid,
				'model'=>1,
				'ticket'=>$ticketstr,
				'expire'=>30*24*3600,
				'createtime'=>time(),
				'status'=>1,
				'url'=>$urlstr,
		);
		pdo_insert('qrcode',$qrcode);
		pdo_insert('xjfb_qrcode',array('weid'=>$_GPC['i'] ,'sceneid'=>$scene_id,'ticket'=>$ticketstr,'rid'=>$listid,'url'=>$urlstr));
	
	}
	
		
	public function doWebJfbsetting(){
		global $_W,$_GPC;
			
	
		$kjsetting = pdo_fetch('select * from '.tablename('xjfb_setting').' where weid = :weid',array(":weid" => $_W['uniacid']));
		$card = pdo_fetch('select * from '.tablename('xjfb_cardlist').' where weid = :weid',array(":weid" => $_W['uniacid']));
	
	
		if (checksubmit('submit')) {
		    
		    load()->func('file');
		    
		    if (!empty($_FILES['apiclient_cert_file']['name'])) {
		         
		        $apiclient_cert = move_uploaded_file($_FILES['apiclient_cert_file']['tmp_name'],MODULE_ROOT.'/img/apiclient_cert'.$_W['uniacid'].'.pem');
		         
		        $apiclient_cert = MODULE_ROOT.'/img/apiclient_cert'.$_W['uniacid'].'.pem';
		    }else{
		        $apiclient_cert = MODULE_ROOT.'/img/apiclient_cert'.$_W['uniacid'].'.pem';
		    }
		    
		    if (!empty($_FILES['apiclient_key_file']['name'])) {
		    
		        $apiclient_key = move_uploaded_file($_FILES['apiclient_key_file']['tmp_name'],MODULE_ROOT.'/img/apiclient_key'.$_W['uniacid'].'.pem');
		    
		        $apiclient_key = MODULE_ROOT.'/img/apiclient_key'.$_W['uniacid'].'.pem';
		    }else{
		        $apiclient_key = MODULE_ROOT.'/img/apiclient_key'.$_W['uniacid'].'.pem';
		    }
		     
		    if (!empty($_FILES['rootca_file']['name'])) {
		         
		        $rootca =  move_uploaded_file($_FILES['rootca_file']['tmp_name'],MODULE_ROOT.'/img/rootca'.$_W['uniacid'].'.pem');
		    
		        $rootca = MODULE_ROOT.'/img/rootca'.$_W['uniacid'].'.pem';
		    }else{
		        $rootca = MODULE_ROOT.'/img/rootca'.$_W['uniacid'].'.pem';
		    }
		    

			$data = array(
				'weid' => $_W['uniacid'],
						
				'title' => trim($_GPC['title']),
		
				'tishi' =>$_GPC['tishi'],
			    
			    'headerimg' => $_GPC['headerimg'],
			    
				'footerimg' =>$_GPC['footerimg'],
						
				'footerCopyright' =>$_GPC['footerCopyright'],
						
			    'mchid' =>$_GPC['mchid'],
			     
			    'apikey' =>$_GPC['apikey'],
			    
			    
			    
				'addrepeat' =>$_GPC['addrepeat'],
			    
			    
			   			    
			    'gethy' =>$_GPC['gethy'],
			    
			    'openhy' =>$_GPC['openhy'],
			    
			    'openpay' =>$_GPC['openpay'],
			    
			    'paymoney' =>$_GPC['paymoney'],
			    
			    
			    'jifen_name' =>$_GPC['jifen_name'],
			    
			    'gzh_name' =>$_GPC['gzh_name'],
			    
			    'md_name' =>$_GPC['md_name'],
			    	
			    'jifenshop' =>$_GPC['jifenshop'],
			    	
			    'gongzhonghao1' =>$_GPC['gongzhonghao1'],
			    	
			    'mendian1' =>$_GPC['mendian1'],
			    
			    
			    'msg_title' =>$_GPC['msg_title'],
			    
			    'msg_img' =>$_GPC['msg_img'],
			    
			    'msg_url' =>$_GPC['msg_url'],
			    
			    'msg_con' =>$_GPC['msg_con'],
			    
			    'ifgroup' =>$_GPC['ifgroup'],
			    
			    'cztext' =>$_GPC['cztext'],
			    
			    'czhead' =>$_GPC['czhead'],
			    
			    
			    'hssupper' =>$_GPC['hssupper'],
			    
			    'ifjifen' =>$_GPC['ifjifen'],
			    
			    'ifyue' =>$_GPC['ifyue'],
			    
			    'ifdengji' =>$_GPC['ifdengji'],
			    
			    'jifen_ratio' => $_GPC['jifen_ratio'],
			    
			    'yue_ratio' => $_GPC['yue_ratio'],
			    
			    'tx_rate' => $_GPC['tx_rate'],
			    
			    'apiclient_cert' => $apiclient_cert,
			    
			    'apiclient_key' => $apiclient_key,
			    
			    'rootca' => $rootca
			    
			);
			/*'xiangqing' =>$_GPC['xiangqing'],
			     
			    'gongzhonghao' =>$_GPC['gongzhonghao'],
			    	
			    'mendian' =>$_GPC['mendian'],
			/*
			 * */
			
			if($_GPC['gethy'] == 0){
			    $data['xhjifen'] =$_GPC['xhjifen'];
			     
			    $data['xhyue'] =$_GPC['xhyue'];
			}else{
			    $data['xhjifen'] =$_GPC['hdjifen'];
			    
			    $data['xhyue'] =$_GPC['hdyue'];
			}
	
			if (!empty($kjsetting)) {
	
				pdo_update('xjfb_setting',$data,array('id'=>$kjsetting[id]));
				
			} else {
	
				pdo_insert('xjfb_setting',$data);
				
			}
			
			if(!empty($card)){
			    pdo_update('xjfb_cardlist',array('card_title'=>$_GPC['card_title'],'card_subtitle'=>$_GPC['card_subtitle']),array('id'=>$card['id']));
			}else{
			    pdo_insert('xjfb_cardlist',array('weid'=>$_W['uniacid'],'cardid'=>"",'card_title'=>$_GPC['card_title'],'card_subtitle'=>$_GPC['card_title']));
			}
	
			message('参数设置成功-&#25240;&#70;&#32764;&#70;&#22825;&#70;&#20351;&#70;&#36164;&#70;&#28304;&#70;&#31038;&#70;&#21306;&#70;&#25552;&#70;&#20379;！', $this->createWebUrl('Jfbsetting', array('op' => 'display')), 'success');
	
		}
	
		include $this->template('jfb-setting');
	}
		
    public function doMobileJilu(){
		global $_W,$_GPC;
			
		$sql1 = "SELECT * FROM " . tablename('xjfb_setting') . " WHERE weid = :weid";
		$setting = pdo_fetch($sql1,array(':weid'=>$_GPC['i']));
	
		$tsql = 'select m.name as mendian,m.number1,d.name as yuangong from  '.tablename('xjfb_mendian').' as m left join '.tablename('xjfb_yuangong').' as d on d.mendian = m.id WHERE m.weid = :weid and m.id = :mendian ';
		$tlist = pdo_fetch($tsql,array(':weid'=>$_GPC['i'],':mendian'=>$_GPC['mid']));
	
	
		$mendian_name = $tlist['mendian'];
		$dianyuan_name = $tlist['yuangong'];
		$zfen_number = 0;
		$fen_number = 0;
		$taday_number = 0;		
		$zfen_number1 = 0;
		$fen_number1 = 0;
		$taday_number1 = 0;		
		
		if(empty($_GPC['tid']))$tid = 0;else $tid = $_GPC['tid'];			
		if($tid==0)		
		    $syjifen = $this->Getsyjifen($_GPC['mid']);		
		else
		    $syjifen = $this->Getsyyue($_GPC['mid']);
		
		if($tid == 0){
			$zong = pdo_fetchcolumn("SELECT SUM(jifen) as jifen FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and dianyuan = :dianyuan and codetype={$tid} and jftype = 0",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($zong))$zfen_number = $zong;else $zfen_number = 0;		
		}else{		
			$zong = pdo_fetchcolumn("SELECT SUM(yuenum) as jifen FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and dianyuan = :dianyuan and codetype={$tid} and jftype = 0",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($zong))$zfen_number = $zong;else $zfen_number = 0;		
		}
		if($tid == 0){
			$mouth = pdo_fetchcolumn("SELECT SUM(jifen) as jifen FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) <= 30 and codetype={$tid} and jftype = 0",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($mouth))$fen_number = $mouth;else $fen_number = 0;		
		}else{			
			$mouth = pdo_fetchcolumn("SELECT SUM(yuenum) as jifen FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime1,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) <= 30 and codetype={$tid} and jftype = 0",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($mouth))$fen_number = $mouth;else $fen_number = 0;
		}
		if($tid == 0){
			$taday = pdo_fetchcolumn("SELECT SUM(jifen) as jifen FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype={$tid} and jftype = 0",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($taday))$taday_number = $taday;else $taday_number = 0;		
		}else{			
		    $taday = pdo_fetchcolumn("SELECT SUM(yuenum) as jifen FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime1,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype={$tid} and jftype = 0",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($taday))$taday_number = $taday;else $taday_number = 0;		
		}	

		
		if($tid == 0){			
		    $zong1 = pdo_fetchcolumn("SELECT SUM(jifen) as jifen FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and dianyuan = :dianyuan and codetype={$tid} and jftype = 1",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($zong1))$zfen_number1 = $zong1;else $zfen_number1 = 0;
		}else{			
		    $zong1 = pdo_fetchcolumn("SELECT SUM(yuenum) as jifen FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and dianyuan = :dianyuan and codetype={$tid} and jftype = 1",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
		    if(!empty($zong1))$zfen_number1 = $zong1;else $zfen_number1 = 0;	
		}		
		if($tid == 0){
			$mouth1 = pdo_fetchcolumn("SELECT SUM(jifen) as jifen FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) <= 30 and codetype={$tid}  and jftype = 1",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
		    if(!empty($mouth1)) $fen_number1 = $mouth1;else $fen_number1 = 0;		
		}else{			
			$mouth1 = pdo_fetchcolumn("SELECT SUM(yuenum) as jifen FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime1,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) <= 30 and codetype={$tid}  and jftype = 1",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
		    if(!empty($mouth1)) $fen_number1 = $mouth1;else $fen_number1 = 0;		
		}	
		if($tid == 0){
			$taday1 = pdo_fetchcolumn("SELECT SUM(jifen) as jifen FROM ".tablename('xjfb_jifenjilu')." where jifen >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype={$tid}  and jftype = 1",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			
			if(!empty($taday1)) $taday_number1 = $taday1;else $taday_number1 = 0;
		}else{			
		    $taday1 = pdo_fetchcolumn("SELECT SUM(yuenum) as jifen FROM ".tablename('xjfb_jifenjilu')." where yuenum >0 and weid = :weid and dianyuan = :dianyuan and DATEDIFF(from_unixtime(addtime1,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype={$tid}  and jftype = 1",array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
			if(!empty($taday1))$taday_number1 = $taday1;else $taday_number1 = 0;		
		}
		
		
		if($tid == 0){
			$sql2 = "select m.nickname,j.jifen as fenshu,j.addtime,j.jftype,d.name as dianyuan from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('xjfb_yuangong')." as d on j.dianyuan = d.id left join ".tablename('mc_members')." as m on m.uid = j.mcid WHERE j.jifen >0 and j.weid = :weid and j.dianyuan = :dianyuan and codetype={$tid} ORDER BY j.addtime DESC LIMIT 0,5";
			$list = pdo_fetchall($sql2,array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
		}else{			
		    $sql2 = "select m.nickname,j.yuenum as fenshu,j.addtime1 as addtime,j.jftype,d.name as dianyuan from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('xjfb_yuangong')." as d on j.dianyuan = d.id left join ".tablename('mc_members')." as m on m.uid = j.mcid WHERE j.yuenum >0 and j.weid = :weid and j.dianyuan = :dianyuan and codetype={$tid} ORDER BY j.addtime1 DESC LIMIT 0,5";
			$list = pdo_fetchall($sql2,array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));		
		}
		
	
	
		include $this->template('jilu');
	}
	
	public function doMobileJiluitem(){
		global $_W,$_GPC;
	
		$pindex = max(1, intval($_GPC['page']));
	
		$pages = ($pindex - 1) * 5;
	
		if(empty($_GPC['tid']))$tid = 0;else $tid = $_GPC['tid'];
		
		if($_GPC['tid'] == 0){
			$sql2 = "select m.nickname,j.jifen as fenshu,j.addtime,j.jftype from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid WHERE j.weid = :weid and j.dianyuan = :dianyuan and codetype={$tid} ORDER BY j.addtime DESC LIMIT ".$pages.",5";
			$list = pdo_fetchall($sql2,array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));
		}else{			
		    $sql2 = "select m.nickname,j.yuenum as fenshu,j.addtime1 as addtime,j.jftype from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid WHERE j.weid = :weid and j.dianyuan = :dianyuan and codetype={$tid} ORDER BY j.addtime1 DESC LIMIT ".$pages.",5";
			$list = pdo_fetchall($sql2,array(':weid'=>$_GPC['i'],':dianyuan'=>$_GPC['id']));		}
		
		
		if(empty($list)) {
	
			die("nodata");
	
		}
		include $this->template('jiluitem');
	}
	
	public function doMobileCzjl(){
	    global $_W,$_GPC;
	    
	    $sql = "SELECT title,footerimg FROM " . tablename('xjfb_setting') . " WHERE weid = :weid";
	    $setting = pdo_fetch($sql,array(':weid'=>$_GPC['i']));
	    
	    
	    $sql = "select h.*,m.nickname from ".tablename('xjfb_hycz')." as h left join ".tablename('mc_members')." as m on m.uid = h.uid WHERE h.weid = :weid and h.uid = :uid ORDER BY h.addtime desc LIMIT 0,5";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":uid" => $_GPC['uid']));
	    
	    
	    include $this->template('czjl');
	}
	public function doMobileCzjlitem(){
	    global $_W,$_GPC;
	    
	    $pindex = max(1, intval($_GPC['page']));
	    
	    $pages = ($pindex - 1) * 5;
	    
	    $sql = "select h.*,m.nickname from ".tablename('xjfb_hycz')." as h left join ".tablename('mc_members')." as m on m.uid = h.uid WHERE h.weid = :weid and h.uid = :uid ORDER BY h.addtime desc LIMIT ".$pages.",5";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":uid" => $_GPC['uid']));
	     
	    if(empty($list)) {
	    
	        die("nodata");
	    
	    }
	    
	    include $this->template('czjlitem');
	}
	
	public function doMobileMendian(){
	    global $_W,$_GPC;
	     
	    $sql = "SELECT title,footerimg FROM " . tablename('xjfb_setting') . " WHERE weid = :weid";
	    $setting = pdo_fetch($sql,array(':weid'=>$_GPC['i']));
	     
	     
	    $sql = "select * from ".tablename('xjfb_mendian')." WHERE weid = :weid ORDER BY addtime desc LIMIT 0,5";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	     
	     
	    include $this->template('mendian');
	}
	public function doMobileMditem(){
	    global $_W,$_GPC;
	     
	    $pindex = max(1, intval($_GPC['page']));
	     
	    $pages = ($pindex - 1) * 5;
	     
	    $sql = "select * from ".tablename('xjfb_mendian')." WHERE weid = :weid ORDER BY addtime desc LIMIT ".$pages.",5";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	
	    if(empty($list)) {
	    
	        die("nodata");
	    
	    }
	    
	    include $this->template('mditem');
	}
	
			
	public function doMobileGetCount(){		
		global $_W,$_GPC;
				
		$yg_id = $_GPC['id'];		
		$md_id = $_GPC['mid'];						
		
		$mdsql = "SELECT number,numtime FROM " . tablename('xjfb_mendian') . " WHERE weid = :weid and id = :id";		
		$md_list = pdo_fetch($mdsql,array(':weid'=>$_W['uniacid'],':id'=>$md_id));		
		
		if(!empty($md_list)){
			if($md_list['number'] != '0'){//from_unixtime(addtime,'%Y-%m-%d %H:%i')>=from_unixtime(:numtime, '%Y-%m-%d %H:%i') and
				$jf_list = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 0 and jftype = 0 and jifen>0",array(":weid"=>$_W['uniacid'],":mendian"=>$md_id));
		        
				//if($_GPC['buttype']!=1&&$_GPC['jftype']!=1){
				$mdsyjifen = $md_list['number'] - $jf_list;
				//}
		
		
			}else
				$mdsyjifen = 0;
		}else
			$mdsyjifen = 0;
		

		$mdsql = "SELECT number1,numtime FROM " . tablename('xjfb_mendian') . " WHERE weid = :weid and id = :id";
		$md_list = pdo_fetch($mdsql,array(':weid'=>$_W['uniacid'],':id'=>$md_id));
		
		
		if(!empty($md_list)){
			if($md_list['number1'] != '0'){
				$yelist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 1 and jftype = 0 and yuenum>0",array(":weid"=>$_W['uniacid'],":mendian"=>$md_id));
		
				//if($_GPC['buttype']!=1&&$_GPC['jftype']!=1){
				$mdsyyue = $md_list['number1']-$yelist;
				//}
		
			}else
				$mdsyyue = 0;
		}else
			$mdsyyue = 0;
		

		$jflist = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and DATEDIFF(from_unixtime(addtime,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0 and codetype = 0 and jftype = 0 and dianyuan = :dianyuan and jifen>0",array(":weid"=>$_GPC['i'],":dianyuan"=>$yg_id));
		
		$yelist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and DATEDIFF(from_unixtime(addtime1,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d')) = 0  and codetype = 1 and jftype = 0 and dianyuan = :dianyuan and yuenum>0",array(":weid"=>$_GPC['i'],":dianyuan"=>$yg_id));
		if(empty($jflist))			
		    $jflist = 0;		
		if(empty($yelist))
			$yelist = 0;
		
		echo json_encode(array('syjf'=>$mdsyjifen,'syyue'=>$mdsyyue,'jflist'=>$jflist,'yelist'=>$yelist));
	}
	
	public function Getsyjifen($mdid){
		global $_W,$_GPC;
	
		$sql = "SELECT number,numtime FROM " . tablename('xjfb_mendian') . " WHERE weid = :weid and id = :id";
		$mdlist = pdo_fetch($sql,array(':weid'=>$_W['uniacid'],':id'=>$mdid));
	
	
		if(!empty($mdlist)){
			if($mdlist['number'] != '0'){//from_unixtime(addtime,'%Y-%m-%d %H:%i')>=from_unixtime(:numtime, '%Y-%m-%d %H:%i') and
				$jflist = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 0 and jftype = 0 and jifen>0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdid));
								//if($_GPC['buttype']!=1&&$_GPC['jftype']!=1){
				$mdsyjifen = $mdlist['number']-$jflist;				//}
	
				return $mdsyjifen;
			}else
				return "";
		}else
			return "";
	}
	public function Getsyyue($mdid){
		global $_W,$_GPC;
	
		$sql = "SELECT number1,numtime FROM " . tablename('xjfb_mendian') . " WHERE weid = :weid and id = :id";
		$mdlist = pdo_fetch($sql,array(':weid'=>$_W['uniacid'],':id'=>$mdid));
	
	
		if(!empty($mdlist)){
			if($mdlist['number1'] != '0'){
				$jflist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 1 and jftype = 0 and yuenum >0",array(":weid"=>$_W['uniacid'],":mendian"=>$mdid));
					//if($_GPC['buttype']!=1&&$_GPC['jftype']!=1){
					$mdsyjifen = $mdlist['number1']-$jflist;
				//}
				return $mdsyjifen;
			}else
				return "";
		}else
			return "";
	}
	
	
	public function doWebMendian() {
	
		global $_W, $_GPC;
	
		
		/*//pdo_delete('qrcode',array('name'=>'zm_jfb_jf'));
		print_r(pdo_fetchall("select * from ".tablename('qrcode')));
		
		print_r(pdo_fetchcolumn('select qrcid from '.tablename("qrcode")." where uniacid = '".$_W['uniacid']."' and acid = 2 and model=1 order by qrcid asc limit 1"));
		
		
		print_r(pdo_fetchall("select * from ".tablename('rule_keyword')." where uniacid = '".$_W['uniacid']."' and module = 'zm_jfb'"));
		print_r(pdo_fetchall("select * from ".tablename('rule')." where uniacid = '".$_W['uniacid']."' and module = 'zm_jfb'"));
		print_r(pdo_fetchall("select * from ".tablename('xjfb_qrcode')." where weid = '".$_W['uniacid']."' "));
		print_r(pdo_fetchall("select * from ".tablename('qrcode')." where uniacid = '".$_W['uniacid']."' and name='zm_jfb'"));
		/*
		pdo_delete('rule',array('uniacid'=>$_W['uniacid'],'module'=>'zm_jfb'));
		pdo_delete('rule_keyword',array('uniacid'=>$_W['uniacid'],'module'=>'zm_jfb'));
		pdo_delete('xjfb_qrcode',array('weid'=>$_W['uniacid']));
		pdo_delete('qrcode',array('uniacid'=>$_W['uniacid'],'name'=>'zm_jfb'));
		*/
		
		
		
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 20;
	
	
		if(empty($_GPC['name']))
			$list = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
		else
			$list = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' and name like '%".$_GPC['name']."%' ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
	
	
	
		if (!empty($list)) {
	
			if(empty($_GPC['name']))
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC");
			else
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' and name like '%".$_GPC['name']."%' ORDER BY id DESC");
				
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
	
		include $this->template('mendian-list');
	}
	
	
	public function doWebMendianadd() {
	
		global $_W, $_GPC;
	
		$id = intval($_GPC['id']);
	
	
		if(!empty($id)){
	
			$item = pdo_fetch("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' AND id = '".$id."'");
	
		}
	
	
	
	
		include $this->template('mendian-add');
	}
	
	public function doWebMendianaddok(){
	
		global $_W,$_GPC;
	
		$id = intval($_GPC['id']);
	
	
		if(checksubmit('submit')){
	
			if(empty($_GPC['name'])){
					
				message("门店名称不能为空",$this->createWebUrl('mendian', array()),'error');
					
			}
	
			if(empty($_GPC['phone'])){
					
				message("门店电话不能为空",$this->createWebUrl('mendian', array()),'error');
					
			}
	
			$data = array(
	
					'weid' => $_W['uniacid'],
	
					'name' => $_GPC['name'],
	
					'addess' => $_GPC['addess'],
	
					'phone' => $_GPC['phone'],										
			    
			    
                    'template' => $_GPC['template'],
			    	
			        'tempmsg' => $_GPC['tempmsg'],
			    	
			        'tempcontent' => $_GPC['tempcontent'],
			    
			        'weixin' => $_GPC['weixin'],
	
					'addtime' => time(),
			    
			        'mincz' => $_GPC['mincz'],
			    
			        'mintx' => $_GPC['mintx']
	
			);
			
	
			if(empty($id)){
				pdo_insert('xjfb_mendian', $data);
	
				message('数据添加成功！', $this->createWebUrl('mendian', array()), 'success');
			}else {
				pdo_update('xjfb_mendian', $data, array('id'=>$id));
	
				message('数据修改成功！', $this->createWebUrl('mendian', array()), 'success');
			}
		}
	}
	
	public function doWebMendiandelete() {
		global $_W,$_GPC;
	
	
		$id = intval($_GPC['id']);
	
	
	
		pdo_delete('xjfb_mendian', array('id' => $id));
	
	
	
		message('删除成功！', $this->createWebUrl('mendian', array()), 'success');
	}
		
	public function doWebDianyuan() {
		global $_W,$_GPC;
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 20;
	
	
		$sql = "SELECT yg.*,md.id as mid,md.name as mendianname FROM " . tablename('xjfb_yuangong') . " as yg left join ". tablename('xjfb_mendian') ." as md on yg.mendian = md.id  WHERE yg.weid = '".$_W['uniacid']."' ORDER BY `id` DESC ";
	
		$list = pdo_fetchall($sql);
	
		if (!empty($list)) {
	
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_yuangong')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC");
	
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
	
		include $this->template('dianyuan-list');
	}
	
	
	public function doWebDianyuanadd() {
		global $_W,$_GPC;
	
	
		$id = intval($_GPC['id']);
	
	
		if(!empty($id)){
	
			$item = pdo_fetch("SELECT * FROM ".tablename('xjfb_yuangong')." WHERE weid = '".$_W['uniacid']."' AND id = '".$id."'");
	
		}
	
		$sql = "SELECT id,name FROM " . tablename('xjfb_mendian') . " WHERE weid = '".$_W['uniacid']."' ORDER BY `id` DESC ";
	
		$list = pdo_fetchall($sql);
	
	
	
	
		include $this->template('dianyuan-add');
	}
	
	
	public function doWebDianyuanaddok() {
		global $_W,$_GPC;
	
		$id = intval($_GPC['id']);
	
	
		if(checksubmit('submit')){
	
			if(empty($_GPC['name'])){
					
				message("店员姓名不能为空",$this->createWebUrl('dianyuan', array()), 'error');
					
			}
			if(empty($_GPC['phone'])){
					
				message("店员电话不能为空",$this->createWebUrl('dianyuan', array()), 'error');
					
			}
			if(empty($_GPC['weixin'])){
					
				message("店员微信不能为空",$this->createWebUrl('dianyuan', array()), 'error');
					
			}
			if($_GPC['ddlmendian'] == "0"){
					
				message("请选择门店",$this->createWebUrl('dianyuan', array()), 'error');
					
			}
	
			/*
			if(empty($id)){
					
				$random = $this->random(4);
	
				$random = $this->checkRandom($random);
					
			}else{
					
				$random = $_GPC['flag'];
					
			}			*/
	
			$data = array(
	
					'weid' => $_W['uniacid'],
	
					'name' => $_GPC['name'],
	
					'mendian' => $_GPC['ddlmendian'],
	
					'phone' => $_GPC['phone'],
	
					'weixin' => $_GPC['weixin'],
	
					'flag' => 1,
						
					'addyue' => $_GPC['addyue'],
			    
    			    'hsjifen' => $_GPC['hsjifen'],
    			    
    			    'hsyue' => $_GPC['hsyue'],
	
					'addtime' => time()
	
			);
	
			if(empty($id)){
				pdo_insert('xjfb_yuangong', $data);
	
				message('数据添加成功！', $this->createWebUrl('dianyuan', array()), 'success');
	
					
			}else {
				pdo_update('xjfb_yuangong', $data, array('id'=>$id));
					
				message('数据修改成功！', $this->createWebUrl('dianyuan', array()), 'success');
			}
		}
	
	
	}
	
	
	public function doWebDianyuandelete() {
		global $_W,$_GPC;
	
		$id = intval($_GPC['id']);
	
	
	
		pdo_delete('xjfb_yuangong', array('id' => $id));
		pdo_delete('xjfb_jifenlist', array('dianyuan' => $id));
	
	
		message('删除成功！', $this->createWebUrl('dianyuan', array()), 'success');
	}
	
	
	public function doWebyewu(){
		global $_W,$_GPC;
	
	
		$mendian = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC  ");
	
		$dopost = $_GPC['dopost'];
		if($dopost=='save'){
	
			$id = $_GPC['id'];
	
			pdo_update('xjfb_jifenjilu',array('content'=>$_GPC['mark']),array('id'=>$id));
	
			exit;
	
		}
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 15;
	
		if(!empty($_GPC['ddlmendian'])){
			$sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_jifenjilu').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.dianyuan left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.codetype = 0  ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
		}else{
			$sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_jifenjilu').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.dianyuan left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.codetype = 0 ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
		}
	
	
	
		if (!empty($list)) {
	
			if(!empty($_GPC['ddlmendian'])){
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifenjilu')." WHERE weid = :weid and mendian = :mendian and codetype = 0 ",array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}else{
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifenjilu')." WHERE weid = :weid and codetype = 0",array(":weid" => $_W['uniacid']));
			}
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
	
	
		if($_GPC['op'] == 'ywexcel'){
			$name = '积分记录';
				
			$title = array(array('name'=>'门店','width'=>40),array('name'=>'店员','width'=>30),array('name'=>'昵称','width'=>50),array('name'=>'获得积分'),array('name'=>'获得时间','width'=>50));
			if(empty($_GPC['ddlmendian']) || $_GPC['ddlmendian'] == 0){
				$sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.jifen,j.jftype,FROM_UNIXTIME(j.addtime,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.dianyuan left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.codetype = 0 ORDER BY j.addtime DESC ";
				$list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid']));
			}else{
				$sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.jifen,j.jftype,FROM_UNIXTIME(j.addtime,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.dianyuan left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.codetype = 0 ORDER BY j.addtime DESC ";
				$list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}
				
				
			foreach ($list1 as $key => $value) {
					
				$i = 0;
				if($value['jftype'] == 1)
					$value['jifen'] = '-'.$value['jifen'];
				else
					$value['jifen'] = $value['jifen'];
	
				foreach ($value as $k => $v) {
						
					$data[$key][$i]= $v ;
						
					$i++;
						
				}
					
			}
				
			$this->_pushExcel($title,$data,$name);
				
		}
	
		include $this->template('yewu-jilu');
	}
	
	public function doWebyewujilu1(){
		global $_W,$_GPC;
	
	
		$mendian = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC  ");
	
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 15;
	
		if(!empty($_GPC['ddlmendian'])){
			$sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_jifenjilu').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.dianyuan left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.codetype = 1 ORDER BY j.addtime1 DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
		}else{
			$sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_jifenjilu').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.dianyuan left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.codetype = 1 ORDER BY j.addtime1 DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
		}
	
	
	
		if (!empty($list)) {
	
			if(!empty($_GPC['ddlmendian'])){
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifenjilu')." WHERE weid = :weid and mendian = :mendian and codetype = 1",array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}else{
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifenjilu')." WHERE weid = :weid and codetype = 1",array(":weid" => $_W['uniacid']));
			}
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
	
		if($_GPC['op'] == 'czexcel'){
			$name = '余额记录';
				
				
			$title = array(array('name'=>'门店','width'=>40),array('name'=>'店员','width'=>30),array('name'=>'昵称','width'=>50),array('name'=>'获得余额'),array('name'=>'获得时间','width'=>50));
			if(!empty($_GPC['ddlmendian'])|| $_GPC['ddlmendian'] == 0){
				$sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.yuenum,FROM_UNIXTIME(j.addtime1,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.dianyuan left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.codetype = 1 ORDER BY j.addtime DESC";
				$list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}else{
				$sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.yuenum,FROM_UNIXTIME(j.addtime1,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.dianyuan left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.codetype = 1 ORDER BY j.addtime DESC LIMIT ";
				$list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid']));
			}
			foreach ($list1 as $key => $value) {
					
				$i = 0;
					
				foreach ($value as $k => $v) {
	
					$data[$key][$i]= $v ;
	
					$i++;
	
				}
					
			}
	
			$this->_pushExcel($title,$data,$name);
	
		}
	
	
		include $this->template('yewu-jilu1');
	}
	public function doWebRecover(){		
	    global $_W,$_GPC;						
	    
	    $mendian = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC  ");
		
		
		$pindex = max(1, intval($_GPC['page']));
		
		$psize = 15;
		
		if(!empty($_GPC['ddlmendian'])){
			$sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_recover').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.yuangong left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.type = 0 ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
		}else{
			$sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_recover').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.yuangong left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.type = 0 ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
		}
		
		
		
		if (!empty($list)) {
		
			if(!empty($_GPC['ddlmendian'])){
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_recover')." WHERE weid = :weid and mendian = :mendian and type = 0",array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}else{
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_recover')." WHERE weid = :weid and type = 0",array(":weid" => $_W['uniacid']));
			}
			$pager = pagination($total, $pindex, $psize);
		
			unset($row);
		
		}
		
		if($_GPC['op'] == 'jfexcel'){
			$name = '积分回收记录';
		
		
			$title = array(array('name'=>'门店','width'=>40),array('name'=>'店员','width'=>30),array('name'=>'昵称','width'=>50),array('name'=>'回收积分'),array('name'=>'回收时间','width'=>50));
			if(!empty($_GPC['ddlmendian'])|| $_GPC['ddlmendian'] == 0){
				$sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.number,FROM_UNIXTIME(j.addtime,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_recover')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.yuangong left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.type = 0 ORDER BY j.addtime DESC";
				$list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}else{
				$sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.number,FROM_UNIXTIME(j.addtime,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_recover')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.yuangong left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.type = 0 ORDER BY j.addtime DESC LIMIT ";
				$list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid']));
			}
			foreach ($list1 as $key => $value) {
					
				$i = 0;
					
				foreach ($value as $k => $v) {
		
					$data[$key][$i]= $v ;
		
					$i++;
		
				}
					
			}
		
			$this->_pushExcel($title,$data,$name);
		
		}
						
		include $this->template('recoverlist');	
	}		
						
	public function doWebRecover1(){
		global $_W,$_GPC;
				
		$mendian = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC  ");	

		$pindex = max(1, intval($_GPC['page']));	

		$psize = 15;	

		if(!empty($_GPC['ddlmendian'])){	
		    $sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_recover').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.yuangong left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.type = 1 ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";	
		    
		    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));		
		}else{			
		    
		    $sql = 'select j.*,m.nickname,y.id as yid,y.name as ygname,d.id as did,d.name as mdname from '.tablename('xjfb_recover').' as j left join '.tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong').' as y on y.id = j.yuangong left join '.tablename('xjfb_mendian').' as d on d.id = j.mendian WHERE j.weid = :weid and j.type = 1 ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";			
		    
		    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));		
		}								
		
		if (!empty($list)) {					
		    
		    if(!empty($_GPC['ddlmendian'])){				
		        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_recover')." WHERE weid = :weid and mendian = :mendian and type = 1",array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));			
		    }else{				
		        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_recover')." WHERE weid = :weid and type = 1",array(":weid" => $_W['uniacid']));			
		    }			
		    
		    $pager = pagination($total, $pindex, $psize);					
		    
		    unset($row);				
		}				
		
		if($_GPC['op'] == 'yeexcel'){			
		    $name = '余额回收记录';							
		    $title = array(array('name'=>'门店','width'=>40),array('name'=>'店员','width'=>30),array('name'=>'昵称','width'=>50),array('name'=>'回收余额'),array('name'=>'回收时间','width'=>50));			
		    
		    if(!empty($_GPC['ddlmendian'])|| $_GPC['ddlmendian'] == 0){				
		        $sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.number,FROM_UNIXTIME(j.addtime,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_recover')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.yuangong left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.mendian = :mendian and j.type = 1 ORDER BY j.addtime DESC";				
		        $list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));			
		    }else{				
		        $sql1 = "select d.name as mdname,y.name as ygname,m.nickname,j.number,FROM_UNIXTIME(j.addtime,'%Y-%m-%d %H:%i:%s') from ".tablename('xjfb_recover')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.yuangong left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE j.weid = :weid and j.type = 1 ORDER BY j.addtime DESC LIMIT ";				
		        $list1 = pdo_fetchall($sql1,array(":weid" => $_W['uniacid']));			
		    }			
		    foreach ($list1 as $key => $value) {									
		        $i = 0;									
		        foreach ($value as $k => $v) {							
		            $data[$key][$i]= $v ;							
		            $i++;						
		        }								
		    }					
		    $this->_pushExcel($title,$data,$name);				
		}
	
		include $this->template('recoverlist1');
	}	
	public function doWebZqadd(){
		global $_W,$_GPC;
	
		$item = pdo_fetch('select * from '.tablename('xjfb_zqsetting').'where weid = :weid',array(":weid" => $_W['uniacid']));
	
	
		include $this->template('zqadd');
	}
	
	public function doWebZqaddok(){
		global $_W,$_GPC;
	
		$setting = pdo_fetch('select * from '.tablename('xjfb_zqsetting').'where weid = :weid',array(":weid" => $_W['uniacid']));
	
	
		if (checksubmit('submit')) {
	
			$data = array(
					'weid' => $_W['uniacid'],
	
					'template' => trim($_GPC['template']),
	
					'msgcon' => trim($_GPC['msgcon']),
	
					'turl' => trim($_GPC['turl']),
	
					'tbottom' => trim($_GPC['tbottom'])
			);
	
			if (!empty($setting)) {
	
				pdo_update('xjfb_zqsetting',$data,array('id'=>$setting['id']));
				//DBUtil::updateById(DBUtil::$TABLE_WKJ_SETTING, $data, $kjsetting['id']);
	
			} else {
	
				pdo_insert('xjfb_zqsetting',$data);
				//DBUtil::create(DBUtil::$TABLE_WKJ_SETTING, $data);
	
			}
	
			message('参数设置成功！', $this->createWebUrl('zqlist', array('op' => 'display')), 'success');
		}
	
	
	}
	
	public function doWebZhouqilist(){
		global $_W,$_GPC;
	
	
		$start = $_GPC['start'];
		
		/*
		if(!empty($_GPC['start'])){
			$start = strtotime("-".$_GPC['start']." day");
		}
		*/
		
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 10;
	
		if(empty($_GPC['start'])){
			$sql = 'select m.nickname,m.realname,m.telephone,j.weid,j.mcid,j.addtime,j.bzcon from (select j.weid,j.mcid,j.addtime,j.bzcon from '.tablename('xjfb_jifenjilu').' as j order by j.addtime desc) as j,'.tablename('mc_members').' as m WHERE m.uid = j.mcid and j.weid = :weid group by j.mcid ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
	
			$_list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
		}else{
			$sql = 'select m.nickname,m.realname,m.telephone,j.weid,j.mcid,j.addtime,j.bzcon from (select j.weid,j.mcid,j.addtime,j.bzcon from '.tablename('xjfb_jifenjilu').' as j order by j.addtime desc) as j,'.tablename('mc_members').' as m WHERE m.uid = j.mcid and j.weid = :weid and DATEDIFF(date_format(now(),"%Y-%m-%d"),from_unixtime(j.addtime,"%Y-%m-%d")) > :start group by j.mcid ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$_list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":start" => $start));
				
		}
	

		if (!empty($_list)) {
	
			if(empty($_GPC['start'])){
				$total = pdo_fetchall("SELECT j.id FROM (select j.id,j.weid,j.mcid,j.addtime,j.bzcon from ".tablename('xjfb_jifenjilu')." as j ) as j,".tablename('mc_members')." as m  WHERE m.uid = j.mcid and j.weid = :weid group by j.mcid ",array(":weid" => $_W['uniacid']));
			}else{
			    $total = pdo_fetchall("SELECT j.id FROM (select j.id,j.weid,j.mcid,j.addtime,j.bzcon from ".tablename('xjfb_jifenjilu')." as j ) as j,".tablename('mc_members')." as m  WHERE m.uid = j.mcid and j.weid = :weid and DATEDIFF(date_format(now(),'%Y-%m-%d'),from_unixtime(j.addtime,'%Y-%m-%d')) > :start group by j.mcid ",array(":weid" => $_W['uniacid'],":start" => $start));
			    
			}
			
			$pager = pagination(count($total), $pindex, $psize);
	
			
		}
	
		$dopost = $_GPC['dopost'];
	
		//$this->SendTemplate('324294','11');
	
		if($dopost=='save'){
	
			$id = $_GPC['id'];
	
			pdo_update('xjfb_jifenjilu',array('bzcon'=>$_GPC['mark']),array('id'=>$id));
	
			exit;
	
		}
		/*if($dopost=='send'){
	
			$id = $_GPC['id'];
	
	
	
			$this->SendTemplate($id,$_GPC['mark']);
	
			exit;
	
		}*/
	
		include $this->template('zhouqilist');
	}
	
	public function doWebZqxq(){
	    global $_W,$_GPC;
	    
	    if(!empty($_GPC['id'])){
	        
	        $pindex = max(1, intval($_GPC['page']));
	        
	        $psize = 10;
	        
    	    $sql = "select m.nickname,m.realname,m.telephone,j.weid,j.mcid,j.jifen,j.jftype,j.codetype,j.yuenum,j.addtime,j.addtime1,j.bzcon from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid WHERE j.weid = :weid and j.mcid in ('".$_GPC['id']."') ORDER BY j.addtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}";
    	    
    	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	    
    	    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifenjilu')." as j where j.weid = :weid and j.mcid in ('".$_GPC['id']."') ",array(":weid" => $_W['uniacid']));
    	    	
    	    $pager = pagination($total, $pindex, $psize);
	    }
	    
	
	    include $this->template('zqxq');
	}
	
	public function doWebCzlist(){
		global $_W,$_GPC;
	
	
		$mendian = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC  ");
	
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 15;
	
		if(!empty($_GPC['ddlmendian'])){
			$sql = "SELECT c.number,c.numtime,c.scoure,m.name FROM " . tablename('xjfb_jifencz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."' and m.id = ".$_GPC['ddlmendian']." ORDER BY `numtime` DESC ";
	
			$list = pdo_fetchall($sql);
		}else{
			$sql = "SELECT c.number,c.numtime,c.scoure,m.name FROM " . tablename('xjfb_jifencz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."' ORDER BY `numtime` DESC ";
				
			$list = pdo_fetchall($sql);
		}
	
		if (!empty($list)) {
	
			if(!empty($_GPC['ddlmendian'])){
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifencz')." WHERE weid = :weid and mendian = :mendian ",array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}else{
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifencz')." WHERE weid = :weid  ",array(":weid" => $_W['uniacid']));
			}
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
	
	
		if($_GPC['op'] == 'jfexcel'){
			$name = '积分记录';
	
			$title = array(array('name'=>'门店','width'=>40),array('name'=>'充值积分'),array('name'=>'获得时间','width'=>50));
			if(empty($_GPC['ddlmendian']) || $_GPC['ddlmendian'] == 0){
				$sql1 = "SELECT m.name,c.number,FROM_UNIXTIME(c.numtime,'%Y-%m-%d %H:%i:%s') FROM " . tablename('xjfb_jifencz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."' ORDER BY c.numtime DESC ";
					
				$list1 = pdo_fetchall($sql1);
	
			}else{
				$sql1 = "SELECT m.name,c.number,FROM_UNIXTIME(c.numtime,'%Y-%m-%d %H:%i:%s') FROM " . tablename('xjfb_jifencz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."' and m.id = ".$_GPC['ddlmendian']." ORDER BY c.numtime DESC ";
	
				$list1 = pdo_fetchall($sql1);
			}
	
	
			foreach ($list1 as $key => $value) {
					
				$i = 0;
					
				foreach ($value as $k => $v) {
	
					$data[$key][$i]= $v ;
	
					$i++;
	
				}
					
			}
	
			$this->_pushExcel($title,$data,$name);
	
		}
	
	
	
	
		include $this->template('czlist');
	}
	
	public function doWebCzadd(){
		global $_W,$_GPC;
	
	
		$sql = "SELECT id,name FROM " . tablename('xjfb_mendian') . " WHERE weid = '".$_W['uniacid']."' ORDER BY `id` DESC ";
	
		$list = pdo_fetchall($sql);
	
		include $this->template('czadd');
	}
	
	public function doWebCzaddok(){
		global $_W,$_GPC;
		$jifennum = 0;
		$jifennum = pdo_fetchcolumn('select number from '.tablename('xjfb_mendian').' where weid = :weid and id = :id',array(':weid' => $_W['uniacid'],':id' => $_GPC['ddlmendian']));
	
	
		if($jifennum >= 0)
			$jifennum = $jifennum + $_GPC['number'];
	
		$data = array(
				'weid' => $_W['uniacid'],
	
				'mendian' => $_GPC['ddlmendian'],
	
				'number' => $_GPC['number'],
		    
		        'scoure' => 0,
	
				'numtime' => TIMESTAMP
		);
	
		pdo_insert('xjfb_jifencz',$data);
	
		pdo_update('xjfb_mendian',array('number'=>$jifennum,'numtime'=>TIMESTAMP),array('id'=>$_GPC['ddlmendian']));
	
		message('积分设置成功！', $this->createWebUrl('czlist', array('op' => 'display')), 'success');
	}
			
	public function doWebCzlist1(){
		global $_W,$_GPC;
	
	
		$mendian = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC  ");
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 15;
	
		if(!empty($_GPC['ddlmendian'])){
			$sql = "SELECT c.number,c.numtime,c.scoure,m.name FROM " . tablename('xjfb_yuecz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."'  and m.id = ".$_GPC['ddlmendian']." ORDER BY `numtime` DESC ";
	
			$list = pdo_fetchall($sql);
		}else{
			$sql = "SELECT c.number,c.numtime,c.scoure,m.name FROM " . tablename('xjfb_yuecz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."' ORDER BY `numtime` DESC ";
				
			$list = pdo_fetchall($sql);
		}
	
		if (!empty($list)) {
				
			if(!empty($_GPC['ddlmendian'])){
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_yuecz')." WHERE weid = :weid and mendian = :mendian ",array(":weid" => $_W['uniacid'],":mendian" => $_GPC['ddlmendian']));
			}else{
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_yuecz')." WHERE weid = :weid ",array(":weid" => $_W['uniacid']));
			}
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
	
		if($_GPC['op'] == 'yeexcel'){
			$name = '余额记录';
	
			$title = array(array('name'=>'门店','width'=>40),array('name'=>'充值余额'),array('name'=>'获得时间','width'=>50));
			if(empty($_GPC['ddlmendian']) || $_GPC['ddlmendian'] == 0){
				$sql1 = "SELECT m.name,c.number,FROM_UNIXTIME(c.numtime,'%Y-%m-%d %H:%i:%s') FROM " . tablename('xjfb_yuecz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."' ORDER BY c.numtime DESC ";
					
				$list1 = pdo_fetchall($sql1);
			}else{
				$sql1 = "SELECT m.name,c.number,FROM_UNIXTIME(c.numtime,'%Y-%m-%d %H:%i:%s') FROM " . tablename('xjfb_yuecz') . " as c left join " . tablename('xjfb_mendian') . " as m on m.id = c.mendian WHERE c.weid = '".$_W['uniacid']."' and m.id = ".$_GPC['ddlmendian']." ORDER BY c.numtime DESC ";
	
				$list1 = pdo_fetchall($sql1);
			}
	
	
			foreach ($list1 as $key => $value) {
					
				$i = 0;
					
				foreach ($value as $k => $v) {
	
					$data[$key][$i]= $v ;
	
					$i++;
	
				}
					
			}
	
			$this->_pushExcel($title,$data,$name);
	
		}
	
	
		include $this->template('czlist1');
	}
	
	public function doWebCzadd1(){
		global $_W,$_GPC;
	
	
		$sql = "SELECT id,name FROM " . tablename('xjfb_mendian') . " WHERE weid = '".$_W['uniacid']."' ORDER BY `id` DESC ";
	
		$list = pdo_fetchall($sql);
	
		include $this->template('czadd1');
	}
	
	public function doWebCzaddok1(){
		global $_W,$_GPC;
	
		$jifennum = 0;
		$jifennum = pdo_fetchcolumn('select number1 from '.tablename('xjfb_mendian').' where weid = :weid and id = :id',array(':weid' => $_W['uniacid'],':id' => $_GPC['ddlmendian']));
	
		if($jifennum >= 0)
			$jifennum = $jifennum + $_GPC['number'];
	
		$data = array(
				'weid' => $_W['uniacid'],
	
				'mendian' => $_GPC['ddlmendian'],
	
				'number' => $_GPC['number'],
		    
		        'scoure' => 0,
	
				'numtime' => TIMESTAMP
		);
	
		pdo_insert('xjfb_yuecz',$data);
	
		pdo_update('xjfb_mendian',array('number1'=>$jifennum,'numtime'=>TIMESTAMP),array('id'=>$_GPC['ddlmendian']));
	
		message('余额设置成功！', $this->createWebUrl('czlist1', array('op' => 'display')), 'success');
	}
	public function doWebhylist(){
		global $_W,$_GPC;
	

		if($_GPC['opp'] == "getmd"){
		    $uid = $_GPC['uid'];
		
		    $list = pdo_fetchall("select m.name,FROM_UNIXTIME(j.addtime,'%Y-%c-%d %h:%i:%s') as addtime from ".tablename('xjfb_mendian')." as m left join ".tablename('xjfb_jifenjilu')." as j on j.mendian = m.id where j.weid = ".$_W['uniacid']." and j.mcid = ".$uid." group by m.name");
		    
		    
		    echo json_encode($list);
		    exit();
		}
		if($_GPC['opp'] == "getuser"){
		    
		    $uid = $_GPC['uid'];
		    $type = $_GPC['type'];
		    
		    $member = pdo_fetch("select m.uid,m.realname,m.groupid,m.credit1,m.credit2,g.title from ".tablename('mc_members')." as m left join ".tablename('mc_groups')." as g on g.groupid = m.groupid where m.uniacid = :uniacid and m.uid = :uid",array(':uniacid'=>$_W['uniacid'],":uid"=>$uid));
		    
		    echo json_encode(array("uid"=>$member['uid'],"realname"=>$member['realname'],"credit1"=>$member['credit1'],"credit2"=>$member['credit2'],"title"=>$member['title']));
		    
		    exit;
		}
		if($_GPC['opp'] == "saveuser"){
		    $uid = $_GPC['uid'];
		    $type = $_GPC['type'];
		    $credit1 = $_GPC['num'];
		    $credit2 = $_GPC['num1'];
		    $remark = $_GPC['remark'];
		    $remark1 = $_GPC['remark1'];
		    
		    
		    load()->model('mc');
		    if($type == 1){
		        mc_credit_update($uid,'credit1',$credit1,array($uid,"云会员后台修改积分".$credit1));
		        echo json_encode("jifen");
		    }
		    else {
		        mc_credit_update($uid,'credit2',$credit2,array($uid,"云会员后台修改余额".$credit2));
		        echo json_encode("yue");
		    }
		    
		    exit;
		}
		
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 15;
	
		//$topfans = pdo_fetch("select m.uid,m.nickname,m.realname,m.mobile,m.avatar,m.credit1,m.credit2,j.* from ".tablename('mc_mapping_fans')." as f left join ".tablename('mc_members')." as m on f.uid = m.uid left join".tablename('xjfb_member')." as j on j.uid = m.uid where j.weid = ".$_GPC['i']." and f.openid = ".$topfans['uid']." limit 1");
		 
		if(!empty($_GPC['hyname'])){
			$sql = 'select j.id,j.iflqhy,m.uid,m.avatar,m.nickname,m.createtime,m.credit1,m.credit2,m.realname,m.mobile from '.tablename('mc_members').' as m left join '.tablename('xjfb_member').' as j on m.uid = j.uid  WHERE j.weid = :weid  and m.nickname = :nickname GROUP BY m.nickname ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid'],":nickname" => $_GPC['hyname']));
		}else{
			$sql = 'select j.id,j.iflqhy,m.uid,m.avatar,m.nickname,m.createtime,m.credit1,m.credit2,m.realname,m.mobile from '.tablename('mc_members').' as m left join '.tablename('xjfb_member').' as j on m.uid = j.uid  WHERE j.weid = :weid GROUP BY m.nickname ORDER BY j.addtime DESC LIMIT '.($pindex - 1) * $psize.",{$psize}";
			$list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
		}
	
		if (!empty($list)) {
				
			if(!empty($_GPC['hyname'])){
				$total = pdo_fetchcolumn("SELECT COUNT(distinct m.nickname) FROM ".tablename('mc_members')." as m left join ".tablename('xjfb_member')." as j on m.uid = j.uid WHERE j.weid = :weid and m.nickname = :nickname ORDER BY j.addtime DESC ",array(":weid" => $_W['uniacid'],":nickname" => $_GPC['hyname']));
			}else{
				$total = pdo_fetchcolumn("SELECT COUNT(distinct m.nickname) FROM ".tablename('mc_members')." as m left join ".tablename('xjfb_member')." as j on m.uid = j.uid WHERE j.weid = :weid  ORDER BY j.addtime DESC ",array(":weid" => $_W['uniacid']));
			}
				
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
		
		/*
		foreach ($list as $index => $row) {
		    if (!empty($row['lcate']) && $row['id'] == $row['lcate']) {
		        $children[$row['lcate']][] = $row;
		        unset($kind[$index]);
		    }
		}
		*/
		
		
	
		include $this->template('hylist');
	}
	public function doWebhydel(){
	    global $_W,$_GPC;
	    
	    pdo_delete("xjfb_member",array("id"=>$_GPC['id']));
	    
	    pdo_delete("mc_card_members",array("uid"=>$_GPC['uid']));
	    
	    message('删除成功！', referer(), 'success');
	
	}
	public function gethy_jfnum($id){
		global $_W,$_GPC;
	
		$jflist = pdo_fetchcolumn("select SUM(jifen) FROM ".tablename('xjfb_jifenjilu')." where id =".$id." and jftype = 0 and weid = ".$_W['uniacid']."");
	
		$jflist1 = pdo_fetchcolumn("select SUM(jifen) FROM ".tablename('xjfb_jifenjilu')." where id =".$id." and jftype = 1 and weid = ".$_W['uniacid']."");
	
		return $jflist - $jflist1;
	}
	
	public function doWebhylists(){
		global $_W,$_GPC;
	
	
		$pindex = max(1, intval($_GPC['page']));
	
		$psize = 15;
	
	
		$sql = "select j.jifen,j.addtime,j.jftype,j.yuenum,j.addtime1,j.codetype,m.nickname,y.name as ygname,d.name as mdname from ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members').' as m on m.uid = j.mcid left join '.tablename('xjfb_yuangong')." as y on y.id = j.dianyuan left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE m.nickname = :nickname and  j.weid = :weid ORDER BY j.id DESC LIMIT ".($pindex - 1) * $psize.",{$psize}";
		$list = pdo_fetchall($sql,array(":nickname" => $_GPC['nick'],":weid" => $_W['uniacid']));
	
	
	
		if (!empty($list)) {
	
				
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_jifenjilu')." as j left join ".tablename('mc_members')." as m on m.uid = j.mcid left join ".tablename('xjfb_yuangong')." as y on y.id = j.dianyuan left join ".tablename('xjfb_mendian')." as d on d.id = j.mendian WHERE m.nickname = :nickname and j.weid = :weid",array(":nickname" => $_GPC['nick'],":weid" => $_W['uniacid']));
				
			$pager = pagination($total, $pindex, $psize);
	
			unset($row);
	
		}
	
		include $this->template('hylists');
	}
	
	public function doWebShitilist(){
	    global $_W,$_GPC;
	    
	    $mendian = pdo_fetchall("SELECT * FROM ".tablename('xjfb_mendian')." WHERE weid = '".$_W['uniacid']."' ORDER BY id DESC  ");
	    
	    if($_GPC['opp'] == "getmd"){
	        $uid = $_GPC['uid'];
	    
	        $list = pdo_fetchall("select m.name,FROM_UNIXTIME(j.addtime,'%Y-%c-%d %h:%i:%s') as addtime from ".tablename('xjfb_mendian')." as m left join ".tablename('xjfb_jifenjilu')." as j on j.mendian = m.id where j.weid = ".$_W['uniacid']." and j.mcid = ".$uid." group by m.name");
	    
	    
	        echo json_encode($list);
	        exit();
	    }
	    
	    if(checksubmit()) {
	         
	        if (!empty($_FILES['im']['name'])) {
	            $file_types = explode(".", $_FILES['im']['name']);

                $excel_type = array('xls', 'csv', 'xlsx');

                //判断是不是excel文件

                if (!in_array(strtolower(end($file_types)), $excel_type)) {

                    message("不是Excel文件，重新上传", $this->createWebUrl('shitilist'),'info');

                }

                $str = date ( 'Ymdhis' );

                $file_name = $str.".".end($file_types);

                load()->func('file');

                file_move($_FILES['im']['tmp_name'], MODULE_ROOT . '/' . $_W['uniacid'] .'.'.end($file_types));

                $data = MODULE_ROOT . '/' . $_W['uniacid'] .'.'.end($file_types);

                if(end($file_types)=='xls'){

                    $objReader = PHPExcel_IOFactory::createReader('Excel5');

                }elseif(end($file_types)=='xlsx'){

                    $objReader = PHPExcel_IOFactory::createReader('Excel2007');

                }elseif(end($file_types)=='csv'){

                    $objReader = PHPExcel_IOFactory::createReader('CSV');

                }

                $objReader->setReadDataOnly(true);

                $objPHPExcel = $objReader->load($data);

                $objWorksheet = $objPHPExcel->getActiveSheet();

                $highestRow = $objWorksheet->getHighestRow();

                $highestColumn = $objWorksheet->getHighestColumn();

                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                $excelData = array();

                for ($row = 2; $row <= $highestRow; $row++) {

                    for ($col = 0; $col < $highestColumnIndex; $col++) {

                        $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

                    }

                }

                unlink($data); //删除excel文件

	           foreach ($excelData as $k => $v){
	               
	                $excelData[$k]['weid'] = $_W['uniacid'];

                    $excelData[$k]['name'] = $excelData[$k][0];

                    unset($excelData[$k][0]);

                    if(!is_numeric($excelData[$k][1])){
                        message("手机号必须是11位数字",$this->createWebUrl("shitilist"),'error');
                        exit;
                    }
                    $excelData[$k]['phone'] = $excelData[$k][1];

                    unset($excelData[$k][1]);
                    
                    $excelData[$k]['cardmember'] = $excelData[$k][2];

                    unset($excelData[$k][2]);

                    if(!is_numeric($excelData[$k][1])){
                        message("积分必须是数字",$this->createWebUrl("shitilist"),'error');
                        exit;
                    }
                    $excelData[$k]['credit1'] = $excelData[$k][3];
                    
                    unset($excelData[$k][3]);

                    if(!is_numeric($excelData[$k][1])){
                        message("余额必须是数字",$this->createWebUrl("shitilist"),'error');
                        exit;
                    }
                    $excelData[$k]['credit2'] = $excelData[$k][4];
                    
                    unset($excelData[$k][4]);

                    $excelData[$k]['group'] = $excelData[$k][5];
                    
                    unset($excelData[$k][5]);
                    
                    $excelData[$k]['addtime'] = time();
                    
                }
                
                foreach ($excelData as $v){
                
                    pdo_insert("xjfb_entity",$v);
                
                }
                
	        }
	    }
	    
	    $pindex = max(1, intval($_GPC['page']));
	     
	    $psize = 15;
	     
	     
	    $sql = "select * from ".tablename('xjfb_entity')."  WHERE weid = :weid ORDER BY addtime desc LIMIT ".($pindex - 1) * $psize.",{$psize}";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	     
	     
	     
	    if (!empty($list)) {
	         
	         
	        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_entity')."  WHERE weid = :weid",array(":weid" => $_W['uniacid']));
	         
	        $pager = pagination($total, $pindex, $psize);
	         
	        unset($row);
	         
	    }
	    
	    include $this->template('shitilist');
	}
	
	public function doWebShitiadd(){
	    global $_W,$_GPC;
	    
	    $list = pdo_fetchall("select g.* from ".tablename('mc_groups')." as g where g.uniacid = :uniacid order by credit asc ",array(":uniacid"=>$_W['uniacid']));
	     
	    if(!empty($_GPC['id']))
	        $list = pdo_fetch("select * from ".tablename('xjfb_entity')." where weid = :weid and id = :id",array(":weid"=>$_W['uniacid'],":id"=>$_GPC['id']));
	    
	    include $this->template('shitiadd');
	}
	
	public function doWebShitiaddok(){
	    global $_W,$_GPC;
	     
	    $list = pdo_fetch("select * from ".tablename('xjfb_entity')." where weid = ".$_W['uniacid']." and phone=".$_GPC['phone']);
	    
	    if(!empty($list)){
	        message("已经存在！",$this->createWebUrl("shitilist"),"error");
	        exit;
	    }

	    $data = array(
	        'weid' => $_W['uniacid'],
	        'name' => $_GPC['name'],
	        'phone' => $_GPC['phone'],
	        'cardmember' => $_GPC['cardmember'],
	        'credit1' => $_GPC['credit1'],
	        'credit2' => $_GPC['credit2'],
	        'group' => $_GPC['groupid'],
	        'addtime' => TIMESTAMP
	    );
	    
	    pdo_insert('xjfb_entity',$data);
	    
	    message("保存成功！",$this->createWebUrl("shitilist"),"success");
	    
	}
	
	public function doWebShitidel(){
	    global $_W,$_GPC;
	    
	    pdo_delete("xjfb_entity",array("id"=>$_GPC['id']));
	    message("删除成功！",$this->createWebUrl("shitilist"),"success");
	}
	
	
	public function doWebGrouplist(){
	   
	    global $_W,$_GPC;
	    
	    $pindex = max(1, intval($_GPC['page']));
	    
	    $psize = 15;
	    
	    
	    $sql = "select g.*,i.groupid as gid,i.topimg from ".tablename('mc_groups')." as g left join ".tablename('xjfb_group')." as i on g.groupid = i.groupid WHERE g.uniacid = :uniacid ORDER BY g.credit asc LIMIT ".($pindex - 1) * $psize.",{$psize}";
	    $list = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid']));
	    
	    
	    
	    if (!empty($list)) {
	    
	    
	        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('mc_groups')."  WHERE uniacid = :uniacid",array(":uniacid" => $_W['uniacid']));
	    
	        $pager = pagination($total, $pindex, $psize);
	    
	        unset($row);
	    
	    }
	    
	    
	    include $this->template('grouplist');
	}
	
	public function doWebGroupadd(){
	
	    global $_W,$_GPC;
	    
	    $item = pdo_fetch("select g.*,i.topimg from ".tablename('mc_groups')." as g left join ".tablename('xjfb_group')." as i on g.groupid = i.groupid where g.uniacid = :uniacid and g.groupid = :groupid",array(":uniacid"=>$_W['uniacid'],":groupid"=>$_GPC['id']));
	    
	    
	    include $this->template('groupadd');
	    
	}
	public function doWebGroupaddok(){
	
	    global $_W,$_GPC;
	     
	    if (checksubmit('submit')) {
	        $group = pdo_fetchcolumn("SELECT groupid FROM ".tablename('mc_groups')." ORDER BY groupid DESC");
    	     $_data = array(
                
    	         "uniacid" => $_W['uniacid'],
    	         
    	         "title" => $_GPC['title'],
    	         
    	         "credit" => $_GPC['credit']
    	     );
    	     $data = array(
    	         "weid" => $_W['uniacid'],
    	         
    	         "groupid" => $_GPC['id'],
    
    	         "topimg" => $_GPC['topimg'],
    	         
    	         "addtime" => time()
    	     );
    	     
    	     if(!empty($_GPC['id'])){
    	         pdo_update('mc_groups',$_data,array('groupid'=>$_GPC['id']));
    
    	     }
    	     else{ 
    	         $_data['groupid'] = $group + 1;
    	         
    	         pdo_insert('mc_groups',$_data);
    	         $groupid = pdo_insertid();
    	             	         
    	         $data['groupid'] = $groupid;
    	         
    	     }
    	    
    	     

    	     if(!empty($_GPC['id'])){
    	         $_list = pdo_fetch("select * from ".tablename('xjfb_group')." where groupid = ".$_GPC['id']);
    	         
    	         if(!empty($_list))
    	             pdo_update('xjfb_group',$data,array('groupid'=>$_GPC['id']));
    	         else 
    	             pdo_insert('xjfb_group',$data);
    	     }else
    	         pdo_insert('xjfb_group',$data);
    	     
    	     
    	     message("保存成功！",$this->createWebUrl("grouplist"),"success");
	    }
	}
	public function doWebGroupdel(){
	
	    global $_W,$_GPC;
	
	
	    pdo_delete("mc_groups",array("groupid"=>$_GPC['id']));
	    pdo_delete("xjfb_group",array("groupid"=>$_GPC['id']));
	
	}
	
	
	public function doWebYinhanglist(){
	
	    global $_W,$_GPC;
	     
	    $pindex = max(1, intval($_GPC['page']));
	     
	    $psize = 15;
	     
	     
	    $sql = "select * from  ".tablename('xjfb_yinhanglist')." WHERE weid = :weid ORDER BY sort asc LIMIT ".($pindex - 1) * $psize.",{$psize}";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	     
	     
	     
	    if (!empty($list)) {
	         
	         
	        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_yinhanglist')."  WHERE weid = :weid",array(":weid" => $_W['uniacid']));
	         
	        $pager = pagination($total, $pindex, $psize);
	         
	        unset($row);
	         
	    }
	     
	     
	    include $this->template('yinhanglist');
	}
	
	public function doWebYinhangadd(){
	
	    global $_W,$_GPC;
	     
	    if(!empty($_GPC['id']))
	    $item = pdo_fetch("select * from  ".tablename('xjfb_yinhanglist')." where weid = :weid and id = :id",array(":weid"=>$_W['uniacid'],":id"=>$_GPC['id']));
	     
	     
	    include $this->template('yinhangadd');
	     
	}
	public function doWebYinhangaddok(){
	
	    global $_W,$_GPC;
	
	    if (checksubmit('submit')) {
	        
	        $data = array(
	            "weid" => $_W['uniacid'],
	
	            "cardname" => $_GPC['cardname'],
	
	            "sort" => $_GPC['sort'],
	
	            "addtime" => time()
	        );
	
	
	        if(!empty($_GPC['id'])){
	            pdo_update('xjfb_yinhanglist',$data,array('id'=>$_GPC['id']));
	            
	        }else
	            pdo_insert('xjfb_yinhanglist',$data);
	
	
	        message("保存成功！",$this->createWebUrl("yinhanglist"),"success");
	    }
	}
	public function doWebYinhangdel(){
	
	    global $_W,$_GPC;
	
	
	    pdo_delete("xjfb_yinhanglist",array("id"=>$_GPC['id']));
	
	    message("删除成功！",$this->createWebUrl("yinhanglist"),"success");
	}
	
	public function doWebTixianlist(){
	    global $_W,$_GPC;
	    
	    if($_GPC['dopost'] == "update"){
	        $txlist = pdo_fetch("SELECT * FROM ".tablename('xjfb_yuetx')." where weid=".$_W['uniacid']." AND id=".$_GPC['id']);
	        
	        if($txlist['txtype'] == 1){
	            $_result = $this->paypeople($txlist['openid'],$txlist['number']);
	             
	            
	            if($_result['result_code'] == "FAIL"){
	                message("企业支付:".$_result['err_code_des'] ,referer(),'error');
	                exit();
	            }
	            if($_result == null || $_result == false){
	                message('异常!',referer(),'error');
	            }
	        }
	            
	        
	        pdo_update('xjfb_yuetx',array('status'=>1,'statustime'=>time()),array('id'=>$_GPC['id']));
	        
	        pdo_update('xjfb_md_history',array('status'=>1),array('lid'=>$_GPC['id']));
	        
	        message('成功',referer(),'success');
	        
	        exit;
	        
	        
	    }elseif($_GPC['dopost'] == "save"){
	        pdo_update('xjfb_yuetx',array('textcon'=>$_GPC['mark']),array('id'=>$_GPC['id']));
	        
	        exit;
	    }

	    $pindex = max(1, intval($_GPC['page']));
	     
	    $psize = 10;
	     
	     
	    $sql = "select y.*,m.name from  ".tablename('xjfb_yuetx')." as y left join ".tablename('xjfb_mendian')." as m on m.id = y.mendian WHERE y.weid = :weid ORDER BY y.addtime desc LIMIT ".($pindex - 1) * $psize.",{$psize}";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	     
	     
	     
	    if (!empty($list)) {
	         
	         
	        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_yuetx')."  WHERE weid = :weid",array(":weid" => $_W['uniacid']));
	         
	        $pager = pagination($total, $pindex, $psize);
	         
	        unset($row);
	         
	    }
	    
	    
	    include $this->template('tixianlist');
	}
	public function doWebTixiandel(){
	    global $_W,$_GPC;
	    
	    
	    $list = pdo_fetch("select mendian,price,jytype,status from ".tablename('xjfb_yuetx')." where id = :id",array(":id"=>$_GPC['id']));
	    
	    
	    if($list['status'] == 0){
	        $mdlist = pdo_fetch("select number,number1 from ".tablename('xjfb_mendian')." where id = :id",array(":id"=>$list['mendian']));
	        if($list['jytype'] == 1)
	           pdo_update("xjfb_mendian",array("number1"=>($mdlist['number1']+$list['price'])),array("id"=>$list['mendian']));
	        else 
	           pdo_update("xjfb_mendian",array("number"=>($mdlist['number']+$list['price'])),array("id"=>$list['mendian']));
	    }
	    
	    pdo_delete("xjfb_yuetx",array("id"=>$_GPC['id']));
	    	  
	    
	    message("删除成功！",$this->createWebUrl("tixianlist"),"success");
	}
	public function paypeople($from,$money){

	    global $_W,$_GPC;
	    
	    
	    
	    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	    
	    $_data =  pdo_fetch("select title,apikey,mchid,apiclient_cert,apiclient_key,rootca from ".tablename('xjfb_setting')." where weid=".$_W['uniacid']);
	     
	    function unicode() {
	         
	        $str = uniqid(mt_rand(),1);
	         
	        $str = sha1($str);
	         
	        return md5($str);
	         
	    }
	    
	    
	    
	    $pars = array();
	    
	    
	    
	    $pars['mch_appid'] =$_W['account']['key']; //商户的应用appid
	    
	    
	    
	    $pars['mchid'] = $_data['mchid']; //商户ID
	    
	    
	    
	    $pars['nonce_str'] = unicode() ;//这个据说是唯一的字符串下面有方法 unicode();
	    
	    
	    
	    $pars['partner_trade_no'] = $_data['mchid'].TIMESTAMP.rand(1000000, 9999999); //.time();//这个是订单号。
	    
	    
	    
	    $pars['openid'] = $from; //授权用户的openid。这个必须得是用户授权才能用
	    
	    
	    
	    $pars['check_name'] = 'NO_CHECK'; //这个是设置是否检测用户真实姓名的
	    
	    
	    
	    $pars['amount'] = $money*100;//提现金额
	    
	    
	    
	    $pars['desc'] = $_data['title'].'提现'; //订单描述
	    
	    
	    
	    $pars['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];//获取服务器的ip
	    
	    
	    
	    $pars=array_filter($pars);
	    
	    
	    
	    ksort($pars);
	    
	    
	    
	    function arraytoxml($data){
	    
	        $str='<xml>';
	    
	        foreach($data as $k=>$v) {
	    
	            $str.='<'.$k.'>'.$v.'</'.$k.'>';
	    
	        }
	    
	        $str.='</xml>';
	    
	        return $str;
	    
	    }
	    
	    function xmltoarray($xml) {
	    
	        //禁止引用外部xml实体
	    
	        libxml_disable_entity_loader(true);
	    
	    
	    
	        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	    
	    
	    
	        $val = json_decode(json_encode($xmlstring),true);
	    
	    
	    
	        return $val;
	    
	    }
	    
	    
	    function curl($param="",$url,$dat) {
	    
	        $postUrl = $url;
	    
	        $curlPost = $param;
	    
	        $ch = curl_init();                                      //初始化curl
	    
	        curl_setopt($ch, CURLOPT_URL,$postUrl);                 //抓取指定网页
	    
	        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
	    
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
	    
	        curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
	    
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);           // 增加 HTTP Header（头）里的字段
	    
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
	    
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    
	        curl_setopt($ch,CURLOPT_SSLCERT,$dat['apiclient_cert']); //这个是证书的位置
	    
	        curl_setopt($ch,CURLOPT_SSLKEY,$dat['apiclient_key']); //这个也是证书的位置
	    
	        curl_setopt($ch,CURLOPT_CAINFO,$dat['rootca']);
	    
	        $data = curl_exec($ch);                                 //运行curl
	    
	        curl_close($ch);
	    
	        return $data;
	    
	    }
	    
	   
	    
	    $str='';
	    
	    foreach($pars as $k=>$v) {
	    
	        $str.=$k.'='.$v.'&';
	    
	    }
	    
	    
	    
	    $secrect_key= $_data['apikey'];///这个就是个API密码。32位的。。随便MD5一下就可以了
	    
	    
	    
	    $str.='key='.$secrect_key;
	    
	    $pars['sign']=md5($str);
	    
	    $xml=arraytoxml($pars);
	    
	    $res = curl($xml,$url,$_data); 
	    
	    $ret = xmltoarray($res);
	    
	    return $ret;
	    
	}
	
	
	public function doWebCreditlist(){
	
	    global $_W,$_GPC;
	     
	    $pindex = max(1, intval($_GPC['page']));
	     
	    $psize = 15;
	     
	     
	    $sql = "select * from ".tablename('xjfb_czzs')." WHERE weid = :weid ORDER BY addtime desc LIMIT ".($pindex - 1) * $psize.",{$psize}";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	     
	     
	     
	    if (!empty($list)) {
	         
	         
	        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_czzs')."  WHERE weid = :weid",array(":weid" => $_W['uniacid']));
	         
	        $pager = pagination($total, $pindex, $psize);
	         
	        unset($row);
	         
	    }
	     
	     
	    include $this->template('creditlist');
	}
	
	public function doWebCreditadd(){
	
	    global $_W,$_GPC;
	     
	    $item = pdo_fetch("select * from ".tablename('xjfb_czzs')." where weid = :weid and id = :id",array(":weid"=>$_W['uniacid'],":id"=>$_GPC['id']));
	     
	    include $this->template('creditadd');
	     
	}
	public function doWebCreditaddok(){
	
	    global $_W,$_GPC;
	
	    if (checksubmit('submit')) {
	        
	        $data = array(
	            "weid" => $_W['uniacid'],
	
	            "czmoney" => $_GPC['czmoney'],
	
	            "zsmoney" => $_GPC['zsmoney'],
	
	            "addtime" => time()
	        );
	
	        	
	        if(!empty($_GPC['id']))
	            pdo_update('xjfb_czzs',$data,array('id'=>$_GPC['id']));
	        else
	            pdo_insert('xjfb_czzs',$data);
	
	        message("保存成功！",$this->createWebUrl("creditlist"),"success");
	    }
	}
	public function doWebCreditdel(){
	
	    global $_W,$_GPC;
	
	
	    pdo_delete("xjfb_czzs",array("id"=>$_GPC['id']));
	
	}
	public function doWebCredit_list(){
	
	    global $_W,$_GPC;
	
	    $pindex = max(1, intval($_GPC['page']));
	
	    $psize = 15;
	
	
	    $sql = "select h.*,m.nickname from ".tablename('xjfb_hycz')." as h left join ".tablename('mc_members')." as m on m.uid = h.uid WHERE h.weid = :weid ORDER BY h.addtime desc LIMIT ".($pindex - 1) * $psize.",{$psize}";
	    $list = pdo_fetchall($sql,array(":weid" => $_W['uniacid']));
	
	   //print_r($list);
	
	    if (!empty($list)) {
	
	
	        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xjfb_hycz')."  WHERE weid = :weid",array(":weid" => $_W['uniacid']));
	
	        $pager = pagination($total, $pindex, $psize);
	
	        unset($row);
	
	    }
	
	
	    include $this->template('credit_list');
	}
	
	
	
	public function getPreCode($mid){
		global $_W;
	
		$sql = 'select addtime from '.tablename('xjfb_jifenjilu').' where weid = :weid and mcid = :mcid  ORDER BY addtime DESC LIMIT 1,1';
		$list = pdo_fetch($sql,array(":weid" => $_W['uniacid'],":mcid" => $mid));
		if(empty($list)){
			return "";
		}else{
			return date("Y-m-d H:i:s",$list['addtime']);
		}
	
	}
	
	
	public function SendTemplate($mcid,$day){
		global $_W;
	
		
		$sql1 = "SELECT template,msgcon FROM " . tablename('xjfb_zqsetting') . " WHERE weid = :weid";
		$send = pdo_fetch($sql1,array(':weid'=>$_W['uniacid']));
	
		$sql2 = "SELECT f.openid,m.nickname FROM " . tablename('mc_mapping_fans') . " as f left join ".tablename('mc_members')." as m on f.uid = m.uid WHERE f.uniacid = :uniacid and f.uid =:uid";
		$user = pdo_fetch($sql2,array(':uniacid'=>$_W['uniacid'],':uid'=>$mcid));
	
		$str = str_replace('{num}',$day,$send['msgcon']);
	
		if(!empty($_W['account']['key'])){
				
			if($this->exists_tokenBytxt($_W['account']['key'])){
				if($this->exprise_tokenBytxt($_W['account']['key'])){
					$token = $this->getToken($_W['account']['key'], $_W['account']['secret']);
	
					unlink($_W['account']['key'].'.txt');
	
					file_put_contents($_W['account']['key'].'.txt', $token);
				}else {
					$token = file_get_contents($_W['account']['key'].'.txt');
				}
			}else{
				$token = $this->getToken($_W['account']['key'], $_W['account']['secret']);
				file_put_contents($_W['account']['key'].'.txt', $token);
			}
	
			//print('openid:'.$user['openid'].';token:'.$token);
			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
			$message['touser'] = $user['openid'];
			$message['template_id'] = $send['template'];
			$message['url'] = '';
			$message['topcolor'] = '#ff0000';
	
			$message['data']['first'] = array(
					"value"=>"亲爱的".$user['nickname'].':'.$str,
					"color"=>"#173177"
			);
			$message['data']['keyword1'] = array(
					"value"=>'会员提醒',
					"color"=>"#173177"
			);
			$message['data']['keyword2'] = array(
					"value"=>date('Y-m-d H:i:s',TIMESTAMP),
					"color"=>"#173177"
			);
			$message['data']['remark'] = array(
					"value"=>"★祝您生活愉快,我们期待您的光临★",//
					"color"=>"#173177"
			);
			$json = json_encode($message,JSON_UNESCAPED_UNICODE);
			$xx_ret = $this->request_post($url,$json);
			$xx_ret = json_decode($xx_ret,true);
	
			print_r($xx_ret);
			if($xx_ret['errcode'] != 0){
				$tokens = $this->GetTokens($_W['account']['key'], $_W['account']['secret']);
				$url2 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$tokens;
	
				$json2 = json_encode($message,JSON_UNESCAPED_UNICODE);
				$ret2 = $this->request_post($url2,$json2);
	
				print($ret2);
			}
		}
	}
	
	
	//判断文件时否存在
	protected function exists_tokenBytxt($txtstr){
		if(file_exists($txtstr.'.txt')){
			return true;
		}else{
			return false;
		}
	}
	//获取token.txt的创建时间
	protected function exprise_tokenBytxt($txtstr){
		$ctime = filectime($txtstr.'.txt');
		if((time() - $ctime )>=5000){
			return true;
		}else{
			return false;
		}
	}
	protected function getToken($appid, $appsecret)
	{
		$curl = curl_init();
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		$obj = json_decode($res,true);
	
		return $obj['access_token'];
	
	}
	function request_post($url = '', $param = '')
	{
		if (empty($url) || empty($param)) {
			return false;
		}
		$postUrl = $url;
		$curlPost = $param;
		$ch = curl_init(); //初始化curl
		curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch); //运行curl
		curl_close($ch);
		return $data;
	}
	function request_get($url = '')
	{
		if (empty($url)) {
			return false;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	public function GetTokens($appid, $appsecret){
	
		$urls = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
		$ress = json_decode($this->httpGet($urls));
		$tokenss = $ress->access_token;
	
		return $tokenss;
	}
	
	public function _importExcel(){
	    
	}
	
	
	public function _pushExcel($title=array(),$data=array(),$name){
	
		$ichar =  ord("A"); //初始节点头A
	
		$_file = $name."(编号:".time().").xls";//定义文件名
	
		$_file = iconv("utf-8", "gb2312", $_file);
	
	
	
		$objPHPExcel = new PHPExcel(); //实例化 phpexcel类
	
		$objProps = $objPHPExcel->getProperties();
	
		//设置表头
	
	
	
		foreach($title as $k => $v){
	
			$colum = chr($ichar);
	
			$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v['name']);
	
			$v['width'] = empty($v['width'])?10:$v['width'];
	
			$objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth($v['width']); //设置宽度
	
			$ichar += 1;
	
		}
	
		//内容列表
	
		$column = 2;
	
		$objActSheet = $objPHPExcel->getActiveSheet();
	
		foreach($data as $key => $rows){ //行写入
	
			$span = ord("A");
	
			foreach($rows as $keyName=>$value){// 列写入
	
				$j = chr($span);
	
				$objActSheet->setCellValueExplicit($j.$column, $value, PHPExcel_Cell_DataType::TYPE_STRING);
	
				$span++;
	
			}
	
			$column++;
	
		}
	
		//重命名表
	
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
	
		//设置活动单指数到第一个表,所以Excel打开这是第一个表
	
		$objPHPExcel->setActiveSheetIndex(0);
	
		//将输出重定向到一个客户端web浏览器(Excel2007)
	
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	
		header("Content-Disposition: attachment; filename=\"$_file\"");
	
		header('Cache-Control: max-age=0');
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	
		$objWriter->save('php://output');
	
		exit;
	
	
	
	}}