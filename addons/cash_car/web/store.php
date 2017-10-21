<?php
 /**
 * 服务点管理
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */

if(empty($setting)){
	message("请先配置相关数据！", $this->createWebUrl('setting'), "error");
}

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	if (checksubmit('submit')) { //排序
		if (is_array($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $val) {
				$data = array('displayorder' => intval($_GPC['displayorder'][$id]));
				pdo_update($this->table_store, $data, array('id' => $id));
			}
		}
		message('操作成功!', $url);
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$where = "WHERE weid = '{$_W['uniacid']}'";
	if(!empty($_GPC['is_own'])){
		$where .= " AND accountid = 0";
	}

	$storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_store) . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
	if (!empty($storelist)) {
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_store) . " {$where}");
		$pager = pagination($total, $pindex, $psize);
	}

} elseif ($operation == 'post') {
	load()->func('tpl');
	
	$id = intval($_GPC['id']); //服务点编号
	$store = pdo_fetch("select * from " . tablename($this->table_store) . " where id=:id and weid =:weid", array(':id' => $id, ':weid' => $_W['uniacid']));
	if (!empty($id)) {
		if (empty($store)) {
			message('抱歉，数据不存在或是已经删除！', '', 'error');
		}
	}
	$piclist = unserialize($store['thumb_url']);
	$worktime = pdo_fetchall("SELECT * FROM " .tablename($this->table_store_time). " WHERE 1 ORDER BY soft");
	$storetime = unserialize($store['hours']);
	
	if (checksubmit('submit')) {
		$data = array();
		$data['weid'] = intval($_W['uniacid']);
		$data['title'] = trim($_GPC['title']);
		$data['store_type'] = trim($_GPC['store_type']);
		$data['map_type'] = intval($_GPC['map_type']);
		$data['info'] = trim($_GPC['info']);
		$data['content'] = trim($_GPC['content']);
		$data['tel'] = trim($_GPC['tel']);
		$data['logo'] = trim($_GPC['logo']);
		$data['address'] = trim($_GPC['address']);
		$data['location_p'] = trim($_GPC['location_p']);
		$data['location_c'] = trim($_GPC['location_c']);
		$data['location_a'] = trim($_GPC['location_a']);
		$data['is_show'] = intval($_GPC['is_show']);
		$data['place'] = trim($_GPC['place']);
		$data['hours'] = $_GPC['hours']?serialize($_GPC['hours']):$setting['hours'];
		$data['hours_time'] = intval($_POST['hours_time'])?intval($_POST['hours_time']):$setting['hours_time'];
		$data['radius'] = intval($_POST['radius'])?intval($_POST['radius']):$setting['radius'];
		$data['lng'] = trim($_GPC['lng']);
		$data['lat'] = trim($_GPC['lat']);
		$data['commission'] = trim($_GPC['commission']);
		$data['bookingtime'] = intval($_GPC['bookingtime']);
		$data['dayoff'] = $_GPC['dayoff']?trim($_GPC['dayoff']):0;
		$data['updatetime'] = TIMESTAMP;
		$data['dateline'] = TIMESTAMP;

		if (empty($data['title'])) {
			message('请输入服务点名称！', '', 'error');
		}
		if (istrlen($data['title']) > 30) {
			message('服务点名称不能多于30个字！', '', 'error');
		}
		if (empty($data['tel'])) {
			message('请输入联系电话！', '', 'error');
		}
		if (empty($data['address'])) {
			message('请输入地址！', '', 'error');
		}

		if (is_array($_GPC['thumbs'])) {
			$data['thumb_url'] = serialize($_GPC['thumbs']);
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

		if (!empty($store)) {
			unset($data['dateline']);
			pdo_update($this->table_store, $data, array('id' => $id, 'weid' => $_W['uniacid']));
		} else {
			pdo_insert($this->table_store, $data);
		}
		message('操作成功!', $this->createWebUrl('store', array('op'=>'post','id'=>$id)), 'success');
	}
	 
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$store = pdo_fetch("SELECT id FROM " . tablename($this->table_store) . " WHERE id = '$id'");
	if (empty($store)) {
		message('抱歉，不存在或是已经被删除！', $this->createWebUrl('store', array('op' => 'display')), 'error');
	}
	pdo_delete($this->table_store, array('id' => $id, 'weid' => $_W['uniacid']));
	message('删除成功！', $this->createWebUrl('store', array('op' => 'display')), 'success');
}

include $this->template('store');