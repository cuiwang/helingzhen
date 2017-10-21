<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebCreditmanage extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        @header('Location: '.url('mc/member'));
        exit;
    }
}

$obj = new Creditmall_doWebCreditmanage;
$obj->exec();
