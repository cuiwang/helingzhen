<?php
defined('IN_IA') or exit('Access Denied');
load()->model("account");
load()->model("mc");
load()->func('cache');
load()->func('communication');
function GetPayResult()
   {
        global $_W, $_GPC;
        $order_no = $_GPC["order_no"];
        $order    = pdo_fetch("SELECT * FROM " . tablename("buymod_payrecords") . " WHERE orderid=:orderid", array(
            ":orderid" => $order_no
        ));
        if (empty($order)) {
            message("订单不存在!", $this->createWebUrl("User"));
        }
        if ($order["status"] <> 1) {
            message("订单待支付状态，如果支付成功请与客服联系!", url("member/muser"));
        }
        if ($order["status"] == 1) {
            message("订单支付成功!", url("member/muser"));
            exit;
        }
    }
define ('YUNPAY_ROOT', "payment" . DIRECTORY_SEPARATOR . "yunpay");
