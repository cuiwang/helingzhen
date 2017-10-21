<?php
/**
 * 单品代销模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');
require 'function.php';

class Tc_consignmentModuleSite extends WeModuleSite {

	public function doWebIndex() {
		global $_W,$_GPC;
		$table_goods = "tc_singleproduct_goods" ;
		$table_orders = "tc_singleproduct_orders" ;
		$page = intval($_GPC['page']) ? intval($_GPC['page']) : 1;
		$id = intval($_GPC['id']);
		$total = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($table_goods)." WHERE uniacid = :uniacid", array(':uniacid'=>$_W['uniacid']));
		$offset=($page-1)*10;
		$list = pdo_fetchall("SELECT a.id, a.rid, a.gname, a.createtime, a.price, count(l.id) AS logsnum FROM ".tablename($table_goods)." AS a 
			LEFT JOIN ".tablename($table_orders)." AS l ON a.id = l.gid WHERE a.uniacid = :uniacid ORDER BY a.id DESC LIMIT ".$offset.",10 ",array(':uniacid'=>$_W['uniacid']));
		$pagination = pagination($total,$page,10);
		include $this->template('list');
	}

	public function doWebClerk() {
		global $_W, $_GPC;
		$ops = array('显示客服列表'=>'kefu', 'qr_img', 'check_qr', 'del');
		$op = isset($_GPC['op']) && in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'kefu';
		$id = intval($_GPC['id']);
		$table = 'tc_singleproduct_clerk';

		if('kefu' == $op){
			$list = pdo_fetchall("SELECT * FROM " . tablename($table) . " WHERE uniacid = :uniacid AND gid = :gid",array(':uniacid'=>$_W['uniacid'],':gid'=>$id));
			include $this->template('admin');

		}elseif ('qr_img' == $op) {
			$url = $_W['siteroot'].'app/'.$this->createMobileUrl('kefu',array('op'=>'add','id'=>$id));//将完整链接用二维码图片保存
			$img_url = mk_qr($url,md5($url),$id);
			// $img_url = mk_qr($url,'dev_qr_'.$_W['uniacid'].'_'.$activity.'_add');
			exit($img_url);

		}elseif ('check_qr' == $op) {
			$url = $_W['siteroot'].'app/'.$this->createMobileUrl('kefu',array('op'=>'check','id'=>$id));
			$img_url = mk_qr($url,md5($url),$id);
			// $img_url = mk_qr($url,'dev_qr_'.$_W['uniacid'].'_'.$activity.'_check');
			exit($img_url);

		}elseif ('del' == $op) {
			$openid = $_GPC['kefu'];
			$exists = pdo_fetch("SELECT * FROM ".tablename($table)." WHERE uniacid =:uniacid AND openid = :openid AND gid = :gid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid, 'gid'=>$id));
			if(empty($exists)){
				exit(json_encode(error(-1,'要删除的客服不存在')));//error()函数的内容为 return array('errno'=>$param1,'message'=>$param2);
			}else{
				$res = pdo_delete($table, array('openid'=>$openid,'uniacid'=>$_W['uniacid'], 'gid'=>$id));
				if($res){
					exit(json_encode(array('errno'=>0,'message'=>'success')));//同error写法
				}else{
					exit(error(-1,'删除失败'));
				}
			}
		}else{
			echo '未知请求';
		}
	}

	public function doWebOrders(){
		global $_W,$_GPC;
		$goodstable = 'tc_singleproduct_goods';
		$ordertable = 'tc_singleproduct_orders';
		$op = $_GPC['op'] == 'del' ? 'del' :'display';
		$id = intval($_GPC['id']);
		if($op == 'display'){
			$page = intval($_GPC['page']) ? intval($_GPC['page']) : 1;
			$total = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($ordertable)." WHERE uniacid = :uniacid AND gid = :gid", array(':uniacid'=>$_W['uniacid'], ':gid'=>$id));
			$offset=($page-1)*10;
			$list = pdo_fetchall("SELECT * FROM ".tablename($ordertable)." WHERE uniacid = :uniacid AND gid = :gid ORDER BY id DESC LIMIT ".$offset.",10 ",array(':uniacid'=>$_W['uniacid'], ':gid'=>$id));
			$pagination = pagination($total,$page,10);
			include $this->template('orders');
		}else{
			$oid = intval($_GPC['oid']);
			$is_exit = pdo_fetch("SELECT id, status,qrcode FROM ".tablename($ordertable)." WHERE id = :id AND uniacid = :uniacid",array('id'=>$oid,'uniacid'=>$_W['uniacid']));
			if(!$is_exit){
				message("订单不存在",$this->createWebUrl('orders',array('id'=>$id)),"error");
			}elseif($is_exit['status'] == 1){
				message("不能删除已付款未消费的订单",$this->createWebUrl('orders',array('id'=>$id)),"error");
			}else{
				if(pdo_delete($ordertable,array('id'=>$oid,'uniacid'=>$_W['uniacid']))){
					$qrcode = MODULE_ROOT . "/qrcode/".$is_exit['qrcode'] ;
					is_file($qrcode)and unlink($qrcode);
					message("删除成功",$this->createWebUrl('orders',array('id'=>$id)),"success");
				}else{
					message("系统错误，请联系管理员核实",$this->createWebUrl('orders',array('id'=>$id)),"error");
				}
			}
			
		}
		
		
	}

	public function doMobileIndex() {
		global $_W, $_GPC;
		$goodstable = 'tc_singleproduct_goods';
		$ordertable = 'tc_singleproduct_orders';
		$id = intval($_GPC['id']);
		$op = $_GPC['op'] == 'buy' ? 'buy' :'display';
		if(!$_W['openid']){
			message("请在微信打开此页面");
		}
		if(!$id){
			message("参数传送错误");
		}
		$fields = pdo_fetch("SELECT * FROM " .tablename($goodstable) . " WHERE id = :id LIMIT 1", array(':id' =>$id));
		$soldcount = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($ordertable)." WHERE gid = :gid AND status >0 ", array(':gid'=>$id));
		if($op == 'buy'){
			if($_GPC['tc_sid'] != '' && $_GPC['tc_sid'] == $_SESSION['tc_sid']){
				unset($_SESSION['tc_sid']);
			}else{
				echo "error";
				exit;
			}
			if($fields['gstatus'] == 0){
				message('还未开放购买，请稍后再试');
			}
			if($fields['count'] < 1){
				message('商品已售磬！');
			}
			$chargerecord = pdo_fetch("SELECT * FROM ".tablename($ordertable)." WHERE openid = :openid AND gid = :gid AND status = 0", array(
				':openid' => $_W['openid'],
				':gid' => $fields['id']
			));
			if (empty($chargerecord)) {
				$chargerecord = array(
					'uniacid' =>$_W['uniacid'],
					'gid' => $fields['id'],
					'openid' => $_W['openid'],
					'amount' => $fields['price'],
					'status' => 0,
					'tid' => date('YmdHi').random(8, 1),
					'createtime' => TIMESTAMP,
				);
				$url = $_W['siteroot'].'app/'.$this->createMobileUrl('checkorder',array('tid'=>$chargerecord['tid'],'openid'=>$_W['openid'],'id'=>$id));
				$chargerecord['qrcode'] = mk_qr($url,$chargerecord['tid'],$id);
				if (!pdo_insert($ordertable, $chargerecord)) {
					message('创建充值订单失败，请重试！', $this->createMobileUrl('index',array('id' => $id )), 'error');
				}
			}
			$params = array(
				'tid' => $chargerecord['tid'],
				'ordersn' => $chargerecord['tid'],
				'title' => $fields['gname'],
				'fee' => $chargerecord['amount'],
			);
			$this->pay($params);
			exit();
		}else{
			$tc_sid = $_SESSION['tc_sid'] = mt_rand(1000, 9999);
			include $this->template('index');
		}		
	}

	public function payResult($params) {
		$order = pdo_fetch("SELECT * FROM ".tablename('tc_singleproduct_orders')." WHERE tid = :tid", array(':tid' => $params['tid']));
		if ($params['result'] == 'success' && $params['from'] == 'notify') {			
			if ($params['fee'] != $order['amount']) {
				exit('用户支付的金额与订单金额不符合');
			}
			$record = array(
			'transid' => $params['tag']['transaction_id'],
			'status' => 1,
			);
			pdo_update('tc_singleproduct_orders',$record,array('tid' => $params['tid']));
			pdo_query("UPDATE ".tablename('tc_singleproduct_goods')." SET count = count-1 WHERE id = :id", array(':id' =>$order['gid']));
		}
		if (empty($params['result']) || $params['result'] != 'success') {
			message('支付失败！', $this->createMobileUrl('index',array('id' => $order['gid'])), 'error');
		}

		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				message('支付成功！', $this->createMobileUrl('myrecord',array('id' => $order['gid'])), 'success');
			} else {
				message('支付失败！', $this->createMobileUrl('index',array('id' => $order['gid'])), 'error');
			}
		}
	}

	public function doMobileMyrecord(){
		global $_W,$_GPC;
		$goodstable = 'tc_singleproduct_goods';
		$ordertable = 'tc_singleproduct_orders';
		$id = intval($_GPC['id']);
		if(!$_W['openid']){
			message("请在微信打开此页面");
		}
		$goods = pdo_fetch("SELECT * FROM " .tablename($goodstable) . " WHERE id = :id LIMIT 1", array(':id' =>$id));
		$result = pdo_fetchall("SELECT * FROM ".tablename($ordertable)." WHERE gid = :gid AND openid = :openid AND status = 1 ORDER BY id DESC", array(':gid' => $id, ':openid'=>$_W['openid']));

		include $this->template('my');
	}

	public function doMobileCheckorder(){
		global $_W,$_GPC;
		$ordertable = 'tc_singleproduct_orders';
		$clerktable = 'tc_singleproduct_clerk';
		$data['id'] = intval($_GPC['id']);
		$data['openid'] = trim($_GPC['openid']);
		$data['tid'] = trim($_GPC['tid']);
		$clerk = pdo_fetch("SELECT * FROM ".tablename($clerktable)." WHERE gid = :gid AND openid = :openid LIMIT 1", array(':gid'=>$data['id'],':openid'=>$_W['openid']));
		if($clerk){
			$has_exists = pdo_fetch("SELECT * FROM ".tablename($ordertable)." WHERE tid = :tid AND openid = :openid AND status = 1", array(':tid'=>$data['tid'],':openid'=>$data['openid']));
			if($has_exists){
				$result = pdo_update($ordertable,array('status' =>2, 'clerkopenid'=>$_W['openid']), array('tid'=>$data['tid']));
				if($result){
					$res['errno'] = 0;
					$res['msg'] = '订单已经成功核销';
				}else{
					$res['errno'] = 1;
					$res['msg'] = '系统错误，请联系管理员核实';
				}
			}else{
				$res['errno'] = 1;
				$res['msg'] = '找不到此订单，请联系管理员核实';
			}
		}else{
			$res['errno'] = 1;
			$res['msg'] = '你不是本商品核销员';
		}
		include $this->template('result');
	}

	public function doMobileKefu() {
		global $_W, $_GPC;

		$ops = array('判断当前扫码用户是否为客服'=>'check', '扫码，注册成客服'=>'add');
		$op = isset($_GPC['op']) && in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'check';
		$id = intval($_GPC['id']);
		$table = 'tc_singleproduct_clerk';

		if('check' == $op){
			$exists = pdo_fetch("SELECT * FROM ".tablename($table)." WHERE uniacid = :uniacid AND openid = :openid AND gid = :gid",array(':uniacid'=>$_W['uniacid'],':openid'=>$_W['openid'],':gid'=>$id));
			$msg = '是本商品核销员';
			if(empty($exists)){
				// $msg = '不是当前活动('.$activity.')的客服';
				$res['errno'] = 1;
				$res['msg'] = '不是本商品核销员';
			}else{
				$res['errno'] = 0;
				$res['msg'] = '是本商品核销员';
			}
			include $this->template('result');

		}elseif ('add' == $op) {
			$data['openid'] = $_W['openid'];
			$data['uniacid'] = $_W['uniacid'];
			$data['gid'] = $id;
			$res['msg'] = '注册成功';
			$res['errno'] = 0;
			$has_exists = pdo_fetch("SELECT * FROM ".tablename($table)." WHERE uniacid = :uniacid AND openid = :openid AND gid = :gid ",array(':uniacid'=>$data['uniacid'],':openid'=>$data['openid'],':gid'=>$id));
			if(!$has_exists){
				//若未注册，则注册
				$_res = false;
				if(!empty($data['openid'])){
					$_res = pdo_insert($table, $data);
				}
				if(!$_res){
					//保存失败
					$res['msg'] = '注册失败';
					$res['errno'] = 1;
				}
			}
			
			include $this->template('result');

		}else {
			echo '未知请求';
		}
	}


}