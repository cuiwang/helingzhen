<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2017/1/17
 * Time: 15:10
 */
require_once(dirname(__FILE__) . "/../../model/order.php");
global $_GPC,$_W;
$abc_id_abc=$_GPC['abc_id_abc'];//获取订单ID
$abc_order_abc=orderModel::getOrder($abc_id_abc,$type=0);//根据订单ID获取订单
include $this->template('orderDetails');