<?php
global $_GPC, $_W;

$url ="../addons/haoman_dpm/";
$file=$url."sign.txt";
$txt = '';
if(file_put_contents($file, $txt) !== FALSE)
{
    message('清除成功！', '', 'success');
}
else
{
    message('清除失败！', '', 'error');
}