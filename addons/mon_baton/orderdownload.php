<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$kid= intval($_GPC['kid']);
if(empty($kid)){
    message('抱歉，传递的参数错误！','', 'error');              
}


$wkj=DBUtil::findById(DBUtil::$TABLE_WKJ,$kid);

$list= pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_WJK_ORDER)." where kid=:kid order by createtime desc ",array(':kid'=>$kid));



$tableheader = array($this->encode("商品"),$this->encode('用户openID'),$this->encode( "订单编号"),$this->encode( "微信支付单号"),$this->encode("收货人"),$this->encode("电话"),$this->encode("收货地址"),$this->encode("商品原价"),$this->encode("砍后价格"),$this->encode("运费"),$this->encode("支付金额"),$this->encode("状态"),$this->encode("下单时间"),$this->encode("支付时间"));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $this->encode($wkj['p_name']) . "\t ,";
	$html .= $value['openid'] . "\t ,";
	 $html .=$value["order_no"]  . "\t ,";
	$html .=$value["wxorder_no"]  . "\t ,";
	$html .=$this->encode($value["uname"])  . "\t ,";
	$html .=$value["tel"]  . "\t ,";
	$html .=$this->encode($value["address"])  . "\t ,";
	$html .=$value["y_price"]  . "\t ,";
	$html .=$value["kh_price"]  . "\t ,";
	$html .=$value["yf_price"]  . "\t ,";
	$html .=$value["total_price"]  . "\t ,";
	$html .=$this->encode($this->getStatusText($value["status"]))  . "\t ,";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\t";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['notifytime'])) . "\n";

}


header("Content-type:text/csv;charset=UTF-8");
header("Content-Disposition:attachment; filename=订单数据.csv");

echo $html;
exit();
