<?php
/**
 * By 高贵血迹
 */
$type = "异常进出";
$leixing = 3;
if($_GPC['signMode']=='65' || $_GPC['signMode']=='66'){
	if($_GPC['signMode']=='65'){
		$leixing = 1;
		$lx = "进校";
	}
	if($_GPC['signMode']=='66'){
		$leixing = 2;
		$lx = "离校";
	}	
	if ($school['jxstart'] < $nowtime & $nowtime < $school['jxend']){
		$type = "早上".$lx;
	}
	if ($school['lxstart'] < $nowtime & $nowtime < $school['lxend']){
		$type = "下午".$lx;
	}
	if ($school['jxstart1'] < $nowtime & $nowtime < $school['jxend1']){
		$type = "午间".$lx;
	}
	if ($school['lxstart1'] < $nowtime & $nowtime < $school['lxend1']){
		$type = "午间".$lx;
	}
	if ($school['jxstart2'] < $nowtime & $nowtime < $school['jxend2']){
		$type = "晚间".$lx;
	}
	if ($school['lxstart2'] < $nowtime & $nowtime < $school['lxend2']){
		$type = "晚间".$lx;
	}	
}else{
	if ($school['jxstart'] < $nowtime & $nowtime < $school['jxend']){
		$type = "早上进校";
		$leixing = 1;
	}

	if ($school['lxstart'] < $nowtime & $nowtime < $school['lxend']){
		$type = "下午离校";
		$leixing = 2;
	}

	if ($school['jxstart1'] < $nowtime & $nowtime < $school['jxend1']){
		$type = "午间进校";
		$leixing = 1;
	}

	if ($school['lxstart1'] < $nowtime & $nowtime < $school['lxend1']){
		$type = "午间离校";
		$leixing = 2;
	}

	if ($school['jxstart2'] < $nowtime & $nowtime < $school['jxend2']){
		$type = "晚间进校";
		$leixing = 1;
	}

	if ($school['lxstart2'] < $nowtime & $nowtime < $school['lxend2']){
		$type = "晚间离校";
		$leixing = 2;
	}
}
?>