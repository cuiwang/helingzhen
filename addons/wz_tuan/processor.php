<?php
/**
 * 新微团购模块处理程序
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class wz_tuanModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		$message = $this -> message;
		$openid = $this -> message['from'];
		$content = $this -> message['content'];
		$msgtype = strtolower($message['msgtype']);
		$event = strtolower($message['event']);
		if ($msgtype == 'text' || $event == 'click') {
			$saler = pdo_fetch('select * from ' . tablename('wz_tuan_saler') . ' where openid=:openid and status=:status', array(':status' => 1, ':openid' => $openid));
			if (empty($saler)) {
				return $this -> salerEmpty();
			} 
			if (!$this -> inContext) {
				$this -> beginContext();
				return $this -> respText('请输入核销码:');
			} else if ($this -> inContext && is_numeric($content)) {
				$order = pdo_fetch('select * from ' . tablename('wz_tuan_order') . ' where hexiaoma=:hexiaoma and uniacid=:uniacid', array(':hexiaoma' => $content, ':uniacid' => $_W['uniacid']));
				if (empty($order)) {
					return $this -> respText('未找到要核销的订单,请重新输入!');
				} 
				$orderid = $order['id'];
				if ($order['is_hexiao'] == 0) {
					$this -> endContext();
					return $this -> respText('订单无需核销!');
				} 
				if ($order['is_hexiao'] == 2) {
					$this -> endContext();
					return $this -> respText('此订单已核销，无需重复核销!');
				} 
				if ($order['status'] != 2) {
					$this -> endContext();
					return $this -> respText('订单状态错误，无法核销!');
				} 
				$storeids = array();
				$salerids = array();
				$goods = pdo_fetch("select hexiao_id from " . tablename('wz_tuan_goods')." where id=:id and uniacid=:uniacid ", array(':uniacid' => $_W['uniacid'], ':id' => $order['g_id']));
				$storeids = array_merge(explode(',', $goods['hexiao_id']), $storeids);
				$salerids = array_merge(explode(',', $saler['storeid']), $salerids);
				$inter = array_intersect($storeids, $salerids);
				if (!empty($storeids)) {
					if (!empty($saler['storeid'])) {
						if (empty($inter)) {
							return $this -> respText('您无此门店的核销权限!');
						} 
					} 
				}
				$time = time();
				pdo_update('wz_tuan_order', array('status' => 4, 'sendtime' => $time, 'gettime' => $time, 'is_hexiao' => 2, 'veropenid' => $openid), array('id' => $order['id']));
				$this -> endContext();
				return $this -> respText('核销成功!');
			} 
		} 
	}
	private function salerEmpty() {
		ob_clean();
		ob_start();
		echo '';
		ob_flush();
		ob_end_flush();
		exit(0);
	}
}