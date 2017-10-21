<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$id = intval($_GPC['id']);
$token = $_GPC['token'];



if($token=='del_one'){
    $rule = pdo_fetch("select id from " . tablename('haoman_dpm_xyhm') . " where id = :id ", array(':id' => $id));
    if (empty($rule)) {
        message('抱歉，您要删除的不存在！');
    }
    if(pdo_delete('haoman_dpm_xyhm', array('id' => $rule['id']))){
        message('删除成功！', referer(), 'success');
    }
}elseif($token=='all'){

    $rule = pdo_fetch("select id,rid from " . tablename('haoman_dpm_xyhm') . " where rid = :rid ", array(':rid' => $rid));
    if (empty($rule)) {
        $data = array(
            'success' => 100,
            'msg' => "删除失败",
        );

        echo json_encode($data);
        exit();
    }

    if(pdo_delete('haoman_dpm_xyhm', array('rid' => $rule['rid']))){
        $data = array(
            'success' => 1,
            'msg' => "删除成功",
        );

        echo json_encode($data);
        exit();
    }
}elseif($token=='checks'){
    foreach ($_GPC['idArr'] as $k=>$rid) {
        $rid = intval($rid);
        if ($rid == 0 ||$rid ==1)
            continue;
        $rule = pdo_fetch("select id from " . tablename('haoman_dpm_xyhm') . " where id = :id ", array(':id' => $rid));
        if (empty($rule)) {
            message('抱歉，您要删除的不存在！', '', 'error');
        }
        pdo_delete('haoman_dpm_xyhm', array('id' => $rule['id']));
    }

    $data = array(
        'flag' => 1,
        'msg' => "批量删除成功",
    );

    echo json_encode($data);
}elseif($token=="downloads"){


if(empty($rid)){

    message('抱歉，传递的参数错误！','', 'error');

}

@header("content-Type: text/html; charset=utf-8"); //语言强制
date_default_timezone_set('PRC');//时区设置
//require_once 'medoo.php';
$pagenum=200;//每次200条

$count = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_xyhm')."WHERE rid = {$rid}  ORDER BY id DESC");

//        $count=$database->count('vote_record_memory',array('id[<]'=>2817701));//计算要取得数据总数
$page_count =ceil($count/$pagenum);//计算循环总页数

//echo $page_count;exit;
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="全部幸运号.csv"');
header('Cache-Control: max-age=0');
// 打开PHP文件句柄，php://output 表示直接输出到浏览器
$fp = fopen('php://output', 'a');
// 输出列名信息
$head=array('序号','模式', '号码','批次','中奖时间');
foreach ($head as $i => $v) {
    // CSV的Excel支持GBK编码，一定要转换，否则乱码
    $head[$i] = iconv('utf-8', 'gbk', $v);
}


// 将数据通过fputcsv写到文件句柄
fputcsv($fp, $head);

for($i=0;$i<=$page_count;$i++){
   $data = pdo_fetchall("select * from " . tablename('haoman_dpm_xyhm') . "WHERE rid = {$rid} limit ".($i)*$pagenum.','.$pagenum);
//    $data = pdo_fetchall ("SELECT a.id,a.from_user,a.awardname,a.status,a.createtime,b.realname,b.nickname,b.mobile,b.address FROM " . tablename ( 'haoman_bigwheel_award') . "as a left join" . tablename ('haoman_bigwheel_fans') . "as b on a.from_user=b.from_user WHERE a.rid =b.rid And a.rid = " . $rid . " ORDER BY a.createtime limit ".($i)*$pagenum.','.$pagenum);

    foreach ($data as &$row) {

        if($row['turntable'] == 1){

            $row['turntable']='幸运号';

        }else{

            $row['turntable']='幸运手机号';

        }

    }
unset($row);
    foreach($data as $k=>$val){
        $row=array();//初始化行数据
        $row[0]=iconv('utf-8', 'gbk', $val['id']);
        $row[1]=iconv('utf-8', 'gbk', $val['turntable']);
        $row[2]=iconv('utf-8', 'gbk', ltrim(rtrim($val['number'], ","), ","));
        $row[3]=iconv('utf-8', 'gbk', $val['pici']);
        $row[4]=iconv('utf-8', 'gbk', date('Y-m-d H:i:s', $val['createtime']));
        fputcsv($fp, $row); //按行写入文件
    }

    ob_flush();
    flush();
}


}


//        if (pdo_update('haoman_dpm_pay_order', array('status'=>3), array('id'=>$rule['id']))) {
//            message('删除成功！', referer(), 'success');
//        }