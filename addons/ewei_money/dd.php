<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');
}
$where = 'WHERE rid=:rid and nickname != ""';
$arr = array(':rid'=>$rid);
$list = pdo_fetchall("SELECT * FROM ".tablename('ewei_money_fans').$where." ORDER BY max_score DESC ",$arr);
$tableheader = array('排名', '姓名', '手机', '游戏总分', '领取者微信码', '最高分', '最后游戏时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
$conut = 0;
foreach ($list as $value) {
	$conut++;
	$html .= $conut . "\t ,";
	$html .= $value['nickname'] . "\t ,";
	$html .= $value['mobile'] . "\t ,";
	$html .= $value['sum'] . "\t ,";
	$html .= $value['from_user'] . "\t ,";
	$html .= $value['max_score'] . "\t ,";
	$html .= date('Y-m-d H:i:s', $value['lasttime']) ."\n";
}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=排行榜.csv");

echo $html;
exit();
