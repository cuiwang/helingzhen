<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}



$id = intval($_GPC['_id']);
$num = intval($_GPC['_num']);
$status = intval($_GPC['_status']);

if(empty($id))
{
    return 1;
}
$goods= pdo_fetch("select * from " . tablename("haoman_dpm_shop_goods") . " where id=:id and  rid =:rid and uniacid=:uniacid and deleted!=1 and status=1",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$id));

$shoppingcar = pdo_fetch("select * from " . tablename("haoman_dpm_shop_car") . " where rid =:rid and uniacid=:uniacid and from_user=:from_user and shopid=:shopid and status =0 ",array(':rid'=>$rid,':uniacid'=>$uniacid,':from_user'=>$from_user,':shopid'=>$goods['id']));

if($status==1){
     if($goods['sale_number']>=$goods['stock']&&$goods['stock']>0){
         $result = array(
             'True' => 0,
         );

         $this->message($result);
     }
    if(empty($shoppingcar)){
        $result = pdo_insert('haoman_dpm_shop_car', array(
            'rid' => $rid,
            'uniacid' => $uniacid,
            'from_user' => $from_user,
            'money' => $goods['productprice'],
            'number' => 1,
            'shopname' => $goods['title'],
            'categoryid' => $goods['categoryid'],
            'shopid' => $goods['id'],
            'status' => 0,
            'createtime' => time(),
        ));
    }else{
        $temp = pdo_update('haoman_dpm_shop_car', array('number' => $shoppingcar['number']+1), array('shopid' => $id));
    }
    $result = array(
        'True' => 1,
    );

    $this->message($result);
}elseif ($status==2){
    if($shoppingcar['number']>1){
        $temp = pdo_update('haoman_dpm_shop_car', array('number' => $shoppingcar['number']-1), array('shopid' => $id));
    }elseif ($shoppingcar['number']==1){
        pdo_delete('haoman_dpm_shop_car', array('shopid' => $id));

    }

    $result = array(
        'True' => 1,
    );
}
