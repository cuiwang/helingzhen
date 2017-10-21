<?php
global $_GPC;
global $_W;
load()->func("file");
if ($_POST) {
    if($_GPC['op']=="member_l"){
        echo 'asdf';
        var_dump($_POST);
    }
    $data = array('uniacid' => $this->_weid, 'is_notice' => $_GPC['is_notice'], 'tpluser' => $_GPC['tpluser'], 'istplnotice' => $_GPC['istplnotice'], 'xindan' => $_GPC['xindan'], 'ddfk' => $_GPC['ddfk'], 'jdyd' => $_GPC['jdyd'], 'gtuifang' => $_GPC['gtuifang'], 'tuid' => $_GPC['tuid'], 'tkzhi' => $_GPC['tkzhi'], 'is_mihua_mall' => $_GPC['is_mihua_mall'], 'is_daidian' => $_GPC['is_daidian']);
    $id = $_GPC['id'];
    $hotel_member=array('is_notice' => $_GPC['is_notice'], 'tpluser' => $_GPC['tpluser'], 'istplnotice' => $_GPC['istplnotice']);
    if ($id) {
        $where['id'] = $id;
        $where['uniacid'] = $this->_weid;
        $temp = pdo_update($this->setting, $data, $where);
        if ($temp === false) {
            message('设置失败!', '', 'error');
            return;
        }
        message("状态设置成功！", referer(), "success");
        return;
    }
    $result = pdo_insert($this->setting, $data);
    if ($result) {
        message('状态设置成功！', referer(), 'success');
        return;
    }
    message("设置失败!", '', "error");
    return;
}

$setting = pdo_fetch('SELECT * FROM ' . tablename($this->setting) . ' WHERE uniacid = ' . $this->_weid);
include $this->template('settings');

?>