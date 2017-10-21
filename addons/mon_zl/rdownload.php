<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;


$sid = $_GPC['sid'];
$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
$pid = $_GPC['pid'];
$where = '';
$params = array();
$params[':sid'] = $sid;
$status = $_GPC['status'];
if ($_GPC['uid']!='')
{
	$where .= ' AND r.uid =:uid';
	$params[':uid'] = $_GPC['uid'];

}
if (!empty($pid))
{
	$where .= ' AND r.pid =:pid';
	$params[':pid'] = $pid;

}

if (!empty($status))
{
	$where .= ' AND r.status =:status';
	$params[':status'] = $status;
}


$list = pdo_fetchall("SELECT r.*,r.id as rid,u.*  FROM " . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " r left join " . tablename(DBUtil::$TABLE_QMSHAKE_USER) . " u  on r.uid=u.id  WHERE r.sid =:sid " . $where . " ORDER BY r.createtime DESC, r.id DESC " , $params);







$tableheader = array('openID', $this->encode("昵称"),$this->encode("姓名"),$this->encode("手机号"),$this->encode("奖品"),$this->encode("状态"),$this->encode('中奖时间'),$this->encode('兑奖时间' ));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['openid'] . "\t ,";
	 $html .= $this->encode( $value['nickname'] )  . "\t ,";
	$html .= $this->encode( $value['uname'] )  . "\t ,";
	$html .= $this->encode( $value['tel'] )  . "\t ,";
	$html .= $this->encode( $value['pname'] )  . "\t ,";

	if ($value['status'] == 0)
	{
		$html .= $this->encode( "未中奖" )  . "\t ,";
	}

	if ($value['status'] == 1)
	{
		$html .= $this->encode( "已中奖" )  . "\t ,";
	}

	if ($value['status'] == 2)
	{
		$html .= $this->encode( "已兑换" )  . "\t ,";
	}
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\t,";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['djtime'])) . "\n";

}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参与记录数据.xls");

echo $html;
exit();
