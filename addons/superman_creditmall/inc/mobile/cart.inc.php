<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileCart extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
        $this->checkauth();
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '购物车';
            //TODO
        }
        include $this->template('cart');
    }
}

$obj = new Creditmall_doMobileCart;
$obj->exec();
