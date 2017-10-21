<?php
global $_W, $_GPC;
$rid = intval($_GPC['rid']);
$pici = intval($_GPC['pici']);
$uniacid = $_W['uniacid'];
if (!empty($rid)) {
    $reply = pdo_fetch( " SELECT pici,yyy_maxnum FROM ".tablename('haoman_dpm_yyyreply')." WHERE rid='".$rid."' " );
}
$newPici = array();
//        if($reply['pici'] > 10){
//            $start = $reply['pici'] - 10;
//        }
//        for($i=$start;$i<$reply['pici'];$i++){
//            $newPici[$i]['pici'] = $i+1;
//        }

 $sql = 'select id,rid,pici, COUNT( DISTINCT from_user ) as `c` from ' . tablename('haoman_dpm_yyyuser') . 'where uniacid = :uniacid and rid = :rid group by pici order by `id` desc LIMIT 17';

 $prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid);
 $newPici = pdo_fetchall($sql, $prarm);
 $newPici = array_reverse($newPici);

if(empty($reply['yyy_maxnum'])){
    $reply['yyy_maxnum'] = 100;
}

if(!empty($pici)){
    $data = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_yyyuser') . " WHERE rid = :rid AND uniacid = :uniacid AND pici = :pici  AND point != 0 ORDER BY point DESC,endtime ASC LIMIT 20", array(':rid' => $rid, ':uniacid' => $uniacid, ':pici' => $pici));
   foreach ($data as &$v){
       if(empty($v['realname'])){
           $v['realname'] = $reply['yyy_maxnum'];
       }
   }
   unset($v);
}else{
    $data = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_yyyuser') . " WHERE rid = :rid AND uniacid = :uniacid AND pici = :pici  AND point != 0 ORDER BY point DESC,endtime ASC LIMIT 20", array(':rid' => $rid, ':uniacid' => $uniacid, ':pici' => $reply['pici']));
    foreach ($data as &$v){
        if(empty($v['realname'])){
            $v['realname'] = $reply['yyy_maxnum'];
        }
    }
    unset($v);
}



include $this->template('dpm_index10_result');