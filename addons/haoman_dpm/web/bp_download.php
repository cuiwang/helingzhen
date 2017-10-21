<?php
global $_GPC,$_W;
$rid = intval($_GPC['rid']);

checklogin();
$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_pay_order') . ' where uniacid = :uniacid and rid = :rid  ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));

$tableheader = array('ID','微信名称','姓名','手机号','OPENID','订单号','商户定单号','霸屏/打赏/红包','霸屏/打赏/红包金额(元)','霸屏时间(秒)','霸屏内容','实际金额','手续费','支付方式','支付状态','支付IP','支付时间','是否管理员免支付','创建时间');

$html = "\xEF\xBB\xBF";

foreach ($list as &$row) {

    if($row['status'] == 1){

        $row['status']='未支付';

    }else if($row['status'] == 2){

        $row['status']='已支付';

    }
    else{
        $row['status']='已删除';
    }


    if($row['isadmin'] == 1){

        $row['isadmin']='是';
    }
    else{
        $row['isadmin']='否';
    }



   if($row['orderid']==1){
       $row['orderid'] ="余额";
   }elseif ($row['orderid']==2){
       $row['orderid'] ="微信支付";
   }elseif ($row['orderid']==3){
       $row['orderid'] ="支付宝";
   }elseif ($row['orderid']==0){
       $row['orderid'] ="未付款";
   }else{
       $row['orderid'] ="其他";
   }
    $row['mobile'] = pdo_fetchcolumn("select mobile from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $row['from_user']));
    $row['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $row['from_user']));
//            $lists['address'] = pdo_fetchcolumn("select address from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
    if($row['pay_type']==3){
        $row['bptime']= pdo_fetchcolumn("select ds_time from " . tablename('haoman_dpm_guest') . " where id = :id", array(':id' => $row['pay_addr']));

        $row['message']= pdo_fetchcolumn("select says from " . tablename('haoman_dpm_guest') . " where id = :id", array(':id' => $row['pay_addr']));

    }
    if($row['pay_type'] == 2){

        $row['pay_type']='霸屏';
    }
    elseif($row['pay_type']==3){
        $row['pay_type']='打赏';
    }elseif($row['pay_type']==4){
        $row['pay_type']='红包';
    }else{
        $row['pay_type']='空';
    }
}
unset($row);

foreach ($tableheader as $value) {
    $html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
    $html .= $value['id'] . "\t ,";
    $html .= str_replace('"','',$value['nickname']) . "\t ,";
    if($value['pay_type']!='红包'){
        $html .= $value['from_realname'] . "\t ,";
    }else{
        $html .= "\t ,";
    }

    $html .= $value['mobile'] . "\t ,";
    $html .=  $value['from_user'] . "\t ,";
    $html .=  $value['transid'] . "\t ,";
    $html .=  $value['transaction_id'] . "\t ,";
    $html .=  $value['pay_type'] . "\t ,";
    $html .=  floatval($value['pay_total']) . "\t ,";
    $html .=  $value['bptime'] . "\t ,";
    $html .=  $value['message'] . "\t ,";
    $html .=  $value['pay_addr'] . "\t ,";
    $html .=  $value['from_realname'] . "\t ,";
    $html .=  $value['orderid'] . "\t ,";
    $html .=  $value['status'] . "\t ,";
    $html .=  $value['pay_ip'] . "\t ,";
    if($value['paytime']){
        $html .=  date('Y-m-d H:i:s', $value['paytime']) . "\t ,";
    }else{
        $html .=  $value['paytime']. "\t ,";
    }
    $html .=  $value['isadmin']. "\t ,";
    $html .=  date('Y-m-d H:i:s', $value['createtime']) . "\n ";



}


header("Content-type:text/csv");

header("Content-Disposition:attachment;filename=霸屏记录.csv");

$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

echo $html;
exit();