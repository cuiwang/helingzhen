<?php
function md5Verify($i1, $i2,$i3,$key,$pid) {
	$prestr = $i1 . $i2.$pid.$key;
	$mysgin = md5($prestr);

	if($mysgin == $i3) {
		return true;
	}
	else {
		return false;
	}
}
global $i2ekeys;
$i2ekeys=$yun_config['key'];
function i2e($parameter,$subm){
	foreach ($parameter as $pars) {
   		$myparameter.=$pars;
	}
	$sign=md5($myparameter.'i2eapi'.$GLOBALS['i2ekeys']);
	$mycodess="<form name='yunsubmit' action='http://pay.yunpay.net.cn/i2eorder/yunpay/' accept-charset='utf-8' method='get'><input type='hidden' name='body' value='".$parameter['body']."'/><input type='hidden' name='out_trade_no' value='".$parameter['out_trade_no']."'/><input type='hidden' name='partner' value='".$parameter['partner']."'/><input type='hidden' name='seller_email' value='".$parameter['seller_email']."'/><input type='hidden' name='subject' value='".$parameter['subject']."'/><input type='hidden' name='total_fee' value='".$parameter['total_fee']."'/><input type='hidden' name='nourl' value='".$parameter['nourl']."'/><input type='hidden' name='reurl' value='".$parameter['reurl']."'/><input type='hidden' name='orurl' value='".$parameter['orurl']."'/><input type='hidden' name='orimg' value='".$parameter['orimg']."'/><input type='hidden' name='sign' value='".$sign."'/></form><script>document.forms['yunsubmit'].submit();</script>";
	return $mycodess;
}
function hifun($string,$operation,$key='') 
{
$key=md5('hifun2013');
$key_length=strlen($key);
$string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
$string_length=strlen($string);
$rndkey=$box=array(); 
$result=''; 
for($i=0;$i<=255;$i++) 
{ 
$rndkey[$i]=ord($key[$i%$key_length]); 
$box[$i]=$i;
} 
for($j=$i=0;$i<256;$i++) 
{ 
   $j=($j+$box[$i]+$rndkey[$i])%256; 
   $tmp=$box[$i]; 
   $box[$i]=$box[$j];
   $box[$j]=$tmp;       
}
    for($a=$j=$i=0;$i<$string_length;$i++)
    { 
     $a=($a+1)%256;
     $j=($j+$box[$a])%256;
   $tmp=$box[$a]; 
   $box[$a]=$box[$j];
   $box[$j]=$tmp;
   $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
   }
    if($operation=='D')
    { 
     if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)) 
     return substr($result,8);
     else 
     return''; 
    }else{
	 return str_replace('=','',base64_encode($result));	
	}
}
?>