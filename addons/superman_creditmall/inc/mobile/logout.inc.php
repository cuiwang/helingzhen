<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileLogout extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        unset($_SESSION);
        session_destroy();
        isetcookie('logout', 1, 60);
        @header('Location: '.$this->createMobileUrl('home'));
        exit;
    }
}

$obj = new Creditmall_doMobileLogout;
$obj->exec();
