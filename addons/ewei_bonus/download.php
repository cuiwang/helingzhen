<?php
	
/**
 * 合体红包
 *
 * @author ewei qq:22185157
 * @url 
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}

  $params = array(':rid' => $rid);
  $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_bonus_fans') . " WHERE rid = :rid " . $where . " ORDER BY createtime asc ", $params);
  foreach ($list as &$row) {
	if($row['status'] == 0){
		$row['status']='未提现';
	}elseif($row['status'] == 1){
		$row['status']='已提现';
	}
}
$tableheader = array('ID', '微信码','用户', '地区','初始金额' ,'合体金额', '提现金额','红包总额' , '合体次数', '参与时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['id'] . "\t ,";
	 $html .= $value['openid'] . "\t ,";	
	$html .= $value['nickname'] . "\t ,";	
        $html .= $value['area'] . "\t ,";	
        $html .= $value['points_start'] . "\t ,";	
        $html .= $value['points_help'] . "\t ,";	
        $html .= $value['points_withdraw'] . "\t ,";	
        $html .= $value['points_total'] . "\t ,";	
        $html .= $value['helps'] . "\t ,";	
        $html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,\n";	
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部用户数据.csv");

echo $html;
exit();
