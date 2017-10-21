<?php
global $_GPC, $_W;

load()->model('reply');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';

if (!empty($_GPC['keyword'])) {
	$sql .= ' and `name` LIKE :keyword';
	$params[':keyword'] = "%{$_GPC['keyword']}%";
}
$list = reply_search($sql, $params, $pindex, $psize, $total);
$pager = pagination($total, $pindex, $psize);

if (!empty($list)) {
	foreach ($list as &$item) {
		$condition = "`rid`={$item['id']}";
		$item['keyword'] = reply_keywords_search($condition);
		$dpm = pdo_fetch("select fansnum, viewnum from " . tablename('haoman_dpm_reply') . " where rid = :rid ", array(':rid' => $item['id']));
		$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $_W['uniacid'],':rid'=>$item['id']));
		$item['fansnum'] = $dpm['fansnum'];
		$item['viewnum'] = $totaldata;
		
	}
}

if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){ 
    $isinti = 0;
    if(pdo_tableexists('haoman_dpm_ds_reply')){
        $isinti = 1;
    }
    include $this->template('custom_ds_manage');

}else if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
    $isinti = 0;
    if(pdo_tableexists('haoman_dpm_znl_reply')){
        $isinti = 1;
    }
    include $this->template('custom_znl_manage');

}else{
    include $this->template('manage');
}