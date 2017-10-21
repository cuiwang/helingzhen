<?php
defined('IN_IA') or exit('Access Denied');
global $_GPC,$_W;
load()->func('tpl');
$id = intval($_GPC['id']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$id));
if (empty($item)) {
	message('您访问的活动不存在',referer(),'error');
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$condition = ' WHERE o.uniacid = :uniacid AND o.did=:did AND o.status=1';
	$params = array(':uniacid' => $_W['uniacid'],':did'=>$id);
	if ($_GPC['output'] == 1) {
		$sql = 'SELECT distinct o.*,u.nickname FROM ' . tablename($this->t_order) . ' o LEFT join ' . tablename($this->t_user) . ' u on u.openid=o.openid ' . $condition;
		$list = pdo_fetchall($sql, $params);
		//print_r($list);exit();
		$i = 0;
		foreach ($list as $key => $value) {
			$arr[$i]['ordersn'] = $value['ordersn'];
			$arr[$i]['nickname'] = $value['nickname'];
			$arr[$i]['realname'] = $value['realname'];
			$arr[$i]['mobile'] = $value['mobile'];
			$arr[$i]['openid'] = $value['openid'];
			$arr[$i]['price'] = $value['price'];
			$arr[$i]['add_time'] = "'".date('Y-m-d H:i:s',$value['createtime']);
			$arr[$i]['status'] = $value['status'] == 1 ? '支付成功' : '支付失败';
			$arr[$i]['remark'] = $value['remark'];
			$i ++;
		}
		exportexcel($arr,array('订单号','昵称','姓名','电话','openid','捐赠金额','捐赠时间','状态','备注'),'捐赠记录-'.date('Y-m-d'));
		exit();
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->t_order) . 'WHERE uniacid = :uniacid AND did=:did AND status=1', array(':uniacid'=>$_W['uniacid'],':did'=>$id));
	if (!empty($total)) {
		$sql = 'SELECT distinct o.*,u.nickname FROM ' . tablename($this->t_order) . ' o LEFT JOIN ' . tablename($this->t_user) . ' u on u.openid=o.openid ' . $condition . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		//pdo_debug();
		$pager = pagination($total, $pindex, $psize);
	}
	include $this->template('order');
}
function exportexcel($data=array(),$title=array(),$filename='report'){
   	header("Content-type:application/octet-stream");
   	header("Accept-Ranges:bytes");
   	header("Content-type:application/vnd.ms-excel");  
   	header("Content-Disposition:attachment;filename=".$filename.".xls");
   	header("Pragma: no-cache");
   	header("Expires: 0");
   	//导出xls 开始
   	if (!empty($title)){
   	    foreach ($title as $k => $v) {
   	        $title[$k]=iconv("UTF-8", "GB2312",$v);
   	    }
   	    $title= implode("\t", $title);
   	    echo "$title\n";
   	}
   	if (!empty($data)){
   	    foreach($data as $key=>$val){
   	        foreach ($val as $ck => $cv) {
   	            $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
   	        }
   	        $data[$key]=implode("\t", $data[$key]);
   	        
   	    }
   	    echo implode("\n",$data);
   	}
}
?>