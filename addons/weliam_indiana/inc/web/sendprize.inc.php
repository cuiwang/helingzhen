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
	$from_user=$_GPC['openid'];
	$goods=pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = '{$uniacid}' and id ='{$_GPC['sid']}'" );
	$member=pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_member')." WHERE from_user = '{$from_user}' and uniacid = '{$uniacid}'" );

	if (checksubmit()) {
		$data = $_GPC['express']; // 获取打包值
		$data['send_state']=1;
		$data['send_time']=TIMESTAMP;

		$ret = pdo_update(weliam_indiana_goodslist, $data, array('id'=>$goods['id']));
		if (!empty($ret)) {
			message('发货成功', referer(), 'success');
		} else {
			message('发货失败');
		}
	}

	include $this->template('sendprize');
?>