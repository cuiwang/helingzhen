<?php
	//查询具体充值fee并且恢复
			global $_W;
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
			$limt_num = 450;
			$paylog = pdo_fetchall("select * from".tablename('core_paylog')."where module='weliam_indiana'");
			foreach($paylog as $key=>$value){
				$result = pdo_fetch("select * from".tablename('weliam_indiana_rechargerecord')."where ordersn='{$value['tid']}'");
				$left = $value['fee'] - $result['num'];
				if($left != 0){
					pdo_update('weliam_indiana_rechargerecord',array('num' => $value['fee']),array('ordersn' =>$value['tid']));
					m('credit')->updateCredit2($value['openid'],$value['uniacid'],$left);
				}
			}
		echo 'chenggong';
		exit;
 ?>