<?php
	global $_W, $_GPC;
	/*买家信息*/
	$orderno = $_GPC['mid'];
	$result = $_GPC['result'];
	$order = pdo_fetch("select * from".tablename('wz_tuan_order')."where uniacid='{$_W['uniacid']}' and orderno = '{$orderno}'");
	$goods = pdo_fetch("select *from".tablename('wz_tuan_goods')."where id = '{$order['g_id']}' and uniacid='{$_W['uniacid']}'");
	$is_hexiao_member = FALSE;
	$store_ids = explode(',',substr($goods['hexiao_id'],0,strlen($goods['hexiao_id'])-1)); 
	 //*判断是否是核销人员*/
	$hexiao_member = pdo_fetch("select *from".tablename('wz_tuan_saler')."where openid='{$_W['openid']}' and uniacid='{$_W['uniacid']}' and status=1");
	if($hexiao_member){
		if($hexiao_member['storeid']==''){
			$is_hexiao_member = TRUE;
		}else{
			$hexiao_ids = explode(',', substr($hexiao_member['storeid'],0,strlen($hexiao_member['storeid'])-1)); 
			foreach($hexiao_ids as$key=> $value){
				if(in_array($value,$store_ids)){
					$is_hexiao_member = TRUE;
				}
			}
		}
	}
	//门店信息
	$storesids = explode(",", $goods['hexiao_id']);
	foreach($storesids as$key=>$value){
		if($value){
			$stores[$key] =  pdo_fetch("select * from".tablename('wz_tuan_store')."where id ='{$value}' and uniacid='{$_W['uniacid']}'");
		}
	}
	if(checksubmit()){
		pdo_update('wz_tuan_order',array('status'=>4,'is_hexiao'=>2),array('orderno'=>$orderno));
		echo "<script>  location.href='" . $this -> createMobileUrl('check', array('mid' => $orderno,'result' => 'success')) . "';</script>";
		exit ;
	}
	include $this->template('check');
?>
