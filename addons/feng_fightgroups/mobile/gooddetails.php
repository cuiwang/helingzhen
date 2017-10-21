<?php
	$id = intval($_GPC['id']);
	$tuan_id = intval($_GPC['tuan_id']);
	if(!empty($id)){
		//商品
		$sql = 'SELECT * FROM '.tablename('tg_goods').' WHERE id=:id and uniacid=:uniacid';
		$paramse = array(':id'=>$id, ':uniacid'=>$weid);
		$goods = pdo_fetch($sql, $paramse);
		$collect = pdo_fetch("select * from " . tablename('tg_collect') . " where uniacid='{$_W['uniacid']}' and openid = '{$_W['openid']}' and sid = '{$id}'");
		//得到图集
		$advs = pdo_fetchall("select * from " . tablename('tg_goods_atlas') . " where g_id='{$id}'");
        foreach ($advs as &$adv) {
        	if (substr($adv['link'], 0, 5) != 'http:') {
                $adv['link'] = "http://" . $adv['link'];
            }
        }
        unset($adv);
		$params = pdo_fetchall("SELECT * FROM" . tablename('tg_goods_param') .  "WHERE goodsid = '{$id}' ");
		if(empty($goods)){
			message('未找到指定的商品.', $this->createMobileUrl('index'));
		}
		
		// 分享团数据
		if ($this->module['config']['sharestatus'] != 2) {
			$sql1  = "select * from".tablename('tg_order')."where g_id=:g_id and is_tuan=:is_tuan and status = 1 and tuan_first = 1 and success = 1";
			$params1  = array(':g_id' => $id,':is_tuan'=>1);
			$tuan_ids = pdo_fetchall($sql1,$params1);
			if (!empty($tuan_ids)) {
				foreach ($tuan_ids as $key => $value) {
					$pttime = $value['starttime'] + 60*60*$value['endtime'];
					if ($pttime <= TIMESTAMP) {
						unset($tuan_ids[$key]);
					}else{
						$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$value['openid']}'");
						$tuan_ids[$key]['nickname'] = $profile['nickname'];
						$tuan_ids[$key]['avatar'] = $profile['avatar'];
						$pnumber = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('tg_order')." where uniacid ='{$_W['uniacid']}' and tuan_id = '{$value['tuan_id']}' and status = 1 ");
						$tuan_ids[$key]['snumber'] = $goods['groupnum'] - $pnumber;
					}
				}
			}
		}
	}
	if ($this->module['config']['mode'] == 1) {
		include $this->template('simpgooddetails');
	}else {
		include $this->template('gooddetails');
	}
?>