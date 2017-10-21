<?php
/**
 * 【微赞】房产模块定义
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileQrcode extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            superman_qrcode_png(urldecode($_GPC['content']));
        }
    }
}

$obj = new Creditmall_doMobileQrcode;
$obj->exec();
