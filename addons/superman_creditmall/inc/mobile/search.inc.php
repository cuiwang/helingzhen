<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileSearch extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
        $this->checkauth();
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        $uid = $_W['member']['uid'];
        if ($act == 'display') {
            $header_title = '搜索结果';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            $kw = $_GPC['kw'];
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'type' => array(1, 2, 7, 8),
                'isshow' => 1,
            );
            if ($kw) {
                $filter['title'] = '# LIKE "%'.$kw.'%"';
            }
            $list = M::t('superman_creditmall_product')->fetchall($filter, '', $start, $pagesize);
            if ($list) {
                foreach ($list as &$item) {
                    superman_product_set($item);
                }
                unset($item);
            }
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }
        }
        include $this->template('search');
    }
}

$obj = new Creditmall_doMobileSearch;
$obj->exec();
