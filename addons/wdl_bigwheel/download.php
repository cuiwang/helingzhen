<?php
if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');
global $_GPC,$_W;

$rid= intval($_GPC['rid']);
$starttime = $_GPC['starttime'];
$endtime = $_GPC['endtime'];
$starttime = strtotime($starttime);
$endtime = strtotime($endtime);
if(empty($rid)){
	message('抱歉，传递的参数错误！','', 'error');
}
$list = pdo_fetchall("SELECT a.*,b.tel FROM ".tablename('bigwheel_award')." as a LEFT JOIN ".tablename('bigwheel_fans')." as b on a.rid=b.rid and a.from_user=b.from_user "
		."WHERE a.rid = :rid and a.weid=:weid and :starttime < a.createtime and a.createtime > :endtime ORDER BY a.id DESC" , array(':rid' => $rid,':weid'=>$_W['weid'], ':starttime' => $starttime, ':endtime' => $endtime));

foreach ($list as &$row) {
	if($row['status'] == 0){
		$row['status']='未领取';
	}elseif($row['status'] == 1){
		$row['status']='已中奖';
	}else{
		$row['status']='已兑奖';
	}
}
$tableheader = array('ID', 'sn码', '奖项', '奖品名称', '状态', '领取者手机号', '中奖者微信码', '中奖时间', '使用时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['id'] . "\t ,";
	$html .= $value['award_sn'] . "\t ,";
	$html .= $value['name'] . "\t ,";
	$html .= $value['description'] . "\t ,";
	$html .= $value['status'] . "\t ,";
	$html .= $value['tel'] . "\t ,";
	$html .= $value['from_user'] . "\t ,";
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
	$html .=($value['consumetime'] !== '' ? date('Y-m-d H:i:s',$value['consumetime']) :'未使用' ) . "\n";
}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部数据.csv");
echo $html;
exit();
