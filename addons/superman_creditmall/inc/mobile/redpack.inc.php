<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileRedpack extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = $this->_share;
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('confirm', 'display', 'transfer', 'send'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $this->checkauth();
            $header_title = '微信红包';
            //$id = intval($_GPC['id']);
            $order_id = intval($_GPC['orderid']);
            $order = superman_order_fetch($order_id);
            if (!$order) {
                $this->message('订单不存在或已删除', '', 'warning');
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->message('非法请求', '', 'warning');
            }
            superman_order_set($order);
            $back_url = $this->createMobileUrl('list', array('type' => 5));
            $_share['link'] = $_W['siteroot'].'app/'.$back_url;
            include $this->template('detail-redpack');
        } else if ($act == 'confirm') {
            $total = 1;
            if (!defined('LOCAL_DEVELOPMENT')) {
                if($_W['container'] != 'wechat' || !IN_MOBILE) {
                    $this->json_output(ERRNO::NOT_IN_WECHAT);
                }
            }
            if (!$_W['member']['uid']) {
                $this->json_output(ERRNO::NOT_LOGIN);
            }
            if (!checksubmit('token')) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            if ($this->module['config']['subscribe']['check'] && (!isset($_W['fans']['follow']) || !$_W['fans']['follow'])) {
                $this->json_output(ERRNO::UNSUBSCRIBE_NOT_EXCHANGE); //未关注不允许兑换
            }
            $id = intval($_GPC['id']);
            $product = superman_product_fetch($id);
            if (!$product) {
                $this->json_output(ERRNO::PRODUCT_NOT_FOUND);
            }
            if ($product['start_time'] > 0 && $product['start_time'] > TIMESTAMP) {
                $this->json_output(ERRNO::PRODUCT_EXCHANGE_NOT_BEGIN, '兑换未开始('.date('Y-m-d H:i:s', $product['start_time']).')');
            }
            if ($product['end_time'] > 0 && $product['end_time'] < TIMESTAMP) {
                $this->json_output(ERRNO::PRODUCT_EXCHANGE_END, '兑换已结束('.date('Y-m-d H:i:s', $product['end_time']).')');
            }
            if ($product['total'] <= 0) {
                $this->json_output(ERRNO::PRODUCT_NOT_TOTAL, '已被抢光，下次早点来哦~');
            }
            if ($product['groupid'] && $product['groupid'] != $_W['member']['groupid']) {
                $this->json_output(ERRNO::PRODUCT_GROUP_LIMIT);
            }
            superman_product_set($product);

            //访问限制判断
            $this->check_member_access($product);

            //红包
            if (!superman_is_redpack($product['type'])) {
                $this->json_output(ERRNO::PARAM_ERROR);
            }

            //未支付订单
            $order_count = superman_order_count_status($_W['member']['uid'], $id, 0);
            if ($order_count > 0) {
                unset($ret);
                $ret = array(
                    'url' => $this->createMobileUrl('order', array('status' => 'no_pay')),
                );
                $this->json_output(ERRNO::ORDER_EXIST_NOT_PAY, '有未支付订单，跳转中...', $ret);
            }

            //检查用户每天购买数量
            if ($product['today_limit'] > 0) {
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'more_status' => 0, //status > 0
                    'product_id' => $product['id'],
                    'start_time' => strtotime(date('0:0:0'))
                );
                $today_total = superman_order_sum('total', $filter);
                if ($today_total >= $product['today_limit']) {
                    $this->json_output(ERRNO::PRODUCT_EXCHANGE_LIMIT);
                }
            }

            //检查最多购买数
            if ($product['max_buy_num'] > 0) {
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'more_status' => 0, //status > 0
                    'product_id' => $product['id'],
                );
                $buy_total = superman_order_count($filter);
                if ($buy_total >= $product['max_buy_num']) {
                    $this->json_output(ERRNO::PRODUCT_EXCHANGE_LIMIT);
                }
            }

            //我的积分
            $mycredit = superman_mycredit($_W['member']['uid'], $product['credit_type'], true);
            if (!$mycredit) {
                $this->json_output(ERRNO::SYSTEM_ERROR);
            }
            if ($mycredit['value'] < $product['credit']) {
                $this->json_output(ERRNO::CREDIT_NOT_ENOUGH, $product['credit_title'].'不足');
            }

            //支付信息
            $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
            $payment = array();
            if ($setting && isset($setting['payment']) && is_array($setting['payment'])) {
                $payment = $setting['payment'];
            }

            //初始化订单数据
            $data = array(
                'uniacid' => $_W['uniacid'],
                'ordersn' => date('ymd') . random(6, 1),
                'uid' => $_W['member']['uid'],
                'product_id' => $id,
                'total' => $total,
                'price' => $product['price'],
                'credit_type' => $product['credit_type'],
                'credit' => $product['credit'],
                'status' => 0,
                'extend' => iserializer(array('redpack_amount' => $product['extend']['redpack_amount'])),
                'dateline' => TIMESTAMP,
            );
            pdo_insert('superman_creditmall_order', $data);
            $order_id = pdo_insertid();
            if (!$order_id) {
                $this->json_output(ERRNO::SYSTEM_ERROR);
            }

            //更新商品数据：拍下减库存
            if ($product['minus_total'] == 2) {
                $new_total = $product['total'] - $total;
                pdo_update('superman_creditmall_product', array(
                    'total' => $new_total>=0?$new_total:0,
                ), array(
                    'id' => $product['id'],
                ));
            }
            if ($this->module['config']['base']['exchange_iplog_open']) {
                //ip记录
                $ip_log = array(
                    'uniacid' => $_W['uniacid'],
                    'ip' => CLIENT_IP,
                    'location' => '',
                    'orderid' => $order_id,
                    'ordersn' => $data['ordersn'],
                    'product_id' => $product['id'],
                    'dateline' => TIMESTAMP
                );
                M::t('superman_creditmall_ip_log')->insert($ip_log);
            }
            $url = $this->createMobileUrl('pay', array('orderid' => $order_id));
            $this->json_output(ERRNO::OK, '', array('url' => $url));
        } else if ($act == 'transfer') {
            if (!empty($_W['openid'])) {
                $fan = mc_fansinfo($_W['openid']);
                if (defined('ONLINE_DEVELOPMENT')) {
                    WeUtility::logging('debug', '[redpack:transfer] mc_fansinfo fan=' . var_export($fan, true));
                }
                if (empty($fan)) {
                    mc_oauth_userinfo();
                }
            } else {
                mc_oauth_userinfo();
            }
            $orderid = intval($_GPC['orderid']);
            $order = superman_order_fetch($orderid);
            if (!$order) {
                $this->message('订单不存在或已删除', '', 'warn');
            }
            $query_string = array(
                'i' => $_GPC['i'],
                'j' => $_GPC['j'],
                'c' => $_GPC['c'],
                'do' => $_GPC['do'],
                'act' => $_GPC['act'],
                'm' => $_GPC['m'],
                'orderid' => $_GPC['orderid'],
                'orig_uniacid' => $_GPC['orig_uniacid'],
                'orig_acid' => $_GPC['orig_acid'],
                'orig_openid' => $_GPC['orig_openid'],
            );
            $sign = SupermanUtil::get_sign($query_string, $_W['config']['setting']['authkey']);
            if ($this->debug) {
                WeUtility::logging('trace', "[transfer] sign=$sign, GPC[sign]={$_GPC['sign']}, openid=".$_W['openid']);
            }
            if ($sign != $_GPC['sign']) {
                $this->message('非法请求！', '', 'warn');
            }
            $this->superman['sendredpack'] = array(
                'orig_uniacid' => $_GPC['orig_uniacid'],
                'orig_acid' => $_GPC['orig_acid'],
                'orig_openid' => $_GPC['orig_openid'],
                'orig_account' => account_fetch($_GPC['orig_acid']),
                'oauth_uniacid' => $_GPC['i'],
                'oauth_acid' => $_GPC['j'],
            );
            if ($this->debug) {
                WeUtility::logging('trace', '[transfer] sendredpack='.var_export($this->superman['sendredpack'], true));
            }
            $params = http_build_query(array(
                'i' => $this->superman['sendredpack']['orig_uniacid'],
                'c' => 'entry',
                'do' => 'order',
                'act' => 'detail',
                'orderid' => $orderid,
                'm' => 'superman_creditmall',
            ));
            $order_url = './index.php?'.$params;
            $order_url = $this->build_site_url(
                $order_url,
                $this->superman['sendredpack']['orig_uniacid'],
                $this->superman['sendredpack']['orig_acid'],
                $this->superman['sendredpack']['orig_account']['original'],
                $this->superman['sendredpack']['orig_openid']
            );
            $params = http_build_query(array(
                'i' => $this->superman['sendredpack']['orig_uniacid'],
                'c' => 'entry',
                'do' => 'redpack',
                'act' => 'display',
                'id' => $order['product_id'],
                'orderid' => $orderid,
                'm' => 'superman_creditmall',
            ));
            $redpack_url = './index.php?'.$params;
            $redpack_url = $this->build_site_url(
                $redpack_url,
                $this->superman['sendredpack']['orig_uniacid'],
                $this->superman['sendredpack']['orig_acid'],
                $this->superman['sendredpack']['orig_account']['original'],
                $this->superman['sendredpack']['orig_openid']
            );
            if ($this->debug) {
                WeUtility::logging('trace', "[transfer] order_url={$order_url}, redpack_url={$redpack_url}");
            }
            if ($order['status'] == 0) {
                superman_order_set($order);
                $product = superman_product_fetch($order['product_id'], array('type', 'isvirtual', 'title'));
                if (!$product) {
                    $this->json_output(ERRNO::PRODUCT_NOT_FOUND);
                }
                $order_data = array(
                    'status' => 2,  //红包无需发货, 2:已发货
                    'pay_time' => TIMESTAMP,
                );
                $credit_title = superman_credit_type($order['credit_type'], $this->superman['sendredpack']['orig_uniacid']);
                $redpack = array(
                    'amount' => $order['extend']['redpack_amount'],
                    'wishing' => superman_redpack_wishing(),
                    'act_name' => $credit_title.'兑换红包',
                );
                $ret = $this->sendRedpack($this->superman['sendredpack']['oauth_uniacid'], $_W['openid'], $redpack, $order);

                //更新订单发送红包结果
                $new_data = array();
                $new_data['extend'] = $order['extend'];
                $new_data['extend']['redpack_result'] = $ret;
                $new_data['extend']['oauth_openid'] = $_W['openid'];
                $new_data['extend'] = iserializer($new_data['extend']);
                pdo_update('superman_creditmall_order', $new_data, array('id' => $orderid));

                //发红包失败，退回积分
                if ($ret['success'] !== true) {
                    //退钱
                    $ret = $this->returnCredit($order['uniacid'], $order, '发红包失败');
                    if ($ret !== true) {
                        WeUtility::logging('fatal', '[transfer] 发红包退积分失败, order='.var_export($order, true));
                    }
                    //跳转到订单详情页
                    $this->message('发红包失败，跳转中...', $order_url, 'info');
                }

                //更新订单状态
                $ret = pdo_update('superman_creditmall_order', $order_data, array('id' => $orderid));
                if ($ret === false) {
                    WeUtility::logging('fatal', '[transfer] 订单状态更新失败, id='.$orderid.', data='.var_export($order_data, true));
                    $this->message('数据库更新失败，请联系管理员', '', 'info');
                }

                //更新销量&库存
                if ($order && $order['product_id']) {
                    superman_product_update_sales($order['product_id'], $order['total']);
                }

                //发客服消息
                $this->sendCustomerStatusNotice($order['uid'], $order['ordersn'], 1, $order_url);

                //redirect
                $this->message('兑换成功，跳转中...', $redpack_url, 'success');
            } else {
                if ($this->debug) {
                    WeUtility::logging('fatal', '[transfer] order='.var_export($order, true));
                }
                $this->message('非法请求！', $order_url, 'warn');
            }
        }
    }
}

$obj = new Creditmall_doMobileRedpack;
$obj->exec();
