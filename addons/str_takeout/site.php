<?php

defined('IN_IA') or exit('Access Denied');
include('model.php');
include 'wprint.class.php';
class Str_takeoutModuleSite extends WeModuleSite
{
    public function __construct()
    {
        global $_W, $_GPC;
        $config            = get_config();
        $_W['str_takeout'] = $config;
    }
    public function doWebConfig()
    {
        global $_W, $_GPC;
        $config = get_config();
        if (empty($config)) {
            $config = array(
                'version' => 1,
                'notice' => array(
                    'type' => 1
                )
            );
        }
        if (checksubmit('submit')) {
            $data = array(
                'uniacid' => $_W['uniacid'],
                'version' => intval($_GPC['version']),
                'default_sid' => intval($_GPC['default_sid']),
                'sms' => iserializer($_GPC['sms']),
                'notice' => iserializer($_GPC['notice']),
                'area_search' => intval($_GPC['area_search'])
            );
            if (!empty($config['id'])) {
                pdo_update('str_config', $data, array(
                    'uniacid' => $_W['uniacid']
                ));
            } else {
                pdo_insert('str_config', $data);
            }
            message('设置参数成功', referer(), 'success');
        }
        $stores = pdo_getall('str_store', array(
            'uniacid' => $_W['uniacid']
        ));
        include $this->template('config');
    }
    public function doWebStore()
    {
        global $_W, $_GPC;
        $op     = empty($_GPC['op']) ? 'list' : trim($_GPC['op']);
        $config = get_config();
        if ($config['num_limit'] > 0) {
            $now_store_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_store') . ' WHERE uniacid = :uniacid', array(
                ':uniacid' => $_W['uniacid']
            ));
        }
        if ($op == 'list') {
            $condition      = ' uniacid = :aid';
            $params[':aid'] = $_W['uniacid'];
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }
            $area_id = intval($_GPC['area_id']);
            if ($area_id > 0) {
                $condition .= " AND area_id = :area_id";
                $params[':area_id'] = $area_id;
            }
            if ($_W['role'] != 'manager' && empty($_W['isfounder'])) {
                $condition .= " AND id in (select sid from " . tablename('str_account') . " where uniacid = :uniacid and uid = :uid)";
                $params[':uniacid'] = $_W['uniacid'];
                $params[':uid']     = $_W['uid'];
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_store') . ' WHERE ' . $condition, $params);
            $lists  = pdo_fetchall('SELECT * FROM ' . tablename('str_store') . ' WHERE ' . $condition . ' ORDER BY displayorder DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            $pager  = pagination($total, $pindex, $psize);
            if (!empty($lists)) {
                foreach ($lists as &$li) {
                    $li['address']      = str_replace('+', ' ', $li['district']) . ' ' . $li['address'];
                    $li['sys_url']      = murl('entry', array(
                        'm' => 'str_takeout',
                        'do' => 'dish',
                        'sid' => $li['id']
                    ), true, true);
                    $li['store_qrcode'] = (array) iunserializer($li['store_qrcode']);
                    $li['wx_url']       = $li['store_qrcode']['url'];
                }
            }
            $area = get_area();
        }
        if ($op == 'post') {
            load()->func('tpl');
            $id   = intval($_GPC['id']);
            $area = get_area();
            if ($id) {
                isetcookie('__sid', $id, 86400 * 7);
                checkstore();
                $item = pdo_fetch('SELECT * FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
                    ':aid' => $_W['uniacid'],
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('门店信息不存在或已删除', 'referer', 'error');
                } else {
                    $item['copyright'] = iunserializer($item['copyright']);
                    $item['thumbs']    = iunserializer($item['thumbs']);
                    $district_tmp      = explode('+', $item['district']);
                    if (is_array($district_tmp)) {
                        $item['reside'] = array(
                            'province' => $district_tmp[0],
                            'city' => $district_tmp[1],
                            'district' => $district_tmp[2]
                        );
                    }
                    $item['map']            = array(
                        'lat' => $item['location_x'],
                        'lng' => $item['location_y']
                    );
                    $item['business_hours'] = iunserializer($item['business_hours']);
                    $item['sns']            = (array) iunserializer($item['sns']);
                    $item['mobile_verify']  = (array) iunserializer($item['mobile_verify']);
                }
            } else {
                if ($config['num_limit'] > 0 && ($config['num_limit'] - $now_store_num <= 0)) {
                    message("您的公众号只能添加{$config['num_limit']}个门店，不能再添加门店，请联系管理员", referer(), 'error');
                }
                $item['comment_set']    = 1;
                $item['comment_status'] = 1;
                $item['is_meal']        = 1;
                $item['dish_style']     = 1;
                $item['is_takeout']     = 1;
                $item['is_assign']      = 2;
                $item['is_reserve']     = 2;
                $item['dish_style']     = 1;
                $item['business_hours'] = array(
                    array(
                        's' => '8:00',
                        'e' => '24:00'
                    )
                );
                $item['area_id']        = intval($_GPC['aid']);
                $item['sns']            = array();
                $item['mobile_verify']  = array();
            }
            if (checksubmit('submit')) {
                $data = array(
                    'title' => trim($_GPC['title']),
                    'logo' => trim($_GPC['logo']),
                    'telephone' => trim($_GPC['telephone']),
                    'description' => htmlspecialchars_decode($_GPC['description']),
                    'send_price' => intval($_GPC['send_price']),
                    'delivery_price' => intval($_GPC['delivery_price']),
                    'delivery_time' => intval($_GPC['delivery_time']),
                    'serve_radius' => intval($_GPC['serve_radius']),
                    'delivery_area' => trim($_GPC['delivery_area']),
                    'district' => $_GPC['reside']['province'] . '+' . $_GPC['reside']['city'] . '+' . $_GPC['reside']['district'],
                    'address' => trim($_GPC['address']),
                    'location_x' => $_GPC['map']['lat'],
                    'location_y' => $_GPC['map']['lng'],
                    'displayorder' => intval($_GPC['displayorder']),
                    'status' => intval($_GPC['status']),
                    'dish_style' => intval($_GPC['dish_style']),
					'is_sms' => trim($_GPC['is_sms']),
					'sms_id' => trim($_GPC['sms_id']),
					'mobile' => trim($_GPC['mobile']),
					'email' => trim($_GPC['email']),
					'code' => trim($_GPC['code']),
					'secret' => trim($_GPC['secret']),
                    'is_meal' => intval($_GPC['is_meal']),
                    'is_takeout' => intval($_GPC['is_takeout']),
                    'comment_set' => intval($_GPC['comment_set']),
                    'comment_status' => intval($_GPC['comment_status']),
                    'slide_status' => intval($_GPC['slide_status']),
                    'print_type' => intval($_GPC['print_type']),
                    'notice' => trim($_GPC['notice']),
                    'content' => trim($_GPC['content']),
                    'area_id' => intval($_GPC['area_id']),
                    'copyright' => iserializer(array(
                        'name' => trim($_GPC['copyright']['name']),
                        'url' => trim($_GPC['copyright']['url'])
                    )),
                    'is_assign' => intval($_GPC['is_assign']),
                    'is_reserve' => intval($_GPC['is_reserve']),
                    'sns' => iserializer(array(
                        'qq' => trim($_GPC['sns']['qq']),
                        'weixin' => trim($_GPC['sns']['weixin'])
                    )),
                    'mobile_verify' => iserializer(array(
                        'first_verify' => intval($_GPC['mobile_verify']['first_verify']),
                        'takeout_verify' => intval($_GPC['mobile_verify']['takeout_verify'])
                    )),
                    'forward_mode' => intval($_GPC['forward_mode'])
                );
                if (!empty($_GPC['business_start_hours'])) {
                    $hour = array();
                    foreach ($_GPC['business_start_hours'] as $k => $v) {
                        $v = str_replace('：', ':', trim($v));
                        if (!strexists($v, ':')) {
                            $v .= ':00';
                        }
                        $end = str_replace('：', ':', trim($_GPC['business_end_hours'][$k]));
                        if (!strexists($end, ':')) {
                            $end .= ':00';
                        }
                        $hour[] = array(
                            's' => $v,
                            'e' => $end
                        );
                    }
                }
                $data['business_hours'] = iserializer($hour);
                if (!empty($_GPC['thumbs']['image'])) {
                    $thumbs = array();
                    foreach ($_GPC['thumbs']['image'] as $key => $image) {
                        if (empty($image)) {
                            continue;
                        }
                        $thumbs[] = array(
                            'image' => $image,
                            'url' => trim($_GPC['thumbs']['url'][$key])
                        );
                    }
                    $data['thumbs'] = iserializer($thumbs);
                }
                if ($id) {
                    pdo_update('str_store', $data, array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
                } else {
                    $data['uniacid'] = $_W['uniacid'];
                    pdo_insert('str_store', $data);
                }
                message('编辑门店信息成功', $this->createWebUrl('store', array(
                    'op' => 'list'
                )), 'success');
            }
        }
        if ($op == 'del') {
            $id = intval($_GPC['id']);
            pdo_delete('str_dish_category', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $id
            ));
            pdo_delete('str_dish', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $id
            ));
            pdo_delete('str_order', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $id
            ));
            pdo_delete('str_order_print', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $id
            ));
            pdo_delete('str_print', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $id
            ));
            pdo_delete('str_clerk', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $id
            ));
            pdo_delete('str_store', array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            message('删除门店成功', $this->createWebUrl('store', array(
                'op' => 'list'
            )), 'success');
        }
        include $this->template('store');
    }
    public function doWebAjax()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']);
        if ($op == 'status_store') {
            $id    = intval($_GPC['id']);
            $value = intval($_GPC['value']);
            $state = pdo_update('str_store', array(
                'status' => $value
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            if ($state !== false) {
                exit('success');
            }
            exit('error');
        }
        if ($op == 'status_dish') {
            $id    = intval($_GPC['id']);
            $value = intval($_GPC['value']);
            $state = pdo_update('str_dish', array(
                'is_display' => $value
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            if ($state !== false) {
                exit('success');
            }
            exit('error');
        }
        if ($op == 'recommend_dish') {
            $id    = intval($_GPC['id']);
            $value = intval($_GPC['value']);
            $state = pdo_update('str_dish', array(
                'recommend' => $value
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            if ($state !== false) {
                exit('success');
            }
            exit('error');
        }
    }
    public function doWebSwitch()
    {
        global $_W, $_GPC;
        $sid = intval($_GPC['sid']);
        isetcookie('__sid', $sid, 86400 * 7);
        header('location: ' . $this->createWebUrl('manage'));
        exit();
    }
    public function doWebManage()
    {
        global $_W, $_GPC;
        $op  = trim($_GPC['op']) ? trim($_GPC['op']) : 'cate_list';
        $sid = intval($_GPC['__sid']);
        checkstore();
        $store = pdo_fetch('SELECT id, title FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $sid
        ));
        $title = $store['title'];
        if (empty($store)) {
            message('门店信息不存在或已删除', $this->createWebUrl('store'), 'error');
        }
        $pay_types = array(
            'alipay' => '支付宝支付',
            'wechat' => '微信支付',
            'credit' => '余额支付',
            'delivery' => '餐到付款'
        );
        load()->model('mc');
        $groups = mc_groups();
        if ($op == 'stat_detail') {
            load()->func('tpl');
            $condition      = " WHERE uniacid = :aid AND sid = :sid AND pay_type != ''";
            $params[':aid'] = $_W['uniacid'];
            $params[':sid'] = $sid;
            $is_print       = intval($_GPC['is_print']);
            if (!$is_print) {
                if (!empty($_GPC['addtime'])) {
                    $starttime = strtotime($_GPC['addtime']['start']);
                    $endtime   = strtotime($_GPC['addtime']['end']);
                } else {
                    $starttime = strtotime(date('Y-m'));
                    $endtime   = TIMESTAMP;
                }
            } else {
                $starttime = intval($_GPC['starttime']);
                $endtime   = intval($_GPC['endtime']);
                $title     = date('Y-m-d H:i', $starttime) . ' ~~ ' . date('Y-m-d H:i', $endtime) . ' 订单统计';
            }
            $condition .= " AND status != 4 AND addtime > :start AND addtime < :end";
            $params[':start'] = $starttime;
            $params[':end']   = $endtime;
            $count            = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order') . $condition, $params);
            $total_price      = pdo_fetchcolumn('SELECT SUM(card_fee+delivery_fee) FROM ' . tablename('str_order') . $condition, $params);
            $data             = pdo_fetchall('SELECT * FROM ' . tablename('str_order') . $condition . ' ORDER BY addtime ', $params);
            $total            = array();
            if (!empty($data)) {
                foreach ($data as &$da) {
                    if ($da['pay_type'] == 'cash') {
                        $total_price = $da['card_fee'] + $da['delivery_fee'];
                    } else {
                        $total_price = $da['card_fee'];
                    }
                    $key = date('Y-m-d', $da['addtime']);
                    $return[$key]['price'] += $total_price;
                    $return[$key]['count'] += 1;
                    $total['total_price'] += $total_price;
                    $total['total_count'] += 1;
                    if ($da['pay_type'] == 'alipay') {
                        $return[$key]['alipay'] += $total_price;
                        $total['total_alipay'] += $total_price;
                    } elseif ($da['pay_type'] == 'wechat') {
                        $return[$key]['wechat'] += $total_price;
                        $total['total_wechat'] += $total_price;
                    } elseif ($da['pay_type'] == 'credit') {
                        $return[$key]['credit'] += $total_price;
                        $total['total_credit'] += $total_price;
                    } elseif ($da['pay_type'] == 'delivery') {
                        $return[$key]['delivery'] += $total_price;
                        $total['total_delivery'] += $total_price;
                    } else {
                        $return[$key]['cash'] += $total_price;
                        $total['total_cash'] += $total_price;
                    }
                }
            }
            include $this->template('stat_detail');
        }
        if ($op == 'stat_day') {
            $orderby = trim($_GPC['orderby']) ? trim($_GPC['orderby']) : 'num';
            if ($orderby == 'num') {
                $order_by = ' ORDER BY num DESC';
            } else {
                $order_by = ' ORDER BY price DESC';
            }
            $day = trim($_GPC['day']);
            if (empty($day)) {
                $start = intval($_GPC['start']);
                $end   = intval($_GPC['end']);
            } else {
                $start = strtotime($day);
                $end   = strtotime($day) + 86399;
            }
            $data   = array();
            $orders = pdo_fetchall('SELECT * FROM ' . tablename('str_order') . " WHERE uniacid = :aid AND sid = :sid AND addtime >= :start AND addtime < :end  AND status != 4 AND pay_type != '' ORDER BY id ASC", array(
                ':sid' => $sid,
                ':aid' => $_W['uniacid'],
                ':start' => $start,
                ':end' => $end
            ), 'id');
            $count  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order') . " WHERE uniacid = :aid AND sid = :sid AND addtime >= :start AND addtime < :end  AND status != 4 AND pay_type != '' ORDER BY id ASC", array(
                ':sid' => $sid,
                ':aid' => $_W['uniacid'],
                ':start' => $start,
                ':end' => $end
            ));
            if (!empty($orders)) {
                $str   = implode(',', array_keys($orders));
                $data  = pdo_fetchall('SELECT *,SUM(dish_num) AS num, SUM(dish_price) AS price FROM ' . tablename('str_stat') . " WHERE uniacid = :aid AND sid = :sid AND oid IN ({$str}) GROUP BY dish_id" . $order_by, array(
                    ':aid' => $_W['uniacid'],
                    ':sid' => $sid
                ), 'dish_id');
                $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_stat') . " WHERE uniacid = :aid AND sid = :sid AND oid IN ({$str})", array(
                    ':aid' => $_W['uniacid'],
                    ':sid' => $sid
                ));
                $price = pdo_fetchcolumn('SELECT SUM(card_fee) FROM ' . tablename('str_order') . " WHERE uniacid = :aid AND sid = :sid AND status != 4 AND id IN ({$str})", array(
                    ':aid' => $_W['uniacid'],
                    ':sid' => $sid
                ));
            }
            if (!empty($orders)) {
                foreach ($orders as &$da) {
                    if ($da['pay_type'] == 'cash') {
                        $total_price = $da['card_fee'] + $da['delivery_fee'];
                    } else {
                        $total_price = $da['card_fee'];
                    }
                    if ($da['pay_type'] == 'alipay') {
                        $return['alipay']['price'] += $total_price;
                        $return['alipay']['num'] += 1;
                    } elseif ($da['pay_type'] == 'wechat') {
                        $return['wechat']['price'] += $total_price;
                        $return['wechat']['num'] += 1;
                    } elseif ($da['pay_type'] == 'credit') {
                        $return['credit']['price'] += $total_price;
                        $return['credit']['num'] += 1;
                    } elseif ($da['pay_type'] == 'delivery') {
                        $return['delivery']['price'] += $total_price;
                        $return['delivery']['num'] += 1;
                    } else {
                        $return['cash']['price'] += $total_price;
                        $return['cash']['num'] += 1;
                    }
                }
            }
            include $this->template('stat_detail');
        }
        if ($op == 'table_post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if ($id > 0) {
                $item = pdo_get('str_tables', array(
                    'uniacid' => $_W['uniacid'],
                    'sid' => $sid,
                    'id' => $id
                ));
                if (empty($item)) {
                    message('桌号不存在或已经删除', referer(), 'error');
                }
            }
            if (checksubmit()) {
                $num      = intval($_GPC['num']);
                $is_exist = pdo_fetchcolumn('select num from ' . tablename('str_tables') . ' where uniacid = :uniacid and num = :num and id != :id and sid = :sid', array(
                    ':uniacid' => $_W['uniacid'],
                    ':num' => $num,
                    ':sid' => $sid,
                    ':id' => $id
                ));
                if (!empty($is_exist)) {
                    message("{$num}号桌已存在,不能重复添加", '', 'error');
                }
                $wx_qrcode = intval($_GPC['wx_qrcode']);
                if (empty($item)) {
                    if (!$wx_qrcode) {
                        $data = array(
                            'uniacid' => $_W['uniacid'],
                            'sid' => $sid,
                            'num' => $num,
                            'url' => $this->createMobileUrl('dish', array(
                                'sid' => $sid,
                                'z' => $num
                            )),
                            'createtime' => TIMESTAMP
                        );
                        pdo_insert('str_tables', $data);
                        message('添加桌号成功', $this->createwebUrl('manage', array(
                            'op' => 'table_list'
                        )), 'success');
                    }
                    $acc                                          = WeAccount::create($_W['acid']);
                    $barcode                                      = array(
                        'expire_seconds' => '',
                        'action_name' => '',
                        'action_info' => array(
                            'scene' => array()
                        )
                    );
                    $barcode['action_info']['scene']['scene_str'] = "str_takeout-{$sid}-{$num}";
                    $barcode['action_name']                       = 'QR_LIMIT_STR_SCENE';
                    $result                                       = $acc->barCodeCreateFixed($barcode);
                    if (is_error($result)) {
                        message("生成微信二维码出错,错误详情:{$result['message']}", referer(), 'error');
                    }
                    $qrcode = array(
                        'uniacid' => $_W['uniacid'],
                        'acid' => $_W['acid'],
                        'qrcid' => '',
                        'scene_str' => $barcode['action_info']['scene']['scene_str'],
                        'keyword' => "{$sid}-{$num}号餐桌",
                        'name' => "{$store['title']}-{$sid}-({$num})号餐桌触发规则",
                        'model' => 1,
                        'ticket' => $result['ticket'],
                        'url' => $result['url'],
                        'expire' => $result['expire_seconds'],
                        'createtime' => TIMESTAMP,
                        'status' => '1',
                        'type' => 'str_takeout'
                    );
                    pdo_insert('qrcode', $qrcode);
                    $rule = array(
                        'uniacid' => $_W['uniacid'],
                        'name' => "{$store['title']}-{$sid}-({$num})号餐桌触发规则",
                        'module' => 'str_takeout',
                        'status' => 1
                    );
                    pdo_insert('rule', $rule);
                    $rid     = pdo_insertid();
                    $keyword = array(
                        'uniacid' => $_W['uniacid'],
                        'module' => 'str_takeout',
                        'content' => "{$sid}-{$num}号餐桌",
                        'status' => 1,
                        'type' => 1,
                        'displayorder' => 1,
                        'rid' => $rid
                    );
                    pdo_insert('rule_keyword', $keyword);
                    $kid  = pdo_insertid();
                    $data = array(
                        'uniacid' => $_W['uniacid'],
                        'sid' => $sid,
                        'num' => $num,
                        'ticket' => $result['ticket'],
                        'wx_url' => $result['url'],
                        'url' => $this->createMobileUrl('dish', array(
                            'sid' => $sid,
                            'z' => $num
                        )),
                        'thumb' => trim($_GPC['thumb']),
                        'content' => trim($_GPC['content']),
                        'rid' => $rid,
                        'createtime' => TIMESTAMP
                    );
                    pdo_insert('str_tables', $data);
                } else {
                    if ($wx_qrcode == 1) {
                        $data = array(
                            'thumb' => trim($_GPC['thumb']),
                            'content' => trim($_GPC['content'])
                        );
                        pdo_update('str_tables', $data, array(
                            'uniacid' => $_W['uniacid'],
                            'sid' => $sid,
                            'id' => $id
                        ));
                    }
                }
                message('编辑桌号成功', $this->createwebUrl('manage', array(
                    'op' => 'table_list'
                )), 'success');
            }
            include $this->template('table');
        }
        if ($op == 'table_list') {
            $data = pdo_getall('str_tables', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid
            ));
            include $this->template('table');
        }
        if ($op == 'table_download') {
            $id   = intval($_GPC['id']);
            $item = pdo_get('str_tables', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'id' => $id
            ));
            if (empty($item)) {
                message('桌号不存在或已经删除', referer(), 'error');
            }
            if (empty($item['wx_url'])) {
                message('该桌号未生成微信二维码', referer(), 'error');
            }
            $name = $item['num'] . '号桌.png';
            require_once('../framework/library/qrcode/phpqrcode.php');
            $errorCorrectionLevel = "L";
            $matrixPointSize      = "8";
            QRcode::png($item['wx_url'], $name, $errorCorrectionLevel, $matrixPointSize);
            header('Content-type: image/jpeg');
            header("Content-Disposition: attachment; filename={$name}");
            @readfile($name);
            unlink($name);
            exit();
        }
        if ($op == 'table_del') {
            $id   = intval($_GPC['id']);
            $item = pdo_get('str_tables', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'id' => $id
            ));
            if (empty($item)) {
                message('桌号不存在或已经删除', referer(), 'error');
            }
            if ($item['rid'] > 0) {
                pdo_delete('rule', array(
                    'uniacid' => $_W['uniacid'],
                    'id' => $item['rid']
                ));
                pdo_delete('rule_keyword', array(
                    'uniacid' => $_W['uniacid'],
                    'rid' => $item['rid']
                ));
            }
            pdo_delete('str_tables', array(
                'uniacid' => $_W['uniacid'],
                'id' => $item['id']
            ));
            message('删除桌号成功', referer(), 'success');
        }
        if ($op == 'cate_list') {
            $condition      = ' uniacid = :aid AND sid = :sid';
            $params[':aid'] = $_W['uniacid'];
            $params[':sid'] = $sid;
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_dish_category') . ' WHERE ' . $condition, $params);
            $lists  = pdo_fetchall('SELECT * FROM ' . tablename('str_dish_category') . ' WHERE ' . $condition . ' ORDER BY displayorder DESC,id ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
            if (!empty($lists)) {
                $ids  = implode(',', array_keys($lists));
                $nums = pdo_fetchall('SELECT count(*) AS num,cid FROM ' . tablename('str_dish') . " WHERE uniacid = :aid AND cid IN ({$ids}) GROUP BY cid", array(
                    ':aid' => $_W['uniacid']
                ), 'cid');
            }
            $pager = pagination($total, $pindex, $psize);
            if (checksubmit('submit')) {
                if (!empty($_GPC['ids'])) {
                    foreach ($_GPC['ids'] as $k => $v) {
                        $data = array(
                            'title' => trim($_GPC['title'][$k]),
                            'displayorder' => intval($_GPC['displayorder'][$k])
                        );
                        pdo_update('str_dish_category', $data, array(
                            'uniacid' => $_W['uniacid'],
                            'id' => intval($v)
                        ));
                    }
                    message('编辑成功', $this->createWebUrl('manage', array(
                        'op' => 'cate_list'
                    )), 'success');
                }
            }
            include $this->template('category');
        } elseif ($op == 'cate_post') {
            if (checksubmit('submit')) {
                if (!empty($_GPC['title'])) {
                    foreach ($_GPC['title'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v))
                            continue;
                        $data['sid']          = $sid;
                        $data['uniacid']      = $_W['uniacid'];
                        $data['title']        = $v;
                        $data['displayorder'] = intval($_GPC['displayorder'][$k]);
                        pdo_insert('str_dish_category', $data);
                    }
                }
                message('添加菜品分类成功', $this->createWebUrl('manage', array(
                    'sid' => $sid,
                    'op' => 'cate_list'
                )), 'success');
            }
            include $this->template('category');
        } elseif ($op == 'cate_del') {
            $id = intval($_GPC['id']);
            pdo_delete('str_dish_category', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'id' => $id
            ));
            pdo_delete('str_dish', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'cid' => $id
            ));
            message('删除菜品分类成功', $this->createWebUrl('manage', array(
                'op' => 'cate_list'
            )), 'success');
        } elseif ($op == 'dish_del') {
            $id = intval($_GPC['id']);
            pdo_delete('str_dish', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'id' => $id
            ));
            message('删除菜品成功', $this->createWebUrl('manage', array(
                'op' => 'dish_list'
            )), 'success');
        } elseif ($op == 'dish_list') {
            $condition      = ' uniacid = :aid AND sid = :sid';
            $params[':aid'] = $_W['uniacid'];
            $params[':sid'] = $sid;
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }
            if (!empty($_GPC['cid'])) {
                $condition .= " AND cid = :cid";
                $params[':cid'] = intval($_GPC['cid']);
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_dish') . ' WHERE ' . $condition, $params);
            $lists  = pdo_fetchall('SELECT * FROM ' . tablename('str_dish') . ' WHERE ' . $condition . ' ORDER BY displayorder DESC,id ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            if (!empty($lists)) {
                foreach ($lists as &$di) {
                    $di['price'] = iunserializer($di['price']);
                }
            }
            $pager    = pagination($total, $pindex, $psize);
            $category = pdo_fetchall('SELECT title, id FROM ' . tablename('str_dish_category') . ' WHERE uniacid = :aid AND sid = :sid', array(
                ':aid' => $_W['uniacid'],
                ':sid' => $sid
            ), 'id');
            include $this->template('dish');
        } elseif ($op == 'dish_post') {
            load()->func('tpl');
            $category = pdo_fetchall('SELECT title, id FROM ' . tablename('str_dish_category') . ' WHERE uniacid = :aid AND sid = :sid ORDER BY displayorder DESC, id ASC', array(
                ':aid' => $_W['uniacid'],
                ':sid' => $sid
            ));
            $id       = intval($_GPC['id']);
            if ($id) {
                $item = pdo_fetch('SELECT * FROM ' . tablename('str_dish') . ' WHERE uniacid = :aid AND id = :id', array(
                    ':aid' => $_W['uniacid'],
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('菜品不存在或已删除', $this->createWebUrl('manage', array(
                        'dish_list'
                    )), 'success');
                }
                $item['price'] = iunserializer($item['price']);
            } else {
                $item['total']    = -1;
                $item['unitname'] = '份';
            }
            if (checksubmit('submit')) {
                $data           = array(
                    'sid' => $sid,
                    'uniacid' => $_W['uniacid'],
                    'title' => trim($_GPC['title']),
                    'unitname' => trim($_GPC['unitname']),
                    'total' => intval($_GPC['total']),
                    'sailed' => intval($_GPC['sailed']),
                    'grant_credit' => intval($_GPC['grant_credit']),
                    'is_display' => intval($_GPC['is_display']),
                    'cid' => intval($_GPC['cid']),
                    'thumb' => trim($_GPC['thumb']),
                    'recommend' => intval($_GPC['recommend']),
                    'show_group_price' => intval($_GPC['show_group_price']),
                    'label' => trim($_GPC['label']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'description' => trim($_GPC['description']),
                    'first_order_limit' => intval($_GPC['first_order_limit']),
                    'buy_limit' => intval($_GPC['buy_limit'])
                );
                $price          = array();
                $price_original = floatval($_GPC['price'][0]);
                foreach ($_GPC['group'] as $k => $v) {
                    $temp = floatval($_GPC['price'][$k]);
                    if (!$temp) {
                        $temp = $price_original;
                    }
                    $price[$v] = $temp;
                }
                $data['price'] = iserializer($price);
                if ($id) {
                    pdo_update('str_dish', $data, array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
                } else {
                    pdo_insert('str_dish', $data);
                }
                message('编辑菜品成功', $this->createWebUrl('manage', array(
                    'op' => 'dish_list'
                )), 'success');
            }
            include $this->template('dish');
        } elseif ($op == 'order') {
            load()->func('tpl');
            $condition      = ' WHERE uniacid = :aid AND sid = :sid';
            $params[':aid'] = $_W['uniacid'];
            $params[':sid'] = $sid;
            $status         = intval($_GPC['status']);
			$pay_type       = $_GPC['pay_type'];
            if ($status) {
                $condition .= ' AND status = :stu';
                $params[':stu'] = $status;
            }
			if ($pay_type){
				$condition .= ' AND pay_type = :pay';
                $params[':pay'] = $pay_type;
			}
            $keyword = trim($_GPC['keyword']);
            if (!empty($keyword)) {
                $condition .= " AND (username LIKE '%{$keyword}%' OR mobile LIKE '%{$keyword}%')";
            }
            if (!empty($_GPC['addtime'])) {
                $starttime = strtotime($_GPC['addtime']['start']);
                $endtime   = strtotime($_GPC['addtime']['end']) + 86399;
            } else {
                $starttime = strtotime('-15 day');
                $endtime   = TIMESTAMP;
            }
            $condition .= " AND addtime > :start AND addtime < :end";
            $params[':start'] = $starttime;
            $params[':end']   = $endtime;
            $pindex           = max(1, intval($_GPC['page']));
            $psize            = 20;
            $total            = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order') . $condition, $params);
            $data             = pdo_fetchall('SELECT * FROM ' . tablename('str_order') . $condition . ' ORDER BY addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            if (!empty($data)) {
                foreach ($data as &$da) {
                    $da['is_trash'] = check_trash($da['sid'], $da['uid'], 'fetch');
                    if ($da['order_type'] >= 3) {
                        $da['table'] = pdo_get('str_tables_category', array(
                            'uniacid' => $_W['uniacid'],
                            'sid' => $sid,
                            'id' => $da['table_id']
                        ));
                    }
                }
            }
			if ($_GPC['out_put'] == 'output') {
				//数据整理
				$data  = pdo_fetchall('SELECT * FROM ' . tablename('str_order') . $condition . ' ORDER BY addtime DESC ', $params);
				$paytypes=array(
					'alipay'=>'支付宝',
					'wechat'=>'微信支付',
					'credit'=>'余额支付',
					'delivery'=>'餐到付款'
				);
				$status=array(
					'1'=>'待处理',
					'2'=>'处理中',
					'3'=>'已完成',
					'4'=>'已取消',
					'8'=>'退款申请',
					'9'=>'已退款'
				);
				$types=array(
					'1'=>'店内',
					'2'=>'外卖',
					'3'=>'订座',
					'4'=>'预定'
				);
				$cardtypes=array(
					'1'=>'微信卡券',
					'2'=>'系统卡券'
				);
				foreach($data as &$dat){
					//时间显示
					$dat['addtime']=date('Y-m-d H:i',$dat['addtime']);
					//支付方式
					if(empty($dat['pay_type'])){
						$dat['pay_type']='未支付';
					}elseif(empty($paytypes[$dat['pay_type']])){
						$dat['pay_type']='餐到付款';
					}else{
						$dat['pay_type']=$paytypes[$dat['pay_type']];
					}
					//订单状态
					if(empty($status[$dat['status']])){
						$dat['status']='未知';
					}else{
						$dat['status']=$status[$dat['status']];
					}
					//订单类型
					$dat['order_type']=$types[$dat['order_type']];
					//优惠券
					if(empty($dat['is_usecard'])){
						$dat['youhuiquan']='未使用';
					}elseif(empty($cardtypes[$dat['card_type']])){
						$dat['youhuiquan']='人工优惠';
					}else{
						$dat['youhuiquan']=$cardtypes[$dat['card_type']];
					}
					//优惠后价格
					if($dat['is_usecard']){
						$dat['yprice']=$dat['card_fee'].'元';
					}else{
						$dat['yprice']='0';
					}
					//份数/总价
					$dat['fprice']=$dat['num']."份/".$dat['price']."元";
				}
				//表头信息
				$header=array(
					'id'=>'订单ID',
					'username'=>'预定人',
					'mobile'=>'电话',
					'order_type'=>'类型',
					'pay_type'=>'支付方式',
					'status'=>'订单状态',
					'fprice'=>'份数/总价',
					'youhuiquan'=>'优惠券',
					'yprice'=>'优惠后价格',
					'addtime'=>'下单时间'
				);
				//执行导出
				export2excel($header,$data,'订单数据');
				exit;
			}
            $pager = pagination($total, $pindex, $psize);
            $types = order_types();
            include $this->template('order');
        } elseif ($op == 'orderdetail') {
            $pay_types = pay_types();
            $id        = intval($_GPC['id']);
            $order     = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $id
            ));
            if (empty($order)) {
                message('订单不存在或已经删除', $this->createWebUrl('manage', array(
                    'op' => 'order'
                )), 'error');
            } else {
                $order['dish'] = get_dish($order['id']);
                if ($order['comment'] == 1) {
                    $comment = pdo_fetch('SELECT * FROM ' . tablename('str_order_comment') . ' WHERE uniacid = :aid AND oid = :oid', array(
                        ':aid' => $_W['uniacid'],
                        ':oid' => $id
                    ));
                }
            }
            $logs = get_order_log($id);
            include $this->template('order');
        } elseif ($op == 'status') {
            $ids = $_GPC['id'];
			$order     = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $id
            ));
            if (!is_array($ids)) {
                $ids = array(
                    $ids
                );
            }
            $status = intval($_GPC['status']);
            foreach ($ids as $id) {
                $id = intval($id);
                if ($id <= 0)
                    continue;
				$order = get_order($id);
                if ($status == 5) {
                    pdo_update('str_order', array(
                        'pay_type' => 'cash'
                    ), array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
                   
                    if ($order['order_type'] == 1 && $order['table_id'] > 0) {
                        pdo_update('str_tables', array(
                            'status' => '4'
                        ), array(
                            'uniacid' => $_W['uniacid'],
                            'id' => $order['table_id']
                        ));
                    }
				} elseif ($status == 9){
					pdo_update('str_order', array(
                        'status' => $status
                    ), array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
					$log=array($order['uid'],"码上微外卖单号{$order['id']}退款");
					mc_credit_update($order['uid'], 'credit2', $order['price'],$log);
                } else {
                    pdo_update('str_order', array(
                        'status' => $status
                    ), array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
                }
                set_order_log($id, $sid, $status);
                wechat_notice_order($sid, $id, $status);
            }
            message('更新订状态成功', referer(), 'success');
        } elseif ($op == 'trash_add') {
            $id    = intval($_GPC['id']);
            $order = get_order($id);
            if (empty($order)) {
                message('订单不存在或已经删除', referer(), 'error');
            }
            $isexist = pdo_fetchcolumn('SELECT uid FROM ' . tablename('str_user_trash') . ' WHERE uniacid = :uniacid AND sid = :sid AND uid = :uid', array(
                ':uniacid' => $_W['uniacid'],
                ':sid' => $order['sid'],
                ':uid' => $order['uid']
            ));
            if (!empty($isexist)) {
                message('该用户已经在黑名单中', referer(), 'error');
            }
            $data = array(
                'uniacid' => $_W['uniacid'],
                'sid' => $order['sid'],
                'uid' => $order['uid'],
                'username' => $order['username'],
                'mobile' => $order['mobile'],
                'addtime' => TIMESTAMP
            );
            pdo_insert('str_user_trash', $data);
            message('添加到黑名单成功', referer(), 'success');
        } elseif ($op == 'trash_list') {
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 20;
            $condition = ' WHERE uniacid = :uniacid AND sid = :sid';
            $params    = array(
                ':uniacid' => $_W['uniacid'],
                ':sid' => $sid
            );
            $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_user_trash') . $condition, $params);
            $data      = pdo_fetchall('SELECT * FROM ' . tablename('str_user_trash') . $condition . ' ORDER BY addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            $pager     = pagination($total, $pindex, $psize);
            include $this->template('trash');
        } elseif ($op == 'trash_del') {
            $uid = intval($_GPC['uid']);
            pdo_delete('str_user_trash', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'uid' => $uid
            ));
            message('从黑名单中移除成功', referer(), 'success');
        } elseif ($op == 'comment_list') {
            load()->func('tpl');
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 20;
            $condition = ' WHERE a.uniacid = :uniacid AND a.sid = :sid';
            $params    = array(
                ':uniacid' => $_W['uniacid'],
                ':sid' => $sid
            );
            $status    = intval($_GPC['status']);
            if ($status > 0) {
                $condition .= " AND a.status = :status";
                $params[':status'] = $status;
            }
            $oid = intval($_GPC['oid']);
            if ($oid > 0) {
                $condition .= " AND a.oid = :oid";
                $params[':oid'] = $oid;
            }
            if (!empty($_GPC['addtime'])) {
                $starttime = strtotime($_GPC['addtime']['start']);
                $endtime   = strtotime($_GPC['addtime']['end']) + 86399;
            } else {
                $starttime = strtotime('-15 day');
                $endtime   = TIMESTAMP;
            }
            $condition .= " AND a.addtime > :start AND a.addtime < :end";
            $params[':start'] = $starttime;
            $params[':end']   = $endtime;
            $total            = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order_comment') . ' AS a ' . $condition, $params);
            $data             = pdo_fetchall('SELECT a.*, b.uid,b.openid,b.addtime FROM ' . tablename('str_order_comment') . ' AS a LEFT JOIN ' . tablename('str_order') . ' AS b ON a.oid = b.id ' . $condition . ' ORDER BY a.addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            $pager            = pagination($total, $pindex, $psize);
            include $this->template('comment');
        } elseif ($op == 'comment_status') {
            $id = intval($_GPC['id']);
            pdo_update('str_order_comment', array(
                'status' => intval($_GPC['status'])
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            message('设置评论状态成功', $this->createWebUrl('manage', array(
                'op' => 'comment_list'
            )), 'success');
        } elseif ($op == 'orderdel') {
            $id = intval($_GPC['id']);
            pdo_delete('str_order', array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            pdo_delete('str_stat', array(
                'uniacid' => $_W['uniacid'],
                'oid' => $id
            ));
            pdo_delete('str_order_comment', array(
                'uniacid' => $_W['uniacid'],
                'oid' => $id
            ));
            message('删除订单成功', $this->createWebUrl('manage', array(
                'op' => 'order'
            )), 'success');
        } elseif ($op == 'print_post') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                $item = pdo_fetch('SELECT * FROM ' . tablename('str_print') . ' WHERE uniacid = :uniacid AND id = :id', array(
                    ':uniacid' => $_W['uniacid'],
                    ':id' => $id
                ));
            }
            if (empty($item)) {
                $item = array(
                    'status' => 1,
                    'print_nums' => 1,
                    'type' => 1
                );
            }
			$printers=pdo_getall('printer',array('uniacid'=>$_W['uniacid']));
            if (checksubmit('submit')) {
                $data['type']       = intval($_GPC['type']);
                $data['status']     = intval($_GPC['status']);
                $data['name']       = trim($_GPC['name']);
                $data['print_no']   = trim($_GPC['print_no']);
                $data['key']        = trim($_GPC['key']);
				$data['print_type']        = intval($_GPC['print_type']);
                $data['print_nums'] = intval($_GPC['print_nums']) ? intval($_GPC['print_nums']) : 1;
                if (!empty($_GPC['qrcode_link']) && (strexists($_GPC['qrcode_link'], 'http://') || strexists($_GPC['qrcode_link'], 'https://'))) {
                    $data['qrcode_link'] = trim($_GPC['qrcode_link']);
                }
                $data['print_header'] = trim($_GPC['print_header']);
                $data['print_footer'] = trim($_GPC['print_footer']);
                $data['uniacid']      = $_W['uniacid'];
                $data['sid']          = $sid;
                if (!empty($item) && $id) {
                    pdo_update('str_print', $data, array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
                } else {
                    pdo_insert('str_print', $data);
                }
                message('更新打印机设置成功', $this->createWebUrl('manage', array(
                    'op' => 'print_list'
                )), 'success');
            }
            include $this->template('print');
        } elseif ($op == 'print_list') {
            $data = pdo_fetchall('SELECT * FROM ' . tablename('str_print') . ' WHERE uniacid = :uniacid AND sid = :sid', array(
                ':uniacid' => $_W['uniacid'],
                ':sid' => $sid
            ));
            include $this->template('print');
        } elseif ($op == 'print_del') {
            $id = intval($_GPC['id']);
            pdo_delete('str_print', array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            message('删除打印机成功', referer(), 'success');
        } elseif ($op == 'log_del') {
            $id = intval($_GPC['id']);
            pdo_delete('str_order_print', array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            message('删除打印记录成功', referer(), 'success');
        } elseif ($op == 'print_log') {
            $id   = intval($_GPC['id']);
            $item = pdo_fetch('SELECT * FROM ' . tablename('str_print') . ' WHERE uniacid = :uniacid AND id = :id', array(
                ':uniacid' => $_W['uniacid'],
                ':id' => $id
            ));
            if (empty($item)) {
                message('打印机不存在或已删除', $this->createWebUrl('manage', array(
                    'op' => 'print_list'
                )), 'success');
            }
            if (!empty($item['print_no']) && !empty($item['key'])) {
                $wprint = new wprint();
                $status = $wprint->QueryPrinterStatus($item['print_no'], $item['key']);
                if (is_error($status)) {
                    $status = '查询打印机状态失败。请刷新页面重试';
                }
            }
            $condition      = ' WHERE a.uniacid = :aid AND a.sid = :sid AND a.pid = :pid';
            $params[':aid'] = $_W['uniacid'];
            $params[':sid'] = $sid;
            $params[':pid'] = $id;
            if (!empty($_GPC['oid'])) {
                $oid = trim($_GPC['oid']);
                $condition .= ' AND a.oid = :oid';
                $params[':oid'] = $oid;
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order_print') . ' AS a ' . $condition, $params);
            $data   = pdo_fetchall('SELECT a.*,b.username,b.mobile FROM ' . tablename('str_order_print') . ' AS a LEFT JOIN' . tablename('str_order') . ' AS b ON a.oid = b.id' . $condition . ' ORDER BY addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            $pager  = pagination($total, $pindex, $psize);
            include $this->template('print');
        } elseif ($op == 'ajaxprint') {
            $id     = intval($_GPC['id']);
            $status = print_order($id, true);
            if (is_error($status)) {
                exit($status['message']);
            }
            exit('success');
        }
        if ($op == 'clerk_post') {
            $id    = intval($_GPC['id']);
            $clerk = get_clerk($id);
            if ($_W['ispost']) {
                $insert['uniacid']  = $_W['uniacid'];
                $insert['sid']      = $sid;
                $insert['title']    = trim($_GPC['title']);
                $insert['nickname'] = trim($_GPC['nickname']);
                $insert['openid']   = trim($_GPC['openid']);
                $insert['email']    = trim($_GPC['email']);
                $insert['is_sys']   = intval($_GPC['is_sys']);
                if (empty($insert['openid']) && empty($insert['email'])) {
                    exit('粉丝openid和店员邮箱必须填写一项');
                }
                if ($id > 0) {
                    pdo_update('str_clerk', $insert, array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
                } else {
                    $insert['addtime'] = TIMESTAMP;
                    pdo_insert('str_clerk', $insert);
                }
                exit('success');
            }
            include $this->template('clerk');
        }
        if ($op == 'fetch_openid') {
            $acid     = $_W['acid'];
            $nickname = trim($_GPC['nickname']);
            $openid   = trim($_GPC['openid']);
            if (!empty($openid)) {
                $data = pdo_fetch('SELECT openid,nickname FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :uniacid AND acid = :acid AND openid = :openid ', array(
                    ':uniacid' => $_W['uniacid'],
                    ':acid' => $acid,
                    ':openid' => $openid
                ));
            }
            if (empty($data)) {
                if (!empty($nickname)) {
                    $data = pdo_fetch('SELECT openid,nickname FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :uniacid AND acid = :acid AND nickname = :nickname ', array(
                        ':uniacid' => $_W['uniacid'],
                        ':acid' => $acid,
                        ':nickname' => $nickname
                    ));
                    if (empty($data)) {
                        exit('error');
                    } else {
                        exit(json_encode($data));
                    }
                } else {
                    exit('error');
                }
            } else {
                exit(json_encode($data));
            }
        }
        if ($op == 'clerk_list') {
            $data = pdo_fetchall('SELECT * FROM ' . tablename('str_clerk') . ' WHERE uniacid = :aid AND sid = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $sid
            ));
            include $this->template('clerk');
        }
        if ($op == 'clerk_del') {
            $id = intval($_GPC['id']);
            pdo_delete('str_clerk', array(
                'uniacid' => $_W['uniacid'],
                'sid' => $sid,
                'id' => $id
            ));
            message('删除店员成功', referer(), 'success');
        }
        if ($op == 'use_card') {
            $id    = intval($_GPC['id']);
            $price = floatval($_GPC['price']);
            $order = pdo_fetch('SELECT card_fee FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $id
            ));
            if (empty($order)) {
                exit('订单不存在');
            } elseif ($order['card_fee'] < $price) {
                exit('每个订单只能优惠一次');
            }
            $update = array(
                'card_fee' => $order['card_fee'] - $price,
                'is_usecard' => 1,
                'card_type' => 3
            );
            pdo_update('str_order', $update, array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            set_order_log($id, $sid, "管理员设置订单优惠,原价{$order['card_fee']}元,减免{$price}元,优惠后价格{$update['card_fee']}");
            exit('success');
        }
        if ($op == 'edit_table_id') {
            $id       = intval($_GPC['id']);
            $table_id = intval($_GPC['table_id']);
            $table    = get_table($table_id);
            if (empty($table)) {
                exit('桌号不存在');
            }
            $order = pdo_fetch('SELECT table_id, table_info FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $id
            ));
            if (empty($order)) {
                exit('订单不存在');
            } elseif ($order['table_id'] == $table_id) {
                exit('修改桌号和原桌号相同');
            }
            $update = array(
                'table_id' => $table_id,
                'table_info' => $table['ctitle'] . '-' . $table['title']
            );
            pdo_update('str_order', $update, array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            set_order_log($id, $sid, "管理员修改桌号,原桌号{$order['table_info']},修改后桌号{$update['table_info']}");
            exit('success');
        }
        if ($op == 'back_order') {
            $keyword   = trim($_GPC['keyword']);
            $condition = '';
            if (!empty($keyword)) {
                $condition .= " AND (uid LIKE '%{$keyword}%' OR realname LIKE '%{$keyword}%' OR mobile LIKE '%{$keyword}%')";
                $members = pdo_fetchall('SELECT uid,mobile,email,groupid,realname FROM ' . tablename('mc_members') . ' WHERE uniacid = :uniacid' . $condition, array(
                    ':uniacid' => $_W['uniacid']
                ), 'uid');
            }
            $uid = intval($_GPC['uid']);
            if ($uid > 0) {
                $member             = mc_fetch($uid, array(
                    'uid',
                    'groupid',
                    'email',
                    'realname',
                    'mobile',
                    'address'
                ));
                $fans               = mc_fansinfo($uid);
                $member['nickname'] = $fans['nickname'];
                $member['openid']   = $fans['openid'];
                $groups             = mc_groups();
                $_W['member']       = $member;
                $keyword            = $uid;
            }
            $categorys = pdo_fetchall('SELECT * FROM ' . tablename('str_dish_category') . ' WHERE uniacid = :uniacid AND sid = :sid ORDER BY displayorder DESC', array(
                ':uniacid' => $_W['uniacid'],
                ':sid' => $sid
            ), 'id');
            $dishes    = pdo_fetchall('SELECT id,title,price,total,cid FROM ' . tablename('str_dish') . ' WHERE uniacid = :uniacid AND sid = :sid ORDER BY total ASC,displayorder DESC', array(
                ':uniacid' => $_W['uniacid'],
                ':sid' => $sid
            ), 'id');
            $data      = array();
            foreach ($dishes as &$dish) {
                $dish['price']        = dish_group_price($dish['price']);
                $data[$dish['cid']][] = $dish;
            }
            $dishes_str = json_encode($dishes, true);
            $tables     = get_tables($sid);
            include $this->template('back_order');
        }
        if ($op == 'back_submit') {
            if ($_GPC['order_type'] == 1) {
                $table_id = intval($_GPC['table_id']);
                $table    = get_table($table_id);
                if (empty($table)) {
                    message('桌号不存在', referer(), 'error');
                }
            }
            $uid = intval($_GPC['uid']);
            if ($uid > 0) {
                $member             = mc_fetch($uid, array(
                    'uid',
                    'groupid',
                    'email',
                    'realname'
                ));
                $fans               = mc_fansinfo($uid);
                $member['nickname'] = $fans['nickname'];
                $member['openid']   = $fans['openid'];
                $_W['member']       = $member;
            }
            $store        = pdo_fetch('SELECT id,title FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $sid
            ));
            $_GPC['dish'] = iunserializer(base64_decode($_GPC['str']));
            $dish         = array();
            if (!empty($_GPC['ids'])) {
                foreach ($_GPC['ids'] as $key => $val) {
                    $key = intval($key);
                    $val = intval($val);
                    $num = $_GPC['nums'][$key];
                    if ($num && $val) {
                        $dish[$val] = intval($num);
                    }
                }
            }
            if (empty($dish)) {
                message('订单信息出错,请重新点餐', $this->createwebUrl('manage', array(
                    'op' => 'back_order'
                )), 'error');
            }
            if (!empty($dish)) {
                $ids_str   = implode(',', array_keys($dish));
                $dish_info = pdo_fetchall('SELECT * FROM ' . tablename('str_dish') . " WHERE uniacid = :aid AND sid = :sid AND id IN ($ids_str)", array(
                    ':aid' => $_W['uniacid'],
                    ':sid' => $sid
                ), 'id');
            }
            $price     = 0;
            $num       = 0;
            $dish_data = array();
            foreach ($dish as $k => &$v) {
                $k = intval($k);
                $v = intval($v);
                if ($k && $v) {
                    $price += ($v * dish_group_price($dish_info[$k]['price']));
                    $num += $v;
                }
                pdo_query('UPDATE ' . tablename('str_dish') . " set sailed = sailed + {$v} WHERE uniacid = :aid AND id = :id", array(
                    ':aid' => $_W['uniacid'],
                    ':id' => $k
                ));
                if ($dish_info[$k]['total'] != -1 && $dish_info[$k]['total'] > 0) {
                    pdo_query('UPDATE ' . tablename('str_dish') . " set total = total - {$v} WHERE uniacid = :aid AND id = :id", array(
                        ':aid' => $_W['uniacid'],
                        ':id' => $k
                    ));
                }
                $dish_data[$k] = array(
                    'id' => $k,
                    'title' => $dish_info[$k]['title'],
                    'price' => dish_group_price($dish_info[$k]['price']) * $v,
                    'num' => $v
                );
            }
            $data['uniacid']    = $_W['uniacid'];
            $data['sid']        = $sid;
            $data['uid']        = $_W['member']['uid'];
            $data['groupid']    = $_W['member']['groupid'];
            $data['openid']     = trim($_W['member']['openid']);
            $data['address']    = !empty($_GPC['address']) ? $_GPC['address'] : $_W['member']['address'];
            $data['mobile']     = !empty($_GPC['mobile']) ? $_GPC['mobile'] : $_W['member']['mobile'];
            $data['username']   = !empty($_GPC['username']) ? $_GPC['username'] : $_W['member']['username'];
            $data['order_type'] = intval($_GPC['order_type']);
            $data['note']       = trim($_GPC['note']);
            $data['pay_type']   = '';
            $data['table_id']   = $table['id'];
            $data['table_info'] = $table['ctitle'] . '-' . $table['title'];
            $data['price']      = $price;
            $data['card_fee']   = $price;
            $data['num']        = $num;
            $data['addtime']    = TIMESTAMP;
            $data['status']     = 1;
            $data['is_notice']  = 1;
            $data['is_back']    = 1;
            pdo_insert('str_order', $data);
            $id = pdo_insertid();
            foreach ($dish as $k => &$v) {
                $k    = intval($k);
                $v    = intval($v);
                $stat = array();
                if ($k && $v) {
                    $stat['oid']        = $id;
                    $stat['uniacid']    = $_W['uniacid'];
                    $stat['sid']        = $sid;
                    $stat['dish_id']    = $k;
                    $stat['dish_num']   = $v;
                    $stat['dish_title'] = $dish_info[$k]['title'];
                    $stat['dish_price'] = ($v * dish_group_price($dish_info[$k]['price']));
                    $stat['addtime']    = TIMESTAMP;
                    pdo_insert('str_stat', $stat);
                }
            }
            message('后台点餐成功', $this->createwebUrl('manage', array(
                'op' => 'order'
            )), 'list');
        }
    }
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $config = get_config();
        if ($config['version'] == 2) {
            $store = pdo_fetch('SELECT id,forward_mode FROM ' . tablename('str_store') . ' WHERE uniacid = :uniacid and id = :id', array(
                ':uniacid' => $_W['uniacid'],
                ':id' => $config['default_sid']
            ));
            if (!$store) {
                message('没有有效的门店');
            } else {
                if (!$store['forward_mode']) {
                    header('location: ' . $this->createMobileUrl('store', array(
                        'sid' => $store['id']
                    )));
                } else {
                    header('location: ' . $this->createMobileUrl('dish', array(
                        'mode' => $store['forward_mode'],
                        'sid' => $store['id']
                    )));
                }
                exit();
            }
        }
        $pindex         = max(1, intval($_GPC['page']));
        $psize          = 100;
        $key            = trim($_GPC['key']);
        $condition      = ' WHERE uniacid = :aid AND status = 1';
        $params[':aid'] = $_W['uniacid'];
        if (!empty($key)) {
            $condition .= " AND title LIKE '%{$key}%'";
        }
        $area_id = intval($_GPC['aid']);
        if (!empty($area_id) && $config['area_search'] == 1) {
            $condition .= " AND area_id = :area_id";
            $params[':area_id'] = $area_id;
        }
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_store') . $condition, $params);
        $data  = pdo_fetchall('SELECT * FROM ' . tablename('str_store') . $condition . ' ORDER BY displayorder DESC LIMIT ' . (($pindex - 1) * $psize) . ', ' . $psize, $params);
        $str   = '';
        if (!empty($data)) {
            foreach ($data as &$dca) {
                if (!$dca['forward_mode']) {
                    $dca['url'] = $this->createMobileUrl('store', array(
                        'sid' => $dca['id']
                    ));
                } else {
                    $dca['url'] = $this->createMobileUrl('dish', array(
                        'mode' => $dca['forward_mode'],
                        'sid' => $dca['id']
                    ));
                }
                $dca['business_hours_flag'] = 0;
                $dca['business_hours']      = iunserializer($dca['business_hours']);
                if (is_array($dca['business_hours'])) {
                    foreach ($dca['business_hours'] as $li) {
                        $li_s_tmp  = explode(':', $li['s']);
                        $li_e_tmp  = explode(':', $li['e']);
                        $s_timepas = mktime($li_s_tmp[0], $li_s_tmp[1]);
                        $e_timepas = mktime($li_e_tmp[0], $li_e_tmp[1]);
                        $now       = TIMESTAMP;
                        if ($now >= $s_timepas && $now <= $e_timepas) {
                            $dca['business_hours_flag'] = 1;
                            break;
                        }
                    }
                }
            }
        }
        $pager = pagination($total, $pindex, $psize, '', array(
            'before' => 0,
            'after' => 0
        ));
        include $this->template('index');
    }
    public function doMobileDish()
    {
        global $_W, $_GPC;
        $sid = intval($_GPC['sid']);
        checkauth();
        checkclerk($sid);
        check_trash($sid);
        $mode = intval($_GPC['mode']);
        if (!$mode) {
           $mode = 2;
        }
        isetcookie('__z', 0, -10000);
        if ($mode == 1) {
            $table_id = intval($_GPC['tid']);
            $table    = get_table($table_id);
            if (empty($table)) {
                message('桌号错误', referer(), 'error');
            }
            isetcookie('__z', $table_id, 5000);
            if (!empty($_GPC['f'])) {
                table_qrcode_scan($sid, $table_id);
            }
        } elseif ($mode == 4) {
            $cid            = intval($_GPC['cid']);
            $table_category = pdo_get('str_tables_category', array(
                'uniacid' => $_W['uniacid'],
                'id' => $cid
            ));
            if (empty($table_category)) {
                message('桌台类型不存在或已删除', referer(), 'error');
            }
        }
        $store = pdo_fetch('SELECT title,logo,id,content,delivery_price,business_hours,slide_status,send_price,dish_style,is_meal,is_takeout,comment_status,notice,copyright,thumbs FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $sid
        ));
        if (empty($store)) {
            message('门店信息不存在', $this->createMobileUrl('index'), 'error');
        }
        $store['copyright'] = (array) iunserializer($store['copyright']);
        $store['thumbs']    = (array) iunserializer($store['thumbs']);
        $title              = $store['title'];
        $_share             = get_share($store);
        if ($store['comment_status'] == 1) {
            $comment_stat = comment_stat($sid);
        }
        $store['business_hours_flag'] = 0;
        $store['business_hours']      = iunserializer($store['business_hours']);
        if (is_array($store['business_hours'])) {
            $hour_str = '';
            foreach ($store['business_hours'] as $li) {
                $hour_str .= $li['s'] . '~' . $li['e'] . '、';
                $li_s_tmp  = explode(':', $li['s']);
                $li_e_tmp  = explode(':', $li['e']);
                $s_timepas = mktime($li_s_tmp[0], $li_s_tmp[1]);
                $e_timepas = mktime($li_e_tmp[0], $li_e_tmp[1]);
                $now       = TIMESTAMP;
                if (!$store['business_hours_flag']) {
                    if ($now >= $s_timepas && $now <= $e_timepas) {
                        $store['business_hours_flag'] = 1;
                    }
                }
            }
            $hour_str = trim($hour_str, '、');
        }
        if (!empty($_GPC['f'])) {
            del_order_cart($sid);
        }
        $cart      = get_order_cart($sid);
        $category  = pdo_fetchall('SELECT title, id FROM ' . tablename('str_dish_category') . ' WHERE uniacid = :aid AND sid = :sid ORDER BY displayorder DESC, id ASC', array(
            ':aid' => $_W['uniacid'],
            ':sid' => $sid
        ));
        $dish      = pdo_fetchall('SELECT * FROM ' . tablename('str_dish') . ' WHERE uniacid = :aid AND sid = :sid AND is_display = 1 ORDER BY displayorder DESC, id ASC', array(
            ':aid' => $_W['uniacid'],
            ':sid' => $sid
        ));
        $cate_dish = array();
        foreach ($dish as &$di) {
            $di['member_price']      = dish_group_price($di['price']);
            $di['price']             = iunserializer($di['price']);
            $cate_dish[$di['cid']][] = $di;
        }
        load()->model('mc');
        $groups         = mc_groups();
        $is_first_order = pdo_get('str_order', array(
            'uniacid' => $_W['uniacid'],
            'sid' => $sid,
            'uid' => $_W['member']['uid']
        ));
        if (empty($is_first_order)) {
            $is_first_order = 1;
        } else {
            $is_first_order = 0;
        }
        include $this->template('dish');
    }
    public function doMobileAjax()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']);
        if ($op == 'table') {
            $type = trim($_GPC['type']);
            if ($type == 'submit') {
                isetcookie('__z', intval($_GPC['num']), 1000);
            } else {
                isetcookie('__z', 0, -10);
            }
        }
    }
    public function doMobileStore()
    {
        global $_W, $_GPC;
        $sid = intval($_GPC['sid']);
        checkclerk($sid);
        check_trash($sid);
        $store                        = pdo_fetch('SELECT * FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $sid
        ));
        $store['copyright']           = (array) iunserializer($store['copyright']);
        $title                        = $store['title'];
        $_share                       = get_share($store);
        $store['thumbs']              = iunserializer($store['thumbs']);
        $store['sns']                 = (array) iunserializer($store['sns']);
        $store['business_hours_flag'] = 0;
        $store['business_hours']      = iunserializer($store['business_hours']);
        if (is_array($store['business_hours'])) {
            $hour_str = '';
            foreach ($store['business_hours'] as $li) {
                $hour_str .= $li['s'] . '~' . $li['e'] . '、';
                $li_s_tmp  = explode(':', $li['s']);
                $li_e_tmp  = explode(':', $li['e']);
                $s_timepas = mktime($li_s_tmp[0], $li_s_tmp[1]);
                $e_timepas = mktime($li_e_tmp[0], $li_e_tmp[1]);
                $now       = TIMESTAMP;
                if (!$store['business_hours_flag']) {
                    if ($now >= $s_timepas && $now <= $e_timepas) {
                        $store['business_hours_flag'] = 1;
                    }
                }
            }
            $hour_str = trim($hour_str, '、');
        }
        $store['address'] = str_replace('+', '', $store['distirct']) . $store['address'];
        include $this->template('store');
    }
    public function doMobileOrder()
    {
        global $_W, $_GPC;
        checkauth();
        $sid  = intval($_GPC['sid']);
        $mode = intval($_GPC['mode']);
        checkclerk($sid);
        check_trash($sid);
        $store              = pdo_fetch('SELECT * FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $sid
        ));
        $store['copyright'] = (array) iunserializer($store['copyright']);
        $title              = $store['title'];
        $_share             = get_share($store);
        if (empty($store)) {
            message('门店不存在', '', 'error');
        }
        $is_first_order = pdo_get('str_order', array(
            'uniacid' => $_W['uniacid'],
            'sid' => $sid,
            'uid' => $_W['member']['uid']
        ));
        if (empty($is_first_order)) {
            $is_first_order = 1;
        } else {
            $is_first_order = 0;
        }
        $cart = set_order_cart($sid);
        if (is_error($cart)) {
            message($cart . message, '', 'error');
        }
        $dishes    = $cart['data'];
        $is_add    = 0;
        $recommend = pdo_fetchall('SELECT id FROM ' . tablename('str_dish') . ' WHERE uniacid = :uniacid AND sid = :sid AND recommend = 1 AND is_display = 1', array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $sid
        ), id);
        $add       = array_keys($recommend);
        $add_arr   = array_diff($add, array_keys($dishes));
        if (!empty($add_arr)) {
            $is_add   = 1;
            $add_str  = implode(',', $add_arr);
            $dish_add = pdo_fetchall('SELECT * FROM ' . tablename('str_dish') . " WHERE uniacid = :aid AND sid = :sid AND id IN ($add_str)", array(
                ':aid' => $_W['uniacid'],
                ':sid' => $sid
            ), 'id');
            if (!empty($dish_add)) {
                foreach ($dish_add as &$di_add) {
                    $di_add['member_price'] = dish_group_price($di_add['price']);
                    $di_add['price']        = iunserializer($di_add['price']);
                }
            }
        }
        if (!empty($dishes)) {
            $ids_str   = implode(',', array_keys($dishes));
            $dish_info = pdo_fetchall('SELECT * FROM ' . tablename('str_dish') . " WHERE uniacid = :aid AND sid = :sid AND id IN ($ids_str)", array(
                ':aid' => $_W['uniacid'],
                ':sid' => $sid
            ), 'id');
            foreach ($dish_info as &$dis) {
                $dis['price']        = iunserializer($dis['price']);
                $dis['member_price'] = dish_group_price($dis['price']);
            }
        }
        include $this->template('order');
    }
    public function doMobileOrderConfirm()
    {
        global $_W, $_GPC;
        checkauth();
        $sid  = intval($_GPC['sid']);
        $mode = intval($_GPC['mode']);
        if (!$_W['isajax']) {
            $return = intval($_GPC['r']);
            checkclerk($sid);
            $store = get_store($sid);
            if (empty($store)) {
                message('门店不存在', '', 'error');
            }
            $store['copyright'] = (array) iunserializer($store['copyright']);
            $title              = $store['title'];
            $is_first           = is_first_order($sid);
            $mobile_verify      = false;
            if ($is_first && $store['mobile_verify']['first_verify'] == 1 && $_W['str_takeout']['sms']['status'] == 1) {
                $mobile_verify = true;
            }
            if (!$return) {
                $cart = set_order_cart($sid);
            } else {
                $cart = get_order_cart($sid);
            }
            if (empty($cart['data'])) {
                message('订单信息出错', '', 'error');
            }
            if ($mode == 2) {
                $minut = date('i', TIMESTAMP);
                if ($minut <= 15) {
                    $minut = 15;
                } elseif ($minut > 15 && $minut <= 30) {
                    $minut = 30;
                } elseif ($minut > 30 && $minut <= 45) {
                    $minut = 45;
                } elseif ($minut > 45 && $minut <= 60) {
                    $minut = 60;
                }
                $now       = mktime(date('H'), $minut);
                $now_limit = $now + 180 * 60;
                for ($now; $now <= $now_limit; $now += 15 * 60) {
                    $str .= '<a href="javascript:void(0);">' . date('H:i', $now) . '</a>';
                }
                $address_id = intval($_GPC['address_id']);
                $address    = get_address($address_id);
                if (empty($address)) {
                    $address = get_default_address();
                }
            } elseif ($mode == 1) {
                $table_id = intval($_GPC['__z']);
                $table    = get_table($table_id);
            }
            $order_member       = pdo_fetch('SELECT id,mobile,username, address FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND uid = :uid ORDER BY id DESC LIMIT 1', array(
                ':aid' => $_W['uniacid'],
                ':uid' => $_W['member']['uid']
            ));
            $member['realname'] = !empty($order_member['username']) ? $order_member['username'] : $member['realname'];
            $member['mobile']   = !empty($order_member['mobile']) ? $order_member['mobile'] : $member['mobile'];
            $member['address']  = !empty($order_member['address']) ? $order_member['address'] : $member['address'];
        } else {
            $store        = pdo_fetch('SELECT title,delivery_price FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $sid
            ));
            $out['errno'] = 1;
            $out['error'] = '';
            if (!$sid || empty($dish)) {
                $out['errno'] = 1;
                $out['error'] = '订单信息不存在或已失效';
            }
            $data['uniacid']    = $_W['uniacid'];
            $data['acid']       = $_W['acid'];
            $data['sid']        = $sid;
            $data['uid']        = $_W['member']['uid'];
            $data['groupid']    = intval($_GPC['groupid']);
            $data['openid']     = $_W['openid'];
            $data['order_type'] = intval($_GPC['order_type']);
            if ($data['order_type'] == 1) {
                $data['mobile']     = trim($_GPC['mobile']);
                $data['username']   = trim($_GPC['username']);
                $data['person_num'] = intval($_GPC['person_num']);
                $data['table_id']   = intval($_GPC['table_id']);
                $data['table_info'] = trim($_GPC['table_info']);
            } elseif ($data['order_type'] == 2) {
                $address               = get_address($_GPC['address_id']);
                $data['mobile']        = trim($address['mobile']);
                $data['username']      = trim($address['realname']);
                $data['address']       = trim($address['address']);
                $data['delivery_time'] = trim($_GPC['delivery_time']) ? trim($_GPC['delivery_time']) : '尽快送出';
                $data['delivery_fee']  = $store['delivery_price'];
            }
            $data['note']     = trim($_GPC['note']);
            $data['pay_type'] = '';
            $cart             = get_order_cart($sid);
            if ($cart['num'] == 0) {
                $out['errno'] = 1;
                $out['error'] = '菜品为空';
                exit(json_encode($out));
            }
            $data['num']          = $cart['num'];
            $data['price']        = $cart['price'];
            $data['card_fee']     = $cart['price'];
            $data['groupid']      = $cart['groupid'];
            $data['addtime']      = TIMESTAMP;
            $data['status']       = 1;
            $data['is_notice']    = 0;
            $data['grant_credit'] = $cart['grant_credit'];
            ;
            $data['is_grant'] = 0;
            pdo_insert('str_order', $data);
            $id = pdo_insertid();
            set_order_log($id, $sid, '用户提交订单');
            set_order_user($sid, $mobile, $realname);
            if (!empty($cart['data'])) {
                $ids_str   = implode(',', array_keys($cart['data']));
                $dish_info = pdo_fetchall('SELECT id,title,price,grant_credit,total FROM ' . tablename('str_dish') . " WHERE uniacid = :aid AND sid = :sid AND id IN ($ids_str)", array(
                    ':aid' => $_W['uniacid'],
                    ':sid' => $sid
                ), 'id');
            }//易 福 源 码 网 破 解
            foreach ($cart['data'] as $k => $v) {
                $k = intval($k);
                $v = intval($v);
                pdo_query('UPDATE ' . tablename('str_dish') . " set sailed = sailed + {$v} WHERE uniacid = :aid AND id = :id", array(
                    ':aid' => $_W['uniacid'],
                    ':id' => $k
                ));
                if ($dish_info[$k]['total'] != -1 && $dish_info[$k]['total'] > 0) {
                    pdo_query('UPDATE ' . tablename('str_dish') . " set total = total - {$v} WHERE uniacid = :aid AND id = :id", array(
                        ':aid' => $_W['uniacid'],
                        ':id' => $k
                    ));
                }
                $stat = array();
                if ($k && $v) {
                    $stat['oid']        = $id;
                    $stat['uniacid']    = $_W['uniacid'];
                    $stat['sid']        = $sid;
                    $stat['dish_id']    = $k;
                    $stat['dish_num']   = $v;
                    $stat['dish_title'] = $dish_info[$k]['title'];
                    $stat['dish_price'] = ($v * dish_group_price($dish_info[$k]['price']));
                    $stat['addtime']    = TIMESTAMP;
                    pdo_insert('str_stat', $stat);
                }
            }
            init_print_order($sid, $id, 'order');
            del_order_cart($sid);
			

            if ($data['order_type'] == 1 && $data['table_id'] > 0) {
                pdo_update('str_tables', array(
                    'status' => '3'
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'id' => $data['table_id']
                ));
            }
            if ($id) {
                $out['errno'] = 0;
                $out['url']   = $this->createMobileUrl('pay', array(
                    'id' => $id
                ));
            } else {
                $out['errno'] = 1;
                $out['error'] = '保存订单失败';
            }
            exit(json_encode($out));
        }
		
        include $this->template('orderconfirm');
    }
    public function doMobileOrderDetail()
    {
        global $_W, $_GPC;
        checkauth();
        $sid   = intval($_GPC['sid']);
        $oid   = intval($_GPC['id']);
        $store = pdo_fetch('SELECT * FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $sid
        ));
        if (empty($store)) {
            message('门店不存在', '', 'error');
        }
        $store['copyright'] = (array) iunserializer($store['copyright']);
        $_share             = get_share($store);
        $title              = $store['title'];
        $order              = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $oid
        ));
        if (empty($order)) {
            message('订单信息不存在', '', 'error');
        }
        $pay_types     = pay_types();
        $order['dish'] = get_dish($order['id']);
        $logs          = get_order_log($oid);
        $types         = order_types();
        include $this->template('orderdetail');
    }
    public function doMobileAjaxOrder()
    {
        global $_W, $_GPC;
        checkauth();
        $id           = intval($_GPC['id']);
		$sid   = intval($_GPC['sid']);
        $op           = trim($_GPC['op']);
        $order        = pdo_fetch('SELECT id FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $id
        ));
        $out['errno'] = 0;
        $out['error'] = 0;
        if (empty($order)) {
            $out['errno'] = 1;
            $out['error'] = '订单不存在';
            exit(json_encode($out));
        }
        if ($op == 'editstatus') {
            pdo_update('str_order', array(
                'status' => 3
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
			$note ='用户确认收到餐厅的外卖！';
			set_order_log($id, $sid, $note);
        } elseif ($op == 'del') {
            pdo_update('str_order', array(
                'status' => 7
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            $out['error'] = $this->createMobileUrl('myorder');
		} elseif ($op == 'tk') {
			pdo_update('str_order', array(
                'status' => 8
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
			$note ='用户申请退款';
			set_order_log($id, $sid, $note);
			wechat_notice_order($sid, $id, 8);
        }
        exit(json_encode($out));
    }//易 福源码 网 破 解
    public function doMobilePay()
    {
        global $_W, $_GPC;
        checkauth();
        $id    = intval($_GPC['id']);
        $order = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $id
        ));
		$item = pdo_fetch('SELECT * FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
                    ':aid' => $_W['uniacid'],
                    ':id' => $order['sid']
                ));//获取门店信息
		$store_print = pdo_fetch('SELECT * FROM ' . tablename('str_print') . ' WHERE uniacid = :aid AND sid = :id and type = :type', array(
                    ':aid' => $_W['uniacid'],
                    ':id' => $order['sid']
                ));//获取门店打印机信息	
        if (empty($order)) {
            message('订单不存在或已删除', $this->createMobileUrl('myorder'), 'error');
        }
        if (!empty($order['pay_type'])) {
            message('该订单已付款或已关闭,正在跳转到我的订单...', $this->createMobileUrl('myorder', array(
                'sid' => $order['sid']
            )), 'info');
        }
        $params['module']  = "str_takeout";
        $params['tid']     = $order['id'];
        $params['ordersn'] = $order['id'];
        $params['user']    = $_W['member']['uid'];
        $params['fee']     = $order['price'] + $order['delivery_fee'];
        $params['title']   = $_W['account']['name'] . "外卖订单{$order['ordersn']}";
        $this->pay($params);
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        if (($params['result'] == 'success' && $params['from'] == 'notify') || ($params['from'] == 'return')) {
            $order = pdo_fetch('SELECT id, sid, pay_type,order_type, table_id FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $params['tid']
            ));
			if(!empty($order['pay_type'])){
				message('这个订单已经支付过了，无需重复支付！', '../../app/' . $this->createMobileUrl('orderdetail', array(
                    'id' => $order['id'],
                    'sid' => $order['sid']
                )), 'success');
			}
			$data['pay_type']   = $params['type'];
            $data['is_usecard'] = intval($params['is_usecard']);
            $data['card_type']  = intval($params['card_type']);
            $data['card_fee']   = $params['card_fee'];
            $data['card_id']    = trim($params['card_id']);
            pdo_update('str_order', $data, array(
                'id' => $params['tid'],
                'uniacid' => $_W['uniacid']
            ));

            if ($data['is_usecard'] == 1) {
                $price = $params['fee'] - $params['card_fee'];
                $note  = "用户付款成功,订单原价{$params['fee']}元,使用优惠券抵消{$price}元,实际支付{$params['card_fee']}元";
            } else {
                $note = "用户付款成功,订单总价{$params['fee']}元";
            }
            set_order_log($order['id'], $sid, $note);
            init_print_order($order['sid'], $order['id'], 'pay');
            init_notice_order($order['sid'], $order['id'], 'order');
			
            if ($order['order_type'] == 1 && $order['table_id'] > 0) {
                pdo_update('str_tables', array(
                    'status' => '4'
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'id' => $order['table_id']
                ));
            }
        }
        if ($params['from'] == 'return') {
            $order = pdo_fetch('SELECT id, sid FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $params['tid']
            ));
			//支付成功后如果后台选择打印支付订单，则在此打印
			$id=$params['tid'];
			$order = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $id
        ));
		$item = pdo_fetch('SELECT * FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
                    ':aid' => $_W['uniacid'],
                    ':id' => $order['sid']
                ));//获取门店信息
		$store_print = pdo_fetch('SELECT * FROM ' . tablename('str_print') . ' WHERE uniacid = :aid AND sid = :id and type = :type', array(
                    ':aid' => $_W['uniacid'],
					':type' => 2,
                    ':id' => $order['sid']
                ));//获取门店打印机信易 福 源 码 网 破 解	
            if ($params['type'] == 'credit' || $params['type'] == 'delivery') {
                message('支付成功！', $this->createMobileUrl('orderdetail', array(
                    'id' => $order['id'],
                    'sid' => $order['sid']
                )), 'success');
            } else {
                message('支付成功！', '../../app/' . $this->createMobileUrl('orderdetail', array(
                    'id' => $order['id'],
                    'sid' => $order['sid']
                )), 'success');
            }
        }
    }
    public function doMobileMyorder()
    {
        global $_W, $_GPC;
        checkauth();
        $sid = intval($_GPC['sid']);
        check_trash($sid);
        $store  = get_store($sid);
        $_share = get_share($store);
        if (empty($store)) {
            message('门店不存在', referer(), 'error');
        }
        $title  = $store['title'];
        $where  = ' WHERE uniacid = :aid AND sid = :sid AND uid = :uid';
        $params = array(
            ':aid' => $_W['uniacid'],
            ':uid' => $_W['member']['uid'],
            ':sid' => $sid
        );
        $status = intval($_GPC['status']);
        if ($status > 0 && $status != 5) {
            $where .= ' AND status = :status';
            $params[':status'] = $status;
        }
        if ($status == 5) {
            $where .= " AND pay_type = ''";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;
        $limit  = ' ORDER BY addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order') . $where, $params);
        $data   = pdo_fetchall('SELECT * FROM ' . tablename('str_order') . $where . $limit, $params);
        $pager  = pagination($total, $pindex, $psize, '', array(
            'before' => 0,
            'after' => 0
        ));
        include $this->template('myorder');
    }
    public function doMobileComment()
    {
        global $_W, $_GPC;
        $id    = intval($_GPC['id']);
        $order = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $id
        ));
        check_trash($order['sid']);
        if (!$_W['isajax']) {
            if (empty($order)) {
                message('订单不存在或已经删除', $this->createMobileUrl('myorder'), 'error');
            }
            if ($order['comment'] == 1) {
                $comment = pdo_fetch('SELECT * FROM ' . tablename('str_order_comment') . ' WHERE uniacid = :aid AND oid = :oid', array(
                    ':aid' => $_W['uniacid'],
                    ':oid' => $id
                ));
            }
        } else {
            $out['errno'] = 0;
            $out['error'] = 0;
            if (empty($order)) {
                $out['errno'] = 1;
                $out['error'] = '订单不存在或已经删除';
                exit(json_encode($out));
            }
            $store = pdo_fetch('SELECT id,comment_set FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':id' => $order['sid']
            ));
            if ($order['comment'] == 1) {
                $out['errno'] = 1;
                $out['error'] = '该订单已经评价过';
                exit(json_encode($out));
            }
            if (!empty($_GPC['score_data'])) {
                $insert = array(
                    'uniacid' => $_W['uniacid'],
                    'sid' => $order['sid'],
                    'oid' => $order['id'],
                    'uid' => $order['uid'],
                    'addtime' => TIMESTAMP,
                    'status' => ($store['comment_set']) == 1 ? 1 : 3,
                    'note' => trim($_GPC['note'])
                );
                foreach ($_GPC['score_data'] as $row) {
                    if ($row['id'] && in_array($row['id'], array(
                        'taste',
                        'speed',
                        'serve'
                    ))) {
                        $score              = intval($row['score']);
                        $insert[$row['id']] = $score;
                    }
                }
                pdo_insert('str_order_comment', $insert);
            }
            pdo_update('str_order', array(
                'comment' => 1
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            exit(json_encode($out));
        }
        include $this->template('comment');
    }
    public function doWebCron()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'print';
        if ($op == 'print') {
            $sid  = intval($_GPC['__sid']);
            $data = pdo_fetchall('SELECT a.foid, b.print_no, b.key FROM ' . tablename('str_order_print') . ' AS a LEFT JOIN ' . tablename('str_print') . ' AS b ON a.pid = b.id WHERE a.uniacid = :aid AND a.sid = :sid AND a.status = 2 AND a.print_type = 1 ORDER BY addtime ASC LIMIT 5', array(
                ':aid' => $_W['uniacid'],
                ':sid' => $sid
            ));
            if (!empty($data)) {
                foreach ($data as $da) {
                    if (!empty($da['foid']) && !empty($da['print_no']) && !empty($da['key'])) {
                        $print  = new wprint();
                        $status = $print->QueryOrderState($da['print_no'], $da['key'], $da['foid']);
                        if (!is_error($status)) {
                            pdo_update('str_order_print', array(
                                'status' => $status
                            ), array(
                                'uniacid' => $_W['uniacid'],
                                'sid' => $sid,
                                'foid' => $da['foid']
                            ));
                        }
                    }
                }
            }
        } elseif ($op == 'order') {
            $sid   = intval($_GPC['__sid']);
            $order = pdo_fetch('SELECT id FROM ' . tablename('str_order') . ' WHERE uniacid = :uniacid AND sid = :sid AND is_notice = 0 ORDER BY addtime DESC', array(
                ':uniacid' => $_W['uniacid'],
                ':sid' => $sid
            ));
            if (!empty($order)) {
                pdo_update('str_order', array(
                    'is_notice' => 1
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'id' => $order['id']
                ));
				
                exit('success');
            }
			
            exit('error');
        }
    }
    public function doWebSystem()
    {
        global $_W, $_GPC;
        include $this->template('system');
    }
    public function doMobileManage()
    {
        global $_W, $_GPC;
        $op    = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        $sid   = $_GPC['sid'];
        $store = pdo_fetch('SELECT * FROM ' . tablename('str_store') . ' WHERE uniacid = :aid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':id' => $sid
        ));
        if (empty($store)) {
            message('门店不存在', referer(), 'error');
        }
        $store['copyright'] = (array) iunserializer($store['copyright']);
        $_share             = get_share($store);
        $title              = $store['title'];
        $clerk              = checkclerk($sid);
        if (is_error($clerk)) {
            message($clerk['message'], referer(), 'error');
        }
        $pay_types = pay_types();
        if ($op == 'list') {
            $where  = ' WHERE uniacid = :aid AND sid = :sid';
            $params = array(
                ':aid' => $_W['uniacid'],
                ':sid' => $sid
            );
            if ($status > 0 && $status != 5) {
                $where .= ' AND status = :status';
                $params['status'] = $status;
            }
            if ($status == 5) {
                $where .= " AND pay_type = ''";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 10;
            $limit  = ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order') . $where, $params);
            $data   = pdo_fetchall('SELECT * FROM ' . tablename('str_order') . $where . $limit, $params);
            $pager  = pagination($total, $pindex, $psize, '', array(
                'before' => 0,
                'after' => 0
            ));
            include $this->template('manage');
        } elseif ($op == 'detail') {
            $id    = intval($_GPC['id']);
            $order = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND sid = :sid AND id = :id', array(
                ':aid' => $_W['uniacid'],
                ':sid' => $store['id'],
                ':id' => $id
            ));
            if (empty($order)) {
                message('订单不存在或已经删除', referer(), 'error');
            }
            $order['dish'] = get_dish($order['id']);
            include $this->template('manage-detail');
        }
    }
    public function doMobileStatus()
    {
        global $_W, $_GPC;
        $id     = intval($_GPC['id']);
        $sid    = intval($_GPC['sid']);
        $status = intval($_GPC['status']);
        $order  = pdo_fetch('SELECT id FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND sid = :sid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':sid' => $sid,
            ':id' => $id
        ));
        if (empty($order)) {
            exit('订单不存在');
        }
        if ($status == 5) {
            pdo_update('str_order', array(
                'pay_type' => 'cash'
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
        } else {
            pdo_update('str_order', array(
                'status' => $status
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
        }
        wechat_notice_order($sid, $id, $status);
        exit('success');
    }
    public function doMobilePrint()
    {
        global $_W, $_GPC;
        $id    = intval($_GPC['id']);
        $sid   = intval($_GPC['sid']);
        $order = pdo_fetch('SELECT id FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND sid = :sid AND id = :id', array(
            ':aid' => $_W['uniacid'],
            ':sid' => $sid,
            ':id' => $id
        ));
        if (empty($order)) {
            exit('订单不存在');
        }
        $status = print_order($id);
        if (is_error($status)) {
            exit($status['message']);
        }
        exit('success');
    }
    public function doMobileAddress()
    {
        global $_W, $_GPC;
        checkauth();
        $sid    = intval($_GPC['sid']);
        $store  = get_store($sid);
        $_share = get_share($store);
        if (empty($store)) {
            message('商家不存在', '', 'error');
        }
        $title      = $store['title'];
        $op         = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        $return_url = '';
        if (!empty($_GPC['return_url'])) {
            $return_url = urldecode($_GPC['return_url']);
        }
        if ($op == 'list') {
            $addresses = get_addresses();
        }
        if ($op == 'post') {
            $id      = intval($_GPC['id']);
            $address = get_address($id);
            if ($_W['ispost']) {
                if ($store['mobile_verify']['takeout_verify'] == 1 && !$address['is_verify'] && $_W['str_takeout']['sms']['status'] == 1) {
                    $code    = trim($_GPC['code']);
                    $mobile  = trim($_GPC['mobile']);
                    $isexist = pdo_fetch('select * from ' . tablename('uni_verifycode') . ' where uniacid = :uniacid and receiver = :receiver and verifycode = :verifycode and createtime >= :createtime', array(
                        ':uniacid' => $_W['uniacid'],
                        ':receiver' => $mobile,
                        ':verifycode' => $code,
                        ':createtime' => time() - 1800
                    ));
                    if (empty($isexist)) {
                        exit(json_encode(array(
                            'errorno' => 1,
                            'message' => '验证码错误'
                        )));
                    }
                }
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $_W['member']['uid'],
                    'realname' => trim($_GPC['realname']),
                    'mobile' => trim($_GPC['mobile']),
                    'is_verify' => 0,
                    'address' => trim($_GPC['address'])
                );
                if ($store['mobile_verify']['takeout_verify'] == 1 && !$address['is_verify']) {
                    $data['is_verify'] = 1;
                }
                if (!empty($address)) {
                    pdo_update('str_address', $data, array(
                        'uniacid' => $_W['uniacid'],
                        'id' => $id
                    ));
                } else {
                    pdo_insert('str_address', $data);
                    $id = pdo_insertid();
                }
                exit(json_encode(array(
                    'errorno' => 0,
                    'message' => $id
                )));
            }
        }
        if ($op == 'del') {
            $id = intval($_GPC['id']);
            pdo_delete('str_address', array(
                'uniacid' => $_W['uniacid'],
                'uid' => $_W['member']['uid'],
                'id' => $id
            ));
            exit(json_encode(array(
                'errorno' => 0,
                'message' => ''
            )));
        }
        if ($op == 'default') {
            $id = intval($_GPC['id']);
            pdo_update('str_address', array(
                'is_default' => 0
            ), array(
                'uniacid' => $_W['uniacid'],
                'uid' => $_W['member']['uid']
            ));
            pdo_update('str_address', array(
                'is_default' => 1
            ), array(
                'uniacid' => $_W['uniacid'],
                'id' => $id
            ));
            exit(json_encode(array(
                'errorno' => 0,
                'message' => ''
            )));
        }
        include $this->template('address');
    }
    public function doMobileComment_list()
    {
        global $_W, $_GPC;
        checkauth();
        $sid = intval($_GPC['sid']);
        check_trash($sid);
        $store  = get_store($sid);
        $_share = get_share($store);
        if (empty($store)) {
            message('商家不存在', '', 'error');
        }
        $title        = $store['title'];
        $comment_stat = comment_stat($sid);
        $avg          = ($comment_stat['avg_taste'] + $comment_stat['avg_serve'] + $comment_stat['avg_speed']) / 3;
        $pindex       = max(1, intval($_GPC['page']));
        $psize        = 15;
        $condition    = ' WHERE a.uniacid = :uniacid AND a.sid = :sid AND a.status = 1';
        $params       = array(
            ':uniacid' => $_W['uniacid'],
            ':sid' => $sid
        );
        $total        = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('str_order_comment') . ' AS a ' . $condition, $params);
        $data         = pdo_fetchall('SELECT a.*, b.nickname,b.avatar,b.realname FROM ' . tablename('str_order_comment') . ' AS a LEFT JOIN ' . tablename('mc_members') . ' AS b ON a.uid = b.uid ' . $condition . ' ORDER BY a.addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
        $pager        = pagination($total, $pindex, $psize, '', array(
            'before' => 0,
            'after' => 0
        ));
        include $this->template('comment_list');
    }
    public function doWebLimit()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        if ($op == 'list') {
            if (!$_W['isfounder']) {
                message('此项操作只有超级管理员有权限', referer(), 'error');
            }
            $title     = intval($_GPC['title']);
            $condition = '';
            if ($title > 0) {
                $condition .= " WHERE uniacid = {$title}";
            } else {
                $title = trim($_GPC['title']);
                if (!empty($title)) {
                    $condition .= " WHERE name LIKE '%{$title}%'";
                }
            }
            $pindex   = max(1, intval($_GPC['page']));
            $psize    = 15;
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('uni_account') . $condition);
            $accounts = pdo_fetchall('SELECT * FROM ' . tablename('uni_account') . $condition . ' ORDER BY uniacid DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(), 'uniacid');
            if (!empty($accounts)) {
                $ids    = implode(',', array_keys($accounts));
                $limits = pdo_fetchall('SELECT uniacid, num_limit FROM ' . tablename('str_config') . " WHERE uniacid IN ({$ids})", array(), 'uniacid');
            }
            $pager = pagination($total, $pindex, $psize);
        }
        if ($op == 'num') {
            if (!$_W['isfounder']) {
                exit('您没有权限进行该操作');
            }
            $uniacid = intval($_GPC['uniacid']);
            if (!$uniacid)
                exit('公众号信息错误');
            $num    = intval($_GPC['num']);
            $config = get_config($uniacid);
            if (!empty($config)) {
                pdo_update('str_config', array(
                    'num_limit' => $num
                ), array(
                    'uniacid' => $uniacid
                ));
            } else {
                $data = array(

                    'uniacid' => $uniacid,
                    'version' => 1,
                    'area_search' => 1,
                    'num_limit' => $num
                );
                pdo_insert('str_config', $data);
            }
            exit('success');
        }
        include $this->template('limit');
    }
    public function doWebArea()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        if ($op == 'list') {
            $condition      = ' uniacid = :aid';
            $params[':aid'] = $_W['uniacid'];
            $lists          = get_area();
            if (!empty($lists)) {
                $ids  = implode(',', array_keys($lists));
                $nums = pdo_fetchall('SELECT count(*) AS num,area_id FROM ' . tablename('str_store') . " WHERE uniacid = :aid AND area_id IN ({$ids}) GROUP BY area_id", array(
                    ':aid' => $_W['uniacid']
                ), 'area_id');
            }
            if (checksubmit('submit')) {
                if (!empty($_GPC['ids'])) {
                    foreach ($_GPC['ids'] as $k => $v) {
                        $data = array(
                            'title' => trim($_GPC['title'][$k]),
                            'displayorder' => intval($_GPC['displayorder'][$k])
                        );
                        pdo_update('str_area', $data, array(
                            'uniacid' => $_W['uniacid'],
                            'id' => intval($v)
                        ));
                    }
                    message('编辑成功', $this->createWebUrl('area'), 'success');
                }
            }
        }
        if ($op == 'post') {
            if (checksubmit('submit')) {
                if (!empty($_GPC['title'])) {
                    foreach ($_GPC['title'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v))
                            continue;
                        $data['uniacid']      = $_W['uniacid'];
                        $data['title']        = $v;
                        $data['displayorder'] = intval($_GPC['displayorder'][$k]);
                        pdo_insert('str_area', $data);
                    }

                }
                message('添加区域成功', $this->createWebUrl('area'), 'success');
            }
        }
        include $this->template('area');
    }
}
