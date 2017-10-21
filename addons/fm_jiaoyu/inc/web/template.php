<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action1           = 'template';
$this1             = 'no1';
$action            = 'semester';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){
    if(checksubmit('submit')){
        $data = array(
            'style1'    => trim($_GPC['style1']),
            'style2'    => trim($_GPC['style2']),
            'style3'    => trim($_GPC['style3']),
            'userstyle' => trim($_GPC['userstyle']),
            'bjqstyle'  => trim($_GPC['bjqstyle']),
        );

        pdo_update($this->table_index, $data, array('id' => $schoolid, 'weid' => $weid));
        $this->imessage('修改前端模板成功!', referer(), 'success');
    }
}elseif($operation == 'display1'){
    $icons = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 1 ORDER by ssort ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
    if(checksubmit('submit')){
        $titles         = $_GPC['iconname'];
        $url            = $_GPC['url'];
        $icon           = $_GPC['iconurl'];
        $ssort          = $_GPC['ssort'];
        $filter         = array();
        $filter['weid'] = $_W['uniacid'];
        foreach($titles as $key => $t){
            $id           = intval($key);
            $filter['id'] = intval($id);
            if(!empty($t)){
                $rec = array(
                    'name'  => $t,
                    'icon'  => trim($icon[$id]),
                    'url'   => trim($url[$id]),
                    'ssort' => intval($ssort[$id])
                );
                pdo_update($this->table_icon, $rec, $filter);
            }
        }
        $this->imessage('修改成功!', referer(), 'success');
    }
}elseif($operation == 'display2'){
    $icons1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 3 ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
    $icons2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 4 ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
    $icons3 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = 5 ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
    $lastid = pdo_fetch("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid ORDER by id DESC LIMIT 0,1", array(':weid' => $weid, ':schoolid' => $schoolid));
    if(checksubmit('submit')){
        $type               = $_GPC['type'];//类型 1覆盖 2新建
        $btnname            = $_GPC['btnname'];//按钮名称
        $mfbzs              = $_GPC['mfbzs'];//魔方小字
        $bzcolor            = $_GPC['bzcolor'];//魔方按钮颜色
        $iconpics           = $_GPC['iconpics']; //图标地址
        $url                = $_GPC['url']; //链接地址
        $place              = $_GPC['place'];//位置 3顶部 4魔方 5底部
        $filter             = array();
        $filter['weid']     = $_W['uniacid'];
        $filter['schoolid'] = $_W['schoolid'];
        foreach($type as $key => $t){
            $id           = intval($key);
            $filter['id'] = intval($id);
            if($t == 1){
                $rec = array(
                    'name'   => trim($btnname[$id]),
                    'beizhu' => trim($mfbzs[$id]),
                    'color'  => trim($bzcolor[$id]),
                    'icon'   => trim($iconpics[$id]),
                    'url'    => trim($url[$id]),
                    'place'  => intval($place[$id])
                );
                pdo_update($this->table_icon, $rec, array('id' => $id));
            }else{
                $data = array(
                    'weid'     => trim($_GPC['weid']),
                    'schoolid' => trim($_GPC['schoolid']),
                    'name'     => trim($btnname[$id]),
                    'beizhu'   => trim($mfbzs[$id]),
                    'color'    => trim($bzcolor[$id]),
                    'icon'     => trim($iconpics[$id]),
                    'url'      => trim($url[$id]),
                    'place'    => intval($place[$id]),
                    'status'   => 1,
                );
                pdo_insert($this->table_icon, $data);
            }
        }
        $this->imessage('操作成功!', referer(), 'success');
    }
}elseif($operation == 'change'){
    $status = trim($_GPC['status']);
    $id     = trim($_GPC['id']);
    $data   = array('status' => $status);
    pdo_update($this->table_icon, $data, array('id' => $id));
}elseif($operation == 'delclass'){
    $id   = trim($_GPC['id']);
    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And id = :id", array(':weid' => $weid, ':schoolid' => $_GPC['schoolid'], ':id' => $id));
    if($item){
        pdo_delete($this->table_icon, array('id' => $id));
        $message         = "删除操作成功！";
        $data ['result'] = true;
        $data ['msg']    = $message;
    }else{
        $message         = "删除失败请重刷新页面重试!";
        $data ['result'] = false;
        $data ['msg']    = $message;
    }
    die (json_encode($data));
}
include $this->template('web/template');
?>