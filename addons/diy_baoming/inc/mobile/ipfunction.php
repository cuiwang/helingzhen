<?php


function getIPLoc_sina($queryIP){
	    
$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP;    
$ch = curl_init($url);     
curl_setopt($ch,CURLOPT_ENCODING ,'utf8');     
curl_setopt($ch, CURLOPT_TIMEOUT, 5);   
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
$location = curl_exec($ch);    
$location = json_decode($location);    
curl_close($ch);         
$loc = "";   
if($location===FALSE) return "";     
if (empty($location->desc)) {    
  $loc = $location->province.$location->city.$location->district.$location->isp;  
}else{
  $loc = $location->desc;    
}    

return $loc;

}

function getTaobaoIP($queryIP){    
$url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$queryIP;    
$ch = curl_init($url);     
curl_setopt($ch,CURLOPT_ENCODING ,'utf8');     
curl_setopt($ch, CURLOPT_TIMEOUT, 5);   
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
$location = curl_exec($ch);    

curl_close($ch);         
$loc = "";   
return $location;

}

  function iplimit($ip,$iplimit){
 	if (empty($iplimit)){
 		return true;
 	}
/*	$zz=getIpPlace($ip);
	$pos = strpos($zz, $iplimit);
	return $pos;
//无效代码	
*/
//百度api，够准确
/*$res1=file_get_contents("http://api.map.baidu.com/location/ip?ak=L0WX15RmEZckRNLBwPpGIiEM&ip=$ip");
 $res1 = json_decode($res1); 


if ($res1->status==0){
	$arr=explode('|',$res1->address);
	$pos = strpos($iplimit,$arr[1]);
	if ($pos===false){
		$pos = strpos($iplimit,$arr[2]);
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
}*/



/*getIPLoc_sina($queryIP)
*/

//淘宝的ip 备用。
//59.56.61.93
/*$res1 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$ip");

*/
$res1=getIPLoc_sina($ip);



$iplimitarr=explode("|",$iplimit);
    
	foreach ($iplimitarr as $value){

	  $pos = strexists($res1,$value);
      if ($pos!==false){
        return true;	
     }	
     
	}

return false;
return;
$res1 = json_decode($res1); 
if ($res1->code==0){
	
	$iplimitarr=explode("|",$iplimit);
    
	foreach ($iplimitarr as $value){

	  $pos = strpos($res1->data->country,$value);
      if ($pos!==false){
        return true;	
     }	
     
    
  
	  $pos = strpos($res1->data->region,$value);
	  
	  if ($pos!==false){
        return true;	
     }
	  
	 
		$pos = strpos($res1->data->city,$value);
		if ($pos!==false){
           return true;
		} else {
			continue;
		}
	} 

} else
{
	return false;
}


return false;

}


 