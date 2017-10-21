<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2017/1/17
 * Time: 14:38
 */
require_once(dirname(__FILE__) . "/../../model/order.php");
global $_W,$_GPC;
$op=$_GPC['op'];//获取操作类型
$abc_id_abc=$_GPC['abc_id_abc'];//获取订单ID

if ($op=='delete'){
    if (orderModel::delUserOrder($abc_id_abc)){
        message('操作成功','../web/'.$this->createWebUrl('manager4'));

    }
}else if ($op=='recovery'){
   if ( orderModel::recoveryUserOrder($abc_id_abc)){
       message('操作成功','../web/'.$this->createWebUrl('manager4'));
   }
}