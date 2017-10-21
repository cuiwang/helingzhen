<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileExchangelog extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '兑换记录';
            $product_id = intval($_GPC['id']);
            if ($product_id == 0) {
                $this->message('非法请求', '', 'error');
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 15;
            $start = ($pindex - 1) * $pagesize;
            $sql = 'SELECT a.nickname,a.avatar,b.dateline,b.credit,b.credit_type FROM '.tablename('mc_members').' AS a,'.tablename('superman_creditmall_order').' AS b WHERE b.product_id=:product_id AND a.uid=b.uid AND b.status>=:status ORDER BY b.id DESC LIMIT '.$start.','.$pagesize;
            $filter = array(
                ':product_id' => $product_id,
                ':status' => 1,
            );
            $list = pdo_fetchall($sql,$filter);
            if ($list) {
                foreach ($list as &$item) {
                    superman_order_set($item);
                    $item['nickname'] = empty($item['nickname'])?'***':superman_hide_nickname($item['nickname']);
                    $item['avatar'] = tomedia($item['avatar']);
                }
                unset($order);
            }
//            print_r($list);
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }
        }
        include $this->template('exchangelog');
    }
}

$obj = new Creditmall_doMobileExchangelog;
$obj->exec();
