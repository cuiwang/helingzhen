<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileMycredit extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'morecreditlog'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '我的积分';
            $this->checkauth();
            $credit_type = $_GPC['credit_type'];
            $credit_title = superman_credit_type($credit_type);
            if (!$credit_title){
                $this->message('积分类型不存在', referer(), 'error');
            }
            $sql = 'SELECT num,remark,createtime FROM '.tablename('mc_credits_record').' WHERE uid=:uid AND credittype=:credittype ORDER BY `id` DESC LIMIT 20';
            $params = array(
                ':uid' => $_W['member']['uid'],
                ':credittype' => $credit_type,
            );
            $list = pdo_fetchall($sql,$params);
            if ($list) {
                foreach ($list as &$item) {
                    $item['createtime'] = date('Y-m-d H:i', $item['createtime']);
                    $item['num'] = superman_format_price($item['num']);
                    $item['remark'] = $item['remark']?$item['remark']:'系统操作';
                }
                unset($item);
            }
            //print_r($list);
        } else if ($act == 'morecreditlog') {
            $header_title = '积分明细';
            $this->checkauth();
            $credit_type = trim($_GPC['credit_type']);
            $credit_title = superman_credit_type($credit_type);
            if (!$credit_title){
                $this->message('积分类型不存在', referer(), 'error');
            }

            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 15;
            $start = ($pindex - 1) * $pagesize;

            $sql = 'SELECT num,remark,createtime FROM '.tablename('mc_credits_record').' WHERE uid=:uid AND credittype=:credittype ORDER BY `id` DESC LIMIT '.$start.','.$pagesize;
            $params = array(
                ':uid' => $_W['member']['uid'],
                ':credittype' => $credit_type,
            );
            $list = pdo_fetchall($sql,$params);
            if ($list) {
                foreach ($list as &$item) {
                    $item['createtime'] = date('Y-m-d H:i', $item['createtime']);
                    $item['num'] = superman_format_price($item['num']);
                    $item['remark'] = $item['remark']?$item['remark']:'系统操作';
                }
                unset($item);
            }
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }
        }
        include $this->template('mycredit');
    }
}

$obj = new Creditmall_doMobileMycredit;
$obj->exec();
