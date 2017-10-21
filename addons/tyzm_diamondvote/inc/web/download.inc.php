<?php
/**
 * 导出数据
 *
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
$this->authorization();
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}
	$Where = "";

	if (!empty($rid)){
		$Where .= " AND `rid` = $rid";		
	}
		//取得用户详细数据
$list = pdo_fetchall('SELECT * FROM '.tablename($this->tablevoteuser).' WHERE uniacid= :uniacid '.$Where.'  ORDER BY `id` DESC, `createtime` ASC', array(':uniacid' => $_W['uniacid']) );
		
load()->model('mc');
$tableheader = array('序号','编号','微信昵称', 'openid','姓名','报名信息','宣言','票数','礼物','审核','参加时间' );
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $mid => $value) {

	
	if($value['status']==1){
		$status="已审核";
	}else{
		$status="待审核";
	}
    $joindata=@unserialize($value['joindata']);
	
	foreach ($joindata as $key => $rom) {
		$join.=$rom['name']."：".$rom['val'].";  ";
	}
	
	//print_r($status);exit;
	$html .= $value['id'] . "\t ,";	
	$html .= $value['noid'] . "\t ,";	
	$html .= htmlspecialchars($value['nickname']). "\t ,";
	$html .= $value['openid']. "\t ,";
	$html .= htmlspecialchars($value['name']) . "\t ,";	
	$html .= $join . "\t ,";
	$html .= htmlspecialchars($value['introduction']) . "\t ,";	
	$html .= $value['votenum'] . "\t ,";	
	$html .= $value['giftcount'] . "\t ,";	
	$html .= $status . "\t ,";
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";	
	$html .= "\n";
	$join="";
}
$html .= "\n";



$now = date('Y-m-d H:i:s', time());

$filename ='用户信息'.'_'.$rid.'_'.$now;

header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=".$filename.".csv");

echo $html;
exit();
