<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');
}
$params = array(':rid'=>$rid);
$list = pdo_fetchall("SELECT a.*,f.nickname,f.mobile FROM " . tablename('ewei_money_award') . " a left join " . tablename('ewei_money_fans') . " f on a.from_user=f.from_user WHERE a.rid = :rid " . $where . " ORDER BY a.status DESC ", $params);
$tableheader = array('序号', 'SN码', '奖品名称', '状态', '手机号', '姓名', '微信码', '中奖时间', '兑奖时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
$conut = 0;
foreach ($list as $value) {
	$conut++;
	$html .= $conut . "\t ,";
	$html .= $value['award_sn'] . "\t ,";
	$html .= $value['name'] . "\t ,";
	$html .= $value['status'] == 0?未领取. "\t ,":已兑奖 . "\t ,";
	$html .= $value['mobile'] . "\t ,";
	$html .= $value['nickname'] . "\t ,";
	$html .= $value['from_user'] . "\t ,";
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
	$html .= $value['consumetime'] == 0?未兑奖. "\n":date('Y-m-d H:i:s', $value['consumetime']) . "\n";
}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=现金劵中奖名单.csv");

echo $html;
exit();
