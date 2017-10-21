<?php 
global $_W , $_GPC;

$openid = m('user') -> getOpenid();

$uid = pdo_fetch("select * from ims_mc_mapping_fans where openid = :openid AND uniacid = :uniacid",array('openid'=>$openid,'uniacid'=>$_W['uniacid']))['uid'];
$info = pdo_fetch("select * from ims_mc_members where uid = :uid AND uniacid = :uniacid",array('uid'=>$uid,'uniacid'=>$_W['uniacid']));

/**
 * 最后一次签到时间
 */
$last = pdo_fetch("select createtime from ".tablename('xuan_miaosha_sign')."where uid = :uid AND uniacid = :uniacid order by createtime desc",array('uid'=>$uid,'uniacid'=>$_W['uniacid']));
/**
 * 今天零0时间
 */
$today = strtotime(date('y-m-d',time()));
/**
 *签到次数
 */
$count = pdo_fetch("select count(*) as count  from ".tablename('xuan_miaosha_sign')."where uid = :uid AND uniacid = :uniacid",array('uid'=>$uid,'uniacid'=>$_W['uniacid']))['count'];



$operation = isset($_GPC['op']) ? $_GPC['op'] : 'index';

/**
 * 查询我的订单列表
 */

$psize = 15;
$pindex = max(1, intval($_GPC['page']));
$limit = " order by t.id desc LIMIT " . ($pindex - 1) * $psize . ", {$psize}";
$lists = pdo_fetchall("select t.id as tid,picarr,address,phone,num,fee,tradetime,t.status as statu,pay_type,size1,size2,size3,size1,title,fubiaoti from ".tablename('xuan_miaosha_trade')."as t left join ".tablename('xuan_miaosha_goodslist')." as l on t.goodsid = l.id AND t.uniacid = l.uniacid where pid = :pid AND t.uniacid = :uniacid ".$limit,array('pid'=>$openid,'uniacid'=>$_W['uniacid']));

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xuan_miaosha_trade') . " where uniacid=".$_W['uniacid']." AND pid = '".$openid."'");
$pager = pagination($total, $pindex, $psize);

if($operation == 'index'){
    include $this->template('member/person');
}else if($operation == 'order'){

    include $this->template('member/myorder');
}else if($operation == 'sign'){
    if($last['createtime'] - $today < 0){
        $sign['createtime'] = time();  //现在
        $sign['uid'] = $uid;
        $sign['signstatus'] = 1;
        $sign['creditrecord'] = 2;
        $sign['uniacid'] = $_W['uniacid'];
        $result1 = pdo_insert('xuan_miaosha_sign', $sign);

        $result2 = mc_credit_update($uid,'credit1','2',array());
        if($result1&&$result2){
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo -1;
    }
}else if($operation == 'orderdetail'){
    $id = $_GPC['id'];
    $sql = "SELECT picarr,t.name as tname,address,phone,num,fee,tradetime,t.status as statu,pay_type,size1,size2,size3,size1,title,fubiaoti FROM ims_xuan_miaosha_trade AS t LEFT JOIN ims_xuan_miaosha_goodslist AS l ON t.goodsid = l.id AND t.uniacid = l.uniacid where t.pid = '".$openid."' AND t.uniacid = ".$_W['uniacid']." AND t.id = ".$id;

    $order = pdo_fetch($sql);

    include $this->template('member/order_detail');
}

