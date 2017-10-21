<?php
	// 
	//  checkcodes.api.php
	//  <project>
	//  检测并且恢复错误夺宝码
	//  Created by Administrator on 2016-07-07.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	require '../../../../framework/bootstrap.inc.php';
	require IA_ROOT. '/addons/weliam_indiana/defines.php';
	require WELIAM_INDIANA_INC.'function.php';
	load()->func('communication');
	
	
	global $_W,$_GPC;
	$period_number = $_GPC['period_number'];
	$uniacid = $_GPC['uniacid'];
	m('log')->WL_log('check_codes','检测夺宝码情况',$period_number,$uniacid);
	
	$check_period = pdo_fetch("select id,codes,status,shengyu_codes,zong_codes from".tablename('weliam_indiana_period')." where uniacid=:uniacid and period_number=:period_number",array(':uniacid'=>$uniacid,':period_number'=>$period_number));
	m('log')->WL_log('check_codes','$check_period',$check_period,$uniacid);
	
	if(!empty($check_period)){
		if(($check_period['codes']=='s:0:"";'||$check_period['codes']==''||$check_period['codes']='a:0:{}') && $check_period['status']==1 && $check_period['shengyu_codes'] > 0){
			$arr = array();
			$result_fetch = pdo_fetchall("select code from".tablename('weliam_indiana_record')." where period_number=:period_number and uniacid=:uniacid",array(':period_number'=>$period_number,':uniacid'=>$uniacid));
			foreach($result_fetch as $key=>$value){
				if(empty($arr)){
					$arr = unserialize($value['code']);
				}else{
					$arr = array_merge($arr,unserialize($value['code']));
				}
			}
			$codes_num = intval($check_period['zong_codes']);
			$new_codes = array();
			$j = 0;
			for($i=0;$i<$codes_num;$i++){
				$num = 1000001 + $i;
				if(!in_array($num, $arr)){
					$new_codes[$j] = $num;
					if($j < $check_period['shengyu_codes']){
						$j++;
					}
				}
			}
			m('log')->WL_log('check_codes','恢复【'.$period_number.'】arr',$arr,$uniacid);
			m('log')->WL_log('check_codes','恢复【'.$period_number.'】new_codes',$new_codes,$uniacid);
			for($k = $j;$k < $check_period['shengyu_codes'];$k++){
				$new_codes[$j] = 1000001 + rand(0, $check_period['shengyu_codes']);
				$j++;
			}
			shuffle($new_codes);
			$new_codes = serialize($new_codes);
			$result_update = pdo_update('weliam_indiana_period',array('codes'=>$new_codes,'allcodes'=>'a:0:{}'),array('uniacid'=>$uniacid,'period_number'=>$period_number));
			m('log')->WL_log('check_codes','恢复【'.$period_number.'】夺宝码情况',$result_update,$uniacid);
		}
	}
	exit('恢复完毕');