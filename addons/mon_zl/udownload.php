<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$zid=$_GPC['zid'];
$ord = $_GPC['ord'];
if ($ord=='') {
	$orderStr ="createtime desc";
}
if($ord == 1) {
	$orderStr ="createtime desc";
}
if($ord == 2) {
	$orderStr ="createtime asc";
}

if($ord == 3) {
	$orderStr ="point desc";
}
if($ord == 4) {
	$orderStr ="point asc";
}
$dc = $_GPC['dc'];
$where='';
$params = array();
$params[':zid'] =$zid;

$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_ZL_USER). " WHERE zid =:zid ".$where." ORDER BY ".$orderStr, $params);


$tableheader = array($this->encode("openid",$dc), $this->encode("昵称",$dc),$this->encode("姓名",$dc),$this->encode("手机号",$dc),$this->encode('点数',$dc ),$this->encode('助力次数',$dc ), $this->encode('是否被拉黑',$dc),$this->encode('IP',$dc),$this->encode('参与时间',$dc ));
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['openid'] . "\t ,";
	 $html .= $this->encode( $value['nickname'],$dc )  . "\t ,";
	$html .= $this->encode( $value['uname'],$dc )  . "\t ,";
	$html .= $this->encode( $value['tel'],$dc )  . "\t ,";
	$html .= $this->encode( $value['point'],$dc )  . "\t ,";
	$html .= $this->encode( $value['ptime'] ,$dc)  . "\t ,";
    if ($value['isblack'] == 1) {
		$html .= $this->encode("已拉黑" ,$dc)  . "\t ,";
	} else {
		$html .= $this->encode("正常" ,$dc)  . "\t ,";
	}
	$html .= $this->encode( $value['ptime'] ,$dc)  . "\t ,";
	$html .= $this->encode( $value['ip'] ,$dc)  . "\t ,";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\n";

}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参加用户数据.xls");
echo $html;
exit();
