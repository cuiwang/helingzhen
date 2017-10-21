<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation=='deleteall_mess'){
    if(pdo_delete('haoman_dpm_messages', array('rid' => $rid))){
        message('删除成功！', referer(), 'success');
    }

}elseif ($operation=='download_messags'){

    $list = pdo_fetchall('select * from ' . tablename('haoman_dpm_messages') . ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));
    $tableheader = array('ID','微信名称','OPENID','消息内容','时间');
    $html = "\xEF\xBB\xBF";

//    foreach ($list as &$v){
//        if($v['isbaoming']==1){
//            $v['isbaoming']="已经报名";
//        }else if ($v['isbaoming']==0){
//            $v['isbaoming']="已经签到";
//        }
//        if($v['is_back']==1){
//            $v['is_back']="已拉黑";
//        }else if ($v['is_back']==0){
//            $v['is_back']="正常";
//        }
//    }
    foreach ($tableheader as $value) {
        $html .= $value . "\t ,";
    }
    $html .= "\n";
    foreach ($list as $value) {
        $html .= $value['id'] . "\t ,";
        $html .= str_replace('"','',$value['nickname']) . "\t ,";
        $html .=  $value['from_user'] . "\t ,";
        $html .=  $value['word'] . "\t ,";
        $html .=  date('Y-m-d H:i:s', $value['createtime']) . "\n ";



    }


    header("Content-type:text/csv");

    header("Content-Disposition:attachment;filename=消息数据.csv");

    $html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

    echo $html;
    exit();
} else{
    $rule = pdo_fetch("select id from " . tablename('haoman_dpm_messages') . " where id = :id ", array(':id' => $id));
    if (empty($rule)) {
        message('抱歉，参数错误！');
    }
    if (pdo_delete('haoman_dpm_messages', array('id' => $id))) {
        message('删除成功！', referer(), 'success');
    }
}
