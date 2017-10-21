<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileExchangerank extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = $this->_share;
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '兑换排行榜';
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
            );
            $start = 0;
            $pagesize = 20;
            $rank = in_array($_GPC['rank'], array('all', 'week', 'month'))?$_GPC['rank']:'all';
            $orderby = '';
            if ($rank == 'all') {
                $orderby = 'ORDER BY sales DESC';
            } else if ($rank == 'week') {
                $orderby = 'ORDER BY week_sales DESC';
            } else if ($rank == 'month') {
                $orderby = 'ORDER BY month_sales DESC';
            } else {
                $this->message('非法访问', '', 'error');
            }
            $list = superman_product_fetchall($filter,$orderby,$start,$pagesize);
            if ($list) {
                foreach ($list as $k => &$item) {
                    superman_product_set($item);
                    $item['index'] = $k + 1;
                }
                unset($k, $item);
            }
            //print_r($list);

            //ad list
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'time' => TIMESTAMP,
                'position_id' => 2,
            );
            $adlist = superman_ad_fetchall_posid($filter);
            //print_r($adlist);
        }
        include $this->template('exchangerank');
    }
}

$obj = new Creditmall_doMobileExchangerank;
$obj->exec();
