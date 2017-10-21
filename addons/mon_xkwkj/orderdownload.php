<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$kid= intval($_GPC['kid']);
if(empty($kid)){
    message('抱歉，传递的参数错误！','', 'error');              
}
$xkwkj=DBUtil::findById(DBUtil::$TABLE_XKWKJ,$kid);


$where = '';
$params = array(
	':kid' => $kid
);

$status = $_GPC['status'];
if ($status != '') {
	$where .= ' and status =:status';
	$params[':status'] = $_GPC['status'];
}

$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid =:kid " . $where . "  ORDER BY createtime DESC ", $params);


$dc = $_GPC['dc'];

$tableheader = array($this->encode("商品", $dc),$this->encode("款式", $dc),$this->encode('用户openID', $dc),$this->encode( "订单编号", $dc),
	$this->encode( "微信支付单号", $dc),$this->encode("收货人", $dc),$this->encode("电话", $dc), $this->encode("邮编", $dc),$this->encode("收货地址", $dc),$this->encode("商品原价", $dc),
	$this->encode("砍后价格", $dc),$this->encode("运费", $dc),$this->encode("支付金额", $dc),$this->encode("状态", $dc),$this->encode("下单时间", $dc),$this->encode("支付时间", $dc));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $this->encode($xkwkj['p_name'], $dc) . "\t ,";
	$html .= $this->encode($value['p_model'], $dc) . "\t ,";
	$html .= $value['openid'] . "\t ,";
	 $html .=$value["order_no"]  . "\t ,";
	$html .=$value["wxorder_no"]  . "\t ,";
	$html .=$this->encode($value["uname"], $dc)  . "\t ,";
	$html .=$value["tel"]  . "\t ,";
	$html .=$value["zipcode"]  . "\t ,";
	$html .=$this->encode($value["address"], $dc)  . "\t ,";
	$html .=$value["y_price"]  . "\t ,";
	$html .=$value["kh_price"]  . "\t ,";
	$html .=$value["yf_price"]  . "\t ,";
	$html .=$value["total_price"]  . "\t ,";
	$html .=$this->encode($this->getStatusText($value["status"]), $dc)  . "\t ,";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\t,";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['notifytime'])) . "\n";

}


header("Content-type:text/csv;charset=UTF-8");
header("Content-Disposition:attachment; filename=订单数据.csv");

echo $html;
exit();
