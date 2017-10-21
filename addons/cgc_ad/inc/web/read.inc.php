<?php
   	global $_W, $_GPC;  
   	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   	$uniacid=$_W["uniacid"];
   	$advid=$_GPC['advid'];
   	$quan_id = $_GPC['quan_id'];
   	$cgc_ad_read = new cgc_ad_read();
   	if ($op=='display') { 		
   		
    
    
    $adv = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_adv') . " WHERE weid=" . $uniacid . " AND id=$advid");
    
    $pindex = max(1, intval($_GPC['page']));

	$psize = 20;
    $start = ($pindex - 1) * $psize;
    $con="weid=$uniacid AND advid=$advid AND quan_id=$quan_id and share_nickname like '%{$_GPC['share_nickname']}%' and share_openid like '%{$_GPC['share_openid']}%'  ORDER BY `id` DESC LIMIT {$start},{$psize}";
        $list = $cgc_ad_read->getByConAll($con);
    

	$total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('cgc_ad_read') . "  WHERE $con ");

	$pager = pagination($total, $pindex, $psize);
  	}else if ($op=='deleteAll') {
	  $cgc_ad_read->deleteAll(" and quan_id = $quan_id and advid=$advid");
	  $ret3=pdo_update("cgc_ad_adv",array('read_numed'=>0),array('id'=>$advid));	
	  message('删除成功！',referer(), 'success');
	}
	include $this->template('web/read');
	 
