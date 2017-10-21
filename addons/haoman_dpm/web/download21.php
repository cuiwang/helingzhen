<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);

checklogin();
$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_award') . ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));
$tableheader = array('ID','微信名称','姓名','OPENID','抽奖OR抢红包','奖品名称','奖品类型','红包金额','姓名','手机号','地址','中奖时间','状态');
$html = "\xEF\xBB\xBF";

foreach ($list as &$row) {

	if($row['status'] == 1){

		$row['status']='未兑奖';

	}else if($row['status'] == 2){

		$row['status']='已兑奖';

	}
	else{
		$row['status']='不知道';
	}
	if($row['turntable'] == 1){

        $row['turntable'] ='抽奖';
    }
    if($row['turntable'] == 2){

        $row['turntable'] ='抢红包';
    }
    if($row['turntable'] == 3){

        $row['turntable'] ='大转盘';
    }
    if($row['prizetype'] == 1){

        $row['prizetype']='卡券';

    }else if($row['prizetype'] == 2){

        $row['prizetype']='实物';

    }
    else{
        $row['prizetype']='红包';
    }
}
foreach ($list as &$lists) {
	$lists['nickname'] = pdo_fetchcolumn("select nickname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
	$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
	$lists['address'] = pdo_fetchcolumn("select address from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
}
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {

	$html .= $value['id'] . "\t ,";
	$html .= str_replace('"','',$value['nickname']) . "\t ,";
	$html .= str_replace('"','',$value['realname']) . "\t ,";
//			$html .= $value['realname'] . "\t ,";
	$html .=  $value['from_user'] . "\t ,";
	$html .=  $value['turntable'] . "\t ,";
	$html .=  $value['awardname'] . "\t ,";
	$html .=  $value['prizetype'] . "\t ,";
	$html .=  $value['credit']/100 . "\t ,";
	$html .=  $value['realname'] . "\t ,";
	$html .=  $value['mobile'] . "\t ,";
	$html .=  $value['address'] . "\t ,";
	$html .=  date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
	$html .=  $value['status'] . "\n ";


}


header("Content-type:text/csv");

header("Content-Disposition:attachment;filename=中奖记录.csv");

$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

echo $html;
exit();