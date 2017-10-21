<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileList extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = $this->_share;
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'share'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $uid = $_W['member']['uid'];
            $typeid = intval($_GPC['type'])?$_GPC['type']:1;
            //$credit_type = isset($_GPC['credit_type'])?$_GPC['credit_type']:'credit1';        //取出筛选条件
            $product_types = superman_product_type();
            $header_title = isset($product_types[$typeid])?$product_types[$typeid]['title']:'商品列表';
            $header_title = superman_is_redpack($typeid)?'微信红包':$header_title;
            $credit_types = superman_credit_type();
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
                'type' => $typeid,
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
            );
            $orderby = ' ORDER BY ';
            $sort = in_array($_GPC['sort'], array('default', 'sales', 'comment', 'creditdown', 'creditup', 'canbuy')) ? $_GPC['sort'] : 'default';
            if ($sort == 'sales') {
                $orderby .= ' `sales` DESC,`dateline` DESC';
            } else if ($sort == 'comment') {
                $orderby .= ' `comment_count` DESC,`dateline` DESC';
            } else if ($sort == 'creditdown') {
                $orderby .= ' `credit` DESC,`dateline` DESC';
            } else if ($sort == 'creditup') {
                $orderby .= ' `credit` ASC,`dateline` DESC';
            } else if ($sort == 'canbuy') {
                if ($_W['member']['uid']) {
                    $mycredit = superman_mycredit($_W['member']['uid']);
                    $filter['credit_max'] = $mycredit['credit1']['value'];
                    $orderby .= ' `credit` DESC,`dateline` DESC';
                }
            } else {
                $orderby .= ' `displayorder` DESC,`dateline` DESC';
            }
            $list = superman_product_fetchall($filter, $orderby, $start, $pagesize);
            if ($list) {
                foreach ($list as &$item) {
                    superman_product_set($item);
                }
                unset($item);
            }

            //print_r($list);
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }

            if (superman_is_redpack($_GPC['type'])) {
                include $this->template('list-redpack');
            } else {
                include $this->template('list');
            }
            exit;
        } else if ($act == 'share') {
            $header_title = '商品列表';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'share_credit' => 0,
                'in_type' => array(1, 2, 3, 4, 7),
            );
            $orderby = '';
            $list = superman_product_fetchall($filter, $orderby, $start, $pagesize);
            if ($list) {
                foreach ($list as &$item) {
                    superman_product_set($item);
                }
                unset($item);
            }

            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }

            include $this->template('list-share');
            exit;
        }
    }
}

$obj = new Creditmall_doMobileList;
$obj->exec();
