<?php
require '../../../../framework/bootstrap.inc.php';
require IA_ROOT. '/addons/weliam_indiana/defines.php';
require WELIAM_INDIANA_INC.'function.php';

load()->func('communication');
error_reporting(0);
set_time_limit(0);

$i = 0;
$uniacid = $_GPC['uniacid'];
/*********进程检测开始*********/
$sql_c = "select id,status,createtime,goodsid from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid  and goodsid=-1";
$data_c = array(
	':uniacid'=>$uniacid
);
$result_c = pdo_fetch($sql_c,$data_c);
if($result_c == FALSE){
	$result_c = pdo_fetch("select id,status,createtime,goodsid from".tablename('weliam_indiana_machineset')."where uniacid='{$uniacid}'  and period_number like '%openmachine%'");
}
if($result_c == FALSE){//再次检测是否没有检测到数据
	return FALSE;
}
$difference_time = time()-$result_c['createtime'];

/*************日志文件位置开始************/
m('log')->WL_log('robot','检测启动数据',$result_c,$uniacid);
/*************日志文件位置结束************/  

/*********进程检测结束*********/
if(($difference_time > 600 && $result_c['status'] == 1) || ($result_c['status'] == 0 && $result_c['goodsid'] == -1)){
	pdo_update('weliam_indiana_machineset',array('status'=>1,'createtime'=>time()),array('id'=>$result_c['id'],'uniacid'=>$uniacid));		//修改机器人状态
	$flag = 1;																					//给定标记参数
	while($flag == 1){
		//异步请求、
		ihttp_request($_W["siteroot"].'api/machine.api.php', array('uniacid' => $uniacid,'flag'=>'bendi'),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
		/*************调整日志文件位置开始************/
	   	m('log')->WL_log('robot','启动执行次数',$i,$uniacid);
		/*************调整日志文件位置结束************/ 
		$result = pdo_fetch("select status,id from ".tablename('weliam_indiana_machineset')." where uniacid='{$uniacid}' and goodsid=-1");
		m('log')->WL_log('robot','机器人进程检索情况',$result,$uniacid);
		pdo_update('weliam_indiana_machineset',array('createtime'=>time()),array('goodsid'=>-1,'uniacid'=>$uniacid));		//修改机器人状态
		if($result == FALSE){
			$flag = 0;
		}else{
			$flag = $result['status'];
		}
		$i++;
		$sleep_time = rand(1, 20);		//设置循环随机时间
	    sleep($sleep_time); //每隔十秒循环一次    
	}
}
exit('true');