<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileDetail extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title =  '积分商城';
        $act = in_array($_GPC['act'], array('display', 'view', 'share'))?$_GPC['act']:'display';
        $id = intval($_GPC['id']);
        if ($act == 'display') {
            $header_title = '商品详情';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            if (!$id) {
                $this->message('非法请求！', referer(), 'warning');
            }
            $product = superman_product_fetch($id);
            if (!$product) {
                $this->message('数据不存在或已删除！', referer(), 'error');
            }
            //必须在superman_product_set函数前调用，因为需要cover变量的相对路径
            $share_imgurl = $product['cover'];
            if ($product['cover']) {
                //调用封面缩略图
                $attachment_path = superman_attachment_root();
                $img_path = superman_cover_share_filename($product['cover']);
                if (file_exists($attachment_path.$img_path)) {
                    $share_imgurl = tomedia($img_path);
                } else {
                    $share_imgurl = tomedia($product['cover']);
                }
            }
            superman_product_set($product);
            $product['group'] = superman_mc_groups_fetch($product['groupid']);

            //分享赚积分
            if (isset($_GPC['fromuid']) && $product['share_credit'] > 0 && $_W['container'] == 'wechat') {
                $this->update_share($_GPC['fromuid'], $product);
            }
            //当前会员积分
            if ($_W['member']['uid']) {
                $mycredit = superman_mycredit($_W['member']['uid'], $product['credit_type'], true);
            }
            $exchange_list = array();
            switch ($product['type']) {
                case 1: //一口价
                    $subscribe = $this->init_subscribe_variable();
                    $exchange_list = $this->exchange_record($id, $start, $pagesize);
                    break;
                case 2: //竞拍
                    $exchange_list = $this->auction_record($id, $start, $pagesize);
                    break;
                case 3: //猜价
                    //TODO
                    break;
                case 4: //抽奖
                    //TODO
                    break;
                case 5: //微信红包
                    break;
                //case 6: //运气红包
                    //break;
                case 7: //秒杀
                    $subscribe = $this->init_subscribe_variable();
                    $exchange_list = $this->exchange_record($id, $start, $pagesize);
                    break;
                case 8: //优惠券
                    $exchange_list = $this->exchange_record($id, $start, $pagesize);
                    break;
            }
            //print_r($exchange_list);
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($exchange_list));
            }
            //设置分享参数
            $share_title = $product['title'];
            $share_desc = str_replace('&nbsp;', '', cutstr(strip_tags(htmlspecialchars_decode($product['description'])), 60));
            if (isset($product['extend']['share']['title']) && $product['extend']['share']['title'] != '') {
                $share_title = $product['extend']['share']['title'];
            }
            if (isset($product['extend']['share']['imgurl']) && $product['extend']['share']['imgurl'] != '') {
                $share_imgurl = tomedia($product['extend']['share']['imgurl']);
            }
            if (isset($product['extend']['share']['desc']) && $product['extend']['share']['desc'] != '') {
                $share_desc = $product['extend']['share']['desc'];
            }

            $_share = array(
                'title' => $share_title,
                'desc' => $share_desc,
                'link' => $_W['siteroot'].'app/'.$this->createMobileUrl('detail', array('id' => $id, 'fromuid' => $_W['member']['uid'])),
                'imgUrl' => $share_imgurl,
            );
            if (superman_is_redpack($product['type'])) {
                include $this->template('detail-redpack');
            } else {
                include $this->template('detail');
            }
        } else if ($act == 'view') {
            if ($_W['container'] == 'wechat') {
                $key = '_'.md5('_product_view_'.$id.'_superman_creditmall_view');
                $value = 'yes';
                if (!isset($_GPC[$key]) || $_GPC[$key] != $value) {
                    $ret = superman_product_update_count($id, 'view_count');
                    if ($ret) {
                        $expire_time = strtotime(date('Y-m-d 23:59:59')) - TIMESTAMP;
                        $expire_time = $expire_time>=3600?$expire_time:3600;
                        isetcookie($key, $value, $expire_time);
                    }
                    superman_stat_update_count(date('Ymd'), 'product_views');
                }
            }
            exit();
        } else if ($act == 'share') {
            if ($_W['container'] == 'wechat') {
                superman_product_update_count($id, 'share_count');
                superman_stat_update_count(date('Ymd'), 'product_shares');
            }
            exit();
        }
    }

    private function exchange_record($id, $start, $pagesize) {
        $list = array();
        $sql = 'SELECT a.nickname,a.avatar,b.dateline,b.credit,b.credit_type FROM '.tablename('mc_members');
        $sql .= ' AS a,'.tablename('superman_creditmall_order').' AS b ';
        $sql .= ' WHERE b.product_id=:id AND a.uid=b.uid AND b.status>=:status';
        $sql .= ' ORDER BY b.id DESC LIMIT '.$start.','.$pagesize;
        $params = array(
            ':id' => $id,
            ':status' => 1,
        );
        $list = pdo_fetchall($sql, $params);
        if ($list) {
            foreach ($list as &$item) {
                superman_order_set($item);
                $item['nickname'] = superman_hide_nickname($item['nickname']);
                $item['avatar'] = tomedia($item['avatar']);
            }
            unset($item);
        }
        return $list;
    }

    private function auction_record($id, $start, $pagesize) {
        $list = array();
        $sql = 'SELECT a.nickname,a.avatar,b.uid,b.dateline,b.credit,b.credit_type,b.millisecond,b.status FROM '.tablename('mc_members');
        $sql .= ' AS a,'.tablename('superman_creditmall_product_log').' AS b ';
        $sql .= ' WHERE a.uid=b.uid AND b.product_id=:id ';
        $sql .= ' ORDER BY b.id DESC LIMIT '.$start.','.$pagesize;
        $params = array(
            ':id' => $id,
        );
        $list = pdo_fetchall($sql, $params);
        if ($list) {
            foreach ($list as &$item) {
                superman_product_log_set($item);
                //$item['nickname'] = superman_hide_nickname($item['nickname']);
                $item['avatar'] = tomedia($item['avatar']);
            }
            unset($item);
        }
        //print_r($list);
        return $list;
    }

    //更新分享数据
    private function update_share($fromuid, $product) {
        global $_W, $_GPC;
        $share_key = '_'.md5('_product_share_'.$fromuid.'_'.$product['id'].'superman_creditmall_share');
        $share_value = 'yes';
        if (isset($_GPC[$share_key]) && $_GPC[$share_key] == $share_value) {
            return;
        }
        $friend_uid = 0;
        $member = mc_fetch($fromuid, array('nickname'));
        if ($member) {
            //获取任务数据
            $task = superman_task_fetch_name('superman_creditmall_task5', $_W['uniacid']);
            if (!$task) {
                return;
            }
            superman_task_set($task);

            //检查是否领取任务
            $mytask = superman_mytask_fetch_uid($fromuid, $task['id']);
            if (!$mytask) {
                return;
            }

            //每天领取分享积分上限
            if (isset($task['extend']['credit_limit']) && $task['extend']['credit_limit'] > 0) {
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $fromuid,
                    'starttime' => strtotime(date('Y-m-d 0:0:0', TIMESTAMP)),
                    'endtime' => strtotime(date('Y-m-d 23:59:59', TIMESTAMP)),
                );
                $credit_total = superman_prodcut_share_sum($filter);
                if ($credit_total > 0 && $credit_total + $product['share_credit'] > $task['extend']['credit_limit']) {
                    return;
                }
            }
            if ($_W['member']['uid']) { //会员
                $friend_uid = $_W['member']['uid'];
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $fromuid,
                    'product_id' => $product['id'],
                    'friend_uid' => $friend_uid,
                );
                $list = superman_product_share_fetchall($filter, '', 0, 1);
                if ($list) {   //存在分享记录
                    return;
                }
            } else {
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $fromuid,
                    'product_id' => $product['id'],
                    'ip' => $_W['clientip'],
                );
                $list = superman_product_share_fetchall($filter, '', 0, 1);
                if ($list && count($list) >= 3) {   //存在分享记录
                    return;
                }
            }
            //记录分享数据
            $data = array(
                'uniacid' => $_W['uniacid'],
                'uid' => $fromuid,
                'product_id' => $product['id'],
                'friend_uid' => $friend_uid,
                'ip' => $_W['clientip'],
                'credit_type' => $product['share_credit_type'],
                'credit' => $product['share_credit'],
                'dateline' => TIMESTAMP,
            );
            $new_id = superman_product_share_insert($data);
            if ($new_id) {
                //增加积分
                $log = array(
                    $friend_uid,    //记录积分来源
                    "分享商品(id={$product['id']})奖励积分",
                    'superman_creditmall',
                );
                $ret = mc_credit_update($fromuid, $product['share_credit_type'], $product['share_credit'], $log);
                if (is_error($ret)) {
                    WeUtility::logging('fatal', '更新积分失败, result='.var_export($ret, true));
                    return;
                }

                //记录cookie
                $expire_time = 30*365*86400;
                isetcookie($share_key, $share_value, $expire_time);
            }
        }
    }
}

$obj = new Creditmall_doMobileDetail;
$obj->exec();
