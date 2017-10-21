<?php
defined('IN_IA') or exit ('Access Denied');
define('STYLE_PATH', '../addons/cgc_baoming_share/template/style');
define('MB_ROOT', IA_ROOT . '/addons/cgc_baoming_share');
require MB_ROOT . '/inc/util.php';
require MB_ROOT . '/inc/common.php';
//作者海纳百川qq:1120924338
class Cgc_baoming_shareModuleSite extends WeModuleSite {

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
	
		  if (empty($user['is_pay']) && ($activity['pay_money'])>0){
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

	public function __construct() {
		global $_W, $_GPC;
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

     if ($activity['pay_num']>0){
       $cgc_baoming_user=new cgc_baoming_user();
      $user_count=$cgc_baoming_user->selectPay_count($id);
      if ($user_count>$activity['pay_num']) {
        message('抱歉，报名名额已满！');
        }
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

/*	protected function payz($params = array (), $mine = array ()) {
		global $_W;
		$params['module'] = $this->module['name'];
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$pars = array ();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		$log = pdo_fetch($sql, $pars);
		if (!empty ($log) && $log['status'] == '1') {
			$this->returnError('这个订单已经支付成功, 不需要重复支付.');
		}

		if (empty ($log)) {
			$log = array (
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $params['openid'],
				'module' => $this->module['name'], //模块名称，请保证$this可用
	'tid' => $params['tid'],
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
				'status' => '0',
				'is_usecard' => '0',
			);
			pdo_insert('core_paylog', $log);
		}

		//微信收银台支付
		if ($params['pay_type'] == 2) {
			$pay_params['WIDout_trade_no'] = $params['tid'];
			return $pay_params;
		}

		return $pay_params;
	}*/

	public function payResult($params) {
		global $_W;
	/*	if ($params['type'] == 'credit' ) {
			message('error!');
		}
*/

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
		
		
			$siteroot = getSiteRoot($_W['siteroot']) . "/";	

	  if ($params['result'] == 'success' && $params['from'] == 'notify') {
			
	
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
	 

}