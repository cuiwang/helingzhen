<?php
global $_GPC,$_W;
checklogin();
$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_pw') . ' where uniacid = :uniacid and status = 1 ORDER BY id ', array(':uniacid' => $_W['uniacid']));
$tableheader = array('ID','批次','博饼码','适用规则','开始时间','结束时间','剩余数量','创建时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['id'] . "\t ,";
	$html .= $value['pici'] . "\t ,";
	$html .=  $value['title'] . "\t ,";
	$html .=  $value['rulename'] . "\t ,";
	$html .=  date('Y-m-d H:i:s', $value['starttime']) . "\t ,";
	$html .=  date('Y-m-d H:i:s', $value['endtime']) . "\t ,";
	$html .=  $value['num'] . "\t ,";
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";

}


header("Content-type:text/csv");

header("Content-Disposition:attachment;filename=全部博饼码.csv");

$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

echo $html;
exit();