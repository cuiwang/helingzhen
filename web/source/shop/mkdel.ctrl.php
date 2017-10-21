<?php
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$dos = array('mkset', 'go');
$do = in_array($do, $dos) ? $do : 'mkset';
$weid= $_W['uniacid'];
$starsit=0;
if($do =='go'){
	//ignore_user_abort(true);
	//set_time_limit(0);
	//while(1){
		//sleep(200);
		$starsit=1;
		$now=time();
		$moduleall=pdo_fetchall("SELECT * FROM ".tablename('buymod_mbuy')."where status=1");
		foreach($moduleall as $row){
			
			if($row['endtime']<$now){
				
				$buymodule = pdo_fetch("SELECT * FROM ".tablename('uni_group')." WHERE uniacid = :uniacid", array(':uniacid' => $row['weid']));
				
				$i=0;
				
				foreach(unserialize($buymodule['modules']) as $m){
					
					if($m!=$row['module']){
					
						$modules[].=$m;
						
					   }
					   $i=$i++;
		
					}
					
					$data=array(
				
						'modules' => iserializer($modules),
			
						'name' => '',
						
					);
					
					if(!empty($buymodule)){
		
							pdo_update('uni_group', $data, array('id' => $buymodule['id']));
							
							}
						
					pdo_update('buymod_mbuy', array('status' => '2'), array('id' => $row['id']));
					
					cache_delete("unisetting:{$row['weid']}");
			
					cache_delete("unimodules:{$row['weid']}:1");
			
					cache_delete("unimodules:{$row['weid']}:");
			
					cache_delete("uniaccount:{$row['weid']}");
	
				}
			}


		if(!file_exists("../attachment/buymod/inc/web/test.txt")){
		$fp = fopen("../attachment/buymod/inc/web/test.txt","wb");
		fclose($fp);
		}
		$str = file_get_contents('../attachment/buymod/inc/web/test.txt');
		$str .= "\r\n".date('Y-m-d H:i:s',$now)."公众号：121";
		$fp = fopen("../attachment/buymod/inc/web/test.txt","wb");
		fwrite($fp,$str);
		fclose($fp);	
}
template('shop/mkdel');

