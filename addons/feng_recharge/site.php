<?php
/**
 * 余额充值模块微站定义
 *
 * @author 封遗
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Feng_rechargeModuleSite extends WeModuleSite {
	public $settings;
	public function __construct() {
		global $_W;
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'feng_fightgroups'));
		$this->settings = iunserializer($settings);
	}
	//充值首页
	public function doMobileIndex() {
		global $_W, $_GPC;
		$advs = pdo_fetchall("select * from " . tablename('recharge_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
		include $this->template('index');
	}
	//充值订单提交
	public function doMobileajax() {
		global $_W, $_GPC;
		if (empty($_GPC['price'])) {
	        message('抱歉，参数错误！', '', 'error');
	    }
		$data=array(
			'weid'=>$_W['uniacid'],
			'from_user'=>$_W['fans']['from_user'],
			'price'=>$_GPC['price'],
			'status'=>0,
			'ordersn'=>date('md') . random(4, 1),
			'createtime'=>time(),
		);

		if(pdo_insert('recharge_order',$data)){
			$orderid = pdo_insertid();
			message('充值订单提交成功，跳转到付款页面！',$this->createMobileUrl('pay',array('id'=>$orderid)),'success');
		}else{
			message("充值订单提交失败，跳转到付款页面！");
		}
	}
	//跳转到支付页
	public function doMobilepay() {
		global $_W, $_GPC;
		if (empty($_GPC['id'])) {
	        message('抱歉，参数错误！', '', 'error');
	    }
		$orderid = intval($_GPC['id']);
		$uniacid=$_W['uniacid'];
		$order = pdo_fetch("SELECT ordersn,price FROM " . tablename('recharge_order') . " WHERE id ='{$orderid}'");

		$params['tid'] = $order['ordersn'];
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['title'] = "余额充值";
		$params['ordersn'] = $order['ordersn'];

		include $this->template('pay');	
	}
	//支付结果返回
	public function payResult($params) {
		global $_W, $_GPC;

		$uniacid=$_W['uniacid'];
		$fee = intval($params['fee']);
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '3');
		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		if ($params['from'] == 'return') {
			$order = pdo_fetch("SELECT * FROM " . tablename('recharge_order') . " WHERE ordersn ='{$params['tid']}'");//获取商品ID
			if ($order['status'] != 1) {
				if ($params['result'] == 'success') {
					$data['status'] = 1;
				}
				pdo_update('recharge_order', $data, array('ordersn' => $params['tid']));
				$settingss = $this->settings;
				$profile = fans_search($_W['fans']['from_user'], array('nickname', 'credit1', 'credit2', 'avatar'));
				$uid = mc_openid2uid($_W['fans']['from_user']);
				if($fee >= $settingss['marketprice1']){
					pdo_update('mc_members', array('credit2' => $profile['credit2']+$fee+$settingss['productprice1']), array('uid' => $uid));
				}elseif ($fee >= $settingss['marketprice2']) {
					pdo_update('mc_members', array('credit2' => $profile['credit2']+$fee+$settingss['productprice2']), array('uid' => $uid));
				}elseif ($fee >= $settingss['marketprice3']) {
					pdo_update('mc_members', array('credit2' => $profile['credit2']+$fee+$settingss['productprice3']), array('uid' => $uid));
				}elseif ($fee >= $settingss['marketprice4']) {
					pdo_update('mc_members', array('credit2' => $profile['credit2']+$fee+$settingss['productprice4']), array('uid' => $uid));
				}else{
					pdo_update('mc_members', array('credit2' => $profile['credit2']+$fee), array('uid' => $uid));
				}
			}
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				message('充值成功！', $this->createMobileUrl('myorder'), 'success');
			} else {
				message('充值成功！', '../../app/' . $this->createMobileUrl('myorder'), 'success');
			}
		}
	}
	//我的订单
	public function doMobilemyorder() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$profile = fans_search($_W['fans']['from_user'], array('nickname', 'credit1', 'credit2', 'avatar'));
		if ($operation == 'display') {
			$orders = pdo_fetchall("SELECT * FROM " . tablename('recharge_order') . " WHERE from_user ='{$_W['openid']}' and status < 1");
		}elseif ($operation == 'finish') {
			$orders = pdo_fetchall("SELECT * FROM " . tablename('recharge_order') . " WHERE from_user ='{$_W['openid']}' and status = 1");
		}
		include $this->template('myorder');
	}

	//后台订单管理
	public function doWebOrder() {
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = "";
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND createtime >= '{$starttime}' AND createtime <= '{$endtime}' ";
			}
			if (!empty($_GPC['paytype'])) {
				$condition .= " AND paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND paytype = '{$_GPC['paytype']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND (mobile LIKE '%{$_GPC['member']}%')";
			}
			$goodses = pdo_fetchall("SELECT * FROM ".tablename('recharge_order')." WHERE weid = '{$_W['uniacid']}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('recharge_order') . " WHERE weid = '{$_W['uniacid']}' ");
			$pager = pagination($total, $pindex, $psize);
			$paytype = array (
				'0' => array('css' => 'default', 'name' => '未支付'),
				'1' => array('css' => 'danger','name' => '余额支付'),
				'2' => array('css' => 'info', 'name' => '微信支付'),
				'3' => array('css' => 'warning', 'name' => '支付宝支付')
			);
		}elseif ($operation == 'delete') {
			$orderid = intval($_GPC['id']);
			if (pdo_delete('recharge_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}elseif ($operation == 'set') {
			$orderid = intval($_GPC['id']);
			if (pdo_update("recharge_order", array("status" => 2), array('id' => $orderid))) {
				$this->doMobilesendmessage($orderid);
				message('订单发货成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}
		include $this->template('order');
	}

	//后台幻灯片管理
	public function doWebAdv() {
		global $_W, $_GPC;
		load()->func('tpl');

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('recharge_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb']
				);
				if (!empty($id)) {
					pdo_update('recharge_adv', $data, array('id' => $id));
				} else {
					pdo_insert('recharge_adv', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('recharge_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('recharge_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
			}
			pdo_delete('recharge_adv', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('adv', TEMPLATE_INCLUDEPATH, true);
	}
}