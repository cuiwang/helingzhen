<?php
	
/**
 * 合体红包
 *
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
$list = pdo_fetchall("SELECT r.*,f.openid,f.account,f.bank,f.paytype,f.mobile,f.realname FROM " . tablename('ewei_bonus_fans_record') . " r left join " . tablename('ewei_bonus_fans') . " f on f.openid = r.openid WHERE r.rid = :rid and r.sim=0 " . $where . " ORDER BY r.createtime asc " . $limit, $params);
  	 
foreach ($list as &$row) {
	if($row['status'] == 0){
		$row['status']='未提现';
	}elseif($row['status'] == 1){
		$row['status']='已提现';
	} 
}
$tableheader = array('ID','微信码', '昵称','真实姓名','手机号','提现方式','提现账号或卡号', '提现金额', '申请时间', '提现时间','状态');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
        $html .= $value['id'] . "\t ,";
        $html .= $value['openid'] . "\t ,";	
        $html .= $value['nickname'] . "\t ,";	
        $html .= $value['realname']. "\t ,";	
        $html .= $value['mobile']. "\t ,";	
        $html .= ($value['paytype']==1?'支付宝':'银行卡') . "\t ,";	
        $html .= $value['account']." ".(!empty($value['bank'])?'开户行:':'').$value['bank'] . "\t ,";	
        $html .= $value['points'] . "\t ,";
        $html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\t ,";
        $html .= ($value['consumetime'] == 0 ? '' : date('Y-m-d H:i',$value['consumetime'])) . "\t ,";
        $html .= $value['status'] . "\t ,\n";	
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部提现记录.csv");

echo $html;
exit();
