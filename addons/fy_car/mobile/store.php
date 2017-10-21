<?php
   /**
	* 服务点管理
	*/

	global $_GPC, $_W;
	$this->checklogin();
	$weid = $_W['uniacid'];

	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$psize = 15;
	$pindex = max(1, intval($_GPC['page']));

	/*登录用户信息*/
	$account_info = pdo_fetch("SELECT * FROM " .tablename($this->table_car_account). " WHERE id='{$_SESSION['accountid']}'");

	/* 微洗车设置 */
	$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid={$weid} LIMIT 1");

	/* 微洗车核销设置 */
	$fysetting = pdo_fetch("SELECT * FROM " . tablename($this->table_car_setting) . " WHERE weid='{$weid}'");
   
	if($op=='display'){
		$condition = " weid='{$weid}' AND accountid='{$_SESSION[accountid]}'";
		if(!empty($_GPC['title'])){
			$condition .= " AND title LIKE '%{$_GPC[title]}%'";
		}
		if(in_array($_GPC['is_show'], array('0','1','2'))){
			$condition .= " AND is_show = '{$_GPC[is_show]}'";
		}

		$storeslist = pdo_fetchall("SELECT * FROM " .tablename($this->table_store). " WHERE {$condition} order by displayorder desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");

		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_store). " WHERE {$condition}");
		$pager = $this->pagination($total, $pindex, $psize);

   }elseif($op=='addstore'){
		load()->func('tpl');

		$id = intval($_GPC['storeid']); //服务点编号
		$store = pdo_fetch("SELECT * FROM " . tablename($this->table_store) . " WHERE id=:id AND weid =:weid AND accountid=:accountid", array(':id' => $id, ':weid' => $weid, 'accountid'=>$_SESSION['accountid']));
		$mystores = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND accountid='{$_SESSION['accountid']}'"); 

		if (!empty($id)) {
			if (empty($store)) {
				message('抱歉，服务点不存在！', '', 'error');
			}
		}

		if(empty($id) && $mystores>=$fysetting['store_number']){
			message("您最多只能创建{$fysetting['store_number']}个服务点！", "", "error");
		}

		if($store){
			$reside['province'] = $store['location_p'];
			$reside['city']     = $store['location_c'];
			$reside['district'] = $store['location_a'];
		}

		$worktime = pdo_fetchall("SELECT * FROM " .tablename($this->table_store_time). " WHERE 1 ORDER BY soft");
		$storetime = unserialize($store['hours']);
		$piclist = unserialize($store['thumb_url']);

		if ($_POST) {
			$data = array();
			$data['weid'] = intval($weid);
			$data['title'] = trim($_GPC['title']);
			$data['logo'] = trim($_GPC['logo']);
			$data['store_type'] = intval($_GPC['store_type']);
			$data['commission'] = $fysetting['commission'];
			$data['info'] = $_GPC['info'];
			$data['content'] = trim($_GPC['content']);
			$data['tel'] = trim($_GPC['tel']);
			$data['location_p'] = trim($_GPC['dis']['province']);
			$data['location_c'] = trim($_GPC['dis']['city']);
			$data['location_a'] = trim($_GPC['dis']['district']);
			$data['address'] = trim($_GPC['address']);
			$data['lat'] = trim($_GPC['lat']);
			$data['lng'] = trim($_GPC['lng']);
			$data['hours'] = serialize($_POST['hours']);
			$data['hours_time'] = intval($_GPC['hours_time']);
			$data['radius'] = intval($_GPC['radius']);
			$data['updatetime'] = TIMESTAMP;
			$data['accountid'] = $_SESSION['accountid'];
			$data['bookingtime'] = intval($_GPC['bookingtime']);
			$data['dateline'] = TIMESTAMP;

			if (empty($data['title'])) {
				message('请输入服务点名称！', '', 'error');
			}
			if (empty($data['logo'])) {
				message('请上传服务点logo！', '', 'error');
			}
			if (!in_array($data['store_type'], array('1','2'))) {
				message('请选择服务点类型！', '', 'error');
			}
			if (empty($_POST['hours'])) {
				message('请选择服务点营业时间！', '', 'error');
			}
			if (empty($_POST['hours_time'])) {
				message('请输入每个时间段最大订单量！', '', 'error');
			}
			if (empty($_POST['radius'])) {
				message('请输入服务点下单距离范围！', '', 'error');
			}
			if (empty($data['tel'])) {
				message('请输入商家电话！', '', 'error');
			}
			if (empty($data['address'])) {
				message('请输入服务点地址！', '', 'error');
			}
			if (empty($data['lng']) || empty($data['lat'])) {
				message('请选择服务点经纬度！', '', 'error');
			}

			if(!empty($data['lng']) && !empty($data['lat'])){
				$converurl = "http://apis.map.qq.com/ws/coord/v1/translate?locations=".$data['lat'].",".$data['lng']."&type=3&key=672BZ-O7URG-NYGQO-I7YIR-EG55Q-RGFY6";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $converurl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
				$r = curl_exec($ch);
				curl_close($ch);

				$converres = json_decode($r);
				$converres=$this->object_array($converres);

				if($converres['status']==0){
					$data['txlng'] = $converres['locations'][0]['lng'];
					$data['txlat'] = $converres['locations'][0]['lat'];
				}
			}

			if (!empty($id)) {
				if($store['is_show']!=2){
					$data['is_show'] = $_GPC['is_show'];
				}
				unset($data['commission']);
				unset($data['dateline']);
				pdo_update($this->table_store, $data, array('id' => $id, 'weid' => $weid, 'accountid'=>$_SESSION['accountid']));
				message('修改成功!', $this->createMobileUrl('store'), 'success');
			} else {
				$data['is_show'] = 2;
				pdo_insert($this->table_store, $data);
				message('添加成功，请耐心等待管理员审核!', $this->createMobileUrl('store'), 'success');
			}
			
		}

   }elseif($op=='order'){
	   $storeid = intval($_GPC['storeid']);
	   $store = pdo_fetch("SELECT * FROM " .tablename($this->table_store)." WHERE id='{$storeid}' AND accountid='{$_SESSION['accountid']}'");
	   if(empty($store)){
		   message('服务点不存在，请重新选择！', '', 'error');
	   }

	   $condition  = " weid='{$weid}' AND storeid='{$storeid}' ";
	   if(in_array($_GPC['status'], array('-1','0','1','2','3','4'))){
		   $condition .= " AND status='{$_GPC[status]}'";
	   }
	   if(!empty($_GPC['ordersn'])){
		   $condition .= " AND ordersn='{$_GPC[ordersn]}'";
	   }

	   $order_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_order) . " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	   $total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_order) . "  WHERE {$condition}");

	   $pager = $this->pagination($total, $pindex, $psize);

   }elseif($op=='orderdetails'){
	   $orderid = trim($_GPC['orderid']);
	   $order = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE id='{$orderid}'");

	   if(empty($order)){
		   message('该订单不存在！');
	   }

	   /* 服务项目 */
	   $order['goods'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$order[id]}'");
	   /* 遍历该订单下的服务项目评价 */
		foreach($order['goods'] as $k=>$g){
			$evaluate = pdo_fetch("SELECT * FROM " . tablename($this->table_goods_evaluate) . " WHERE orderid=:orderid AND goodsid=:goodsid", array(':orderid'=>$order['id'], ':goodsid'=>$g['goodsid']));
			$order['goods'][$k]['evaluate'] = $evaluate;
		}
	   /* 洗车工作人员 */
	   $order['worker'] = pdo_fetch("SELECT * FROM " .tablename($this->table_worker). " WHERE openid='{$order[worker_openid]}'");

   }elseif($op=='goods'){
	   $storeid = intval($_GPC['storeid']);
	   $store = pdo_fetch("SELECT * FROM " .tablename($this->table_store)." WHERE id='{$storeid}' AND accountid='{$_SESSION['accountid']}'");
	   if(empty($store)){
		   message('服务点不存在，请重新选择！', '', 'error');
	   }

	   if($setting['store_model']==1){
		   message('当前系统设置不允许自行添加服务项目！', '', 'error');
	   }
	   
	   $condition = " weid='{$weid}' AND storeid='{$storeid}' ";
	   if($_GPC['title'] != ''){
		   $condition .= " AND title LIKE '%{$_GPC['title']}%' ";
	   }
	   if($_GPC['status'] != ''){
		   $condition .= " AND status='{$_GPC['status']}' ";
	   }

	   $goodslist = pdo_fetchall("SELECT * FROM " .tablename($this->table_goods). " WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	   $total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_goods) . "  WHERE {$condition}");
	   foreach($goodslist as $key=>$value){
			$goodslist[$key]['cname'] = pdo_fetch("SELECT name FROM " .tablename($this->table_category). " WHERE id='{$value['cid']}'");
		}

	   $pager = $this->pagination($total, $pindex, $psize);
	 
   }elseif($op=='addgoods'){
	   load()->func('tpl');

	   $goodsid = intval($_GPC['goodsid']);
	   $storeid = intval($_GPC['storeid']); //服务点编号
	   $store = pdo_fetch("SELECT * FROM " . tablename($this->table_store) . " WHERE id=:id AND weid =:weid AND accountid=:accountid", array(':id' => $storeid, ':weid' => $weid, 'accountid'=>$_SESSION['accountid']));
	   if(empty($store)){
		   message('该服务点不存在', '', 'error');
	   }
	   if(!empty($goodsid)){
		   $goods = pdo_fetch("SELECT a.*,b.id AS storeid FROM " .tablename($this->table_goods). " a LEFT JOIN " .tablename($this->table_store). " b ON a.storeid=b.id WHERE a.id='{$goodsid}' AND b.accountid='{$_SESSION['accountid']}'");
		   if(empty($goods)){
			   message('该服务项目不存在', '', 'error');
		   }
	   }

	   //洗车卡套餐列表
	   $tao_list = pdo_fetchall("SELECT DISTINCT onlycard,onlycard_name FROM " . tablename($this->table_onecard) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
	   //项目分类
	   $category = pdo_fetchall("SELECT id,name FROM " .tablename($this->table_category). " WHERE weid='{$weid}' AND parentid=0");

	   if ($_POST) {
			$data = array(
				'weid'         => intval($_W['uniacid']),
				'storeid'      => $storeid,
				'title'        => trim($_GPC['title']),
				'cid'          => intval($_GPC['cid']),
				'thumb'        => trim($_GPC['thumb']),
				'unitname'     => trim($_GPC['unitname']),
				'content'      => $_GPC['content'],
				'description'  => trim($_GPC['description']),
				'productprice' => trim($_GPC['productprice']),
				'integral'     => trim($_GPC['integral']),
				'status'       => intval($_GPC['status']),
				'displayorder' => intval($_GPC['displayorder']),
				'onlycard'     => trim($_GPC['onlycard']),
				'dateline'     => TIMESTAMP,
			);

			if (empty($data['title'])) {
				message('请输入项目名称！');
			}
			if (empty($data['cid'])) {
				message('请选择项目分类！');
			}
			if (empty($data['thumb'])) {
				message('请上传项目图片！');
			}
			if (empty($data['productprice'])) {
				message('请输入项目价格！');
			}
			if(!is_numeric($data['productprice']) || $data['productprice']<0){
				message('项目价格输入非法！');
			}
			if($data['integral']>$data['productprice']*$fysetting['multiple']){
				message('当前赠送积分最多为'.$data['productprice']*$fysetting['multiple'].'积分');
			}

			if (!empty($_FILES['thumb']['tmp_name'])) {
				load()->func('file');
				file_delete($_GPC['thumb_old']);
				$upload = file_upload($_FILES['thumb']);
				if (is_error($upload)) {
					message($upload['message'], '', 'error');
				}
				$data['thumb'] = $upload['path'];
			}
			if (empty($goodsid)) {
				pdo_insert($this->table_goods, $data);
			} else {
				unset($data['dateline']);
				pdo_update($this->table_goods, $data, array('id' => $goodsid));
			}
			message('项目更新成功！', $this->createMobileUrl('store', array('op' => 'goods', 'storeid' => $storeid)), 'success');
		}

   }elseif($op=='delgoods'){
	   $goodsid = intval($_GPC['goodsid']);
	   $goods = pdo_fetch("SELECT b.id AS storeid FROM " .tablename($this->table_goods). " a LEFT JOIN " .tablename($this->table_store). " b ON a.storeid=b.id WHERE a.id='{$goodsid}' AND b.accountid='{$_SESSION['accountid']}'");

	   if(empty($goods)){
		   message('该服务项目不存在！', '', 'error');
	   }

	   pdo_delete($this->table_goods, array('id'=>$goodsid));
	   message('删除成功', $this->createMobileUrl('store', array('op'=>'goods', 'storeid'=>$goods['storeid'])), 'success');
   }elseif ($operation == 'ajaxcategory') {
		$pid = intval($_GPC['pid']);
		$catlist = pdo_fetchall("SELECT id,name FROM " .tablename($this->table_category). " WHERE weid='{$weid}' AND parentid='{$pid}'");

		echo json_encode(array('catlist'=>$catlist));
		exit();
	}

   include $this->template('store');

