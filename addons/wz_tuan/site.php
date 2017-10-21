<?php
/**
 * 新微团购模块微站定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/wz_tuan');
define('S_URL', '../addons/wz_tuan/static/');
class wz_tuanModuleSite extends WeModuleSite {
	
//	public function myconstruct(){
//		global $_GPC, $_W;
//		$content = "and uniacid='{$_W['uniacid']}' and status in(0,1,2,6)";
//		$orders = pdo_fetchall("select orderno,status,id,ptime,mobile from" . tablename('wz_tuan_order') . "where checkpay = 0 $content");
//		foreach ($orders as $key => $value) {
//			$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . "WHERE tid = '{$value['orderno']}' AND uniacid = '{$_W['uniacid']}'");
//			$tag = iunserializer($log['tag']);
//			$params['type'] = $log['type'];
//			$params['tag'] = $tag;
//			$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
//			$data['pay_type'] = $paytype[$params['type']];
//			if ($log['status'] == 1 && !empty($params['tag']['transaction_id'])) {
//				if ($value['status'] == 0) {
//					$data['status'] = 1;
//				}
//				$data['checkpay'] = 1;
//				if ($params['type'] == 'wechat') {
//					$data['transid'] = $params['tag']['transaction_id'];
//				}
//				pdo_update('wz_tuan_order', $data, array('id' => $value['id']));
//			}else{
//				if($value['mobile']=='虚拟'){
//					pdo_update('wz_tuan_order', array('status'=>1,'pay_type'=>0,'ptime'=>''), array('id' => $value['id']));
//				}else{
//					pdo_update('wz_tuan_order', array('status'=>0,'pay_type'=>0,'ptime'=>''), array('id' => $value['id']));
//				}
//				
//			}
//		}
//	}
//	public function checkpay($openid){
//		global $_GPC, $_W;
//		$content = "and uniacid='{$_W['uniacid']}' and openid='{$openid}'";
//		$orders = pdo_fetchall("select orderno,status,id,ptime from" . tablename('wz_tuan_order') . "where checkpay = 0 $content");
//		foreach ($orders as $key => $value) {
//			$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . "WHERE tid = '{$value['orderno']}' AND uniacid = '{$_W['uniacid']}'");
//			$tag = iunserializer($log['tag']);
//			$params['type'] = $log['type'];
//			$params['tag'] = $tag;
//			$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
//			$data['pay_type'] = $paytype[$params['type']];
//			if ($log['status'] == 1 && !empty($params['tag']['transaction_id'])) {
//				if ($value['status'] == 0) {
//					$data['status'] = 1;
//				}
//				$data['checkpay'] = 1;
//				if ($params['type'] == 'wechat') {
//					$data['transid'] = $params['tag']['transaction_id'];
//				}
//				pdo_update('wz_tuan_order', $data, array('id' => $value['id']));
//			}else{
//				pdo_update('wz_tuan_order', array('status'=>0,'pay_type'=>0,'ptime'=>''), array('id' => $value['id']));
//			}
//		}
//	}

	public function getuserinfo() {
		global $_W;
		load() -> model('mc');
		$profile = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_member') . " WHERE uniacid ='{$_W['uniacid']}' and openid = '{$_W['openid']}'");
		if (empty($profile['nickname']) || empty($profile['avatar'])) {
			$userinfo = mc_oauth_userinfo();
			if (!empty($profile)) {
				$record = array(
					'nickname' => stripslashes($userinfo['nickname']),
					'avatar' => $userinfo['headimgurl'],
					'tag' => base64_encode(iserializer($userinfo))
				);
				pdo_update('wz_tuan_member', $record, array('id' => $profile['id']));
			} elseif(!empty($_W['openid'])) {
				$record = array();
				$record['nickname'] = stripslashes($userinfo['nickname']);
				$record['tag'] = base64_encode(iserializer($userinfo));
				$record['openid'] = $_W['openid'];
				$record['avatar'] = $userinfo['headimgurl'];
				$record['uniacid'] = $_W['uniacid'];
				pdo_insert('wz_tuan_member', $record);
			}
		}
	}
	
	public function getfansinfo($openid) {
		global $_W;
		load() -> model('mc');
		$profile = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_member') . " WHERE uniacid ='{$_W['uniacid']}' and openid = '{$openid}'");
		if (empty($profile['nickname'])) {
			$uid = mc_openid2uid($openid);
			$result = mc_fetch($uid, array('credit1', 'credit2','avatar','nickname'));
		} else {
			$result['nickname'] = $profile['nickname'];
			$result['avatar'] = $profile['avatar'];
		}
		return $result;
	}
	
	//更新团状态
	public function updategourp() {
		global $_W;
		$now = time();
		$allgroups = pdo_fetchall("select *from" . tablename('wz_tuan_group') . "where groupstatus=3 and uniacid='{$_W['uniacid']}'");
		foreach ($allgroups as $key => $value) {
			if ($value['endtime'] < $now && $value['lacknum'] > 0) {
				pdo_update('wz_tuan_group', array('groupstatus' => 1), array('groupnumber' => $value['groupnumber']));
				$orders = pdo_fetchall("select * from" . tablename('wz_tuan_order') . "where tuan_id='{$value['groupnumber']}' and uniacid='{$_W['uniacid']}' and status in(1,2,3,4)");
				foreach ($orders as $k => $v) {
					$res = pdo_update('wz_tuan_order', array('status' => 6), array('id' => $v['id']));
				}
			}
		}
	}
	//更新取消订单
	public function cancleorder() {
		global $_W;
		$allgroups = pdo_fetchall("select *from" . tablename('wz_tuan_order') . "where status=0 and uniacid='{$_W['uniacid']}'");
		$now = time();
		foreach ($allgroups as $key => $value) {
			$shouldpaytime = $value['createtime']+1800;
			if ($shouldpaytime < $now) {
				$res = pdo_update('wz_tuan_order', array('status' => 5), array('id' => $value['id']));
			}
		}
	}

	public function backlists() {
		global $_W, $frames;
		require_once MB_ROOT . '/source/backlist.class.php';
		$backlist = new backlist();
		$frames = $backlist -> getModuleFrames('wz_tuan');
		$backlist -> _calc_current_frames2($frames);
	}
	
	protected function pay($params = array(), $mine = array()) {
		global $_W;
		if(!$this->inMobile) {
			message('支付功能只能在手机上使用');
		}
		$params['module'] = $this->module['name'];
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
				if($params['fee'] <= 0) {
			$pars['from'] = 'return';
			$pars['result'] = 'success';
			$pars['type'] = 'alipay';
			$pars['tid'] = $params['tid'];
			$site = WeUtility::createModuleSite($pars[':module']);
			$method = 'payResult';
			if (method_exists($site, $method)) {
				exit($site->$method($pars));
			}
		}

		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && $log['status'] == '1') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if(!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$pay = $setting['payment'];
		$pay['credit']['switch'] = false;
		$pay['delivery']['switch'] = false;
		if (!empty($pay['credit']['switch'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
		}
		$iscard = pdo_fetchcolumn('SELECT iscard FROM ' . tablename('modules') . ' WHERE name = :name', array(':name' => $params['module']));
		$you = 0;
		if($pay['card']['switch'] == 2 && !empty($_W['openid'])) {
			if($_W['card_permission'] == 1 && !empty($params['module'])) {
				$cards = pdo_fetchall('SELECT a.id,a.card_id,a.cid,b.type,b.title,b.extra,b.is_display,b.status,b.date_info FROM ' . tablename('coupon_modules') . ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.cid = b.id WHERE a.acid = :acid AND a.module = :modu AND b.is_display = 1 AND b.status = 3 ORDER BY a.id DESC', array(':acid' => $_W['acid'], ':modu' => $params['module']));
				$flag = 0;
				if(!empty($cards)) {
					foreach($cards as $temp) {
						$temp['date_info'] = iunserializer($temp['date_info']);
						if($temp['date_info']['time_type'] == 1) {
							$starttime = strtotime($temp['date_info']['time_limit_start']);
							$endtime = strtotime($temp['date_info']['time_limit_end']);
							if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
								continue;
							} else {
								$param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
								$num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
								if($num <= 0) {
									continue;
								} else {
									$flag = 1;
									$card = $temp;
									break;
								}
							}
						} else {
							$deadline = intval($temp['date_info']['deadline']);
							$limit = intval($temp['date_info']['limit']);
							$param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
							$record = pdo_fetchall('SELECT addtime,id,code FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
							if(!empty($record)) {
								foreach($record as $li) {
									$time = strtotime(date('Y-m-d', $li['addtime']));
									$starttime = $time + $deadline * 86400;
									$endtime = $time + $deadline * 86400 + $limit * 86400;
									if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
										continue;
									} else {
										$flag = 1;
										$card = $temp;
										break;
									}
								}
							}
							if($flag) {
								break;
							}
						}
					}
				}
				if($flag) {
					if($card['type'] == 'discount') {
						$you = 1;
						$card['fee'] = sprintf("%.2f", ($params['fee'] * ($card['extra'] / 100)));
					} elseif($card['type'] == 'cash') {
						$cash = iunserializer($card['extra']);
						if($params['fee'] >= $cash['least_cost']) {
														$you = 1;
							$card['fee'] = sprintf("%.2f", ($params['fee'] -  $cash['reduce_cost']));
						}
					}
					load()->classs('coupon');
					$acc = new coupon($_W['acid']);
					$card_id = $card['card_id'];
					$time = TIMESTAMP;
					$randstr = random(8);
					$sign = array($card_id, $time, $randstr, $acc->account['key']);
					$signature = $acc->SignatureCard($sign);
					if(is_error($signature)) {
						$you = 0;
					}
				}
			}
		}

		if($pay['card']['switch'] == 3 && $_W['member']['uid']) {
						$cards = array();
			if(!empty($params['module'])) {
				$cards = pdo_fetchall('SELECT a.id,a.couponid,b.type,b.title,b.discount,b.condition,b.starttime,b.endtime FROM ' . tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND a.module = :modu AND b.condition <= :condition AND b.starttime <= :time AND b.endtime >= :time  ORDER BY a.id DESC', array(':uniacid' => $_W['uniacid'], ':modu' => $params['module'], ':time' => TIMESTAMP, ':condition' => $params['fee']), 'couponid');
				if(!empty($cards)) {
					$condition = '';
					if($iscard == 1) {
						$condition = " AND grantmodule = '{$params['module']}'";
					}
					foreach($cards as $key => &$card) {
						$has = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . ' WHERE uid = :uid AND uniacid = :aid AND couponid = :cid AND status = 1' . $condition, array(':uid' => $_W['member']['uid'], ':aid' => $_W['uniacid'], ':cid' => $card['couponid']));
						if($has > 0){
							if($card['type'] == '1') {
								$card['fee'] = sprintf("%.2f", ($params['fee'] * $card['discount']));
								$card['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $card['discount']));
							} elseif($card['type'] == '2') {
								$card['fee'] = sprintf("%.2f", ($params['fee'] -  $card['discount']));
								$card['discount_cn'] = $card['discount'];
							}
						} else {
							unset($cards[$key]);
						}
					}
				}
			}
			if(!empty($cards)) {
				$cards_str = json_encode($cards);
			}
		}
		$share_data = $this -> module['config'];
		$_W['page']['footer'] = $share_data['copyright'];
		$title = '支付方式';
		include $this->template('paycenter');
	}
	

	// public function orderquery($uniontid){
	// 	global $_GPC, $_W;
	// 	include_once IA_ROOT . '/addons/wz_tuan/source/WxPay.Api.php';
	// 	load() -> model('account');
	// 	load() -> func('communication');
	// 	$accounts = uni_accounts();
	// 	$WxPayApi = new WxPayApi();
	// 	$input = new WxPayOrderQuery();
	// 	$input->SetOut_trade_no($uniontid);
	// 	$account_info = pdo_fetch("select * from" . tablename('account_wechats') . "where uniacid={$_W['uniacid']}");
	// 	$appid = $account_info['key'];
	// 	$key = $this -> module['config']['apikey'];
	// 	$mchid = $this -> module['config']['mchid'];
	// 	$input->SetAppid($appid);//公众账号ID
	// 	$input->SetMch_id($mchid);//商户号
	// 	$result = $WxPayApi -> orderQuery($input,6,$key);
	// 	$trade_state = $result['trade_state'];
	// 	$transaction_id = $result['transaction_id'];
	// 	$time_end  = $result['time_end'];
	// 	$res=array(
	// 		'trade_state'=>$trade_state,
	// 		'transaction_id'=>$transaction_id,
	// 		'time_end'=>$time_end
	// 	);
		
	// 	$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . "WHERE uniontid = '{$uniontid}' AND uniacid = '{$_W['uniacid']}'");
	// 	$tag = iunserializer($log['tag']);
	// 	$tag['transaction_id'] = $res['transaction_id'];
	// 	$tag=serialize($tag);
		
	// 	pdo_update('core_paylog',array('tag'=>$tag),array('uniontid'=>$uniontid));
	// 	return $res;
	// }
	public function refund($orderno, $price, $type) {
		global $_GPC, $_W;
		include_once '../addons/wz_tuan/source/WxPay.Api.php';
		$WxPayApi = new WxPayApi();
		$input = new WxPayRefund();
		load() -> model('account');
		load() -> func('communication');
		$accounts = uni_accounts();
		$acid = $_W['uniacid'];
		$path_cert = IA_ROOT . '/addons/wz_tuan/cert/' . $_W['uniacid'] . '/apiclient_cert.pem';
		//证书路径
		$path_key = IA_ROOT . '/addons/wz_tuan/cert/' . $_W['uniacid'] . '/apiclient_key.pem';
		//证书路径
		$key = $this -> module['config']['apikey'];
		//商户支付秘钥（API秘钥）
		$account_info = pdo_fetch("select * from" . tablename('account_wechats') . "where uniacid={$_W['uniacid']}");
		//身份标识（appid）
		$appid = $account_info['key'];
		//身份标识（appid）
		$mchid = $this -> module['config']['mchid'];
		//微信支付商户号(mchid)
		$refund_ids = pdo_fetch("select * from" . tablename('wz_tuan_order') . "where orderno ='{$orderno}'");
		$goods = pdo_fetch("select * from" . tablename('wz_tuan_goods') . "where id='{$refund_ids['g_id']}'");
		if (!empty($price)) {
			$fee = $price;
		} else {
			$fee = $refund_ids['price']*100;
		}
		//退款金额
		$refundid = $refund_ids['transid'];
		//微信订单号
		/*$input：退款必须要的参数*/
		$input -> SetAppid($appid);
		$input -> SetMch_id($mchid);
		$input -> SetOp_user_id($mchid);
		$input -> SetRefund_fee($fee);
		$input -> SetTotal_fee($refund_ids['price']*100);
		if($refund_ids['is_tuan']==3){
			$paylog = pdo_fetch("select uniontid from".tablename('core_paylog')."where tid='{$refund_ids['orderno']}' and uniacid='{$_W['uniacid']}' ");
			$input -> SetOut_trade_no($paylog['uniontid']);
			$new = date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
			$input -> SetOut_refund_no($new);
		}else{
			$input -> SetTransaction_id($refundid);
			$input -> SetOut_refund_no($refund_ids['orderno']);
		}
	
		$result = $WxPayApi -> refund($input, 6, $path_cert, $path_key, $key);
		
		//写入退款记录
		$data = array('transid' => $refund_ids['transid'], 'refund_id' => $result['refund_id'], 'createtime' => TIMESTAMP, 'status' => 0, 'type' => $type, 'goodsid' => $refund_ids['g_id'], 'orderid' => $refund_ids['id'], 'payfee' => $refund_ids['price'], 'refundfee' => $fee*0.01, 'refundername' => $refund_ids['addname'], 'refundermobile' => $refund_ids['mobile'], 'goodsname' => $goods['gname'], 'uniacid' => $_W['uniacid']);
		pdo_insert('wz_tuan_refund_record', $data);
		if ($result['return_code'] == 'SUCCESS') {
			if ($type == 3) {
				pdo_update('wz_tuan_order', array('status' => 7, 'is_tuan' => 2), array('id' => $refund_ids['id']));
			} elseif($type == 4){
				pdo_update('wz_tuan_order', array('is_tuan' =>3), array('id' => $refund_ids['id']));
			}else{
				pdo_update('wz_tuan_order', array('status' => 7), array('id' => $refund_ids['id']));
			}
			pdo_update('wz_tuan_refund_record', array('status' => 1), array('transid' => $refund_ids['transid']));

			/*退款通知*/
			require_once IA_ROOT . '/addons/wz_tuan/source/Message.class.php';
			$access_token = WeAccount::token();
			$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
			$url2 = '';
			$sendmessage = new Message();
			if ($type == 4) {
				$res = $sendmessage -> part_refund($refund_ids['openid'], $fee*0.01, $this, $url1, $url2);
			} else {
				$res = $sendmessage -> refund($refund_ids['openid'], $fee*0.01, $this, $url1, $url2);
			}
			/*退款通知*/
			pdo_query("update" . tablename('wz_tuan_goods') . " set gnum=gnum+1 where id = '{$refund_ids['g_id']}'");
			return 'success';
		} else {
			if ($type == 3) {
				pdo_update('wz_tuan_order', array('status' => 6, 'is_tuan' => 2), array('id' => $refund_ids['id']));
			} else {
				pdo_update('wz_tuan_order', array('status' => 6), array('id' => $refund_ids['id']));
			}
			return 'fail';
		}
}

	//验证新微团购模式
	public function checkmode() {
		if (empty($this -> module['config']['mode'])) {
			message('请先设置新微团购模式', "../web/index.php?c=profile&a=module&do=setting&m=wz_tuan", 'warning');
			exit ;
		}
	}

	//支付结果返回
	public function payResult($params) {
		/*写入文件*/
		file_put_contents(MB_ROOT."/params.log", var_export($params, true).PHP_EOL, FILE_APPEND);
		global $_W, $_GPC;
		load()->model('mc');
		load() -> model('account');
		require_once MB_ROOT . '/source/Message.class.php';
		include_once MB_ROOT . '/source/wprint.class.php';
		$success = FALSE;
		$order_out = pdo_fetch("SELECT * FROM ".tablename('wz_tuan_order')." WHERE orderno = :tid", array(':tid' => $params['tid']));
		$goodsInfo = pdo_fetch("SELECT * FROM" . tablename('wz_tuan_goods') . "WHERE id = :id ", array(':id' => $order_out['g_id']));
		$nowtuan = pdo_fetch("select * from" . tablename('wz_tuan_group') . "where groupnumber = :groupnumber",array(':groupnumber'=>$order_out['tuan_id']));
		if(empty($order_out['status'])){
			$data = array('status' => $params['result'] == 'success' ? 1 : 0);
			if($params['is_usecard']==1){
				$fee = $params['card_fee'];
				$data['is_usecard'] = 1;
			}else{
				$fee = $params['fee'];
			}
			$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
			$data['pay_type'] = $paytype[$params['type']];
			if ($params['type'] == 'wechat') {
				$data['transid'] = $params['tag']['transaction_id'];
			}
			$data['ptime'] = TIMESTAMP;
			$data['price'] = $fee;
			$data['starttime'] = TIMESTAMP;
			if (!empty($nowtuan)) {
					if ($nowtuan['lacknum'] == 0 ) {
						$success = TRUE;
					}
			}
			//后台通知，修改状态
			if ($params['result'] == 'success' && $params['from'] == 'notify') {
				/*积分*/
				load()->model('mc');
				$uid = $params['tag']['uid'];
				$credit=mc_credit_fetch($uid);
				$mycredit = $credit['credit1'];
				/*更新订单状态*/
				pdo_update('wz_tuan_order', $data, array('orderno' => $params['tid']));
				if(!$success){
					if ($order_out['is_tuan'] == 0) {
							pdo_update('wz_tuan_order', array('status' => 2), array('orderno' => $params['tid']));
							pdo_update('mc_members', array('credit1' => $mycredit+$goodsInfo['credits']), array('uid' => $uid,'uniacid'=>$order_out['uniacid']));
						}else{
							if ($nowtuan['lacknum'] > 0) {
								pdo_update('wz_tuan_group', array('lacknum' => $nowtuan['lacknum'] - 1), array('groupnumber' => $order_out['tuan_id']));
							}
						}
						// 更改库存
					if ($goodsInfo['gnum'] == 1) {
							pdo_update('wz_tuan_goods', array('gnum' => $goodsInfo['gnum'] - 1, 'salenum' => $goodsInfo['salenum'] + 1, 'isshow' => 0), array('id' => $order_out['g_id']));
						}elseif(!empty($goodsInfo['gnum'])){
							pdo_update('wz_tuan_goods', array('gnum' => $goodsInfo['gnum'] - 1, 'salenum' => $goodsInfo['salenum'] + 1), array('id' => $order_out['g_id']));
						}
					$now = pdo_fetch("select * from" . tablename('wz_tuan_group') . "where groupnumber = :groupnumber",array(':groupnumber'=>$order_out['tuan_id']));
					if (!empty($now) && $now['lacknum'] == 0) {
						pdo_update('wz_tuan_group', array('groupstatus' => 2), array('groupnumber' => $now['groupnumber']));
						pdo_update('wz_tuan_order', array('status' => 2), array('tuan_id' => $now['groupnumber'], 'status' => 1));
						pdo_update('wz_tuan_order', array('status' => 2), array('tuan_id' => $now['groupnumber'], 'orderno' => $params['tid']));
						$credit_orders = pdo_fetchall("select openid from".tablename('wz_tuan_order')."where tuan_id=:tuan_id and status=:status",array(':tuan_id'=>$now['groupnumber'],':status'=>2));
						foreach($credit_orders as$key=>$cre){
							$theuid = mc_openid2uid($cre['openid']);
							$credit=mc_credit_fetch($theuid);
							$mycredit = $credit['credit1'];
							pdo_update('mc_members', array('credit1' => $mycredit+$goodsInfo['credits']), array('uid' => $theuid,'uniacid'=>$_W['uniacid']));
						}
					}
			  }else{
			  	pdo_update('wz_tuan_order', array('status'=>6,'is_tuan'=>2), array('orderno' => $params['tid']));
			  }
			}
		}
		//前台通知
		if ($params['from'] == 'return') {
			/*支付成功消息模板*/
				$access_token = WeAccount::token();
				$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
				$url2 = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('myorder', array('id' => $order_out['id']));
				$sendmessage = new Message();
				$res = $sendmessage -> pay_success($order_out['openid'], $order_out['orderno'], $goodsInfo['gname'], $this, $url1, $url2);
				
			 if($nowtuan['groupstatus']==2 && $order_out['is_tuan']=1){
			 	 //*组团成功成功消息模板*/
					load() -> model('account');
					$access_token = WeAccount::token();
					$url1 = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token . "";
					$url2 = '';
					$sendmessage = new Message();
					$res = $sendmessage -> group_success($order_out['tuan_id'], $this, $url1, $url2);
			 }
			//获取所有打印机
			$prints = pdo_fetchall('SELECT * FROM ' . tablename('wz_tuan_print') . ' WHERE uniacid = :aid AND status = 1', array(':aid' => $_W['uniacid']));
					if (!empty($prints)) {
						//遍历所有打印机
						foreach ($prints as $li) {
							if (!empty($li['print_no']) && !empty($li['key'])) {
								$wprint = new wprint();
								$alltuan = pdo_fetchall("select * from" . tablename('wz_tuan_order') . "where tuan_id = '{$now['groupnumber']}' and status = 2 ");
								if ($li['mode'] == 1) {
									$orderinfo .= "<CB>组团成功</CB><BR>";
									$orderInfo .= "商品信息：<BR>";
									$orderinfo .= '------------------------------<BR>';
									$orderinfo .= "商品名称：{$goodsInfo['gname']}<BR>";
									$orderinfo .= '------------------------------<BR>';
									$orderinfo .= "用户信息：<BR>";
									$orderinfo .= '------------------------------<BR>';
									foreach ($alltuan as $row) {
										$orderinfo .= "用户名：{$row['addname']}<BR>";
										$orderinfo .= "手机号：{$row['mobile']}<BR>";
										$orderinfo .= "地址：{$row['address']}<BR>";
										$orderinfo .= '------------------------------<BR>';
									}
									$status = $wprint -> StrPrint($li['print_no'], $li['key'], $orderinfo, $li['print_nums']);
								} else {
									$orderinfo .= "
										 组团成功
										
										商品信息：
										-----------------------------
										商品名称：{$goodsInfo['gname']}
										
										用户信息：
										-----------------------------
										";
																		foreach ($alltuan as $row) {
																			$orderinfo .= "
										用户名：{$row['addname']}
										手机号：{$row['mobile']}
										地址：{$row['address']}
										-----------------------------
										";
									}
									$status = $wprint -> testSendFreeMessage($li['member_code'], $li['print_no'], $li['key'], $orderinfo);
								}
							}
						}
					}
			
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			$order_out = pdo_fetch("SELECT * FROM ".tablename('wz_tuan_order')." WHERE orderno = :tid", array(':tid' => $params['tid']));
			if ($params['type'] == $credit) {
				if($order_out['is_tuan']==2){
					echo "<script>location.href='".$this->createMobileUrl('index',array('result'=>'success'))."';</script>";
					exit;
				}else{
					if ($order_out['is_tuan'] == 0) {
					echo "<script> location.href='" . $this -> createMobileUrl('myorder',array('payreslut' => 'success')) . "';</script>";
					exit ;
					} else {
						echo "<script>  location.href='" . $this -> createMobileUrl('group', array('tuan_id' => $order_out['tuan_id'],'payreslut' => 'success')) . "';</script>";
						exit ;
					}
				}
				
			} else {
				if($order_out['is_tuan']==2){
					echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('result'=>'success'))."';</script>";
					exit;
				}else{
					if ($order_out['is_tuan'] == 0) {
					echo "<script>  location.href='" . $_W['siteroot'] . 'app/' . $this -> createMobileUrl('myorder',array('payreslut' => 'success')) . "';</script>";
					exit ;
					} else {
						echo "<script>  location.href='" . $_W['siteroot'] . 'app/' . $this -> createMobileUrl('group', array('tuan_id' => $order_out['tuan_id'],'payreslut' => 'success')) . "';</script>";
						exit ;
					}
				}
				
			}
		}
	}

}
