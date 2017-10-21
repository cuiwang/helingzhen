<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebAccess extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }
    public function exec() {
        global $_W, $_GPC;
        $title = '访问控制';
        $act = in_array($_GPC['act'], array('setting', 'ip', 'uid'))?$_GPC['act']:'setting';
        if ($act == 'setting') {
            $setting = M::t('superman_creditmall_kv')->fetch_value(SUPERMAN_SKEY_ACCESS_SETTING);
            if (checksubmit()) {
                $data = array(
                    'svalue' => iserializer($_GPC['setting']),
                );
                if ($setting) {
                    M::t('superman_creditmall_kv')->update($data, array(
                        'uniacid' => $_W['uniacid'],
                        'skey' => SUPERMAN_SKEY_ACCESS_SETTING,
                    ));
                } else {
                    $data['uniacid'] = $_W['uniacid'];
                    $data['skey'] = SUPERMAN_SKEY_ACCESS_SETTING;
                    M::t('superman_creditmall_kv')->insert($data);
                }
                message('操作成功，跳转中...', referer(), 'success');
            }
        } else if ($act == 'ip') {
            if (checksubmit('btn_batch')) {
                if (!in_array($_GPC['btn_batch'], array('refuse', 'cancel'))) {
                    message('非法请求', referer(), 'error');
                }
                if (!$_GPC['id'] || !is_array($_GPC['id'])) {
                    message('未选择IP', referer(), 'error');
                }
                $ips = array_unique($_GPC['id']);
                $setting = M::t('superman_creditmall_kv')->fetch_value(SUPERMAN_SKEY_ACCESS_SETTING);
                if ($_GPC['btn_batch'] == 'refuse') {   //添加ip
                    if ($setting) { //添加
                        //已被禁止的uid字符串拆分成数组
                        $refund_ips = explode("\r\n", trim($setting['ips']));
                        foreach ($ips as $ip) {
                            if (!in_array($ip, $refund_ips)) {
                                $refund_ips[] = $ip;
                            }
                        }
                        $setting = array(
                            'ips' => implode("\r\n", $refund_ips),
                            'uids' => $setting['uids']
                        );
                        $data = array(
                            'svalue' => iserializer($setting)
                        );
                        M::t('superman_creditmall_kv')->update($data, array(
                            'uniacid' => $_W['uniacid'],
                            'skey' => SUPERMAN_SKEY_ACCESS_SETTING,
                        ));
                    } else {        //新增
                        $setting = array(
                            'uids' => '',
                            'ips' => implode("\r\n", $ips)
                        );
                        $data = array(
                            'svalue' => iserializer($setting)
                        );
                        $data['uniacid'] = $_W['uniacid'];
                        $data['skey'] = SUPERMAN_SKEY_ACCESS_SETTING;
                        M::t('superman_creditmall_kv')->insert($data);
                    }
                } else {    //取消ip禁止
                    if ($setting) { //添加
                        //已被禁止的ip字符串拆分成数组
                        $refund_ips = explode("\r\n", trim($setting['ips']));
                        //在已被禁止的ip中找出所有取消禁止的ip
                        $ips = array_intersect($ips, $refund_ips);
                        if ($ips) {
                            foreach ($ips as $ip) {
                                $k = array_search($ip, $refund_ips);
                                unset($refund_ips[$k]);
                            }
                            ksort($refund_ips);
                            $setting = array(
                                'ips' => implode("\r\n", $refund_ips),
                                'uids' => $setting['uids'],
                            );
                            $data = array(
                                'svalue' => iserializer($setting)
                            );
                            M::t('superman_creditmall_kv')->update($data, array(
                                'uniacid' => $_W['uniacid'],
                                'skey' => SUPERMAN_SKEY_ACCESS_SETTING,
                            ));
                        }
                    }
                }
                message('操作成功，跳转中...', referer(), 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = isset($_GPC['export'])?-1:20;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
                'uniacid' => $_W['uniacid']
            );
            $total = M::t('superman_creditmall_ip_log')->count($filter);
            if ($total) {
                $list = M::t('superman_creditmall_ip_log')->fetchall($filter, '', $start, $pagesize);
                if ($list) {
                    foreach ($list as &$li) {
                        $product = M::t('superman_creditmall_product')->fetch($li['product_id']);
                        $li['product_title'] = isset($product['title'])?$product['title']:'<span style="color: red">【商品已删除】</span>';
                        if ($li['location'] == '') {
                            $li['location'] = superman_get_location_by_ip($li['ip']);
                            if ($li['location']) {
                                M::t('superman_creditmall_ip_log')->update(array('location' => $li['location']), array(
                                    'id' => $li['id']
                                ));
                            }
                        }
                        $li['dateline'] = date('Y-m-d H:i:s', $li['dateline']);
                        unset($li, $product);
                    }
                    $pager = pagination($total, $pindex, $pagesize);
                }
            }
        } else if ($act == 'uid') {
            if (checksubmit('btn_batch')) {
                if (!in_array($_GPC['btn_batch'], array('refuse', 'cancel'))) {
                    message('非法请求', referer(), 'error');
                }
                if (!$_GPC['id'] || !is_array($_GPC['id'])) {
                    message('未选择用户', referer(), 'error');
                }
                $uids = array_unique($_GPC['id']);
                $setting = M::t('superman_creditmall_kv')->fetch_value(SUPERMAN_SKEY_ACCESS_SETTING);
                if ($_GPC['btn_batch'] == 'refuse') {   //添加uid
                    if ($setting) { //添加
                        //已被禁止的uid格式化为数组
                        $refund_uids = explode("\r\n", trim($setting['uids']));
                        foreach ($uids as $uid) {
                            if (!in_array($uid, $refund_uids)) {
                                $refund_uids[] = $uid;
                            }
                        }
                        $setting = array(
                            'ips' => $setting['ips'],
                            'uids' => implode("\r\n", $refund_uids)
                        );
                        $data = array(
                            'svalue' => iserializer($setting)
                        );
                        M::t('superman_creditmall_kv')->update($data, array(
                            'uniacid' => $_W['uniacid'],
                            'skey' => SUPERMAN_SKEY_ACCESS_SETTING,
                        ));
                    } else {        //新增
                        $setting = array(
                            'uids' => implode("\r\n", $uids),
                            'ips' => ''
                        );
                        $data = array(
                            'svalue' => iserializer($setting)
                        );
                        $data['uniacid'] = $_W['uniacid'];
                        $data['skey'] = SUPERMAN_SKEY_ACCESS_SETTING;
                        M::t('superman_creditmall_kv')->insert($data);
                    }
                } else {    //取消uid
                    if ($setting) { //添加
                        //已被禁止的uid格式化为数组
                        $refund_uids = explode("\r\n", trim($setting['uids']));
                        //在已被禁止的uid中找出所有取消禁止的uid
                        $uids = array_intersect($uids, $refund_uids);
                        if ($uids) {
                            foreach ($uids as $uid) {
                                $k = array_search($uid, $refund_uids);
                                unset($refund_uids[$k]);
                            }
                            ksort($refund_uids);
                            $setting = array(
                                'ips' => $setting['ips'],
                                'uids' => implode("\r\n", $refund_uids)
                            );
                            $data = array(
                                'svalue' => iserializer($setting)
                            );
                            M::t('superman_creditmall_kv')->update($data, array(
                                'uniacid' => $_W['uniacid'],
                                'skey' => SUPERMAN_SKEY_ACCESS_SETTING,
                            ));
                        }
                    }
                }
                message('操作成功，跳转中...', referer(), 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = isset($_GPC['export'])?-1:20;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
                'uniacid' => $_W['uniacid']
            );
            $total = M::t('superman_creditmall_order')->count($filter);
            if ($total) {
                $list = M::t('superman_creditmall_order')->fetchall($filter, '', $start, $pagesize);
                if ($list) {
                    foreach ($list as &$li) {
                        $product = M::t('superman_creditmall_product')->fetch($li['product_id']);
                        $li['product_title'] = isset($product['title'])?$product['title']:'<span style="color: red">商品已删除</span>';
                        $user = mc_fetch($li['uid'], array('nickname', 'avatar'));
                        $li['nickname'] = isset($user['nickname'])?$user['nickname']:'';
                        $li['avatar'] = isset($user['avatar'])?$user['avatar']:'';
                        $li['dateline'] = date('Y-m-d H:i:s', $li['dateline']);
                        unset($li, $user, $product);
                    }
                    $pager = pagination($total, $pindex, $pagesize);
                }
            }
//            var_dump($list);
        }
        include $this->template('web/access');
    }
}
$obj = new Creditmall_doWebAccess;
$obj->exec();