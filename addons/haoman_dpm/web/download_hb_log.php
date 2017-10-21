<?php
global $_GPC,$_W;
$rid= intval($_GPC['rid']);

if(empty($rid)){

    message('抱歉，传递的参数错误！','', 'error');

}

checklogin();
$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_hb_award') . " WHERE rid = {$rid}  ORDER BY id DESC ");
foreach ($list as &$row) {
    $row['from_nickname'] = pdo_fetchcolumn("select nickname from " . tablename('haoman_dpm_hb_log') . " where id = :id and rid=:rid", array(':id' => $row['prize'],':rid'=>$rid));
    $row['from_openid'] = pdo_fetchcolumn("select from_user from " . tablename('haoman_dpm_hb_log') . " where id = :id and rid=:rid", array(':id' => $row['prize'],':rid'=>$rid));


            if($row['status'] == 1){

                $row['status']='未兑奖';

            }else{

                $row['status']='已兑换';

            }

}

$tableheader = array('序号','昵称','openid','金额', '状态','发放人昵称','发放人openid','参与时间' );
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
    $html .= $value . "\t ,";
}
$html .= "\n";

foreach ($list as $value) {

    $html .= $value['id'] . "\t ,";

    $html .= $value['nickname'] . "\t ,";
    $html .= $value['from_user'] . "\t ,";

    $html .= $value['credit'] . "\t ,";

    $html .= $value['status'] . "\t ,";

    $html .= $value['from_nickname'] . "\t ,";
    $html .= $value['from_openid'] . "\t ,";


    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\n ";


}


header("Content-type:text/csv");

header("Content-Disposition:attachment;filename=红包领取记录.csv");

$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

echo $html;
exit();