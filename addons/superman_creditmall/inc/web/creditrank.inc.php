<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebCreditrank extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '积分排行榜';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $credits = superman_credit_type();
            $credit_type = $_GPC['credit_type']?$_GPC['credit_type']:'credit1';
            $_GPC['credit_type'] = $credit_type;
            $credit_name = superman_credit_type($credit_type);
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;
            $condition = ' WHERE `uniacid`=:uniacid';
            $uid = $_GPC['uid'];
            if ($uid != '') {
                if (is_numeric($uid)) {
                    $condition .= " AND uid = '{$uid}'";
                } else {
                    $condition .= " AND nickname LIKE '%{$uid}%'";
                }
            }
            $sql = "SELECT `uid`,`nickname`,`$credit_type`,`avatar` FROM ".tablename('mc_members').$condition." ORDER BY `$credit_type` DESC,uid ASC LIMIT $start, $pagesize";
            $params = array(
                ':uniacid' => $_W['uniacid'],
            );
            $list = pdo_fetchall($sql, $params);
            if ($list) {
                foreach ($list as $k=>&$item) {
                    $item['index'] = $k + 1 + $start;
                    $item[$credit_type] = superman_format_price($item[$credit_type]);
                }
                unset($item);
            }
            $sql = "SELECT COUNT(*) FROM ".tablename('mc_members').$condition;
            $total = pdo_fetchcolumn($sql,$params);
            $pager = pagination($total, $pindex, $pagesize);
        }
        include $this->template('web/creditrank');
    }
}

$obj = new Creditmall_doWebCreditrank;
$obj->exec();
