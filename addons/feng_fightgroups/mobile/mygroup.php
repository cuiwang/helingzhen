<?
	 global $_W, $_GPC;
	 $op = intval($_GPC['op']); //op=0对应获取全部订单
	 $openid = $_W['openid'];//用户的openid
	//获取当前用户全部订单信息
	if($op==0){
			$sql = 'SELECT * FROM '.tablename('tg_order').' a,'.tablename('tg_goods').' b'.' WHERE a.openid = :openid  and a.is_tuan = 1 and a.status = 1 and a.pay_type<>0 and a.uniacid = :uniacid and b.id = a.g_id order by ptime desc'; //从订单信息表里面取得数据的sql语句
			$params = array(':openid'=>$openid , ':uniacid'=>$weid);
			$orders = pdo_fetchall($sql, $params); 
			//当前用户所有团购订单
			$mytuan = pdo_fetchall("SELECT * FROM ".tablename('tg_order')."where openid = '{$openid}' and is_tuan = 1 ");
	}
	include $this->template('mygroup');

	

?>