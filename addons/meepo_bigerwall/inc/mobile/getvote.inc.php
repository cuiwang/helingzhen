<?php
global $_GPC, $_W;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$sql='SELECT *  FROM '.tablename('weixin_vote').' WHERE id!=:id and weid=:weid AND rid=:rid ORDER BY res DESC';
$para2 = array(':id'=>0,':weid'=>$weid,':rid'=>$rid);
$all = array();
$statList = array();
$statList = pdo_fetchall($sql,$para2);
$total = pdo_fetchcolumn("SELECT sum(res) FROM " . tablename('weixin_vote') . " where weid = '{$_W['uniacid']}' AND rid= '{$rid}'");
if(is_array($statList) && !empty($statList)){
	foreach($statList as &$row){
		if($total > 0){
			 $row['per'] =  sprintf("%.2f", ($row['res']/$total)*100 );
		}else{
			 $row['per'] = 0;
		}
		$row['num'] = $row['res'];
		$row['content'] = $row['name'];
		$row['vote_img'] = $_W['attachurl'].$row['vote_img'];
	}
	unset($row);
}
$all['statList']  = $statList;
$all['total'] = $total;
die(json_encode($all)); 