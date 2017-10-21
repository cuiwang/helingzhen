<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2017/1/6
 * Time: 16:17
 */

require_once(dirname(__FILE__) . "/../../model/pay.php");
global $_GPC,$_W;
load()->func('tpl');            //调用模板

$abc_start_abc=$_GPC['time']['start'];//获取查询开始时间
$abc_end_abc=$_GPC['time']['end'];//获取查询结束时间

$abc_count_abc=payModel::getPayCount($abc_start_abc,$abc_end_abc);
$abc_pages_abc=intval($abc_count_abc['count']/10)+1; //根据总数计算页数
$p=1;       //设置分页初始值
if(isset($_GPC['p'])){
    if (intval($_GPC['p'])>0){
        $p=$_GPC['p'];
    }
}

$abc_payList_abc=payModel::getPayPaging($abc_start_abc,$abc_end_abc);
include $this->template('payment');