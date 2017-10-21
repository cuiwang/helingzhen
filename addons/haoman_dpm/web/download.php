<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);


checklogin();
$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_fans') . ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));

$tableheader = array('ID','微信名称','OPENID','姓名','手机号','地址','报名','状态','账户余额','时间');
$html = "\xEF\xBB\xBF";

    foreach ($list as &$v){
        if($v['isbaoming']==1){
            $v['isbaoming']="已经报名";
        }else if ($v['isbaoming']==0){
            $v['isbaoming']="已经签到";
        }
        if($v['is_back']==1){
            $v['is_back']="已拉黑";
        }else if ($v['is_back']==0){
            $v['is_back']="正常";
        }
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
	$html .=  $value['address'] . "\t ,";
	$html .=  $value['isbaoming'] . "\t ,";
	$html .=  $value['is_back'] . "\t ,";
	$html .=  $value['totalnum'] . "\t ,";
	$html .=  date('Y-m-d H:i:s', $value['createtime']) . "\n ";



}


header("Content-type:text/csv");

header("Content-Disposition:attachment;filename=粉丝数据.csv");

$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

echo $html;
exit();