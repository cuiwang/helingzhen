<?php
global $_GPC,$_W;
$rid= intval($_GPC['rid']);

if(empty($rid)){

    message('抱歉，传递的参数错误！','', 'error');

}

checklogin();
$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_tp_log') . " WHERE rid = {$rid}  ORDER BY id DESC ");
foreach ($list as &$row) {
    $row['name'] = pdo_fetchcolumn("select name from " . tablename('haoman_dpm_toupiao') . " where id = :id and rid=:rid", array(':id' => $row['toupiaoip'],':rid'=>$rid));
    $row['number'] = pdo_fetchcolumn("select number from " . tablename('haoman_dpm_toupiao') . " where id = :id and rid=:rid", array(':id' => $row['toupiaoip'],':rid'=>$rid));

    $row['description'] = pdo_fetchcolumn("select description from " . tablename('haoman_dpm_toupiao') . " where id = :id and rid = :rid", array(':id' => $row['toupiaoip'],':rid'=>$rid));

//            if($row['status'] == 1){
//
//                $row['status']='未兑奖';
//
//            }else{
//
//                $row['status']='已兑换';
//
//            }

}

$tableheader = array('序号','投票人','投票编码','标题', '简介','数量','参与时间' );
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
    $html .= $value . "\t ,";
}
$html .= "\n";

foreach ($list as $value) {

    $html .= $value['id'] . "\t ,";

    $html .= $value['nickname'] . "\t ,";
    $html .= $value['number'] . "\t ,";

    $html .= $value['name'] . "\t ,";

    $html .= $value['description'] . "\t ,";

    $html .= $value['viewnum'] . "\t ,";


    $html .= date('Y-m-d H:i:s', $value['visitorstime']) . "\n ";


}


header("Content-type:text/csv");

header("Content-Disposition:attachment;filename=投票记录.csv");

$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

echo $html;
exit();