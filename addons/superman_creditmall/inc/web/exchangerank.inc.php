<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebExchangerank extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $act = in_array($_GPC['act'], array('display','new','month','week'))?$_GPC['act']:'display';
        $pindex = max(1, intval($_GPC['page']));
        $pagesize = 20;
        $start = ($pindex - 1) * $pagesize;
        if ($act == 'display') {
            $title = '总排行榜';
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
            );
            $section = $_GPC['section'];
            $orderby = 'ORDER BY sales DESC';
            if ( $section && in_array($section,array('week','month'))) {
                if ($section == 'week') {
                    $orderby = 'ORDER BY week_sales DESC';
                } else if ($section == 'month') {
                    $orderby = 'ORDER BY month_sales DESC';
                }
            }

            $list = superman_product_fetchall($filter,$orderby,$start,$pagesize);
            if ($list) {
                foreach ($list as $key => &$item) {
                    superman_product_set($item);
                    $item['index'] = $key + 1;
                }
                unset($item);
            }
            //print_r($list);
            $total = superman_product_count($filter);
            $pager = pagination($total, $pindex, $pagesize);
        }
        include $this->template('web/exchangerank');
    }
}

$obj = new Creditmall_doWebExchangerank;
$obj->exec();
