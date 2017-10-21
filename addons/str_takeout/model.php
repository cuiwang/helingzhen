<?php
function p($data)
{
    echo '<pre>';
    print_r($data);
}
function order_types()
{
    $data = array(
        '0' => '',
        '1' => array(
            'text' => '店内',
            'css' => 'label label-success'
        ),
        '2' => array(
            'text' => '外卖',
            'css' => 'label label-danger'
        ),
        '3' => array(
            'text' => '订座',
            'css' => 'label label-info'
        ),
        '4' => array(
            'text' => '预定',
            'css' => 'label label-warning'
        )
    );
    return $data;
}
function date2week($timestamp)
{
    $weekdays = array(
        '周日',
        '周一',
        '周二',
        '周三',
        '周四',
        '周五',
        '周六'
    );
    $week     = date('w', $timestamp);
    return $weekdays[$week];
}
function table_qrcode_scan($sid, $table_id)
{
    global $_W;
    $data = array(
        'uniacid' => $_W['uniacid'],
        'sid' => $sid,
        'table_id' => $table_id,
        'openid' => $_W['openid'],
        'nickname' => $_W['member']['nickname'],
        'avatar' => $_W['member']['avatar'],
        'createtime' => TIMESTAMP
    );
    pdo_insert('str_tables_scan', $data);
    pdo_update('str_tables', array(
        'scan_num' => $table['scan_num'] + 1
    ), array(
        'uniacid' => $_W['uniacid'],
        'id' => $table_id
    ));
    return true;
}
function get_order_user($sid)
{
    global $_W;
    $user = pdo_get('str_users', array(
        'uniacid' => $_W['uniacid'],
        'sid' => $sid,
        'uid' => $_W['member']['uid']
    ));
    return $user;
}
function set_order_user($sid, $mobile = '', $realname = '')
{
    global $_W;
    if (empty($_W['member']['uid'])) {
        return false;
    }
    $user = pdo_get('str_users', array(
        'uniacid' => $_W['uniacid'],
        'sid' => $sid,
        'uid' => $_W['member']['uid']
    ));
    if (empty($user)) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'sid' => $sid,
            'uid' => $_W['member']['uid'],
            'realname' => $realname ? $realname : $_W['member']['realname'],
            'nickname' => $_W['member']['nickname'],
            'avatar' => $_W['member']['avatar'],
            'mobile' => $mobile ? $mobile : $_W['member']['mobile']
        );
        pdo_insert('str_users', $data);
    }
    return true;
}
function sms_init()
{
    global $_W;
    if (empty($_W['str_takeout']['sms']) || !is_array($_W['str_takeout']['sms'])) {
        return error(-1, '管理员未配置短信接口');
    }
    if (!$_W['str_takeout']['sms']['status']) {
        return error(-1, '未开启短信功能');
    }
    if (!$_W['str_takeout']['sms']['name'] || !$_W['str_takeout']['sms']['password']) {
        return error(-1, '短信账号密码不正确');
    }
    $_W['str_takeout']['sms']['api'] = 'http://sms.106jiekou.com/utf8/sms.aspx?';
    return $_W['str_takeout']['sms'];
}
function sms_send($sid, $mobile, $content)
{
    $sms = sms_init();
    if (is_error($sms)) {
        return $sms;
    }
    $data = array(
        'account' => $sms['name'],
        'password' => $sms['password'],
        'mobile' => $mobile,
        'content' => rawurlencode($content)
    );
    $data = http_build_query($data);
    load()->func('communication');
    $response = ihttp_get($sms['api'] . $data);
    if (is_error($response)) {
        return $response;
    }
    $code = $response['content'];
    if ($code == 100) {
        return true;
    }
    $error = array(
        '101' => '验证失败',
        '102' => '手机号码格式不正确',
        '103' => '会员级别不够',
        '104' => '内容未审核',
        '105' => '内容过多',
        '106' => '账户余额不足',
        '107' => 'Ip受限',
        '108' => '手机号码发送太频繁，请换号或隔天再发',
        '109' => '帐号被锁定',
        '110' => '手机号发送频率持续过高，黑名单屏蔽数日',
        '120' => '系统升级'
    );
    return error(-1, "发送验证码失败:{$error[$code]}");
}
function get_board_status()
{
    $data = array(
        '0' => array(),
        '1' => array(
            'css' => 'label label-primary',
            'text' => '排队中'
        ),
        '2' => array(
            'css' => 'label label-success',
            'text' => '已入号'
        ),
        '3' => array(
            'css' => 'label label-danger',
            'text' => '已取消'
        ),
        '4' => array(
            'css' => 'label label-warning',
            'text' => '已过号'
        )
    );
    return $data;
}
function get_table($id)
{
    global $_W;
    $table = pdo_fetch('select a.*, b.title as ctitle from ' . tablename('str_tables') . ' as a left join ' . tablename('str_tables_category') . ' as b on a.cid = b.id where a.uniacid = :uniacid and a.id = :id', array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    ));
    return $table;
}
function get_table_status()
{
    $data = array(
        '0' => array(),
        '1' => array(
            'css' => 'label label-default',
            'css_block' => 'block-gray',
            'text' => '空闲中'
        ),
        '2' => array(
            'css' => 'label label-danger',
            'css_block' => 'block-red',
            'text' => '已开台'
        ),
        '3' => array(
            'css' => 'label label-primary',
            'css_block' => 'block-primary',
            'text' => '已下单'
        ),
        '4' => array(
            'css' => 'label label-success',
            'css_block' => 'block-success',
            'text' => '已支付'
        )
    );
    return $data;
}
function checkstore()
{
    global $_W, $_GPC;
    if (!defined('IN_MOBILE')) {
        $sid = intval($_GPC['__sid']);
    } else {
        $sid = intval($_GPC['sid']);
    }
    if (!defined('IN_MOBILE')) {
        if ($_W['role'] != 'manager' && empty($_W['isfounder'])) {
            $data = pdo_get('str_account', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'uid' => $_W['uid']
            ));
            if (empty($data)) {
                exit('您没有管理该门店的权限');
            }
        }
    }
    $store = pdo_fetch('SELECT id, title FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
        ':aid' => $_W['uniacid'],
        ':id' => $sid
    ));
    if (empty($store)) {
        if (!defined('IN_MOBILE')) {
            message('门店信息不存在或已删除', '', 'error');
        }
        exit();
    }
    return $store;
}
function set_order_log($id, $sid, $note)
{
    global $_W;
    $codes  = array(
        '2' => '商家已确认订单.商品正在准备中...',
        '3' => '订单完成',
        '4' => '订单已取消',
        '5' => '订单支付成功(管理员操作)',
		'8' => '订单已申请退款',
		'9' => '订单已退款成功'
    );
    $status = intval($note);
    if ($status > 0) {
        $note = $codes[$note];
    }
    $data = array(
        'uniacid' => $_W['uniacid'],
        'sid' => $sid,
        'oid' => $id,
        'note' => $note,
        'addtime' => TIMESTAMP
    );
    pdo_insert('str_order_log', $data);
    return true;
}
function get_order_log($id)
{
    global $_W;
    $data = pdo_fetchall("SELECT * FROM " . tablename('str_order_log') . ' WHERE uniacid = :uniacid and oid = :oid order by id desc', array(
        ':uniacid' => $_W['uniacid'],
        ':oid' => $id
    ));
    return $data;
}
function dish_group_price($price)
{
    global $_W;
    $price     = (array) iunserializer($price);
    $price_tmp = $price[0];
    if (empty($_W['member']['groupid'])) {
        $_W['member']['groupid'] = pdo_fetchcolumn('select groupid from ' . tablename('mc_members') . ' where uid = :uid', array(
            ':uid' => $_W['member']['uid']
        ));
    }
    if (empty($_W['member']['groupid']) || empty($price[$_W['member']['groupid']])) {
        return $price_tmp;
    } else {
        return $price[$_W['member']['groupid']];
    }
}
function get_config($uniacid = 0)
{
    global $_W;
    $uniacid = intval($uniacid);
    if (!$uniacid) {
        $uniacid = $_W['uniacid'];
    }
    $data = pdo_fetch("SELECT * FROM " . tablename('str_config') . ' WHERE uniacid = :uniacid', array(
        ':uniacid' => $uniacid
    ));
    if (empty($data)) {
        return $data;
    }
    if (!empty($data['sms'])) {
        $data['sms'] = iunserializer($data['sms']);
    } else {
        $data['sms'] = array();
    }
    if (!empty($data['notice'])) {
        $data['notice'] = iunserializer($data['notice']);
    } else {
        $data['notice'] = array();
    }
    return $data;
}
function init_print_order($store_id, $order_id, $type = 'order')
{
    $store = get_store($store_id, array(
        'id',
        'print_type'
    ));
    if (empty($store)) {
        return error(-1, '商家不存在');
    }
    if ($store['print_type'] == 1 && $type == 'order') {
        print_order($order_id);
        return true;
    }
    if ($store['print_type'] == 2 && $type == 'pay') {
        print_order($order_id);
        return true;
    }
    return true;
}
function check_trash($sid, $uid = 0, $type = 'exit')
{
    global $_W;
    $uid = intval($uid);
    if (!$uid) {
        $uid = $_W['member']['uid'];
    }
    $isexist = pdo_fetchcolumn("SELECT uid FROM " . tablename('str_user_trash') . ' WHERE uniacid = :uniacid AND sid = :sid AND uid = :uid', array(
        ':uniacid' => $_W['uniacid'],
        ':sid' => $sid,
        ':uid' => $uid
    ));
    if ($type == 'exit') {
        if (!empty($isexist)) {
            message('您被添加到商户黑名单了,不能进行点餐', '', 'error');
        } else {
            return true;
        }
    } else {
        return $isexist;
    }
}
function pay_types()
{
    $pay_types = array(
        '' => '未支付',
        'alipay' => '支付宝支付',
        'wechat' => '微信支付',
        'credit' => '余额支付',
        'delivery' => '餐到付款',
        'cash' => '现金支付'
    );
    return $pay_types;
}
function get_address($id)
{
    global $_W;
    $data = pdo_fetch("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND id = :id', array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    ));
    return $data;
}
function get_addresses()
{
    global $_W;
    $data = pdo_fetchall("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND uid = :uid ORDER BY is_default DESC,id DESC', array(
        ':uniacid' => $_W['uniacid'],
        ':uid' => $_W['member']['uid']
    ));
    return $data;
}
function get_default_address()
{
    global $_W;
    $data = pdo_fetch("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND uid = :uid AND is_default = 1', array(
        ':uniacid' => $_W['uniacid'],
        ':uid' => $_W['member']['uid']
    ));
    if (empty($data)) {
        $data = pdo_fetch("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND uid = :uid ORDER BY id DESC', array(
            ':uniacid' => $_W['uniacid'],
            ':uid' => $_W['member']['uid']
        ));
    }
    return $data;
}
function get_mine_order()
{
    global $_W;
    $data = pdo_fetchall("SELECT * FROM " . tablename('str_order') . ' WHERE uniacid = :uniacid AND uid = :uid ORDER BY id DESC', array(
        ':uniacid' => $_W['uniacid'],
        ':uid' => $_W['member']['uid']
    ));
    return $data;
}
function set_order_cart($sid)
{
    global $_W, $_GPC;
    if (!empty($_GPC['dish'])) {
        $num          = 0;
        $price        = 0;
        $ids_str      = implode(',', array_keys($_GPC['dish']));
        $dish_info    = pdo_fetchall('SELECT * FROM ' . tablename('str_dish') . " WHERE uniacid = :aid AND sid = :sid AND id IN ($ids_str)", array(
            ':aid' => $_W['uniacid'],
            ':sid' => $sid
        ), 'id');
        $grant_credit = 0;
        foreach ($_GPC['dish'] as $k => $v) {
            $k = intval($k);
            $v = intval($v);
            if ($k && $v) {
                $dishes[$k] = $v;
                $num += $v;
                $price += dish_group_price($dish_info[$k]['price']) * $v;
                $grant_credit += ($dish_info[$k]['grant_credit'] * $v);
            }
        }
        $isexist = pdo_fetchcolumn('SELECT id FROM ' . tablename('str_order_cart') . " WHERE uniacid = :aid AND sid = :sid AND uid = :uid", array(
            ':aid' => $_W['uniacid'],
            ':sid' => $sid,
            ':uid' => $_W['member']['uid']
        ));
        $data    = array(
            'uniacid' => $_W['uniacid'],
            'sid' => $sid,
            'uid' => $_W['member']['uid'],
            'groupid' => $_W['member']['groupid'],
            'num' => $num,
            'price' => $price,
            'grant_credit' => $grant_credit,
            'data' => iserializer($dishes),
            'addtime' => TIMESTAMP
        );
        if (empty($isexist)) {
            pdo_insert('str_order_cart', $data);
        } else {
            pdo_update('str_order_cart', $data, array(
                'uniacid' => $_W['uniacid'],
                'id' => $isexist,
                'uid' => $_W['member']['uid']
            ));
        }
        $data['data'] = $dishes;
        return $data;
    } else {
        return error(-1, '菜品信息错误');
    }
    return true;
}
function get_order_cart($sid)
{
    global $_W, $_GPC;
    $cart = pdo_fetch('SELECT * FROM ' . tablename('str_order_cart') . " WHERE uniacid = :aid AND sid = :sid AND uid = :uid", array(
        ':aid' => $_W['uniacid'],
        ':sid' => $sid,
        ':uid' => $_W['member']['uid']
    ));
    if (empty($cart)) {
        return array(
            'num' => 0,
            'price' => 0
        );
    }
    if ((TIMESTAMP - $cart['addtime']) > 7 * 86400) {
        pdo_delete('str_order_cart', array(
            'id' => $cart['id']
        ));
    }
    $cart['data'] = iunserializer($cart['data']);
    return $cart;
}
function del_order_cart($sid)
{
    global $_W;
    pdo_delete('str_order_cart', array(
        'sid' => $sid,
        'uid' => $_W['member']['uid']
    ));
    return true;
}
function checkclerk($sid)
{
    global $_W;
    $_W['is_manager'] = 0;
    $openid           = trim($_W['openid']);
    $sid              = intval($sid);
    if (empty($openid)) {
        return error(-1, '系统没有获取到您的身份');
    }
    $clerk = pdo_fetch('SELECT * FROM ' . tablename('str_clerk') . ' WHERE uniacid = :aid AND openid = :openid AND sid = :sid AND is_sys = 1', array(
        ':aid' => $_W['uniacid'],
        ':openid' => $openid,
        ':sid' => $sid
    ));
    if (empty($clerk)) {
        return error(-1, '您没有权限进行订单管理');
    }
    $_W['is_manager'] = 1;
    return $clerk;
}
function get_store($id, $field = array())
{
    global $_W;
    $field_str = '*';
    if (!empty($field)) {
        $field_str = implode(',', $field);
    }
    $data           = pdo_fetch("SELECT {$field_str} FROM " . tablename('str_store') . ' WHERE uniacid = :uniacid AND id = :id', array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    ));
    $data['thumbs'] = array();
    if (!empty($data['thumbs'])) {
        $data['thumbs'] = iunserializer($data['thumbs']);
    }
    if (!empty($data['copyright'])) {
        $data['copyright'] = iunserializer($data['copyright']);
    } else {
        $data['copyright'] = array();
    }
    if (!empty($data['sns'])) {
        $data['sns'] = iunserializer($data['sns']);
    } else {
        $data['sns'] = array(
            'qq' => '',
            'weixin' => ''
        );
    }
    if (!empty($data['mobile_verify'])) {
        $data['mobile_verify'] = iunserializer($data['mobile_verify']);
    } else {
        $data['mobile_verify'] = array(
            'first_verify' => 0,
            'takeout_verify' => 0
        );
    }
    return $data;
}
function get_order($id)
{
    global $_W;
    $id    = intval($id);
    $order = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
        ':aid' => $_W['uniacid'],
        ':id' => $id
    ));
    return $order;
}
function get_dish($oid, $cancel = false)
{
    global $_W;
    $oid       = intval($oid);
    $condition = '';
    if (!$cancel) {
        $condition = ' AND is_complete != 2';
    }
    $data = pdo_fetchall('SELECT * FROM ' . tablename('str_stat') . ' WHERE uniacid = :aid AND oid = :oid' . $condition, array(
        ':aid' => $_W['uniacid'],
        ':oid' => $oid
    ), 'dish_id');
    return $data;
}
function get_clerks($sid)
{
    global $_W;
    $data = pdo_fetchall("SELECT * FROM " . tablename('str_clerk') . ' WHERE uniacid = :uniacid AND sid = :sid', array(
        ':uniacid' => $_W['uniacid'],
        ':sid' => $sid
    ));
    return $data;
}
function get_clerk($id)
{
    global $_W;
    $data = pdo_fetch("SELECT * FROM " . tablename('str_clerk') . ' WHERE uniacid = :uniacid AND id = :id', array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    ));
    return $data;
}
function print_order($id, $force = false)
{
    global $_W;
    $order = get_order($id);
    if (empty($order)) {
        return error(-1, '订单不存在');
    }
    if ($order['print_nums'] >= 1 && !$force) {
        return error(-1, '已经打印过该订单');
    }
    $sid    = intval($order['sid']);
    $store  = get_store($order['sid'], array(
        'title'
    ));
    $prints = pdo_fetchall('SELECT * FROM ' . tablename('str_print') . ' WHERE uniacid = :aid AND sid = :sid AND status = 1', array(
        ':aid' => $_W['uniacid'],
        ':sid' => $sid
    ));
    if (empty($prints)) {
        return error(-1, '没有有效的打印机');
    }
	$num = 0;
	//$num += jinyun_print($order, $store);
    foreach ($prints as $li) {

			$num += jinyun_print($order, $store, $li);

			
    }
    if ($num > 0) {
        pdo_query('UPDATE ' . tablename('str_order') . " SET print_nums = print_nums + {$num} WHERE uniacid = {$_W['uniacid']} AND id = {$order['id']}");
    } else {
        return error(-1, '发送打印指令失败。没有有效的打印机或没有开启打印机');
    }
    return true;
}
function jinyun_print($order, $store,$print)
{
    global $_W, $_GPC;
    $orderid   = str_pad($order['id'], 8, '0', STR_PAD_LEFT);
    $pay_types = pay_types();
    if (empty($order['pay_type'])) {
        $order['pay_type'] = '未支付';
    } else {
        $order['pay_type'] = !empty($pay_types[$order['pay_type']]) ? $pay_types[$order['pay_type']] : '其他支付方式';
    }
    if (empty($order['delivery_time'])) {
        $order['delivery_time'] = '尽快送出';
    }
    $order['dish'] = get_dish($order['id']);
    $orderinfo     = '';
    $orderinfo .= "{$store['title']}\n";

    $orderinfo .= '名称　　　　　 单价  数量   金额\n';
    $orderinfo .= '--------------------------------\n';
    if (!empty($order['dish'])) {
        foreach ($order['dish'] as $di) {
            $dan = ($di['dish_price'] / $di['dish_num']);
            $orderinfo .= str_pad(cutstr($di['dish_title'], 7), '21', '　', STR_PAD_RIGHT);
            $orderinfo .= ' ' . str_pad($dan, '6', ' ', STR_PAD_RIGHT);
            $orderinfo .= 'X ' . str_pad($di['dish_num'], '3', ' ', STR_PAD_RIGHT);
            $orderinfo .= ' ' . str_pad($di['dish_price'], '5', ' ', STR_PAD_RIGHT);
            $orderinfo .= '\n';
        }
    }
    $orderinfo .= '--------------------------------\n';
    $orderinfo .= "订单号：{$orderid}\n";
    $orderinfo .= "支付方式：{$order['pay_type']}\n";
    if ($order['order_type'] == 2) {
        $orderinfo .= "配送费：{$order['delivery_fee']}\n";
    }
    $orderinfo .= "合计：{$order['price']}元\n";
    if ($order['is_usecard'] == 1) {
        $price = $order['price'] + $order['delivery_fee'] - $order['card_fee'];
        $orderinfo .= "使用优惠券: -{$price}元\n";
        $orderinfo .= "实际支付: {$order['card_fee']}元\n";
    }
    if ($order['order_type'] == 1) {
        $orderinfo .= "下单人：{$order['username']}\n";
        $orderinfo .= "联系电话：{$order['mobile']}\n";
        $orderinfo .= "桌号：{$order['table_info']}　就餐人数：{$order['person_num']}人\n";
    } elseif ($order['order_type'] == 2) {
        $orderinfo .= "下单人：{$order['username']}\n";
        $orderinfo .= "联系电话：{$order['mobile']}\n";
        $orderinfo .= "送餐地址：{$order['address']}\n";
        $orderinfo .= "送餐时间：{$order['delivery_time']}\n";
    } else {
        $orderinfo .= "下单人：{$order['username']}\n";
        $orderinfo .= "联系电话：{$order['mobile']}\n";
    }
    $orderinfo .= "下单时间：" . date('Y-m-d H:i', $order['addtime']) . "\n";
    if (!empty($order['note'])) {
        $orderinfo .= '备注：' . $order['note'] . '\n';
    }
    $i = 0;
       $status = printer($orderinfo,'',$print['type']);
		if ($status) {
            $i++;
            $data = array(
                'uniacid' => $_W['uniacid'],
                'sid' => $order['sid'],
                'pid' => $print_set['id'],
                'oid' => $order['id'],
                'status' => 2,
                'foid' => $status,
                'print_type' => 1,
                'addtime' => TIMESTAMP
            );
            pdo_insert('str_order_print', $data);
        }
    return $i;
}
function feie_print($order, $store, $print_set)
{
    global $_W, $_GPC;
    $orderid   = str_pad($order['id'], 8, '0', STR_PAD_LEFT);
    $pay_types = pay_types();
    if (empty($order['pay_type'])) {
        $order['pay_type'] = '未支付';
    } else {
        $order['pay_type'] = !empty($pay_types[$order['pay_type']]) ? $pay_types[$order['pay_type']] : '其他支付方式';
    }
    if (empty($order['delivery_time'])) {
        $order['delivery_time'] = '尽快送出';
    }
    $order['dish'] = get_dish($order['id']);
    $orderinfo     = '';
    $orderinfo .= "<CB>{$store['title']}</CB>\n";
    if (!empty($print_set['print_header'])) {
        $orderinfo .= $print_set['print_header'] . '<BR>';
    }
    $orderinfo .= '名称　　　　　 单价  数量 金额<BR>';
    $orderinfo .= '--------------------------------<BR>';
    if (!empty($order['dish'])) {
        foreach ($order['dish'] as $di) {
            $dan = ($di['dish_price'] / $di['dish_num']);
            $orderinfo .= str_pad(cutstr($di['dish_title'], 7), '21', '　', STR_PAD_RIGHT);
            $orderinfo .= ' ' . str_pad($dan, '6', ' ', STR_PAD_RIGHT);
            $orderinfo .= 'X ' . str_pad($di['dish_num'], '3', ' ', STR_PAD_RIGHT);
            $orderinfo .= ' ' . str_pad($di['dish_price'], '5', ' ', STR_PAD_RIGHT);
            $orderinfo .= '<BR>';
        }
    }
    $orderinfo .= '--------------------------------<BR>';
    $orderinfo .= "订单号：{$orderid}<BR>";
    $orderinfo .= "支付方式：{$order['pay_type']}<BR>";
    if ($order['order_type'] == 2) {
        $orderinfo .= "配送费：{$order['delivery_fee']}<BR>";
    }
    $orderinfo .= "合计：{$order['price']}元<BR>";
    if ($order['is_usecard'] == 1) {
        $price = $order['price'] + $order['delivery_fee'] - $order['card_fee'];
        $orderinfo .= "使用优惠券: -{$price}元<BR>";
        $orderinfo .= "实际支付: {$order['card_fee']}元<BR>";
    }
    if ($order['order_type'] == 1) {
        $orderinfo .= "下单人：{$order['username']}<BR>";
        $orderinfo .= "联系电话：{$order['mobile']}<BR>";
        $orderinfo .= "桌号：{$order['table_info']}　就餐人数：{$order['person_num']}人<BR>";
    } elseif ($order['order_type'] == 2) {
        $orderinfo .= "下单人：{$order['username']}<BR>";
        $orderinfo .= "联系电话：{$order['mobile']}<BR>";
        $orderinfo .= "送餐地址：{$order['address']}<BR>";
        $orderinfo .= "送餐时间：{$order['delivery_time']}<BR>";
    } else {
        $orderinfo .= "下单人：{$order['username']}<BR>";
        $orderinfo .= "联系电话：{$order['mobile']}<BR>";
    }
    $orderinfo .= "下单时间：" . date('Y-m-d H:i', $order['addtime']) . "<BR>";
    if (!empty($order['note'])) {
        $orderinfo .= '备注：' . $order['note'] . '<BR>';
    }
    if (!empty($print_set['qrcode_link'])) {
        $orderinfo .= "<QR>{$print_set['qrcode_link']}</QR>";
    }
    if (!empty($print_set['print_footer'])) {
        $orderinfo .= $print_set['print_footer'] . '<BR>';
    }
    $i = 0;
    if (!empty($print_set['print_no']) && !empty($print_set['key'])) {
        $wprint = new wprint();
        $status = $wprint->StrPrint($print_set['print_no'], $print_set['key'], $orderinfo, $print_set['print_nums']);
        if (!is_error($status)) {
            $i++;
            $data = array(
                'uniacid' => $_W['uniacid'],
                'sid' => $order['sid'],
                'pid' => $print_set['id'],
                'oid' => $order['id'],
                'status' => 2,
                'foid' => $status,
                'print_type' => 1,
                'addtime' => TIMESTAMP
            );
            pdo_insert('str_order_print', $data);
        }
    }
    return $i;
}
function tpl_format($title, $from, $content, $remark = '')
{
    $send = array(
        'first' => array(
            'value' => $title,
            'color' => '#ff510'
        ),
        'keyword1' => array(
            'value' => $from,
            'color' => '#ff510'
        ),
        'keyword2' => array(
            'value' => $content,
            'color' => '#ff510'
        ),
        'remark' => array(
            'value' => $remark,
            'color' => '#ff510'
        )
    );
    return $send;
}
function init_notice_order($sid, $id, $type = 'order')
{
    global $_W;
    $store = get_store($sid, array(
        'title'
    ));
    $order = get_order($id);
    if (empty($order)) {
        return error(-1, '订单不存在');
    }
    $config    = get_config();
    $orderid   = str_pad($id, 8, '0', STR_PAD_LEFT);
    $pay_types = pay_types();
    if (empty($order['pay_type'])) {
        $order['pay_type'] = '未支付';
    } else {
        $order['pay_type'] = !empty($pay_types[$order['pay_type']]) ? $pay_types[$order['pay_type']] : '其他支付方式';
    }
    $order['dish'] = get_dish($order['id']);
    $clerks        = pdo_fetchall('SELECT * FROM ' . tablename('str_clerk') . ' WHERE uniacid = :aid AND sid = :sid', array(
        ':aid' => $_W['uniacid'],
        ':sid' => $sid
    ), 'id');
    if (!empty($config['notice'])) {
        $acc = WeAccount::create($order['acid']);
        if ($config['notice']['type'] == '2') {
            $orderinfo = '';
            $orderinfo .= "支付方式：{$order['pay_type']}.合计：{$order['price']}元.";
            if ($order['is_usecard'] == 1) {
                $price = $order['price'] - $order['card_fee'];
                $orderinfo .= "使用优惠券优惠{$price}元,实际支付{$order['card_fee']}元.";
            }
            if ($order['order_type'] == 1) {
                $orderinfo .= "下单人：{$order['username']}.";
                $orderinfo .= "联系电话：{$order['mobile']}.";
                $orderinfo .= "桌号：{$order['table_info']}.就餐人数：{$order['person_num']}人";
            } elseif ($order['order_type'] == 2) {
                $orderinfo .= "配送费：{$order['delivery_fee']}.";
                $orderinfo .= "下单人：{$order['username']}.";
                $orderinfo .= "联系电话：{$order['mobile']}.";
                $orderinfo .= "送餐地址：{$order['address']}.";
                $orderinfo .= "送餐时间：{$order['delivery_time']}.";
            } else {
                $orderinfo .= "下单人：{$order['username']}.";
                $orderinfo .= "联系电话：{$order['mobile']}.";
            }
            $orderinfo .= '菜品信息:';
            foreach ($order['dish'] as $di) {
                $orderinfo .= $di['dish_title'] . ' * ' . $di['dish_num'] . '份.';
            }
            $send = tpl_format('新订单通知', $store['title'], $orderinfo);
            if (!empty($clerks)) {
                $url = $_W['siteroot'] . 'app' . ltrim(murl('entry', array(
                    'do' => 'manage',
                    'op' => 'detail',
                    'm' => 'str_takeout',
                    'sid' => $order['sid'],
                    'id' => $order['id']
                )), '.');
                foreach ($clerks as $li) {
                    $status = $acc->sendTplNotice($li['openid'], $config['notice']['public_tpl'], $send, $url);
                }
            }
            if (!empty($order['openid'])) {
                $url    = $_W['siteroot'] . 'app' . ltrim(murl('entry', array(
                    'do' => 'orderdetail',
                    'm' => 'str_takeout',
                    'sid' => $order['sid'],
                    'id' => $order['id']
                )), '.');
                $status = $acc->sendTplNotice($order['openid'], $config['notice']['public_tpl'], $send, $url);
            }
        } else {
            $header    = "【{$store['title']}】新订单通知\n";
            $orderinfo = '';
            $orderinfo .= "订单编号：{$orderid}\n";
            $orderinfo .= '名称　　　　　　数量　单价\n';
            $orderinfo .= '--------------------\n';
            foreach ($order['dish'] as $di) {
                $dan = ($di['dish_price'] / $di['dish_num']);
                $orderinfo .= istr_pad(cutstr($di['dish_title'], 8), 8, '　', STR_PAD_RIGHT);
                $orderinfo .= istr_pad($di['dish_num'], '3', '　', STR_PAD_RIGHT);
                $orderinfo .= istr_pad($dan, '6', ' ', STR_PAD_RIGHT);
                $orderinfo .= '\n';
            }
            $orderinfo .= '--------------------\n';
            $orderinfo .= "支付方式：{$order['pay_type']}\n";
            $orderinfo .= "合计：{$order['price']}元\n";
            if ($order['is_usecard'] == 1) {
                $price = $order['price'] - $order['card_fee'];
                $orderinfo .= "使用优惠券优惠{$price}元,实际支付{$order['card_fee']}元.\n";
            }
            if ($order['order_type'] == 1) {
                $orderinfo .= "下单人：{$order['username']}\n";
                $orderinfo .= "联系电话：{$order['mobile']}\n";
                $orderinfo .= "桌号：{$order['table_info']}　　就餐人数：{$order['person_num']}人\n";
            } elseif ($order['order_type'] == 2) {
                $orderinfo .= "配送费：{$order['delivery_fee']}\n";
                $orderinfo .= "下单人：{$order['username']}\n";
                $orderinfo .= "联系电话：{$order['mobile']}\n";
                $orderinfo .= "送餐地址：{$order['address']}\n";
                $orderinfo .= "送餐时间：{$order['delivery_time']}\n";
            } else {
                $orderinfo .= "下单人：{$order['username']}.";
                $orderinfo .= "联系电话：{$order['mobile']}.";
            }
            $orderinfo .= "下单时间：" . date('Y-m-d H:i', $order['addtime']) . "\n";
            if (!empty($order['note'])) {
                $orderinfo .= '备注：' . $order['note'] . '\n';
            }
            if (!empty($clerks)) {
                $url             = $_W['siteroot'] . 'app' . ltrim(murl('entry', array(
                    'do' => 'manage',
                    'op' => 'detail',
                    'm' => 'str_takeout',
                    'sid' => $order['sid'],
                    'id' => $order['id']
                )), '.');
                $footer          = "\n<a href='{$url}'>点击查看订单详情</a>";
                $send['msgtype'] = 'text';
                $send['text']    = array(
                    'content' => urlencode($header . $orderinfo . $footer)
                );
                foreach ($clerks as $li) {
                    if (!empty($li['openid'])) {
                        $send['touser'] = trim($li['openid']);
                        $status         = $acc->sendCustomNotice($send);
                    }
                }
            }
            if (!empty($order['openid'])) {
                $header          = "【{$store['title']}】下单通知\n";
                $url             = $_W['siteroot'] . 'app' . ltrim(murl('entry', array(
                    'do' => 'orderdetail',
                    'm' => 'str_takeout',
                    'sid' => $order['sid'],
                    'id' => $order['id']
                )), '.');
                $footer          = "\n<a href='{$url}'>点击查看订单详情</a>";
                $send['msgtype'] = 'text';
                $send['text']    = array(
                    'content' => urlencode($header . $orderinfo . $footer)
                );
                $send['touser']  = trim($order['openid']);
                $status          = $acc->sendCustomNotice($send);
            }
        }
    }
    if (!empty($clerks)) {
        $header    = '';
        $orderinfo = '';
        load()->func('communication');
        if ($type == 'order') {
            $header = "<h3>【{$store['title']}】新订单通知</h3> <br />";
            $orderinfo .= "订单编号：{$orderid}<br />";
            $orderinfo .= "订单详情：<br />";
            foreach ($order['dish'] as $row) {
                $orderinfo .= "名称：{$row['dish_title']} ，数量：{$row['dish_num']} 份<br />";
            }
            $orderinfo .= "支付方式：{$order['pay_type']}<br>";
            $orderinfo .= "合计：{$order['price']}元<br>";
            if ($order['order_type'] == 1) {
                $orderinfo .= "下单人：{$order['username']}<br>";
                $orderinfo .= "联系电话：{$order['mobile']}<br>";
                $orderinfo .= "桌号：{$order['table_info']}　　就餐人数：{$order['person_num']}人<br>";
            } elseif ($order['order_type'] == 2) {
                $orderinfo .= "配送费：{$order['delivery_fee']}<br>";
                $orderinfo .= "下单人：{$order['username']}<br>";
                $orderinfo .= "联系电话：{$order['mobile']}<br>";
                $orderinfo .= "送餐地址：{$order['address']}<br>";
                $orderinfo .= "送餐时间：{$order['delivery_time']}<br>";
            }
            $orderinfo .= "下单时间：" . date('Y-m-d H:i', $order['addtime']) . "<br>";
            if (!empty($order['note'])) {
                $orderinfo .= '备注：' . $order['note'] . '<br>';
            }
            foreach ($clerks as $clerk) {
                if (!empty($clerk['email'])) {
                    ihttp_email($clerk['email'], '微外卖订单提醒', $header . $orderinfo);
                }
            }
        }
    }
    return true;
}
function wechat_notice_order($sid, $id, $status)
{
    global $_W;
    load()->model('mc');
    $types_arr = array(
        '2' => 'handel',
        '3' => 'end',
        '4' => 'cancel',
        '5' => 'pay',
		'8' => 'tk',
		'9' =>'tuikuan'
    );
    $type      = $types_arr[$status];
    $store     = get_store($sid, array(
        'title'
    ));
    $order     = get_order($id);
    $orderid   = str_pad($id, 8, '0', STR_PAD_LEFT);
    if (!$order['is_grant'] && $order['grant_credit'] > 0 && $status == 3) {
        $log = array(
            $order['uid'],
            "外卖模块订单赠送{$order['grant_credit']}积分。订单id:{$order['id']}"
        );
        mc_credit_update($order['uid'], 'credit1', $order['grant_credit'], $log);
        pdo_update('str_order', array(
            'is_grant' => 1
        ), array(
            'uniacid' => $_W['uniacid'],
            'id' => $order['id']
        ));
        $is_grant = 1;
    }
    $acc = WeAccount::create($order['acid']);
    if (!empty($order['openid'])) {
        if ($type == 'pay') {
            $pay_types         = pay_types();
            $order['pay_type'] = $pay_types[$order['pay_type']];
            $header            = "【{$store['title']}】付款通知\n";
            $orderinfo         = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "通过【{$order['pay_type']}】付款成功。";
        }
        if ($type == 'handel') {
            $header    = "【{$store['title']}】订单进度通知\n";
            $orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "处理，店家正在配餐中。。。";
        }
        if ($type == 'end') {
            $header    = "【{$store['title']}】订单进度通知\n";
            $orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "处理完成\n";
            if ($is_grant == 1) {
                $orderinfo .= "系统赠送您【{$order['grant_credit']}积分】，感谢您对我们的支持。";
            }
        }
        if ($type == 'cancel') {
            $header    = "【{$store['title']}】订单进度通知\n";
            $orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "取消";
        }
		if ($type == 'tk') {
            $header    = "【{$store['title']}】订单进度通知\n";
            $orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "申请。我们会尽快处理！";
        }
		if ($type == 'tuikuan') {
            $header    = "【{$store['title']}】订单进度通知\n";
            $orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "成功退款。点餐费用已退至您的余额账户！";
        }
        $url = $_W['siteroot'] . 'app' . ltrim(murl('entry', array(
            'do' => 'orderdetail',
            'm' => 'str_takeout',
            'sid' => $order['sid'],
            'id' => $order['id']
        )), '.');
        if ($_W['str_takeout']['notice']['type'] == '1') {
            $footer          = "\n<a href='{$url}'>点击查看订单详情</a>";
            $send['msgtype'] = 'text';
            $send['text']    = array(
                'content' => urlencode($header . $orderinfo . $footer)
            );
            $send['touser']  = trim($order['openid']);
            $status          = $acc->sendCustomNotice($send);
        } elseif ($_W['str_takeout']['notice']['type'] == '2') {
            $header = str_replace("\n", '', $header);
            $send   = tpl_format($header, $store['title'], $orderinfo);
            $status = $acc->sendTplNotice($order['openid'], $_W['str_takeout']['notice']['public_tpl'], $send, $url);
        }
    }
    return true;
}
function istr_pad($input, $pad_length, $pad_string, $pad_type = STR_PAD_RIGHT)
{
    $strlen = istrlen($input);
    if ($strlen < $pad_length) {
        $difference = $pad_length - $strlen;
        switch ($pad_type) {
            case STR_PAD_RIGHT:
                return $input . str_repeat($pad_string, $difference);
                break;
            case STR_PAD_LEFT:
                return str_repeat($pad_string, $difference) . $input;
                break;
            default:
                $left  = $difference / 2;
                $right = $difference - $left;
                return str_repeat($pad_string, $left) . $input . str_repeat($pad_string, $right);
                break;
        }
    } else {
        return $input;
    }
}
function comment_stat($sid)
{
    global $_W;
    $sid    = intval($sid);
    $count  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(
        ':uniacid' => $_W['uniacid'],
        ':sid' => $sid
    ));
    $count  = intval($count);
    $return = array(
        'total' => 0,
        'avg_taste' => 0.00,
        'avg_serve' => 0.00,
        'avg_speed' => 0.00
    );
    if ($count > 0) {
        $sum_taste = pdo_fetchcolumn("SELECT SUM(taste) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $sid
        ));
        $sum_serve = pdo_fetchcolumn("SELECT SUM(serve) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $sid
        ));
        $sum_speed = pdo_fetchcolumn("SELECT SUM(speed) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $sid
        ));
        $return    = array(
            'total' => $count,
            'avg_taste' => round($sum_taste / $count, 2),
            'avg_serve' => round($sum_serve / $count, 2),
            'avg_speed' => round($sum_speed / $count, 2)
        );
    }
    return $return;
}
function get_share($store)
{
    global $_W;
    if (!is_array($store)) {
        $store = get_store($store, array(
            'title',
            'id',
            'content',
            'logo'
        ));
    }
    $_share = array(
        'title' => $store['title'],
        'imgUrl' => tomedia($store['logo']),
        'desc' => $store['content'],
        'link' => $_W['siteroot'] . 'app' . ltrim(murl('entry', array(
            'do' => 'dish',
            'sid' => $store['id'],
            'm' => 'str_takeout'
        )), '.')
    );
    return $_share;
}
function get_area()
{
    global $_W;
    $lists = pdo_fetchall('SELECT * FROM ' . tablename('str_area') . ' WHERE uniacid = :uniacid ORDER BY displayorder DESC,id ASC', array(
        ':uniacid' => $_W['uniacid']
    ), 'id');
    return $lists;
}
function get_tables($sid, $status = 0)
{
    global $_W;
    $params = array(
        ':uniacid' => $_W['uniacid'],
        ':sid' => $sid
    );
    if ($status > 0) {
        $condition .= ' and status = :status';
        $params[':status'] = $status;
    }
    $tables = pdo_fetchall('select * from ' . tablename('str_tables') . ' where uniacid = :uniacid and sid = :sid' . $condition . ' order by displayorder desc', $params);
    return $tables;
}
function get_assign_board($id)
{
    global $_W;
    $board = pdo_get('str_assign_board', array(
        'uniacid' => $_W['uniacid'],
        'id' => $id
    ));
    return $board;
}
function get_assign_queue($id)
{
    global $_W;
    $queue = pdo_get('str_assign_queue', array(
        'uniacid' => $_W['uniacid'],
        'id' => $id
    ));
    return $queue;
}
function wechat_notice_assign($sid, $id, $status)
{
    global $_W;
    $config = get_config();
    $result = error(-1, '通知参数错误');
    if (!empty($config['notice'])) {
        $store = get_store($sid, array(
            'id',
            'title'
        ));
        if (empty($sid)) {
            return false;
        }
        $board = get_assign_board($id);
        if (empty($board)) {
            return false;
        }
        $queue = get_assign_queue($board['queue_id']);
        if (empty($queue)) {
            return false;
        }
        $board_status = get_board_status();
        $url          = murl('entry', array(
            'm' => 'str_takeout',
            'do' => 'dish',
            'sid' => $sid,
            'mode' => 3
        ), true, true);
        $wait_count   = pdo_fetchcolumn('select count(*) from ' . tablename('str_assign_board') . ' where uniacid = :uniacid and sid = :sid and status = 1 and id < :id and queue_id = :queue_id', array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $sid,
            ':queue_id' => $board['queue_id'],
            ':id' => $board['id']
        ));
        $createtime   = date("Y-m-d H:i", $board['createtime']);
        if ($config['notice']['type'] == 1) {
            if ($status == 1) {
                $content = array(
                    "排号提醒：编号{$board['number']}已成功领号，您可以<a href='{$url}'>点击本消息</a>提前点菜，节约等待时间哦",
                    "门店名称：{$store['title']}",
                    "当前排号：{$board['number']}",
                    "取号时间：{$createtime}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "前面等待：{$wait_count}桌",
                    "排队状态：排队中"
                );
            } elseif ($status == 2) {
                $content = array(
                    "排号入号提醒：编号{$board['number']}已入号,请您立即前往迎宾台",
                    "门店名称：{$store['title']}",
                    "当前排号：{$board['number']}",
                    "取号时间：{$createtime}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：已入号",
                    "您在{$store['title']}的的排队状态更新为已入号，请您立即前往迎宾台，如果疑问，请联系我们工作人员"
                );
            } elseif ($status == 3) {
                $content = array(
                    "排号过号提醒：编号{$board['number']}已过号",
                    "门店名称：{$store['title']}",
                    "当前排号：{$board['number']}",
                    "取号时间：{$createtime}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：已过号",
                    "您在{$store['title']}的的排队状态更新为已过号，如果疑问，请联系我们工作人员"
                );
            } elseif ($status == 4) {
                $content = array(
                    "排号取消提醒：编号{$board['number']}已取消",
                    "门店名称：{$store['title']}",
                    "当前排号：{$board['number']}",
                    "取号时间：{$createtime}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：已取消",
                    "您在{$store['title']}的的排队状态更新为已取消，如果疑问，请联系我们工作人员"
                );
            } elseif ($status == 5) {
                $content = array(
                    "排号提醒：还需等待{$wait_count}桌",
                    "门店名称：{$store['title']}",
                    "当前排号：{$board['number']}",
                    "取号时间：{$createtime}",
                    "还需等待：{$wait_count}桌",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：{$board_status[$board['status']]['text']}"
                );
            }
            $content = implode("\n", $content);
            $send    = array(
                'touser' => $board['openid'],
                'msgtype' => 'text',
                'text' => array(
                    'content' => urlencode($content)
                )
            );
            $acc     = WeAccount::create();
            $result  = $acc->sendCustomNotice($send);
        } else {
            if (empty($config['notice']['assign_tpl'])) {
                return false;
            }
            if ($status == 1) {
                $first  = "排号提醒：编号{$board['number']}已成功领号，您可以点击本消息提前点菜，节约等待时间哦";
                $remark = array(
                    "门店名称：{$store['title']}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "前面等待：{$wait_count}桌",
                    "排队状态：排队中"
                );
            } elseif ($status == 2) {
                $first  = "排号入号提醒：编号{$board['number']}已入号,请您立即前往迎宾台";
                $remark = array(
                    "门店名称：{$store['title']}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：已入号",
                    "您在{$store['title']}的的排队状态更新为已入号，请您立即前往迎宾台，如果疑问，请联系我们工作人员"
                );
            } elseif ($status == 3) {
                $first  = "排号过号提醒：编号{$board['number']}已过号";
                $remark = array(
                    "门店名称：{$store['title']}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：已过号",
                    "您在{$store['title']}的的排队状态更新为已过号，如果疑问，请联系我们工作人员"
                );
            } elseif ($status == 4) {
                $first  = "排号取消提醒：编号{$board['number']}已取消";
                $remark = array(
                    "门店名称：{$store['title']}",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：已取消",
                    "您在{$store['title']}的的排队状态更新为已取消，如果疑问，请联系我们工作人员"
                );
            } elseif ($status == 5) {
                $first  = "排号提醒：还需等待{$wait_count}桌";
                $remark = array(
                    "门店名称：{$store['title']}",
                    "还需等待：{$wait_count}桌",
                    "排队号码：{$queue['title']} {$board['number']}",
                    "排队状态：{$board_status[$board['status']]['text']}"
                );
            }
            $remark = implode("\n", $remark);
            $send   = array(
                'first' => array(
                    'value' => $first,
                    'color' => '#ff510'
                ),
                'keyword1' => array(
                    'value' => $board['number'],
                    'color' => '#ff510'
                ),
                'keyword2' => array(
                    'value' => $createtime,
                    'color' => '#ff510'
                ),
                'remark' => array(
                    'value' => $remark,
                    'color' => '#ff510'
                )
            );
            $acc    = WeAccount::create();
            $result = $acc->sendTplNotice($board['openid'], $config['notice']['assign_tpl'], $send, $url);
        }
    }
    return $result;
}
function wechat_notice_clerk_assign($sid, $id)
{
    global $_W;
    $config = get_config();
    if (!empty($config['notice'])) {
        $store = get_store($sid, array(
            'id',
            'title'
        ));
        if (empty($sid)) {
            return false;
        }
        $board = get_assign_board($id);
        if (empty($board)) {
            return false;
        }
        $queue = get_assign_queue($board['queue_id']);
        if (empty($queue)) {
            return false;
        }
        $clerks = get_clerks($sid);
        if (empty($clerks)) {
            return false;
        }
        $board_status = get_board_status();
        $url          = murl('entry', array(
            'm' => 'str_takeout',
            'do' => 'dish',
            'sid' => $sid
        ), true, true);
        $wait_count   = pdo_fetchcolumn('select count(*) from ' . tablename('str_assign_board') . ' where uniacid = :uniacid and sid = :sid and status = 1 and id < :id and queue_id = :queue_id', array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $sid,
            ':queue_id' => $board['queue_id'],
            ':id' => $board['id']
        ));
        $createtime   = date("Y-m-d H:i", $board['createtime']);
        if ($config['notice']['type'] == 1) {
            $content = array(
                "排号提醒：有新的排号，编号{$board['number']}",
                "门店名称：{$store['title']}",
                "当前排号：{$board['number']}",
                "取号时间：{$createtime}",
                "排队号码：{$queue['title']} {$board['number']}",
                "还需等待：{$wait_count}桌"
            );
            $content = implode("\n", $content);
            $send    = array(
                'touser' => '',
                'msgtype' => 'text',
                'text' => array(
                    'content' => urlencode($content)
                )
            );
            $acc     = WeAccount::create();
            foreach ($clerks as $clerk) {
                if (empty($clerk['openid'])) {
                    continue;
                }
                $send['touser'] = $clerk['openid'];
                $status         = $acc->sendCustomNotice($send);
            }
        } else {
            if (empty($config['notice']['assign_tpl'])) {
                return false;
            }
            $first  = "排号提醒：有新的排号，编号{$board['number']}.请登陆后台进行处理";
            $remark = array(
                "门店名称：{$store['title']}",
                "排队号码：{$queue['title']} {$board['number']}",
                "还需等待：{$wait_count}桌"
            );
            $remark = implode("\n", $remark);
            $send   = array(
                'first' => array(
                    'value' => $first,
                    'color' => '#ff510'
                ),
                'keyword1' => array(
                    'value' => $board['number'],
                    'color' => '#ff510'
                ),
                'keyword2' => array(
                    'value' => $createtime,
                    'color' => '#ff510'
                ),
                'remark' => array(
                    'value' => $remark,
                    'color' => '#ff510'
                )
            );
            $acc    = WeAccount::create();
            foreach ($clerks as $clerk) {
                if (empty($clerk['openid'])) {
                    continue;
                }
                $status = $acc->sendTplNotice($clerk['openid'], $config['notice']['assign_tpl'], $send);
            }
        }
    }
    return $status;
}
function wechat_notice_assign_queue($board_id, $queue_id)
{
    global $_W;
    $queue = get_assign_queue($queue_id);
    if (!empty($queue) && $queue['notify_num'] > 0) {
        $boards = pdo_fetchall('select * from ' . tablename('str_assign_board') . ' where uniacid = :uniacid and sid = :sid and queue_id = :queue_id and status = 1 and id > :id limit ' . $queue['notify_num'], array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $queue['sid'],
            ':queue_id' => $queue_id,
            ':id' => $board_id
        ));
        if (!empty($boards)) {
            foreach ($boards as $board) {
                if (!empty($board['openid'])) {
                    wechat_notice_assign($queue['sid'], $board['id'], 5);
                }
            }
        }
    }
}
function is_first_order($sid)
{
    global $_W, $_GPC;
    $data = pdo_get('str_users', array(
        'uniacid' => $_W['uniacid'],
        'sid' => $sid,
        'uid' => $_W['member']['uid']
    ));
    if (empty($data)) {
        return true;
    }
    return false;
}
function setFansCoin($from_user, $credit2, $remark)
    {
        load()->model('mc');
        load()->func('compat.biz');
        $uid = $from_user;
        $fans = mc_fetch($uid, array("credit2"));
        if (!empty($fans)) {
            //$uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            mc_credit_update($uid, 'credit2', $credit2, $log);
        }
    }
