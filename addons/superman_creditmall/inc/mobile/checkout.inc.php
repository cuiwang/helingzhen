<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileCheckout extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
        $this->checkauth();
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'check'))?$_GPC['act']:'display';
        //核销员权限查询
        $filter = array(
            'uniacid' => $_W['uniacid'],
            'uid' => $_W['member']['uid'],
        );
        $user = M::t('superman_creditmall_checkout_user')->fetch($filter);
        if (!$user) {
            $this->json_output(ERRNO::INVALID_REQUEST, '没有核销权限');
        }
        $orderid = intval($_GPC['orderid']);
        if ($orderid <= 0) {
            $this->json_output(ERRNO::INVALID_REQUEST);
        }
        $order = M::t('superman_creditmall_order')->fetch($orderid);
        if (!$order) {
            $this->json_output(ERRNO::ORDER_NOT_EXIST);
        }
        superman_order_set($order);
        if ($order['status'] < 1 || $order['status'] > 3) {
            //非自取订单或订单状态有误
            $this->json_output(ERRNO::ORDER_CANNOT_CHECKOUT);
        }
        $order_item = superman_product_fetch($order['product_id']);
        superman_product_set($order_item);
        if (!isset($order_item['extend']['checkout']['user']) || !in_array($user['id'], $order_item['extend']['checkout']['user'])) {
            $this->json_output(ERRNO::INVALID_REQUEST, '没有核销权限');
        }
        $filter = array('orderid' => $orderid);
        $checkout_log = M::t('superman_creditmall_checkout_log')->fetch($filter);
        if ($act == 'display') {
            $header_title = '扫码核销';
            $order['member'] = mc_fetch($order['uid'], array('nickname', 'avatar'));
        } else if ($act == 'check') {
            if ($checkout_log && $order['status'] == 3) { //已核销
                $this->json_output(ERRNO::ORDER_HAD_CHECKOUT);
            }
            if (!$checkout_log) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $order['uid'],
                    'orderid' => $orderid,
                    'ordersn' => $order['ordersn'],
                    'checkout' => $user['uid'],
                    'type' => 1,
                    'remark' => $_GPC['remark'],
                    'dateline' => TIMESTAMP,
                );
                $new_id = M::t('superman_creditmall_checkout_log')->insert($data);
                if (!$new_id) {
                    $this->json_output(ERRNO::SYSTEM_ERROR, '核销失败，请重试');
                }
            }
            if ($order['status'] != 3) {
                $ret = pdo_update('superman_creditmall_order', array('status' => 3), array('id' => $orderid));
                if ($ret === false) {
                    $this->json_output(ERRNO::OK, '更新订单失败，请重试');
                }
            }
            $this->json_output(ERRNO::OK, '核销成功，跳转中...');
        }
        include $this->template('checkout');
    }
}
$obj = new Creditmall_doMobileCheckout;
$obj->exec();