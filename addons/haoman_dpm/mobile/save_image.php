<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];

load()->func('file');
$rid = intval($_GPC['rid']);
$mid = $_GPC['mid'];

$data = $this->downimg($mid);


//$url ="../addons/haoman_dpm/";
//$filenames=$url."sign.txt";
//$from_user =$mid;
//$nickname =$data;
//$avatar='12345';
//$handle=fopen($filenames,"a+");
//$avatar =empty($avatar)?"../addons/haoman_dpm/common/item2.jpg":$avatar;
//$str=fwrite($handle,$from_user."|".$nickname."|".$avatar."|".date('Y/m/d H:i',time())."\n");
//
//fclose($handle);

$snid = $this->sn();
$filename ="images/$uniacid/haoman_dpm_".$snid.'.jpg';
file_write($filename, $data);
echo '{"src":"'.tomedia($filename).'","v":"'.$filename.'"}';