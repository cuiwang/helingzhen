<?php
		session_start();
		$this->getuserinfo();
		$_SESSION['goodsid']='';
		$_SESSION['tuan_id']='';
		$_SESSION['groupnum']='';
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$content = '';
		if(!empty($op)){
			$content .= " and groupstatus='{$op}' ";
		}else{
			$content .= " and groupstatus=3 ";
		}
		$this->updategourp();
		$reslut = $_GPC['result'];
		$share_data = $this->module['config'];
		$orders = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_order') . " WHERE uniacid ='{$_W['uniacid']}' and openid='{$_W['openid']}' and is_tuan = 1 and status in(1,2,3,4,6,7) order by ptime desc");
	     foreach ($orders as $key => $order) {
			$goods = pdo_fetch("SELECT * FROM ".tablename('wz_tuan_goods')."WHERE id = '{$order['g_id']}'");
			$thistuan = pdo_fetch("SELECT * FROM ".tablename('wz_tuan_group')."WHERE groupnumber = '{$order['tuan_id']}' $content");
			if(empty($thistuan)){
				unset($orders[$key]);
			}else{
				$orders[$key]['groupnum'] = $goods['groupnum'];
				$orders[$key]['gprice'] = $goods['gprice'];
				$orders[$key]['gid'] = $goods['id'];
				$orders[$key]['gname'] = $goods['gname'];
				$orders[$key]['gimg'] = $goods['gimg'];
		        $orders[$key]['itemnum'] = $thistuan['lacknum'];
				$orders[$key]['groupstatus'] = $thistuan['groupstatus'];
			}
			
		}
		include $this->template('mygroup');

	

?>