
<?php
/**
 * Created by PhpStorm.
 * User: start
 * Date: 2016/12/25
 * Time: 17:15
 */

/**
 * 信息管理
 */
require_once(dirname(__FILE__) . "/../../model/order.php");
global $_GPC,$_W;


$abc_order_type_abc=-1; /*默认订单类型为全部*/
if(intval($_GPC['abc_order_type_abc'])>0){
    $abc_order_type_abc=intval($_GPC['abc_order_type_abc']);
}

$orderList=  orderModel::getOrderListByPage();
$orderCount= orderModel::getListCount();
$pages=intval($orderCount/10)+1;

$p=1;
if(isset($_GPC['p'])){

    if(intval($_GPC['p'])>0){
        $p=intval($_GPC['p']);
    }
}


include $this->template('info');