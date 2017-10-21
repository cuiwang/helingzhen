<?php

/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action1           = 'jsfz';
$this1             = 'no1';
$action            = 'semester';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){
    if(!empty($_GPC['ssort'])){
        foreach($_GPC['ssort'] as $sid => $ssort){
            pdo_update($this->table_classify, array('ssort' => $ssort), array('sid' => $sid));
        }
        $this->imessage('批量更新排序成功', referer(), 'success');
    }
    $children = array();
    $jsfz     = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'jsfz' And schoolid = {$schoolid} ORDER BY sid ASC, ssort DESC");
    foreach($jsfz as $index => $row){
        if(!empty($row['parentid'])){
            $children[$row['parentid']][] = $row;
            unset($jsfz[$index]);
        }
    }
}elseif($operation == 'post'){
    $parentid = intval($_GPC['parentid']);
    $sid      = intval($_GPC['sid']);
    if(!empty($sid)){
        $jsfz = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    }else{
        $jsfz = array(
            'ssort' => 0,
        );
    }

    if(checksubmit('submit')){
        if(empty($_GPC['catename'])){
            $this->imessage('抱歉，请输入名称！', referer(), 'error');
        }

        $data = array(
            'weid'     => $weid,
            'schoolid' => $_GPC['schoolid'],
            'sname'    => trim($_GPC['catename']),
            'pname'    => trim($_GPC['pname']),
            'ssort'    => intval($_GPC['ssort']),
            'type'     => 'jsfz',
            'parentid' => intval($parentid),
        );

        if(empty($data['pname']))
            $this->imessage('抱歉，请输入名称！', referer(), 'error');
        if(!empty($sid)){
            unset($data['parentid']);
            pdo_update($this->table_classify, $data, array('sid' => $sid));
        }else{
            pdo_insert($this->table_classify, $data);
            $sid = pdo_insertid();
        }
        $this->imessage('更新分组成功！', referer(), 'success');
    }
}elseif($operation == 'delete'){
    $sid  = intval($_GPC['sid']);
    $jsfz = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    if(empty($jsfz)){
        $this->imessage('抱歉，分组不存在或是已经被删除！', referer(), 'error');
    }
    pdo_delete($this->table_classify, array('sid' => $sid), 'OR');
    $this->imessage('分组删除成功！', referer(), 'success');
}
include $this->template('web/jsfz');
?>