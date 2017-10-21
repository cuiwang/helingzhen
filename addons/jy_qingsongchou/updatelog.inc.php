<?php
//更新日志
global $_W,$_GPC;
$return = array();
$return['status'] = 0;
if(empty($_W['isfounder'])) {
  $return['message'] = '您没有相应操作权限';
  die(json_encode($return));
}
$return['message'] = '黑锐提示：暂不支持在线更新！';
  die(json_encode($return));
