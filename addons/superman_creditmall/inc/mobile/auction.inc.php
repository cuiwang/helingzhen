<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileAuction extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'bid'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '竞拍出价';
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $back_url = $this->createMobileUrl('detail', array('id' => $id));
            $product = superman_product_fetch($id);
            if (!$product) {
                $this->json_output(ERRNO::PRODUCT_NOT_FOUND);
            }

            superman_product_set($product);
            $finished = false;
            if ($product['end_time'] > 0 && $product['end_time'] < TIMESTAMP) {
                //时间已结束
                $finished = true;
            }
            //检查&更新拍卖状态
            $this->check_auction($product);
            $bid_url = $this->createMobileUrl('auction', array(
                'act' => 'bid',
                'id' => $id,
            ));
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
                'product_id' => $id,
            );
            if (isset($_GPC['last_id'])) {
                $filter['last_id'] = intval($_GPC['last_id']);
            }
            $list = superman_product_log_fetchall($filter, 'ORDER BY credit DESC, dateline DESC, millisecond DESC', $start, $pagesize);
            if ($list) {
                $members = array();
                foreach ($list as $k=>&$item) {
                    $item['first'] = false;
                    if ($start == 0 && $k == 0) {
                        $item['first'] = true;
                        if ($item['status'] == 1) {
                            $finished = true; //已拍中结束
                        }
                    }
                    if (!isset($members[$item['uid']])) {
                        $members[$item['uid']] = mc_fetch($item['uid'], array('avatar', 'nickname'));
                    }
                    $item['member'] = $members[$item['uid']];
                    superman_product_log_set($item);
                }
                unset($item);
            }
            //加载更多
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                //die(json_encode($list));
                if ($finished) {
                    $this->json_output(ERRNO::AUCTION_HAS_ENDED, '', $list);
                } else {
                    $this->json_output(ERRNO::OK, '', $list);
                }
            }
            $subscribe = $this->init_subscribe_variable();
            //print_r($list);
            include $this->template('auction');
        } else if ($act == 'bid') {
            if (!$_W['member']['uid']) {
                $this->json_output(ERRNO::NOT_LOGIN);
            }
            $id = intval($_GPC['id']);
            if (empty($id)) {
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
            $credit = floatval($_GPC['credit']);
            if ($credit <= 0) {
                $this->json_output(ERRNO::PARAM_ERROR);
            }
            superman_product_set($product);
            //未开始
            if ($product['start_time'] > 0 && $product['start_time'] > TIMESTAMP) {
                $this->json_output(ERRNO::AUCTION_NOT_START);
            }
            //检查&更新拍卖状态
            $this->check_auction($product);
            //已结束
            if ($product['end_time'] > 0 && $product['end_time'] < TIMESTAMP) {
                $this->json_output(ERRNO::AUCTION_HAS_ENDED);
            }
            //检查库存
            if ($product['total'] == 0) {
                $this->json_output(ERRNO::PRODUCT_NOT_TOTAL);
            }
            //检查会员组
            if ($product['groupid'] && $product['groupid'] != $_W['member']['groupid']) {
                $this->json_output(ERRNO::PRODUCT_GROUP_LIMIT);
            }
            superman_product_set($product);
            //检查积分
            $mycredit = superman_mycredit($_W['member']['uid'], $product['credit_type'], true);
            if (!$mycredit) {
                $this->json_output(ERRNO::SYSTEM_ERROR);
            }
            if ($mycredit['value'] < $credit) {
                $this->json_output(ERRNO::CREDIT_NOT_ENOUGH, $product['credit_title'].'不足');
            }
            $sql = "SELECT * FROM ".tablename('superman_creditmall_product_log')." WHERE product_id=:product_id ORDER BY id DESC";
            $params = array(
                ':product_id' => $id,
            );
            $log = pdo_fetch($sql, $params);
            if ($log) {
                if ($credit <= $log['credit']) {
                    $this->json_output(ERRNO::AUCTION_CREDIT_INVALID, '出价不合法，未大于最高出价');
                }
                //检查竞拍加价幅度
                if (isset($product['extend']['auction_credit']) && $product['extend']['auction_credit'] > 0) {
                    if ($credit - $log['credit'] < $product['extend']['auction_credit']) {
                        $min = $product['extend']['auction_credit'] + $log['credit'];
                        $this->json_output(ERRNO::AUCTION_CREDIT_INVALID, '出价不合法，最少出价'.$min.$product['credit_title']);
                    }
                }
                //检查出价频率
                if ($_W['member']['uid'] == $log['uid'] && TIMESTAMP - $log['dateline'] <= 2) {
                    $this->json_output(ERRNO::INVALID_REQUEST, '非法出价');
                }
            } else {
                //第一个出价，检查起拍价
                if ($product['credit'] > 0 && $credit < $product['credit']) {
                    $this->json_output(ERRNO::AUCTION_CREDIT_INVALID, '出价不合法，起拍价为'.$product['credit'].$product['credit_title']);
                }
            }
            //检查用户每天购买数量
            if ($product['today_limit'] > 0) {
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'product_id' => $product['id'],
                    'status' => 1,
                    'start_time' => strtotime(date('0:0:0'))
                );
                $today_total = superman_product_log_count($filter);
                if ($today_total >= $product['today_limit']) {
                    $this->json_output(ERRNO::PRODUCT_EXCHANGE_LIMIT);
                }
            }
            //检查用户最多购买数
            if ($product['max_buy_num'] > 0) {
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'product_id' => $product['id'],
                    'status' => 1,
                );
                $buy_total = superman_product_log_count($filter);
                if ($buy_total >= $product['max_buy_num']) {
                    $this->json_output(ERRNO::PRODUCT_EXCHANGE_LIMIT);
                }
            }
            $t = microtime(true);
            $millisecond = sprintf("%06d", ($t - floor($t)) * 1000000);
            $data = array(
                'uniacid' => $_W['uniacid'],
                'uid' => $_W['member']['uid'],
                'product_id' => $id,
                'credit_type' => $product['credit_type'],
                'credit' => $credit,
                'status' => 0,
                'dateline' => TIMESTAMP,
                'millisecond' => $millisecond,
            );
            pdo_insert('superman_creditmall_product_log', $data);
            $new_id = pdo_insertid();
            if (!$new_id) {
                WeUtility::logging('fatal', 'insert `superman_creditmall_product_log` failed, data='.var_export($data, true));
                $this->json_output(ERRNO::SYSTEM_ERROR);
            }
            superman_product_update_count($product['id'], 'joined_total');
            $this->json_output(ERRNO::OK, '出价成功');
        }
    }

    private function check_auction($product) {
        global $_W;
        if ($product['end_time'] > 0 && $product['end_time'] > TIMESTAMP) {
            return; //未结束
        }
        $filter = array(
            'product_id' => $product['id'],
            'status' => 1,
        );
        $log = M::t('superman_creditmall_product_log')->fetch($filter);
        if ($log) {
            return; //已结束
        }
        $over = true;
        $start = 0;
        $params = array(
            ':product_id' => $product['id'],
        );
        do {
            $sql = "SELECT * FROM ".tablename('superman_creditmall_product_log');
            $sql .= " WHERE product_id=:product_id ORDER BY credit DESC, dateline DESC, millisecond DESC LIMIT {$start}, 1";
            $log = pdo_fetch($sql, $params);
            if (!$log) {
                break;
            }
            superman_product_log_set($log);
            if ($log['status'] == 1) {
                break; //已拍中
            }
            $mycredit = superman_mycredit($log['uid'], $log['credit_type'], true);
            if (!$mycredit) {
                break;
            }
            if ($mycredit['value'] < $log['credit']) {  //积分不足
                WeUtility::logging('trace', '会员积分不足查找下一个出价人,mycredit='.var_export($mycredit, true).', log='.var_export($log, true));
                $start += 1;
                $over = false;
                continue; //积分不足，查找下一个出价者
            }
            //扣积分
            $credit_log = array(
                $log['uid'],
                '竞拍'.$product['title'],
                'superman_creditmall',
            );
            $result = mc_credit_update($log['uid'], $product['credit_type'], -$log['credit'], $credit_log);
            if (is_error($result)) {
                WeUtility::logging('fatal', 'mc_credit_update failed, message='.var_export($result, true).', log='.var_export($log, true));
                break;
            }
            $ret = pdo_update('superman_creditmall_product_log', array(
                'status' => 1,
            ), array(
                'id' => $log['id'],
            ));
            if ($ret !== false) {
                //加载自取信息
                $pickup_info = '';
                //$dispatch = superman_dispatch_fetch($product['dispatch_id']);
                $dispatch = superman_dispatch_fetchall(array('id' => $product['dispatch_id']), 0, -1);
                if ($dispatch) {
                    foreach ($dispatch as $d) {
                        if (!$d['need_address'] && $d['extend']['pickup_info'] != '') {
                            $pickup_info = isset($d['extend']['pickup_info'])?$d['extend']['pickup_info']:'';
                            break;
                        }
                    }
                }
                //生成订单
                $order_data = array(
                    'uniacid' => $_W['uniacid'],
                    'ordersn' => date('ymd') . random(6, 1),
                    'uid' => $log['uid'],
                    'product_id' => $product['id'],
                    'total' => 1,
                    'price' => 0,
                    'credit_type' => $product['credit_type'],
                    'credit' => $log['credit'],
                    'remark' => '',
                    'username' => '',
                    'mobile' => '',
                    'zipcode' => '',
                    'address' => '',
                    'express_title' => '',
                    'express_fee' => '',
                    'pickup_info' => $pickup_info,
                    'status' => 1, //已支付
                    'pay_type' => 1, //余额支付
                    'pay_credit' => 1,
                    'dateline' => TIMESTAMP,
                );
                pdo_insert('superman_creditmall_order', $order_data);
                $order_id = pdo_insertid();
                if (!$order_id) {
                    WeUtility::logging('fatal', 'insert superman_creditmall_order failed, data='.var_export($order_data, true));
                }
                superman_product_update_sales($product['id'], 1);

                //竞拍成功，发送消息
                $url = $_W['siteroot'].'app/'.$this->createMobileUrl('order', array(
                    'act' => 'detail',
                    'orderid' => $order_id,
                ));
                $vars = array(
                    '{商品标题}' => $product['title'],
                    '{商品价格}' => $log['credit'].$log['credit_title'],
                );
                if ($_W['account']['level'] == 4 && isset($this->module['config']['template_message']['auction_success_id']) && $this->module['config']['template_message']['auction_success_id']
                    && isset($this->module['config']['template_message']['auction_success_content']) && $this->module['config']['template_message']['auction_success_content']) {
                    //发送模板消息
                    $message = array(
                        'uniacid' => $_W['uniacid'],
                        'template_id' => $this->module['config']['template_message']['auction_success_id'],
                        'template_variable' => $this->module['config']['template_message']['auction_success_content'],
                        'vars' => $vars,
                        'receiver_uid' => $log['uid'],
                        'url' => $url,
                    );
                    $this->sendTemplateMessage($message);
                } elseif ($_W['account']['level'] == 3 || $_W['account']['level'] == 4) {   //已认证订阅号 || 已认证服务号
                    //发客服消息
                    $vars['{title}'] = '竞拍成功通知';
                    $this->send_auction_svcmsg($vars, superman_uid2openid($log['uid']), $url);
                }
            } else {
                WeUtility::logging('fatal', 'update superman_creditmall_product_log failed, log='.var_export($log, true));
            }
            $over = true;
        } while(!$over);
    }
}

$obj = new Creditmall_doMobileAuction;
$obj->exec();
