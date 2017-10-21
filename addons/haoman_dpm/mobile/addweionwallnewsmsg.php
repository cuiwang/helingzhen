<?php
 global $_GPC, $_W;
$rid = intval($_GPC['id']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];
$len = intval($_GPC['last_id']);
$bpreply = pdo_fetch("SELECT status,bp_mesages_num FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
$num = $bpreply['bp_mesages_num'];
if($bpreply['status']==1){
   $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and status = 1 and is_back !=1 and is_xy !=1 and is_bpshow = 0", array(':uniacid' => $uniacid,':rid'=>$rid));
   $limit = $totaldata - $len;
   if($num>0){
       if($limit>$num){
           $limit =$num;
       }

   }else{
       if($limit>2000){
           $limit =2000;
       }
   }


   $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and status = 1 and is_back !=1 and is_xy !=1 and is_bpshow = 0 ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid));


//           $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and status =1 and id>:id  and is_back !=1 and is_xy !=1  and is_bpshow = 0   ORDER BY id DESC limit 30",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$len));
}else{

   $totaldata = pdo_fetchcolumn("SELECT id FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and is_back !=1 and is_xy !=1 and is_bpshow = 0 ORDER BY id DESC", array(':uniacid' => $uniacid,':rid'=>$rid));
   $limit = $totaldata - $len;
    if($num>0){
        if($limit>$num){
            $limit =$num;
        }

    }else{
        if($limit>2000){
            $limit =2000;
        }
    }
   $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and is_back !=1 and is_xy !=1 and is_bpshow = 0 ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid));

//   $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and id>:id  and is_back !=1 and is_xy !=1  and is_bpshow = 0   ORDER BY id DESC limit 30",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$len));

}

foreach ($list as &$v){
    $v['createtime'] = date("Y-m-d H:i:s", $v['createtime']) ;
    if($v['wordimg']){
        $v['wordimg'] = tomedia($v['wordimg']);
    }

}
unset($v);
if($list){

    $result = array(
        'isResultTrue' => 1,
        'msg' => $limit,
        'resultMsg' =>$list,
    );
    $this->message($result);
}else{
    $result = array(
        'isResultTrue' => 0,
        'msg' => $limit,
    );
    $this->message($result);
}
