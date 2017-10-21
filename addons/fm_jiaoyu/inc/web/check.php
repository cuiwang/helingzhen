<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'check';
$this1             = 'no5';
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$logo      = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
if(!empty($_W['setting']['remote']['type'])){
    $urls = $_W['attachurl'];
}else{
    $urls = $_W['siteroot'] . 'attachment/';
}
if($operation == 'display'){
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ORDER BY id DESC");
    foreach($list as $key => $row){
        $item                 = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE id = '{$row['id']}'");
        $banner               = unserialize($item['banner']);
        $list[$key]['isflow'] = $banner['isflow'];
        $list[$key]['video']  = $banner['video'];
    }
}elseif($operation == 'post'){
    $id     = intval($_GPC['id']);
    $item   = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE id = {$id} ");
    $banner = unserialize($item['banner']);
    if(checksubmit('submit')){
        $data = array(
            'weid'       => $weid,
            'schoolid'   => $schoolid,
            'name'       => $_GPC['name'],
            'macname'    => intval($_GPC['macname']),
            'macid'      => trim($_GPC['macid']),
            'type'       => intval($_GPC['type']),
            'createtime' => time(),
        );
        if($_GPC['macname'] == 2){
            if(empty($_GPC['pop']) || empty($_GPC['pic1']) || empty($_GPC['pic2']) || empty($_GPC['pic3']) || empty($_GPC['pic4'])){
                $this->imessage('广告语、图片1-4，不可为空！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
            }
            $temp = array(
                'isflow'   => 1,
                'pop'      => $_GPC['pop'],
                'video'    => $_GPC['video'],
                'pic1'     => $_GPC['pic1'],
                'pic2'     => $_GPC['pic2'],
                'pic3'     => $_GPC['pic3'],
                'pic4'     => $_GPC['pic4'],
                'VOICEPRE' => $_GPC['VOICEPRE'],
            );
        }
        if($_GPC['macname'] == 3){
            if(empty($_GPC['pop1']) || empty($_GPC['pic11']) || empty($_GPC['pic21']) || empty($_GPC['pic31']) || empty($_GPC['pic41']) || empty($_GPC['VOICEPRE1'])){
                $this->imessage('提示语言、滚动公告、图片1-4，不可为空！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
            }
            $temp = array(
                'isflow'   => 1,
                'pop'      => $_GPC['pop1'],
                'video'    => $_GPC['video1'],
                'pic1'     => $_GPC['pic11'],
                'pic2'     => $_GPC['pic21'],
                'pic3'     => $_GPC['pic31'],
                'pic4'     => $_GPC['pic41'],
                'VOICEPRE' => $_GPC['VOICEPRE1'],
            );
        }
        $data['banner'] = serialize($temp);
        if(!empty($id)){
            pdo_update($this->table_checkmac, $data, array('id' => $id));
        }else{
            pdo_insert($this->table_checkmac, $data);
        }
        $this->imessage('操作成功！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }

}elseif($operation == 'delete'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id  FROM " . tablename($this->table_checkmac) . " WHERE id = {$id} ");
    if(empty($item)){
        $this->imessage('抱歉，不存在或是已经被删除！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
    }
    pdo_delete($this->table_checkmac, array('id' => $id));
    $this->imessage('删除成功！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
}elseif($operation == 'change'){
    $id    = intval($_GPC['id']);
    $is_on = intval($_GPC['is_on']);

    $data = array('is_on' => $is_on);

    pdo_update($this->table_checkmac, $data, array('id' => $id));
}else{
    $this->imessage('请求方式不存在');
}
include $this->template('web/check');
?>