<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');

//init setting
function superman_setting_data() {
    return array(
        '_init' => 1,
        'base' => array(
            'wechat' => 1,
            'debug' => 0,
            'debug_message' => '系统升级中...',
            'debug_uids' => array(1),
            'creditrank' => array('credit1'),
        ),
        'wxpay' => array(
            'mch_appid' => '',
            'mchid' => '',
        ),
        'redpack' => array(
            'help' => '',
        ),
        'help' => array(
            'base' => '占位',
            'use' => '占位',
            'get' => '占位',
        ),
        'share' => array(
            'title' => '你的积分价值超乎你的想象！',
            'imgurl' => 'addons/superman_creditmall/template/mobile/images/share_img.jpg',
            'desc' => '赶快抱走那堆发光的奖品吧！',
        ),
        'subscribe' => array(
            'check' => 0,
            'tips' => '',
        ),
    );
}

function superman_setting_init($uniacid, $settings) {
    global $_W;
    load()->model('cache');
    if (function_exists('cache_build_account_modules')) {
        cache_build_account_modules();
    }
    $sql = "SELECT module FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid";
    $params = array(
        ':module' => 'superman_creditmall',
        ':uniacid' => $_W['uniacid'],
    );
    if (pdo_fetchcolumn($sql, $params)) {
        $row = array();
        $row['settings'] = iserializer($settings);
        $pars = array('module' => 'superman_creditmall', 'uniacid' => $uniacid);
        return pdo_update('uni_account_modules', $row, $pars) !== false;
    } else {
        pdo_insert('uni_account_modules', array(
            'settings' => iserializer($settings),
            'module' => 'superman_creditmall',
            'uniacid' => $_W['uniacid'],
            'enabled' => 1
        ));
        return pdo_insertid()?true:false;
    }
}

function superman_redpack_wishing() {
    return '恭喜发财';
}

//table: superman_creditmall_product
function superman_product_type($typeid = 0, $sort = false) {
    $all_types = array(
        '1' => array(
            'id' => 1,
            'name' => '一口价',
            'title' => '一锤定音',
            'enable' => 1,
            'displayorder' => 50,
        ),
        '2' => array(
            'id' => 2,
            'name' => '竞拍',
            'title' => '夺宝奇兵',
            'enable' => 1,
            'displayorder' => 90,
        ),
        '3' => array(
            'id' => 3,
            'name' => '猜价',
            'title' => '我猜猜猜',
            'enable' => 0,
            'displayorder' => 70,
        ),
        '4' => array(
            'id' => 4,
            'name' => '抽奖',
            'title' => '幸运抽奖',
            'enable' => 0,
            'displayorder' => 60,
        ),
        '5' => array(
            'id' => 5,
            'name' => '微信红包',
            'title' => '抢红包',
            'enable' => 1,
            'displayorder' => 80,
        ),
        /*'6' => array(
            'id' => 6,
            'name' => '运气红包',
            'title' => '微信红包',
            'enable' => 0,
        ),*/
        '7' => array(
            'id' => 7,
            'name' => '秒杀',
            'title' => '限时秒杀',
            'enable' => 1,
            'displayorder' => 100,
        ),
        '8' => array(
            'id' => 8,
            'name' => '优惠券',
            'title' => '微信卡券',
            'enable' => 1,
            'displayorder' => 110,
        ),
    );
    if ($typeid > 0 && isset($all_types[$typeid])) {
        return $all_types[$typeid];
    }
    if ($sort) {
        usort($all_types, 'superman_product_type_orderby');
        $ret = array();
        foreach ($all_types as &$v) {
            $ret[$v['id']] = $v;
        }
        return $ret;
    }
    return $all_types;
}

function superman_product_type_orderby($a, $b)
{
    if ($a['displayorder'] == $b['displayorder']) {
        return 0;
    }
    return ($a['displayorder'] > $b['displayorder']) ? -1 : 1;
}

function superman_product_minus_total() {
    return array(
        '1' => '付款减库存',
        '2' => '拍下减库存',
        '3' => '永不减库存',
    );
}

function superman_product_set(&$product) {
    $product['cover'] = tomedia($product['cover']);
    $product['album'] = !empty($product['album'])?unserialize($product['album']):array();
    $product['credit_title'] = superman_credit_type($product['credit_type']);
    $product['price'] = superman_format_price($product['price']);
    $product['credit'] = superman_format_price($product['credit']);
    $product['market_price'] = superman_format_price($product['market_price']);
    $product['share_credit'] = superman_format_price($product['share_credit']);
    $product_type = superman_product_type($product['type']);
    $product['type_name'] = $product_type['name'];
    $product['dateline'] = date('Y-m-d H:i:s', $product['dateline']);
    $product['activity_time'] = array(
        'start' => $product['start_time']?date('Y-m-d H:i:s',$product['start_time']):'',
        'end' => $product['end_time']?date('Y-m-d H:i:s',$product['end_time']):'',
    );
    $product['allow_sale'] = 0;
    if ($product['total'] > 0 &&
        (($product['start_time']==0 && $product['end_time']==0) || ($product['start_time']>0 && $product['start_time']<=TIMESTAMP && $product['end_time']>0 && $product['end_time']>=TIMESTAMP))) {
        $product['allow_sale'] = 1;
    }
    $product['extend'] = $product['extend']?iunserializer($product['extend']):array();
    $product['share_credit_title'] = superman_credit_type($product['share_credit_type']);
    $product['isredpack'] = superman_is_redpack($product['type']);
    $product['dispatch_id'] = is_serialized($product['dispatch_id'])?iunserializer($product['dispatch_id']):array($product['dispatch_id']);
    return $product;
}

function superman_product_fetch($id, $fields = '*') {
    if (is_array($fields)) {
        $fields = implode(',', $fields);
    }
    $sql = "SELECT $fields FROM ".tablename('superman_creditmall_product')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_product_fetch_title($title) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product')." WHERE title LIKE '%{$title}%'";
    return pdo_fetch($sql);
}

function superman_product_fetchall_by_title($title) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product')." WHERE title LIKE '%{$title}%'";
    return pdo_fetchall($sql);
}

function superman_product_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['type'])) {
        $where .= ' AND type=:type';
        $params[':type'] = $filter['type'];
    }
    if (isset($filter['in_type']) && is_array($filter['in_type'])) {
        $where .= ' AND type IN('.implode(',', $filter['in_type']).')';
    }
    if (isset($filter['credit_type'])) {
        $where .= ' AND credit_type=:credit_type';
        $params[':credit_type'] = $filter['credit_type'];
    }
    if (isset($filter['credit_max'])) {
        $where .= ' AND credit<=:credit_max';
        $params[':credit_max'] = $filter['credit_max'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['ishome'])) {
        $where .= ' AND ishome=:ishome';
        $params[':ishome'] = $filter['ishome'];
    }
    if (isset($filter['ishot'])) {
        $where .= ' AND ishot=:ishot';
        $params[':ishot'] = $filter['ishot'];
    }
    if (isset($filter['title'])) {
        $where .= ' AND title LIKE "%'.$filter['title'].'%"';
    }
    if (isset($filter['share_credit'])) {
        $where .= ' AND share_credit>:share_credit';
        $params[':share_credit'] = $filter['share_credit'];
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY displayorder DESC,dateline DESC';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product')." {$where} {$orderby}";
    if ($pagesize > 0) {
        $sql .= " LIMIT {$start},{$pagesize}";
    }
    return pdo_fetchall($sql, $params);
}

function superman_product_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['type'])) {
        $where .= ' AND type=:type';
        $params[':type'] = $filter['type'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['title'])) {
        $where .= ' AND title LIKE "%'.$filter['title'].'%"';
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_product')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_product_update_sales($id, $total = 1) {
    $product = superman_product_fetch($id);
    $ret = 0;
    if ($product) {
        $data = array();
        //库存
        if ($product['minus_total'] == 1) { //付款减库存
            $new_total = $product['total'] - $total;
            $data['total'] = $new_total>0?$new_total:0;
        }

        //总销量
        $sales = $product['sales'] + $total;
        $data['sales'] = $sales;

        //周销量
        $week_start = mktime(0,  0,  0,  date('m'), date('d') - date('w') + 1, date('Y'));
        $week_end   = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7, date('Y'));
        $week_sales = superman_order_sum('total', array(
            'product_id' => $id,
            'more_status' => 0, //status > 0
            'start_time' => $week_start,
            'end_time' => $week_end,
        ));
        $data['week_sales'] = $week_sales;

        //月销量
        $month_start = mktime(0,  0,  0,  date('m'), 1, date('Y'));
        $month_end = mktime(0,  0,  0,  date('m'), date('t'), date('Y'));
        $month_sales = superman_order_sum('total', array(
            'product_id' => $id,
            'more_status' => 0, //status > 0
            'start_time' => $month_start,
            'end_time' => $month_end,
        ));
        $data['month_sales'] = $month_sales;

        $condition = array(
            'id' => $product['id'],
        );
        $ret = pdo_update('superman_creditmall_product', $data, $condition);
    }
    WeUtility::logging('trace', '[superman_product_update_sales] ret='.$ret.', id='.$id.', total='.$total.', data='.var_export($data, true).', condition='.var_export($condition, true));
}

function superman_product_update_count($id, $field, $value = 1) {
    if (!in_array($field, array('view_count', 'share_count', 'comment_count', 'joined_total'))) {
        return false;
    }
    $sql = 'UPDATE '.tablename('superman_creditmall_product')." SET {$field}={$field}+{$value} WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_query($sql, $params)>0?true:false;
}

//table: superman_creditmall_order
function superman_order_status($status = null) {
    $all_status = array(
        '-2' => '已删除',
        '-1' => '已取消',
        '0' => '待支付',
        '1' => '待发货',
        '2' => '已发货',
        '3' => '已收货',
        //'4' => '已评价',
    );
    if ($status !== null && isset($all_status[$status])) {
        return $all_status[$status];
    }
    return $all_status;
}

function superman_order_set(&$order) {
    $order['credit_title'] = superman_credit_type($order['credit_type']);
    $order['price'] = superman_format_price($order['price']);
    $order['credit'] = superman_format_price($order['credit']);
    $order['status_title'] = superman_order_status($order['status']);
    if ($order['pay_type'] == 1) {
        $order['pay_type_title'] = '余额支付';
    } else if ($order['pay_type'] == 2) {
        $order['pay_type_title'] = '微信支付';
    }
    if ($order['extend']) {
        $order['extend'] = $order['extend']?iunserializer($order['extend']):array();
    }
    if (isset($order['extend']['redpack_amount'])) {
        //红包发送成功
        if (is_array($order['extend']['redpack_result']) && isset($order['extend']['redpack_result']['success'])) {
            if (isset($order['extend']['redpack_result']['send_listid'])) {
                $order['extend']['redpack_result']['微信单号'] = $order['extend']['redpack_result']['send_listid'];
                unset($order['extend']['redpack_result']['send_listid']);
            }
            if (isset($order['extend']['redpack_result']['send_time'])) {
                $order['extend']['redpack_result']['发放成功时间'] = $order['extend']['redpack_result']['send_time'];
                unset($order['extend']['redpack_result']['send_time']);
            }
            if (isset($order['extend']['redpack_result']['mch_billno'])) {
                $order['extend']['redpack_result']['商户订单号'] = $order['extend']['redpack_result']['mch_billno'];
                unset($order['extend']['redpack_result']['mch_billno']);
            }
            $str = '';
            foreach ($order['extend']['redpack_result'] as $k=>$v) {
                $str .= "{$k}：{$v}\n";
            }
            $order['extend']['redpack_result'] = $str;
        }
    }
    $order['dateline'] = date('Y-m-d H:i:s', $order['dateline']);
    $order['pay_time'] = empty($order['pay_time'])?'':date('Y-m-d H:i:s',$order['pay_time']);
    return $order;
}

function superman_order_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_order')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_order_fetch_ordersn($ordersn) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_order')." WHERE ordersn=:ordersn";
    $params = array(
        ':ordersn' => $ordersn,
    );
    return pdo_fetch($sql, $params);
}

function superman_order_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    if (isset($filter['in_status']) && is_array($filter['in_status'])) {
        $where .= ' AND status IN('.implode(',', $filter['in_status']).')';
    }
    if (isset($filter['more_status'])) {
        $where .= ' AND status>:status';
        $params[':status'] = $filter['more_status'];
    }
    if (isset($filter['product_id'])) {
        if (is_numeric($filter['product_id'])) {
            $where .= ' AND product_id=:product_id';
            $params[':product_id'] = $filter['product_id'];
        } else if (is_array($filter['product_id'])) {
            $where .= ' AND product_id IN('.implode(',', $filter['product_id']).')';
        }
    }
    if (isset($filter['isread'])) {
        $where .= ' AND isread=:isread';
        $params[':isread'] = $filter['isread'];
    }
    if (isset($filter['ordersn'])) {
        $where .= ' AND ordersn LIKE "%'.$filter['ordersn'].'%"';
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY updatetime DESC, id DESC';
    }
    $limit = '';
    if ($pagesize > 0) {
        $limit = "LIMIT {$start},{$pagesize}";
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_order')." {$where} {$orderby} {$limit}";
    return pdo_fetchall($sql, $params);
}

function superman_order_count($filter = array()) {
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    if (isset($filter['in_status']) && is_array($filter['in_status'])) {
        $where .= ' AND status IN('.implode(',', $filter['in_status']).')';
    }
    if (isset($filter['more_status'])) {
        $where .= ' AND status>:status';
        $params[':status'] = $filter['more_status'];
    }
    if (isset($filter['product_id'])) {
        if (is_numeric($filter['product_id'])) {
            $where .= ' AND product_id=:product_id';
            $params[':product_id'] = $filter['product_id'];
        } else if (is_array($filter['product_id'])) {
            $where .= ' AND product_id IN('.implode(',', $filter['product_id']).')';
        }
    }
    if (isset($filter['isread'])) {
        $where .= ' AND isread=:isread';
        $params[':isread'] = $filter['isread'];
    }
    if (isset($filter['start_time'])) {
        $where .= ' AND dateline>=:start_time';
        $params[':start_time'] = $filter['start_time'];
    }
    if (isset($filter['end_time'])) {
        $where .= ' AND dateline<=:end_time';
        $params[':end_time'] = $filter['end_time'];
    }
    if (isset($filter['ordersn'])) {
        $where .= ' AND ordersn LIKE "%'.$filter['ordersn'].'%"';
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_order')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_order_count_status($uid, $product_id, $status) {
    $where = ' WHERE uid=:uid AND product_id=:product_id AND status=:status';
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_order')." {$where}";
    $params = array(
        ':uid' => $uid,
        ':product_id' => $product_id,
        ':status' => $status,
    );
    return pdo_fetchcolumn($sql, $params);
}

function superman_order_sum($field, $filter) {
    if (!in_array($field, array('total', 'price', 'credit'))) {
        return false;
    }
    $sql = "SELECT SUM({$field}) FROM ".tablename('superman_creditmall_order').' WHERE 1=1';
    $params = array();
    if (isset($filter['product_id'])) {
        $sql .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    if (isset($filter['uid'])) {
        $sql .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['status'])) {
        $sql .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    if (isset($filter['more_status'])) {
        $sql .= ' AND status>:status';
        $params[':status'] = $filter['more_status'];
    }
    if (isset($filter['start_time'])) {
        $sql .= ' AND dateline>=:start_time';
        $params[':start_time'] = $filter['start_time'];
    }
    if (isset($filter['end_time'])) {
        $sql .= ' AND dateline<=:end_time';
        $params[':end_time'] = $filter['end_time'];
    }
    return pdo_fetchcolumn($sql, $params);
}

function superman_order_update($data, $condition) {
    return pdo_update('superman_creditmall_order', $data, $condition);
}

function superman_order_delete($id) {
    return pdo_delete('superman_creditmall_order', array('id' => $id));
}

function superman_order_insert($data) {
    pdo_insert('superman_creditmall_order', $data);
    return pdo_insertid();
}

//table: superman_creditmall_product_log
function superman_product_log_status($status = null) {
    $all_status = array(
        '-1' => '失败',
        '0' => '出价',
        '1' => '拍中',
    );
    if ($status !== null && isset($all_status[$status])) {
        return $all_status[$status];
    }
    return $all_status;
}
function superman_product_log_set(&$item) {
    $item['credit'] = superman_format_price($item['credit']);
    $item['credit_title'] = superman_credit_type($item['credit_type']);
    $item['status_title'] = superman_product_log_status($item['status']);
    $item['dateline'] = date('m-d H:i:s', $item['dateline']);
    if ($item['millisecond']) {
        $item['dateline'] .= '.'.$item['millisecond'];
    }
    /*$t = microtime(true);
    $micro = sprintf("%06d",($t - floor($t)) * 1000000);*/

    /*echo $micro.'<hr>';
    $d = new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );
    print $d->format("Y-m-d H:i:s.u");
    die;*/
}

function superman_product_log_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product_log')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_product_log_fetchall_uid($uid, $start = 0, $pagesize = 10) {
    $where = ' WHERE 1=1 AND uid=:uid';
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product_log')." {$where} ORDER BY id DESC LIMIT {$start},{$pagesize}";
    $params = array(
        ':uid' => $uid,
    );
    return pdo_fetchall($sql, $params);
}

function superman_product_log_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    if (isset($filter['last_id'])) {
        $where .= ' AND id>:id';
        $params[':id'] = $filter['last_id'];
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY id DESC';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product_log')." {$where} {$orderby}";
    if ($pagesize > 0) {
        $sql .= " LIMIT {$start},{$pagesize}";
    }
    return pdo_fetchall($sql, $params);
}
function superman_product_log_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    if (isset($filter['start_time'])) {
        $where .= ' AND dateline>=:start_time';
        $params[':start_time'] = $filter['start_time'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_product_log')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

//table: superman_creditmall_dispatch
function superman_dispatch_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_dispatch')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_dispatch_fetchall($filter = array(), $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['id'])) {
        $where .= ' AND id IN('.implode(',', $filter['id']).')';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_dispatch')." {$where} ORDER BY displayorder DESC, id DESC";
    if ($pagesize > 0) {
        $sql .= " LIMIT {$start},{$pagesize}";
    }
    return pdo_fetchall($sql, $params);
}

function superman_dispatch_init($uniacid) {
    $where = ' WHERE uniacid=:uniacid';
    $params = array(
        ':uniacid' => 0,
    );
    $sql = "SELECT * FROM ".tablename('superman_creditmall_dispatch')." {$where}";
    $list = pdo_fetchall($sql, $params);
    $new_list = array();
    if ($list) {
        foreach ($list as $item) {
            unset($item['id']);
            unset($item['displayorder']);
            unset($item['dateline']);
            $item['uniacid'] = $uniacid;
            $item['dateline'] = TIMESTAMP;
            pdo_insert('superman_creditmall_dispatch', $item);
            $new_id = pdo_insertid();
            if ($new_id) {
                $item['id'] = $new_id;
                $new_list[] = $item;
            }
        }
    }
    return $new_list;
}

//table: superman_creditmall_ad
function superman_ad_position($id = 0) {
    $all_positions = array(
        '1' => array(
            'id' => 1,
            'title' => '首页',
            'enable' => 1,
        ),
        '2' => array(
            'id' => 2,
            'title' => '兑换排行',
            'enable' => 1,
        ),
        /*'3' => array(
            'id' => 3,
            'title' => '积分排行',
            'enable' => 0,
        ),*/
        '4' => array(
            'id' => 4,
            'title' => '个人中心',
            'enable' => 0,
        ),
        '5' => array(
            'id' => 5,
            'title' => '商品列表',
            'enable' => 0,
        ),
        '6' => array(
            'id' => 6,
            'title' => '商品详情',
            'enable' => 0,
        ),
        '7' => array(
            'id' => 7,
            'title' => '任务中心',
            'enable' => 1,
        ),
    );
    if ($id > 0 && isset($all_positions[$id])) {
        return $all_positions[$id];
    }
    return $all_positions;
}

function superman_ad_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_ad')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_ad_fetchall($filter = array(), $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['time'])) {
        $where .= ' AND (start_time<='.$filter['time'].' OR start_time=0)';
        $where .= ' AND (end_time>='.$filter['time'].' OR end_time=0)';
    }
    if (isset($filter['start_time'])) {
        $where .= ' AND (start_time<='.$filter['start_time'].' OR start_time=0)';
    }
    if (isset($filter['end_time'])) {
        $where .= ' AND (end_time>='.$filter['end_time'].' OR end_time=0)';
    }
    if (isset($filter['title'])) {
        $where .= ' AND title LIKE "%'.$filter['title'].'%"';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_ad')." {$where} ORDER BY displayorder DESC, id DESC LIMIT {$start},{$pagesize}";
    return pdo_fetchall($sql, $params);
}

function superman_ad_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['time'])) {
        $where .= ' AND (start_time<='.$filter['time'].' OR start_time=0)';
        $where .= ' AND (end_time>='.$filter['time'].' OR end_time=0)';
    }
    if (isset($filter['start_time'])) {
        $where .= ' AND (start_time<='.$filter['start_time'].' OR start_time=0)';
    }
    if (isset($filter['end_time'])) {
        $where .= ' AND (end_time>='.$filter['end_time'].' OR end_time=0)';
    }
    if (isset($filter['title'])) {
        $where .= ' AND title LIKE "%'.$filter['title'].'%"';
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_ad')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_ad_fetchall_posid($filter, $start = 0, $pagesize = 10) {
    global $_W;
    $adlist = array();
    $where = ' WHERE a.id=b.ad_id';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND a.uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND a.isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['time'])) {
        $where .= ' AND (a.start_time<='.$filter['time'].' OR a.start_time=0)';
        $where .= ' AND (a.end_time>='.$filter['time'].' OR a.end_time=0)';
    }
    if (isset($filter['position_id'])) {
        $where .= ' AND b.position_id='.$filter['position_id'];
    }
    $sql = "SELECT a.* FROM ".tablename('superman_creditmall_ad')." AS a,".tablename('superman_creditmall_ad_position')." AS b {$where}";
    $ads = pdo_fetchall($sql, $params);
    if ($ads) {
        foreach ($ads as &$item) {
            superman_ad_set($item);
            if ($item['content']) {
                foreach ($item['content'] as $v) {
                    $adlist[] = array(
                        'title' => $v['title'],
                        'thumb' => $v['thumb'],
                        'url' => $v['url'],
                    );
                }
            }
        }
        unset($item);
    }
    return $adlist;
}

function superman_ad_set(&$item) {
    $item['content'] = $item['content']?iunserializer($item['content']):array();
    if ($item['content']) {
        foreach ($item['content'] as &$v) {
            $v['thumb'] = $v['thumb']?tomedia($v['thumb']):'';
        }
        unset($v);
    }
    $item['start_time'] = $item['start_time']?date('Y-m-d H:i:s', $item['start_time']):date('Y-m-d H:i');
    $item['end_time'] = $item['end_time']?date('Y-m-d H:i:s', $item['end_time']):date('Y-m-d H:i', strtotime('+1 month'));
    $item['ad_time'] = array(
        'start' => $item['start_time'],
        'end' => $item['end_time'],
    );
    $item['position']= array();
    $positions = superman_ad_position_fetchall($item['id']);
    if ($positions) {
        foreach ($positions as $v) {
            $pos = superman_ad_position($v['position_id']);
            $v['title'] = $pos['title'];
            $item['position'][$v['position_id']] = $v;
        }
    }
}

function superman_ad_update($data, $condition) {
    return pdo_update('superman_creditmall_ad', $data, $condition);
}

function superman_ad_delete($id) {
    return pdo_delete('superman_creditmall_ad', array('id' => $id));
}

function superman_ad_insert($data) {
    pdo_insert('superman_creditmall_ad', $data);
    return pdo_insertid();
}

//table: superman_creditmall_ad_position
function superman_ad_position_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_ad_position')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

/*function superman_ad_position_fetch_adid($ad_id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_ad_position')." WHERE ad_id=:ad_id";
    $params = array(
        ':ad_id' => $ad_id,
    );
    return pdo_fetch($sql, $params);
}*/

function superman_ad_position_fetchall($ad_id) {
    global $_W;
    $where = ' WHERE ad_id=:ad_id';
    $params = array(
        ':ad_id' => $ad_id,
    );
    $sql = "SELECT * FROM ".tablename('superman_creditmall_ad_position')." {$where}";
    return pdo_fetchall($sql, $params);
}

function superman_ad_position_fetchall_posid($position_id) {
    global $_W;
    $where = ' WHERE position_id=:position_id';
    $params = array(
        ':position_id' => $position_id,
    );
    $sql = "SELECT * FROM ".tablename('superman_creditmall_ad_position')." {$where}";
    return pdo_fetchall($sql, $params);
}

function superman_ad_position_update($data, $condition) {
    return pdo_update('superman_creditmall_ad_position', $data, $condition);
}

function superman_ad_position_delete($id) {
    return pdo_delete('superman_creditmall_ad_position', array('id' => $id));
}

function superman_ad_position_delete_adid($ad_id) {
    return pdo_delete('superman_creditmall_ad_position', array('ad_id' => $ad_id));
}

function superman_ad_position_insert($data) {
    pdo_insert('superman_creditmall_ad_position', $data);
    return pdo_insertid();
}

//table: superman_creditmall_task
function superman_task_status_title($status) {
    $title = '';
    switch ($status) {
        case -1:
            $title = '失败';
            break;
        case 0:
            $title = '进行中';
            break;
        case 1:
            $title = '已完成';
            break;
        default:
            $title = '未知';
            break;
    }
    return $title;
}
function superman_task_type($typeid = 0) {
    $all_types = array(
        '1' => array(
            'id' => 1,
            'title' => '新手任务',
        ),
        '2' => array(
            'id' => 2,
            'title' => '日常任务',
        ),
        '3' => array(
            'id' => 3,
            'title' => '活动任务',
        ),
    );
    if ($typeid > 0 && isset($all_types[$typeid])) {
        return $all_types[$typeid];
    }
    return $all_types;
}

function superman_task_data() {
    return array(
        array(
            'type' => 1,
            'name' => 'superman_creditmall_task1',
            'title' => '上传头像',
            'description' => '个人信息页面上传头像',
            'icon' => 'addons/superman_creditmall/template/mobile/images/task1.png',
            'url' => './index.php?c=entry&act=setting&do=profile&m=superman_creditmall',
            'credit_type' => 'credit1',
            'credit_min' => 10,
            'credit_max' => 10,
            'isshow' => 1,
            'builtin' => 1,
            'issuperman' => 1,
            'allow_repeat' => 0,
        ),
        array(
            'type' => 1,
            'name' => 'superman_creditmall_task2',
            'title' => '设置昵称',
            'description' => '个人信息页面设置昵称',
            'icon' => 'addons/superman_creditmall/template/mobile/images/task2.png',
            'url' => './index.php?c=entry&act=setting&do=profile&m=superman_creditmall',
            'credit_type' => 'credit1',
            'credit_min' => 10,
            'credit_max' => 10,
            'isshow' => 1,
            'builtin' => 1,
            'issuperman' => 1,
            'allow_repeat' => 0,
        ),
        array(
            'type' => 1,
            'name' => 'superman_creditmall_task3',
            'title' => '绑定手机号',
            'description' => '个人信息页面设置手机号',
            'icon' => 'addons/superman_creditmall/template/mobile/images/task3.png',
            'url' => './index.php?c=entry&act=setting&do=profile&m=superman_creditmall',
            'credit_type' => 'credit1',
            'credit_min' => 10,
            'credit_max' => 10,
            'isshow' => 1,
            'builtin' => 1,
            'issuperman' => 1,
            'allow_repeat' => 0,
        ),
        array(
            'type' => 1,
            'name' => 'superman_creditmall_task4',
            'title' => '绑定邮箱',
            'description' => '个人信息页面设置邮箱',
            'icon' => 'addons/superman_creditmall/template/mobile/images/task4.png',
            'url' => './index.php?c=entry&act=setting&do=profile&m=superman_creditmall',
            'credit_type' => 'credit1',
            'credit_min' => 10,
            'credit_max' => 10,
            'isshow' => 1,
            'builtin' => 1,
            'issuperman' => 1,
            'allow_repeat' => 0,
        ),
        array(
            'type' => 2,
            'name' => 'superman_creditmall_task5',
            'title' => '分享礼品',
            'description' => '分享积分商城中的商品',
            'icon' => 'addons/superman_creditmall/template/mobile/images/task5.png',
            'url' => './index.php?c=entry&act=share&do=list&m=superman_creditmall',
            'credit_type' => 'credit1',
            'credit_min' => 1,
            'credit_max' => 100,
            'isshow' => 1,
            'builtin' => 1,
            'issuperman' => 1,
            'allow_repeat' => 0,
        ),
        array(
            'type' => 1,
            'name' => 'superman_creditmall_task6',
            'title' => '关注公众号',
            'description' => '微信关注公众号',
            'icon' => 'addons/superman_creditmall/template/mobile/images/task6.png',
            'url' => '',
            'credit_type' => 'credit1',
            'credit_min' => 100,
            'credit_max' => 100,
            'isshow' => 1,
            'builtin' => 1,
            'issuperman' => 1,
            'allow_repeat' => 0,
        ),
        //...
        array(
            'type' => 2,
            'name' => 'superman_sign',
            'title' => '每日签到',
            'description' => '每日签到可获得积分奖励',
            'icon' => 'addons/superman_creditmall/template/mobile/images/superman_sign.png',
            'url' => './index.php?c=entry&do=sign&m=superman_sign',
            'credit_type' => 'credit1',
            'credit_min' => 1,
            'credit_max' => 5,
            'isshow' => 0,
            'builtin' => 0,
            'issuperman' => 1,
            'allow_repeat' => 1,
        ),
        array(
            'type' => 2,
            'name' => 'superman_house',
            'title' => '分享楼盘',
            'description' => '分享楼盘获得积分奖励',
            'icon' => 'addons/superman_creditmall/template/mobile/images/superman_house.png',
            'url' => './index.php?c=entry&do=house&m=superman_house',
            'credit_type' => 'credit1',
            'credit_min' => 1,
            'credit_max' => 5,
            'isshow' => 0,
            'builtin' => 0,
            'issuperman' => 1,
            'allow_repeat' => 1,
        ),
        array(
            'type' => 3,
            'name' => '',
            'title' => '自定义任务',
            'description' => '添加自定义活动任务',
            'icon' => 'addons/superman_creditmall/template/mobile/images/superman_custom.png',
            'url' => '',
            'credit_type' => 'credit1',
            'credit_min' => 1,
            'credit_max' => 5,
            'isshow' => 0,
            'builtin' => 0,
            'issuperman' => 0,
            'allow_repeat' => 1,
        ),
    );
}

function superman_task_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_task')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_task_fetch_name($name, $uniacid = 0) {
    global $_W;
    if (!$uniacid) {
        $uniacid = $_W['uniacid'];
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_task')." WHERE name=:name AND uniacid=:uniacid";
    $params = array(
        ':name' => $name,
        ':uniacid' => $uniacid,
    );
    return pdo_fetch($sql, $params);
}

function superman_task_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['type'])) {
        $where .= ' AND type=:type';
        $params[':type'] = $filter['type'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['title'])) {
        $where .= ' AND title LIKE "%'.$filter['title'].'%"';
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY displayorder DESC,id DESC';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_task')." {$where} {$orderby}";
    if ($pagesize > 0) {
        $sql .= " LIMIT {$start},{$pagesize}";
    }
    return pdo_fetchall($sql, $params);
}

function superman_task_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['type'])) {
        $where .= ' AND type=:type';
        $params[':type'] = $filter['type'];
    }
    if (isset($filter['isshow'])) {
        $where .= ' AND isshow=:isshow';
        $params[':isshow'] = $filter['isshow'];
    }
    if (isset($filter['title'])) {
        $where .= ' AND title LIKE "%'.$filter['title'].'%"';
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_task')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_task_set(&$item) {
    global $_W;
    $item['credit_title'] = superman_credit_type($item['credit_type']);
    $item['credit_min'] = superman_format_price($item['credit_min']);
    $item['credit_max'] = superman_format_price($item['credit_max']);
    if ($item['credit_min'] == $item['credit_max']) {
        $item['award'] = $item['credit_min'];
        $item['credit'] = $item['credit_min'];
    } else {
        $item['award'] = $item['credit_min'].'-'.$item['credit_max'];
        //$item['credit'] = superman_random_float($item['credit_min'], $item['credit_max']);
        $item['credit'] = mt_rand($item['credit_min'], $item['credit_max']);
    }
    $item['starttime'] = $item['starttime']?date('Y-m-d H:i:s', $item['starttime']):'';
    $item['endtime'] = $item['endtime']?date('Y-m-d H:i:s', $item['endtime']):'';
    $item['icon'] = strexists($item['icon'], 'addons/superman_creditmall/template/mobile/images/')?$_W['siteroot'].$item['icon']:tomedia($item['icon']);
    $item['extend'] = $item['extend']?iunserializer($item['extend']):array();
    //$item['abs_url'] = superman_task_url($item);
    $item['applyperm'] = $item['applyperm']?iunserializer($item['applyperm']):array();
}

function superman_task_update_count($id, $field, $value = 1) {
    if (!in_array($field, array('applied', 'completed'))) {
        return false;
    }
    $sql = 'UPDATE '.tablename('superman_creditmall_task')." SET {$field}={$field}+{$value} WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_query($sql, $params)>0?true:false;
}

function superman_task_update($data, $condition) {
    return pdo_update('superman_creditmall_task', $data, $condition);
}

function superman_task_delete($id) {
    return pdo_delete('superman_creditmall_task', array('id' => $id));
}

function superman_task_insert($data) {
    pdo_insert('superman_creditmall_task', $data);
    return pdo_insertid();
}

//table: superman_creditmall_mytask
function superman_mytask_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_mytask')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_mytask_fetch_uid($uid, $task_id) {
    global $_W;
    $sql = "SELECT * FROM ".tablename('superman_creditmall_mytask')." WHERE uniacid=:uniacid AND uid=:uid AND task_id=:task_id";
    $params = array(
        ':uniacid' => $_W['uniacid'],
        ':uid' => $uid,
        ':task_id' => $task_id,
    );
    return pdo_fetch($sql, $params);
}

function superman_mytask_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['task_id'])) {
        $where .= ' AND task_id=:task_id';
        $params[':task_id'] = $filter['task_id'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY id DESC';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_mytask')." {$where} {$orderby}";
    if ($pagesize > 0) {
        $sql .= " LIMIT {$start},{$pagesize}";
    }
    return pdo_fetchall($sql, $params, 'task_id');
}

function superman_mytask_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['task_id'])) {
        $where .= ' AND task_id=:task_id';
        $params[':task_id'] = $filter['task_id'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_mytask')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_mytask_update($data, $condition) {
    return pdo_update('superman_creditmall_mytask', $data, $condition);
}

function superman_mytask_delete($id) {
    return pdo_delete('superman_creditmall_mytask', array('id' => $id));
}

function superman_mytask_insert($data) {
    pdo_insert('superman_creditmall_mytask', $data);
    return pdo_insertid();
}

//table: superman_creditmall_product_share
function superman_product_share_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product_share')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_product_share_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['friend_uid'])) {
        $where .= ' AND friend_uid=:friend_uid';
        $params[':friend_uid'] = $filter['friend_uid'];
    }
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY id DESC';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_product_share')." {$where} {$orderby}";
    if ($pagesize > 0) {
        $sql .= " LIMIT {$start},{$pagesize}";
    }
    return pdo_fetchall($sql, $params);
}

function superman_product_share_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['friend_uid'])) {
        $where .= ' AND friend_uid=:friend_uid';
        $params[':friend_uid'] = $filter['friend_uid'];
    }
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_product_share')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_prodcut_share_sum($filter = array()) {
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['starttime'])) {
        $where .= ' AND dateline>='.$filter['starttime'];
    }
    if (isset($filter['endtime'])) {
        $where .= ' AND dateline<='.$filter['endtime'];
    }
    $sql = "SELECT SUM(credit) FROM ".tablename('superman_creditmall_product_share')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_product_share_update($data, $condition) {
    return pdo_update('superman_creditmall_product_share', $data, $condition);
}

function superman_product_share_delete($id) {
    return pdo_delete('superman_creditmall_product_share', array('id' => $id));
}

function superman_product_share_insert($data) {
    pdo_insert('superman_creditmall_product_share', $data);
    return pdo_insertid();
}

//table: superman_credit_stat
function superman_stat_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_stat')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_stat_fetch_daytime($daytime) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_stat')." WHERE daytime=:daytime";
    $params = array(
        ':daytime' => $daytime,
    );
    return pdo_fetch($sql, $params);
}

function superman_stat_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10, $keyfield = 'daytime') {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['daytime'])) {
        $where .= ' AND daytime=:daytime';
        $params[':daytime'] = $filter['daytime'];
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY id DESC';
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_stat')." {$where} {$orderby}";
    if ($pagesize > 0) {
        $sql .= " LIMIT {$start},{$pagesize}";
    }
    return pdo_fetchall($sql, $params, $keyfield);
}

function superman_stat_update_count($daytime, $field, $value = 1) {
    global $_W;
    if (!in_array($field, array('product_views', 'product_shares', 'product_comments'))) {
        return false;
    }
    $row = superman_stat_fetch_daytime($daytime);
    if (!$row) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'daytime' => $daytime,
            $field => $value,
        );
        return superman_stat_insert($data)?true:false;
    } else {
        $sql = 'UPDATE '.tablename('superman_creditmall_stat')." SET {$field}={$field}+{$value} WHERE id=:id";
        $params = array(
            ':id' => $row['id'],
        );
        return pdo_query($sql, $params)>0?true:false;
    }
}

function superman_stat_update($data, $condition) {
    return pdo_update('superman_creditmall_stat', $data, $condition);
}

function superman_stat_delete($id) {
    return pdo_delete('superman_creditmall_stat', array('id' => $id));
}

function superman_stat_insert($data) {
    pdo_insert('superman_creditmall_stat', $data);
    return pdo_insertid();
}

//table:superman_creditmall_virtual_stuff
function superman_virtual_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    $params = array();
    $where = ' WHERE 1=1';
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    if ($orderby == '') {
        $orderby = ' ORDER BY id DESC';
    }
    $sql = 'SELECT * FROM '.tablename('superman_creditmall_virtual_stuff')." {$where} {$orderby} LIMIT {$start},{$pagesize}";

    return pdo_fetchall($sql, $params);
}

function superman_virtual_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    if (isset($filter['status'])) {
        $where .= ' AND status=:status';
        $params[':status'] = $filter['status'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_virtual_stuff')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

function superman_virtual_set(&$virtual) {
    $virtual['dateline'] = date('Y-m-d H:i:s', $virtual['dateline']);
    $virtual['get_time'] = !empty($virtual['get_time'])?date('Y-m-d H:i:s', $virtual['get_time']):'';
    $virtual['extend'] = $virtual['extend']?iunserializer($virtual['extend']):array();
    if ($virtual['uid'] > 0) {
        $virtual['member'] = mc_fetch($virtual['uid'], array('nickname', 'avatar'));
    }
    return $virtual;
}

//table: superman_creditmall_comment
function superman_comment_fetch($id) {
    $sql = "SELECT * FROM ".tablename('superman_creditmall_comment')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_comment_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10, $keyfield = '') {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    if ($orderby == '') {
        $orderby = 'ORDER BY id DESC';
    }
    $limit = '';
    if ($pagesize > 0) {
        $limit = "LIMIT {$start},{$pagesize}";
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_comment')." {$where} {$orderby} {$limit}";
    return pdo_fetchall($sql, $params, $keyfield);
}

function superman_comment_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['product_id'])) {
        $where .= ' AND product_id=:product_id';
        $params[':product_id'] = $filter['product_id'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_comment')." {$where}";
    return pdo_fetchall($sql, $params);
}

function superman_comment_update($data, $condition) {
    return pdo_update('superman_creditmall_comment', $data, $condition);
}

function superman_comment_delete($id) {
    return pdo_delete('superman_creditmall_comment', array('id' => $id));
}

function superman_comment_insert($data) {
    pdo_insert('superman_creditmall_comment', $data);
    return pdo_insertid();
}

//table: superman_creditmall_checkout_log
function superman_checkout_log_fetch($id, $fields = '*') {
    if (is_array($fields)) {
        $fields = implode(',', $fields);
    }
    $sql = "SELECT $fields FROM ".tablename('superman_creditmall_checkout_log')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_checkout_log_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    if (isset($filter['orderid'])) {
        $where .= ' AND orderid=:orderid';
        $params[':orderid'] = $filter['orderid'];
    }
    $limit = '';
    if ($pagesize > 0) {
        $limit = "LIMIT {$start},{$pagesize}";
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_checkout_log')." {$where} {$orderby} {$limit}";
    return pdo_fetchall($sql, $params);
}

function superman_checkout_log_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_checkout_log')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

//table: superman_creditmall_checkout_user
function superman_checkout_user_fetch($id, $fields = '*') {
    if (is_array($fields)) {
        $fields = implode(',', $fields);
    }
    $sql = "SELECT $fields FROM ".tablename('superman_creditmall_checkout_user')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_checkout_user_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    $limit = '';
    if ($pagesize > 0) {
        $limit = "LIMIT {$start},{$pagesize}";
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_checkout_user')." {$where} {$orderby} {$limit}";
    return pdo_fetchall($sql, $params);
}

function superman_checkout_user_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['openid'])) {
        $where .= ' AND openid=:openid';
        $params[':openid'] = $filter['openid'];
    }
    if (isset($filter['uid'])) {
        $where .= ' AND uid=:uid';
        $params[':uid'] = $filter['uid'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_checkout_user')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

//table: superman_creditmall_checkout_code
function superman_checkout_code_fetch($id, $fields = '*') {
    if (is_array($fields)) {
        $fields = implode(',', $fields);
    }
    $sql = "SELECT $fields FROM ".tablename('superman_creditmall_checkout_code')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_checkout_code_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    $limit = '';
    if ($pagesize > 0) {
        $limit = "LIMIT {$start},{$pagesize}";
    }
    $sql = "SELECT * FROM ".tablename('superman_creditmall_checkout_code')." {$where} {$orderby} {$limit}";
    return pdo_fetchall($sql, $params);
}

function superman_checkout_code_count($filter = array()) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    $sql = "SELECT COUNT(*) FROM ".tablename('superman_creditmall_checkout_code')." {$where}";
    return pdo_fetchcolumn($sql, $params);
}

//table: uni_settings
function superman_credit_type($type = '', $uniacid = 0) {
    global $_W;
    static $data = null;
    if ($data !== null) {
        if ($type != '') {
            return $data[$type]['title'];
        }
        return $data;
    }
    $sql = 'SELECT `creditnames` FROM '.tablename('uni_settings').' WHERE `uniacid` = :uniacid';
    if ($uniacid > 0) {
        $params = array(
            ':uniacid' => $uniacid,
        );
    } else {
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );
    }
    $data = pdo_fetch($sql, $params);
    if(!empty($data['creditnames'])) {
        $data = iunserializer($data['creditnames']);
        if (is_array($data)) {
            if ($type) {
                foreach($data as $k => $v) {
                    if ($type == $k && $v['enabled'] == 1) {
                        return $v['title'];
                    }
                }
            } else {
                return $data;
            }
        }
    }
}

//table: mc_member_address
function superman_mc_address_fetch($id) {
    $sql = "SELECT * FROM ".tablename('mc_member_address')." WHERE id=:id";
    $params = array(
        ':id' => $id,
    );
    return pdo_fetch($sql, $params);
}

function superman_mc_address_fetch_uid($uid, $isdefault = 1) {
    $sql = "SELECT * FROM ".tablename('mc_member_address')." WHERE uid=:uid AND isdefault=:isdefault";
    $params = array(
        ':uid' => $uid,
        ':isdefault' => $isdefault,
    );
    return pdo_fetch($sql, $params);
}

function superman_mc_address_fetchall_uid($uid) {
    $sql = "SELECT * FROM ".tablename('mc_member_address')." WHERE uid=:uid";
    $params = array(
        ':uid' => $uid,
    );
    return pdo_fetchall($sql, $params);
}

//table: mc_members
function superman_mycredit($uid, $credit_type = '', $force_fetch = false) {
    static $result = null;
    if ($force_fetch || $result === null) {
        $data = mc_credit_fetch($uid);
        if ($data) {
            $credit_types = superman_credit_type();
            if ($credit_type != '' && isset($data[$credit_type])) {
                $result[$credit_type] = array(
                    'value' => $data[$credit_type],
                    'title' => isset($credit_types[$credit_type]['title'])?$credit_types[$credit_type]['title']:'',
                );
                return $result[$credit_type];
            }
            foreach ($data as $k=>$v) {
                $result[$k] = array(
                    'value' => superman_format_price($v),
                    'title' => isset($credit_types[$k]['title'])?$credit_types[$k]['title']:'',
                );
            }
        }
    }
    return $result;
}

//table: core_paylog
function superman_core_paylog_fetch($plid) {
    $sql = 'SELECT * FROM '.tablename('core_paylog').' WHERE plid=:plid';
    $params = array(
        ':plid' => $plid,
    );
    return pdo_fetch($sql, $params);
}

function superman_core_paylog_fetchall($filter = array(), $start = 0, $pagesize = 10) {
    global $_W;
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['module'])) {
        $where .= ' AND module=:module';
        $params[':module'] = $filter['module'];
    }
    if (isset($filter['tid'])) {
        $where .= ' AND tid=:tid';
        $params[':tid'] = $filter['tid'];
    }
    $sql = "SELECT * FROM ".tablename('core_paylog')." {$where} ORDER BY plid DESC LIMIT {$start},{$pagesize}";
    return pdo_fetchall($sql, $params);
}

//table: mc_groups
function superman_mc_groups_fetch($groupid) {
    $sql = "SELECT * FROM ".tablename('mc_groups')." WHERE groupid=:groupid";
    $params = array(
        ':groupid' => $groupid,
    );
    return pdo_fetch($sql, $params);
}

function superman_mc_groups_fetchall($filter = array(), $orderby = '', $start = 0, $pagesize = 10) {
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['groupid'])) {
        if (is_array($filter['groupid'])) {
            $where .= ' AND groupid IN('.implode(',', $filter['groupid']).')';
        } else {
            $where .= ' AND groupid=:groupid';
            $params[':groupid'] = $filter['groupid'];
        }
    }
    if ($orderby == '') {
        if (IMS_VERSION == 0.6) {
            $orderby = 'ORDER BY isdefault DESC';
        } else {
            $orderby = 'ORDER BY isdefault DESC,credit ASC';
        }
    }
    $limit = '';
    if ($pagesize > 0) {
        $limit = "LIMIT {$start},{$pagesize}";
    }
    $sql = "SELECT * FROM ".tablename('mc_groups')." {$where} {$orderby} {$limit}";
    //echo $sql;
    return pdo_fetchall($sql, $params);
}

//table: mc_credits_record
function superman_credit_sum($filter = array()) {
    $where = ' WHERE 1=1';
    $params = array();
    if (isset($filter['uniacid'])) {
        $where .= ' AND uniacid=:uniacid';
        $params[':uniacid'] = $filter['uniacid'];
    }
    if (isset($filter['credittype'])) {
        $where .= ' AND credittype=:credittype';
        $params[':credittype'] = $filter['credittype'];
    }
    if (isset($filter['num_up'])) {
        $where .= ' AND num>:num_up';
        $params[':num_up'] = $filter['num_up'];
    }
    if (isset($filter['num_down'])) {
        $where .= ' AND num<:num_down';
        $params[':num_down'] = $filter['num_down'];
    }
    if (isset($filter['start_time'])) {
        $where .= ' AND createtime>=:start_time';
        $params[':start_time'] = $filter['start_time'];
    }
    if (isset($filter['end_time'])) {
        $where .= ' AND createtime<=:end_time';
        $params[':end_time'] = $filter['end_time'];
    }
    $sql = "SELECT SUM(num) FROM ".tablename('mc_credits_record')." {$where}";
    return floatval(pdo_fetchcolumn($sql, $params));
}

//table:superman_creditmall_navigation
function superman_navigation_data () {
    global $_W;
    return array(
        array(
            'icon' => 'icon icon-home',
            'title' => '首页',
            'url' => murl('entry', array('do' => 'home', 'm' => 'superman_creditmall')),
            'displayorder' => '5',
            'isshow' => '1',
        ),
        array(
            'icon' => 'icon icon-gift',
            'title' => '兑换排行',
            'url' => murl('entry', array('do' => 'exchangerank', 'm' => 'superman_creditmall')),
            'displayorder' => '4',
            'isshow' => '1',
        ),
        array(
            'icon' => 'icon icon-app',
            'title' => '任务中心',
            'url' => murl('entry', array('do' => 'task', 'm' => 'superman_creditmall')),
            'displayorder' => '3',
            'isshow' => '1',
        ),
        array(
            'icon' => 'icon icon-me',
            'title' => '个人中心',
            'url' => murl('entry', array('do' => 'profile', 'm' => 'superman_creditmall')),
            'displayorder' => '2',
            'isshow' => '1',
        ),
    );
}