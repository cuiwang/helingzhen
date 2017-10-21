<?php
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
// if ($_W['isajax']) {
    $rid = intval($_GPC['rid']);
    // $topnum = intval($_GPC['topnum']); //前10名的摇的总数量

    $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );
    if(empty($reply) || $reply['isyyy'] != 0){
        $data = array(
            'flag' => 100,
            'msg' => "活动信息错误",
        );
        echo json_encode($data);
        exit;
    }



    


    $data = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_yyyuser') . " WHERE rid = :rid AND pici = :pici AND uniacid = :uniacid ORDER BY point DESC,endtime ASC  LIMIT 10", array(':rid' => $rid, ':pici' => $reply['pici'], ':uniacid' => $uniacid));
    
    $reply['yyy_maxnum'] = empty($reply['yyy_maxnum']) ? 100 : $reply['yyy_maxnum'];

    if (is_array($data)) {
        foreach ($data as &$row) {
            $row['progress'] = sprintf("%.2f", $row['point'] / $reply['yyy_maxnum'] * 100);
            if($row['progress'] > 100){
                $row['progress'] = 100;
            }
            $topnum += $row['point'];
        }
        unset($row);
    }

    if($reply['status'] == 2){
        $status = -1;
    }else{
        if ($topnum >= $reply['yyy_maxnum']*10) {
            pdo_update('haoman_dpm_yyyreply', array('status' => 2), array('id' => $reply['id']));
            $status = -1;
        } else {
            $status = 1;
        }
    }

    

    if(empty($reply['yyy_maimg'])){
        $yyy_maimg = "../addons/haoman_dpm/img10/runing.gif";
    }else{
        $yyy_maimg = tomedia($reply['yyy_maimg']);
    }
    $lists = array();
    $lists['flag'] = 1;
    $lists['data']['status'] = $status;
    $lists['ma'] = $yyy_maimg;
    $lists['data']['players'] = $data;
    die(json_encode($lists));
// }