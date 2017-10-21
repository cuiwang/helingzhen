<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;

$list = pdo_fetchall("SELECT a.*, b.name, b.daihao FROM ".tablename('fm_autogroup_members')." as a left join ".tablename('fm_autogroup_grouplist')." as b on a.gname=b.daihao  WHERE a.uniacid=:uniacid  ORDER BY a.id DESC" , array(':uniacid'=>$_W['uniacid']));

$tableheader = array('ID', '用户昵称', '用户组', '微信号', '手机号', '用户发送( 关键字 )次数', '关注状态', '是否取消过', '头像', '加入时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$ft = $this->_getfscount($value['gname'],$value['from_user']);
	$html .= $value['id'] . "\t ,";
	$html .= $value['nickname'] . "\t ,";	
	$html .= (!empty($value['name']) ? $value['name'].'('.$value['daihao'].')'  :  $value['gname']) . "\t ,";	
	$html .= $value['from_user'] . "\t ,";	
	$html .= $value['mobile'] . "\t ,";	
	$html .= $ft['total'] . "\t ,";	
	$html .= ($value['follow'] == 1 ? '正常关注'  : '取消关注') . "\t ,";	
	$html .= ($value['followtrue'] == 1 ? '是'  : '否') . "\t ,";	
	$html .= $value['avatar'] . "\t ,";	
	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
	$html .= "\n";
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=用户数据.csv");

echo $html;
exit();
