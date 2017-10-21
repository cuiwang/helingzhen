<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);

@header("content-Type: text/html; charset=utf-8"); //语言强制
date_default_timezone_set('PRC');//时区设置
//require_once 'medoo.php';
$pagenum = 200;//每次200条

$count = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_award'). ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));

//        $count=$database->count('vote_record_memory',array('id[<]'=>2817701));//计算要取得数据总数
$page_count = ceil($count / $pagenum);//计算循环总页数
//echo $page_count;exit;
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="中奖记录.csv"');
header('Cache-Control: max-age=0');
// 打开PHP文件句柄，php://output 表示直接输出到浏览器
$fp = fopen('php://output', 'a');
// 输出列名信息
$head = array('ID','微信名称','姓名','OPENID','抽奖OR抢红包','奖品名称','奖品类型','红包金额','手机号','公司名称/部门/职位','中奖时间','状态');

foreach ($head as $i => $v) {
    // CSV的Excel支持GBK编码，一定要转换，否则乱码
    $head[$i] = iconv('utf-8', 'gbk', $v);
}
// 将数据通过fputcsv写到文件句柄
fputcsv($fp, $head);

for($i=0;$i<$page_count;$i++){
    $data = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where uniacid = '".$_W['uniacid']."' and rid = '".$rid."' ORDER BY id desc limit ".($i)*$pagenum.','.$pagenum);

//            $strsql="select * from vote_record_memory where id<2817701 limit ".($i)*$pagenum.','.$pagenum;
//            $data=$database->query($strsql)->fetchAll();
    foreach ($data as &$row) {

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
    foreach ($data as &$lists) {
        $lists['nickname'] = pdo_fetchcolumn("select nickname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
        $lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
        $lists['address'] = pdo_fetchcolumn("select address from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
    }
    foreach($data as $k=>$val){
        $row=array();//初始化行数据

        $row[0]=iconv('utf-8', 'gbk', $val['id']);
        $row[1]=iconv('utf-8', 'gbk', $val['nickname']=$this->strFilter($val['nickname']));
        $row[2]=iconv('utf-8', 'gbk', $val['realname']);
        $row[3]=iconv('utf-8', 'gbk', $val['from_user']);
        $row[4]=iconv('utf-8', 'gbk', $val['turntable']);
        $row[5]=iconv('utf-8', 'gbk', $val['awardname']);
        $row[6]=iconv('utf-8', 'gbk', $val['prizetype']);
        $row[7]=iconv('utf-8', 'gbk', $val['credit']/100);
        $row[8]=iconv('utf-8', 'gbk', $val['mobile']);
        $row[9]=iconv('utf-8', 'gbk', $val['address']);
        $row[10]=iconv('utf-8', 'gbk', date('Y-m-d H:i:s', $val['createtime']));
        $row[11]=iconv('utf-8', 'gbk', $val['status']);
        fputcsv($fp,$row); //按行写入文件
    }

    ob_flush();
    flush();
}