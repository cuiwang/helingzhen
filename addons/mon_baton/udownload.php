<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$bid= intval($_GPC['bid']);
if(empty($bid)){
    message('抱歉，传递的参数错误！','', 'error');              
}


$o_der=$_GPC['o_der'];
if(empty($o_der)) {
	$order=" baton_num asc";
} else {
	if($o_der=="11"){
		$order=" baton_num asc";
	} else if($o_der=="12") {
		$order=" baton_num desc";
	}else if($o_der=="21") {
		$order="baton desc";
	}else if($o_der=="22") {
		$order="baton desc";
	}

}


$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid =:bid   ORDER BY  ".$order, array(":bid"=>$bid));




$tableheader = array('openID', $this->encode("昵称"),$this->encode("姓名"),$this->encode("手机号"),$this->encode("传棒序号"),$this->encode("传棒数"),$this->encode('接棒寄语'),$this->encode('接棒时间' ));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['openid'] . "\t ,";
	 $html .= $this->encode( $value['nickname'] )  . "\t ,";
	$html .= $this->encode( $value['uname'] )  . "\t ,";
	$html .= $this->encode( $value['tel'] )  . "\t ,";
	$html .= $value['baton_num'] . "\t ,";
	$html .= $value['baton'] . "\t ,";
	$html .= $this->encode( $value['speak'] )  . "\t ,";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\n";

}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参加用户数据.xls");

echo $html;
exit();
