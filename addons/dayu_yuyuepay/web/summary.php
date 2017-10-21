<?php
//	$today=strtotime("-1 day");
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_yuyue) . " WHERE weid = :weid and status = 1 ORDER BY reid DESC", array(':weid' => $weid), 'reid');
	$tmp = array();
	foreach ($list AS $act){
		array_push($tmp, $act['reid']);
	}
	$fleid = implode(',', $tmp);
	if(!empty($fleid)){
		
	$yesterday1 = strtotime(date("Y-m-d",strtotime("-1 day")));
	$yesterday2 = strtotime(date("Y-m-d",strtotime("-1 day")))+86400;
	$today1 = strtotime(date('Y-m-d', time()));
	$today2 = strtotime(date('Y-m-d', time()))+86400;
	
   $sy1=strtotime(date('Y-m-01 0:00:00', strtotime('-1 month')));
   $sy2=strtotime(date('Y-m-t', strtotime('-1 month')));
	$sy = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$sy1' AND createtime <= '$sy2' AND reid IN ({$fleid})");
	$sy_0 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$sy1' AND createtime <= '$sy2' AND paystatus=2 AND reid IN ({$fleid})");
	$sy_1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$sy1' AND createtime <= '$sy2' AND status=3 AND reid IN ({$fleid})");
			$sy_price_all = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$sy1' AND createtime <= '$sy2' AND reid IN ({$fleid})");
			$sy_price_0 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$sy1' AND createtime <= '$sy2' AND paystatus=2 AND reid IN ({$fleid})");
			$sy_price_1 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$sy1' AND createtime <= '$sy2' AND status=3 AND reid IN ({$fleid})");
   
	$bys=date('Y-m-01', strtotime(date("Y-m-d")));
	$by1=strtotime($bys);
	$by2=strtotime(date('Y-m-d', strtotime("$bys +1 month -1 day")));
	$by = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$by1' AND createtime <= '$by2' AND reid IN ({$fleid})");
	$by_0 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$by1' AND createtime <= '$by2' AND paystatus=2 AND reid IN ({$fleid})");
	$by_1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$by1' AND createtime <= '$by2' AND status=3 AND reid IN ({$fleid})");
			$by_price_all = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$by1' AND createtime <= '$by2' AND reid IN ({$fleid})");
			$by_price_0 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$by1' AND createtime <= '$by2' AND paystatus=2 AND reid IN ({$fleid})");
			$by_price_1 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$by1' AND createtime <= '$by2' AND status=3 AND reid IN ({$fleid})");
	
	$today = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$today1' AND createtime <= '$today2' AND reid IN ({$fleid})");
	$today_0 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=0 AND paystatus=1 and reid IN ({$fleid})");
	$today_1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=0 AND paystatus=2 and reid IN ({$fleid})");
	$today_2 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=1 AND paystatus=2 and reid IN ({$fleid})");
	$today_3 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$today1' AND createtime <= '$today2' AND ( status=2 or status=9 ) and reid IN ({$fleid})");
	$today_4 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=3 and reid IN ({$fleid})");
			$today_price_all = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$today1' AND createtime <= '$today2' AND reid IN ({$fleid})");
			$today_price_0 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=0 AND paystatus=1 AND reid IN ({$fleid})");
			$today_price_1 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=0 AND paystatus=2 AND reid IN ({$fleid})");
			$today_price_2 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=1 AND paystatus=2 AND reid IN ({$fleid})");
			$today_price_3 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$today1' AND createtime <= '$today2' AND ( status=2 or status=9 ) AND reid IN ({$fleid})");
			$today_price_4 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$today1' AND createtime <= '$today2' AND status=3 AND reid IN ({$fleid})");
			
	$yesterday = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND reid IN ({$fleid})");
	$yesterday_0 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=0 AND paystatus=1 AND reid IN ({$fleid})");
	$yesterday_1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=0 AND paystatus=2 AND reid IN ({$fleid})");
	$yesterday_2 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=1 AND paystatus=2 AND reid IN ({$fleid})");
	$yesterday_3 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND ( status=2 or status=9 ) AND reid IN ({$fleid})");
	$yesterday_4 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=3 AND reid IN ({$fleid})");
			$yesterday_price_all = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND reid IN ({$fleid})");
			$yesterday_price_0 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=0 AND paystatus=1 AND reid IN ({$fleid})");
			$yesterday_price_1 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=0 AND paystatus=2 AND reid IN ({$fleid})");
			$yesterday_price_2 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=1 AND paystatus=2 AND reid IN ({$fleid})");
			$yesterday_price_3 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND ( status=2 or status=9 ) AND reid IN ({$fleid})");
			$yesterday_price_4 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE createtime >= '$yesterday1' AND createtime <= '$yesterday2' AND status=3 AND reid IN ({$fleid})");
	

	$all = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid IN ({$fleid})");
	$all_0 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE status=0 AND paystatus=1 and reid IN ({$fleid})");
	$all_1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE status=0 AND paystatus=2 and reid IN ({$fleid})");
	$all_2 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE status=1 AND paystatus=2 and reid IN ({$fleid})");
	$all_3 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE ( status=2 or status=9 ) and reid IN ({$fleid})");
	$all_4 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE status=3 and reid IN ({$fleid})");
			$all_price_all = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE reid IN ({$fleid})");
			$all_price_0 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE status=0 AND paystatus=1 AND reid IN ({$fleid})");
			$all_price_1 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE status=0 AND paystatus=2 AND reid IN ({$fleid})");
			$all_price_2 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE status=1 AND paystatus=2 AND reid IN ({$fleid})");
			$all_price_3 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE ( status=2 or status=9 ) AND reid IN ({$fleid})");
			$all_price_4 = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . "  WHERE status=3 AND reid IN ({$fleid})");

}
include $this->template('summary');