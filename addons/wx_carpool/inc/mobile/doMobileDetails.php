<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/26
 * Time: 20:58
 */
require_once(dirname(__FILE__) . "/../../model/order.php");
require_once(dirname(__FILE__) . "/../../model/parConfig.php");

global $_W,$_GPC;
$weid=$_W['uniacid'];//获取当前公众号ID
$abc_id_abc=$_GPC['abc_id_abc'];//获取订单ID
$abc_data_abc=orderModel::getOrder($abc_id_abc);//查询当前订单详情信息
orderModel::AddClick();//点击量加1



$abc_wx_logo_abc=ParConfigModel::getWxLogo();//获取当前微信端LOGO

$abc_wx_name_abc=ParConfigModel::getWxName();//获取当前配置的平台名称

include $this->template('details');