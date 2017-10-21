<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
include MODULE_ROOT . '/inc/common.php';
$uniacid = $_W["uniacid"];
$table_name = 'record';
$act = trim($_GPC['act']);
$allow_acts = array(
    'lists', 'delete'
);
if (!in_array($act, $allow_acts)) {
    $act = 'lists';
}
/**
 * 列表
 */
if ($act == 'lists') {
    $where = ' AND openid != "" AND status = 1';
    $where .= !empty($_GPC['module_name']) ? " AND module_name = '" . addslashes($_GPC['module_name']) . "'" : "";
    $where .= !empty($_GPC['title']) ? " AND title LIKE '%" . addslashes($_GPC['title']) . "%'" : "";
    if ($where != '') {
        $_GPC['page'] = 1;
    }
    $result = getPageList($table_name, $_GPC['page'], $where);
    $module_list = array();
    $res = getAllData($table_name, ' AND module_name != "" AND status = 1', 'id DESC', "DISTINCT module_name");
    foreach ($res AS $row) {
        $module_list[$row['module_name']] = $row['module_name'];
    }
}
/**
 * 删除数据
 */
else if($act == 'delete') {
    $id = intval($_GPC['id']);
    fr_delete($table_name, array('id' => $id, 'uniacid' => $uniacid));
    message('删除数据成功!', $this->createWebUrl('record', array('act' => 'lists')), 'success');
    exit();
}
include $this->template("web/record_lists");