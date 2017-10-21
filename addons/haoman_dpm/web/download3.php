<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);

checklogin();
$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_cash') . ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));
$tableheader = array('ID','微信名称','OPENID','姓名','手机号','提现金额(元)','到账金额(元)','提现IP','提现时间','状态');
$html = "\xEF\xBB\xBF";

foreach ($list as &$row) {

	if($row['status'] == 1){
        $row['money'] =$row['awardname'];
		$row['status']='同意';

	}else if($row['status'] == 2){

		$row['status']='拒绝';

	}
	else{
	    if($row['credit']>0){
            $row['money'] =$row['credit'];
        }
		$row['status']='申请中';
	}

}
foreach ($list as &$lists) {
	$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
}
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['id'] . "\t ,";
	$html .= str_replace('"','',$value['nickname']) . "\t ,";
	$html .=  $value['from_user'] . "\t ,";
	$html .=  $value['realname'] . "\t ,";
	$html .=  $value['mobile'] . "\t ,";
	$html .=  $value['awardname']/100 . "\t ,";
	$html .=  $value['money']/100 . "\t ,";
	$html .=  $value['awardsimg'] . "\t ,";
	$html .=  date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
	$html .=  $value['status'] . "\n ";


}


header("Content-type:text/csv");

header("Content-Disposition:attachment;filename=提现记录.csv");

$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

echo $html;
exit();