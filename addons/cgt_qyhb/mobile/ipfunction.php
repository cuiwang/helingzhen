<?php
  function iplimit($ip,$iplimit){
 	if (empty($iplimit)){
 		return true;
 	}
	$zz=getIpPlace($ip);
	$pos = strpos($zz, $iplimit);
	return $pos;
//无效代码	
$res1 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$ip"); 
$res1 = json_decode($res1); 
if ($res1->code==0){
	$pos = strpos($res1->data->city, $iplimit);
	if ($pos===false){
		$pos = strpos($res1->data->region, $iplimit);
		if ($pos===false){
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
} else
{
	return false;
}
}

function getIpPlace($ip){  
    require_once("iplocation.php");//加载类文件IpLocation.php  
    $ipfile = dirname(__FILE__)."/qqwry.dat";	
    $iplocation = new IpLocation($ipfile);  //new IpLocation($ipfile) $ipfile ip对应地区信息文件  
    $ipresult = $iplocation->getlocation($ip); //根据ip地址获得地区 getlocation("ip地区")  
    return $ipresult['country'];  
}  
 