<?php
global $_GPC, $_W;
$merchant = $this->checkmergentauth();
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('cygoodssale_order')." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status >= 4 AND fenxiaoprice > 0 AND shareopenid != ''");
$allpage = ceil($total/10)+1;
$page = intval($_GPC["page"]);
$pindex = max(1, $page);
$psize = 10;
$yongjinlist = pdo_fetchall("SELECT * FROM ".tablename('cygoodssale_order')." WHERE merchant_id = {$merchant['id']} AND weid = {$_W['uniacid']}  AND status >= 4 AND fenxiaoprice > 0 AND shareopenid != '' ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
$isajax = intval($_GPC['isajax']);
if($isajax == 1){
	$html = '';
	foreach($yongjinlist as $k=>$v){
		$html .= '<div class="item">
						<div class="iconfont">&#xe695;</div>
						<div class="msg">
							<div class="ordersn textellipsis1">'.date('Y-m-d H:i:s',$v['createtime']).'</div>
							<div class="status">订单号：'.$v['ordersn'].'</div>
						</div>
						<div class="yongjin text-c">¥'.$v['fenxiaoprice'].'</div>
					</div>';
	}
	echo $html;
	exit;
}else{
	include $this->template('merchantyongjin');
}
?>