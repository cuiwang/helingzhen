<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;

$storeid = intval($_GPC['storeid']); //门店id
$dishid = intval($_GPC['dishid']); //商品id
$total = intval($_GPC['o2uNum']); //更新数量

if (empty($from_user)) {
    $result['msg'] = '会话已过期，请重新发送关键字!';
    message($result, '', 'ajax');
}

$store = $this->getStoreById($storeid);
if ($store['is_rest'] != 1) {
    $result['msg'] = '商家休息中,暂不接单';
    message($result, '', 'ajax');
}

//查询商品是否存在
$goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE  id=:id", array(":id" => $dishid));
if (empty($goods)) {
    $result['msg'] = '没有相关商品';
    message($result, '', 'ajax');
}
$nowtime = mktime(0, 0, 0);
if ($goods['lasttime'] <= $nowtime) {
    pdo_query("UPDATE " . tablename($this->table_goods) . " SET today_counts=0,lasttime=:time WHERE id=:id", array(':id' => $dishid, ':time' => TIMESTAMP));
}
//查询购物车有没该商品
$cart = pdo_fetch("SELECT * FROM " . tablename($this->table_cart) . " WHERE goodsid=:goodsid AND weid=:weid AND storeid=:storeid AND from_user=:from_user ", array(':goodsid' => $dishid, ':weid' => $weid, ':storeid' => $storeid, ':from_user' => $from_user));
if ($goods['counts'] == 0) {
    $result['msg'] = '该商品已售完!';
    message($result, '', 'ajax');
}
if ($goods['counts'] > 0) {
    $count = $goods['counts'] - $goods['today_counts'];
    if ($count <= 0) {
        $result['msg'] = '该商品已售完!!';
        message($result, '', 'ajax');
    }
    if (!empty($cart)) {
        if ($cart['total'] < $total) {
            if ($total > $count) {
                $result['msg'] = '该商品已没库存!!';
                message($result, '', 'ajax');
            }
        }
    } else {
        if ($total > $count) {
            $result['msg'] = '该商品已没库存!!';
            message($result, '', 'ajax');
        }
    }
}

$iscard = $this->get_sys_card($from_user);
$price = $goods['marketprice'];
if ($iscard == 1 && !empty($goods['memberprice'])) {
    $price = $goods['memberprice'];
}

if (empty($cart)) {
    //不存在的话增加商品点击量
    pdo_query("UPDATE " . tablename($this->table_goods) . " SET subcount=subcount+1 WHERE id=:id", array(':id' => $dishid));
    //添加进购物车
    $data = array(
        'weid' => $weid,
        'storeid' => $goods['storeid'],
        'goodsid' => $goods['id'],
        'goodstype' => $goods['pcate'],
        'price' => $price,
        'packvalue' => $goods['packvalue'],
        'from_user' => $from_user,
        'total' => 1
    );
    pdo_insert($this->table_cart, $data);
} else {
    //更新商品在购物车中的数量
    pdo_query("UPDATE " . tablename($this->table_cart) . " SET total=:total WHERE id=:id", array(':id' => $cart['id'], ':total' => $total));
}

$totalcount = 0;
$totalprice = 0;

$cart = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " WHERE  storeid=:storeid AND from_user=:from_user AND weid=:weid", array(':storeid' => $storeid, ':from_user' => $from_user, ':weid' => $weid));

$cart_html = '<ul>';
foreach ($cart as $key => $value) {
    $goods_t = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id = :id LIMIT 1 ", array(':id' => $value['goodsid']));
    $cart[$key]['goodstitle'] = $goods_t['title'];
    $totalcount = $totalcount + $value['total'];
    $totalprice = $totalprice + $value['total'] * $value['price'];

    if ($value['total'] > 0) {
        $cart_html .= '<li dishid="'.$value['goodsid'].'">';
        $cart_html .= '<div class="cart-item-name">'.$goods_t['title'].'</div>';
        $cart_html .= '<div class="cart-item-price">¥<font>'.$value['price'].'</font></div>';
        $cart_html .= '<div class="cart-item-num">';
        $cart_html .= '<i class="cart-item-add"></i>';
        $cart_html .= '<span>'.$value['total'].'</span>';
        $cart_html .= '<i class="cart-item-jj"></i>';
        $cart_html .= '</div>';
        $cart_html .= '</li>';
    }
}
$cart_html .= '</ul>';
$result['totalprice'] = $totalprice;
$result['totalcount'] = $totalcount;
$result['cart'] = $cart_html;
$result['code'] = 0;
message($result, '', 'ajax');