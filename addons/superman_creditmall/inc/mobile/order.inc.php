<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileOrder extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
        $this->checkauth();
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'delete', 'receive',
            'cancel', 'detail', 'checkout', 'status', 'show_checkout'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '我的订单';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            //$allstatus = array(-99,0,2,3);
            $list = array(
                'all' => array(),
                'no_pay' => array(),
                'no_receive' => array(),
                'no_comment' => array(),
            );
            $status = 'all';  //全部订单
            if (isset($_GPC['status']) && array_key_exists($_GPC['status'], $list)) {
                $status = $_GPC['status'];
            }
            $filter = array(
                'uid' => $_W['member']['uid'],
            );
            switch ($status) {
                case 'all':
                    $filter['more_status'] = -2;    //status > -2
                    break;
                case 'no_pay':
                    $filter['status'] = 0;    //status == 0
                    break;
                case 'no_receive':
                    $filter['in_status'] = array(1,2);    //status IN(1,2)
                    break;
                case 'no_comment':
                    $filter['status'] = 3;    //status == 3
                    break;
            }
            $order_list = superman_order_fetchall($filter, '', $start, $pagesize);
            if ($order_list) {
                foreach ($order_list as &$item) {
                    superman_order_set($item);
                    $product = superman_product_fetch($item['product_id']);
                    $item['product'] = $product?superman_product_set($product):array(
                        'id' => $item['product_id'],
                        'type' => 0,
                        'cover' => '',
                        'title' => '',
                        'type_name' => '',
                    );
                }
                unset($item);
                $list[$status] = $order_list;
            }
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list[$status]));
            }
            include $this->template('order');
        } else if ($act == 'detail') {
            $header_title = '订单详情';
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            superman_order_set($order);
            $product = superman_product_fetch($order['product_id']);
            if ($product) {
                superman_product_set($product);
            }
            include $this->template('order');
        } else if ($act == 'delete') {
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order || $order['status'] == -2) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $condition = array(
                'id' => $id,
            );
            $data = array(
                'status' => -2,
                'updatetime' => TIMESTAMP,
            );
            pdo_update('superman_creditmall_order', $data, $condition);
            $url = $this->createMobileUrl('order');
            $this->json_output(ERRNO::OK, '删除成功，跳转中...', array('url' => $url));
        } else if ($act == 'receive') {
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order || $order['status'] == -2) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $condition = array(
                'id' => $id,
            );
            $data = array(
                'status' => 3,
                'updatetime' => TIMESTAMP,
            );
            pdo_update('superman_creditmall_order', $data, $condition);
            $url = $this->createMobileUrl('order', array('status' => 'no_comment'));
            $this->json_output(ERRNO::OK, '确认收货，跳转中...', array('url' => $url));
        } else if ($act == 'cancel') {
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order || $order['status'] == -2) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            //积分+现金兑换时，可能已经扣了积分，但是没有支付成功，这时需要退积分
            if ($order['pay_credit']) {
                $ret = $this->returnCredit($order['uniacid'], $order, "取消订单({$order['ordersn']})");
                if ($ret !== true) {
                    WeUtility::logging('fatal', '取消订单退积分失败, order='.var_export($order, true));
                }
            }
            $condition = array(
                'id' => $id,
            );
            $data = array(
                'status' => -1,
                'updatetime' => TIMESTAMP,
            );
            pdo_update('superman_creditmall_order', $data, $condition);
            $url = $this->createMobileUrl('order', array('status' => 'no_pay'));
            $this->json_output(ERRNO::OK, '取消成功，跳转中...', array('url' => $url));
        } else if ($act == 'checkout') {
            $orderid = intval($_GPC['orderid']);
            if ($orderid <= 0) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = M::t('superman_creditmall_order')->fetch($orderid);
            if (!$order) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['status'] < 1 || $order['status'] > 3) {
                //非自取订单或订单状态有误
                $this->json_output(ERRNO::ORDER_CANNOT_CHECKOUT);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'orderid' => $orderid
            );
            $checkout_log = M::t('superman_creditmall_checkout_log')->fetch($filter);
            if ($checkout_log && $order['status'] == 3) { //已核销
                $this->json_output(ERRNO::ORDER_HAD_CHECKOUT);
            }
            $code = $_GPC['code'];
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'code' => $code
            );
            $check_code = M::t('superman_creditmall_checkout_code')->fetch($filter);
            if (!$check_code) {
                $this->json_output(ERRNO::CODE_NOT_EXIST);
            }
            $order_item = superman_product_fetch($order['product_id']);
            superman_product_set($order_item);
            if (!isset($order_item['extend']['checkout']['code']) || !in_array($check_code['id'], $order_item['extend']['checkout']['code'])) {
                $this->json_output(ERRNO::CODE_CANT_USE);
            }
            if (!$checkout_log) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $order['uid'],
                    'orderid' => $orderid,
                    'ordersn' => $order['ordersn'],
                    'checkout' => $check_code['code'],
                    'type' => 2,
                    'remark' => '',
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
        } else if ($act == 'status') {
            $orderid = intval($_GPC['orderid']);
            if ($orderid <= 0) {
                return -99;
            }
            $order = M::t('superman_creditmall_order')->fetch($orderid);
            if (!$order) {
                return -99;
            }
            exit($order['status']);
        } else if ($act == 'show_checkout') {
            $orderid = intval($_GPC['orderid']);
            if ($orderid <= 0) {
                $this->message('非法请求！', '', 'warn');
            }
            $order = M::t('superman_creditmall_order')->fetch($orderid);
            if (!$order) {
                $this->message('订单不存在或已删除！', '', 'warn');
            }
            if ($order['status'] == 1 || $order['status'] == 2) {
                $this->message('未核销！', '', 'info');
            } else if ($order['status'] == 3) {
                $this->message('已核销！', '', 'success');
            } else {
                $this->message('订单状态非法！', '', 'warn');
            }
        }
    }
}

$obj = new Creditmall_doMobileOrder;
$obj->exec();
