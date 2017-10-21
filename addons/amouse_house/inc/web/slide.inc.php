<?php


global $_W, $_GPC;
$weid= $_W['uniacid'];
load()->func('tpl');
$op= $_GPC['op'] ? $_GPC['op'] : 'display';
if($op=='display'){
    if(checksubmit('submit')){
        if (!empty($_GPC['slide-new'])) {
            foreach ($_GPC['slide-new'] as $index => $row) {
                if (empty($row)) {
                    continue;
                }
                $data = array(
                    'weid' => $weid,
                    'slide' => $_GPC['slide-new'][$index],
                    'url' => $_GPC['url-new'][$index],
                    'listorder' => $_GPC['listorder-new'][$index],
                );
                pdo_insert('amouse_house_slide', $data);
            }
        }
        if (!empty($_GPC['attachment'])) {
            foreach ($_GPC['attachment'] as $index => $row) {
                if (empty($row)) {
                    continue;
                }
                $data = array(
                    'weid' => $weid,
                    'slide' => $_GPC['attachment'][$index],
                    'url' => $_GPC['url'][$index],
                    'listorder' => $_GPC['listorder'][$index],
                    'isshow' => $_GPC['isshow'][$index],
                );
                pdo_update('amouse_house_slide', $data, array('id' => $index));
            }
        }
        message('幻灯片更新成功！', $this->createWebUrl('slide'));
    }
}
if($op=='delete'){
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item = pdo_fetch("SELECT * FROM ".tablename('amouse_house_slide')." WHERE id = :id",array(':id'=>$id));
        if (empty($item)) {
            message('图片不存在或是已经被删除！');
        }
        pdo_delete('amouse_house_slide', array('id' => $item['id']));
    }else{
        $item['attachment'] = $_GPC['attachment'];
    }
    message('删除成功！', $this->createWebUrl('slide', array('op' => 'display', 'name' => 'amouse_house')), 'success');
}
$photos = pdo_fetchall("SELECT * FROM " . tablename('amouse_house_slide') . " WHERE weid = :weid ORDER BY listorder DESC", array(':weid' => $weid));
include $this->template('web/slide');

?>
