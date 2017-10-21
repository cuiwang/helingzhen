<?php
/**
 * 导出数据
 *
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$id= intval($_GPC['id']);
$rid= intval($_GPC['rid']);
$uniacid=$_W['uniacid'];
if(empty($rid) || empty($id)){
    message('抱歉，传递的参数错误！','', 'error');              
}
	$condition .= " AND votetype=0  AND tid = '{$id} '";
    //取得用户详细数据
	$list =  pdo_fetchall('SELECT * FROM ' . tablename($this->tablevotedata) . " WHERE uniacid = '{$uniacid}' AND rid = '{$rid} '  $condition");
		
load()->model('mc');
$tableheader = array('id','用户','openid', 'ip', '投票时间' );
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $mid => $value) {
	$html .= $value['id'] . "\t ,";		
	$html .= htmlspecialchars($value['nickname']). "\t ,";
	$html .= $value['openid'] . "\t ,";	
	$html .= $value['user_ip'] . "\t ,";	
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";	
	$html .= "\n";
}
$html .= "\n";



$now = date('Y-m-d H:i:s', time());

$filename ='用户（'.$id.'）信息'.'_'.$rid.'_'.$now;

header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=".$filename.".csv");

echo $html;
exit();
