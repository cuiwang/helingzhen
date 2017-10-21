<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileConfirm extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $id = intval($_GPC['id']);
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '确认订单';
            if (!$_W['member']['uid']) {
                $this->json_output(ERRNO::NOT_LOGIN);
            }
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $product = superman_product_fetch($id);
            if (!$product) {
                $this->json_output(ERRNO::PRODUCT_NOT_FOUND);
            }
            //访问限制判断
            $this->check_member_access($product);

            if (isset($this->module['config']['subscribe']['check']) && $this->module['config']['subscribe']['check'] && (!isset($_W['fans']['follow']) || !$_W['fans']['follow'])) {
                $this->json_output(ERRNO::UNSUBSCRIBE_NOT_EXCHANGE); //未关注不允许兑换
            }
            $back_url = $this->createMobileUrl('detail', array('id' => $id));
            if ($product['start_time'] > 0 && $product['start_time'] > TIMESTAMP) {
                $this->json_output(ERRNO::PRODUCT_EXCHANGE_NOT_BEGIN);
            }
            if ($product['end_time'] != 0 && $product['end_time'] < TIMESTAMP) {
                $this->json_output(ERRNO::PRODUCT_EXCHANGE_END);
            }
            if ($product['total'] == 0) {
                $this->json_output(ERRNO::PRODUCT_NOT_TOTAL);
            }
            if ($product['groupid'] && $product['groupid'] != $_W['member']['groupid']) {
                $this->json_output(ERRNO::PRODUCT_GROUP_LIMIT);
            }
            superman_product_set($product);
            $order_count = superman_order_count_status($_W['member']['uid'], $id, 0);
            if ($order_count > 0) {
                $this->json_output(ERRNO::ORDER_EXIST_NOT_PAY, '', array('url' => $this->createMobileUrl('order', array('status' => 'no_pay'))));
            }
            if (!in_array($product['type'], array(1,7,8))) {    //一口价、秒杀、优惠券
                $this->json_output(ERRNO::PARAM_ERROR);
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

            //检查用户最多购买数
            if ($product['max_buy_num'] > 0) {
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'more_status' => 0, //status > 0
                    'product_id' => $product['id'],
                );
                $buy_total = superman_order_sum('total', $filter);
                if ($buy_total >= $product['max_buy_num']) {
                    $this->json_output(ERRNO::PRODUCT_EXCHANGE_LIMIT);
                }
            }

            //详情页点击兑换检查
            if ($_W['isajax'] && $_GPC['check'] == 'yes' ) {
                $this->json_output(ERRNO::OK);
            }

            //非优惠券非虚拟物品需要配送方式和收货地址
            if ($product['type'] != 8 && $product['isvirtual'] != 1) {
                //print_r($product);
                $address = superman_mc_address_fetch_uid($_W['member']['uid']);
                if ($address) {
                    $address['mobile'] = $address['mobile']?superman_hide_mobile($address['mobile']):'';
                }
                //print_r($address);

                $dispatch_list = array();
                if (in_array('0', $product['dispatch_id'])) {
                    $filter = array(
                        'uniacid' => $_W['uniacid'],
                        'isshow' => 1,
                    );
                    $dispatch_list = superman_dispatch_fetchall($filter, 0, -1);
                } else {
                    $filter = array(
                        'isshow' => 1,
                        'id' => $product['dispatch_id'],
                    );
                    $dispatch_list = superman_dispatch_fetchall($filter, 0, -1);
                }

                foreach ($dispatch_list as &$item) {
                    $item['extend'] = $item['extend']!=''?iunserializer($item['extend']):array();
                }
                unset($item);
                //print_r($dispatch_list);
                $address_url = $this->createMobileUrl('address', array(
                    'forward' => base64_encode($this->createMobileUrl('confirm', array(
                        'id' => $id,
                    )))
                ));
            }

            $mycredit = superman_mycredit($_W['member']['uid'], $product['credit_type'], true);
            if (!$mycredit) {
                $this->json_output(ERRNO::SYSTEM_ERROR);
            }

            if (checksubmit('submit')) {

                $dispatch_id = intval($_GPC['dispatch_id']);
                $total = intval($_GPC['total']);
                $remark = trim($_GPC['remark']);
                $address_id = intval($_GPC['address_id']);
                if ($product['today_limit'] > 0) {
                    $today_total = isset($today_total)?$today_total:0;
                    $today_total += $total;
                    if ($today_total > $product['today_limit']) {
                        $this->json_output(ERRNO::PRODUCT_EXCHANGE_LIMIT);
                    }
                }
                if ($product['max_buy_num'] > 0) {
                    $buy_total = isset($buy_total)?$buy_total:0;
                    $buy_total += $total;
                    if ($buy_total > $product['max_buy_num']) {
                        $this->json_output(ERRNO::PRODUCT_EXCHANGE_LIMIT);
                    }
                }
                if ($total > $product['total']) {
                    $this->json_output(ERRNO::PRODUCT_TOTAL_NOT_ENOUGH);
                }
                //非优惠券非虚拟商品
                $pickup_info = $username = $mobile = $zipcode = $alladdr = '';
                if ($product['type'] != 8 && $product['isvirtual'] != 1) {
                    if (!$dispatch_id || $total <= 0) {
                        $this->json_output(ERRNO::PARAM_ERROR);
                    }
                    $dispatch = superman_dispatch_fetch($dispatch_id);
                    if (!$dispatch) {
                        $this->json_output(ERRNO::PRODUCT_DISPATCH_NOT_FOUND);
                    }
                    $dispatch['extend'] = $dispatch['extend']?unserialize($dispatch['extend']):array();

                    if ($dispatch['need_address']) {
                        if ($address_id <= 0) {
                            $this->json_output(ERRNO::ADDRESS_NULL, '', array('url' => $this->createMobileUrl('address', array('id' => $id))));
                        }
                        $address = superman_mc_address_fetch($address_id);
                        if (!$address) {
                            $this->json_output(ERRNO::ADDRESS_NOT_EXIST);
                        }
                        $username = $address['username'];
                        $mobile = $address['mobile'];
                        $zipcode = $address['zipcode'];
                        $alladdr .= $address['province'].' ';
                        $alladdr .= $address['city'].' ';
                        $alladdr .= $address['district'].' ';
                        $alladdr .= $address['address'];
                    } else {
                        $pickup_info = isset($dispatch['extend']['pickup_info'])?$dispatch['extend']['pickup_info']:'';
                    }
                }

                $price = ($total * $product['price']) + $dispatch['fee'];
                $credit = $total * $product['credit'];

                //检查积分
                if ($mycredit['value'] < $credit) {
                    $this->json_output(ERRNO::CREDIT_NOT_ENOUGH, $product['credit_title'].'不足');
                }
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'ordersn' => date('ymd') . random(6, 1),
                    'uid' => $_W['member']['uid'],
                    'product_id' => $id,
                    'total' => $total,
                    'price' => $price,
                    'credit_type' => $product['credit_type'],
                    'credit' => $credit,
                    'remark' => $remark,
                    'username' => $username,
                    'mobile' => $mobile,
                    'zipcode' => $zipcode,
                    'address' => $alladdr,
                    'express_title' => $dispatch['title'],
                    'express_fee' => $dispatch['fee'],
                    'pickup_info' => $pickup_info,
                    'status' => 0,
                    'dateline' => TIMESTAMP,
                );
                pdo_insert('superman_creditmall_order', $data);
                $new_id = pdo_insertid();
                if (!$new_id) {
                    $this->json_output(ERRNO::SYSTEM_ERROR);
                }
                //拍下减库存
                if ($product['minus_total'] == 2) {
                    $new_total = $product['total'] - $total;
                    pdo_update('superman_creditmall_product', array(
                        'total' => $new_total>=0?$new_total:0,
                    ), array(
                        'id' => $product['id'],
                    ));
                }
                //发送模板消息
                $url = $_W['siteroot'].'app/'.$this->createMobileUrl('pay', array('act' => 'pay', 'orderid' => $new_id));
                if ($_W['account']['level'] == 4 && $this->module['config']['template_message']['order_submit_id']
                    && $this->module['config']['template_message']['order_submit_content']) {
                    if ($price > 0) {
                        $order_amount = $credit.$product['credit_title'].'+'.$price.'元';
                    } else {
                        $order_amount = $credit.$product['credit_title'];
                    }
                    $vars = array(
                        '{订单编号}'   => $data['ordersn'],
                        '{订单时间}'  => date('Y-m-d H:i:s', TIMESTAMP),
                        '{订单金额}'  => $order_amount,
                    );
                    $message = array(
                        'uniacid' => $_W['uniacid'],
                        'template_id' => $this->module['config']['template_message']['order_submit_id'],
                        'template_variable' => $this->module['config']['template_message']['order_submit_content'],
                        'vars' => $vars,
                        'receiver_uid' => $_W['member']['uid'],
                        'url' => $url,
                    );
                    $this->sendTemplateMessage($message);
                }  elseif ($_W['account']['level'] == 3 || $_W['account']['level'] == 4) {
                    //发客服消息
                    $this->sendCustomerStatusNotice($_W['member']['uid'], $data['ordersn'], 0, $url);
                }
                if ($this->module['config']['base']['exchange_iplog_open']) {
                    //ip记录
                    $ip_log = array(
                        'uniacid' => $_W['uniacid'],
                        'ip' => CLIENT_IP,
                        'location' => '',
                        'orderid' => $new_id,
                        'ordersn' => $data['ordersn'],
                        'product_id' => $product['id'],
                        'dateline' => TIMESTAMP
                    );
                    M::t('superman_creditmall_ip_log')->insert($ip_log);
                }
                $this->json_output(ERRNO::OK, '订单创建成功，跳转中...', array('url' => $this->createMobileUrl('pay', array('orderid' => $new_id))));
            }
        }
        include $this->template('confirm');
    }
}

$obj = new Creditmall_doMobileConfirm;
$obj->exec();
