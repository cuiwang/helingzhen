<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$kid= intval($_GPC['kid']);
if(empty($kid)){
    message('抱歉，传递的参数错误！','', 'error');              
}



$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_WKJ_USER) . " WHERE kid =:kid   ORDER BY createtime DESC ", array(":kid"=>$kid));




$tableheader = array('openID', $this->encode("昵称"),$this->encode('砍后价格'),$this->encode('注册时间' ));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['openid'] . "\t ,";
	 $html .= $this->encode( $value['nickname'] )  . "\t ,";

    $html .= $value['price'] . "\t ,";

	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\n";

}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参加用户数据.xls");

echo $html;
exit();
