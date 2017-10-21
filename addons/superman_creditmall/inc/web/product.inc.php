<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebProduct extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '商品管理';
        $eid = intval($_GPC['eid']);
        $act = in_array($_GPC['act'], array('display', 'post', 'delete', 'setattr', 'virtual'))?$_GPC['act']:'display';
        $product_types = superman_product_type();
        if ($act == 'display') {
            //更新排序
            if(checksubmit('submit')) {
                $displayorder = $_GPC['displayorder'];
                if ($displayorder) {
                    foreach ($displayorder as $id=>$val) {
                        pdo_update('superman_creditmall_product', array('displayorder' => $val), array('id' => $id));
                    }
                    message('操作成功！', referer(), 'success');
                }
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;
            $filter['uniacid'] = $_W['uniacid'];
            if (trim($_GPC['title']) != ''){
                $filter['title'] = trim($_GPC['title']);
            }
            if ($_GPC['type'] > 0) {
                $filter['type'] = intval($_GPC['type']);
            }
            $sort = $_GPC['sort'];
            $orderby = isset($_GPC['orderby'])&&$_GPC['orderby']=='ASC'?'ASC':'DESC';
            $order_by = '';
            if (in_array($sort, array('total', 'sales'))) {
                $order_by = ' ORDER BY '.$sort.' '.$orderby;
                $orderby = $orderby=='ASC'?'DESC':'ASC';
            }
            $list = superman_product_fetchall($filter,$order_by,$start,$pagesize);
            $total = superman_product_count($filter);
            $pager = pagination($total, $pindex, $pagesize);
            if ($list) {
                foreach($list as &$p){
                    superman_product_set($p);
                    $p['prices'] = '';      //价格格式化
                    $p['credit_type'] = superman_credit_type($p['credit_type']);
                    if ($p['price'] && $p['credit']) {
                        $p['prices'] = $p['price'].'元+'.$p['credit'].$p['credit_type'];
                    } else if ($p['price']) {
                        $p['prices'] = $p['price'].'元';
                    } else if ($p['credit']) {
                        $p['prices'] = $p['credit'].$p['credit_type'];
                    } else {
                        if ($p['type'] == 2) {
                            $p['prices'] = '0'.$p['credit_type']; //0积分起拍
                        } else {
                            $p['prices'] = '免费';
                        }

                    }
                }
                unset($p);
            }
            //print_r($list);
        } else if ($act == 'post') {
            $minus_total = superman_product_minus_total();
            $credit_type = superman_credit_type();
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            $dispatch = superman_dispatch_fetchall($filter, 0, -1);
            if (!$dispatch) {
                $dispatch = superman_dispatch_init($_W['uniacid']);
            }
            $id = intval($_GPC['id']);
            $item = superman_product_fetch($id);
            if ($item) {
                superman_product_set($item);
                //虚拟物品自动更新库存
                if (superman_is_virtual($item)) {
                    $filter = array(
                        'status' => 0,
                        'product_id' => $item['id']
                    );
                    $item['virtual_total'] = superman_virtual_count($filter);
                    pdo_update('superman_creditmall_product', array('total' => $item['virtual_total']), array('id' => $item['id']));
                }
            } else {
                $item['isvirtual'] = 0;
                $item['isshow'] = 1;
                $item['ishome'] = 1;
                $item['ishot'] = 1;
                $item['minus_total'] = 1;
                $item['order_buy_num'] = 0;
                $item['today_limit'] = 0;
                $item['max_buy_num'] = 0;
                $item['activity_time'] = array(
                    'start' => date('Y-m-d H:i:s'),
                    'end' => date('Y-m-d H:i:s', strtotime('+1 months')),
                );
                $item['share_credit_type'] = 'credit1';
                $item['share_credit'] = 0;
                $item['extend']['auction_rule'] = '';
                if (isset($_GPC['type']) && $_GPC['type']=='coupon') {
                    $item['order_buy_num'] = 1;
                    $item['today_limit'] = 1;
                    $item['max_buy_num'] = 1;
                }
            }
            //获取会员组
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            $groups = superman_mc_groups_fetchall($filter, '', 0, -1);
            $filter = array(
                'uniacid' => $_W['uniacid']
            );
            $orderby = ' ORDER BY displayorder DESC, id ASC';
            $checkout_user = superman_checkout_user_fetchall($filter, $orderby, 0, -1);
            if ($checkout_user) {
                foreach ($checkout_user as &$user) {
                    if (isset($item['extend']['checkout']['user']) && in_array($user['id'], $item['extend']['checkout']['user'])) {
                        $user['selected'] = 1;
                    }
                    $member_info = mc_fetch($user['uid'], array('nickname'));
                    $user['nickname'] = $member_info['nickname'];
                    unset($user, $member_info);
                }
            }
            $checkout_code = superman_checkout_code_fetchall($filter, '', 0, -1);
            if ($checkout_code) {
                foreach ($checkout_code as &$code) {
                    if (isset($item['extend']['checkout']['code']) && in_array($code['id'], $item['extend']['checkout']['code'])) {
                        $code['selected'] = 1;
                    }
                    unset($code);
                }
            }
            if ($_GPC['type'] == 'coupon' || $item['extend']['coupon']['id']) {
                $sql = 'SELECT id,title,status,quantity FROM '.tablename('coupon');
                $sql .= " WHERE uniacid=:uniacid AND acid=:acid ORDER BY id DESC";
                $params = array(
                    ':uniacid' => $_W['uniacid'],
                    ':acid' => $_W['acid'],
                );
                $coupon_list = pdo_fetchall($sql, $params);

                //优惠券商品自动同步库存
                if ($coupon_list) {
                    foreach ($coupon_list as $li) {
                        if ($li['id'] == $item['extend']['coupon']['id'] && $item['total'] != $li['quantity']) {
                            $item['total'] = $li['quantity'];
                        }
                    }
                }
            }
            //print_r($coupon_list);
            //print_r($item);
            if (checksubmit('submit')) {
                $extend = $_GPC['extend'];
                $extend['checkout'] = $_GPC['checkout'];
                $type = $item['type']?$item['type']:intval($_GPC['type']);
                $order_buy_num = intval($_GPC['order_buy_num']);
                $today_limit = intval($_GPC['today_limit']);
                $max_buy_num = intval($_GPC['max_buy_num']);
                if ($type == 2) { //竞拍
                    $start_time = strtotime($_GPC['activity_time2']['start']);
                    $end_time = strtotime($_GPC['activity_time2']['end']);
                    $credit = intval($_GPC['credit2']);
                } else if (superman_is_redpack($type)) { //红包
                    if ($_GPC['isvirtual']) {
                        message('微信红包不可选虚拟商品类型', referer(), 'error');
                    }
                    $start_time = !isset($_GPC['activity_time_limit'])?strtotime($_GPC['activity_time']['start']):0;
                    $end_time = !isset($_GPC['activity_time_limit'])?strtotime($_GPC['activity_time']['end']):0;
                    $credit = intval($_GPC['credit']);
                    unset($extend['auction_credit']);
                    unset($extend['auction_rule']);
                } else if ($type == 8) { //微信卡券
                    $extend['coupon']['id'] = $_GPC['couponid'];
                    $start_time = !isset($_GPC['activity_time_limit'])?strtotime($_GPC['activity_time']['start']):0;
                    $end_time = !isset($_GPC['activity_time_limit'])?strtotime($_GPC['activity_time']['end']):0;
                    $credit = intval($_GPC['credit']);
                    $order_buy_num = 1;
                    $today_limit = 1;
                    $max_buy_num = 1;
                    unset($extend['auction_credit']);
                    unset($extend['auction_rule']);
                    unset($extend['redpack_amount']);
                } else {
                    $start_time = !isset($_GPC['activity_time_limit'])?strtotime($_GPC['activity_time']['start']):0;
                    $end_time = !isset($_GPC['activity_time_limit'])?strtotime($_GPC['activity_time']['end']):0;
                    $credit = intval($_GPC['credit']);
                    unset($extend['auction_credit']);
                    unset($extend['auction_rule']);
                    unset($extend['redpack_amount']);
                }
                if ($type != 2 && $credit <= 0) {
                    message('兑换积分不能设置为0，请重新填写！', '', 'success');
                }
                $cover = superman_fix_path($_GPC['cover']);
                if ($cover) {
                    //生成封面缩略图，分享页面时调用
                    $attachment_path = superman_attachment_root();
                    $img_path = superman_cover_share_filename($cover);
                    if (!file_exists($attachment_path.$img_path)) {
                        file_image_thumb($attachment_path.$cover, $attachment_path.$img_path, 200);
                    }
                }

                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'title' => $_GPC['title'],
                    'market_price' => $_GPC['market_price'],
                    'price' => $_GPC['price'],
                    'credit_type' => $_GPC['credit_type'],
                    'credit' => $credit,
                    'total' => intval($_GPC['total']),
                    'sales' => intval($_GPC['sales']),
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'cover' => $cover,
                    'view_count' => intval($_GPC['view_count']),
                    'share_count' => intval($_GPC['share_count']),
                    'comment_count' => intval($_GPC['comment_count']),
                    'description' => $_GPC['description'],
                    'joined_total' => $_GPC['joined_total'],
                    'displayorder' => $_GPC['displayorder'],
                    'dateline' => TIMESTAMP,
                    'minus_total' => $_GPC['minus_total'],
                    'isshow' => $_GPC['isshow'],
                    'ishome' => $_GPC['ishome'],
                    'ishot' => $_GPC['ishot'],
                    'isvirtual' => $_GPC['isvirtual'] == 'on'?1:0,
                    'extend' => isset($extend) && $extend?iserializer($extend):'',
                    'order_buy_num' => $order_buy_num,
                    'today_limit' => $today_limit,
                    'max_buy_num' => $max_buy_num,
                    'dispatch_id' => iserializer($_GPC['dispatch_id']),
                    'share_credit_type' => $_GPC['share_credit_type'],
                    'share_credit' => $_GPC['share_credit'],
                    'groupid' => $_GPC['groupid'],
                    'province' => isset($_GPC['exchange_area'])?$_GPC['exchange_area']['province']:'',
                    'city' => isset($_GPC['exchange_area'])?$_GPC['exchange_area']['city']:'',
                    'district' => isset($_GPC['exchange_area'])?$_GPC['exchange_area']['district']:'',
                );
                //print_r($data);die;
                $album = $_GPC['album'];
                if (is_array($album) && !empty($album)){
                    $data['album'] = serialize($album);
                }
                if (empty($id)){
                    $data['type'] = $type;
                    if (superman_is_virtual($data)) {
                        unset($data['total']); //虚拟商品自动处理库存
                    }
                    if (superman_is_redpack($type) && $cover == '') {
                        $data['cover'] = './addons/superman_creditmall/template/mobile/images/redpack_bg.jpg';
                    }
                    pdo_insert('superman_creditmall_product', $data);
                    $new_id = pdo_insertid();

                    //如果是虚拟物品
                    if ($new_id && superman_is_virtual($data) && $_GPC['virtual_keys']) {
                        $virtual_keys =  trim($_GPC['virtual_keys']);
                        if (empty($virtual_keys)) {
                            message('无添加数据', referer());
                        }
                        $virtual_keys = explode("\n", $virtual_keys);
                        $_data = array(
                            'uniacid' => $_W['uniacid'],
                            'product_id' => $new_id,
                            'dateline' => TIMESTAMP,
                        );
                        //虚拟数据入库
                        foreach ($virtual_keys as $key => $v) {
                            if ($v == '') {
                                unset($virtual_keys['key']);
                                continue;
                            }
                            $_data['key'] = $v;
                            pdo_insert('superman_creditmall_virtual_stuff', $_data);
                        }
                        unset($_data);
                        //更新库存
                        pdo_update('superman_creditmall_product', array('total' => count($virtual_keys)), array('id' => $new_id));
                    }
                } else {
                    //修复红包默认封面图片
                    if (superman_is_redpack($item['type'])) {
                        if (stripos($data['cover'], 'addons/') !== false && substr($data['cover'], 0, 2) != './') {
                            $data['cover'] = './'.$data['cover'];
                        }
                        if ($data['cover'] == '') {
                            $data['cover'] = './addons/superman_creditmall/template/mobile/images/redpack_bg.jpg';
                        }
                    }
                    unset($data['type']);   //商品类型不能修改
                    unset($data['dateline']);
                    unset($data['isvirtual']);//虚拟商品类型不可修改
                    if (superman_is_virtual($item)) {
                        unset($data['total']);
                    }
                    pdo_update('superman_creditmall_product', $data, array('id' => $id));
                }
                message('操作成功！', $this->createWebUrl('product'), 'success');
            }
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            $item = superman_product_fetch($id);
            if (empty($item)) {
                message('商品不存在或是已被删除！', referer(), 'error');
            }
            if (!empty($item['album'])) {
                $arr = unserialize($item['album']);
                if ($arr) {
                    foreach ($arr as $v) {
                        file_delete($v);
                    }
                }
            }
            if ($item['cover']) {
                file_delete($item['cover']);
            }
            pdo_delete('superman_creditmall_product', array('id' => $id));
            message('操作成功！', referer(), 'success');
        } else if ($act == 'setattr') {
            $id = intval($_GPC['id']);
            if (!$id) {
                echo '非法请求！';
                exit;
            }
            $field = $_GPC['field'];
            $value = $_GPC['value'];
            if (!in_array($field, array('isshow'))) {
                echo '非法请求！';
                exit;
            }
            $data = array(
                $field => $value==1?0:1
            );
            $condition = array(
                'id' => $id,
            );
            pdo_update('superman_creditmall_product', $data, $condition);
            echo 'success';
            exit;
        } else if ($act == 'virtual') {
            $product_id = intval($_GPC['product_id']);
            if ($product_id > 0) {
                $product = superman_product_fetch($product_id);
                if (empty($product)) {
                    message('商品不存在或已删除', $this->createWebUrl('product'), 'error');
                }
            }

            if ($_GPC['delete'] == 1 && $_GPC['id'] != '') {
                pdo_delete('superman_creditmall_virtual_stuff', array('id'=> $_GPC['id']));
                message('删除成功', referer(), 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;

            $filter = array(
                'product_id' => $product_id
            );
            $list = superman_virtual_fetchall($filter, '', $start, $pagesize);
            if ($list) {
                foreach ($list as &$item) {
                    superman_virtual_set($item);
                }
                unset($item);
            }

            $total = superman_virtual_count($filter);
            $pager = pagination($total, $pindex, $pagesize);
            if (checksubmit('submit')) {
                $virtual_keys =  trim($_GPC['virtual_keys']);
                if (empty($virtual_keys)) {
                    message('无添加数据', referer());
                }
                $virtual_keys = explode("\n", $virtual_keys);
                $_data = array(
                    'uniacid' => $_W['uniacid'],
                    'product_id' => $product_id,
                    'dateline' => TIMESTAMP,
                );

                foreach ($virtual_keys as $key => $item) {
                    if ($item == '') {
                        unset($virtual_keys['key']);
                        continue;
                    }
                    $_data['key'] = $item;
                    pdo_insert('superman_creditmall_virtual_stuff', $_data);
                }
                $filter = array(
                    'product_id' => $product_id,
                    'status' => 0
                );
                $count = superman_virtual_count($filter);
                pdo_update('superman_creditmall_product', array('total' => $count), array('id' => $product_id));
                message('添加成功', referer(), 'success');
            }

        }
        include $this->template('web/product');
    }
}

$obj = new Creditmall_doWebProduct;
$obj->exec();
