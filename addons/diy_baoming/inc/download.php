<?php
	
function download_user_csv($list,$activity_type){

  global $_GPC,$_W;
  
  $share_status=array(0=>"未分享",1=>"已分享");
  $zj_status=array(0=>"未中奖",1=>"已中奖");
  $zf_status=array(0=>"未支付",1=>"已支付");
  $hx_status=array(0=>"未核销",1=>"已核销");
      					 
foreach ($list as &$row) {
  $row['share_status']=$share_status[$row['share_status']];
  $row['zj_status']=$zj_status[$row['zj_status']];
  $row['zf_status']=$zf_status[$row['is_pay']];
   $row['hx_status']=$hx_status[$row['hx_status']];
}

/*$html = "\xEF\xBB\xBF";*/

if($activity_type=='2'){
	$tableheader = array('用户id','手机号','昵称','姓名','微信号','地址',
'分享状态','中奖状态', '抽奖码','头像','支付状态','核销状态','创建时间');
}
else{
	$tableheader = array('用户id','手机号','昵称','姓名','微信号','地址',
'分享状态','中奖状态', '抽奖码','头像','核销状态','创建时间');
}

foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}


$html .= "\n";

foreach ($list as $index=>$value) {
	$html .= $value['openid'] . ",\t";
	$html .= $value['tel'] . ",\t";
	$html .= $value['nickname'] . ",\t";
    $html .= $value['realname'] . ",\t";	
	$html .= $value['wechat_no'] . ",\t";	
	$html .= $value['addr'] . ",\t";	
	$html .= $value['share_status'] . ",\t";	
	$html .= $value['zj_status'] . ",\t";	
	$html .= $value['cj_code'] . ",\t";	
	$html .= $value['headimgurl'] . ",\t";	
	if($activity_type=='2'){
		$html .= $value['zf_status'] . ",\t";	
	}
	$html .= $value['hx_status'] . ",\t";
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=用户数据.csv");

echo $html;
exit();	
}

function download_user_example($list){
  global $_GPC,$_W;  


$tableheader = array('用户id','用户名','头像');
/*$html = "\xEF\xBB\xBF";*/

foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}


$html .= "\n";

foreach ($list as $index=>$value) {
	$html .= $value['openid'] . ",\t";

	$html .= str_replace(array("'"), "", $value['nickname']) . ",\t";
	
	
	//$html .=iconv('utf-8','gb2312',$value['nickname']) . ",\t";
// $html .=iconv('utf-8','gb2312',$value['nickname']) . ",\t";
	$html .= $value['headimgurl'] . ",\n";	


}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=用户例子数据.csv");

echo $html;
exit();	
}


function down_file($filepath,$filename)
{
if(!file_exists($filepath))
{
echo "backup error ,download file no exist";
exit();
}
ob_end_clean();
header('Content-Type: application/download');
header("Content-type: text/csv");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header("Content-Encoding: binary");
  header("Content-Length:".filesize($filepath));
header("Pragma: no-cache");
header("Expires: 0");
readfile($filepath);
$e=ob_get_contents();
ob_end_clean();
}





