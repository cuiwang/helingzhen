<?
  global $_W, $_GPC;
  $url=$_W['siteurl'];
  $tuan_id = intval($_GPC['tuan_id']);

  if(!empty($tuan_id)){
  	$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
  	$profileall = pdo_fetchall("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' GROUP BY from_user");
    //取得该团所有订单
    $orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE uniacid ='{$_W['uniacid']}' and tuan_id = {$tuan_id} and status = 1 order by id asc");
    //取一个订单$order
    foreach($orders as $key=>$value){
    	$order['g_id']=$value['g_id'];
    }
//  $order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE  tuan_id = {$tuan_id} and status = 1");
   //若没有参团则$myorder为空
    $myorder = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE openid = '{$_W['openid']}' and tuan_id = {$tuan_id} and status = 1");
  	
  	$goods = pdo_fetch("SELECT * FROM".tablename('tg_goods')."WHERE id = {$order['g_id']}");
    //该团购已有订单数count($item),已付款的订单
    $sql= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status = 1 and pay_type <> 0";
    $params= array(':tuan_id'=>$tuan_id);
    $alltuan = pdo_fetchall($sql, $params);
    $item = array();
    foreach ($alltuan as $num => $all) {
    $item[$num] = $all['id'];
    }
     /*$n ：剩余人数，$nn 该团只有一人*/
    $n = intval($goods['groupnum']) - count($item);
    $nn = intval($goods['groupnum'])-1;
    $arr = array();
    for ($i=0; $i <$n ; $i++) { 
       $arr[$i]=0;
    }
    /*团是否过期*/
    //团长订单
    $tuan_first_order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = {$tuan_id} and tuan_first = 1");
    $hours=$tuan_first_order['endtime'];
    $time = time();
    $date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
    $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
  
    $date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
    $lasttime2 = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
    $lasttime = $tuan_first_order['endtime'];
    
  }

  include $this->template('group');
