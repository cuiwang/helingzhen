<?php
/**
 * 万能小店
 *
 * @url
 */

defined('IN_IA') or exit('Access Denied');

include "model.php";

class Wn_storexModuleSite extends WeModuleSite {
	public $_from_user = '';

	public $_weid = '';

	public $_version = 0;

	public $_hotel_level_config = array(5 => '五星级酒店', 4 => '四星级酒店', 3 => '三星级酒店', 2 => '两星级以下', 15 => '豪华酒店', 14 => '高档酒店', 13 => '舒适酒店', 12 => '经济型酒店', );

	public $_set_info = array();

	public $_user_info = array();

	function __construct() {
		global $_W;
		$this->_from_user = $_W['fans']['from_user'];
		$this->_weid = $_W['uniacid'];
		$this->_set_info = get_storex_set();
		$this->_version = $this->_set_info['version'];
	}

	public function __call($name, $arguments) {
		$isWeb = stripos($name, 'doWeb') === 0;
		$isMobile = stripos($name, 'doMobile') === 0;
		if ($isWeb || $isMobile) {
			$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/';
			if ($isWeb) {
				$dir .= 'web/';
				$fun = strtolower(substr($name, 5));
				$func = IA_ROOT . '/addons/wn_storex/function/function.php';
				if (is_file($func)) {
					require $func;
				}
			}
			if ($isMobile) {
				$dir .= 'mobile/';
		 		$fun = strtolower(substr($name, 8));
		 		$init = $dir . '__init.php';
		 		$func = IA_ROOT . '/addons/wn_storex/function/function.php';
				if (is_file($init)) {
					require $init;
				}
				if (is_file($func)) {
					require $func;
				}
			}
 			$file = $dir . $fun . '.inc.php';
			if (file_exists($file)) {
				require $file;
				exit;
			} else {
				$dir = str_replace("addons", "framework/builtin", $dir);
				$file = $dir . $fun . '.inc.php';
				if (file_exists($file)) {
					require $file;
					exit;
				}
			}
		}
		trigger_error("访问的方法 {$name} 不存在.", E_USER_WARNING);
		return null;
	}

	public function getItemTiles() {
		global $_W;
		$urls = array(
			array('title' => '酒店首页', 'url' => $this->createMobileUrl('display')),
			array('title' => '我的订单', 'url' => $this->createMobileUrl('display')) . '#/Home/OrderList/',
			array('title' => '会员中心', 'url' => $this->createMobileurl(''))
		);
		return $urls;
	}

	//入口文件
	public function doMobileIndex() {
		global $_GPC, $_W;
		$weid = $this->_weid;
		$from_user = $this->_from_user;
		$set = $this->_set_info;
		$hid = $_GPC['hid'];
		$user_info = pdo_fetch("SELECT * FROM " . tablename('storex_member') . " WHERE from_user = :from_user AND weid = :weid limit 1", array(':from_user' => $from_user, ':weid' => $weid));
		//独立用户
		if ($set['user'] == 2) {

			if (empty($user_info['id'])) {
				//用户不存在
				if ($set['reg'] == 1) {
					//开启注册
					$url = $this->createMobileUrl('register');
				} else {
					//禁止注册
					$url = $this->createMobileUrl('login');
				}
			} else {
				//用户已经存在，判断用户是否登录
				$check = check_hotel_user_login($this->_set_info);
				if ($check) {
					if ($user_info['status'] == 1) {
						$url = $this->createMobileUrl('search');
					} else {
						$url = $this->createMobileUrl('login');
					}
				} else {
					$url = $this->createMobileUrl('login');
				}
			}
		} else {
			//微信用户
			if (empty($user_info['id'])) {
				//用户不存在，自动添加一个用户
				$member = array();
				$member['weid'] = $weid;
				$member['from_user'] = $from_user;

				$member['createtime'] = time();
				$member['isauto'] = 1;
				$member['status'] = 1;
				pdo_insert('storex_member', $member);
				$member['id'] = pdo_insertid();
				$member['user_set'] = $set['user'];
				//自动添加成功，将用户信息放入cookie
				hotel_set_userinfo(0, $member);
			} else {
				if ($user_info['status'] == 1) {
					$user_info['user_set'] = $set['user'];
					//用户已经存在，将用户信息放入cookie
					hotel_set_userinfo(1, $user_info);
				} else {
					//用户帐号被禁用
					$msg = "抱歉，你的帐号被禁用，请联系管理员解决。";
					if ($this->_set_info['is_unify'] == 1) {
						$msg .= "店铺电话：" . $this->_set_info['tel'] . "。";
					}
					$url = $this->createMobileUrl('error',array('msg' => $msg));
					header("Location: $url");
					exit;
				}
			}
			//微信粉丝，可以直接使用
			$url = $this->createMobileUrl('display');
		}
		header("Location: $url");
		exit;
	}

	public function doMobiledisplay() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$setting = get_storex_set();
		if (empty($id) && $setting['version'] == 0 && empty($_GPC['orderid']) && $_GPC['pay_type'] != 'recharge') {
			$storex_base = pdo_get('storex_bases', array('weid' => $_W['uniacid'], 'status' => 1), array('id'), '', 'displayorder DESC');
			$url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=wn_storex&do=display&id='
					 . $storex_base['id'] . '#/StoreIndex/' . $storex_base['id'];
			header("Location: $url");
		}
		$url = $this->createMobileurl('display', array('id' => $id));
		if (!empty($_GPC['orderid'])) {
			$redirect = $url . '#/Home/OrderInfo/' . $_GPC['orderid'];
			header("Location: $redirect");
		}
		if ($_GPC['pay_type'] == 'recharge') {
			$redirect = $url;
			header("Location: $redirect");
		}
		$skin_style = $this->get_skin_style($id);
		include $this->template($skin_style);
	}

	public function doMobileservice() {
		global $_GPC, $_W;
		include $this->template('service');
	}
	
	//店铺id
	public function get_skin_style($id) {
		$store = pdo_get('storex_bases', array('id' => $id), array('id', 'skin_style'));
		$style = array('display', 'black');
		$skin_style = in_array($store['skin_style'], $style) ? $store['skin_style'] : 'display';
		return $skin_style;
	}

	//登录页
	public function doMobilelogin() {
		global $_GPC, $_W;;
		$set = $this->_set_info;
		if (checksubmit()) {
			$member = array();
			$username = trim($_GPC['username']);
			if (empty($username)) {
				die(json_encode(array("result" => 2, "error" => "请输入要登录的用户名")));
			}
			$member['username'] = $username;
			$member['password'] = $_GPC['password'];
			// $member['status'] = 1;
			if (empty($member['password'])) {
				die(json_encode(array("result" => 3, "error" => "请输入登录密码")));
			}
			$weid = $this->_weid;
			$from_user = $this->_from_user;
			$set = $this->_set_info;
			$member['weid'] = $weid;
			$record = hotel_member_single($member);
			if (!empty($record)) {
				if ( ($set['bind'] == 3 && ($record['userbind'] == 1) || $set['bind'] == 2)) {
					if (!empty($record['from_user'])) {
						if ($record['from_user'] != $this->_from_user) {
							die(json_encode(array("result" => 0, "error" => "登录失败，您的帐号与绑定的微信帐号不符！")));
						}
					}
				}
				if (empty($record['status'])) {
					die(json_encode(array("result" => 0, "error" => "登录失败，您的帐号被禁止登录，请联系酒店解决！")));
				}
				$record['user_set'] = $set['user'];
				//登录成功
				hotel_set_userinfo(0, $record);
				$url = $this->createMobileUrl('search');
				die(json_encode(array("result" => 1, "url" => $url)));
			} else {
				die(json_encode(array("result" => 0, "error" => "登录失败，请检查您输入的用户名和密码！")));
			}
		} else {
			include $this->template('login');
		}
	}

	//发送短信验证码
	public function doMobilecode() {
		global $_GPC, $_W;
		$mobile = $_GPC['mobile'];
		$weid = $this->_weid;
		$code = random(4);
		if (empty($mobile)) {
			exit('请输入手机号');
		}
		$sql = 'DELETE FROM ' . tablename('hotel12_code') . ' WHERE `mobile` = :mobile and `createtime`< :time and `weid` = :weid ';
		$delete = pdo_query($sql,array('mobile' => $mobile, 'time'=> TIMESTAMP - 1800, 'weid'=> $weid));
		$sql = 'SELECT * FROM ' . tablename('hotel12_code') . ' WHERE `mobile` = :mobile AND `weid` = :weid ';
		$pars = array();
		$pars['mobile'] = $mobile;
		$pars['weid'] = $weid;
		$row = pdo_fetch($sql, $pars);
		$record = array();
		if ($row['total'] >= 5) {
			message(error(1,'您发送的验证码太频繁'), '', 'ajax');
			exit;
			$code = $row['code'];
			$record['total'] = $row['total'] + 1;
		} else {
			$record['weid'] = $weid;
			$record['code'] = $code;
			$record['createtime'] = TIMESTAMP;
			$record['total'] = $row['total'] + 1;
			$record['mobile'] = $mobile;
		}
		if (!empty($row)) {
			pdo_update('hotel12_code', $record, array('id' => $row['id']));
		} else {
			pdo_insert('hotel12_code', $record);
		}
		if (!empty($mobile)) {
			load()->model('cloud');
			cloud_prepare();
			$postdata = array(
				'verify_code' => '万能小店订单验证码为' . $code ,
			);
			$result = cloud_sms_send($mobile, '800010', $postdata);
			if (is_error($result)) {
				message($result, '', 'ajax');
			} else {
				message(error(0, '发送成功'), '', 'ajax');
			}
		}
	}

	//检查用户是否登录
	public function check_login() {
		$check = check_hotel_user_login($this->_set_info);
		if ($check == 0) {
			$url = $this->createMobileUrl('index');
			header("Location: $url");
		} else {
			if (empty($this->_user_info)) {
				$weid = $this->_weid;
				$from_user = $this->_from_user;
				$user_info = pdo_fetch("SELECT * FROM " . tablename('storex_member') . " WHERE from_user = :from_user AND weid = :weid limit 1", array(':from_user' => $from_user, ':weid' => $weid));
				$this->_user_info = $user_info;
			}
		}
	}
	public function payResult($params) {
		global $_GPC, $_W;
		load()->model('mc');
		mload()->model('card');
		if ($params['type'] == 'credit') {
			$paytype = 1;
		} elseif ($params['type'] == 'wechat') {
			$paytype = 21;
		} elseif ($params['type'] == 'alipay') {
			$paytype = 22;
		} elseif ($params['type'] == 'delivery') {
			$paytype = 3;
		}
		$recharge_info = pdo_get('mc_credits_recharge', array('uniacid' => $_W['uniacid'], 'tid' => $params['tid']), array('id', 'backtype', 'fee'));
		if (!empty($recharge_info)) {
			if ($params['result'] == 'success' && $params['from'] == 'notify') {
				$fee = $params['fee'];
				$total_fee = $fee;
				$data = array('status' => $params['result'] == 'success' ? 1 : -1);
				//如果是微信支付，需要记录transaction_id。
				if ($params['type'] == 'wechat') {
					$data['transid'] = $params['tag']['transaction_id'];
					$params['user'] = mc_openid2uid($params['user']);
				}
				pdo_update('mc_credits_recharge', $data, array('tid' => $params['tid']));
				$paydata = array('wechat' => '微信', 'alipay' => '支付宝', 'baifubao' => '百付宝', 'unionpay' => '银联');
				//余额充值
				if (empty($recharge_info['type']) || $recharge_info['type'] == 'credit') {
					$setting = uni_setting($_W['uniacid'], array('creditbehaviors', 'recharge'));
					$credit = $setting['creditbehaviors']['currency'];
					$card_setting = card_setting_info();
					$card_recharge = $card_setting['params']['cardRecharge'];
					$recharge_params = array();
					if ($card_recharge['params']['recharge_type'] == 1) {
						$recharge_params = $card_recharge['params'];
					}
					if (empty($credit)) {
						message('站点积分行为参数配置错误,请联系服务商', '', 'error');
					} else {
						if ($recharge_params['recharge_type'] == '1') {
							$recharges = $recharge_params['recharges'];
						}
						if ($recharge_info['backtype'] == '2') {
							$total_fee = $fee;
						} else {
							foreach ($recharges as $key => $recharge) {
								if ($recharge['backtype'] == $recharge_info['backtype'] && $recharge['condition'] == $recharge_info['fee']) {
									if ($recharge_info['backtype'] == '1') {
										$total_fee = $fee;
										$add_credit = $recharge['back'];
									} else {
										$total_fee = $fee + $recharge['back'];
									}
								}
							}
						}
						if ($recharge_info['backtype'] == '1') {
							$add_str = ",充值成功,返积分{$add_credit}分,本次操作共增加余额{$total_fee}元,积分{$add_credit}分";
							$remark = '用户通过' . $paydata[$params['type']] . '充值' . $fee . $add_str;
							$record[] = $params['user'];
							$record[] = $remark;
							mc_credit_update($params['user'], 'credit1', $add_credit, $record);
							mc_credit_update($params['user'], 'credit2', $total_fee, $record);
							mc_notice_recharge($recharge_info['openid'], $recharge_info['uid'], $total_fee, '', $remark);
						} else {
							$add_str = ",充值成功,本次操作共增加余额{$total_fee}元";
							$remark = '用户通过' . $paydata[$params['type']] . '充值' . $fee . $add_str;
							$record[] = $params['user'];
							$record[] = $remark;
							$record[] = $this->module['name'];
							mc_credit_update($params['user'], 'credit2', $total_fee, $record);
							mc_notice_recharge($recharge_info['openid'], $params['user'], $total_fee, '', $remark);
						}
					}
				}
			}
			$url = $this->createMobileurl('display', array('pay_type' => 'recharge'));
			//如果消息是用户直接返回（非通知），则提示一个付款成功
			if ($params['from'] == 'return') {
				if ($params['result'] == 'success') {
					message('支付成功！', $url, 'success');
				} else {
					message('支付失败！', $url, 'error');
				}
			}
		} else {
			$weid = intval($_W['uniacid']);
			$order = pdo_get('storex_order', array('id' => $params['tid'], 'weid' => $weid));
			$storex_bases = pdo_get('storex_bases', array('id' => $order['hotelid'], 'weid' => $weid), array('id', 'store_type', 'title'));
			pdo_update('storex_order', array('paystatus' => 1, 'paytype' => $paytype), array('id' => $params['tid']));
			$setInfo = pdo_get('storex_set', array('weid' => $_W['uniacid']), array('email', 'mobile', 'nickname', 'template', 'confirm_templateid', 'templateid'));
			$starttime = $order['btime'];
			if ($setInfo['email']) {
				$body = "<h3>店铺订单</h3> <br />";
				$body .= '订单编号：' . $order['ordersn'] . '<br />';
				$body .= '姓名：' . $order['name'] . '<br />';
				$body .= '手机：' . $order['mobile'] . '<br />';
				$body .= '名称：' . $order['style'] . '<br />';
				$body .= '订购数量' . $order['nums'] . '<br />';
				$body .= '原价：' . $order['oprice'] . '<br />';
				$body .= '优惠价：' . $order['cprice'] . '<br />';
				if ($storex_bases['store_type'] == 1) {
					$body .= '入住日期：' . date('Y-m-d', $order['btime']) . '<br />';
					$body .= '退房日期：' . date('Y-m-d', $order['etime']) . '<br />';
				}
				$body .= '总价:' . $order['sum_price'];
				// 发送邮件提醒
				if (!empty($setInfo['email'])) {
					load()->func('communication');
					ihttp_email($setInfo['email'], '万能小店订单提醒', $body);
				}
			}
			if ($setInfo['mobile']) {
				// 发送短信提醒
				if (!empty($setInfo['mobile'])) {
					load()->model('cloud');
					cloud_prepare();
					$body = 'df';
					$body = '用户' . $order['name'] . ',电话:' . $order['mobile'] . '于' . date('m月d日H:i') . '成功支付万能小店订单' . $order['ordersn']
						. ',总金额' . $order['sum_price'] . '元' . '.' . random(3);
					cloud_sms_send($setInfo['mobile'], $body);
				}
			}

			if ($params['from'] == 'return') {
				if ($storex_bases['store_type'] == 1) {
					$goodsinfo = pdo_get('storex_room', array('id' => $order['roomid'], 'weid' => $weid));
				} else {
					$goodsinfo = pdo_get('storex_goods', array('id' => $order['roomid'], 'weid' => $weid));
				}
				$score = intval($goodsinfo['score']);
				$acc = WeAccount::create($_W['acid']);
				if ($params['result'] == 'success' && $_SESSION['ewei_hotel_pay_result'] != $params['tid']) {
					//发送模板消息提醒
					if (!empty($setInfo['template']) && !empty($setInfo['confirm_templateid'])) {
						// $acc = WeAccount::create($_W['acid']);
						$time = '';
						$time.= date('Y年m月d日',$order['btime']);
						$time.='-';
						$time.= date('Y年m月d日',$order['etime']);
						$data = array(
							'first' => array('value' =>'你好，你已成功提交订单'),
							'keyword1' => array('value' => $order['style']),
							'keyword2' => array('value' => $time),
							'keyword3' => array('value' => $order['name']),
							'keyword4' => array('value' => $order['sum_price']),
							'keyword5' => array('value' => $order['ordersn']),
							'remark' => array('value' => '如有疑问，请咨询店铺前台'),
						);
						$acc->sendTplNotice($_W['uniacid'], $setInfo['confirm_templateid'], $data);

					} else {
							$info = '您在' . $storex_bases['title'] . '预订的' . $goodsinfo['title'] . "已预订成功";
							$custom = array(
								'msgtype' => 'text',
								'text' => array('content' => urlencode($info)),
								'touser' => $_W['openid'],
							);
							$status = $acc->sendCustomNotice($custom);
						}

					//TM00217
					$clerks = pdo_getall('storex_clerk', array('weid' => $_W['uniacid'], 'status'=>1));
					if (!empty($clerks)) {
						foreach ($clerks as $k => $info) {
							$permission = iunserializer($info['permission']);
							if (!empty($permission[$order['hotelid']])) {
								$is_permit = false;
								foreach ($permission[$order['hotelid']] as $permit) {
									if ($permit == 'wn_storex_permission_order') {
										$is_permit = true;
										continue;
									}
								}
								if (empty($is_permit)) {
									unset($clerks[$k]);
								}
							}
						}
					}
					if (!empty($setInfo['nickname'])) {
						$from_user = pdo_get('mc_mapping_fans', array('nickname' => $setInfo['nickname'], 'uniacid' => $_W['uniacid']));
						if (!empty($from_user)) {
							$clerks[]['from_user'] = $from_user['openid'];
						}
					}
					if (!empty($setInfo['template']) && !empty($setInfo['templateid'])) {
						$tplnotice = array(
							'first' => array('value' => '您好，店铺有新的订单等待处理'),
							'order' => array('value' => $order['ordersn']),
							'Name' => array('value' => $order['name']),
							'datein' => array('value' => date('Y-m-d', $order['btime'])),
							'dateout' => array('value' => date('Y-m-d', $order['etime'])),
							'number' => array('value' => $order['nums']),
							'room type' => array('value' => $order['style']),
							'pay' => array('value' => $order['sum_price']),
							'remark' => array('value' => '为保证用户体验度，请及时处理！')
						);
						foreach ($clerks as $clerk) {
							$acc->sendTplNotice($clerk['from_user'], $setInfo['templateid'], $tplnotice);
						}
					} else {
						foreach ($clerks as $clerk) {
							$info = '店铺有新的订单,为保证用户体验度，请及时处理!';
							$custom = array(
								'msgtype' => 'text',
								'text' => array('content' => urlencode($info)),
								'touser' => $clerk['from_user'],
							);
							$status = $acc->sendCustomNotice($custom);
						}
					}

					for ($i = 0; $i < $order['day']; $i++) {
						$day = pdo_get('storex_room_price', array('weid' => $weid, 'roomid' => $order['roomid'], 'roomdate' => $starttime));
						pdo_update('storex_room_price', array('num' => $day['num'] - $order['nums']), array('id' => $day['id']));
						$starttime += 86400;
					}
					if ($score) {
						$from_user = $_W['openid'];
						pdo_fetch("UPDATE " . tablename('storex_member') . " SET score = (score + " . $score . ") WHERE from_user = '" . $from_user . "' AND weid = " . $weid . "");
						//会员送积分
						$_SESSION['ewei_hotel_pay_result'] = $params['tid'];
						//判断公众号是否卡其会员卡功能
						$card_setting = pdo_get('mc_card', array('uniacid' => intval($_W['uniacid'])));
						$card_status = $card_setting['status'];
						//查看会员是否开启会员卡功能
						$membercard_setting = pdo_get('mc_card_members', array('uniacid' => intval($_W['uniacid']), 'uid' => $params['user']));
						$membercard_status = $membercard_setting['status'];
						if ($membercard_status && $card_status) {
							$room_credit = pdo_get('storex_room', array('weid' => $_W['uniacid'], 'id' => $order['roomid']));
							$room_credit = $room_credit['score'];
							$member_info = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $params['user']));
							pdo_update('mc_members', array('credit1' => $member_info['credit1'] + $room_credit), array('uniacid' => $_W['uniacid'], 'uid' => $params['user']));
						}
					}
					//核销卡券
					if (!empty($order['coupon'])) {
						pdo_update('storex_coupon_record', array('status' => 3), array('id' => $order['coupon']));
					}
				}
				if ($paytype == 3) {
					message('提交成功！', '../../app/' . $this->createMobileUrl('detail', array('hid' => $room['hotelid'])), 'success');
				} else {
					message('支付成功！', $this->createMobileurl('display', array('orderid' => $params['tid'], 'id' => $order['hotelid'])), 'success');
				}
			}
		}
	}

	//用户注册
	public function doMobileregister() {
		global $_GPC, $_W;
		if (checksubmit()) {
			$weid = $this->_weid;
			$from_user = $this->_from_user;
			$set = $this->_set_info;
			$member = array();
			$member['from_user'] = $from_user;
			$member['username'] = $_GPC['username'];
			$member['password'] = $_GPC['password'];
			if (!preg_match(REGULAR_USERNAME, $member['username'])) {
				die(json_encode(array("result" => 0, "error" => "必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。")));
			}

			// if (!preg_match(REGULAR_USERNAME, $member['from_user'])) {
			//	die(json_encode(array("result" => 0, "error" => "微信号码获取失败。")));
			//}

			if (hotel_member_check(array('from_user' => $member['from_user'], 'weid' => $weid))) {
				die(json_encode(array("result" => 0, "error" => "非常抱歉，此用微信号已经被注册，你可以直接使用注册时的用户名登录，或者更换微信号注册！")));
			}

			if (hotel_member_check(array('username' => $member['username'], 'weid' => $weid))) {
				die(json_encode(array("result" => 0, "error" => "非常抱歉，此用户名已经被注册，你需要更换注册用户名！")));
			}

			if (istrlen($member['password']) < 6) {
				die(json_encode(array("result" => 0, "error" => "必须输入密码，且密码长度不得低于6位。")));
			}
			$member['salt'] = random(8);
			$member['password'] = hotel_member_hash($member['password'], $member['salt']);

			$member['weid'] = $weid;
			$member['mobile'] = $_GPC['mobile'];
			$member['realname'] = $_GPC['realname'];
			$member['createtime'] = time();
			$member['status'] = 1;
			$member['isauto'] = 0;
			pdo_insert('storex_member', $member);
			$member['id'] = pdo_insertid();
			$member['user_set'] = $set['user'];

			//注册成功
			hotel_set_userinfo(1, $member);

			$url = $this->createMobileUrl('search');
			die(json_encode(array("result" => 1, "url" => $url)));
		} else {
			//$css_url = $this->_css_url;
			include $this->template('register');
		}
	}

	//错误信息提示页
	public function doMobileError() {
		global $_GPC, $_W;
		$msg = $_GPC['msg'];
		include $this->template('error');
	}

	public function doMobileAjaxdelete() {
		global $_GPC;
		$delurl = $_GPC['pic'];
		if (file_delete($delurl)) {
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function web_message($error, $url = '', $errno = -1) {
		$data = array();
		$data['errno'] = $errno;
		if (!empty($url)) {
			$data['url'] = $url;
		}
		$data['error'] = $error;
		echo json_encode($data);
		exit;
	}
	
	protected function pay($params = array(), $mine = array()) {
		global $_W;
		if (!$this->inMobile) {
			message(error(-1, '支付功能只能在手机上使用'), '', 'ajax');
		}
		$params['module'] = $this->module['name'];
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		//如果价格为0 直接执行模块支付回调方法
		if ($params['fee'] <= 0) {
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
		$pars[':openid'] = $_W['member']['uid'];
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid AND `openid`=:openid';
		$log = pdo_fetch($sql, $pars);
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
		if ($log['status'] == '1') {
			message(error(-1, '这个订单已经支付成功, 不需要重复支付.'), '', 'ajax');
		}
		$payment = uni_setting(intval($_W['uniacid']), array('payment', 'creditbehaviors'));
		if (!is_array($payment['payment'])) {
			message(error(-1, '没有有效的支付方式, 请联系网站管理员.'), '', 'ajax');
		}
		$pay = $payment['payment'];
		if (empty($_W['member']['uid'])) {
			$pay['credit'] = false;
		}
		$pay['delivery']['switch'] = 0;
		foreach ($pay as $paytype => $val) {
			if (empty($val['switch'])) {
				unset($pay[$paytype]);
			} else {
				$pay[$paytype] = array();
				$pay[$paytype]['switch'] = $val['switch'];
			}
		}
		if (!empty($pay['credit'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
		}
		$pay_data['pay'] = $pay;
		$pay_data['credits'] = $credtis;
		$pay_data['params'] = json_encode($params);
		return $pay_data;
	}
}