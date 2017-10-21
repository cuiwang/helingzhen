<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebStat extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '数据统计';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $type = in_array($_GPC['type'], array('order', 'product', 'credit'))?$_GPC['type']:'order';
            $scroll = intval($_GPC['scroll']);
            $st = $_GPC['datelimit']['start'] ? strtotime($_GPC['datelimit']['start']) : strtotime('-30day');
            $et = $_GPC['datelimit']['end'] ? strtotime($_GPC['datelimit']['end']) : strtotime(date('Y-m-d 23:59:59'));
            $starttime = min($st, $et);
            $endtime = max($st, $et);

            if ($type == 'order') {
                $list = $this->stat_order($starttime, $endtime);
                if ($_W['isajax']) {
                    echo json_encode($list);
                    exit;
                }
            } else if ($type == 'product') {
                $list = $this->stat_product($starttime, $endtime);
                if ($_W['isajax']) {
                    echo json_encode($list);
                    exit;
                }
            } else if ($type == 'credit') {
                $arr = $this->_credit_stat_init();
                $datasets = '';
                foreach ($arr as $k => $v) {
                    $datasets .= "ds.{$k},";
                }
                $datasets = rtrim($datasets, ',');
                $list = $this->stat_credit($starttime, $endtime);
                if ($_W['isajax']) {
                    echo json_encode($list);
                    exit;
                }
            }
            //print_r($list);
        }
        include $this->template('web/stat');
    }

    private function stat_order($starttime, $endtime) {
        global $_W;
        $list = array();
        for ($i = $starttime; $i <= $endtime; $i += (24*3600)) {
            if ($i == $starttime) {          //每日开始时间戳
                $t1 = $i;
            } else {
                $t1 = strtotime(date('Y-m-d 0:0:0', $i));
            }
            $t2 = strtotime(date('Y-m-d 23:59:59', $i));

            //日期
            $list['label'][] = date('m-d', $t1);

            $filter = array(
                'uniacid' => $_W['uniacid'],
                'start_time' => $t1,
                'end_time' => $t2,
            );

            //待支付
            $filter['status'] = 0;
            $count1 = superman_order_count($filter);
            $list['datasets']['flow1'][] = $count1;

            //待发货
            $filter['status'] = 1;
            $count2 = superman_order_count($filter);
            $list['datasets']['flow2'][] = $count2;

            //已发货
            $filter['status'] = 2;
            $count3 = superman_order_count($filter);
            $list['datasets']['flow3'][] = $count3;

            //已收货
            $filter['status'] = 3;
            $count4 = superman_order_count($filter);
            $list['datasets']['flow4'][] = $count4;

            /*//已评价
            $filter['status'] = 4;
            $count5 = superman_order_count($filter);
            $list['datasets']['flow5'][] = $count5;*/
        }
        return $list;
    }

    private function stat_product($starttime, $endtime) {
        global $_W;
        $list = array();
        $pagesize = intval(($endtime - $starttime) / 86400);
        $filter = array(
            'uniacid' => $_W['uniacid'],
        );
        $data = superman_stat_fetchall($filter, '', 0, $pagesize);
        for ($i = $starttime; $i <= $endtime; $i += (24*3600)) {
            if ($i == $starttime) {          //每日开始时间戳
                $t1 = $i;
            } else {
                $t1 = strtotime(date('Y-m-d 0:0:0', $i));
            }
            //$t2 = strtotime(date('Y-m-d 23:59:59', $i));
            $daytime = date('Ymd', $t1);

            //日期
            $list['label'][] = date('m-d', $t1);

            //浏览数
            $list['datasets']['flow1'][] = isset($data[$daytime]['product_views'])?$data[$daytime]['product_views']:0;

            //分享数
            $list['datasets']['flow2'][] = isset($data[$daytime]['product_shares'])?$data[$daytime]['product_shares']:0;

            //评论数
            //$list['datasets']['flow3'][] = isset($data[$daytime]['product_comments'])?$data[$daytime]['product_comments']:0;
        }
        return $list;
    }

    private function stat_credit($starttime, $endtime) {
        global $_W;
        $list = array();
        $arr = $this->_credit_stat_init();

        for ($i = $starttime; $i <= $endtime; $i += (24*3600)) {
            if ($i == $starttime) {          //每日开始时间戳
                $t1 = $i;
            } else {
                $t1 = strtotime(date('Y-m-d 0:0:0', $i));
            }
            $t2 = strtotime(date('Y-m-d 23:59:59', $i));
            $daytime = date('Ymd', $t1);

            //日期
            $list['label'][] = date('m-d', $t1);

            foreach ($arr as $k => $credit) {
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'credittype' => $credit['type'],
                    'start_time' => $t1,
                    'end_time' => $t2
                );
                if ($credit['sign'] > 0) {
                    //充值
                    $filter['num_up'] = 0;
                } else {
                    //消费
                    $filter['num_down'] = 0;
                }
                $list['datasets'][$k][] = abs(superman_credit_sum($filter));
            }
        }
        return $list;
    }

    function _credit_stat_init() {
        $arr = array();
        $all_credits = superman_credit_type();
        $all_colors = array(
            'flow1' => '36,165,222',
            'flow2' => '203,48,48',
            'flow3' => '149,192,0',
            'flow4' => '231,160,23',
            'flow5' => '119,119,119',
            'flow6' => '106,90,205',
            'flow7' => '255,0,255',
            'flow8' => '255,165,0',
            'flow9' => '0,0,205',
            'flow10' => '0,205,0',
        );

        foreach ($all_credits as $k => $v) {
            //过滤未开启积分
            if ($v['enabled'] == 0) {
                unset($all_credits[$k]);
                continue;
            }
            //每个积分分配两个颜色
            for ($i = 0; $i<2; $i++) {
                $key = key($all_colors);
                $value = $all_colors[$key];
                unset($all_colors[$key]);
                $v['sign'] = $i;
                if ($i > 0) {
                    $v['name'] = $v['title'].'充值';
                } else {
                    $v['name'] = $v['title'].'消费';
                }
                $v['type'] = $k;
                $v['color'] = $value;
                $arr[$key] = $v;
            }
        }
        return $arr;
    }
}

$obj = new Creditmall_doWebStat;
$obj->exec();
