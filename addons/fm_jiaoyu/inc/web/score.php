<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action1           = 'score';
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
    $score    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'score' And schoolid = {$schoolid} ORDER BY sid ASC, ssort DESC");
    foreach($score as $index => $row){
        if(!empty($row['parentid'])){
            $children[$row['parentid']][] = $row;
            unset($score[$index]);
        }
    }
}elseif($operation == 'post'){
    $parentid = intval($_GPC['parentid']);
    $sid      = intval($_GPC['sid']);
    if(!empty($sid)){
        $score = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    }else{
        $score = array(
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
            'sname'    => $_GPC['catename'],
            'ssort'    => intval($_GPC['ssort']),
            'type'     => 'score',
            'parentid' => intval($parentid),
        );


        if(!empty($sid)){
            unset($data['parentid']);
            pdo_update($this->table_classify, $data, array('sid' => $sid));
        }else{
            pdo_insert($this->table_classify, $data);
            $sid = pdo_insertid();
        }
        $this->imessage('更新成绩成功！', referer(), 'success');
    }
}elseif($operation == 'delete'){
    $sid   = intval($_GPC['sid']);
    $score = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    if(empty($score)){
        $this->imessage('抱歉，成绩不存在或是已经被删除！', referer(), 'error');
    }
    pdo_delete($this->table_classify, array('sid' => $sid), 'OR');
    $this->imessage('成绩删除成功！', referer(), 'success');
}
include $this->template('web/score');
?>