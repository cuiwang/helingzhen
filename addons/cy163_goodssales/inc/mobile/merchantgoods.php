<?php
global $_GPC, $_W;
$merchant = $this->checkmergentauth();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('cygoodssale_goods')." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND deleted = 0");
	$allpage = ceil($total/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$list = pdo_fetchall("SELECT * FROM ".tablename('cygoodssale_goods')." WHERE weid = {$_W['uniacid']} AND deleted = 0 AND merchant_id = {$merchant['id']} ORDER BY displayorder ASC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($list as $k=>$v){
		$thumbs = unserialize($v['thumb_url']);
		$list[$k]['thumb'] = $thumbs[0];
	}
	$isajax = intval($_GPC['isajax']);
	if($isajax == 1){
		$html = '';
		foreach($list as $k=>$v){			
			$status = $v['status'] == 1 ? '已审核' : '未审核';
			$html .= '<div class="item">
						<div class="top text-r">'.$status.'</div>
						<div class="goods">
							<div class="img"><img src="'.tomedia($v['thumb']).'" /></div>
							<div class="goodsmsg">
								<div class="title textellipsis2">'.$v['title'].'</div>
								<div class="pricenum">
									<div class="price">￥'.$v['normalprice'].'</div>
									<div class="num text-r"></div>
								</div>
							</div>
						</div>
						<div class="btns">
							<a href="'.$this->createMobileUrl('merchantgoods',array('op'=>'post','id'=>$v['id'])).'" class="pay text-c">编辑</a>
							<a href="javascript:;" onclick="delgoods('.$v['id'].');" class="text-c">删除</a>
						</div>
					</div>';
		}
		echo $html;
		exit;
	}
}elseif ($operation == 'post') {
	$cservicelist = pdo_fetchall("SELECT * FROM " . tablename('cygoodssale_cservice') . " WHERE weid = '{$_W['uniacid']}' AND merchant_id = {$merchant['id']} ORDER BY displayorder ASC");
	$id = intval($_GPC['id']);
	$goodscservicearr = pdo_fetchall("SELECT cserviceid FROM " . tablename('cygoodssale_goodscservice') . " WHERE weid = '{$_W['uniacid']}' AND goodsid = {$id}");
	$goodscservice = array();
	foreach($goodscservicearr as $k=>$v){
		$goodscservice[] = $v['cserviceid'];
	}
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM " . tablename('cygoodssale_goods') . " WHERE merchant_id = {$merchant['id']} AND id = {$id}");
		if (empty($item)) {
			message('抱歉，商品不存在或是已经删除！', '', 'error');
		}
		$piclist1 = unserialize($item['thumb_url']);
		$piclist = array();
		if(is_array($piclist1)){
			foreach($piclist1 as $p){
				$piclist[] = is_array($p)?$p['attachment']:$p;
			}
		}
		
		$piclist2 = unserialize($item['thumbsdes']);
		$piclistdetail = array();
		if(is_array($piclist2)){
			foreach($piclist2 as $p){
				$piclistdetail[] = is_array($p)?$p['attachment']:$p;
			}
		}
		$options = pdo_fetchall("select * from " . tablename('cygoodssale_goods_option') . " where goodsid={$id} order by displayorder ASC");
	}
	if ($_GPC['submit']) {
		if (empty($_GPC['title'])) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入商品名称！';
			echo json_encode($resArr);
			exit();
		}
		if(empty($_GPC['thumbs'])){
			$_GPC['thumbs'] = array();
		}
		if(empty($_GPC['thumbsdes'])){
			$_GPC['thumbsdes'] = array();
		}
		$setting = $this->setting;
		if($setting['isshenhe'] == 1){
			$status = 0;
		}else{
			$status = 1;
		}
		$isdistribution = intval($_GPC['isdistribution']);
		if($isdistribution == 1){
			$fenxiaoprice = $_GPC['fenxiaoprice'];
		}else{
			$fenxiaoprice = 0;
		}
		$ishexiao = intval($_GPC['ishexiao']);
		if($ishexiao == 1){
			$fenxiaoprice = $_GPC['fenxiaoprice'];
			$hexiaocon = $merchant['openid'];
		}else{
			$hexiaocon = '';
		}
		$data = array(			
			'weid' => intval($_W['uniacid']),
			'displayorder' => intval($_GPC['displayorder']),
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'createtime' => TIMESTAMP,
			'total' => intval($_GPC['total']),
			'normalprice' => $_GPC['normalprice'],
			'yunfei' => $_GPC['yunfei'],
			'maxbuy' => intval($_GPC['maxbuy']),
			'status' => $status,
			'merchant_id' => intval($merchant['id']),
			'viewcount' => intval($_GPC['viewcount']),
			'fenxiaoprice' => $fenxiaoprice,
			'isdistribution'=>$isdistribution,
			'ishexiao'=>$ishexiao,
			'hexiaocon'=>$hexiaocon,
			'isallhexiao'=>intval($_GPC['isallhexiao']),
		);
		if ($data['total'] === -1) {
			$data['total'] = 0;
		}

		if(is_array($_GPC['thumbs'])){
			$data['thumb_url'] = serialize($_GPC['thumbs']);
		}
		if(is_array($_GPC['thumbsdes'])){
			$data['thumbsdes'] = serialize($_GPC['thumbsdes']);
		}
		if (empty($id)) {
			pdo_insert('cygoodssale_goods', $data);
			$id = pdo_insertid();
		} else {
			unset($data['createtime']);
			pdo_update('cygoodssale_goods', $data, array('id' => $id));
			pdo_delete('cygoodssale_goodscservice',array('goodsid' => $id));
		}
		
		//处理客服
		if(!empty($_GPC['cservice'])){
			foreach($_GPC['cservice'] as $k=>$v){
				$datacservice['weid'] = $_W['uniacid'];
				$datacservice['goodsid'] = $id;
				$datacservice['cserviceid'] = $v;
				pdo_insert('cygoodssale_goodscservice', $datacservice);
			}
		}
		
		//处理规格
		$totalstocks = 0;
		$option_ids = $_POST['option_id'];
		$option_titles = $_POST['option_title'];
		$option_normalprices = $_POST['option_normalprice'];
		$option_stocks = $_POST['option_stock'];
		$option_displayorders = $_POST['option_displayorder'];
		$len = count($option_ids);
		$optionids = array();
		for ($k = 0; $k < $len; $k++) {
			$option_id = "";
			$get_option_id = $option_ids[$k];
			$a = array(
				"title" => $option_titles[$k],
				"normalprice" => $option_normalprices[$k],
				"stock" => $option_stocks[$k],
				"displayorder" => $k,
				"goodsid" => $id,
			);
			if (!is_numeric($get_option_id)) {
				pdo_insert("cygoodssale_goods_option", $a);
				$option_id = pdo_insertid();
			} else {
				pdo_update("cygoodssale_goods_option", $a, array('id' => $get_option_id));
				$option_id = $get_option_id;
			}
			$optionids[] = $option_id;
			$totalstocks += $option_stocks[$k];
		}
		if (count($optionids) > 0) {
			pdo_query("delete from " . tablename('cygoodssale_goods_option') . " where goodsid = {$id} and id not in ( " . implode(',', $optionids) . ")");
			pdo_update("cygoodssale_goods",array('total'=>$totalstocks),array('id'=>$id));
		}else{
			pdo_query("delete from " . tablename('cygoodssale_goods_option') . " where goodsid = {$id}");
		}
		$resArr['error'] = 0;
		$resArr['msg'] = '操作成功！';
		echo json_encode($resArr);
		exit();
	}
}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id FROM " . tablename('cygoodssale_goods') . " WHERE merchant_id = {$merchant['id']} AND id = {$id}");
	if (empty($row)) {
		$resArr['error'] = 1;
		$resArr['msg'] = '抱歉，商品不存在或是已经被删除！';
		echo json_encode($resArr);
		exit();
	}
	pdo_update("cygoodssale_goods", array("deleted" => 1), array('id' => $id));
	$resArr['error'] = 0;
	$resArr['msg'] = '删除成功！';
	echo json_encode($resArr);
	exit();
}
include $this->template('merchantgoods');
?>