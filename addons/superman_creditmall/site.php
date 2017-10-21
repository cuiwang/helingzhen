<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/superman_creditmall/const.php';
require IA_ROOT . '/addons/superman_creditmall/errno.class.php';
require IA_ROOT . '/addons/superman_creditmall/util.class.php';
require IA_ROOT . '/addons/superman_creditmall/common.func.php';
require IA_ROOT . '/addons/superman_creditmall/model.func.php';
require IA_ROOT . '/addons/superman_creditmall/WxpayAPI.class.php';
require IA_ROOT . '/addons/superman_creditmall/task.class.php';
require IA_ROOT . '/addons/superman_creditmall/table.class.php';
class Superman_creditmallModuleSite extends WeModuleSite
{
    protected $member = array();
    protected $debug = true;
    protected $navigation = array();
    public function __construct($allowInit = false)
    {
        if (!$allowInit) {
            return;
        }
        global $_W, $_GPC, $do;
        load()->func('tpl');
        load()->func('file');
        load()->model('mc');
        load()->model('module');
        $this->modulename = 'superman_creditmall';
        $this->__define   = MODULE_ROOT . '/module.php';
        $this->module     = module_fetch($this->modulename);
        $this->inMobile   = defined('IN_MOBILE');
        if (defined('LOCAL_DEVELOPMENT') || defined('ONLINE_DEVELOPMENT')) {
            $this->debug = true;
        }
        if ($_W['uniacid']) {
            $this->_init_navigation();
        }
        if ($this->inMobile) {
            if (isset($this->module['config']['base']['wechat']) && $this->module['config']['base']['wechat'] && $_W['container'] != 'wechat' && !$_W['uid']) {
                $this->message('请在微信中打开！', '', 'warning');
            }
            if (!empty($_W['account']['oauth']) && empty($_W['member']['uid'])) {
                $this->checkauth();
            }
            $_version = str_replace('.', '', $this->module['version']);
            if (defined('LOCAL_DEVELOPMENT') && !SupermanUtil::is_we7_encrypt(MODULE_ROOT . '/site.php')) {
                $this->superman_css       = '<link rel="stylesheet" href="' . MODULE_URL . '/min/index.php?g=css&debug=1&' . $_version . '">';
                $this->superman_global_js = '<script src="' . MODULE_URL . '/min/index.php?g=global-js&debug=1&' . $_version . '"></script>';
                $this->superman_main_js   = '<script src="' . MODULE_URL . '/min/index.php?g=main-js&debug=1&' . $_version . '"></script>';
            } else {
                if (file_exists(MODULE_ROOT . '/template/mobile/cache/css.css') && !file_exists(IA_ROOT . '/online-dev.lock')) {
                    $this->superman_css       = '<link rel="stylesheet" href="' . MODULE_URL . '/template/mobile/cache/css.css?' . $_version . '">';
                    $this->superman_global_js = '<script src="' . MODULE_URL . '/template/mobile/cache/global.js?' . $_version . '"></script>';
                    $this->superman_main_js   = '<script src="' . MODULE_URL . '/template/mobile/cache/main.js?' . $_version . '"></script>';
                } else {
                    $this->superman_css       = '<link rel="stylesheet" href="' . MODULE_URL . '/min/index.php?g=css&' . $_version . '">';
                    $this->superman_global_js = '<script src="' . MODULE_URL . '/min/index.php?g=global-js&' . $_version . '"></script>';
                    $this->superman_main_js   = '<script src="' . MODULE_URL . '/min/index.php?g=main-js&' . $_version . '"></script>';
                }
            }
            $this->_init_fans();
            if ($_W['member']['uid']) {
                $this->member = mc_fetch($_W['member']['uid']);
                if ($this->member) {
                    $this->member['avatar']     = tomedia($this->member['avatar']);
                    $this->member['big_avatar'] = rtrim($this->member['avatar'], '132');
                }
                $this->member['group'] = superman_mc_groups_fetch($this->member['groupid']);
            } else {
                $this->member = array(
                    'uid' => '0',
                    'avatar' => '',
                    'nickname' => '您的昵称',
                    'group' => array(
                        'title' => ''
                    )
                );
            }
            $this->check_debug();
            if (!isset($_GPC['do']) && isset($_GPC['eid']) && $_GPC['eid']) {
                $eid    = intval($_GPC['eid']);
                $sql    = "SELECT `do` FROM " . tablename('modules_bindings') . " WHERE eid=:eid";
                $params = array(
                    ':eid' => $eid
                );
                $do     = pdo_fetchcolumn($sql, $params);
                if (!empty($do)) {
                    $_GPC['do'] = $do;
                }
            }
            $this->_share = array();
            $share_params = $this->module['config']['share'];
            if (!empty($share_params)) {
                $this->_share = array(
                    'title' => $share_params['title'],
                    'link' => $_W['siteurl'],
                    'imgUrl' => tomedia($share_params['imgurl']),
                    'content' => $share_params['desc']
                );
            }
            unset($share_params);
        } else {
            if ($_W['isfounder'] && !$this->module['config']['_init']) {
                $this->module['config'] = superman_setting_data();
                superman_setting_init($_W['uniacid'], $this->module['config']);
            }
        }
        $this->_background_running();
    }
    private function _init_fans()
    {
        global $_W, $_GPC;
        if ($_W['member']['uid']) {
            $_W['member'] = array_merge($_W['member'], mc_fetch($_W['member']['uid'], array(
                'nickname',
                'avatar'
            )));
            $data         = array();
            if (!empty($_W['fans'])) {
                if (empty($_W['member']['nickname'])) {
                    $data['nickname'] = $_W['fans']['tag']['nickname'];
                }
                if (empty($_W['member']['avatar'])) {
                    $data['avatar'] = $_W['fans']['tag']['headimgurl'] ? $_W['fans']['tag']['headimgurl'] : $_W['fans']['tag']['avatar'];
                }
            } else {
                $fan = mc_fansinfo($_W['member']['uid']);
                if ($fan) {
                    if (empty($_W['member']['nickname'])) {
                        $data['nickname'] = $fan['tag']['nickname'];
                    }
                    if (empty($_W['member']['avatar'])) {
                        $data['avatar'] = $fan['tag']['headimgurl'] ? $fan['tag']['headimgurl'] : $fan['tag']['avatar'];
                    }
                }
            }
            if (!empty($data)) {
                pdo_update('mc_members', $data, array(
                    'uid' => $_W['member']['uid']
                ));
                $_W['member']['nickname'] = $data['nickname'];
                $_W['member']['avatar']   = $data['avatar'];
            }
        } else {
            $_W['member'] = array(
                'nickname' => '微信昵称',
                'avatar' => ''
            );
        }
    }
    public function json_output($errno, $errmsg = '', $data = array(), $redirect = false)
    {
        global $_W;
        ob_clean();
        if ($errmsg == '') {
            $errmsg = ERRNO::$ERRMSG[$errno];
        }
        $result = array(
            'errno' => $errno,
            'errmsg' => $errmsg,
            'data' => $data
        );
        if ($redirect && $result['data']['url']) {
            @header("Location: {$result['data']['url']}#wechat_redirect");
            exit;
        }
        if ($_W['isajax']) {
            @header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            $msg          = "$errmsg($errno)";
            $redirect_url = murl('entry', array(
                'do' => 'home',
                'm' => 'superman_creditmall'
            ), true, true);
            if (!empty($data['redirect_url'])) {
                $redirect_url = $data['redirect_url'];
            }
            if (!empty($data['url'])) {
                $redirect_url = $data['url'];
            }
            $this->message($msg, $redirect_url, 'info');
        }
        exit;
    }
    public function checkauth()
    {
        global $_W, $_GPC;
        $subscribe = $this->init_subscribe_variable();
        if ($subscribe['exchange'] == 0 && $_W['container'] == 'wechat') {
            $this->message($subscribe['tips'], $subscribe['url'], 'info');
        }
        if (empty($_W['member']['uid'])) {
            if ($_W['container'] == 'wechat') {
                define('ONLINE_DEVELOPMENT', true);
                if (!defined('LOCAL_DEVELOPMENT')) {
                    if (defined('ONLINE_DEVELOPMENT')) {
                        WeUtility::logging('debug', '[checkauth] _W[fans]=' . var_export($_W['fans'], true));
                    }
                    if (!empty($_W['fans']['openid'])) {
                        $fan = mc_fansinfo($_W['fans']['openid']);
                        if (defined('ONLINE_DEVELOPMENT')) {
                            WeUtility::logging('debug', '[checkauth] mc_fansinfo fan=' . var_export($fan, true));
                        }
                        if (empty($fan)) {
                            mc_oauth_userinfo();
                        }
                        if (empty($fan['uid'])) {
                            $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
                                ':uniacid' => $_W['uniacid']
                            ));
                            $salt            = random(8);
                            $data            = array(
                                'uniacid' => $_W['uniacid'],
                                'email' => md5($fan['openid']) . '@012wz.com',
                                'salt' => $salt,
                                'groupid' => $default_groupid,
                                'createtime' => TIMESTAMP,
                                'password' => md5($fan['openid'] . $salt . $_W['config']['setting']['authkey']),
                                'nickname' => stripslashes($fan['tag']['nickname']),
                                'avatar' => $fan['tag']['headimgurl'],
                                'gender' => $fan['tag']['sex'],
                                'nationality' => $fan['tag']['country'],
                                'resideprovince' => $fan['tag']['province'] . '省',
                                'residecity' => $fan['tag']['city'] . '市'
                            );
                            pdo_insert('mc_members', $data);
                            $fan['uid'] = pdo_insertid();
                            if (defined('ONLINE_DEVELOPMENT')) {
                                WeUtility::logging('debug', '[checkauth] init mc_members, uid=' . $fan['uid']);
                            }
                        }
                        if (empty($fan['fanid'])) {
                            $data = array(
                                'openid' => $fan['openid'],
                                'uid' => $fan['uid'],
                                'acid' => $_W['acid'],
                                'uniacid' => $_W['uniacid'],
                                'salt' => random(8),
                                'updatetime' => TIMESTAMP,
                                'nickname' => stripslashes($fan['tag']['nickname']),
                                'follow' => 0,
                                'followtime' => 0,
                                'unfollowtime' => 0,
                                'tag' => base64_encode(iserializer($fan['tag']))
                            );
                            pdo_insert('mc_mapping_fans', $data);
                            $fan['fanid'] = pdo_insertid();
                            if (defined('ONLINE_DEVELOPMENT')) {
                                WeUtility::logging('debug', '[checkauth] init mc_mapping_fans, fanid=' . $fan['fanid']);
                            }
                        }
                        if (!empty($fan['uid']) && _mc_login(array(
                            'uid' => $fan['uid']
                        ))) {
                            if (defined('ONLINE_DEVELOPMENT')) {
                                WeUtility::logging('debug', '[checkauth] _mc_login success');
                            }
                            return true;
                        }
                    } else {
                        if (defined('ONLINE_DEVELOPMENT')) {
                            WeUtility::logging('debug', '[checkauth] mc_oauth_userinfo start');
                        }
                        mc_oauth_userinfo();
                    }
                }
            }
            $this->message('未登录，跳转中...', url("auth/login", array(
                "forward" => base64_encode($_SERVER["QUERY_STRING"])
            )), 'info');
        }
        return true;
    }
    public function message($msg, $redirect = '', $type = '')
    {
        global $_W, $_GPC;
        if ($redirect == 'refresh') {
            $redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];
        }
        if ($redirect == 'referer') {
            $redirect = referer();
        }
        $type = in_array($type, array(
            'success',
            'warn',
            'info',
            'waiting',
            'safe_success',
            'safe_warn'
        )) ? $type : 'info';
        if (empty($msg) && !empty($redirect) && $redirect != 'close') {
            @header('Location: ' . $redirect);
        }
        include $this->template('message', TEMPLATE_INCLUDEPATH);
        exit();
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        if ($this->debug) {
            WeUtility::logging('trace', '[payResult] params=' . var_export($params, true) . ', url=' . $_W['siteurl']);
        }
        $order_id = $params['tid'];
        $order    = superman_order_fetch($order_id);
        if (!$order) {
            $this->json_output(ERRNO::ORDER_NOT_EXIST);
        }
        $order_url   = $_W['siteroot'] . 'app/' . $this->createMobileUrl('order', array(
            'act' => 'detail',
            'orderid' => $order['id']
        ));
        $redpack_url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('redpack', array(
            'id' => $order['product_id'],
            'orderid' => $order_id
        ));
        if ($this->debug) {
            WeUtility::logging('trace', "[payResult] order_url=$order_url, redpack_url=$redpack_url");
        }
        if ($order['price'] > 0) {
            $paylog = M::t('core_paylog')->fetch(array(
                'uniacid' => $params['uniacid'],
                'openid' => $params['user'],
                'tid' => $params['tid'],
                'uniontid' => $params['uniontid']
            ));
            if ($this->debug) {
                WeUtility::logging('trace', '[payResult] paylog=' . var_export($paylog, true));
            }
            if (!$paylog || $paylog['card_fee'] != $order['price'] || $paylog['status'] != 1) {
                $this->json_output(ERRNO::INVALID_REQUEST, '', $paylog);
            }
        }
        superman_order_set($order);
        $product = superman_product_fetch($order['product_id'], array(
            'type',
            'isvirtual',
            'title',
            'extend'
        ));
        if (!$product) {
            $this->json_output(ERRNO::PRODUCT_NOT_FOUND);
        }
        $order_data = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        if (superman_is_redpack($product['type'])) {
            $order_data['status'] = 3;
        }
        if (superman_is_virtual($product)) {
            $order_data['status'] = 2;
        }
        if ($product['type'] == 8) {
            $order_data['status'] = 3;
        }
        if ($params['result'] == 'success' && ($params['from'] == 'notify' || ($params['from'] == 'return' && $params['type'] == 'credit') || ($params['from'] == 'return' && $params['type'] == 'wechat')) && $order['status'] == 0) {
            $paytype           = array(
                'credit' => '1',
                'wechat' => '2'
            );
            $order['pay_type'] = $order_data['pay_type'] = $paytype[$params['type']];
            if ($params['type'] == 'wechat') {
                $order_data['payment_no'] = $params['tag']['transaction_id'];
            }
            $order_data['pay_time'] = TIMESTAMP;
            $credit_title           = superman_credit_type($order['credit_type']);
            if (superman_is_redpack($product['type'])) {
                $this->initAccount();
                if (in_array($_W['account']['level'], array(
                    1,
                    2,
                    3
                ))) {
                    if (empty($this->module)) {
                        $this->module = module_fetch('superman_creditmall');
                    }
                    if ($this->module['config']['redpack']['orig_uniacid'] && $this->module['config']['redpack']['orig_acid'] && $this->module['config']['redpack']['oauth_acid'] && $this->module['config']['redpack']['oauth_uniacid']) {
                        $query_string         = array(
                            'i' => $this->module['config']['redpack']['oauth_uniacid'],
                            'j' => $this->module['config']['redpack']['oauth_acid'],
                            'c' => 'entry',
                            'do' => 'redpack',
                            'act' => 'transfer',
                            'm' => 'superman_creditmall',
                            'orderid' => $order_id,
                            'orig_uniacid' => $this->module['config']['redpack']['orig_uniacid'],
                            'orig_acid' => $this->module['config']['redpack']['orig_acid'],
                            'orig_openid' => $params['user']
                        );
                        $query_string['sign'] = SupermanUtil::get_sign($query_string, $_W['config']['setting']['authkey']);
                        $url                  = $_W['siteroot'] . "app/index.php?" . http_build_query($query_string);
                        if ($this->debug) {
                            WeUtility::logging('trace', 'openid=' . $params['user'] . ', url=' . $url);
                        }
                        $this->json_output(ERRNO::OK, '跳转中...', array(
                            'redirect_url' => $url
                        ));
                        exit;
                    } else {
                        WeUtility::logging('trace', '未配置发红包借权, config=' . var_export($this->module['config']['redpack'], true));
                    }
                }
                $redpack                              = array(
                    'amount' => $order['extend']['redpack_amount'],
                    'wishing' => superman_redpack_wishing(),
                    'act_name' => $credit_title . '兑换红包'
                );
                $ret                                  = $this->sendRedpack($this->module['config']['redpack']['oauth_uniacid'], $params['user'], $redpack, $order);
                $new_data                             = array();
                $new_data['extend']                   = $order['extend'];
                $new_data['extend']['redpack_result'] = $ret;
                $new_data['extend']                   = iserializer($new_data['extend']);
                pdo_update('superman_creditmall_order', $new_data, array(
                    'id' => $order_id
                ));
                if ($ret['success'] !== true) {
                    $ret = $this->returnCredit($order['uniacid'], $order, '发红包失败');
                    if ($ret !== true) {
                        WeUtility::logging('fatal', '发红包退积分失败, order=' . var_export($order, true));
                    }
                    $this->json_output(ERRNO::SYSTEM_ERROR, '发红包失败，跳转中...', array(
                        'url' => $order_url
                    ));
                }
            }
            if (superman_is_virtual($product)) {
                $res = array();
                pdo_begin();
                $filter  = array(
                    'product_id' => $order['product_id'],
                    'status' => 0
                );
                $orderby = ' ORDER BY `id` ASC ';
                $rows    = superman_virtual_fetchall($filter, $orderby, 0, $order['total']);
                if (count($rows) != $order['total']) {
                    WeUtility::logging('fatal', '虚拟物品库存不足, filter=' . var_export($filter, true));
                    pdo_rollback();
                    $ret = $this->returnCredit($order['uniacid'], $order, '库存不足');
                    if ($ret !== true) {
                        WeUtility::logging('fatal', '发虚拟物品退积分失败, order=' . var_export($order, true));
                    }
                    $this->json_output(ERRNO::SYSTEM_ERROR, '库存不足，跳转中...', array(
                        'url' => $order_url
                    ));
                }
                foreach ($rows as $v) {
                    $condition = array(
                        'id' => $v['id'],
                        'status' => 0
                    );
                    $new_data  = array(
                        'status' => 1,
                        'uid' => $order['uid'],
                        'get_time' => TIMESTAMP,
                        'extend' => iserializer(array(
                            'ordersn' => $order['ordersn']
                        ))
                    );
                    $result    = pdo_update('superman_creditmall_virtual_stuff', $new_data, $condition);
                    if ($result !== false) {
                        $res['errno'] += 0;
                        $res['key'] .= $v['key'] . "\n";
                    } else {
                        WeUtility::logging('fatal', '虚拟物品更新失败, new_data=' . var_export($new_data, true), ', condition=' . var_export($condition, true));
                        $res['errno'] += -1;
                        $res['key'] .= '更新虚拟物品表失败，data=' . var_export($new_data, true) . "\n";
                    }
                }
                if (isset($result) && $result != false) {
                    pdo_commit();
                } else {
                    pdo_rollback();
                }
                unset($_data);
                $_data                             = array();
                $_data['extend']                   = $order['extend'];
                $_data['extend']['virtual_result'] = $res;
                $_data['extend']                   = iserializer($_data['extend']);
                pdo_update('superman_creditmall_order', $_data, array(
                    'id' => $order_id
                ));
                unset($_data);
            }
            if ($product['type'] == 8) {
                $product['extend'] = $product['extend'] ? iunserializer($product['extend']) : array();
                $ret               = $this->_sendCoupon($product['extend']['coupon']['id']);
                if ($ret === false) {
                    $ret = $this->returnCredit($order['uniacid'], $order, '优惠券发送失败');
                    if ($ret !== true) {
                        WeUtility::logging('fatal', '优惠券发送失败且退积分失败, order=' . var_export($order, true));
                    }
                    $this->json_output(ERRNO::COUPON_SEND_FAILED, '系统错误，优惠券发送失败，跳转中...', array(
                        'url' => $order_url
                    ));
                }
            }
            $ret = pdo_update('superman_creditmall_order', $order_data, array(
                'id' => $order_id
            ));
            if ($ret === false) {
                WeUtility::logging('fatal', '订单状态更新失败, id=' . $order_id . ', data=' . var_export($order_data, true));
                $this->json_output(ERRNO::SYSTEM_ERROR, '订单状态更新失败，请联系管理员');
            }
            if ($order && $order['product_id']) {
                if ($this->debug) {
                    WeUtility::logging('trace', '[payResult] update total&sales product=' . var_export($product, true) . ', order=' . var_export($order, true));
                }
                superman_product_update_sales($order['product_id'], $order['total']);
            }
            if ($_W['account']['level'] == 4 && $this->module['config']['template_message']['order_pay_id'] && $this->module['config']['template_message']['order_pay_content']) {
                if ($order['price'] > 0) {
                    $order_amount = $order['credit'] . $credit_title . '+' . $order['price'] . '元';
                } else {
                    $order_amount = $order['credit'] . $credit_title;
                }
                $vars    = array(
                    '{订单编号}' => $order['ordersn'],
                    '{订单商品}' => $product['title'],
                    '{订单金额}' => $order_amount
                );
                $message = array(
                    'uniacid' => $_W['uniacid'],
                    'template_id' => $this->module['config']['template_message']['order_pay_id'],
                    'template_variable' => $this->module['config']['template_message']['order_pay_content'],
                    'vars' => $vars,
                    'receiver_uid' => $order['uid'],
                    'url' => $order_url
                );
                $this->sendTemplateMessage($message);
                if (isset($this->module['config']['template_message']['order_pay_openid']) && $this->module['config']['template_message']['order_pay_openid']) {
                    $admin_uid = mc_openid2uid($this->module['config']['template_message']['order_pay_openid']);
                    if ($admin_uid) {
                        $message['template_variable'] = "first=您好，有新的订单已支付！\nkeyword1={订单编号}\nkeyword2={订单金额}\nremark=请登录后台查看订单";
                        $message['receiver_uid']      = $admin_uid;
                        $message['url']               = '';
                        $this->sendTemplateMessage($message);
                    }
                }
            } elseif ($_W['account']['level'] == 3 || $_W['account']['level'] == 4) {
                $this->sendCustomerStatusNotice($order['uid'], $order['ordersn'], 1, $order_url);
            }
        }
        if ($params['result'] == 'success' && $params['from'] == 'return') {
            if (superman_is_redpack($product['type'])) {
                $this->json_output(ERRNO::OK, '支付成功，跳转中...', array(
                    'redirect_url' => $redpack_url
                ));
            } else {
                if ($product['type'] == 8) {
                    $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('order', array(
                        'status' => 'no_comment'
                    ));
                } else {
                    $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('order', array(
                        'status' => 'no_receive'
                    ));
                }
                if ($params['type'] == 'credit') {
                    $this->json_output(ERRNO::OK, '支付成功，跳转中...', array(
                        'url' => $url
                    ));
                } else {
                    $this->json_output(ERRNO::OK, '支付成功，跳转中...', array(
                        'url' => $url
                    ), true);
                }
            }
        }
    }
    public function returnCredit($uniacid, $order, $return_msg = '')
    {
        if ($order['credit'] > 0 && $order['pay_credit']) {
            $credit_title = superman_credit_type($order['credit_type']);
            $back_credit  = "+{$order['credit']}{$credit_title}";
            $log          = array(
                $order['uid'],
                "{$return_msg}退" . $credit_title,
                $this->modulename
            );
            $ret          = mc_credit_update($order['uid'], $order['credit_type'], $order['credit'], $log);
            if (is_error($ret)) {
                WeUtility::logging('fatal', 'mc_credit_update failed: ret=' . var_export($ret, true));
                return false;
            }
            pdo_update('superman_creditmall_order', array(
                'status' => 0,
                'pay_credit' => 0
            ), array(
                'id' => $order['id']
            ));
        }
        if ($order['price'] > 0 && $order['pay_price']) {
            $setting = uni_setting($uniacid, array(
                'payment',
                'creditbehaviors'
            ));
            $payment = array();
            if ($setting && isset($setting['payment']) && is_array($setting['payment'])) {
                $payment = $setting['payment'];
            }
            if (empty($payment['credit']['switch'])) {
                WeUtility::logging('fatal', '退余额失败，未开启余额支付开关');
                return false;
            }
            $credit_type  = $setting['creditbehaviors']['currency'];
            $credit_title = superman_credit_type($credit_type);
            $back_credit  = "+{$order['price']}" . $credit_title;
            $log          = array(
                $order['uid'],
                "{$return_msg}退余额",
                $this->modulename
            );
            $ret          = mc_credit_update($order['uid'], $credit_type, $order['price'], $log);
            if (is_error($ret)) {
                WeUtility::logging('fatal', 'mc_credit_update failed: ret=' . var_export($ret, true));
                return false;
            }
            pdo_update('superman_creditmall_order', array(
                'status' => 0,
                'pay_price' => 0
            ), array(
                'id' => $order['id']
            ));
            $condition = array(
                'uniacid' => $uniacid,
                'module' => $this->module['name'],
                'tid' => $order['id']
            );
            pdo_update('core_paylog', array(
                'status' => 0
            ), $condition);
        }
        return true;
    }
    public function sendRedpack($uniacid, $openid, $redpack = array(), $order = array())
    {
        global $_W;
        if ($openid == '' || $redpack['amount'] <= 0 || $redpack['wishing'] == '' || $redpack['act_name'] == '') {
            $msg = "红包发送失败：openid={$openid}, redpack=" . var_export($redpack, true);
            WeUtility::logging("fatal", '[sendRedpack] ' . $msg);
            return $msg;
        }
        $setting = uni_setting($uniacid, array(
            'payment'
        ));
        $pay     = $setting['payment'];
        if (!$pay) {
            $msg = '红包发送失败：未配置微信支付参数';
            WeUtility::logging("fatal", '[sendRedpack] ' . $msg);
            return $msg;
        }
        if (!isset($pay['wechat']['signkey']) || $pay['wechat']['signkey'] == '') {
            $msg = '红包发送失败：未配置微信支付参数, signkey is null';
            WeUtility::logging("fatal", '[sendRedpack] ' . $msg);
            return $msg;
        }
        if (empty($this->module['config']['wxpay']['apiclient_cert'])) {
            $msg = '红包发送失败：未配置微信支付证书, apiclient_cert is null';
            WeUtility::logging("fatal", '[sendRedpack] ' . $msg);
            return $msg;
        }
        if (empty($this->module['config']['wxpay']['apiclient_key'])) {
            $msg = '红包发送失败：未配置微信支付证书, apiclient_key is null';
            WeUtility::logging("fatal", '[sendRedpack] ' . $msg);
            return $msg;
        }
        if (empty($this->module['config']['wxpay']['rootca'])) {
            $msg = '红包发送失败：未配置微信支付证书, rootca is null';
            WeUtility::logging("fatal", '[sendRedpack] ' . $msg);
            return $msg;
        }
        if ($openid == $order['uid']) {
            $openid = superman_uid2openid($order['uid']);
        }
        $params                  = array(
            'nonce_str' => random(32),
            'mch_billno' => $order['ordersn'] . $order['id'],
            'mch_id' => $this->module['config']['wxpay']['mchid'],
            'wxappid' => $this->module['config']['wxpay']['mch_appid'],
            'send_name' => $_W['account']['name'],
            're_openid' => $openid,
            'total_amount' => $redpack['amount'],
            'total_num' => 1,
            'wishing' => $redpack['wishing'],
            'client_ip' => CLIENT_IP,
            'act_name' => $redpack['act_name'],
            'remark' => $redpack['wishing']
        );
        $extra                   = array();
        $extra['sign_key']       = $pay['wechat']['signkey'];
        $attach_path             = superman_attachment_root();
        $extra['apiclient_cert'] = $attach_path . $this->module['config']['wxpay']['apiclient_cert'];
        $extra['apiclient_key']  = $attach_path . $this->module['config']['wxpay']['apiclient_key'];
        $extra['rootca']         = $attach_path . $this->module['config']['wxpay']['rootca'];
        $ret                     = WxpayAPI::sendredpack($params, $extra, $order);
        if (!is_array($ret) || !isset($ret['success'])) {
            WeUtility::logging('fatal', '[sendRedpack] failed, params=' . var_export($params, true) . ', ret=' . var_export($ret, true));
        }
        return $ret;
    }
    public function checkRedpack($orderid)
    {
        global $_W;
        if ($orderid <= 0) {
            $msg = "红包发送失败：orderid is null";
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        $order = superman_order_fetch($orderid);
        if (!$order) {
            $msg = "红包发送失败：order is null";
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        superman_order_set($order);
        $openid       = superman_uid2openid($order['uid']);
        $credit_title = superman_credit_type($order['credit_type']);
        $redpack      = array(
            'amount' => $order['extend']['redpack_amount'],
            'wishing' => superman_redpack_wishing(),
            'act_name' => $credit_title . '兑换红包'
        );
        if ($openid == '' || $redpack['amount'] <= 0 || $redpack['wishing'] == '' || $redpack['act_name'] == '') {
            $msg = "红包发送失败：openid={$openid}, redpack=" . var_export($redpack, true);
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        $setting = uni_setting($_W['uniacid'], array(
            'payment'
        ));
        $pay     = $setting['payment'];
        if (!$pay) {
            $msg = '红包发送失败：未配置微信支付参数';
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        if (!isset($pay['wechat']['signkey']) || $pay['wechat']['signkey'] == '') {
            $msg = '红包发送失败：未配置微信支付参数, signkey is null';
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        if (empty($this->module['config']['wxpay']['apiclient_cert'])) {
            $msg = '红包发送失败：未配置微信支付证书, apiclient_cert is null';
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        if (empty($this->module['config']['wxpay']['apiclient_key'])) {
            $msg = '红包发送失败：未配置微信支付证书, apiclient_key is null';
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        if (empty($this->module['config']['wxpay']['rootca'])) {
            $msg = '红包发送失败：未配置微信支付证书, rootca is null';
            WeUtility::logging("fatal", '[checkRedpack] ' . $msg);
            return $msg;
        }
        $params                  = array(
            'nonce_str' => random(32),
            'mch_billno' => $order['ordersn'] . $order['id'],
            'mch_id' => $this->module['config']['wxpay']['mchid'],
            'wxappid' => $this->module['config']['wxpay']['mch_appid'],
            'send_name' => $_W['account']['name'],
            're_openid' => $openid,
            'total_amount' => $redpack['amount'],
            'total_num' => 1,
            'wishing' => $redpack['wishing'],
            'client_ip' => CLIENT_IP,
            'act_name' => $redpack['act_name'],
            'remark' => $redpack['wishing']
        );
        $extra                   = array();
        $extra['sign_key']       = $pay['wechat']['signkey'];
        $attach_path             = superman_attachment_root();
        $extra['apiclient_cert'] = $attach_path . $this->module['config']['wxpay']['apiclient_cert'];
        $extra['apiclient_key']  = $attach_path . $this->module['config']['wxpay']['apiclient_key'];
        $extra['rootca']         = $attach_path . $this->module['config']['wxpay']['rootca'];
        $ret                     = WxpayAPI::sendredpack($params, $extra, true);
        if (!is_array($ret) || !isset($ret['success'])) {
            WeUtility::logging('fatal', 'WxpayAPI::sendredpack failed, params=' . var_export($params, true) . ', ret=' . var_export($ret, true));
        }
        return $ret;
    }
    private function _sendCoupon($id)
    {
        global $_W;
        load()->classs('weixin.account');
        load()->classs('coupon');
        $coupon = new coupon($_W['acid']);
        if (is_error($coupon)) {
            WeUtility::logging('fatal', 'coupon=' . var_export($coupon, true));
            return false;
        }
        $card = $coupon->BuildCardExt($id);
        if (is_error($card)) {
            WeUtility::logging('fatal', '$card=' . var_export($card, true));
            return false;
        }
        $data   = array(
            'touser' => $_W['openid'],
            'msgtype' => 'wxcard',
            'wxcard' => array(
                'card_id' => $card['card_id'],
                'card_ext' => $card['card_ext']
            )
        );
        $acc    = WeAccount::create($_W['acid']);
        $status = $acc->sendCustomNotice($data);
        if (is_error($status)) {
            WeUtility::logging('fatal', 'status=' . var_export($status, true));
            return false;
        }
        return true;
    }
    public function sendTemplateMessage($message_info)
    {
        global $_W;
        load()->model('mc');
        $template_id       = $message_info['template_id'];
        $template_variable = $message_info['template_variable'];
        $fans              = mc_fansinfo($message_info['receiver_uid']);
        if ($fans) {
            if ($fans['follow']) {
                $this->initAccount();
                $account           = WeAccount::create($_W['acid']);
                $message           = array(
                    'template_id' => $template_id,
                    'postdata' => array(),
                    'url' => $message_info['url'],
                    'topcolor' => '#008000'
                );
                $template_variable = explode("\n", $template_variable);
                foreach ($template_variable as $line) {
                    $arr                                = explode("=", trim($line));
                    $message['postdata'][trim($arr[0])] = array(
                        'value' => $this->replaceTemplateMessageVariable(trim($arr[1]), $message_info['vars']),
                        'color' => '#173177'
                    );
                }
                $ret = $account->sendTplNotice($fans['openid'], $message['template_id'], $message['postdata'], $message['url'], $message['topcolor']);
                if ($ret !== true) {
                    WeUtility::logging("fatal", "模板消息发送失败：openid={$fans['openid']}, ret=" . var_export($ret, true) . ", message=" . var_export($message, true));
                } else {
                    WeUtility::logging("trace", "模板消息发送成功：template_id={$message['template_id']}, openid={$fans['openid']}, message=" . var_export($message, true));
                }
            } else {
                WeUtility::logging("warning", "模板消息发送失败：粉丝已取消关注, fans=" . var_export($fans, true));
            }
        } else {
            WeUtility::logging("warning", "模板消息发送失败：没有找到粉丝信息, uid={$message_info['receiver_uid']}");
        }
    }
    public function sendCustomerStatusNotice($uid, $ordersn, $status, $url = '', $update_time = TIMESTAMP)
    {
        global $_W;
        $account = $this->initAccount();
        if (is_error($account)) {
            WeUtility::logging('fatal', '[sendCustomerStatusNotice] failed: account=' . var_export($account, true));
            return $account;
        }
        $update_time = date('Y-m-d H:i:s', $update_time);
        $fans        = mc_fansinfo($uid);
        $openid      = $fans['openid'];
        $nickname    = $fans['nickname'] == '' ? $uid : $fans['nickname'];
        $text        = "{$nickname} 您好，";
        $title       = '';
        if ($status == 0) {
            $title = '订单创建通知';
            $text .= "订单已创建成功，请尽快支付！\n";
            $text .= "订单号：$ordersn\n";
            $text .= "创建时间：$update_time";
        } elseif ($status == 1) {
            $title = '订单支付通知';
            $text .= "订单已支付成功！\n";
            $text .= "订单号：$ordersn\n";
            $text .= "支付时间：$update_time";
        } else {
            $title = '订单发货通知';
            $text .= "订单已发货！\n";
            $text .= "订单号：$ordersn\n";
            $order = M::t('superman_creditmall_order')->fetch(array(
                'ordersn' => $ordersn
            ));
            if (!empty($order['username']) && !empty($order['express_title'])) {
                $text .= "快递公司：{$order['express_title']}\n";
                $text .= "快递单号：{$order['express_no']}\n";
                $text .= "收货信息：{$order['username']} {$order['mobile']} {$order['address']}\n";
            }
            $text .= "发货时间：$update_time";
        }
        $message = array(
            'msgtype' => 'news',
            'news' => array(
                'articles' => array(
                    array(
                        'title' => urlencode($title),
                        'description' => urlencode($text),
                        'url' => urlencode($url),
                        'picurl' => ''
                    )
                )
            ),
            'touser' => $openid
        );
        $result  = $account->sendCustomNotice($message);
        if (is_error($result)) {
            WeUtility::logging('fatal', '[sendCustomerStatusNotice] failed: result=' . var_export($result, true));
        }
        if (file_exists(IA_ROOT . '/online-dev.lock')) {
            WeUtility::logging("trace", "[sendCustomerStatusNotice] 客服消息发送成功：openid={$openid}, message=" . var_export($message, true));
        }
        return $result;
    }
    public function send_auction_svcmsg($vars, $openid, $url)
    {
        global $_W;
        $account = $this->initAccount();
        if (is_error($account)) {
            WeUtility::logging('fatal', '[send_auction_svcmsg] failed: account=' . var_export($account, true));
            return $account;
        }
        if (!in_array($_W['account']['level'], array(
            3,
            4
        ))) {
            WeUtility::logging('fatal', '[send_auction_svcmsg] 非认证公众号没有客服消息权限, name=' . $_W['account']['name'] . ', level=' . $_W['account']['level']);
            return false;
        }
        if (!$openid) {
            WeUtility::logging('fatal', '[send_auction_svcmsg] 非法参数，openid is null');
            return false;
        }
        $content = "恭喜，您已竞拍成功！\n拍卖商品：{$vars['{商品标题}']}\n拍卖价：{$vars['{商品价格}']}\n请尽快领取，点击查看详情";
        $message = array(
            'msgtype' => 'news',
            'news' => array(
                'articles' => array(
                    array(
                        'title' => urlencode($vars['{title}']),
                        'description' => urlencode($content),
                        'url' => urlencode($url),
                        'picurl' => ''
                    )
                )
            ),
            'touser' => $openid
        );
        $ret     = $account->sendCustomNotice($message);
        if (is_error($ret)) {
            WeUtility::logging("fatal", "[send_auction_svcmsg] 客服消息发送失败：openid={$openid}, ret=" . var_export($ret, true) . ", message=" . var_export($message, true));
            return false;
        }
        if (file_exists(IA_ROOT . '/online-dev.lock')) {
            WeUtility::logging("trace", "[send_auction_svcmsg] 客服消息发送成功：openid={$openid}, message=" . var_export($message, true) . ', title=' . $vars['{title}'] . ', content=' . $content . ', url=' . $url);
        }
        return true;
    }
    public function initAccount()
    {
        global $_W;
        static $account = null;
        if (!isset($this->superman['sendredpack']['orig_uniacid'])) {
            if (!is_null($account)) {
                return $account;
            }
            if (empty($_W['account'])) {
                if (isset($_W['acid']) && $_W['acid']) {
                    $_W['account'] = account_fetch($_W['acid']);
                } else {
                    $_W['account'] = uni_fetch($_W['uniacid']);
                }
            }
            if (empty($_W['account'])) {
                return error(-1, '获取公众号数据失败');
            }
            $account = WeAccount::create();
            if (is_null($account)) {
                return error(-1, '创建公众号操作对象失败');
            }
            return $account;
        } else {
            if (empty($_W['account'])) {
                if (isset($this->superman['sendredpack']['orig_acid']) && $this->superman['sendredpack']['orig_acid']) {
                    $_W['account'] = account_fetch($this->superman['sendredpack']['orig_acid']);
                } else {
                    $_W['account'] = uni_fetch($this->superman['sendredpack']['orig_uniacid']);
                }
            }
            if (empty($_W['account'])) {
                return error(-1, '获取公众号数据失败');
            }
            $account = WeAccount::create();
            if (is_null($account)) {
                return error(-1, '创建公众号操作对象失败');
            }
            return $account;
        }
    }
    public function check_member_access($product = array())
    {
        global $_W;
        $access_setting = M::t('superman_creditmall_kv')->fetch_value(SUPERMAN_SKEY_ACCESS_SETTING);
        if ($access_setting) {
            if ($access_setting['uids'] != '') {
                $uids = explode("\r\n", $access_setting['uids']);
                if (in_array($_W['member']['uid'], $uids)) {
                    $this->json_output(ERRNO::ACCESS_UID_LIMIT);
                }
            }
            if ($access_setting['ips'] != '') {
                $ips = explode("\r\n", $access_setting['ips']);
                if (is_array($ips)) {
                    foreach ($ips as $k => $ip) {
                        if (substr($ip, 0, 1) == '#') {
                            unset($ips[$k]);
                        }
                    }
                    $ips_str = implode("\r\n", $ips);
                    if (SupermanUtil::ip_access(CLIENT_IP, $ips_str) != 0) {
                        $this->json_output(ERRNO::ACCESS_IP_LIMIT);
                    }
                }
            }
        }
        $member   = mc_fetch($_W['member']['uid'], array(
            'resideprovince',
            'residecity',
            'residedist'
        ));
        $location = SupermanUtil::ip2location(CLIENT_IP);
        $errno    = 0;
        if (!empty($product['province'])) {
            $product['province'] = str_replace('省', '', $product['province']);
            if (!empty($location) && isset($location['province']) && $location['province'] != '') {
                $location['province'] = str_replace('省', '', $location['province']);
                if (!strexists($product['province'], $location['province'])) {
                    $errno = ERRNO::ACCESS_PROVINCE_LIMIT;
                    if ($this->debug) {
                        WeUtility::logging('trace', "[check_member_access] province limit, province={$product['province']}, location=" . var_export($location, true));
                    }
                }
            } else if (!empty($member) && isset($member['resideprovince']) && $member['resideprovince'] != '') {
                $member['resideprovince'] = str_replace('省', '', $member['resideprovince']);
                if (!strexists($product['province'], $member['resideprovince'])) {
                    $errno = ERRNO::ACCESS_PROVINCE_LIMIT;
                    if ($this->debug) {
                        WeUtility::logging('trace', "[check_member_access] province limit, province={$product['province']}, member=" . var_export($member, true));
                    }
                }
            } else {
                $errno = ERRNO::ACCESS_PROVINCE_LIMIT;
                if ($this->debug) {
                    WeUtility::logging('trace', "[check_member_access] province limit, province={$product['province']}");
                }
            }
        }
        if ($errno != 0) {
            $this->json_output($errno);
        }
        if (!empty($product['city'])) {
            $product['city'] = str_replace('市', '', $product['city']);
            if (!empty($location) && isset($location['city']) && $location['city'] != '') {
                $location['city'] = str_replace('市', '', $location['city']);
                if (!strexists($product['city'], $location['city'])) {
                    $errno = ERRNO::ACCESS_CITY_LIMIT;
                    if ($this->debug) {
                        WeUtility::logging('trace', "[check_member_access] city limit, city={$product['city']}, location=" . var_export($location, true));
                    }
                }
            } else if (!empty($member) && isset($member['residecity']) && $member['residecity'] != '') {
                $member['residecity'] = str_replace('市', '', $member['residecity']);
                if (!strexists($product['city'], $member['residecity'])) {
                    $errno = ERRNO::ACCESS_CITY_LIMIT;
                    if ($this->debug) {
                        WeUtility::logging('trace', "[check_member_access] city limit, city={$product['city']}, member=" . var_export($member, true));
                    }
                }
            } else {
                $errno = ERRNO::ACCESS_CITY_LIMIT;
                if ($this->debug) {
                    WeUtility::logging('trace', "[check_member_access] city limit");
                }
            }
        }
        if ($errno != 0) {
            $this->json_output($errno);
        }
        if (!empty($product['district'])) {
            $product['district'] = str_replace('市', '', $product['district']);
            if (!empty($location) && isset($location['district']) && $location['district'] != '') {
                $location['district'] = str_replace('市', '', $location['district']);
                if (!strexists($product['district'], $location['district'])) {
                    $errno = ERRNO::ACCESS_DISTRICT_LIMIT;
                    if ($this->debug) {
                        WeUtility::logging('trace', "[check_member_access] district limit, district={$product['district']}, location=" . var_export($location, true));
                    }
                }
            } else if (!empty($member) && isset($member['residedist']) && $member['residedist'] != '') {
                $member['residedist'] = str_replace('市', '', $member['residedist']);
                if (!strexists($product['district'], $member['residedist'])) {
                    $errno = ERRNO::ACCESS_DISTRICT_LIMIT;
                    if ($this->debug) {
                        WeUtility::logging('trace', "[check_member_access] district limit, district={$product['district']}, member=" . var_export($member, true));
                    }
                }
            } else {
                $errno = ERRNO::ACCESS_DISTRICT_LIMIT;
                if ($this->debug) {
                    WeUtility::logging('trace', "[check_member_access] district limit, district={$product['district']}");
                }
            }
        }
        if ($errno != 0) {
            $this->json_output($errno);
        }
        return true;
    }
    public function init_subscribe_variable()
    {
        global $_W;
        $subscribe = array(
            'exchange' => 1,
            'url' => $_W['account']['subscribeurl']
        );
        if (isset($this->module['config']['subscribe']['tips'])) {
            $subscribe['tips'] = $this->module['config']['subscribe']['tips'];
        }
        if (isset($this->module['config']['subscribe']['check']) && $this->module['config']['subscribe']['check'] && (!isset($_W['fans']['follow']) || !$_W['fans']['follow'])) {
            $subscribe['exchange'] = 0;
        }
        return $subscribe;
    }
    public function build_site_url($url, $uniacid = 0, $acid = 0, $from = '', $to = '')
    {
        global $_W;
        if (empty($uniacid)) {
            $uniacid = $_W['uniacid'];
        }
        if (empty($acid)) {
            $acid = $_W['acid'];
        }
        $mapping = array(
            '[from]' => $from,
            '[to]' => $to,
            '[rule]' => '',
            '[uniacid]' => $uniacid
        );
        $url     = str_replace(array_keys($mapping), array_values($mapping), $url);
        if (strexists($url, 'http://') || strexists($url, 'https://')) {
            return $url;
        }
        if (uni_is_multi_acid($uniacid) && strexists($url, './index.php?i=') && !strexists($url, '&j=') && !empty($acid)) {
            $url = str_replace("?i={$uniacid}&", "?i={$uniacid}&j={$acid}&", $url);
        }
        $pass            = array();
        $pass['openid']  = $to;
        $pass['acid']    = $acid;
        $sql             = 'SELECT `fanid`,`salt`,`uid` FROM ' . tablename('mc_mapping_fans') . ' WHERE `acid`=:acid AND `openid`=:openid';
        $pars            = array();
        $pars[':acid']   = $acid;
        $pars[':openid'] = $pass['openid'];
        $fan             = pdo_fetch($sql, $pars);
        if (empty($fan) || !is_array($fan) || empty($fan['salt'])) {
            $fan = array(
                'salt' => ''
            );
        }
        $pass['time']    = TIMESTAMP;
        $pass['hash']    = md5("{$pass['openid']}{$pass['time']}{$fan['salt']}{$_W['config']['setting']['authkey']}");
        $auth            = base64_encode(json_encode($pass));
        $vars            = array();
        $vars['uniacid'] = $uniacid;
        $vars['__auth']  = $auth;
        $vars['forward'] = base64_encode($url);
        return $_W['siteroot'] . 'app/' . str_replace('./', '', $this->_murl('auth/forward', $vars, false, false, $uniacid, $acid));
    }
    private function _murl($segment, $params = array(), $noredirect = true, $addhost = false, $uniacid = 0, $acid = 0)
    {
        global $_W;
        list($controller, $action, $do) = explode('/', $segment);
        if (!empty($addhost)) {
            $url = $_W['siteroot'] . 'app/';
        } else {
            $url = './';
        }
        $str = '';
        if (uni_is_multi_acid()) {
            $str = "&j={$acid}";
        }
        $url .= "index.php?i={$uniacid}{$str}&";
        if (!empty($controller)) {
            $url .= "c={$controller}&";
        }
        if (!empty($action)) {
            $url .= "a={$action}&";
        }
        if (!empty($do)) {
            $url .= "do={$do}&";
        }
        if (!empty($params)) {
            $queryString = http_build_query($params, '', '&');
            $url .= $queryString;
            if ($noredirect === false) {
                $url .= '&wxref=mp.weixin.qq.com#wechat_redirect';
            }
        }
        return $url;
    }
    private function check_debug()
    {
        global $_W;
        $config = $this->module['config'];
        if (isset($config['base']['debug']) && $config['base']['debug']) {
            if (!$_W['member']['uid'] && !$_W['uid']) {
                checkauth();
            }
            if ($_W['member']['uid'] && $config['base']['debug_uids'] && !in_array($_W['member']['uid'], $config['base']['debug_uids'])) {
                $message = $config['base']['debug_message'] != '' ? $config['base']['debug_message'] : '系统升级中...';
                $this->message($message, '', 'info');
            }
        }
    }
    private function replaceTemplateMessageVariable($str, $vars)
    {
        if (!$vars) {
            return $str;
        }
        foreach ($vars as $k => $v) {
            if (strpos($str, $k) !== false) {
                $str = str_replace($k, $v, $str);
            }
        }
        return $str;
    }
    private function _init_navigation()
    {
        global $_W;
        $filter  = array(
            'uniacid' => $_W['uniacid']
        );
        $orderby = " ORDER BY displayorder DESC";
        $navlist = M::t('superman_creditmall_navigation')->fetchall($filter, $orderby, 0, -1);
        if (!$navlist) {
            $navlist = superman_navigation_data();
            foreach ($navlist as $v) {
                $v['uniacid'] = $_W['uniacid'];
                M::t('superman_creditmall_navigation')->insert($v);
            }
            unset($v);
        }
        foreach ($navlist as &$v) {
            if ($v['title'] == '' || $v['url'] == '' || $v['isshow'] == 0) {
                continue;
            }
            $v['active'] = false;
            $url         = str_replace('./', '/', $v['url']);
            $url         = str_replace('//', '/', $url);
            if (strexists($_W['siteurl'], $url)) {
                $v['active'] = true;
            }
            $this->navigation[] = $v;
        }
    }
    private function _background_running()
    {
        $this->_check_order();
    }
    private function _check_order()
    {
        global $_W;
        $path = MODULE_ROOT . '/data/' . $_W['uniacid'];
        mkdirs($path);
        if ($this->_check_running_interval_time($path . '/order.txt', 600)) {
            if (isset($this->module['config']['order']['auto_close']) && $this->module['config']['order']['auto_close'] > 0) {
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'status' => 0,
                    'dateline' => '# <' . (TIMESTAMP - intval($this->module['config']['order']['auto_close'] * 60 * 60))
                );
                $list   = M::t('superman_creditmall_order')->fetchall($filter, '', 0, 100);
                if ($list) {
                    foreach ($list as $li) {
                        $product = M::t('superman_creditmall_product')->fetch($li['product_id']);
                        if ($product['minus_total'] == 2) {
                            M::t('superman_creditmall_product')->increment(array(
                                'total' => $li['total']
                            ), array(
                                'id' => $product['id']
                            ));
                        }
                        M::t('superman_creditmall_order')->update(array(
                            'status' => -1,
                            'updatetime' => TIMESTAMP
                        ), array(
                            'id' => $li['id']
                        ));
                    }
                }
            }
            if (isset($this->module['config']['order']['auto_receive']) && $this->module['config']['order']['auto_receive'] > 0) {
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'status' => 2,
                    'updatetime' => '# <' . (TIMESTAMP - intval($this->module['config']['order']['auto_receive'] * 24 * 60 * 60))
                );
                M::t('superman_creditmall_order')->update(array(
                    'status' => 3,
                    'updatetime' => TIMESTAMP
                ), $filter);
            }
        }
    }
    private function _check_running_interval_time($filename, $interval = 300)
    {
        $name = substr($filename, strrpos($filename, '/') + 1);
        if (empty($filename)) {
            WeUtility::logging('fatal', "[_check_running_interval_time:$name] filename is null");
            return false;
        }
        if (!file_exists($filename)) {
            $interval = 0;
        }
        $fp = fopen($filename, "a");
        if (!$fp) {
            WeUtility::logging('fatal', "[_check_running_interval_time:$name] fopen failed, filename=$filename");
            return false;
        }
        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            fclose($fp);
            return false;
        }
        if ($interval > 0) {
            $lasttime = filemtime($filename);
            $diff     = TIMESTAMP - $lasttime;
            if ($diff < $interval) {
                if (defined('LOCAL_DEVELOPMENT')) {
                    WeUtility::logging('debug', "[_check_running_interval_time:$name] interval time diff=$diff, interval=$interval");
                }
                flock($fp, LOCK_UN);
                fclose($fp);
                return false;
            }
        }
        $ret = fwrite($fp, (string) TIMESTAMP);
        if ($ret <= 0) {
            WeUtility::logging('fatal', "[_check_running_interval_time:$name] file_put_contents failed(2), ret=$ret");
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }
        if (defined('LOCAL_DEVELOPMENT')) {
            WeUtility::logging('debug', "[_check_running_interval_time:$name] ok");
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
}

?>