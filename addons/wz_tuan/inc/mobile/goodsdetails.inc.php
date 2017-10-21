<?php
	global $_W,$_GPC;
	$this->getuserinfo();
	$id = intval($_GPC['id']);
	$tuan_id = intval($_GPC['tuan_id']);
	load()->model('mc');
	if(!empty($id)){
		//商品
		$sql = 'SELECT * FROM '.tablename('wz_tuan_goods').' WHERE id=:id and uniacid=:uniacid';
		$paramse = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
		$goods = pdo_fetch($sql, $paramse);
		$collect = pdo_fetch("select * from " . tablename('wz_tuan_collect') . " where uniacid='{$_W['uniacid']}' and openid = '{$_W['openid']}' and sid = '{$id}'");
		//得到图集
		$advs = pdo_fetchall("select * from " . tablename('wz_tuan_goods_atlas') . " where g_id='{$id}'");
        foreach ($advs as &$adv) {
        	if (substr($adv['link'], 0, 5) != 'http:') {
                $adv['link'] = "http://" . $adv['link'];
            }
        }
        unset($adv);
		$params = pdo_fetchall("SELECT * FROM" . tablename('wz_tuan_goods_param') .  "WHERE goodsid = '{$id}' ");
		if(empty($goods)){
			message('未找到指定的商品.', $this->createMobileUrl('index'));
		}
		//门店信息
		$storesids = explode(",", $goods['hexiao_id']);
		foreach($storesids as$key=>$value){
			if($value){
				$stores[$key] =  pdo_fetch("select * from".tablename('wz_tuan_store')."where id ='{$value}' and uniacid='{$_W['uniacid']}'");
			}
		}
		// 分享团数据
		if(empty($goods['is_share'])){
			if ($this->module['config']['sharestatus'] != 2) {
				$thistuan = pdo_fetchall("select * from".tablename('wz_tuan_group')."where uniacid='{$_W['uniacid']}' and goodsid = '{$id}' and groupstatus=3 and lacknum<>neednum order by id asc limit 0,5");
				if (!empty($thistuan)) {
					foreach ($thistuan as $key => $value) {
						$tuan_first_order = pdo_fetch("select * from".tablename('wz_tuan_order')."where tuan_id = '{$value['groupnumber']}' and tuan_first=1 and uniacid='{$_W['uniacid']}'");
						$userinfo = $this->getfansinfo($tuan_first_order['openid']);
						$thistuan[$key]['avatar'] = $userinfo['avatar'];
						$thistuan[$key]['nickname'] = $userinfo['nickname'];
					}
				}
				
			}
		}
		
	}

//	$order = pdo_fetch("select * from".tablename('wz_tuan_order')."where uniacid = '{$_W['uniacid']}' and openid='{$_W['openid']}' and tuan_first=1");
//	if(empty($order)){
//			$open = $this -> module['config']['openfirstpay'];
//			if($open==1){
//				$percent=$this -> module['config']['firstpay'];
//				$thisprice = $goods['gprice']*$percent*0.01+$goods['freight'];
//			}
//		}
	session_start();
	$_SESSION['goodsid']='';
	$_SESSION['tuan_id']='';
	$_SESSION['groupnum']='';
	$_SESSION['goodsid'] = $id;
	$_SESSION['tuan_id'] = $tuan_id;
	$share_data = $this -> module['config'];
	if($share_data['share_imagestatus']){
		if($share_data['share_imagestatus']==3){
			$shareimage = $share_data['share_image'];
		}elseif($share_data['share_imagestatus']==1){
			$shareimage = $goods['gimg'];
		}elseif($share_data['share_imagestatus']==2){
			$result = mc_fetch($_W['member']['uid'], array('credit1', 'credit2','avatar','nickname'));
			$shareimage = $result['avatar'];
		}
	}
			if($_GPC['share_type']=='share_type'){
				include $this->template('share_tuan');
			}else{
				if ($this->module['config']['mode'] == 1) {
					include $this->template('simpgoodsdetails');
				}else {
					include $this->template('goodsdetails');
				}
			}
	
?>