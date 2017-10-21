<?php
/**
 * 【微赞】房产模块定义
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobilePay extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
        $this->checkauth();
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '订单支付';
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::PARAM_ERROR);
            }
            $order = superman_order_fetch($id);
            if (!$order) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            $product = superman_product_fetch($order['product_id']);
            if (!$product) {
                $this->json_output(ERRNO::PRODUCT_NOT_FOUND);
            }
            //访问限制判断
            $this->check_member_access($product);

            if ($order['status'] != 0) {
                if ($_W['isajax']) {
                    $this->json_output(ERRNO::ORDER_NOT_NEED_PAY);
                } else {
                    @header('Location: '.$this->createMobileUrl('order', array('act' => 'detail', 'orderid' => $id)));
                    exit;
                }
            }
            $back_url = $this->createMobileUrl('detail', array('id' => $order['product_id']));
            superman_order_set($order);
            superman_product_set($product);
            if (!$product['isshow']) {
                $this->json_output(ERRNO::PRODUCT_OFFLINE);
            }
            $mycredit = superman_mycredit($_W['member']['uid'], '', true);
            if (!$mycredit) {
                $this->json_output(ERRNO::SYSTEM_ERROR);
            }
            $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
            $payment = array();
            if ($setting && isset($setting['payment']) && is_array($setting['payment'])) {
                $payment = $setting['payment'];
            }
            if (!superman_is_redpack($product['type'])) {
                $back_url = $this->createMobileUrl('detail', array('id' => $order['product_id']));
            } else {
                $back_url = $this->createMobileUrl('order', array('status' => 'no_pay'));
            }
            //点击列表页，点击立即支付
            if ($_W['isajax'] && $_GPC['check'] == 'yes' ) {
                $this->json_output(ERRNO::OK);
            }
            if (checksubmit()) {
                $pay_type = $_GPC['pay_type'];
                if (!in_array($pay_type, array('credit', 'wechat'))) {
                    $this->json_output(ERRNO::ORDER_NOT_FOUND_PAYTYPE);
                }

                //检查虚拟物品库存
                if ($product['isvirtual']) {
					$filter = array(
                    	'product_id' => $product['id'],
                    	'status' => 0
                	);
					$virtual_count = superman_virtual_count($filter);
					if ($virtual_count == 0) {
                        $this->json_output(ERRNO::PRODUCT_NOT_TOTAL);
					}
                }

                //检查积分是否足够支付
                if ($mycredit[$order['credit_type']]['value'] < $order['credit']) {
                    $this->json_output(ERRNO::CREDIT_NOT_ENOUGH, $product['credit_title'].'不足');
                }

                //检查余额是否足够支付
                if ($pay_type == 'credit' && !empty($payment['credit']['switch'])) {
                    if ($mycredit[$setting['creditbehaviors']['currency']]['value'] < $order['price']) {
                        $this->json_output(ERRNO::CREDIT_BALANCE_NOT_ENOUGH);
                    }
                }
                //付款减库存时，需要处理并发支付问题
                if ($product['minus_total'] == 1) {
                    //先检查是否还有库存
                    if ($product['total'] <= 0 || $product['total'] - $order['total'] < 0) {
                        $this->json_output(ERRNO::PRODUCT_TOTAL_NOT_ENOUGH);
                    }
                    //TODO: 处理支付并发
                }

                $credit_log = array(
                    $_W['member']['uid'],
                    '兑换'.$product['title'],
                    'superman_creditmall',
                );

                //扣减积分
                if ($order['pay_credit'] == 0) {
                    $fee = floatval($order['credit']);
                    $result = mc_credit_update($_W['member']['uid'], $order['credit_type'], -$fee, $credit_log);
                    if (is_error($result)) {
                        $this->json_output(ERRNO::SYSTEM_ERROR, $result['message']);
                    }
                    pdo_update('superman_creditmall_order', array('pay_credit' => 1), array('id' => $order['id']));
                }

                //积分兑换
                if ($order['price'] <= 0) {
                    $method = 'payResult';
                    if (method_exists($this, $method)) {
                        $params = array(
                            'from' => 'return',
                            'result' => 'success',
                            'tid' => $order['id'],
                            'type' => $pay_type,
                            'user' => $_W['fans']['openid'],
                        );
                        exit($this->$method($params));
                    }
                }

                //积分+现金兑换
                $paylog = null;
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'module' => $this->module['name'],
                    'tid' => $order['id'],
                );
                $list = superman_core_paylog_fetchall($filter);
                if(!empty($list)) {
                    $paylog = $list[0];
                    if ($paylog['status'] == '0') {
                        pdo_delete('core_paylog', array('plid' => $paylog['plid']));
                        $paylog = null;
                    } else {
                        $this->json_output(ERRNO::ORDER_PAYED);
                    }
                }
                //系统支付日志
                $record = array();
                if (empty($paylog)) {
                    $moduleid = empty($this->module['mid']) ? '000000' : sprintf("%06d", $this->module['mid']);
                    $fee = $order['price'];
                    $record['uniacid'] = $_W['uniacid'];
                    $record['openid'] = $_W['fans']['openid'];
                    $record['module'] = $this->module['name'];
                    $record['type'] = $pay_type;
                    $record['tid'] = $order['id'];
                    $record['uniontid'] = date('YmdHis').$moduleid.random(8,1);
                    $record['fee'] = $fee;
                    $record['status'] = '0';
                    $record['is_usecard'] = 0;
                    $record['card_id'] = 0;
                    $record['card_fee'] = $fee;
                    $record['encrypt_code'] = '';
                    $record['acid'] = $_W['acid'];
                    pdo_insert('core_paylog', $record);
                    $plid = pdo_insertid();
                    if($plid > 0) {
                        $record['plid'] = $plid;
                        $paylog = $record;
                    } else {
                        $this->json_output(ERRNO::SYSTEM_ERROR);
                    }
                }
                WeUtility::logging('trace', '[pay:submit] paylog='.var_export($paylog, true));
                //扣减余额
                if ($pay_type == 'credit' && !empty($payment['credit']['switch'])) {
                    $fee = floatval($order['price']);
                    $result = mc_credit_update($_W['member']['uid'], $setting['creditbehaviors']['currency'], -$fee, $credit_log);
                    if (is_error($result)) {
                        $this->json_output(ERRNO::SYSTEM_ERROR, $result['message']);
                    }
                    pdo_update('superman_creditmall_order', array('pay_price' => 1), array('id' => $order['id']));
                    $row = superman_core_paylog_fetch($paylog['plid']);
                    if ($row && $row['status'] == '0') {
                        pdo_update('core_paylog', array('status' => 1), array('plid' => $paylog['plid']));
                        $method = 'payResult';
                        if (method_exists($this, $method)) {
                            $params = array(
                                'weid' => $_W['uniacid'],
                                'uniacid' => $_W['uniacid'],
                                'uniontid' => $record['uniontid'],
                                'user' => $_W['fans']['openid'],
                                'from' => 'return',
                                'result' => 'success',
                                'tid' => $order['id'],
                                'type' => $pay_type,
                            );
                            exit($this->$method($params));
                        }
                    }
                } else if ($pay_type == 'wechat' && !empty($payment['wechat']['switch'])) {
                    $ps = array();
                    $ps['tid'] = $paylog['plid'];
                    $ps['uniontid'] = $paylog['uniontid'];
                    $ps['user'] = $_W['fans']['openid'];
                    $ps['fee'] = $paylog['fee'];
                    $ps['title'] = mb_substr($product['title'], 0, 8, 'utf-8');
                    if(!empty($plid)) {
                        $tag = array();
                        $tag['acid'] = $_W['acid'];
                        pdo_update('core_paylog', array('openid' => $_W['fans']['openid'], 'tag' => iserializer($tag)), array('plid' => $plid));
                    }
                    load()->model('payment');
                    load()->func('communication');
                    $sl = base64_encode(json_encode($ps));
                    $auth = sha1($sl . $_W['uniacid'] . $_W['config']['setting']['authkey']);
                    $url = "../payment/wechat/pay.php?i={$_W['uniacid']}&auth={$auth}&ps={$sl}";
                    $this->json_output(ERRNO::OK, '跳转中...', array('redirect_url' => $url));
                }
            }
        }
        include $this->template('pay');
    }
}

$obj = new Creditmall_doMobilePay;
$obj->exec();
