<?php
defined('IN_IA') or exit ('Access Denied');
define('STYLE_PATH', '../addons/cgc_baoming_share/template/style');
define('MB_ROOT', IA_ROOT . '/addons/cgc_baoming_share');
require MB_ROOT . '/inc/util.php';
require MB_ROOT . '/inc/common.php';

//作者海纳百川qq:1120924338
class Cgc_baoming_shareModuleSite extends WeModuleSite {
	
	
	public function __construct() {
	  global $_W, $_GPC;
	  //如果电脑端给他更新。	

		$weid = $_W['uniacid'];
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$modulename = str_replace("ModuleSite", "", __CLASS__);
		$dd_settings = pdo_fetchcolumn($sql, array (
			':uniacid' => $_W['uniacid'],
			':module' => $modulename
		));
		$dd_settings = iunserializer($dd_settings);
		if (!empty ($dd_settings) && $dd_settings['debug_mode']) {
			ini_set('display_errors', '1');
			error_reporting(E_ALL ^ E_NOTICE);
		} else {
			error_reporting(0);
		}
	}
	
	public function fans(){
		global $_W, $_GPC;
		$settings = $this->module['config'];
		
		$modulename = $this->modulename;
		
		$userinfo = getFromUser($settings, $modulename);

		$userinfo = json_decode($userinfo, true);

		$cgc_baoming_fans = new cgc_baoming_fans();
	
		$fans = $cgc_baoming_fans->selectByOpenid($userinfo['openid']);
		
		if($fans){
			$cgc_baoming_fans->modify($fans['id'], array("tel"=>trim($_GPC['tel']), "realname"=>trim($_GPC['realname'])));
		}else{
			$data = array (
				"uniacid" => $_W['uniacid'],
				"openid" => $userinfo['openid'],
				"nickname" => $userinfo['nickname'],
				"headimgurl" => $userinfo['headimgurl'],
				"tel" => trim($_GPC['tel']),
				"realname" => trim($_GPC['realname']),
				"createtime" => TIMESTAMP,
			);
			$cgc_baoming_fans->insert($data);
		}
	}
	
	public function forward($id, $from_user,$activity=array()) {
		global $_W, $_GPC;
		$settings = $this->module['config'];
		$this->valid($id, $from_user);
		$cgc_baoming_user = new cgc_baoming_user();
		$user = $cgc_baoming_user->selectByUser($from_user, $id);

		if (empty ($user)) {
			$do = "login";
		} else{
		  //必须支付
	
		  if (empty($user['is_pay']) && ($activity['activity_type'])==2 && empty($activity['pay_time_point'])){
		     $url = $this->createMobileUrl("payment", array(
				'activity_id' => $id,
			    'tid' => $user['id']
			));
		    header("location:" . $url);
	        exit ();
		  }

            if (empty($user['is_pay']) && ($activity['activity_type'])==2 && !empty($activity['pay_time_point']) && !empty($user['share_status'])){
                $url = $this->createMobileUrl("payment", array(
                    'activity_id' => $id,
                    'tid' => $user['id']
                ));
                header("location:" . $url);
                exit ();
            }
		  	
			if (empty ($user['share_status'])) {
				$do = "share";
				if ($do != $_GPC['do'] && !empty ($settings['domain']) && !empty ($settings['zdy_domain'])) {
					$url = get_random_domain($settings['zdy_domain']);
					$url = $url . '/app/' . murl('entry', array (
						'm' => $this->module['name'],
						'do' => "share",
						'id' => $id,
						'sign' => time(),
						'ticket' => $from_user
					));
					header("location:" . $url);
					exit ();
				}

			} else {
				$do = "succ";
			}
		}	
			

		if ($do != $_GPC['do']) {
			header("location:" . $this->createMobileUrl($do, array (
				'id' => $id,
				'sign' => time(),
				'ticket' => $from_user
			)));
			exit ();
		}
	}

	public function doWebQr() {

		global $_GPC;

		$raw = @ base64_decode($_GPC['raw']);

		if (!empty ($raw)) {

			include MB_ROOT . '/inc/phpqrcode.php';

			QRcode :: png($raw, false, QR_ECLEVEL_Q, 4);

		}

	}

	public function doMobileQr() {

		global $_GPC;

		$raw = @ base64_decode($_GPC['raw']);

		if (!empty ($raw)) {

			include MB_ROOT . '/inc/phpqrcode.php';

			QRcode :: png($raw, false, QR_ECLEVEL_Q, 4);

		}

	}


 

	public function valid($id, $from_user) {

		global $_W, $_GPC;
		$settings = $this->module['config'];

		$cgc_baoming_activity = new cgc_baoming_activity();
		if (!empty ($id)) {
			$activity = $cgc_baoming_activity->getOne($id);
		}

		if (empty ($activity)) {
			message("没有此活动");
		}

		if (!empty ($activity["status"])) {
			if (!empty ($activity["end_url"])) {
				header("location:" . $activity["end_url"]);
				exit ();
			}
			message("此活动已经结束");
		}

      if ($activity['activity_type']>0 && $activity['pay_numed']>=$activity['pay_num']) {
          message('抱歉，报名名额已满！');
        }


		if (empty ($activity["locationtype"]) || $activity["locationtype"] == 2) {
			include_once 'inc/mobile/ipfunction.php';
			if (!empty ($activity["iplimit"])) {
				$ip = getip();
				$arr = explode('|', $activity['iplimit']);
				$result = false;
				foreach ($arr as $value) {
					if (iplimit($ip, $value) === false) {
						$result = false;
					} else {
						$result = true;
						break;
					}
				}
				if ($result == false) {
					if (!empty ($activity["zdyurl"])) {
						header("location:" . $activity["zdyurl"]);
						//message("你不在活动区域",$activity["zdyurl"],"success");
						exit ();
					} else {
						message("你不在活动区域");
					}
				}

			}
		}

		$curdate = time();

		if (!empty ($activity["start_time"]) && !empty ($activity["end_time"])) {
			if ($activity["start_time"] > $curdate) {
				message("活动还未开始，敬请期待。");
			}
			if ($activity["end_time"] < $curdate) {
				if (!empty ($activity["end_url"])) {
					header("location:" . $activity["end_url"]);
					exit ();
				}
				message("对不起，活动已结束。");
			}
		}
		
		//不限制人数
		if (empty($activity['join_num'])){
		  return;
		}

		$uniacid = $_W['uniacid'];
		$con = "uniacid=$uniacid";
        
		if (!empty ($id)) {
			$con .= " and  activity_id= $id and share_status=1";
		}

		$cgc_baoming_user = new cgc_baoming_user();
		$total = $cgc_baoming_user->getTotal($con);
		$join_num = empty ($activity['join_num']) ? 1000000 : $activity['join_num'];
		if ($total >= $join_num) {
			message("报名人数已经满了");
		}
	}


	public function payResult($params) {
		global $_W;

		if ($params['type'] == 'wechat') {
			$wechat_sn = $params['tag']['transaction_id'];
		}

		$id = $params['tid'];
		$baoming_user = pdo_fetch("select * from " . tablename('cgc_baoming_user') . " where ordersn=:ordersn ", array (
			':ordersn' => $id
		));
		
		if (empty($baoming_user)){
		  message("没有找到订单");
		}
		
		
		
	/*   $siteroot = getSiteRoot($_W['siteroot']) . "/";	
	   */
	   $siteroot =$_W['siteroot']. "/";	

	  if ($params['result'] == 'success' && ($params['from'] == 'notify' || $params['type'] == 'credit')) {
			if ($params['result'] == 'success') {
				if ($params['fee'] != $baoming_user['pay_money']) {
					message('非法操作！发布失败!');
				}
				
				$data = array (		
					'pay_money' => $params['fee'],
					'wx_ordersn' => $wechat_sn,				
					'is_pay' => 1
				);
				$ret = pdo_update('cgc_baoming_user', $data, array ('ordersn' => $id));
				$cgc_baoming_activity=pdo_fetch("select * from " . tablename('cgc_baoming_activity') . " where id=:id", array (
			     'id' => $baoming_user['activity_id']
		        ));
		        if ($cgc_baoming_activity){
				  $ret = pdo_update('cgc_baoming_activity',array("pay_numed"=>$cgc_baoming_activity['pay_numed']+1),array ('id' => $cgc_baoming_activity['id']));
		        }
			}
	  }	
	  
	if ($params['from'] == 'return') {		
	  if ($params['result'] == 'success') {	
	    $url = $siteroot . 'app/' . substr($this->createMobileUrl('success', array ('id' => $baoming_user['activity_id'],'op' => 'pay')), 2);
	  } else {
	    $url = $siteroot . 'app/' . substr($this->createMobileUrl('error', array ('id' => $baoming_user['activity_id'],'op' => 'pay')), 2);
	  }
	   header("location:" . $url);
	  exit ();
	 }
	 }
	 
	//发送红包
	public function send_redbag($user_id, $settings, $money=0){
		load()->func('tpl');
		
		$cgc_baoming_user = new cgc_baoming_user();
		$user = $cgc_baoming_user -> getOne($user_id);
	
		if (empty($user)) {
			message('无用户记录!');
		}
		
		if (empty($user['zj_status'])) {
			message('未中奖!');
		}
		
		$activity_id = $user['activity_id'];
		$cgc_baoming_activity = new cgc_baoming_activity();
		$activity = $cgc_baoming_activity -> getOne($activity_id);
		
		if (empty ($activity)) {
			message('无记录!');
		}
		
		$amount = $activity['redbag_money'];
	    if (empty($settings['sendtype'])){
		  $ret = send_qyfk($settings, $user['openid'], $amount, "红包来了");
	    } else {
	    	
	    	
         $settings['act_name'] =  $activity['title'];
         $settings['remark'] =  $activity['title'];
         $settings['send_name'] = $activity['title'];
         $settings['nick_name'] = $activity['title'];
	     $ret = send_xjhb($settings, $user['openid'], $amount, "红包来了");
	    }
	    
		if ($ret['code'] != 0) {
			message($ret['message'], referer(), "error");
		}
		
		$ret = $cgc_baoming_user->modify($user_id, array ("redbag_money" => $amount,"is_redbag" => 1));
		
		if (empty ($ret)) {
			message('发红包成功,状态改变失败', referer(), "error");
		}
		return true;
	}
	
	
	//发送红包
	public function send_redbag_mobile($user_id, $settings){
		load()->func('tpl');
		
		$cgc_baoming_user = new cgc_baoming_user();
		$user = $cgc_baoming_user -> getOne($user_id);
	
		if (empty($user)) {
		  return array("code"=>"-1",'msg'=>"用户记录为空");
		}
		
		if (($user['is_redbag'])) {
		  return array("code"=>"-11",'msg'=>"已中奖过");
		}
		
		$activity_id = $user['activity_id'];
		$cgc_baoming_activity = new cgc_baoming_activity();
		$activity = $cgc_baoming_activity -> getOne($activity_id);
		
		if (empty ($activity)) {
		  return array("code"=>"-2",'msg'=>"无记录!");
		}
		
		$amount = $activity['redbag_money'];
	    if (empty($settings['sendtype'])){
		  $ret = send_qyfk($settings, $user['openid'], $amount, "红包来了");
	    } else {
	      $settings['act_name'] =  $activity['title'];
          $settings['remark'] =  $activity['title'];
          $settings['send_name'] = $activity['title'];
          $settings['nick_name'] = $activity['title'];
	      $ret = send_xjhb($settings, $user['openid'], $amount, "红包来了");
	   }
	    
		if ($ret['code'] != 0) {
		  return array("code"=>"-3",'msg'=>$ret['message']);
		}
		
		$ret = $cgc_baoming_user->modify($user_id, array ("redbag_money" => $amount,"is_redbag" => 1));
		
		if (empty ($ret)) {
		  return array("code"=>"-333",'msg'=>"更新失败");
		}
		
		return array("code"=>"0",'msg'=>"成功");
	}
	

}